<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zebra_Image;
use App\Http\Controllers\CustomAwsController;
use App\Models\OperatorPayments;
use App\Models\User;
use Config;
use Log;
use File;
use Response;

class CommonController extends Controller
{
    public $firebase_api_key;
	public $firebase_url;

	public function __construct()
	{
		$this->firebase_api_key = Config::get('custom_config_file.firebase-api-key');
		$this->firebase_url = Config::get('custom_config_file.fcm-url');
		$this->aws = new CustomAwsController;        
	}

	public function sendPushNotification($fields) {
		if($fields['fcmId'] == 'android'){
            $this->firebase_api_key = Config::get('custom_config_file.firebase-server-key');
        }else{
            $this->firebase_api_key = Config::get('custom_config_file.firebase-api-key');
        }
        unset($fields['fcmId']);
	//$this->firebase_api_key = "AAAARufaT4A:APA91bG8mbBKiNy93lxDDIWgyD4LTdFzRKEZ_PZkHiAYo05kEgz2YzKdNc7jKHe-C03isK_1u53FQpbp3WW1WFX3SxVy1tGMJ_p6k4nhXNFssdfJD4OQhngE5i3TExTlIN0x4IUB7xWC";
		Log::info('fields: ', $fields); Log::warning($this->firebase_api_key); Log::warning($this->firebase_url);
		$header=[
            'Authorization: key='.$this->firebase_api_key,
            'Content-Type: application/json'
        ];

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->firebase_url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode( $fields ),
		  CURLOPT_HTTPHEADER => $header,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			Log::warning( $err);
			return 'error';
		  // echo "cURL Error #:" . $err;
		} else {
			Log::warning( $response);
		  // echo $response;
			return $response;
		}
		return 1;
	}

	//function to check if email is present while creating user
	public function CheckEmail(Request $request)
    {
        $user_email = $request->input('user_email');
        $is_email_exist = User::where('email', 'LIKE', '%' . $user_email . '%')->exists();
        
        if($is_email_exist)
        {
            $isAvailable = FALSE;
        }
        else
        {
            $isAvailable = TRUE;
        }
        return json_encode($isAvailable);
    }

    /*
    public function getPincodes(){
		$data =	PincodesMaster::select('id','pincode')->get()->toArray();
		// sort array by colorname
		$data = array_values(Arr::sort($data, function ($value) {
            return $value['pincode'];
        }));
		if(!empty($data)){
			$response = ['status' => 'success', 'Response' => $data, 'statusCode' => Response::HTTP_OK];
            return response()->json(['response'=> $response]);
		}else{
			$response = ['status' => 'Failed', 'Response' => 'No Pincodes Found','statusCode' => Response::HTTP_BAD_REQUEST];
            return response()->json(['response'=> $response]);
		}
	}
 	*/

 	public function get_timeago($ptime)
	{
		$ptime = strtotime($ptime);
		$estimate_time = time() - $ptime;

		if( $estimate_time < 1 )
		{
			return 'less than 1 second ago';
		}

		$condition = array( 
					12 * 30 * 24 * 60 * 60  =>  'year',
					30 * 24 * 60 * 60       =>  'month',
					24 * 60 * 60            =>  'day',
					60 * 60                 =>  'hour',
					60                      =>  'minute',
					1                       =>  'second'
		);

		foreach( $condition as $secs => $str )
		{
			$d = $estimate_time / $secs;
			if( $d >= 1 )
			{
				$r = round( $d );
				return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
			}
		}
	}

	public function getimageBase64($imagepath){
		if(!empty($imagepath)){

			if(isset($imagepath)){
				$saveAsPath = '/tmp/';
				$filename_array = explode('/', $imagepath);
				$download_url = $filename_array[count($filename_array) - 1];
				$tempFileName = end($filename_array);
				$is_file_exists = File::exists($saveAsPath.$tempFileName);
				if($is_file_exists){
					$filename = $tempFileName;
				}
				else{
					$filename = $this->aws->downloadFromS3($imagepath, $saveAsPath);
				}
				if($filename){
					$path = $saveAsPath.$filename;
					$file = File::get($path);
					$type = File::mimeType($path);
					$response = Response::make($file, 200);
					$response->header("Content-Type", $type);
					$b64image = base64_encode(file_get_contents($path));
				}
				else{
					$b64image = null;
				}
				$imagepath = $b64image;
			}
			return $b64image = $b64image;
		}else{
			return $b64image = null;
		}
	}

	public function generateTransactionID(){
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$string = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 14; $i++) {
			$n = rand(0, $alphaLength);
			$string[] = $alphabet[$n];
		}
		$tranx_id = implode($string);
		$new_tax_id = 'pay_'.$tranx_id;
		$isExists = OperatorPayments::where('op_order_transaction_id', $new_tax_id)->exists();
		if($isExists){
			$this->generateTransactionID();
		}
		else{
			return $new_tax_id;
		}
	}

	public function compressImage($image_path){
		$image = new Zebra_Image();
			$image->auto_handle_exif_orientation = false;
			$image->source_path = $image_path;
			$image->target_path = $image_path;
			$image->jpeg_quality = 100;
			$image->preserve_aspect_ratio = true;
			$image->enlarge_smaller_images = true;
			$image->preserve_time = true;
			$image->handle_exif_orientation_tag = true;
			if (!$image->resize(768, 500, ZEBRA_IMAGE_CROP_CENTER)) {

			    switch ($image->error) {

			        case 1:
			            Log::warning('Source file could not be found!');
			            break;
			        case 2:
			            Log::warning('Source file is not readable!');
			            break;
			        case 3:
			            Log::warning('Could not write target file!');
			            break;
			        case 4:
			            Log::warning('Unsupported source file format!');
			            break;
			        case 5:
			            Log::warning('Unsupported target file format!');
			            break;
			        case 6:
			            Log::warning('GD library version does not support target file format!');
			            break;
			        case 7:
			            Log::warning('GD library is not installed!');
			            break;
			        case 8:
			            Log::warning('"chmod" command is disabled via configuration!');
			            break;
			        case 9:
			            Log::warning('"exif_read_data" function is not available');
			            break;

			    }

			// if no errors
			} else echo 'Success!';
	}
}

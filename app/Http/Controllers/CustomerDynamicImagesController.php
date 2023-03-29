<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerDynamicImages;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Gate;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Validator;
use Config;
use File;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use Carbon\Carbon;

class CustomerDynamicImagesController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public $bucketname;
    public $amazon_s3_url;
    
    public function __construct()
    {
        $this->aws = new CustomAwsController;        
        $this->commonFunction = new CommonController;        
        $this->middleware('auth');
        $this->bucketname = Config::get('custom_config_file.public-bucket');
        $this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
        $this->SERVER_API_KEY="AIzaSyBN_cnv9SV90CDmeNFsMo7pMBaiE7Iux34";
        $this->todays = Carbon::now();

    }
 
    public function createDynamicImg(Request $request){
    	$postData = $request->all();
        if(!empty($postData)){
	    	if($postData['image_type'] == 'DASHBOARD'){
	    		$postimgData = $postData['dash_img_slider'];
	    	}elseif($postData['image_type'] == 'MYLUCKYSINGLE'){
	    		$postimgData = $postData['my_lucky_offer'];
	    	}elseif($postData['image_type'] == 'ACCURAL'){
	    		$postimgData = $postData['accural_img'];
	    	}else{
	    		$postimgData = $postData['mylucky_img_multi_slider'];
	    	}
            $user = Auth::getUser();
            $admin_id = $user->id;
            $admin_email = $user->email;
            $uploadImages = $this->uploadImageToS3($postimgData);
            if(!empty($uploadImages)){
            	$input = array(
                    'admin_id' => $admin_id,
                    'image_name' => $uploadImages,
                    'image_type' => $postData['image_type'],
                    'created_by' => $admin_email,
                );      
                $mitr_data = CustomerDynamicImages::create($input);
                return redirect()->route('customerinformationboard.index')->with('success', 'Image uploaded successfully.');
            }else{
            	return redirect()->route('customerinformationboard.index')->with('error', 'Some thing went wrong try again.');
            }
        }else{
        	Log::warning("empty request:customerinformationboardController");
        	return redirect()->route('customerinformationboard.index')->with('error', 'Some thing went wrong try again.');
        }
    }

    public function edit($id){
		if(!empty($id)){
			//get image
			$getImage = CustomerDynamicImages::select('id','image_name','image_type')->where('id',$id)->get()->first();
			$downloadImageS3 = $this->downloadImageS3($getImage->image_name);
			if($downloadImageS3){
				return view('admin.customerinformationboard.dynamicImages.edit', compact('getImage','downloadImageS3'));
			}else{
				Log::warning("empty request:customerinformationboardController");
        		return redirect()->route('customerinformationboard.index')->with('error', 'Some thing went wrong try again.');
			}
		}
	}

	public function update(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$postimgData = $postData['customer_offer_image'];
			$id = $postData['image_id'];
            $uploadImages = $this->uploadImageToS3($postimgData);
            if(!empty($uploadImages)){
            	$input = array(
                    'image_name' => $uploadImages,
                );      
                $update = CustomerInformationBoard::where('id',$id)->update($input);
                return redirect()->route('customerinformationboard.index')->with('success', 'Image updated successfully.');
            }else{
            	return redirect()->route('customerinformationboard.index')->with('error', 'Some thing went wrong try again.');
            }
        }else{
        	Log::warning("empty request:customerinformationboardController");
        	return redirect()->route('customerinformationboard.index')->with('error', 'Some thing went wrong try again.');
        }
	}

    public function uploadImageToS3($imgData){
    	$imgArray = $imgData;
    	if(!empty($imgArray) && $imgArray != 'null'){
    		$bucketname = Config::get('custom_config_file.public-bucket');
        	$amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
            $dir = Config::get('custom_config_file.home_banner_img');
            $image_url = null; 
            if(!file_exists($dir))
            {
                mkdir($dir);
            }
            $image = $imgArray;
            $data = date('Y_m_d_H_i_s');
            $image_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $imgArray->getClientOriginalName());
            $image_name = str_replace('-', '_',$image_name);
            $new_file_name = str_replace(' ', '',$data."-"."customer-dynamic-image"."-".$image_name);
            $image_path = "$dir/$new_file_name";                
            $image->move($dir,$new_file_name);
            $this->commonFunction->compressImage($image_path);
            $image_url =$this->aws->uploadToPublicS3($new_file_name ,$image_path, $this->bucketname);
            $new_op_path=$this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
            return $new_op_path;
        }else{
        	return 0;
        }
    }

    //download customer content images
    public function downloadImageS3($image){
        if(isset($image)){
            $saveAsPath = '/tmp/';
            $filename_array = explode('/', $image);
            $download_url = $filename_array[count($filename_array) - 1];
            $tempFileName = end($filename_array);
            $filename = $this->aws->downloadFromS3($image, $saveAsPath);

            if($filename){
                $path = $saveAsPath.$filename;
                $file = File::get($path);
                $type = File::mimeType($path);
                $response = Response::make($file, 200);
                $response->header("Content-Type", $type);
                $b64image = base64_encode(file_get_contents($path));
                $b64image = 'data:'.$type.';base64,'.$b64image;
            }
            else{
                $b64image = null;
            }
            return $b64image;
        }
        else{
            return null;
        }
    }
}

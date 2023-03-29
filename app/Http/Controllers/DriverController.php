<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Models\Operator;
use App\Models\Document;
use App\Http\Controllers\OperatorsController;
use App\Http\Controllers\DocumentController;
use App\Http\Requests\Admin\StoreDriverRequest;
use App\Http\Controllers\NotificationController;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Hash;
use Validator;
use Config;
use DB;
use File;
use Log;
use Auth;
use Response;


class DriverController extends Controller
{
	public $bucketname;
	public $amazon_s3_url;
	public $shifts_times = [];
	public $days = [];

	public function __construct()
	{
		$this->aws = new CustomAwsController;        
		$this->middleware('auth');
		$this->bucketname = Config::get('custom_config_file.bucket-name');
		$this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
		$this->operatorController = new OperatorsController();
		$this->documentsController = new DocumentController();
		$this->notifiy = new NotificationController;
		$this->commonFunction = new CommonController;
		$this->shifts_times = Array 
		( 
			Array ( "value" => "Shift 1 (6AM to 2PM)" ,"name" => "Shift 1 (6AM to 2PM)" ,"checked" =>false) ,
			Array ( "value" =>"Shift 2 (2PM to 10 PM)" ,"name" => "Shift 2 (2PM to 10 PM)", "checked" => false) ,
			Array ( "value" => "Shift 3 (10PM to 6AM)" ,"name" => "Shift 3 (10PM to 6AM)", "checked" => false) 
		);
		$this->days = Array
		(
			Array("value" => "Mon","name"=>"Mon","checked"=>false),
			Array("value" => "Tue","name"=> "Tue","checked"=>false),
			Array("value" =>"Wed","name"=>"Wed","checked" =>false),
			Array("value" =>"Thu","name"=>"Thu","checked" =>false),
			Array("value" =>"Fri","name"=>"Fri","checked" =>false),
			Array("value" =>"Sat","name"=>"Sat","checked" =>false),
			Array("value" =>"Sun","name"=>"Sun","checked" =>false)
		);
	}

	public function index(request $request)
	{
		
		if (! Gate::allows('driver_view')) {
			return abort(401);
		}
	   
		$driver = Driver::all();
		
	}

	public function create()
	{
		$opOfflineHrs = [];
		array_push($opOfflineHrs, ['id'=>1,'time'=> 2]);
		array_push($opOfflineHrs, ['id'=>2,'time'=>4]);
		array_push($opOfflineHrs, ['id'=>3,'time'=>6]);
		$additionalDocList = $this->documentsController->getDocList();
		return view('admin.driver.create', compact('opOfflineHrs', 'additionalDocList'));
	}

	public function store(StoreDriverRequest $request) //save driver info-nayana
	{
		// dd($request->all());
		$data = $this->formatDriverInformation($request);
		
		$saveDriver = Driver::create($data);
		if($saveDriver){//save additional documents of driver
			$op_data = DB::table("ggt_operator_users")->select('op_user_id', 'op_mobile_no', 'op_type_id')->where('op_user_id','=',$saveDriver->op_user_id)->first();

			//change operator verification status-by nayana(04-oct-2019)
			$change_op_verification_status = Operator::where('op_user_id', $saveDriver->op_user_id)->update(['op_is_verified'=> 0]);
			$adminNotification = array(
				'subject' => 'Approve Operator',
				'message' => 'You have new operator verification request',
				'type' => 'op_verification_request',
				'message_view_id' => isset($saveDriver->op_user_id) ? $saveDriver->op_user_id : null,
				'message_pattern' => 'A-A',
				'message_sender_id' => Auth::user()->id,
				'message_from' => Auth::user()->name ,
				'url' => '/operators',
			);
			$data = $this->notifiy->sendNotificationToAdmin($adminNotification);
			//end-changhe operator verification status.

			if(isset($request->driving_license_doc)){
				$this->operatorController->uploadLicense($request->driving_license_doc, $saveDriver->driver_id, $op_data);
			}
			else{
				Log::warning("license variobale not set:driver controller line 92");
			}

			if(isset($request->pan_doc)){
				$this->operatorController->uploadPan($request->pan_doc, $saveDriver->driver_id, $op_data);
			}
			else{
				Log::warning("pan variobale not set:driver controller line 99");
			}

			if(isset($request->additional_documents))
			{
				$driver_id = isset($saveDriver->driver_id) ? $saveDriver->driver_id : null;
				$op_mobile = isset($op_data->op_mobile_no) ? $op_data->op_mobile_no : null;
				$response = $this->documentsController->saveAdditionalDocuments($request->additional_documents, $op_mobile, $driver_id, $veh_id = null, $op_user_id = null);
			}
		}

		return redirect()->route('operators.edit', $request->operator_id)->with('success', 'Driver has been added successfully!');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Driver  $driver
	 * @return \Illuminate\Http\Response
	 */
	public function show(Driver $driver)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Driver  $driver
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$driver=DB::table('ggt_drivers')
					->join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_drivers.op_user_id')
					->select('ggt_drivers.*','op_type_id')
					->where('driver_id','=',$id)
					->first();
					
		if(!empty($driver)){

			if($driver->op_type_id == 1){
	                $driver_image=DB::table('ggt_operator_users')->select('op_profile_pic')->where('op_user_id','=',$driver->op_user_id)->first();

	                if(isset($driver_image->op_profile_pic)){
	                $saveAsPath = '/tmp/';
	                $filename_array = explode('/', $driver_image->op_profile_pic);
	                $download_url = $filename_array[count($filename_array) - 1];
	                $tempFileName = end($filename_array);
	                $filename = $this->aws->downloadFromS3($driver_image->op_profile_pic, $saveAsPath);
	                if($filename)
	                {
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
	                $driver->driver_profile_pic = $b64image;
	        }

	        }
	        else{

				if(isset($driver->driver_profile_pic)){
					$saveAsPath = '/tmp/';
					$filename_array = explode('/', $driver->driver_profile_pic);
					$download_url = $filename_array[count($filename_array) - 1];
					$tempFileName = end($filename_array);
					$filename = $this->aws->downloadFromS3($driver->driver_profile_pic, $saveAsPath);
					if($filename)
					{
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
					$driver->driver_profile_pic = $b64image;
				}
			}
			$driverDoc = DB::table('ggt_op_document_details')
					->join('ggt_doc_types_master','ggt_doc_types_master.doc_type_id','=','ggt_op_document_details.doc_type_id')
					->where('ggt_op_document_details.deleted_at','=',null)
					->where('doc_driver_id','=',$driver->driver_id)->get()->toArray();

			$driver->lic_number = $driver->lic_expiry = $driver->lic_image = $driver->pan_number = $driver->pan_image = null;
			if(!empty($driverDoc)){
				foreach ($driverDoc as $doc_key => $doc_value) {
					if(isset($doc_value->doc_images)){
						$driverDoc[$doc_key]->doc_images = $this->documentsController->getdocImage($doc_value->doc_images);
					}
					else{
						$driverDoc[$doc_key]->doc_images = null;
					}
					if(isset($driverDoc[$doc_key]->doc_type_id) && ($driverDoc[$doc_key]->doc_type_id == 2)){
						$driver->lic_number = $driverDoc[$doc_key]->doc_number;
						$driver->lic_expiry = $driverDoc[$doc_key]->doc_expiry;
						$driver->lic_image = $driverDoc[$doc_key]->doc_images;
						$driver->lic_id = $driverDoc[$doc_key]->doc_id;
						$driver->lic_is_verify = $driverDoc[$doc_key]->is_verified;
						unset($driverDoc[$doc_key]);
					}elseif(isset($driverDoc[$doc_key]->doc_type_id) && ($driverDoc[$doc_key]->doc_type_id == 1)){
						$driver->pan_number = $driverDoc[$doc_key]->doc_number;
						$driver->pan_image = $driverDoc[$doc_key]->doc_images;
						$driver->pan_id = $driverDoc[$doc_key]->doc_id;
						$driver->pan_is_verify = $driverDoc[$doc_key]->is_verified;
						unset($driverDoc[$doc_key]);
					}else{
						//other additional doc
					}
				}
			}
		}

		$additionalDocList = $this->documentsController->getDocList();//get doc list

		$verification_status = $this->driverVerificationStatus($id); //function for checking driver verification status
		// dd($verification_status);

		$opOfflineHrs = [];
		array_push($opOfflineHrs, ['id'=>1,'time'=> 2]);
		array_push($opOfflineHrs, ['id'=>2,'time'=>4]);
		array_push($opOfflineHrs, ['id'=>3,'time'=>6]);
		$driver = (array) $driver;
		return view('admin.driver.edit', compact('driver', 'opOfflineHrs','b64image', 'driverDoc', 'additionalDocList', 'verification_status'));
		
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Driver  $driver
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		
		if(isset($request->driver_is_verified))
			$state=1;
		else
			$state=0;

		$driver=DB::table('ggt_drivers')
		->where("ggt_drivers.op_user_id", '=',$request->op_user_id)
		->update(['driver_first_name'=> $request->driver_first_name,
				  'driver_last_name'=> $request->driver_last_name,
				  'driver_op_username'=>$request->driver_op_username,                  
				  'driver_profile_pic'=>$request->driver_profile_pic,
				  'working_hours_from'=>$days,
				  'working_hours_to'=>$shifts,
				  'driver_is_online'=>$request->driver_is_online,
				  'driver_mobile_number'=>$request->driver_mobile_number,
				  'is_active'=>$request->is_active,
				  'driver_is_verified'=>$state,
				  
		 ]);

		return redirect()->route('operators.index')->with('success', 'Driver has been updated successfully!');
	   
	} 

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Driver  $driver
	 * @return \Illuminate\Http\Response
	 */
	public function updatebusiness(StoreDriverRequest $request)
	{
		// dd($request->all());
		if(!empty($request)){
			$driver_id = $request->driver_id;
			$data = $this->formatDriverInformation($request);
			unset($data['op_user_id']);

			$op_type = $request->op_type;
			if($op_type == 1){
                $op_data =[];
                $op_profile_pic = isset($data['driver_profile_pic']);
                $op_first_name = isset($data['driver_first_name']);
                
                if(!empty($op_profile_pic)){
                	$op_data['op_profile_pic'] = $data['driver_profile_pic'];
                	unset($data['driver_profile_pic']);
                }else{
                    Log::warning("document image not set:driver controller");
                }
                
                if($op_first_name){
                	$op_data['op_first_name'] = $data['driver_first_name'];
                }else{
                	Log::warning("driver first name  not set:driver controller");
                }
                
                if(!empty($op_data)){
                	$driverimage = DB::table('ggt_operator_users')->where("ggt_operator_users.op_user_id", '=',$request->operator_id)
                	->update($op_data);
                }else{
                    Log::warning("driver data empty:driver controller");
                }
			}
            else{
                    //do nothing
            }

			$driver = DB::table('ggt_drivers')->where("ggt_drivers.driver_id", '=',$request->driver_id)
				->update($data);
			if($driver_id){//save additional documents of driver
				$op_data = DB::table("ggt_operator_users")->select('op_user_id', 'op_mobile_no', 'op_type_id')->where('op_user_id','=',$request->operator_id)->first();
				if(isset($request->additional_documents))
				{
					$driver_id = isset($driver_id) ? $driver_id : null;
					$op_mobile = isset($op_data->op_mobile_no) ? $op_data->op_mobile_no : null;
					$response = $this->documentsController->saveAdditionalDocuments($request->additional_documents, $op_mobile, $driver_id, $veh_id = null, $op_user_id = null);
				}

				if(isset($request->driving_license_doc)){
					$this->operatorController->uploadLicense($request->driving_license_doc, $driver_id, $op_data);
				}
				else{
					Log::warning("license variobale not set:driver controller line 92");
				}

				if(isset($request->pan_doc)){
					$this->operatorController->uploadPan($request->pan_doc, $driver_id, $op_data);
				}
				else{
					Log::warning("pan variobale not set:driver controller line 99");
				}
				
				//change operator verification status-by nayana(04-oct-2019)
				$change_op_verification_status = Operator::where('op_user_id', $request->operator_id)->update(['op_is_verified'=> 0]);
				$adminNotification = array(
					'subject' => 'Approve Operator',
					'message' => 'You have new operator verification request',
					'type' => 'op_verification_request',
					'message_view_id' => isset($request->operator_id) ? $request->operator_id : null,
					'message_pattern' => 'A-A',
					'message_sender_id' => Auth::user()->id,
					'message_from' => Auth::user()->name ,
					'url' => '/operators',
				);
				$data = $this->notifiy->sendNotificationToAdmin($adminNotification);
				//end-changhe operator verification status.
			}
		}
		else{
			//empty request data
		}
		
		return redirect()->route('operators.edit', $request->operator_id)->with('success', 'Driver information has been updated successfully!');
	   
	} 

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Driver  $driver
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{        
		$docdelete = DB::table('ggt_drivers')
					->where('driver_id','=',$request->id)
					->update(['deleted_at'=>date('Y-m-d H:i:s')]); 
		
		$add_doc_delete = DB::table('ggt_op_document_details')
					->where('doc_driver_id','=',$request->id)
					->update(['deleted_at'=>date('Y-m-d H:i:s')]);
		
		
		if($docdelete && $add_doc_delete)
		{
			return 1;
		}
		else
		{
			return fasle;
		}
		
	}

	private function formatDriverInformation($request){
		if(!empty($request->working_shift_days)){
			for($i=0;$i<count($this->days);$i++)
			{
				for($j=0; $j<count($request->working_shift_days); $j++)
				{
					if($request->working_shift_days[$j] === $this->days[$i]["value"])
					{
						$this->days[$i]["checked"] = true;
					}
					else{
						//$this->days[$i]["checked"] = false;
					}
					
				}            
			}
			$driver_working_shift = json_encode($this->days);
		}
		else{
			$driver_working_shift = json_encode($this->days);
		}

		if(!empty($request->working_shift_time)){
			for($i=0;$i<count($this->shifts_times);$i++)
			{
				for($j=0; $j<count($request->working_shift_time); $j++)
				{
					if($request->working_shift_time[$j] === $this->shifts_times[$i]["value"])
					{
						$this->shifts_times[$i]["checked"] = true;
					}
					else{
						//$this->shifts_times[$i]["checked"] = false;
					}
					
				}            
			}
			$driver_working_time = json_encode($this->shifts_times);
		}
		else{
			$driver_working_time = json_encode($this->shifts_times);
		}

		if(isset($request->driver_is_verified)){
			$state=1;
		}
		else{
			$state=0;
		}

		if(isset($request->driver_is_online) && $request->driver_is_online === 'true'){
			$request->driver_is_online = 1;
		}
		else{
			$request->driver_is_online = 0;
		}

		if(isset($request->driver_is_verified)){
			$op_mobile_no = Operator::where('op_user_id', $request->operator_id)->value('op_mobile_no');
		}
		else{
			$op_mobile_no = null;
		}

		$data = [
			'op_user_id'=> isset($request->operator_id) ? $request->operator_id : null,
			'driver_first_name'=> isset($request->driver_first_name) ? $request->driver_first_name : null,
			// 'driver_profile_pic'=> isset($newpath) ? $newpath : null,
			'working_shift_days'=> isset($driver_working_shift) ? $driver_working_shift : null,
			'working_shift_time'=> isset($driver_working_time) ? $driver_working_time : null,
			'driver_offline_hrs'=> isset($request->driver_offline_hrs) ? $request->driver_offline_hrs : null,
			'driver_is_online'=> isset($request->driver_is_online) ? $request->driver_is_online : null,
			'driver_mobile_number'=> isset($request->driver_mobile_number) ? $request->driver_mobile_number : null,
			'is_active'=> isset($request->is_active) ? $request->is_active : null,
			'driver_is_verified'=> isset($state) ? $state : null,
		];

		if(isset($request->driver_offline_hrs)){
			$data['driver_offline_updated_at'] = date('Y-m-d H:i:s');
		}
		else{
			$data['driver_offline_updated_at'] = null;
		}


		if(isset($request->driver_profile_pic))
		{            
			$dir = Config::get('custom_config_file.dir_profile_img');
			$image_url = null;

			if(!file_exists($dir))
			{
				mkdir($dir);
			}
				
			$driver_profile = $request->driver_profile_pic;
			// $file_name = $driver_profile->getClientOriginalName();
			$file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $driver_profile->getClientOriginalName());
			$file_name = str_replace('-', '_',$file_name);
			$new_file_name = str_replace(' ', '',$op_mobile_no."-dprofile-".$file_name);
			$image_path = "$dir/$new_file_name";
			$driver_profile->move($dir,$new_file_name);
			$this->commonFunction->compressImage($image_path);
			$image_url = $this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);
			if(!empty($image_url))
			{
				$data['driver_profile_pic'] =  $this->amazon_s3_url.$image_url;
			}
			else
			{
				//$newpath = NULL;
			}
		}

		return $data;
	}

	public function driverVerificationStatus($driver_id){
		$verification_status['status'] = true;
		$driver_doc = DB::table('ggt_op_document_details')
					->join('ggt_drivers','ggt_drivers.driver_id','=','ggt_op_document_details.doc_driver_id')
					->join('ggt_doc_types_master','ggt_doc_types_master.doc_type_id','=','ggt_op_document_details.doc_type_id')
					->select(
						'ggt_op_document_details.doc_id',
						'ggt_op_document_details.doc_driver_id',
						'ggt_op_document_details.doc_veh_id',
						'ggt_op_document_details.doc_user_id',
						'ggt_op_document_details.doc_number',
						'ggt_op_document_details.doc_type_id',
						'ggt_op_document_details.doc_images',
						'ggt_op_document_details.is_deleted',
						'ggt_op_document_details.doc_expiry',
						'ggt_drivers.driver_first_name',
						'ggt_drivers.driver_id',
						'ggt_op_document_details.is_verified',
						'ggt_doc_types_master.doc_label'
						)
			->where('ggt_op_document_details.doc_number','!=',null)
			->where('ggt_op_document_details.deleted_at','=',null)
			->where('ggt_op_document_details.doc_driver_id','=',$driver_id)->get()->toArray();
			
		if(!empty($driver_doc)){
			$driver_doc_status = $doc_arr = [];
			$flag = 0;
			if(isset($driver_doc[0])){
				$driver_doc_status = ['driver_name' => $driver_doc[0]->driver_first_name];
			}
			if(!empty($driver_doc)){
				$doc_list = [];
				foreach ($driver_doc as $doc_key => $doc_value) {

					if($doc_value->is_verified != 1){
						array_push($doc_list, $doc_value->doc_label);
						if($flag == 0){
							$flag == 1;
							$drivers['status']  = false;
						}
					}
					else{
						//document is verified
					}
				}
				$driver_doc_status['doc_list'] = $doc_list;
			}
			else{
				//empty value
			}
			if(!empty($driver_doc_status['doc_list'])){
				array_push($doc_arr, $driver_doc_status);
			}
			return $doc_arr;
		}
		else{
			//no documents
		}
	}
}

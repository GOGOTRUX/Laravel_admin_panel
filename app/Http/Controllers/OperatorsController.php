<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\DriverNotificationMessages;
use App\Models\DriverNotifications;
use App\Models\Driver;
use App\Models\Document;
use App\Models\MasterMaterial;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Gate;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Hash;
use Validator;
use Config;
use DB;
use Log;
use File;
use Response;
use Auth;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Models\OperatorVehicles;
use App\Models\BankMaster;
use App\Models\OperatorAccounts;
use App\Models\Customer;
use App\Models\Vehicles;
use App\Models\CreateFactor;
use App\Models\CustomerBookTrip;
use Session;

class OperatorsController extends Controller
{

	public $bucketname;
	public $amazon_s3_url;
	public $razor_accounts_api;
	public $razor_key;
	public $razor_secret;
	public $shifts_times;
	public $days;
	
	public function __construct()
	{
		// if (! Gate::allows('operator_manage')) {
		// 	return abort(401);
		// }
		// else{
			$this->aws = new CustomAwsController;        
			$this->commonFunction = new CommonController;   
			$this->documentController = new DocumentController;
			$this->notifiy = new NotificationController;     
			$this->middleware('auth');
			$this->bucketname = Config::get('custom_config_file.bucket-name');
			$this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
			$this->razor_accounts_api = Config::get('custom_config_file.razor_accounts_api');
			$this->razor_key = Config::get('custom_config_file.razor_key');
			$this->razor_secret = Config::get('custom_config_file.razor_secret');
			$this->SERVER_API_KEY="AIzaSyBN_cnv9SV90CDmeNFsMo7pMBaiE7Iux34";
			$this->shifts_times = Array 
			( 
				Array ( "value" => "Shift 1 (6AM to 2PM)" ,"name" => "Shift 1 (6AM to 2PM)" ,"checked" =>true) ,
				Array ( "value" =>"Shift 2 (2PM to 10 PM)" ,"name" => "Shift 2 (2PM to 10 PM)", "checked" => true) ,
				Array ( "value" => "Shift 3 (10PM to 6AM)" ,"name" => "Shift 3 (10PM to 6AM)", "checked" => true) 
			);
			$this->days = Array
			(
				Array("value" => "Mon","name"=>"Mon","checked"=>true),
				Array("value" => "Tue","name"=> "Tue","checked"=>true),
				Array("value" =>"Wed","name"=>"Wed","checked" =>true),
				Array("value" =>"Thu","name"=>"Thu","checked" =>true),
				Array("value" =>"Fri","name"=>"Fri","checked" =>true),
				Array("value" =>"Sat","name"=>"Sat","checked" =>true),
				Array("value" =>"Sun","name"=>"Sun","checked" =>true)
			);
		// }
	}

	public function index($operator_type = NULL)
	{
		if (! Gate::allows('operator_manage')) 
		{
			return abort(401);
		}
		else{
			if(empty($operator_type)) {
				$operators = Operator::select('op_uid', 'op_user_id', 'op_first_name', 'op_last_name','op_mobile_no','op_type_id','op_is_verified','created_at','op_registration_state', 'op_is_blocked','op_user_account_block_note')->orderByDesc('op_user_id')->whereNull('deleted_at')->get();
				$header = "All Operators";
			}elseif($operator_type == "individual") {
				$operators = Operator::select('op_uid', 'op_user_id', 'op_first_name', 'op_last_name','op_mobile_no','op_type_id','op_is_verified','created_at','op_registration_state', 'op_is_blocked','op_user_account_block_note')->orderByDesc('op_user_id')->where('op_type_id', 1)->whereNull('deleted_at')->get();
				$header = "Individual Operators";
			}elseif($operator_type == "business") {
				$operators = Operator::select('op_uid', 'op_user_id', 'op_first_name', 'op_last_name','op_mobile_no','op_type_id','op_is_verified','created_at','op_registration_state', 'op_is_blocked','op_user_account_block_note')->orderByDesc('op_user_id')->where('op_type_id', 2)->whereNull('deleted_at')->get();
				$header = "Business Operators";
			}else {
				abort(404);
			}

			if(!empty($operators)){
				foreach ($operators as $key => $value) {
					$parse_date_from = Carbon::parse($value['created_at']);
        			$operators[$key]['created_date'] = Carbon::createFromFormat('Y-m-d', $parse_date_from->toDateString())->toFormattedDateString();
				}
			}else{
				$operators = array();				
			}

			$materialModels = MasterMaterial::select('material_type')->distinct()->get()->toArray();
	        // sort element by veh_type-name
	        $materialModels = array_values(Arr::sort($materialModels, function ($value) {
	            return $value['material_type'];
	        }));

			return view('admin.operators.index', ['operators' => $operators, 'materialModels' => $materialModels,'header' => $header]);
		}
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function show($id)
	{
		if (! Gate::allows('operator_view')) 
		{
			return abort(401);
		}
		else{

			$header = "Operators";

			$driver=DB::table('ggt_drivers')->where('ggt_drivers.op_user_id','=',$id)->get();
			
			if(!empty($driver)){

				if(isset($driver->driver_profile_pic)){
					$saveAsPath = '/tmp/';
					$filename_array = explode('/', $driver->driver_profile_pic);
					$download_url = $filename_array[count($filename_array) - 1];
					$tempFileName = end($filename_array);
					$filename = $this->aws->downloadFromS3($driver->driver_profile_pic, $saveAsPath);
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
					$driver->driver_profile_pic = $b64image;
				}
			}


			$operatorvehicles=DB::table('ggt_op_vehicles')->where('ggt_op_vehicles.veh_op_id','=',$id)->get()->toArray();        

			$operator = Operator::select('op_uid', 'op_user_id', 'op_first_name', 'op_last_name', 'op_middle_name','op_username', 'op_mobile_no', 'op_alternative_mobile_no', 'op_email', 'op_gender', 'op_pet_name', 'op_city_name', 'op_type_id', 'op_address_line_1', 'op_address_line_2', 'op_address_line_3', 'op_address_city', 'op_address_pin_code', 'op_address_state', 'op_address_country', 'op_bank_name', 'op_bank_ifsc', 'op_bank_account_number', 'is_active', 'op_registration_state', 'operator_selected', 'op_bu_address_city', 'op_bu_address_line_1', 'op_bu_address_line_2', 'op_bu_address_line_3', 'op_bu_address_pin_code', 'op_bu_address_state', 'op_bu_base_charge', 'op_bu_charge_per_person', 'op_bu_email', 'op_bu_gstn_available', 'op_bu_gstn_no', 'op_bu_landmark', 'op_bu_loader_available', 'op_bu_name', 'op_bu_no_person', 'op_bu_pan_no', 'op_bu_pan_no_doc', 'op_bu_per_km', 'op_bu_type', 'op_dob', 'op_profile_pic', 'op_status', 'op_registration_step', 'op_payment_mode', 'op_landmark', 'op_is_verified')->findOrFail($id);
			
			if(!empty($operatorvehicles))
			{
				foreach ($operatorvehicles as $key => $value) {
					$veh_images1 = [];
					$vehicle = (array)$value;
					if(isset($vehicle['veh_images'])){
						$veh_images = json_decode($vehicle['veh_images'], true);
						foreach ($veh_images as $key1 => $value1) {
							$saveAsPath = '/tmp/';
							$filename_array = explode('/', $value1);
							$download_url = $filename_array[count($filename_array) - 1];
							$tempFileName = end($filename_array);
							$filename = $this->aws->downloadFromS3($value1, $saveAsPath);
							if($filename){
								$path = $saveAsPath.$filename;
								$file = File::get($path);
								$type = File::mimeType($path);
								$response = Response::make($file, 200);
								$response->header("Content-Type", $type);
								$b64image = base64_encode(file_get_contents($path));
								array_push($veh_images1, $b64image);
							}
							else{
								$b64image = null;
							}
						}
						$vehicle['veh_images_array'] = json_encode($veh_images1);
					}
					else{
						$vehicle['veh_images_array'] = null;
					}
					$operatorvehicles[$key] = $vehicle;
				}
			}
			
			
			if(!empty($operator)){

				if(isset($operator->op_profile_pic)){
					$saveAsPath = '/tmp/';
					$filename_array = explode('/', $operator->op_profile_pic);
					$download_url = $filename_array[count($filename_array) - 1];
					$tempFileName = end($filename_array);
					$filename = $this->aws->downloadFromS3($operator->op_profile_pic, $saveAsPath);
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
					$operator->op_profile_pic = $b64image;
				}
			}
			

			$documents=DB::table('ggt_op_document_details')
							->join('ggt_drivers','ggt_drivers.driver_id','=','ggt_op_document_details.doc_driver_id')
							->join('ggt_doc_types_master','ggt_doc_types_master.doc_type_id','=','ggt_op_document_details.doc_type_id')
							->where('ggt_op_document_details.deleted_at','=',null)
							->where('ggt_drivers.op_user_id','=',$id)->get();

			   
			for ($i=0; $i <count($documents) ; $i++) 
			{                     
			   if(isset($documents[$i]->doc_images)){
					$saveAsPath = '/tmp/';
					$filename_array = explode('/', $documents[$i]->doc_images);
					$download_url = $filename_array[count($filename_array) - 1];
					$tempFileName = end($filename_array);
					$filename = $this->aws->downloadFromS3($documents[$i]->doc_images, $saveAsPath);
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
					$documents[$i]->doc_images = $b64image;
				}

			} 
			
			$pincodeslist=DB::table('ggt_master_pincodes')->select('id','pincode')->get();                
			 $address=DB::table('ggt_master_cities')->join('ggt_master_states','ggt_master_states.id','=','ggt_master_cities.state_id')->select('ggt_master_cities.state_id','ggt_master_states.state','ggt_master_cities.id','ggt_master_cities.city')->where('ggt_master_cities.id','=',$operator->op_address_city)->get();
			 
			if(!empty($operator->op_type_id)) 
			{
				$header = ($operator->op_type_id == 1)? "Individual Operators":"Business Operators";
			}
			
			return view('admin.operators.view', compact('operator','driver','operatorvehicles','documents','header','states' ,'address','pincodeslist'));
		}
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function verifyMobile(Request $request)
	{
		 
		if (! Gate::allows('operator_manage')) {
			return abort(401);
		}
		else{
			$details=DB::table('ggt_operator_users')->select('op_user_id','op_mobile_no','op_first_name','op_last_name','op_email','op_username')->where('op_mobile_no','=',$request->op_pay_mobile_no)->get();
			 
			 if(isset($details->first()->op_mobile_no))
			 {   
				 $data = array('op_mobile_no'=>$details->first()->op_mobile_no);
			   
				return $details;
			 }
			else
				return $details;
		}	
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function edit($id)
	{
		if (! Gate::allows('operator_edit')) 
		{
			return abort(401);
		}
		else{
			try{
				$bucketname = Config::get('custom_config_file.bucket-name');
				$amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
				$dirPath = Config::get('custom_config_file.dir_path_admin_images');
				$dir = Config::get('custom_config_file.dir_admin_images');

				$header = "Operators";
				$verification_status = [];

				$driver = DB::table('ggt_drivers')->whereNull('deleted_at')->where('ggt_drivers.op_user_id','=',$id)->get()->toArray();
						
				if(!empty($driver)){
					$driver_doc_status = $this->checkDriverDocStatus($driver);
					// dd($driver_doc_status);
					$verification_status['status'] = $driver_doc_status['status'];
					$verification_status['driver_status'] = $driver_doc_status;
				}
				else{
					$driver_status = ['status' => false, 'msg' => 'Please add driver'];
					$verification_status['status'] = false;
					$verification_status['driver_status'] = $driver_status;
				}

				$operatorvehicles = DB::table('ggt_op_vehicles')->where('is_deleted','=',0)
					->where('ggt_op_vehicles.veh_op_id','=',$id)->get()->toArray(); 
				if(!empty($operatorvehicles))
				{
					$driver_veh_status = $this->checkVehicleDocStatus($operatorvehicles);
					if(!$driver_veh_status['status']){
						$verification_status['status'] = $driver_veh_status['status'];
					}
					$verification_status['vehicle_status'] = $driver_veh_status;

					foreach ($operatorvehicles as $key => $value) {
						$veh_images1 = [];
						$vehicle = (array)$value;
						if(isset($vehicle['veh_images']) && !empty($vehicle['veh_images']) && $vehicle['veh_images'] != 'null'){
							$veh_images = json_decode($vehicle['veh_images'], true);
							//$saveAsPath1 = $dirPath.$dir;
							foreach ($veh_images as $key1 => $value1) {
								$saveAsPath1 = $dirPath.$dir;
								// $saveAsPath = '/tmp/';
								$filename_array = explode('/', $value1);
								$download_url = $filename_array[count($filename_array) - 1];
								$tempFileName = end($filename_array);
								$is_file_exists = File::exists($saveAsPath1.$tempFileName);
								// $is_file_exists = File::exists($saveAsPath.$tempFileName);
								if($is_file_exists){
									$filename = $tempFileName;
								}
								else{
									$filename = $this->aws->downloadFromS3($value1, $saveAsPath1);
									// $filename = $this->aws->downloadFromS3($value1, $saveAsPath);
								}
								if($filename){
									// $path = $saveAsPath.$filename;
									$path = $saveAsPath1.$filename;
									$file = File::get($path);
									$type = File::mimeType($path);
									$response = Response::make($file, 200);
									$response->header("Content-Type", $type);
									$b64image = base64_encode(file_get_contents($path));
									// array_push($veh_images1, $b64image);
									array_push($veh_images1, $dir.$filename);
								}
								else{
									$b64image = null;
								}
							}
							$vehicle['veh_images_array'] = json_encode($veh_images1);
						}
						else{
							$vehicle['veh_images_array'] = null;
						}
						$operatorvehicles[$key] = $vehicle;
					}
				}
				else{
					$vehicle_status = ['status' => false, 'msg' => 'Please add vehicle'];
					$verification_status['status'] = false;
					$verification_status['vehicle_status'] = $vehicle_status; 
				}

				$operator = Operator::select('op_user_id', 'op_first_name', 'op_middle_name','op_last_name', 'op_username', 'op_mobile_no', 'op_alternative_mobile_no', 'op_email', 'op_gender', 'op_pet_name', 'op_city_name', 'op_type_id', 'op_address_line_1', 'op_address_line_2', 'op_address_line_3', 'op_address_city', 'op_address_pin_code', 'op_address_state', 'op_address_country', 'op_bank_name', 'op_bank_ifsc', 'op_bank_account_number','is_active', 'op_registration_state', 'operator_selected', 'op_bu_address_city', 'op_bu_address_line_1', 'op_bu_address_line_2', 'op_bu_address_line_3', 'op_bu_address_pin_code', 'op_bu_address_state', 'op_bu_base_charge', 'op_bu_charge_per_person', 'op_bu_email', 'op_bu_gstn_available', 'op_bu_gstn_no', 'op_bu_landmark', 'op_bu_loader_available', 'op_bu_name', 'op_bu_no_person', 'op_bu_pan_no', 'op_bu_pan_no_doc', 'op_bu_per_km', 'op_bu_type', 'op_dob', 'op_profile_pic', 'op_status', 'op_registration_step', 'op_payment_mode', 'op_landmark', 'op_is_verified','op_blank_cheque', 'is_op_bank_verified')->findOrFail($id);
				if(!empty($operator)){
					if($operator->op_type_id == 0){
						return redirect()->route('operators.index')->with('success', 'Operator Type Is Not Set');
					}

					if(isset($operator->op_blank_cheque)){
					    $saveAsPath1 = $dirPath.$dir;
					    // $saveAsPath = '/tmp/';
					    $filename_array = explode('/', $operator->op_blank_cheque);
					    $download_url = $filename_array[count($filename_array) - 1];
					    $tempFileName = end($filename_array);
					    $is_file_exists = File::exists($saveAsPath1.$tempFileName);
					    if($is_file_exists){
					        $filename = $tempFileName;
					    }
					    else{
					        $filename = $this->aws->downloadFromS3($operator->op_blank_cheque, $saveAsPath1);
					    }
					    if($filename){
					        $path = $saveAsPath1.$filename;
					        // $file = File::get($path);
					        // $type = File::mimeType($path);
					        // $response = Response::make($file, 200);
					        // $response->header("Content-Type", $type);
					        // $b64image = base64_encode(file_get_contents($path));
					        $b64image = $dir.$filename;
					    }
					    else{
					        $b64image = null;
					    }
					    $operator->op_blank_cheque = $b64image;
					}
					// else{
					// 	$bank_status = ['status' => false, 'msg' => 'Please add bank details'];
					// 	$verification_status['status'] = false;
					// 	$verification_status['bank_status'] = $bank_status; 
					// } //commented by nayana

					//check whether bank details is verified or not-nayana
				    $operator_payment_mode = $operator['op_payment_mode'];
				    if($operator_payment_mode == 1){
				    	$bank_status = [
				    	'status' => true,
				    	'msg' => 'verified'];
				    }
				    else{
					    $bank_verification_status = ($operator['is_op_bank_verified'] == 1) ? true : false;
					    if($bank_verification_status){
					    	$bank_status = [
					    	'status' => $bank_verification_status,
					    	'msg' => 'verified'];
					    }
					    else{
					    	$bank_status = [
					    	'status' => $bank_verification_status,
					    	'msg' => 'Please verify bank details'];
					    	$verification_status['status'] = false;
					    }
				    }
					$verification_status['bank_status'] = $bank_status;
				    //end code-nayana

					if(isset($operator->op_profile_pic)){
						$saveAsPath1 = $dirPath.$dir;
						// $saveAsPath = '/tmp/';
						$filename_array = explode('/', $operator->op_profile_pic);
						$download_url = $filename_array[count($filename_array) - 1];
						$tempFileName = end($filename_array);
						$is_file_exists = File::exists($saveAsPath1.$tempFileName);
						if($is_file_exists){
							$filename = $tempFileName;
						}
						else{
							$filename = $this->aws->downloadFromS3($operator->op_profile_pic, $saveAsPath1);
						}
						if($filename){
							$path = $saveAsPath1.$filename;
							// $file = File::get($path);
							// $type = File::mimeType($path);
							// $response = Response::make($file, 200);
							// $response->header("Content-Type", $type);
							// $b64image = base64_encode(file_get_contents($path));
							$b64image = $dir.$filename;
						}
						else{
							$b64image = null;
						}
						$operator->op_profile_pic = $b64image;
					}

					if(isset($operator->op_bu_pan_no_doc)){
						$saveAsPath1 = $dirPath.$dir;
						// $saveAsPath = '/tmp/';
						$filename_array = explode('/', $operator->op_bu_pan_no_doc);
						$download_url = $filename_array[count($filename_array) - 1];
						$tempFileName = end($filename_array);
						$is_file_exists = File::exists($saveAsPath1.$tempFileName);
						if($is_file_exists){
							$filename = $tempFileName;
						}
						else{
							$filename = $this->aws->downloadFromS3($operator->op_bu_pan_no_doc, $saveAsPath1);
						}
						if($filename){
							$path = $saveAsPath1.$filename;
							// $file = File::get($path);
							// $type = File::mimeType($path);
							// $response = Response::make($file, 200);
							// $response->header("Content-Type", $type);
							// $b64image = base64_encode(file_get_contents($path));
							$b64image = $dir.$filename;
						}
						else{
							$b64image = null;
						}
						$operator->op_bu_pan_no_doc = $b64image;
					}
				}
				if(!empty($operator) && ( $operator->op_type_id ==1 )){
					$driver_id = Driver::where('op_user_id', $id)->value('driver_id');
					$driver_documents = [];
					if(!empty($driver_id)){
						$driver_documents = DB::table('ggt_op_document_details')
							->join('ggt_doc_types_master','ggt_doc_types_master.doc_type_id','=','ggt_op_document_details.doc_type_id')
							->select('ggt_op_document_details.doc_type_id','ggt_op_document_details.doc_images','ggt_op_document_details.doc_expiry','ggt_op_document_details.doc_number','ggt_op_document_details.doc_id','ggt_op_document_details.doc_user_id','ggt_op_document_details.doc_veh_id','ggt_op_document_details.doc_driver_id', 'ggt_doc_types_master.doc_label', 'ggt_op_document_details.is_verified')
							->where('ggt_op_document_details.deleted_at','=',null)
							->where('ggt_op_document_details.doc_driver_id','=',$driver_id)->get()->toArray();
					}
					else{
						Log::warning("No driver has been added");
					}

					if(!empty($driver_documents)){
						foreach ($driver_documents as $key => $value) {
							if(($driver_documents[$key]->doc_type_id == 1) && empty($driver_documents[$key]->doc_number) && empty($driver_documents[$key]->doc_images)){//remove pan optional field
								unset($driver_documents[$key]);
								continue;
						   }
						   else{
							   if(isset($driver_documents[$key]->doc_images)){
							   		$saveAsPath1 = $dirPath.$dir;
									// $saveAsPath = '/tmp/';
									$filename_array = explode('/', $driver_documents[$key]->doc_images);
									$download_url = $filename_array[count($filename_array) - 1];
									$tempFileName = end($filename_array);
									$is_file_exists = File::exists($saveAsPath1.$tempFileName);
									if($is_file_exists){
										$filename = $tempFileName;
									}
									else{
										$filename = $this->aws->downloadFromS3($driver_documents[$key]->doc_images, $saveAsPath1);
									}
									if($filename){
										$path = $saveAsPath1.$filename;
										// $file = File::get($path);
										// $type = File::mimeType($path);
										// $response = Response::make($file, 200);
										// $response->header("Content-Type", $type);
										// $b64image = base64_encode(file_get_contents($path));
										$b64image = $dir.$filename;
									}
									else{
										$b64image = null;
									}
									$driver_documents[$key]->doc_images = $b64image;
								}
								else{
									$driver_documents[$key]->doc_images = null;
								}
							}

							//set pan number
							if(isset($driver_documents[$key]->doc_type_id) && ($driver_documents[$key]->doc_type_id == 2)){
								$operator['lic_number'] = $driver_documents[$key]->doc_number;
								$operator['lic_expiry'] = $driver_documents[$key]->doc_expiry;
								$operator['lic_image'] = $driver_documents[$key]->doc_images;
								$operator['lic_id'] = $driver_documents[$key]->doc_id;
								$operator['lic_is_verify'] = $driver_documents[$key]->is_verified;
								unset($driver_documents[$key]);
							}elseif(isset($driver_documents[$key]->doc_type_id) && ($driver_documents[$key]->doc_type_id == 1)){
								$operator['pan_number'] = $driver_documents[$key]->doc_number;
								$operator['pan_image'] = $driver_documents[$key]->doc_images;
								$operator['pan_id'] = $driver_documents[$key]->doc_id;
								$operator['pan_is_verify'] = $driver_documents[$key]->is_verified;
								unset($driver_documents[$key]);
							}else{
								Log::warning("Type not match");
							}
						}
					}
					else{
						//no documents
					}
				}
				else{
					//business operator
				}
				
				$pincodesdata=DB::table('ggt_master_pincodes')->select('id','pincode')->get()->toArray();
				
				$pincodeslist = array_values(Arr::sort($pincodesdata, function ($value) {
	            	return $value->pincode;
	        	}));

				$bupincodeslist = $pincodeslist;

				//get document type listing
				$driverAdditionalDoc = $this->documentController->getDocList();
				$businessAdditionalDoc = $this->documentController->getBusinessDocList();
				//end

				//get business document listing
				$businessDoc = $this->documentController->getBusinessDoc($id);

				if(!empty($businessDoc)){
					foreach ($businessDoc as $doc_key => $doc_value) {
						if(isset($doc_value->doc_images)){
							$businessDoc[$doc_key]->doc_images = $this->documentController->getdocImage($doc_value->doc_images);
						}
						else{
							//$businessDoc[$doc_key]->doc_images = null;
						}
					}
				}
				//end

				$address=DB::table('ggt_master_cities')->join('ggt_master_states','ggt_master_states.id','=','ggt_master_cities.state_id')->select('ggt_master_cities.state_id','ggt_master_states.state','ggt_master_cities.id','ggt_master_cities.city')->where('ggt_master_cities.id','=',$operator->op_address_city)->get();

				$bu_address=DB::table('ggt_master_cities')->join('ggt_master_states','ggt_master_states.id','=','ggt_master_cities.state_id')->select('ggt_master_cities.state_id','ggt_master_states.state','ggt_master_cities.id','ggt_master_cities.city')->where('ggt_master_cities.id','=',$operator->op_bu_address_city)->first();

				if(!empty($operator)){
					$operator_id = $operator->op_user_id;
					$isDriverAvailable = Driver::where('op_user_id', $operator_id)->count();
				}
				if(!empty($operator->op_type_id)) {
					$header = ($operator->op_type_id == 1)?"Individual Operators":"Business Operators";
					$operator_type = ($operator->op_type_id == 1) ? 1 : 2;
				}
				if(!empty($operator)){
					$operator_id = $operator->op_user_id;
					$isVehicleAvailable = OperatorVehicles::where('veh_op_id', $operator_id)->where('is_deleted',0)->count();
				}
				$bankslist = BankMaster::select('id','op_bank_name')->get()->toArray();
				return view('admin.operators.edit', compact('operator','driver','operatorvehicles','header','pincodeslist','address', 'bu_address', 'verification_status','operator_id','operator_type','isDriverAvailable','isVehicleAvailable', 'bupincodeslist', 'driverAdditionalDoc', 'businessAdditionalDoc','businessDoc','bankslist','driver_documents'));
			}catch (\Aws\S3\Exception\S3Exception $e) {
				sleep(100000);
				return redirect()->route('operators.edit',[$id]);
			}
		}
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function update(Request $request, $id)
	{    
		if (! Gate::allows('operator_edit')) {
			return abort(401);
		}
		else{
			$rules = [ 
						'op_first_name'=>'required',
						'op_last_name'=>'required',					
						//'op_username'=>'required|alpha',
						'op_dob'=>'required|date',
						// 'op_email'=>'required|email',					
						'op_address_line_1'=>'required',                                          
						'op_address_state'=>'required', 
						'op_address_city'=>'required', 
						'op_address_pin_code'=>'required', 
					 ];
			
			$messages = [
						 // 'op_first_name.alpha' => 'The First Name may only contain letters.',
						 'op_first_name.required'=>'The First name field is required.',
						  // 'op_last_name.alpha' => 'The Last Name may only contain letters.',
						  'op_last_name.required'=>'The Last name field is required.',
						  //'op_username.alpha'=> 'The User Name may only contain letters.',
						  'op_dob.date'=>'The Date of birth is not a valid date.',
						  // 'op_email.email'=>'The  email must be a valid email address.',
						  'op_address_line_1.required'=>'The Flat/Shop/Place field is required', 
						  'op_address_state.required'=>'The State field is required', 
						  'op_address_city.required'=>'The City field is required', 
						  'op_address_pin_code.required'=>'The Pin code field is required', 
			];
					
			$validator = Validator::make($request->all(), $rules,$messages);
			if ($validator->fails()) {
				return redirect("operators/".$id."/edit")
							->withErrors($validator)
							->withInput();
			}

			if(isset($request->op_profile_pic))
			{            
				$dir = Config::get('custom_config_file.dir_profile_img');
				$image_url = null; 
					
					if(!file_exists($dir))
					{
						mkdir($dir);
					}
					
					$op_profile_pic = $request->op_profile_pic;
					$op_mobile_no = $request->op_mobile_no;
					$data = date('Y_m_d_H_i_s');
					// $file_name = $request->op_profile_pic->getClientOriginalName();
					$file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->op_profile_pic->getClientOriginalName());
					$file_name = str_replace('-', '_',$file_name);
					// $new_file_name = str_replace(' ', '',$data."-"."bupan"."-".$file_name);
					$new_file_name = str_replace(' ', '',$op_mobile_no."-profile-".$file_name);
					$image_path = "$dir/$new_file_name";                
					$op_profile_pic->move($dir,$new_file_name);

					$this->commonFunction->compressImage($image_path);

					$image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);

					$new_op_path=$this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
													
				$setpath=DB::table('ggt_operator_users')
				->where('ggt_operator_users.op_user_id','=',$id)                    
				->update([ 'op_profile_pic'=>$new_op_path ]);              
			}
			
			if(!empty($request->op_dob)){
				$op_dob = Carbon::parse($request->op_dob)->format('Y-m-d HH:m:s');
			}else{
				$op_dob = null;
			}

			$data = [
				'op_first_name'=>$request->op_first_name, 
				'op_middle_name' => $request->op_middle_name,
				'op_last_name'=>$request->op_last_name,
				'op_alternative_mobile_no'=> isset($request->op_alternative_mobile_no) ? $request->op_alternative_mobile_no : null, 
				'op_username'=>$request->op_username,
				'op_dob'=>$op_dob,
				'op_email'=>$request->op_email, 
				'op_gender'=>$request->op_gender, 
				'op_address_line_1'=>$request->op_address_line_1,
				'op_pet_name'=>isset($request->op_pet_name) ? $request->op_pet_name : null,
				'op_landmark'=>isset($request->op_landmark) ? $request->op_landmark :null,
				'op_address_line_2'=>$request->op_address_line_2, 
				'op_address_line_3'=>isset($request->op_address_line_3) ? $request->op_address_line_3 : null, 
				'op_address_state'=>$request->op_address_state, 
				'op_address_city'=>$request->op_address_city, 
				'op_address_pin_code'=>$request->op_address_pin_code,
			];
			
			$operators=DB::table("ggt_operator_users")
						->where('ggt_operator_users.op_user_id','=',$id)
						->update($data);
			//save individual op as driver - nayana
			// $this->saveOperatorAsDriver($data, $id);
			$op_data = DB::table("ggt_operator_users")->select('op_user_id', 'op_first_name', 'op_mobile_no', 'op_type_id')->where('op_user_id','=',$id)->first();
			if(!empty($op_data && $op_data->op_type_id == 1)){
				$isDriverAvailable = Driver::where('op_user_id', $op_data->op_user_id)->count();
				if ($isDriverAvailable == 0) {
						$driver_data = [
							'op_user_id'=> isset($id) ? $id : null,
							'driver_first_name'=> isset($op_data->op_first_name) ? $op_data->op_first_name : null,
							'driver_profile_pic'=> null,
							'working_shift_days'=> isset($this->days) ? json_encode($this->days) : null,
							'working_shift_time'=> isset($this->shifts_times) ? json_encode($this->shifts_times) : null,
							'driver_offline_hrs' => isset($request->driver_offline_hrs) ? $request->driver_offline_hrs : 0,
            				'driver_offline_updated_at' => date('Y-m-d H:i:s'),
							'driver_mobile_number'=> isset($op_data->op_mobile_no) ? $op_data->op_mobile_no : null,
							// 'is_active'=> 1,
							'driver_is_verified'=> 0,
						];

						$result = Driver::create($driver_data);
						$driver_id = $result->driver_id;
				}	
				else{

					$driver_data = [
						'driver_first_name'=> isset($op_data->op_first_name) ? $op_data->op_first_name : null,
						'driver_mobile_number'=> isset($op_data->op_mobile_no) ? $op_data->op_mobile_no : null,
					];

					$driver_id = Driver::where('op_user_id', $id)->value('driver_id');
					$update_driver = Driver::where('driver_id', $driver_id)->update($driver_data);
				}
				if(isset($request->additional_documents)){
					$response = $this->documentController->saveAdditionalDocuments($request->additional_documents, $op_data->op_mobile_no, $driver_id, $veh_id = null, $op_user_id = null);
					
				}
				// dd($request->all());
				if(isset($request->driving_license_doc)){
					$this->uploadLicense($request->driving_license_doc, $driver_id, $op_data);
				}
				else{
					Log::warning("license variobale not set:operator controller line 661");
				}
				//function to save documents of operator
				if(isset($request->pan_doc)){
					$this->uploadPan($request->pan_doc, $driver_id, $op_data);
				}
				else{
					Log::warning("pan variobale not set:operator controller line 668");
				}
			}
			//end-nayana
			return redirect()->route('operators.index')->with('success', 'Operator information has been updated successfully.');
		}
		
	}

	public function deleteselected(Request $request)
	{
		
		/*
		if (! Gate::allows('operators'))
		{
			return abort(401);
		}
	   
		foreach ($request->selectid as $deleteid)
		{            
			$operators=DB::table("ggt_operator_users")
					->where('op_user_id','=',$deleteid)            
					->update(['deleted_at'=>date('Y-m-d H:i:s')]);    
		}

		return "Multiple Operator deleted successfully";  
		*/             
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	
	public function updateStatus(Request $request)
	{                
		
		if (! Gate::allows('operator_edit')) {
			return abort(401);
		}
		else{
			// dd($request->all());
			$emailid=DB::table('ggt_operator_users')
					 ->where("ggt_operator_users.op_user_id", '=',$request->op_user_id)
					 ->select('op_email')
					 ->get();

			if(isset($request->op_is_verified))
			{
				$isUIDExists = Operator::where('op_user_id', $request->op_user_id)->value('op_uid');
				if(!empty($isUIDExists)){
					$op_uid = $isUIDExists;
					$verify_op = [
						'op_is_verified' => 1,
					];
				}
				else{
					$op_uid = $this->getuid($request->op_user_id);
					$verify_op = [
						'op_is_verified' => 1,
						'op_uid' => $op_uid,
					];

					/* send notification to operator*/
					$data = Operator::select('op_notification_token')->where('op_user_id', '=', $request->op_user_id)->first();
					$token = isset($data['op_notification_token']) ? $data['op_notification_token'] : null;

					if(!empty($token)){

						$data = [
	                        'message' => 'Congratulation!!! You are verified by admin.',
	                        'notification_types' => 'op-verified',
	                    ]; 
	                    $msgdata = array(
	                        'title' => 'Verification',
	                        'message' => 'You are verified by admin.',
	                        'message_type' => 'CNF',
	                        'message_pattern' => 'A-D',
	                        'message_sender_id' => Auth::user()->id,
	                        'message_view_id' => isset($request->op_user_id) ? $request->op_user_id : null,
	                        'message_from' => Auth::user()->name,
	                        'url' => Config::get('custom_config_file.notification_url'),
	                        'message_payload' => json_encode($data),
	                    );
	                    $createNotification = DriverNotificationMessages::create($msgdata);
	                    $messagedata = array(
	                        'op_user_id' =>  $request->op_user_id,
	                        'message_id' => $createNotification->notification_msg_id,
	                        'message_receiver_id' =>  $request->op_user_id,
	                    );
	                    $notification = DriverNotifications::create($messagedata);
	                    $notification = [
	                        'title'=>'Verification',
	                        'body'=>'You are verified by admin.',
	                        'sound' => 'default',
	                        'vibrate' => [1000, 5000, 1000],
	                        'notification_types' => 'op-verified',
	                        'click_action' => Config::get('custom_config_file.notification_url').'all-notifications-details/'.$createNotification->notification_msg_id,
	                        'icon' => '/assets/icons/notification-icon.png',
	                    ];
	                    $payload = [
	                        'to' => $token,
	                        'notification' => $notification,
	                        'data' => $data,
	                        'fcmId' => 'web',
	                    ];
	                    $pushNotification = $this->commonFunction->sendPushNotification($payload);
	                    //update message response
	                    if($pushNotification != 'error'){
	                        $updateUserAccount = DriverNotificationMessages::where('notification_msg_id',$createNotification->notification_msg_id)->update([
	                            'message_response' => $pushNotification,
	                        ]);
	                    }
					}
					/*end-code: send notification to operator*/
				}
				$updateOperator = DB::table('ggt_operator_users')
				->where("ggt_operator_users.op_user_id", '=',$request->op_user_id)
				->update($verify_op);
				if($updateOperator == 1){
					// dd("here");
					$isAccountExists = OperatorAccounts::where('op_user_id', $request->op_user_id)->exists();
					if(!$isAccountExists){
						$data = array(
							'op_user_id' => $request->op_user_id,
							'op_uid' => $op_uid,
							'op_mobile_no' => $request->op_mobile_no,
							);
						$createAcnt = OperatorAccounts::create($data);
					}{
						Log::warning("account already exists.");
					}
				}
				else{
					Log::warning("something went wrong.");
				}
				return redirect()->route('operators.index')->with('success', 'Operator has been Verified successfully!');    

			}
			else
			{
					$operator=DB::table('ggt_operator_users')
					->where("ggt_operator_users.op_user_id", '=',$request->op_user_id)
					->update(['op_is_verified'=>0 ]);	

					return redirect()->route('operators.index')->with('success', 'Operator information updated successfully');    
			}				
		}
	}

	public function getuid($op_id){
		// $op_info = Operator::select('op_user_id', 'op_dob', 'op_bu_pan_no')->where('op_user_id', $op_id)->get()->first();
		// $isAlreadyAPartner = Operator::where('op_bu_pan_no', $op_info['op_bu_pan_no'])->where('op_dob', $op_info['op_dob'])->where('op_uid', '!=' ,null)->exists();
		// if($isAlreadyAPartner){
  //           $old_uid = Operator::where('op_bu_pan_no', $op_info['op_bu_pan_no'])->where('op_dob', $op_info['op_dob'])->where('op_uid', '!=' ,null)->value('op_uid');
  //           $new_uid = $old_uid;
  //       }else{
  //       }
        $getMaxOpID = Operator::max('op_uid');
        $getMaxOpID++;
        $new_uid = $getMaxOpID;
        return $new_uid;
	}

	public function operatorBlocked(Request $request)
	{
		if (! Gate::allows('operator_block')) {
			return abort(401);
		}
		else{
			$blockedstatus=DB::table("ggt_operator_users")
						->where('op_user_id','=',$request->operator_id)
						->update([
							'op_user_account_block_note' =>$request->reason,
							'op_is_blocked' => 1
						]);   
			if($blockedstatus == 1)
			{
				return redirect()->route('operators.index')->with('success', 'Operator has been blocked successfully.');
			}
			else
			{
				return redirect()->route('operators.index')->with('error', 'Something went wrong');
			}
		}
		
	}

	public function operatorUnlock(Request $request) //written by nayana
	{
		try{
			if (! Gate::allows('operator_block')) {
				return abort(401);
			}
			else{
				$blockedstatus = DB::table("ggt_operator_users")->where('op_user_id','=',$request->unblock_op_id)
							->update([
								'op_user_account_block_note' => null,
								'op_is_blocked' => 0
							]);   
				if($blockedstatus == 1)
				{
					return redirect()->route('operators.index')->with('success', 'Operator has been unblocked successfully.');
				}
				else
				{
					return redirect()->route('operators.index')->with('error', 'Something went wrong.');
				}
			}
		}
		catch (Exception $e) {
			report($e);
		}
	}

	public function destroy(Request $request)
	{	
		/*
	  if (! Gate::allows('operators')) {
			return abort(401);
		}        
	  $operators=DB::table("ggt_operator_users")
					->where('op_user_id','=',$request->selectid)            
					->update(['deleted_at'=>date('Y-m-d H:i:s')]); 
		  return 'Operator has been deleted successfully!';     
		*/

        $customer = Operator::find($request->id)->delete();
        if($customer){
            return json_encode(['status'=> 'success', 'response'=> true,'message' => 'partner deleted successfully']);
        }else{
            return json_encode(['status'=> 'failed', 'response'=> false,'message' => 'failed to delete partner']);
        }
        
	}

	public function saveBusiness(Request $request, $id){
		
	}	

	public function updateBankInfo(Request $request){
		
		if ($request->isMethod('post')) 
		{
			$rules = [ 
				'op_bank_name' => 'required',
				'op_bank_ifsc' =>'required',
				'op_bank_account_number' =>'required',
				'op_blank_cheque' =>'required',
				// 'op_razorpay_accid' =>'required'
			 ];
			
			$messages = [
				 'op_bank_name.required' => 'Bank name is required.',
				 'op_bank_ifsc.required'=>'Bank ifsc is required.',
				 'op_bank_account_number.required'=>'Bank account number is required.',
				 'op_blank_cheque.required'=>'Upload a Bank Cheque is required.',
				 // 'op_razorpay_accid.required'=>'Razorpay account id is required.',
			];
					
			// $validator = Validator::make($request->all(), $rules,$messages);
			// if ($validator->fails()) {
			// 	return redirect("operators/".$request->op_user_id."/edit")
			// 		->withErrors($validator)
			// 		->withInput();
			// }
			if(isset($request->op_document_image))
			{            
			    $dir = Config::get('custom_config_file.dir_profile_img');
			    $image_url = null; 
			        
			        if(!file_exists($dir))
			        {
			            mkdir($dir);
			        }
			        
			        $op_blank_check_pic = $request->op_document_image;
			        // $op_mobile_no = $request->op_mobile_no;
			        $data = date('Y_m_d_H_i_s');
			        // $file_name = $request->op_profile_pic->getClientOriginalName();
			        $file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->op_document_image->getClientOriginalName());
			        $file_name = str_replace('-', '_',$file_name);
			        // $new_file_name = str_replace(' ', '',$data."-"."bupan"."-".$file_name);
			        $new_file_name = str_replace(' ', '',"blank-cheque-".$file_name);
			        $image_path = "$dir/$new_file_name";                
			        $op_blank_check_pic->move($dir,$new_file_name);

			        $this->commonFunction->compressImage($image_path);

			        $image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);

			        $new_op_path=$this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
			        $setpathimage=DB::table('ggt_operator_users')->where('ggt_operator_users.op_user_id','=',$request->op_user_id)->update([ 'op_blank_cheque'=>$new_op_path ]); 
			        //change operator verification status-by nayana(04-oct-2019)
					$change_op_verification_status = Operator::where('op_user_id', $request->op_user_id)->update(['op_is_verified'=> 0, 'is_op_bank_verified' => 0]);
					$adminNotification = array(
						'subject' => 'Approve Operator',
						'message' => 'You have new operator verification request',
						'type' => 'op_verification_request',
						'message_view_id' => isset($request->op_user_id) ? $request->op_user_id : null,
						'message_pattern' => 'A-A',
						'message_sender_id' => Auth::user()->id,
						'message_from' => Auth::user()->name ,
						'url' => '/operators',
					);
					$data = $this->notifiy->sendNotificationToAdmin($adminNotification);
					//end-changhe operator verification status.          
			}
			$data = [
				'op_bank_name' => $request->op_bank_name,
				'op_bank_ifsc' => $request->op_bank_ifsc,
				'op_bank_account_number' => $request->op_bank_account_number,
				// "op_blank_cheque" => $new_op_path,
				// 'op_razorpay_accid' => $request->op_razorpay_accid,
			];
			
			$updateBnkInfo=DB::table("ggt_operator_users")
						->where('ggt_operator_users.op_user_id','=',$request->op_user_id)
						->update($data);
			if(!empty($updateBnkInfo)){
				//change operator verification status-by nayana(04-oct-2019)
				$change_op_verification_status = Operator::where('op_user_id', $request->op_user_id)->update(['op_is_verified'=> 0, 'is_op_bank_verified' => 0]);
				$adminNotification = array(
					'subject' => 'Approve Operator',
					'message' => 'You have new operator verification request',
					'type' => 'op_verification_request',
					'message_view_id' => isset($request->op_user_id) ? $request->op_user_id : null,
					'message_pattern' => 'A-A',
					'message_sender_id' => Auth::user()->id,
					'message_from' => Auth::user()->name ,
					'url' => '/operators',
				);
				$data = $this->notifiy->sendNotificationToAdmin($adminNotification);
				//end-changhe operator verification status.
				return redirect()->route('operators.index')->with('success', 'Operator information has been updated successfully.');
			}else{
				return redirect()->route('operators.index')->with('error', 'Operator information has not updated.');
			}

		}else{
			Log::error('error not a post method, update bankinfo');
		}
	}

	public function uploadLicense($driving_license, $driver_id, $op_data){
		$is_license_available = DB::table('ggt_op_document_details')
				->where('ggt_op_document_details.doc_driver_id','=',$driver_id)
				->where('ggt_op_document_details.deleted_at',null)
				->where('ggt_op_document_details.doc_type_id', $driving_license['doc_type_id'])->value('doc_id');

		if(!empty($driving_license['lic_validity'])){
			$driving_license['lic_validity'] = Carbon::parse($driving_license['lic_validity'])->format('Y-m-d HH:m:s');
		}else{
			$driving_license['lic_validity'] = null;
		}

		if(!empty($driving_license['lic_number']) && !empty($driving_license['doc_type_id'])){
			$lic_data = [
				'doc_number' => $driving_license['lic_number'],
				'doc_expiry' => isset($driving_license['lic_validity']) ? $driving_license['lic_validity'] : null,
				'doc_type_id' => $driving_license['doc_type_id'],
				'doc_driver_id' => isset($driver_id) ? $driver_id : null,
			];
		}
		else{
			Log::warning("empty varibles (lic_number,doc_type_id):operator controller line 878");
		}

		if(isset($driving_license['lic_image'])){ //if only image uploaded
			$lic_data['doc_images'] = $this->documentController->uploadLicImage($driving_license['lic_image'], $op_data->op_mobile_no, 'lic');
		}
		else{
			Log::warning("license image not uploaded:operator controller line 650");
		}
		if(!empty($is_license_available)){
			if(isset($lic_data)){
				$document=DB::table('ggt_op_document_details')
					->where("ggt_op_document_details.doc_id", '=',$is_license_available)
					->update($lic_data);
					$this->updateOperatorVerificationStatus($op_data);
			}
		}
		else{
			if(isset($lic_data)){
				$result = Document::create($lic_data);
				$this->updateOperatorVerificationStatus($op_data);
			}
		}
	}

	public function updateOperatorVerificationStatus($op_data){
		//change operator verification status-by nayana(04-oct-2019)
			$change_op_verification_status = Operator::where('op_user_id', $op_data->op_user_id)->update(['op_is_verified'=> 0]);
			$adminNotification = array(
				'subject' => 'Approve Operator',
				'message' => 'You have new operator verification request',
				'type' => 'op_verification_request',
				'message_view_id' => isset($op_data->op_user_id) ? $op_data->op_user_id : null,
				'message_pattern' => 'A-A',
				'message_sender_id' => Auth::user()->id,
				'message_from' => Auth::user()->name ,
				'url' => '/operators',
			);
			$data = $this->notifiy->sendNotificationToAdmin($adminNotification);
			//end-changhe operator verification status.
	}

	public function uploadPan($driver_pan, $driver_id, $op_data){
		$is_license_available = DB::table('ggt_op_document_details')
				->where('ggt_op_document_details.doc_driver_id','=',$driver_id)
				->where('ggt_op_document_details.doc_type_id', $driver_pan['doc_type_id'])->value('doc_id');

		if(!empty($driver_pan['doc_type_id']) && !empty($driver_pan['pan_number'])){
			$pan_data = [
				'doc_number' => isset($driver_pan['pan_number']) ? $driver_pan['pan_number'] : null,
				'doc_type_id' => $driver_pan['doc_type_id'],
				'doc_driver_id' => isset($driver_id) ? $driver_id : null,
			];
		}
		else{
			Log::warning("empty varibles (pan_number):operator controller line 912");
		}

		if(isset($driver_pan['pan_image'])){ //if only image uploaded
			$pan_data['doc_images'] = $this->documentController->uploadLicImage($driver_pan['pan_image'], $op_data->op_mobile_no, 'pan');
		}
		else{
			Log::warning("pan image not uploaded:operator controller line 919");
		}
		if(!empty($is_license_available)){
			if(isset($pan_data)){
				$document=DB::table('ggt_op_document_details')
					->where("ggt_op_document_details.doc_id", '=',$is_license_available)
					->update($pan_data);
					$this->updateOperatorVerificationStatus($op_data);
			}
		}
		else{
			if(isset($pan_data)){
				$result = Document::create($pan_data);
				$this->updateOperatorVerificationStatus($op_data);
			}
		}
	}

	public function getdriverImage(Request $request){
		$driver_image = $request->img_path;
		$saveAsPath = '/tmp/';
		$filename_array = explode('/', $driver_image);
		$download_url = $filename_array[count($filename_array) - 1];
		$tempFileName = end($filename_array);
		$filename = $this->aws->downloadFromS3($driver_image, $saveAsPath);
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
		return $b64image;
	}

	private function checkDriverDocStatus($driver){
		$op_active_count = $total_op_count = 0;
		$drivers = $doc_arr = [];
		if(!empty($driver)){ //get all driver documents
			$all_driver_doc = [];
			foreach ($driver as $key => $value) {
				if($value->is_active == 1){
					$op_active_count++;
				}
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
					->where('ggt_op_document_details.doc_driver_id','=',$value->driver_id)->get()->toArray();
				array_push($all_driver_doc, $driver_doc);
				if($op_active_count == 0){
					$drivers['status']  = false;
					$drivers['op_active_status'] = false;
					$drivers['op_active_msg'] = 'Please activate at least one driver';
				}
				else{
					$drivers['status'] = true;
					$drivers['op_active_status'] = true;
				}
			}
		}
		else{
			Log::warning("no driver added");
		}

		if(!empty($all_driver_doc)){ //check all driver documents are verified
			// $drivers = $doc_arr = [];
			// $drivers['status'] = true;
			$flag = 0;
			foreach ($all_driver_doc as $driver_doc_key => $driver_doc_value) { //
				$driver_doc_status = [];
				if(isset($driver_doc_value[0])){
					$driver_doc_status = ['driver_name' => $driver_doc_value[0]->driver_first_name];
				}

				if(!empty($driver_doc_value)){
					$doc_list = [];
					foreach ($driver_doc_value as $doc_key => $doc_value) {

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
			}
			$drivers['doc_status'] = $doc_arr;
		}
		else{
			//no documents
		}
		return $drivers;
	}

	private function checkVehicleDocStatus($vehicles){
		$vehicle_active_count = $total_veh_count = 0;
		$vehicle = $doc_arr = [];
		if(!empty($vehicles)){ //get all vehicle documents
			$total_veh_count = count($vehicles);
			$all_vehicle_doc = [];
			foreach ($vehicles as $key => $value) {
				if($value->is_active == 1){
					$vehicle_active_count++;
				}
				$vehicle_doc = DB::table('ggt_op_document_details')
					->join('ggt_op_vehicles','ggt_op_vehicles.veh_id','=','ggt_op_document_details.doc_veh_id')
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
						'ggt_op_vehicles.veh_registration_no',
						'ggt_op_vehicles.veh_id',
						'ggt_op_document_details.is_verified',
						'ggt_doc_types_master.doc_label'
						)
					->where('ggt_op_document_details.doc_number','!=',null)
					->where('ggt_op_document_details.deleted_at','=',null)
					->where('ggt_op_document_details.doc_veh_id','=',$value->veh_id)->get()->toArray();
				array_push($all_vehicle_doc, $vehicle_doc);
			}
			if($vehicle_active_count == 0){
				$vehicle['status']  = false;
				$vehicle['veh_active_status'] = false;
				$vehicle['veh_active_msg'] = 'Please activate at least one vehicle';
			}
			else{
				$vehicle['status'] = true;
				$vehicle['veh_active_status'] = true;
			}
		}
		else{
			Log::warning("no vehicle added");
		}

		if(!empty($all_vehicle_doc)){ //check all vehicle documents are verified
			// $vehicle = $doc_arr = [];
			// $vehicle['status'] = true;
			$flag = 0;
			foreach ($all_vehicle_doc as $veh_doc_key => $veh_doc_value) { //
				$vehicle_doc_status = [];
				if(isset($veh_doc_value[0])){
					$vehicle_doc_status = ['veh_name' => $veh_doc_value[0]->veh_registration_no];
				}

				if(!empty($veh_doc_value)){
					$doc_list = [];
					foreach ($veh_doc_value as $doc_key => $doc_value) {

						if($doc_value->is_verified != 1){
							array_push($doc_list, $doc_value->doc_label);
							if($flag == 0){
								$flag == 1;
								$vehicle['status']  = false;
							}
						}
						else{
							//document is verified
						}
					}
					$vehicle_doc_status['doc_list'] = $doc_list;
				}
				else{
					//empty value
				}
				if(!empty($vehicle_doc_status['doc_list'])){
					array_push($doc_arr, $vehicle_doc_status);
				}
			}
			$vehicle['doc_status'] = $doc_arr;
		}
		else{
			//no documents
		}
		return $vehicle;
	}

	public function verifyBankInfo(Request $request)
	{
		$op_details = Operator::select('op_first_name', 'op_last_name', 'op_email', 'op_bank_ifsc', 'op_bank_account_number', 'op_razoarpay_acc_id')->where('op_user_id','=',$request->id)->first();
		if(!empty($op_details)){
			$name = $op_details['op_first_name'].' '.$op_details['op_last_name'];
			$data = [
				'name' => isset($name) ? $name : null,
				'email' => isset($op_details['op_email']) ? $op_details['op_email'] : null,
				'tnc_accepted' => true,
				'account_details' => ['business_name' => $name, 'business_type' => 'individual'],
				'bank_account' => ['ifsc_code' => $op_details['op_bank_ifsc'], 'beneficiary_name' => $name, 'account_type' => 'current', 'account_number' => $op_details['op_bank_account_number']]
			];
		}
		if(!empty($op_details['op_razoarpay_acc_id'])){
			$data = ['is_op_bank_verified' => 1];
		}
		else{
			$response = $this->razorPayAccount($data);
			$data = [
				'op_razoarpay_acc_api_resp' => $response,
			];
			$response = json_decode($response, true);
			if(isset($response['error'])){
				return json_encode(['status'=> 'failed', 'response'=> $response['error']['description']]);
			}
			else{
				$data['op_razoarpay_acc_id'] = $response['id'];
				$data['is_op_bank_verified'] = 1;
			}
		}
		$verifybank = Operator::where('op_user_id','=',$request->id)->update($data); 
		if($verifybank)
		{
			return json_encode(['status'=> 'success', 'response'=> 'Your account has been verified.']);
		}
		else
		{
			return json_encode(['status'=> 'failed', 'response'=> "Something went wrong."]);
		}    
	}

	public function razorPayAccount($fields) {

		Log::info('fields: ', $fields); Log::warning($this->razor_accounts_api);
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->razor_accounts_api);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ));
		curl_setopt($ch, CURLOPT_USERPWD, $this->razor_key . ':' . $this->razor_secret);

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($ch);
		
		return $response;
	}

	public function partnerRates(Request $request){
		$getOpVehRates = OperatorVehicles::select('veh_id','veh_registration_no','veh_base_charge','veh_base_charge_rate_per_km')->where('veh_op_id',$request->id)->get()->toArray();
		if($getOpVehRates){
			foreach ($getOpVehRates as $key => $value) {
				$veh_per_km_rates = json_decode($getOpVehRates[$key]['veh_base_charge_rate_per_km'],true);
				$getOpVehRates[$key]['veh_3km_15km'] = $veh_per_km_rates['veh_3km_15km'];
				$getOpVehRates[$key]['veh_above_15km'] = $veh_per_km_rates['veh_above_15km'];
			}
			$response = ['status' => 'success', 'detail' => $getOpVehRates];
            return response()->json(['response' => $response]);
		}else{
			$response = ['status' => 'failed', 'message' => 'Unable to load partner charge!'];
            return response()->json(['response' => $response]);
		}
	}

	public function bookPartner(Request $request){
		$op_id = $request->id;
		Session::forget('bookPartnerId');
		Session::push('bookPartnerId', $op_id);
		$response = ['status' => 'success', 'message' => 'op id in session'];
		return response()->json(['response' => $response]);
	}
}

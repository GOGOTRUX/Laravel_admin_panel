<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\OperatorVehicles;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Gate;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Hash;
use Validator;
use Config;
use DB;
use File;
use Log;
use Response;
use Auth;
use App\Models\Operator;
use App\Http\Requests\Admin\storeVehicleRequest;
use App\Models\Vehicles;

class OperatorVehiclesController extends Controller
{
	public $bucketname;
	public $amazon_s3_url;
	
	public function __construct()
	{
		$this->aws = new CustomAwsController;        
		$this->middleware('auth');
		$this->bucketname = Config::get('custom_config_file.bucket-name');
		$this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
		$this->documentController = new DocumentController;
		$this->notifiy = new NotificationController;
		$this->commonFunction = new CommonController;
	}

	public function index(request $request)
	{
		abort(401);                 
	}

	public function create()
	{
		//
	}

	public function store(Request $request)
	{
		//
	}

	public function show($id)
	{
		
	}

	public function edit($id,$header)
	{   
		$dirPath = Config::get('custom_config_file.dir_path_admin_images');
		$dir = Config::get('custom_config_file.dir_admin_images');

		$operatorvehicles=DB::table('ggt_op_vehicles')
			->leftjoin('ggt_drivers','ggt_drivers.driver_id','=','ggt_op_vehicles.veh_driver_id')
			->select('ggt_op_vehicles.*','ggt_drivers.driver_id')
			->where('ggt_op_vehicles.veh_id','=',$id)
			->get();

		$color=DB::table('ggt_master_color')->get();
		$modelname=DB::table('ggt_vehicles')->select('veh_model_name')->where('veh_id','=',$operatorvehicles->first()->veh_model_name)->get();              
		$veh_type_list=DB::table('ggt_vehicles')->select('veh_type_name')->distinct()->get();

		$vehicleAdditionalDoc = $this->documentController->getVehicleDocList();

		if(!empty($operatorvehicles))
		{
			foreach ($operatorvehicles as $key => $value) {
				$veh_images1 = [];
				$vehicle = (array)$value;
			    if(isset($vehicle['veh_base_charge_rate_per_km']) && !empty($vehicle['veh_base_charge_rate_per_km'])){
	                $vehicle['veh_base_charge_rate_per_km'] = json_decode($vehicle['veh_base_charge_rate_per_km'],true);
	                $vehicle['veh_3km_15km'] = $vehicle['veh_base_charge_rate_per_km']['veh_3km_15km']; 
	                $vehicle['veh_above_15km'] = $vehicle['veh_base_charge_rate_per_km']['veh_above_15km'];
                }
                else{
                	$vehicle['veh_3km_15km'] = null;
	                $vehicle['veh_above_15km'] = null;
                    $vehicle['veh_base_charge_rate_per_km'] = null;
                }
        
                if(isset($vehicle['veh_city']) && !empty($vehicle['veh_base_lat_lng'])){
                    $vehicle['veh_base_lat_lng'] = json_decode($vehicle['veh_base_lat_lng'],true);
                    $vehicle['veh_base_lat'] = $vehicle['veh_base_lat_lng'][0]['lati'];
                    $vehicle['veh_base_lng'] = $vehicle['veh_base_lat_lng'][0]['long'];
                }else{
                    $vehicle['veh_base_lat'] = null;
                    $vehicle['veh_base_lng'] = null;
                }

				if(isset($vehicle['veh_images'])){
					$veh_images = json_decode($vehicle['veh_images'], true);
					foreach ($veh_images as $key1 => $value1) {
						$saveAsPath1 = $dirPath.$dir;

						// $saveAsPath = '/tmp/';
						$filename_array = explode('/', $value1);
						$download_url = $filename_array[count($filename_array) - 1];
						$tempFileName = end($filename_array);
						// $filename = $this->aws->downloadFromS3($value1, $saveAsPath);
						$filename = $this->aws->downloadFromS3($value1, $saveAsPath1);
						if($filename){
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
			Log::warning("vehicle not available : line-104");
		}

		$vehicleDoc = DB::table('ggt_op_document_details')
			->join('ggt_doc_types_master','ggt_doc_types_master.doc_type_id','=','ggt_op_document_details.doc_type_id')
			->where('ggt_op_document_details.deleted_at','=',null)
			->where('doc_veh_id','=',$id)->get()->toArray();

		if(!empty($vehicleDoc)){
			foreach ($vehicleDoc as $doc_key => $doc_value) {
				if(isset($doc_value->doc_images)){
					$vehicleDoc[$doc_key]->doc_images = $this->documentController->getdocImage($doc_value->doc_images);
				}
				else{
					$vehicleDoc[$doc_key]->doc_images = null;
				}
			}
		}
		return view('admin.vehicles.edit', compact('operatorvehicles','header','veh_type_list','modelname','color', 'vehicleAdditionalDoc', 'vehicleDoc'));
	}

	public function update(Request $request)
	{                          
		// $postdata = $request->all();              
	}

	public function businessUpdate(storeVehicleRequest $request)
	{
		$validated = $request->validated();
		$veh_op_id = OperatorVehicles::where('veh_id',$request->veh_id)->value('veh_op_id'); 

		if($request->veh_loader_available==0)
		{
			$request->veh_no_person=null;
			$request->veh_charge_per_person=null;
		}

		// veh_base_lat
		// veh_base_lng

	  	// format vehicle base lat,long end
        $veh_base_lat_lng = NULL;
        if(isset($request->veh_city) && isset($request->lat) && isset($request->lng)){
			if(!empty($request->lat) && !empty($request->lng)){
        		$veh_base_dimension = array(array('lati' =>(double) $request->lat, 'long' => (double)$request->lng));
        		$baselat = isset($request->lat) ? (double) $request->lat : null;
            	$baselong = isset($request->lng) ? (double) $request->lng : null;
            	$veh_base_lat_lng = json_encode($veh_base_dimension);
        	}else{
        		$veh_base_lat_lng = NULL;
        		$baselat = null;
        		$baselong = null;
        	}
		} 
        // format vehicle lat,long end

         // generate vehicle code
        if(!empty($request->veh_wheel_type) && !empty($request->veh_capacity) && !empty($request->veh_model_name) && !empty($request->veh_make_model_type) && !empty($request->veh_type)){
            $make = substr($request->veh_make_model_type,0,2);
            $model = Vehicles::where('veh_id', $request->veh_model_name)->value('veh_model_name');
            if(!empty($model) && strlen($model) > 3){
                $model = substr($model,0,3);
            }
            else{
                $model = $model;
            }
            if($request->veh_type == 1){
                $body_type = 'O';
            }
            elseif($request->veh_type == 2){
                $body_type = 'C';
            }else{
                $body_type = 'T';
            }
            $code =$request->veh_wheel_type.$make.$model.$body_type.$request->veh_capacity;
        }
        // generate vehicle code end

        // update vehicle username 
    	if(isset($request->operator_id)){
        	$veh_op_username = Operator::where('op_user_id',$request->operator_id)->value('op_mobile_no');    
        }else{
            $veh_op_username = null;
        }
        // update vehicle username

         // format vehicle registration number
        if(!empty($request->input('veh_registration_no'))){
            $veh_registration_no = explode ("-", $request->veh_registration_no);
            $veh_registration_no = implode ("", $veh_registration_no);
            $firstTwoChar = substr($veh_registration_no, 0, 2);
            $secondTwoChar = substr($veh_registration_no, 2, 2);
            $thirdTwoChar = substr($veh_registration_no, 4, 2);
            $lastFourChar = substr($veh_registration_no, 6,4);
            $veh_registration_no = $firstTwoChar.'-'.$secondTwoChar.'-'.$thirdTwoChar.'-'.$lastFourChar;
        }else{
        	$veh_registration_no = null;
        }
        // format vehicle registration number

        if(isset($request->veh_op_ownership) && $request->veh_op_ownership==0) {
        	$veh_op_ownership = 0;
        	$veh_owner_name = null;
        	$veh_owner_mobile_no = null;
        }else{
        	$veh_op_ownership = $request->veh_op_ownership;
        	$veh_owner_name = $request->veh_owner_name;
        	$veh_owner_mobile_no = $request->veh_owner_mobile_no;
        }
        if(!empty($request->input('veh_3km_15km')) && !empty($request->input('veh_above_15km'))) {
            $veh_base_charge_rate_per_km = array(
            	'veh_3km_15km' => $request->input('veh_3km_15km'),
            	'veh_above_15km' => $request->input('veh_above_15km'),
            );
        }
        else
        {
            $veh_base_charge_rate_per_km = null;
        }
        
    	$vehicleinfo = OperatorVehicles::find($request->veh_id);
		$vehicleinfo->veh_op_username = $veh_op_username;
		$vehicleinfo->veh_owner_name = $veh_owner_name;
		$vehicleinfo->veh_owner_mobile_no = $veh_owner_mobile_no;
		$vehicleinfo->veh_type = $request->veh_type;
		$vehicleinfo->veh_driver_id = $request->veh_driver_id;
		$vehicleinfo->veh_registration_no = $veh_registration_no;
		$vehicleinfo->veh_capacity = $request->veh_capacity;
		$vehicleinfo->veh_op_ownership = $veh_op_ownership;
		$vehicleinfo->veh_is_online = $request->veh_is_online;
		$vehicleinfo->is_active = $request->is_active;
		$vehicleinfo->veh_city = $request->veh_city;
		$vehicleinfo->veh_make_model_type = $request->veh_make_model_type;
		$vehicleinfo->veh_model_name = $request->veh_model_name;
		$vehicleinfo->veh_wheel_type = $request->veh_wheel_type;
		$vehicleinfo->veh_dimension = $request->veh_dimension;
		$vehicleinfo->veh_base_charge = $request->veh_base_charge;
		$vehicleinfo->veh_per_km = $request->veh_per_km;
		$vehicleinfo->veh_loader_available = $request->veh_loader_available;
		$vehicleinfo->veh_no_person = $request->veh_no_person;
		$vehicleinfo->veh_charge_per_person = $request->veh_charge_per_person;
		$vehicleinfo->veh_fuel_type = $request->veh_fuel_type;
		$vehicleinfo->veh_color = $request->veh_color;
		$vehicleinfo->vehicle_is_verified = ($request->vehicle_is_verified==null) ? 0 : 1;
		$vehicleinfo->veh_base_lat_lng = $veh_base_lat_lng;
	 	$vehicleinfo->veh_base_charge_rate_per_km = json_encode($veh_base_charge_rate_per_km);
		$vehicleinfo->veh_base_lati = $baselat;
		$vehicleinfo->veh_base_long = $baselong;
		$vehicleinfo->save();

		// update vehicle images
		if(isset($request->veh_images))
		{
			$image_array=array();

			$dir = Config::get('custom_config_file.dir_profile_img'); 

			for ($i=0; $i < count($request->veh_images); $i++) 
			{               
				$image_url = null; 
				
				if(!file_exists($dir))
				{
					mkdir($dir);
				}
				
				// code modifed by madhuri
				if(!empty($request->veh_id)){
                    $veh_op_id = OperatorVehicles::where('veh_id',$request->veh_id)->value('veh_op_id');
                    if(!empty($veh_op_id))
                    {
                    	$operator_mobile_no = Operator::where('op_user_id',$veh_op_id)->value('op_mobile_no');
                    	// update vehicle images
                    	$veh_images = $request->veh_images[$i];  
						// $file_name = $request->veh_images[$i]->getClientOriginalName();
						$file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->veh_images[$i]->getClientOriginalName());
						$file_name = str_replace('-', '_',$file_name);
						$new_file_name = str_replace(' ', '',$operator_mobile_no."-"."veh"."-".$request->veh_id."-".$file_name);
						
						$image_path = "$dir/$new_file_name";                
						$veh_images->move($dir,$new_file_name);
						
						$this->commonFunction->compressImage($image_path);
						$image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);
						if(!empty($image_url))
						{
						  array_push($image_array,$this->amazon_s3_url.$this->bucketname.'/'.$new_file_name);
						}
						else
						{
						  $image_array  = [];
						}
                    }else{
                    	Log::error('update vehicle imgs failed operator id is not present');	
                    }
                }else{
                	Log::error('update vehicle imgs failed vehid is not present');
                }
                // code modifed by madhuri end
			}
			sleep(5);
			$vehicleimg = OperatorVehicles::find($request->veh_id);
			$vehicleimg->veh_images = json_encode($image_array);
			$vehicleimg->save();

			// $setpath=DB::table('ggt_op_vehicles')->where("ggt_op_vehicles.veh_id", '=',$request->veh_id)->update(['veh_images'=>json_encode($image_array)]);              
		} 
		//end vehicle images

		//save additional documents
        if($request->has('additional_documents')){
            if(!empty($request->additional_documents)){
                $response = $this->documentController->saveAdditionalDocuments($request->additional_documents, $request->veh_op_username, $driver_id = null, $request->veh_id, $veh_op_id);
            }
            else{
                // do-nothing
            }
        }
        //save additional documents
    	$veh_op_id = OperatorVehicles::where('veh_id',$request->veh_id)->value('veh_op_id');

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
        if(!empty($vehicleUpdate)){
        	return redirect()->route('operators.edit',$veh_op_id);
        }else{
        	Log::error('error in vehicle update');
            return redirect()->route('operators.edit',$veh_op_id)->with('success', 'Operator vehicles has been updated successfully!');
        }
	}

	public function destroy(Request $request)
	{
		$op_veh_delete=DB::table('ggt_op_vehicles')
					->where('veh_id','=',$request->id)
					->update(['is_deleted'=>1]); 
		
		$add_doc_delete = DB::table('ggt_op_document_details')
					->where('doc_veh_id','=',$request->id)
					->update(['deleted_at'=>date('Y-m-d H:i:s')]);
		
		if($op_veh_delete && $add_doc_delete)
		{
			return 1;
		}
		else
		{
			return fasle;
		}
	}
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vehicles;
use App\Models\OperatorVehicles;
use App\Models\ColorMaster;
use App\Models\Operator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use DB;
use Validator;
use Auth;
use App\Http\Controllers\OperatorsController;
use App\Http\Requests\Admin\storeVehicleRequest;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommonController;
use Config;
use File;
use Response;
use App\Http\Controllers\DocumentController;
use Log;
use App\Models\GetMapAccessToken;
class VehiclesController extends Controller
{
    
    public function __construct()
    {
        $this->aws = new CustomAwsController;  
        $this->documentController = new DocumentController;
        $this->commonFunction = new CommonController;
        $this->notifiy = new NotificationController;    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     
    public function index()

    {
        if (! Gate::allows('vehicle_view')) {
            return abort(401);
        }
       
        $driver = Vehicles::all();
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	$mapToken = GetMapAccessToken::where('status',1)->get()->first()->access_token;
        $colors = ColorMaster::get()->toArray();
        if(empty($colors)){
            $colors = null;
        }
        
        $veh_type_list=DB::table('ggt_vehicles')->select('veh_type_name')->where('is_active',1)->distinct()->get();
        // get vehicles make
        $vehicle_makes = Vehicles::select('veh_type_name')->where('is_active',1)->distinct()->get()->toArray();
        $vehicle_makes = array_values(Arr::sort($vehicle_makes, function ($value) {
            return $value['veh_type_name'];
        }));
        $additionalDocList = $this->documentController->getVehicleDocList();
        return view('admin.vehicles.create', compact('colors','vehicle_makes','additionalDocList'),compact('mapToken'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeVehicleRequest $request)
    {
        $validated = $request->validated();

        // format vehicle base lat,long end
        $veh_base_lat_lng = NULL;
        if($request->has('lat') && $request->has('lng')){
            $baselat = isset($request->lat) ? (double) $request->lat : null;
            $baselong= isset($request->lng) ? (double) $request->lng : null;

            $veh_base_dimension = array(array('lati' =>(double) $request->input('lat'), 'long' => (double)$request->input('lng')));
            $veh_base_lat_lng = json_encode($veh_base_dimension);
        }else{
            $veh_base_lat_lng = NULL;
        }
        // format vehicle lat,long end
        
        // generate vehicle code
        if(!empty($request->input('veh_wheel_type')) && !empty($request->input('veh_capacity')) && !empty($request->input('veh_model_name')) && !empty($request->input('veh_make_model_type')) && !empty($request->input('veh_type'))){
            $make = substr($request['veh_make_model_type'],0,2);
            $model = Vehicles::where('veh_id', $request->input('veh_model_name'))->value('veh_model_name');
            if(!empty($model) && strlen($model) > 3){
                $model = substr($model,0,3);
            }
            else{
                $model = $model;
            }
            if($request->input('veh_type') == 1){
                $body_type = 'O';
            }
            elseif($request->input('veh_type') == 2){
                $body_type = 'C';
            }else{
                $body_type = 'T';
            }
            $code =$request->input('veh_wheel_type').$make.$model.$body_type.$request->input('veh_capacity');
        }
        // generate vehicle code end
        // format vehicle registration number
        if(!empty($request->input('veh_registration_no'))){
            $veh_registration_no = $request->input('veh_registration_no');
            $firstTwoChar = substr($veh_registration_no, 0, 2);
            $secondTwoChar = substr($veh_registration_no, 2, 2);
            $thirdTwoChar = substr($veh_registration_no, 4, 2);
            $lastFourChar = substr($veh_registration_no, 6,4);
            $veh_registration_no = $firstTwoChar.'-'.$secondTwoChar.'-'.$thirdTwoChar.'-'.$lastFourChar;
        }
        // format vehicle registration number
        if($request->has('veh_op_type')){
            $veh_op_username = Operator::where('op_user_id',$request->input('veh_op_id'))->value('op_mobile_no');    
        }else{
            $veh_op_username = null;
        }
        //save a vehicle charges
         if(!empty($request->input('veh_3km_15km')) &&  !empty($request->input('veh_above_15km'))) {
            $veh_base_charge_rate_per_km = json_encode(array(
                    'veh_3km_15km' => $request->input('veh_3km_15km'),
                    'veh_above_15km' => $request->input('veh_above_15km'),
                ));
        }
        else
        {
            $veh_base_charge_rate_per_km = null;
        }
        
        $Vehicles = new OperatorVehicles();
        $Vehicles->veh_op_id = $request->input('veh_op_id');
        $Vehicles->veh_driver_id = $request->input('veh_driver_id');
        $Vehicles->veh_op_type = $request->input('veh_op_type');
        $Vehicles->veh_type = $request->input('veh_type');
        $Vehicles->veh_op_username = isset($veh_op_username) ? $veh_op_username : null;
        $Vehicles->veh_registration_no = isset($veh_registration_no) ? $veh_registration_no : null;
        $Vehicles->veh_capacity = $request->input('veh_capacity');
        $Vehicles->veh_dimension = $request->input('veh_dimension');
        $Vehicles->veh_make_model_type = $request->input('veh_make_model_type');
        $Vehicles->veh_model_name = $request->input('veh_model_name');
        $Vehicles->veh_wheel_type = $request->input('veh_wheel_type');
        $Vehicles->veh_fuel_type = $request->input('veh_fuel_type');
        $Vehicles->veh_base_charge = $request->input('veh_base_charge');
        $Vehicles->veh_per_km = $request->input('veh_per_km');
        $Vehicles->veh_loader_available = $request->input('veh_loader_available');
        $Vehicles->veh_no_person = $request->input('veh_no_person');
        $Vehicles->veh_charge_per_person = $request->input('veh_charge_per_person');
        $Vehicles->veh_city = $request->input('veh_city');
        $Vehicles->veh_op_ownership = $request->input('veh_op_ownership');
        $Vehicles->veh_owner_name = $request->input('veh_owner_name');
        $Vehicles->veh_owner_mobile_no = $request->input('veh_owner_mobile_no');
        $Vehicles->veh_is_online = $request->input('veh_is_online');
        $Vehicles->veh_base_lat_lng = $veh_base_lat_lng;
        $Vehicles->veh_color = $request->input('veh_color');
        $Vehicles->veh_code = isset($code) ? $code : 'no-code'; // db-required 
        $Vehicles->is_active = $request->input('is_active');
        $Vehicles->veh_base_charge_rate_per_km = isset($veh_base_charge_rate_per_km) ? $veh_base_charge_rate_per_km : null;
        $Vehicles->vehicle_is_verified = $vehicle_is_verified = ($request->input('vehicle_is_verified')==null) ? 0 : 1;
        //$Vehicles->veh_last_location = $request->has('veh_last_location') ?  $request->input('veh_color'): 'baner'; //db-required 
        $Vehicles->is_deleted = 0; 
        $Vehicles->veh_base_lati = $baselat;
        $Vehicles->veh_base_long = $baselong;
        $result = $Vehicles->save();
        $veh_id = $Vehicles->veh_id;
        // dd($result);
        if(!empty($result)){
            if($request->hasfile('veh_images'))
            {
                $bucketname = Config::get('custom_config_file.bucket-name');
                $amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
                $dir = Config::get('custom_config_file.dir_veh_img');
                $filearray =array();
                $i = 0;
                foreach($request->file('veh_images') as $image){
                    if(!empty($image)){
                        if(!file_exists($dir))
                        {
                            mkdir($dir);
                        }
                        // $file_name = $image->getClientOriginalName();
                        $file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $image->getClientOriginalName());
                        $file_name = str_replace('-', '_',$file_name);
                        
                        $veh_op_id = OperatorVehicles::where('veh_id',$veh_id)->value('veh_op_id');

                        if(!empty($request->input('veh_op_type')) && !empty($veh_id)){
                            $operator_mobile_no = Operator::where('op_user_id',$request->input('veh_op_id'))->value('op_mobile_no');

                            $new_file_name = str_replace(' ', '',$operator_mobile_no."-"."veh"."-".$veh_id."-".$file_name);
                            $image_path = "$dir/$new_file_name";
                            $image->move($dir,$new_file_name);
                            $this->commonFunction->compressImage($image_path);
                            $S3_Response = $this->aws->uploadToS3($new_file_name ,$image_path, $bucketname);
                            sleep(3);
                            if(!empty($S3_Response))
                            {
                                $filearray[$i] = $amazon_s3_url.$S3_Response;
                            }
                            else
                            {
                                $filearray =NULL;
                            }

                            $i++;

                        }else{
                            Log::error('no vehilce op present'.$request->input('veh_op_type').$veh_id);
                        }

                    }else{
                        Log::error('add vehicle veh images not present');
                    }
                }
                $updatevehicleImages = OperatorVehicles::where('veh_id', '=',$veh_id)->update(['veh_images' => json_encode($filearray)]);
                $this->updateOperatorverificationStatus($request->input('veh_op_id'));
            }
        }

        //save additional documents
        if($request->has('additional_documents')){
            if(!empty($request->additional_documents)){
                $response = $this->documentController->saveAdditionalDocuments($request->additional_documents, $veh_op_username, $driver_id = null, $veh_id, $request->input('veh_op_id'));
                if($response){
                    $this->updateOperatorverificationStatus($request->input('veh_op_id'));
                    $updatevehicleImages = OperatorVehicles::where('veh_id', '=',$veh_id)->update(['updated_at' => date('Y-m-d H:i:s')]);       
                }
            }
            else{
                // do-nothing
            }
        }
        //save additional documents

        if(!empty($result)){
            $this->updateOperatorverificationStatus($request->input('veh_op_id'));
            return redirect()->route('operators.edit',$request->input('veh_op_id'))->with('success', 'Vehicle has been added successfully.');
        }else{
            Log::error('error in vehicle add');
            return redirect()->route('operators.edit',$request->input('veh_op_id'))->with('error', 'Opps! Something went wrong while adding vehicle information.');
        }
    }    

    public function updateOperatorverificationStatus($veh_op_id){
        //change operator verification status-by nayana(04-oct-2019)
        $change_op_verification_status = Operator::where('op_user_id', $veh_op_id)->update(['op_is_verified'=> 0]);
        $adminNotification = array(
            'subject' => 'Approve Operator',
            'message' => 'You have new operator verification request',
            'type' => 'op_verification_request',
            'message_view_id' => isset($veh_op_id) ? $veh_op_id : null,
            'message_pattern' => 'A-A',
            'message_sender_id' => Auth::user()->id,
            'message_from' => Auth::user()->name ,
            'url' => '/operators',
        );
        $data = $this->notifiy->sendNotificationToAdmin($adminNotification);
        //end-changhe operator verification status.
    }

    public function deleteselected(Request $request)
    {
        
        if (! Gate::allows('vehicles'))
        {
            return abort(401);
        }   
        
        foreach ($request->selectid as $deleteid)
        {                        
            $vehicles=DB::table("ggt_vehicles")
                    ->where('veh_id','=',$deleteid)            
                    ->update(['deleted_at'=>date('Y-m-d H:i:s')]); 
          
        }        
           return "Multiple Vehicles deleted successfully";        
    }
   
    public function getmodelname(Request $request)
    {
        $modelname=DB::table('ggt_vehicles')->select('veh_id','veh_model_name')->where('veh_type_name','=',$request->modeltypename)->get();
        return $modelname;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
          
        $Vehicles=DB::table('ggt_vehicles')->where('veh_id','=',$id)->get();        
        dd($Vehicles); 
        return view('admin.vehiclesdetails.edit',compact('Vehicles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
                        
    }

}

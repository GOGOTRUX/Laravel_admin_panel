<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\CommonController;
use App\Models\MasterMaterial;
use App\Models\OperatorVehicles;
use App\Models\ColorMaster;
use App\Models\CreateFactor;
use App\Models\Vehicles;
use App\Models\GetMapAccessToken;
use App\Models\Driver;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerBookTrip;
use App\Models\Operator;
use App\Models\DriverNotificationMessages;
use App\Models\DriverNotifications;
use App\Models\UserNotificationMessages;
use App\Models\UserPayments;
use App\Models\OperatorAccounts;
use App\Models\UserAccounts;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use DB;
use Log;
use Config;
use File;
use Session;
use Carbon\Carbon;
use Razorpay\Api\Api;
use App\Models\UserDetail;

class AddTripController extends Controller
{
    public function __construct()
    {           
        $this->aws = new CustomAwsController;
        $this->notification = new CommonController;
        $this->middleware('auth');
        $this->today = date('Y-m-d');
        $this->factor_digital = CreateFactor::where('variable_value','digital')->orderBy('factor_id', 'desc')->value('new_value');
        $this->factor_cash = CreateFactor::where('variable_value','cash')->orderBy('factor_id', 'desc')->value('new_value');
        $this->razor_payment_link_api = Config::get('custom_config_file.razor_payment_link_api');
		$this->razor_key = Config::get('custom_config_file.razor_key');
		$this->razor_secret = Config::get('custom_config_file.razor_secret');
    }

    public function tripDelivery(){
        return view('admin.addtrip.add-trip-delivery');
    }

    /*-------------------------send mail smtp--------------------------*/
    #SMTP
    public function sendMail(Request $request){
    // dd( $request->all());
    $findID = DB::Table('ggt_user')->where('user_mobile_no',$request->user_mobile_no)->first()->user_id;
    $postData = $request->all();
    $user = UserDetail::where('user_id',$findID)->update(['gstn'=>$request->gstn]);

    if($user){
    if(!empty($postData)){
			//get trip data from notification id
			$tripDetails = UserNotificationMessages::select('message_payload')->where('notification_msg_id', $postData['notiId'])->get()->first();
			$tripDetails = json_decode($tripDetails->message_payload, true);
			$getbooktype = gettype($tripDetails['booking_details']);

            if($getbooktype == 'string'){
            $tripBookDetails = json_decode($tripDetails['booking_details'], true);
			$tripVehDetails = json_decode($tripDetails['vehicle_detail'], true);

        }else{
			$tripBookDetails = $tripDetails['booking_details'];
            $tripVehDetails = $tripDetails['vehicle_detail'];
			}
			$ride_status = CustomerBookTrip::select('ride_status')->where('trip_transaction_id',$tripBookDetails['trip_transaction_id'])->get()->first();
	            if($ride_status->ride_status == 'success'){
            $email = $postData['email'];
            $email_Subject = 'Your Trip '.$tripBookDetails['trip_transaction_id'].' Bill Details.';
            $email_Body = array(
                        'booking_details' => $tripBookDetails,
                        'trip_details' => $tripVehDetails,
                        'gstn' => $postData['gstn'],
                    );
                    // dd('email body',$email_Body);
                $details = [
                    'title' => 'Invoice Demo mail through smtp',
                    'subject' => $email_Subject,
                    // 'body' => $email_Body,
                ];
                // dd($email_Body);
                $beautifyGenerateTripBillEmailHtml= $this->aws->beautifyGenerateTripBillEmail($email_Body);

                $emailDetails = ['subject'=> $email_Subject, 'html'=> $beautifyGenerateTripBillEmailHtml];
                // dd($emailDetails);
                Mail::to($request->email)->send(new \App\Mail\MailDemo($emailDetails));
                $returnResponse = ['status' => 'Success', 'message' => 'email sent','statusCode' => Response::HTTP_OK];
	        return response()->json(['response'=>$returnResponse]);
		}else{
                $returnResponse = ['status' => 'TripNotCompleted', 'message' => 'trip not completed yet','statusCode' => Response::HTTP_BAD_REQUEST];
                return response()->json(['response'=>$returnResponse]);
	            }
		}else{
			$returnResponse = ['status' => 'Failed', 'message' => 'not paid yet','statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
		}
    }
}

    public function addTrip(Request $request){
	 $mapToken = GetMapAccessToken::where('status',1)->get()->first()->access_token;
	   
    	$postData = $request->all();
    	if(!empty($postData)){

    	}

    	$materialModels = MasterMaterial::select('material_type')->distinct()->get()->toArray();
        // sort element by veh_type-name
        $materialModels = array_values(Arr::sort($materialModels, function ($value) {
            return $value['material_type'];
        }));
        if(!empty($materialModels)) {
    		return view('admin.addtrip.index',compact('materialModels','mapToken'));
        }else{
            $materialModels = null;
            return view('admin.addtrip.index',compact('materialModels','mapToken'));
        }
    }

    public function tripdata(Request $request){
    //	dd($request->all());
    	$postdata = $request->all();
    	$tripdata = [];
    	$bookingData = [];
    	$tripdata['userdetail'] = $postdata;
	//dd($tripdata['userdetail']);
    	/*$tripdata['userdetail']['user_id'] = "6";
    	$tripdata['userdetail']['pickup_date'] = array(
    		"year" => '2019',
    		"month" => '12',
    		'day' => '17' 
    	);*/
	if(isset($tripdata['userdetail']['cust_mobile'])){
    		$user_id = Customer::select('user_id')->where('user_mobile_no', $tripdata['userdetail']['cust_mobile'])->get()->first();
    		if($user_id){
			$user_id = $user_id['user_id'];
		}else{
			$data = array(
                        'user_mobile_no' => $tripdata['userdetail']['cust_mobile']
                );
                $user_id = Customer::create($data);
                $user_id = $user_id->user_id;
		}	
		
    	}/*else{
    		$data = array(
    			'user_mobile_no' => $tripdata['userdetail']['cust_mobile']
    		);
    		$user_id = Customer::create($data);
    		$user_id = $user_id->user_id; 
    	}*/
    	$tripdata['userdetail']['user_id'] = $user_id;
    	$bookingDate = explode('/', $tripdata['userdetail']['pickup_date']); 
    	$tripdata['userdetail']['pickup_date'] = array(
    		"year" => $bookingDate[2],
    		"month" => $bookingDate[0],
    		'day' =>  $bookingDate[1]
    	);
    	$tripdata['userdetail']['PickupLocations'] = array(
    		array(
    		"start_address_line_1" => isset($postdata['start_address_line_1']) ? $postdata['start_address_line_1'] : null,
    		"pickup_address_pin" => isset($postdata['pickup_address_pin']) ? $postdata['pickup_address_pin'] : null,
    		"start_address_line_2" => isset($postdata['start_address_line_2']) ? $postdata['start_address_line_2'] : null,
    		"start_address_line_3" => isset($postdata['start_address_line_3']) ? $postdata['start_address_line_3'] : null,
    		"start_address_line_4" => isset($postdata['start_address_line_4']) ? $postdata['start_address_line_4'] : null,
    		"pickup_user_name" => isset($postdata['pickup_user_name']) ? $postdata['pickup_user_name'] : null,
    		"pickup_user_mobile" => isset($postdata['pickup_user_mobile']) ? $postdata['pickup_user_mobile'] : null,
    		"start_address_lat" => isset($postdata['start_address_lat']) ? $postdata['start_address_lat'] : null,
    		"start_address_lng" => isset($postdata['start_address_lng']) ? $postdata['start_address_lng'] : null,
    	));
    /*	$tripdata['userdetail']['DestinationLocations'] = array(
    		array(
		"id" => 0,
    		"dest_address_line_1" => isset($postdata['dest_address_line_1']) ? $postdata['dest_address_line_1'] : null,
    		"delivery_address_pin" => isset($postdata['delivery_address_pin']) ? $postdata['delivery_address_pin'] : null,
    		"dest_address_line_2" => isset($postdata['dest_address_line_2']) ? $postdata['dest_address_line_2'] : null,
    		"dest_address_line_3" => isset($postdata['dest_address_line_3']) ? $postdata['dest_address_line_3'] : null,
    		"dest_address_line_4" => isset($postdata['dest_address_line_4']) ? $postdata['dest_address_line_4'] : null,
    		"delivery_user_name" => isset($postdata['delivery_user_name']) ? $postdata['delivery_user_name'] : null,
    		"delivery_user_mobile" => isset($postdata['delivery_user_mobile']) ? $postdata['delivery_user_mobile'] : null,
    		"dest_address_lat" => isset($postdata['dest_address_lat']) ? $postdata['dest_address_lat'] : null,
    		"dest_address_lan" => isset($postdata['dest_address_lan']) ? $postdata['dest_address_lan'] : null,
    	));*/
	$dropAddress = [];
	foreach ($tripdata['userdetail']['dest_address_line_1'] as $key => $value) {
		array_push($dropAddress, [
                            'id' => $key,
                            'dest_address_line_1' => isset($value) ? $value : null,
                            'dest_address_lat' => $tripdata['userdetail']['dest_address_lat'][$key],
                            'dest_address_lan' => $tripdata['userdetail']['dest_address_lan'][$key],
                        ]);
		
    		/*$tripdata['userdetail']['DestinationLocations'] = array(
    			array(
	    		"id" => $key,
	    		"dest_address_line_1" => isset($value) ? $value : null,
	    		"dest_address_lat" => $tripdata['userdetail']['dest_address_lat'][$key],
	    		"dest_address_lan" => $tripdata['userdetail']['dest_address_lan'][$key],
	    	));*/
    	}
	$tripdata['userdetail']['DestinationLocations'] = $dropAddress;
    //	dd($tripdata['userdetail']);
    	
    	$vehicleData = $this->getVehicleWithoutBid($tripdata);
    	if($vehicleData == 'Empty Request'){
    		return view('admin.addtrip.index');
    	}else{
    		$materialModels = MasterMaterial::select('material_type')->distinct()->get()->toArray();
        	// sort element by veh_type-name
        	$materialModels = array_values(Arr::sort($materialModels, function ($value) {
            	return $value['material_type'];
        	}));
        	if(!empty($materialModels)){
        		$searchtab = 'true';
        		$bookingData['pickup'] = isset($postdata['start_address_line_1']) ? $postdata['start_address_line_1'] : null;
        		$bookingData['drop'] = isset($postdata['dest_address_line_1']) ? $postdata['dest_address_line_1'] : null;
        		$bookingData['totaldistance'] = $vehicleData['totaldistance'];
                $bookingData['totaltime'] = $vehicleData['totaltime'];
                unset($vehicleData['totaldistance']);
               	unset($vehicleData['totaltime']);
		if(!isset($tripdata['userdetail']['book_op_id'])){
               	$vehicleData = array_values(Arr::sort($vehicleData, function ($value) {
    				return $value['ascircle_distance'];
				}));
		}
				$tripdata = $tripdata['userdetail'];
				$response = ['status' => 'Success','vehicles' => $vehicleData, 'tripdata' => $tripdata, 'bookingdata' => $bookingData];
    			return response()->json(['response'=> $response]);
        	}
    	}
    }

    public function getVehicleWithoutBid($data)
    {
        $lat_long = OperatorVehicles::select('veh_last_location')->where('is_active',1)->get()->toArray();
            $postdata = $data;
            if (!empty($postdata)) {
                    $vehicles = $postdata;
                    //query param
                    $user_id = isset($postdata['userdetail']['user_id']) ? $postdata['userdetail']['user_id'] : null;
                    $start_address_line_1 = isset($postdata['userdetail']['start_address_line_1']) ? $postdata['userdetail']['start_address_line_1'] : null;
                    $start_address_lat = isset($postdata['userdetail']['start_address_lat']) ? $postdata['userdetail']['start_address_lat'] : null;
                    $start_address_lng = isset($postdata['userdetail']['start_address_lng']) ? $postdata['userdetail']['start_address_lng'] : null;
                    $dest_address_line_1 = isset($postdata['userdetail']['dest_address_line_1'][0]) ? $postdata['userdetail']['dest_address_line_1'] : null;
                    $dest_address_lat = isset($postdata['userdetail']['dest_address_lat'][0]) ? $postdata['userdetail']['dest_address_lat'] : null;
                    $dest_address_lan = isset($postdata['userdetail']['dest_address_lan'][0]) ? $postdata['userdetail']['dest_address_lan'] : null;
                    $material_type = isset($postdata['userdetail']['material_type']) ? $postdata['userdetail']['material_type'] : null;
                    $vehicle_type = isset($postdata['userdetail']['vehicle_type']) ? $postdata['userdetail']['vehicle_type'] : null;
                    $weight = isset($postdata['userdetail']['weight']) ? $postdata['userdetail']['weight'] : null;
                    $loader_count = isset($postdata['userdetail']['loader_count']) ? $postdata['userdetail']['loader_count'] : null;
                    $fuel_type = isset($postdata['userdetail']['vehicle_fuel_type']) ? $postdata['userdetail']['vehicle_fuel_type'] : null;
                    $is_bidding = isset($postdata['userdetail']['user_bid_mode']) ? $postdata['userdetail']['user_bid_mode'] : null;
                    $payment_mode = isset($postdata['userdetail']['payment_mode']) ? $postdata['userdetail']['payment_mode'] : null;
                    $intermediate_address = isset($postdata['userdetail']['intermediate_address']) ? $postdata['userdetail']['intermediate_address'] : null;
                    
                    $matchThese = [
                         'ggt_op_vehicles.veh_type' => $vehicle_type,
                         'ggt_op_vehicles.veh_fuel_type' => $fuel_type,
                         'ggt_op_vehicles.is_active' => 1,
                    ];
		    $book_op_id = isset($postdata['userdetail']['book_op_id']) ? $postdata['userdetail']['book_op_id'] : null;
                //end query param
                $offset = 0;
                $limit = 30;
                $tempArr = [];
                $getAvailablevehicles = $this->searchQuery($matchThese,$weight,$offset,$loader_count,$postdata,$limit,$type='search',$book_op_id);
		if(isset($book_op_id)){
			$getAvailablevehicles;
		}
               elseif(count($getAvailablevehicles) == 30){
                    $getAvailablevehicles;
                }else{
                    if(!empty($getAvailablevehicles)){
                        $tempArr = $getAvailablevehicles;
                    }else{
                        $tempArr = [];
                    }
                    $limit = 30 - (count($getAvailablevehicles));
                    $getAvailablevehicles = $this->searchQuery($matchThese,$weight,$offset,$loader_count,$postdata,$limit,$type ='raw',$book_op_id);
                    if(!empty($getAvailablevehicles)){
                        $getAvailablevehicles;
                    }else{
                        $getAvailablevehicles = [];
                    }   
                    $getAvailablevehicles = array_merge($tempArr,$getAvailablevehicles);
                }
                if(!empty($getAvailablevehicles)){
                    $ResultVehicles = $this->modifySearchVehicleParam($getAvailablevehicles,$postdata);
                    $content = $ResultVehicles->getContent();
                    $resVehicles = json_decode($content, true);
                    if($resVehicles['response']['status']=='Failed'){
                        $msg = $resVehicles['response']['msg'];
                        $vehicles = null;
                    }else{
                        $vehicles = $resVehicles['response']['Vehicles'];
                        $collection = collect($vehicles);
                        $unique = $collection->unique('veh_id');
                        $vehicles = $unique->values()->all();
                        $vehicles['totaldistance'] = $resVehicles['response']['totaldistance'];
                        $vehicles['totaltime'] = $resVehicles['response']['totaltime'];
                        $msg = '';
                    }
                }else{
                    $msg = 'no vehicle found';
                    $vehicles = null;
                }
                return $vehicles;
            }else{
            	return 'Empty Request';
            }
    }

    //////////////////////////////////////////////////////////////////////////
    public function searchQuery($matchThese,$weight,$offset,$loader_count,$request,$limit,$type,$book_op_id){
        //query param
        $postdata = $request;
        $user_id = isset($postdata['userdetail']['user_id']) ? $postdata['userdetail']['user_id'] : null;
        $start_address_line_1 = isset($postdata['userdetail']['start_address_line_1']) ? $postdata['userdetail']['start_address_line_1'] : null;
        $start_address_lat = isset($postdata['userdetail']['start_address_lat']) ? $postdata['userdetail']['start_address_lat'] : null;
        $start_address_lng = isset($postdata['userdetail']['start_address_lng']) ? $postdata['userdetail']['start_address_lng'] : null;
        $dest_address_line_1 = isset($postdata['userdetail']['dest_address_line_1']) ? $postdata['userdetail']['dest_address_line_1'] : null;
        $dest_address_lat = isset($postdata['userdetail']['dest_address_lat']) ? $postdata['userdetail']['dest_address_lat'] : null;
        $dest_address_lan = isset($postdata['userdetail']['dest_address_lan']) ? $postdata['userdetail']['dest_address_lan'] : null;
        $material_type = isset($postdata['userdetail']['material_type']) ? $postdata['userdetail']['material_type'] : null;
        $vehicle_type = isset($postdata['userdetail']['vehicle_type']) ? $postdata['userdetail']['vehicle_type'] : null;
        $weight = isset($postdata['userdetail']['weight']) ? $postdata['userdetail']['weight'] : null;
        $loader_count = isset($postdata['userdetail']['loader_count']) ? $postdata['userdetail']['loader_count'] : null;
        $fuel_type = isset($postdata['userdetail']['vehicle_fuel_type']) ? $postdata['userdetail']['vehicle_fuel_type'] : null;
        $is_bidding = isset($postdata['userdetail']['user_bid_mode']) ? $postdata['userdetail']['user_bid_mode'] : null;
        $payment_mode = isset($postdata['userdetail']['payment_mode']) ? $postdata['userdetail']['payment_mode'] : null;
        $intermediate_address = isset($postdata['userdetail']['intermediate_address']) ? $postdata['userdetail']['intermediate_address'] : null;
        $matchThese = [
             'ggt_op_vehicles.veh_type' => $vehicle_type,
             'ggt_op_vehicles.veh_fuel_type' => $fuel_type,
             'ggt_op_vehicles.is_active' => 1,
        ];
        $loader_count = (int) $loader_count;
        
        if(isset($postdata['userdetail']['pickup_date']) && !empty($postdata['userdetail']['pickup_date']))
        {
            $order_date = implode('-', $postdata['userdetail']['pickup_date']);
        }else{
            $order_date = null;
            $response = ['status' => 'Failed', 'message' => 'Please specify date','statusCode' => Response::HTTP_BAD_REQUEST];
            return response()->json(['response' => $response]);
        }
        $order_time = date('H:i:s');
        if(isset($postdata['userdetail']['pickup_time']) && !empty($postdata['userdetail']['pickup_time']) && !empty($order_date) ){
            $ordertime = isset($postdata['userdetail']['pickup_time']) ? $postdata['userdetail']['pickup_time']:null;
            $order_time = date("H:i:s", strtotime($ordertime));
            $orderDT = date('Y-m-d H:i:s', strtotime("$order_date $order_time"));
        }else{
            $orderDT = null;
            $order_date = null;
        }   
        //end query param

        $dist = 10; //search vehicle within 10km radius
        if($type=='search'){
	    if(isset($book_op_id)){
            $getAvailablevehicles = OperatorVehicles::
            join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_op_vehicles.veh_op_id')
            ->select('ggt_op_vehicles.*','ggt_operator_users.op_user_id','ggt_operator_users.op_uid','ggt_operator_users.op_is_verified','ggt_operator_users.android_notification_token')
            ->whereNotNull('ggt_op_vehicles.veh_base_lat_lng')
	    ->where('ggt_operator_users.op_user_id',$book_op_id)
            ->where('ggt_op_vehicles.is_active',1)
            ->where('ggt_op_vehicles.veh_is_online',1)
            ->where('ggt_operator_users.op_is_blocked',0)
            ->where('ggt_op_vehicles.is_deleted',0)
            ->where('ggt_operator_users.op_is_verified',1)
            ->get()
            ->toArray();
	    }else{
		$getAvailablevehicles = OperatorVehicles::
            join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_op_vehicles.veh_op_id')
            ->select('ggt_op_vehicles.*','ggt_operator_users.op_user_id','ggt_operator_users.op_uid','ggt_operator_users.op_is_verified','ggt_operator_users.android_notification_token',
            DB::raw(
                "if(veh_last_location IS NOT NULL, 
                round(DEGREES(ACOS((sin(RADIANS(".$start_address_lat."))*sin(RADIANS(json_extract(replace(replace(veh_last_location, '[', ''), ']',''),'$.lati')))) + (cos(RADIANS(".$start_address_lat.")) *cos(RADIANS(json_extract(replace(replace(veh_last_location, '[', ''), ']',''),'$.lati'))) * cos(RADIANS(".$start_address_lng."- json_extract(replace(replace(veh_last_location, '[', ''), ']',''),'$.long')))))) * 111.13384,2),
                round(DEGREES(ACOS((sin(RADIANS(".$start_address_lat."))*sin(RADIANS(json_extract(replace(replace(veh_base_lat_lng, '[', ''), ']',''),'$.lati')))) + (cos(RADIANS(".$start_address_lat.")) *cos(RADIANS(json_extract(replace(replace(veh_base_lat_lng, '[', ''), ']',''),'$.lati'))) * cos(RADIANS(".$start_address_lng."- json_extract(replace(replace(veh_base_lat_lng, '[', ''), ']',''),'$.long')))))) * 111.13384,2)
            ) as ascircle_distance"))
            ->whereNotNull('ggt_op_vehicles.veh_base_lat_lng')
            ->where(function ($q) use($matchThese,$loader_count,$weight)
                {
                    $q->where($matchThese)
                    ->where('veh_capacity','>', $weight)
                    ->when($loader_count == 0,
                        function($q1) use($loader_count){
                            return $q1->whereRaw('(ggt_op_vehicles.veh_no_person IS NULL OR ggt_op_vehicles.veh_no_person >= '.$loader_count.') AND (ggt_operator_users.op_is_verified = 1)');
                        },function ($q1) use($loader_count) {
                            return $q1->where('ggt_op_vehicles.veh_no_person','>=',$loader_count);
                    });
                })
            ->where('ggt_op_vehicles.is_active',1)
            ->where('ggt_op_vehicles.veh_is_online',1)
            ->where('ggt_operator_users.op_is_blocked',0)
	    ->where('ggt_op_vehicles.is_deleted',0)
            ->where('ggt_operator_users.op_is_verified',1)
            ->groupBy('ggt_op_vehicles.veh_op_id')
            ->havingRaw('ascircle_distance < '.$dist)
            ->orderBy('ascircle_distance','ASC')
            ->limit($limit)
            ->get()
            ->toArray();

	    }
        }else{
            /* If request parameter does not match 
               Then list all nearest vehicle 
            */
            $getAvailablevehicles = null;
            $getAvailablevehicles = OperatorVehicles::
                join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_op_vehicles.veh_op_id')
                ->select('ggt_op_vehicles.*','ggt_operator_users.op_user_id','ggt_operator_users.op_uid','ggt_operator_users.op_is_verified','ggt_operator_users.android_notification_token',
                DB::raw(
                "if(veh_last_location IS NOT NULL, 
                round(DEGREES(ACOS((sin(RADIANS(".$start_address_lat."))*sin(RADIANS(json_extract(replace(replace(veh_last_location, '[', ''), ']',''),'$.lati')))) + (cos(RADIANS(".$start_address_lat.")) *cos(RADIANS(json_extract(replace(replace(veh_last_location, '[', ''), ']',''),'$.lati'))) * cos(RADIANS(".$start_address_lng."- json_extract(replace(replace(veh_last_location, '[', ''), ']',''),'$.long')))))) * 111.13384,2),
                round(DEGREES(ACOS((sin(RADIANS(".$start_address_lat."))*sin(RADIANS(json_extract(replace(replace(veh_base_lat_lng, '[', ''), ']',''),'$.lati')))) + (cos(RADIANS(".$start_address_lat.")) *cos(RADIANS(json_extract(replace(replace(veh_base_lat_lng, '[', ''), ']',''),'$.lati'))) * cos(RADIANS(".$start_address_lng."- json_extract(replace(replace(veh_base_lat_lng, '[', ''), ']',''),'$.long')))))) * 111.13384,2)
            ) as ascircle_distance"))
                ->whereNotNull('ggt_op_vehicles.veh_base_lat_lng')
                ->when($loader_count == 0,
                    function($q1) use($loader_count){
                        return $q1->whereRaw('(ggt_op_vehicles.veh_no_person IS NULL OR ggt_op_vehicles.veh_no_person >= '.$loader_count.') AND (ggt_operator_users.op_is_verified = 1)');
                        
                    },function ($q1) use($loader_count) {
                        return $q1->where('ggt_op_vehicles.veh_no_person','>=',$loader_count);
                })
                ->where('ggt_op_vehicles.is_active',1)
                ->where('ggt_op_vehicles.veh_is_online',1)
                ->where('ggt_operator_users.op_is_blocked',0)
                ->where('ggt_op_vehicles.is_deleted',0)
                ->where('ggt_operator_users.op_is_verified',1)
                ->groupBy('ggt_op_vehicles.veh_op_id')
                ->havingRaw('ascircle_distance < '.$dist)
                ->orderBy('ascircle_distance','ASC')
                ->limit($limit)
                ->get()
                ->toArray();
        }
        // $collection = collect($getAvailablevehicles);
        // $unique = $collection->unique('veh_op_id');
        // $getAvailablevehicles = $unique->values()->all();
//	dd($getAvailablevehicles);
	if(!isset($book_op_id)){
		$getAvailablevehicles = array_values(Arr::sort($getAvailablevehicles, function ($value) {
            	return $value['ascircle_distance'];
        	}));	
	}
        if(!empty($getAvailablevehicles)){
            foreach ($getAvailablevehicles as $key => $value) {
                if(!empty($value['veh_model_name'])){
                    $vehModelName = Vehicles::where('veh_id',$value['veh_model_name'])->value('veh_model_name');
                    $getAvailablevehicles[$key]['veh_model_name'] = $vehModelName;
                }
                else{
                    $getAvailablevehicles[$key]['veh_model_name'] = null;
                }
                if(!empty($value['veh_color'])){
                    $vehColorName = ColorMaster::where('id',$value['veh_color'])->value('name');
                    $getAvailablevehicles[$key]['veh_color_name'] = $vehColorName;
                }else{
                    $getAvailablevehicles[$key]['veh_color_name'] = null;
                }
                //get vehicle images
                $starttime = microtime(true);
                $value['vehicle_img_preview'] = null;
                $value['vehicle_img_type'] = null;
                $value['vehicle_img_name'] = null;
                
                if(!empty($value['veh_images'])){
                    $images = json_decode($value['veh_images']);
                    $rows = [];
                    // $saveAsPath = '/tmp/';
                    $dirPath = Config::get('custom_config_file.dir_path_admin_images');
                    $dir = 'vehicle-single-images/';
                    $saveAsPath1 = $dirPath.$dir;
                    //file not exist mkdir
                    /*
                    if(!file_exists($saveAsPath1))
                    {
                        mkdir($saveAsPath1, 0777, true);
                    }
                    */

                    $filename_array = explode('/', $images[0]);
                    $download_url = $filename_array[count($filename_array) - 1];
                    $filename = $this->aws->downloadFromS3($images[0], $saveAsPath1);
                    if(isset($filename)){
                        // $path = $saveAsPath1.$filename;
                        // $file = File::get($path);
                        // $type = File::mimeType($path);
                        // $fcontent= base64_encode($file);
                        $images['vehicle_img_preview'] = null;//$fcontent;
                        $images['vehicle_img_type'] = null;//$type;
                        $fname = explode('-', $filename);
                        $images['vehicle_img_name'] = end($fname);
                        // $img_frmt = 'data:'.$type.';base64,';
                        array_push($rows, [
                            'img_name' => $images['vehicle_img_name'],
                            'img_type' => $images['vehicle_img_type'],
                            'img_preview' => null,//$img_frmt.$images['vehicle_img_preview']
                            'img_url' => 'vehicle-single-images/'.$filename
                        ]);
                    }
                    else{
                        $value['vehicle_img_preview'] = null;
                        $value['vehicle_img_type'] = null;
                        $value['vehicle_img_name'] = null;
                    }
                    $getAvailablevehicles[$key]['veh_single_image'] = $rows;
                }else{
                    $getAvailablevehicles[$key]['veh_single_image'] = null;
                    $getAvailablevehicles[$key]['veh_single_image']['img_name']= null;
                    $getAvailablevehicles[$key]['veh_single_image']['img_type']= null;
                    $getAvailablevehicles[$key]['veh_single_image']['img_preview']= null;
                } //end get vehicle image
                
                $endtime = microtime(true); // Bottom of page
                $getAvailablevehicles[$key]['base64_image_time_sec'] = $endtime - $starttime;
            }
        }else{
            $getAvailablevehicles = null;
        }
        return $getAvailablevehicles;
    }
    //////////////////////////////////////////////////////////////////////////
    
    public function straightdistanceCalculationFormula($point1_lat,$point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2){
        // Calculate the distance in degrees
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
        
        // Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
        switch($unit) {
            case 'km':
                $distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
                break;
            case 'mi':
                $distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
                break;
            case 'nmi':
                $distance =  $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
        }
        return round($distance, $decimals);
    }
    
    // modify searched vehicle data
    public function modifySearchVehicleParam($getAvailablevehicles,$request){
        $postdata = $request;
        $user_id = isset($postdata['userdetail']['user_id']) ? $postdata['userdetail']['user_id'] : null;
        $start_address_line_1 = isset($postdata['userdetail']['start_address_line_1']) ? $postdata['userdetail']['start_address_line_1'] : null;
        $start_address_lat = isset($postdata['userdetail']['start_address_lat']) ? $postdata['userdetail']['start_address_lat'] : null;
        $start_address_lng = isset($postdata['userdetail']['start_address_lng']) ? $postdata['userdetail']['start_address_lng'] : null;
        $dest_address_line_1 = isset($postdata['userdetail']['dest_address_line_1']) ? $postdata['userdetail']['dest_address_line_1'] : null;
        $dest_address_lat = isset($postdata['userdetail']['dest_address_lat']) ? $postdata['userdetail']['dest_address_lat'] : null;
        $dest_address_lan = isset($postdata['userdetail']['dest_address_lan']) ? $postdata['userdetail']['dest_address_lan'] : null;
        $material_type = isset($postdata['userdetail']['material_type']) ? $postdata['userdetail']['material_type'] : null;
        $vehicle_type = isset($postdata['userdetail']['vehicle_type']) ? $postdata['userdetail']['vehicle_type'] : null;
        $weight = isset($postdata['userdetail']['weight']) ? $postdata['userdetail']['weight'] : null;
        $loader_count = isset($postdata['userdetail']['loader_count']) ? $postdata['userdetail']['loader_count'] : null;
        $fuel_type = isset($postdata['userdetail']['vehicle_fuel_type']) ? $postdata['userdetail']['vehicle_fuel_type'] : null;
        $is_bidding = isset($postdata['userdetail']['user_bid_mode']) ? $postdata['userdetail']['user_bid_mode'] : null;
        $payment_mode = isset($postdata['userdetail']['payment_mode']) ? $postdata['userdetail']['payment_mode'] : null;
        $intermediate_address = isset($postdata['userdetail']['intermediate_address']) ? $postdata['userdetail']['intermediate_address'] : null;
        $destination_locations = isset($postdata['userdetail']['DestinationLocations']) ? $postdata['userdetail']['DestinationLocations'] : null;
	/************** calculate distance start here ************/
	// Calculate distance in km for [<pickup>, <d1>, <d2>, <d3>,.......... <dn>]
                        $dest_lat_long = $destination_locations;
                        $distArr = array();
                        $distArr[] = array("lati"=> $start_address_lat, "longi"=> $start_address_lng);
                        foreach ($dest_lat_long as $dkey => $dvalue) {
                            $distArr[] = array("lati"=> $dvalue['dest_address_lat'], "longi"=> $dvalue['dest_address_lan']);
                        }
                        $totaldistanceKm = [];
                        $totaltimeMinKm = [];
                        for ($i=0; $i < sizeof($distArr) -1 ; $i++) {
                            $result_distance = $this->calculatedistance($distArr[$i]['lati'], $distArr[$i]['longi'], $distArr[$i+1]['lati'], $distArr[$i+1]['longi']);
                            $distanceKm = $result_distance['distance'] * 0.001;
                            array_push($totaldistanceKm,$distanceKm);
                            $total_minutes = floor($result_distance['travel_time'] / 60);
                            array_push($totaltimeMinKm,$total_minutes);
                        }
                        $total_distance = array_sum($totaldistanceKm);
                        $total_travel_time = array_sum($totaltimeMinKm);
                        //end calculate total distance

                        $total_distance = isset($total_distance) ? $total_distance : 0;
                        //$getAvailablevehicles[$key]['total_travel_time'] = isset($total_travel_time) ? $total_travel_time : null;                
                        if($distanceKm > 500){
                            $response = ['status' => 'Failed','msg' => 'Area is not serviceable','statusCode' => Response::HTTP_BAD_REQUEST];
                            return response()->json(['response' => $response]);
                        }

	/************** calculate distance end here *************/
        if(!empty($getAvailablevehicles)){
            foreach ($getAvailablevehicles as $key => $value){
                // Calculate straight line distance
                $veh_lat_long = json_decode($value['veh_base_lat_lng'],true);
                if(!empty($veh_lat_long[0]['lati']) && !empty($veh_lat_long[0]['long'])){
                    $veh_base_lat = $veh_lat_long[0]['lati'];
                    $veh_base_long = $veh_lat_long[0]['long'];
                    $straightdistanceKm = $this->straightdistanceCalculationFormula($start_address_lat, $start_address_lng, $veh_base_lat, $veh_base_long,$unit = 'km', $decimals = 2);
                    
                    if(!empty($start_address_lat) && !empty($start_address_lng) && !empty($dest_address_lat) && !empty($dest_address_lan))
                    { 
                        //calculate amount code start
                            $remaning_distance = $total_distance - 3;
                            $veh_base_charge_rate_per_km = json_decode($value['veh_base_charge_rate_per_km'],true);
                            /*$getAvailablevehicles[$key]['chargeperkm'] = $value['veh_base_charge'].'/'.$veh_base_charge_rate_per_km['veh_3km_15km']./.$veh_base_charge_rate_per_km['veh_above_15km'];*/
                            //case 1 : upto 3kms
                            if($total_distance <= 3 && $total_distance > 0){
                                $getAvailablevehicles[$key]['veh_trip_charge'] = $value['veh_base_charge'] ;
                            }
                            //case 2 : 3-15kms
                            elseif($total_distance > 3 && $total_distance <= 15){
                                $getAvailablevehicles[$key]['veh_trip_charge'] = (($total_distance -3) * $veh_base_charge_rate_per_km['veh_3km_15km']) + ( $value['veh_base_charge']);
                            }
                            //case 3 : above 15kms
                            elseif($total_distance > 15){
                                $getAvailablevehicles[$key]['veh_trip_charge']=0;
                                $getAvailablevehicles[$key]['veh_trip_charge'] = (($total_distance - 15 )* $veh_base_charge_rate_per_km['veh_above_15km']) + ((12* $veh_base_charge_rate_per_km['veh_3km_15km'])) + ( $value['veh_base_charge']);
                            }
                            //case 4 : distance is 0
                            elseif($total_distance <=0){
                            //case 5 : no match
                                $getAvailablevehicles[$key]['veh_trip_charge'] = $value['veh_base_charge'];
                            }else{
                                $getAvailablevehicles[$key]['veh_trip_charge'] = $value['veh_base_charge'];
                            }
                            //calculate loader chagre
                            if(!empty($loader_count) && !empty($value['veh_charge_per_person'])){   
                                $getAvailablevehicles[$key]['veh_loader_charge'] = $loader_count * $value['veh_charge_per_person'];
                            }else{
                                $getAvailablevehicles[$key]['veh_loader_charge'] = 0;
                            }
                            //end calculate loader charge
                        $getAvailablevehicles[$key]['total_distance'] = $total_distance;
                        $getAvailablevehicles[$key]['loader_amount'] = $getAvailablevehicles[$key]['veh_loader_charge'];
                        $getAvailablevehicles[$key]['base_amount'] = ($getAvailablevehicles[$key]['veh_trip_charge']);
                        //calculate trip arrival time
                        $veh_lat_long = json_decode($value['veh_base_lat_lng'],true);
                        /*if(!empty($veh_lat_long)){
                            $veh_base_lat = $veh_lat_long[0]['lati'];
                            $veh_base_long = $veh_lat_long[0]['long'];
                            $arrival_distance = $this->calculatedistance($start_address_lat,$start_address_lng,$veh_base_lat,$veh_base_long);
                            $getAvailablevehicles[$key]['trip_arrival_time'] = isset($arrival_distance['travel_time']) ? $arrival_distance['travel_time'] : null;
                        }else{
                            $getAvailablevehicles[$key]['trip_arrival_time'] = null;
                        }*/
                        $getAvailablevehicles[$key]['total_amount'] = $getAvailablevehicles[$key]['base_amount'] + ($getAvailablevehicles[$key]['veh_loader_charge']);

                        if(!empty($payment_mode)){
                            if($payment_mode == 'cash'){
                                $getAvailablevehicles[$key]['total_amount_factor'] = ($getAvailablevehicles[$key]['total_amount'] * $this->factor_cash);

                                $getAvailablevehicles[$key]['ggt_factor'] = ($getAvailablevehicles[$key]['total_amount_factor'] - $getAvailablevehicles[$key]['total_amount']);
                            }else{
                                $getAvailablevehicles[$key]['total_amount_factor'] = ($getAvailablevehicles[$key]['total_amount'] * $this->factor_digital);
                                $getAvailablevehicles[$key]['ggt_factor'] = ($getAvailablevehicles[$key]['total_amount_factor'] - $getAvailablevehicles[$key]['total_amount']);
                            }
                        }
                        
                        //get driver name
                        /*$getDriverNameNumber = Driver::
                        select('ggt_drivers.driver_id','ggt_drivers.op_user_id','ggt_drivers.driver_first_name','ggt_drivers.driver_last_name','ggt_drivers.driver_mobile_number')*/
			$getDriverNameNumber = Driver::
                        join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_drivers.op_user_id')
                        ->select('ggt_drivers.op_user_id','ggt_drivers.driver_first_name','ggt_drivers.driver_last_name','ggt_drivers.driver_mobile_number','ggt_drivers.driver_id','ggt_operator_users.op_first_name','ggt_operator_users.op_last_name','ggt_operator_users.op_mobile_no')
                        ->where('ggt_operator_users.op_user_id',$value['veh_op_id'])
                        ->where('ggt_drivers.driver_is_online',1)
			//->where('ggt_drivers.is_active',1)
                        ->get()
                        ->toArray();
                        if(!empty($getDriverNameNumber)){
                            $getAvailablevehicles[$key]['drivers'] = $getDriverNameNumber;
                        }else{
                            $getAvailablevehicles[$key]['drivers'] = null;
                        }
                        //get driver name end
                    }else{
                        $response = ['status' => 'Failed','msg' => 'pin or destination location is not correct','statusCode' => Response::HTTP_BAD_REQUEST];
                        return response()->json(['response' => $response]);
                    }
                }else{
                    //no veh base lat long found
                }
            }
            $response = ['status' => 'success', 'Vehicles' => $getAvailablevehicles, 'totaldistance' => $total_distance,'totaltime' => $total_travel_time,'statusCode' => Response::HTTP_OK];
            return response()->json(['response' => $response]);
        }
    }
    // modify searched vehicle data

    public function calculatedistance($point1_lat,$point1_long, $point2_lat, $point2_long){
       // $url_distance = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$point1_lat.",".$point1_long."&destinations=".$point2_lat.",".$point2_long."&departure_time=now&mode=driving&language=pl-PL&key=AIzaSyArZ5oTxpSuxQhaaNyJmKK94fPLKynjVPk";
	//$url_distance = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$point1_lat.",".$point1_long."&destinations=".$point2_lat.",".$point2_long."&departure_time=now&mode=driving&language=pl-PL&key=AIzaSyBOXc9fpTDGLvxYBts4YEazCvFU4f4E-wU";
	
	//here.com api to calculate distance
        $url_distance = "https://route.ls.hereapi.com/routing/7.2/calculateroute.json?waypoint0=".$point1_lat.",".$point1_long."&waypoint1=".$point2_lat.",".$point2_long."&mode=fastest%3Bcar%3Btraffic%3Aenabled&departure=now&apiKey=gY9qIXIbdKsBf9YAza5mCJnPgAxfr9h2y99dQQ_J6EU";

    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_distance);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response,true);
	//distance and time from google api response
        /*$dist = isset($response_a['rows'][0]['elements'][0]['distance']['value']) ? $response_a['rows'][0]['elements'][0]['distance']['value'] : null;
        $dist = $dist + Config::get('custom_config_file.notification_url');
        $travel_time = isset($response_a['rows'][0]['elements'][0]['duration_in_traffic']['value']) ? $response_a['rows'][0]['elements'][0]['duration_in_traffic']['value'] : null;
	//$status = $response_a['status'];
	*/
	if(isset($response_a['response'])){
	$dist = $response_a['response']['route'][0]['summary']['distance'];
	$travel_time = $response_a['response']['route'][0]['summary']['travelTime'];
	return array('distance' => $dist,'travel_time' => $travel_time);
        }
	else{
	    return FALSE;	
        }
    }
    //end of calculate a distance

    //update notification token of admin
    public function updateToken(Request $request){
    	$postdata = $request->all();
    	if(!empty($postdata)){
    		$user = Auth::getUser();
            $admin_id = $user->id;
            if(!empty($admin_id)){
            	$updatetoken = User::where('id','=',$admin_id)            
            	->update(['notification_token' => isset($postdata['token']) ? $postdata['token'] : null ]); 
            	if($updatetoken){
            		return json_encode(['status' => 'success', 'message' => 'Token Updated Successfully!']);
            	}else{
            		return json_encode(['status' => 'failed', 'message' => 'token not updated!']);	
            	} 
            }else{
            	return json_encode(['status' => 'failed', 'message' => 'admin not found!']);
            }
    	}else{
    		return json_encode(['status' => 'failed', 'message' => 'Empty Request!']); 
    	}
    }
    //end here

    //create order and send notification
    public function CustomerRegistrationDetail(Request $request)
    {
	Session::forget('editTripData');Session::forget('editTripIsBooked');
       Session::forget('editTripIsSaved');
       if ($request->ismethod('post')) {
           	$postdata = $request->all();
            if (!empty($postdata)) 
            {
            	$postdata['userdetail'] = json_decode(base64_decode($postdata['userdetail']),true);
                $postdata['vehicledetail'] = json_decode(base64_decode($postdata['vehicledetail']),true);
                //update notification token of user
                $userId = isset($postdata['userdetail']['user_id']) ? $postdata['userdetail']['user_id']:null;
                $token = isset($postdata['usernotificationtoken']) ? $postdata['usernotificationtoken']:null;
                if($userId != null){
                    $user_notification_token = Customer::where('user_id', $userId)->update(['user_notification_token' => $token]);
                }
                // 
                $is_bid = isset($postdata['userdetail']['user_bid_mode']) ? $postdata['userdetail']['user_bid_mode']:null;
                if((!empty($postdata['userdetail']['DestinationLocations']))) {
                    $destinationArr = end($postdata['userdetail']['DestinationLocations']);
                }else{
                    $destinationArr = null;
                }

                if(!empty($postdata['userdetail']['DestinationLocations']))
                {
                    $intermediate_address = json_encode($postdata['userdetail']['DestinationLocations']);
                }
                else
                {
                    $intermediate_address = null;
                }
                if(isset($postdata['userdetail']['pickup_date']) && !empty($postdata['userdetail']['pickup_date']))
                {
                    $order_date = implode('-', $postdata['userdetail']['pickup_date']);
                }else{
                    $order_date = null;
                    $response = ['status' => 'Failed', 'message' => 'Please specify date','statusCode' => Response::HTTP_BAD_REQUEST];
                    return response()->json(['response' => $response]);
                }
                $order_time = date('H:i:s');
                if(isset($postdata['userdetail']['pickup_time']) && !empty($postdata['userdetail']['pickup_time']) && !empty($order_date) ){
                    $ordertime = isset($postdata['userdetail']['pickup_time']) ? $postdata['userdetail']['pickup_time']:null;
                    $order_time = date("H:i:s", strtotime($ordertime));
                    $combinedDT = date('Y-m-d H:i:s', strtotime("$order_date $order_time"));
                }else{
                    $combinedDT = null;
                    $order_date = null;
                    $response = ['status' => 'Failed', 'message' => 'Please specify correct date & time','statusCode' => Response::HTTP_BAD_REQUEST];
                    return response()->json(['response' => $response]);
                }   
                //create unique transaction id
                $currenttimestamp = $trip_transaction_id = null;
                $currenttimestamp = date('Y/m/d H:i:s');
                $currenttimestamp = strtotime($currenttimestamp);
                $trip_transaction_id = "TN".$currenttimestamp;

                /* create arrival and total time */
                $arrival_time = isset($postdata['vehicledetail']['trip_arrival_time'])?$postdata['vehicledetail']['trip_arrival_time']:null;
                if(!empty($arrival_time)){
                    $arrival_min = str_replace('min','',$arrival_time);
                    $arrival_min = (int) $arrival_min;
                    $arrivalTime = date("H:i", mktime(0,$arrival_min));
                }else{
                    $arrivalTime = date('H:i');
                }
                $total_time = isset($postdata['vehicledetail']['total_travel_time'])?$postdata['vehicledetail']['total_travel_time']:null;
                if(!empty($total_time)){
                    $travel_min = str_replace('min','',$total_time);
                    $travel_min = (int) $travel_min;
                    $totalTime = date("H:i", mktime(0,$travel_min));
                }else{
                    $totalTime = date('H:i');
                }
                // trip_arrival_time
                $userTripId = isset($postdata['vehicledetail']['userTripId'])? $postdata['vehicledetail']['userTripId'] : null;
		//check empty driver
                if($postdata['vehicledetail']['veh_driver_id'] == 1){
                	$getDriverId = Driver::select('driver_id')->where('op_user_id',$postdata['vehicledetail']['veh_op_id'])->first();
                    if($getDriverId){
                            $getDriverId = $getDriverId->driver_id;
                    }
                }else if(isset($postdata['vehicledetail']['veh_driver_id'])){
                    $getDriverId = $postdata['vehicledetail']['veh_driver_id'];
             
                }
                else{   
                    $getDriverId = Driver::select('driver_id')->where('op_user_id',$postdata['vehicledetail']['veh_op_id'])->first();
                    if($getDriverId){
                            $getDriverId = $getDriverId->driver_id;
                    }
                }
                //end here
		$ggtAdjustments = $postdata['cust_adjust'] - $postdata['op_adjust'];
                if(empty($userTripId)){
                        // create booking entry (case1 : without bid)
                        $input = array(
                            'user_id'=> isset($postdata['userdetail']['user_id']) ? $postdata['userdetail']['user_id']:null,
                            'op_driver_id'=> $getDriverId,
                            'op_veh_id'=> isset($postdata['vehicledetail']['veh_id']) ? $postdata['vehicledetail']['veh_id']:null,
                            'op_id'=> isset($postdata['vehicledetail']['veh_op_id']) ? $postdata['vehicledetail']['veh_op_id'] : null,                    
                            'start_address_line_1'=> isset($postdata['userdetail']['PickupLocations'][0]['start_address_line_1']) ? $postdata['userdetail']['PickupLocations'][0]['start_address_line_1'] : null,            
                            'start_address_line_2'=> isset($postdata['userdetail']['PickupLocations'][0]['start_address_line_2']) ? $postdata['userdetail']['PickupLocations'][0]['start_address_line_2'] : null,       
                            'start_address_line_3'=> isset($postdata['userdetail']['PickupLocations'][0]['start_address_line_3']) ? $postdata['userdetail']['PickupLocations'][0]['start_address_line_3'] : null,         
                            'start_address_line_4'=> isset($postdata['userdetail']['PickupLocations'][0]['start_address_line_4']) ?$postdata['userdetail']['PickupLocations'][0]['start_address_line_4'] : null,      
                            'start_pincode'=> isset($postdata['userdetail']['PickupLocations'][0]['pickup_address_pin']) ? $postdata['userdetail']['PickupLocations'][0]['pickup_address_pin'] : null,                    
                            'start_address_lat'=> isset($postdata['userdetail']['PickupLocations'][0]['start_address_lat']) ? $postdata['userdetail']['PickupLocations'][0]['start_address_lat'] :null, 
                            'start_address_lan'=> isset($postdata['userdetail']['PickupLocations'][0]['start_address_lng']) ? $postdata['userdetail']['PickupLocations'][0]['start_address_lng']:null,
                            'dest_address_line_1'=> isset($destinationArr['dest_address_line_1']) ? $destinationArr['dest_address_line_1'] : null,
                            'dest_address_line_2'=> isset($destinationArr['dest_address_line_2']) ? $destinationArr['dest_address_line_2'] : null,
                            'dest_address_line_3'=> isset($destinationArr['dest_address_line_3']) ? $destinationArr['dest_address_line_3'] : null,
                            'dest_address_line_4'=> isset($destinationArr['dest_address_line_4']) ? $destinationArr['dest_address_line_4'] : null,
                            'dest_pincode'=>  isset($destinationArr['delivery_address_pin']) ? $destinationArr['delivery_address_pin'] : null,
                            'dest_address_lat'=> isset($destinationArr['delivery_address_lat']) ? $destinationArr['delivery_address_lat'] : null,
                            'dest_address_lan'=> isset($destinationArr['delivery_address_lng']) ? $destinationArr['delivery_address_lng'] : null,
                           'intermediate_address'=> isset($intermediate_address) ? $intermediate_address :null,           
                            'material_type'=> isset($postdata['userdetail']['material_type']) ? $postdata['userdetail']['material_type']:null,                   
                            'vehicle_type'=> isset($postdata['userdetail']['vehicle_type']) ? $postdata['userdetail']['vehicle_type']:null,                   
                            'vehicle_fuel_type'=> isset($postdata['userdetail']['vehicle_fuel_type']) ? $postdata['userdetail']['vehicle_fuel_type']:null,              
                            'weight'=> isset($postdata['userdetail']['weight']) ? $postdata['userdetail']['weight']:null,                         
                            'is_bid'=> isset($postdata['userdetail']['user_bid_mode']) ? $postdata['userdetail']['user_bid_mode']:null,
                            'loader_count'=> isset($postdata['userdetail']['loader_count']) ? $postdata['userdetail']['loader_count']:null,                    
                            'loader_price'=> isset($postdata['vehicledetail']['veh_charge_per_person']) ? $postdata['vehicledetail']['veh_charge_per_person']:null,                    
                            'payment_type'=> isset($postdata['userdetail']['payment_mode']) ? $postdata['userdetail']['payment_mode']:null, 
                            // 'base_amount' =>isset($postdata['vehicledetail']['veh_base_charge'])?$postdata['vehicledetail']['veh_base_charge']:0,
                            // 'actual_amount' => isset($postdata['vehicledetail']['total_amount'])?$postdata['vehicledetail']['total_amount']:0,
                            'book_date' => $combinedDT,
                            'ride_status'=> 'pending',
                            'destinations_completed'=> null,
                            'total_distance' => isset($postdata['vehicledetail']['total_distance']) ? $postdata['vehicledetail']['total_distance']:null,
                            'arrival_time' => $arrivalTime,
                            'total_time' => $totalTime,
                            'trip_transaction_id' => $trip_transaction_id,
                            'base_amount' =>isset($postdata['vehicledetail']['base_amount']) ? $postdata['vehicledetail']['base_amount']:0,
                            'actual_amount' => isset($postdata['vehicledetail']['total_amount_factor']) ? $postdata['vehicledetail']['total_amount_factor']:0, //total_amount
                            'ggt_factor' => isset($postdata['vehicledetail']['ggt_factor']) ? $postdata['vehicledetail']['ggt_factor']:0,
                            'booking_step' => isset($postdata['userdetail']['booking_step']) ? $postdata['userdetail']['booking_step']:null,
			    'user_adjustment' => $postdata['cust_adjust'],
                            'op_adjustment' => $postdata['op_adjust'],
                            'ggt_adjustment' => $ggtAdjustments,
                        );
                        $userDetail = CustomerBookTrip::create($input);
                        if(!empty($userDetail) && $is_bid==0){
                            $availableUsers = Operator::select('android_notification_token')->where('op_user_id',$postdata['vehicledetail']['veh_op_id'])->get()->toArray();
                             // $availableUsers = User::select('op_notification_token')->where('op_mobile_no',9860138618)->get()->toArray();
                            //$availableUsers = User::select('op_notification_token')->where('op_mobile_no',9011190111)->orWhere('op_mobile_no',9940044004)->orWhere('op_mobile_no',9001290012)->orWhere('op_mobile_no',8499127688)->where('op_mobile_no',7758989559)->get()->toArray();
                            $to = array();
                            foreach ($availableUsers as $key => $value) {
                                array_push($to, $value['android_notification_token']);
                            }
			    $user = Auth::getUser();
                            $admin_id = $user->id;
                            unset($postdata['userdetail']['intermediate_address']);
                            unset($postdata['vehicledetail']['veh_images']);
                            unset($postdata['vehicledetail']['veh_single_image']);
                            //store message payload
                            $postdata['userdetail']['trip_transaction_id'] = $trip_transaction_id;
			    $postdata['userdetail']['booking_from'] = 'admin';
			    $postdata['userdetail']['admin_id'] = $admin_id;
			    $postdata['vehicledetail']['base_amount'] = $postdata['vehicledetail']['base_amount'] + ($postdata['op_adjust']);
                            $postdata['vehicledetail']['total_amount_factor'] = $postdata['vehicledetail']['total_amount_factor'] + ($postdata['cust_adjust']);
                            $postdata['vehicledetail']['total_amount'] = $postdata['vehicledetail']['total_amount'] + ($postdata['op_adjust']);
                            $postdata['vehicledetail']['veh_trip_charge'] = $postdata['vehicledetail']['veh_trip_charge'] + ($postdata['op_adjust']);
                            $data = [
                                'booking_details' => $postdata['userdetail'],
                                'vehicle_detail' => $postdata['vehicledetail'],
                                'user_id' => null,
                                'notification_types' => 'enquiry-notification',
                            ]; 
                            $msgdata = array(
                                'title' => 'Enquiry Notification',
                                'message' => 'You Have New Booking Enquiry',
                                'message_type' => 'ENQ',
                                'message_view_id' => $userDetail->id,
                                'message_pattern' => 'C-D',
                                'message_sender_id' => isset($postdata['userdetail']['user_id']) ? $postdata['userdetail']['user_id']:null,
                                'message_from' => 'customer',
                                'url' => Config::get('custom_config_file.notification_url'),
                                'message_payload' => json_encode($data),
                            );
                            $createNotification = DriverNotificationMessages::create($msgdata);
                            $messagedata = array(
                                'op_user_id' => isset($postdata['vehicledetail']['veh_op_id']) ? $postdata['vehicledetail']['veh_op_id'] : null,
                                'message_id' => $createNotification->notification_msg_id,
                                'message_receiver_id' => isset($postdata['vehicledetail']['veh_op_id']) ? $postdata['vehicledetail']['veh_op_id'] : null,
                            );
                            $notification = DriverNotifications::create($messagedata);
                            $notiId = ['id'=>$createNotification->notification_msg_id];
                            //end here
                            $token = $to;
                            $mytime = Carbon::now();
                            $mytime = $mytime->toDateTimeString();
			    
                            $data = [
                                //'title'=>'User Booking::'.$notiId,
                                'title' => 'User Booking',
                                'body'=>'Request For Booking',
                                'sound'=> 'default',
                                'icon' => '/assets/icons/notification-icon.png',
                                'vibrate'=>[1000, 1000, 1000, 1000, 1000],
                                'notification_types' => 'enquiry-notification',
                                'id' => $createNotification->notification_msg_id,
                                'notiTime' => $mytime,
                                'booking_from' => 'admin',
				'admin_id' => $admin_id,
                                'click_action'=> '.SOMEACTIVITY'
                            ];
                            $payload = [
                                'registration_ids' => $token,
                                //'notification' => $notification,
                                'data' => $data,
                                'priority' => 'high',
                                'fcmId' => 'android'
                            ];
                            $pushNotification = $this->notification->sendPushNotification($payload);
                            //update message response
                            if($pushNotification != 'error'){
                                $updateUserAccount = DriverNotificationMessages::where('notification_msg_id',$createNotification->notification_msg_id)->update([
                                    'message_response' => $pushNotification,
                                ]);
                            }
                            //end here
                        }
                }else{
                    //sent confirm notification (case 2 : bid)
                    // $userDetail = CustomerBookTrip::where("id",$userTripId)->first();
                    $userDetail = CustomerBookTrip::select('id','user_id','op_driver_id','op_veh_id','op_id','pay_order_id','book_date','start_address_line_1','intermediate_address','is_bid','loader_count','loader_price','material_type','payment_type')->where("id",$userTripId)->first();
                    if(!empty($userDetail) && $is_bid==1){
                        /* 
                            update amount and other_values 
                        */
                        $updatedata =  array(
                            'base_amount' => isset($postdata['vehicledetail']['base_amount']) ? $postdata['vehicledetail']['base_amount']:null,
                            'actual_amount' => isset($postdata['vehicledetail']['total_amount_factor']) ? $postdata['vehicledetail']['total_amount_factor']:null, //total_amount + loader_amount * factor
                            'ggt_factor' => isset($postdata['vehicledetail']['ggt_factor']) ? $postdata['vehicledetail']['ggt_factor']:null,
                            'total_distance' =>isset($postdata['vehicledetail']['total_distance']) ? $postdata['vehicledetail']['total_distance']:null,
                            'loader_price' =>isset($postdata['vehicledetail']['veh_charge_per_person']) ? $postdata['vehicledetail']['veh_charge_per_person']:null,
                            'arrival_time' => $arrivalTime,
                            'total_time' => $totalTime,
                        );
                        $updateTripInfo = CustomerBookTrip::where("id",$userTripId)->update($updatedata);

                        // end update amount and other values
                        $availableUsers = User::select('op_notification_token')->whereNotNull('op_notification_token')->where('is_active',1)->get()->toArray();
                        //$availableUsers = User::select('op_notification_token')->where('op_mobile_no', 9517534862)->orWhere('op_mobile_no', 9860138760)->get()->toArray();

                        $to = array();
                        foreach ($availableUsers as $key => $value) {
                            array_push($to, $value['op_notification_token']);
                        }
                        $token = $to;
                        $notification = [
                            'title'=>'User Confirm Booking ',
                            'body'=>'Confirm Booking',
                            // 'sound' => 'default',
                            'icon' => '/assets/icons/notification-icon.png',
                            'click_action' => Config::get('custom_config_file.notification_url').'notificationdetails',
                            // 'click_action' => 'https://partners.gogotrux.com/#/notificationdetails',
                        ];
                        $data = [
                            'booking_details' => $userDetail,
                            'vehicle_detail' => $postdata['vehicledetail'],
                            'user_id' => null,
                            'notification_types' => 'confirm-booking-notification',
                        ]; 
                        $payload = [
                            'registration_ids' => $token,
                            'notification' => $notification,
                            'data' => $data,
                        ];
                        $this->sendPushNotification($payload);
                    }
                }
               
                $response = ['status' => 'success', 'userDetail' => $userDetail, 'statusCode' => Response::HTTP_OK];
                return response()->json(['response' => $response]);
            }
            else
            {
                $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
                return response()->json(['response' => $response]);
            }
        }else
        {
            $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
            return response()->json(['response' => $response]);
        }
    }

    /*--------------------------get all images on view-----------------*/
    public function getVehicleAllImages($id)
    {
        if (!empty($id)) {
            $vehicles = OperatorVehicles::select('veh_images')->where('veh_id', $id)->first();
            if(!empty($vehicles)) {
                if (isset($vehicles['veh_images'])) {
                    $images = json_decode($vehicles['veh_images']);
                    $rows = [];
                    $row = [];
                    foreach ($images as $key => $imgs) {
                        //$saveAsPath = '/tmp/';
                        $dir = Config::get('custom_config_file.dir_path_admin_images');
                        $saveAsPath1 = $dir.'vehicle-images/';
                        $filename_array = explode('/', $imgs);
                        $download_url = $filename_array[count($filename_array) - 1];
                        $tempFileName = end($filename_array);
                        $filename = $this->aws->downloadFromS3($imgs, $saveAsPath1);
                        if (isset($filename)) {
                            //$path = $saveAsPath . $filename;
                            $path1 = $saveAsPath1 . $filename;
                            $file = File::get($path1);
                            $type = File::mimeType($path1);
                            $fcontent = base64_encode($file);
                            $images['vehicle_img_preview'] = $fcontent;
                            $images['vehicle_img_type'] = $type;
                            $fname = explode('-', $filename);
                            $images['vehicle_img_name'] = end($fname);
                            $img_frmt = 'data:' . $type . ';base64,';
                            array_push($rows, [
                                'img_name' => $images['vehicle_img_name'],
                                'img_type' => $images['vehicle_img_type'],
                                'img_preview' =>null, //$img_frmt . $images['vehicle_img_preview'],
                                'img_url' => 'vehicle-images/'.$filename
                            ]);
                            /*array_push($row,
                              $images['vehicle_img_name']
                            );*/

                        } else {
                            $vehicles['vehicle_img_preview'] = null;
                            $vehicles['vehicle_img_type'] = null;
                            $vehicles['vehicle_img_name'] = null;
                        }
                        $vehicles['vehicle_pic_url'] = $saveAsPath1 . $tempFileName;

                    }
                    $db_imgs = explode(',', $vehicles['veh_images']);
                    $vehicles['veh_img_names'] = $db_imgs;
                    $vehicles['veh_img_data'] = $rows;
                    
                    $response = ['status' => 'success', 'vehicles' => $vehicles, 'statusCode' => Response::HTTP_OK];
                    return response()->json(['response' => $response]);

                } else {
                    $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
                    return response()->json(['response' => $response]);
                }
            }else{
                $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
                return response()->json(['response' => $response]);
            }
        } else {
            $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
            return response()->json(['response' => $response]);
        }       
    }
    /*--------------------------end here-------------------------------*/

    /*--------------------------make trip payment---------------------*/
    public function makeTripPayment(Request $request){
    	$postData = $request->all();
    	if(!empty($postData)){
    		$notificationId = $postData['notifiId'];
    		$invoiceId = $postData['ispaylinksent'];
		if($postData['israzorpay'] == 'cash'){
			$notificationDetails = UserNotificationMessages::where('notification_msg_id',$notificationId)->first();
			$notificationDetails = json_decode($notificationDetails->message_payload,true);
	            	//$bookdata = json_decode($notificationDetails['booking_details'],true);
			$bookdata = $notificationDetails['booking_details'];
			//$vehdata = json_decode($notificationDetails['vehicle_detail'],true);
			$vehdata = $notificationDetails['vehicle_detail'];
    			//$formOtp = $postData['cashotp'];
    			
    			
				$data = [
	                        'user_id' => $notificationDetails['user_id'],
	                        'user_order_amount' => $vehdata['total_amount_factor'],
				'user_order_pay_mode' => 'cash',
	                        'user_order_status' => 'pending'
	                ];
	                $userPaylink = UserPayments::create($data);
	                if($userPaylink){
				$tranx_id = $this->generateCustomerPaymentTransactionID();
                		$update_id = UserPayments::where('user_order_id', $userPaylink->user_order_id)->update(['user_order_transaction_id' => $tranx_id]);
	                	$updateOrderId = CustomerBookTrip::where('trip_transaction_id','=',$bookdata['trip_transaction_id'])            
	            			->update(['payment_type' => 'cash','pay_order_id' => $userPaylink['user_order_id'],'trip_notification_id' => $notificationId]);
	                }
    				$returnResponse = ['status' => 'otpsuccess', 'message' => 'correct otp','statusCode' => Response::HTTP_BAD_REQUEST];
		        	return response()->json(['response'=> $returnResponse]);
    			
			//$returnResponse = ['status' => 'Failed', 'message' => 'cash payment','statusCode' => Response::HTTP_BAD_REQUEST];
		        //return response()->json(['response'=> $returnResponse]); 
    		}elseif(!empty($invoiceId)){
    			$response = $this->sendPaymentLink($invoiceId);
    		}else{
    			if(!empty($notificationId)){
    			//get trip data
    			try{
	            	$notificationDetails = UserNotificationMessages::where('notification_msg_id',$notificationId)->first();
	            	if(!empty($notificationDetails)){
	                	$notificationDetails = json_decode($notificationDetails->message_payload,true);
				$getbooktype = gettype($notificationDetails['booking_details']);
				if($getbooktype == 'string'){
	                	$bookdata = json_decode($notificationDetails['booking_details'],true);
	                	$vehdata = json_decode($notificationDetails['vehicle_detail'],true);
				}else{
				$bookdata = $notificationDetails['booking_details'];
                                $vehdata = $notificationDetails['vehicle_detail'];
				}
	                	if(!empty($bookdata)){
	                		//create unique receipt id
			                $currenttimestamp = $receipt = null;
			                $currenttimestamp = date('Y/m/d H:i:s');
			                $currenttimestamp = strtotime($currenttimestamp);
			                $receipt = "RC".$currenttimestamp;
			                $amt = round($vehdata['total_amount_factor'], 2);
			                $amt = $amt * 100;
	                		$data = [
								'customer' => [
									'name'=> $bookdata['cust_name'],
							    	'contact'=> $bookdata['cust_mobile'],
								],
								'type' => 'link',
								'view_less' => 1,
								'amount' => $amt,
								'currency' => 'INR',
								'description'=> 'Trip Payment.',
							  	'receipt'=> $receipt,
							  	'reminder_enable'=> true,
							  	'sms_notify'=> 1,
							  	'email_notify'=> 1,
							  	'expire_by'=> 1793630556,
							  	'callback_url'=> 'https://gogotrux.com/',
							  	'callback_method'=> "get"
							];
							$response = $this->getRazorpayPaymentLink($data);
							$response = json_decode($response,true);
							if(isset($response['id'])){
		                        //save response
		                        $data = [
		                                'user_id' => $notificationDetails['user_id'],
		                                'user_order_paylink_id' => $response['id'],
						'user_order_amount' => $vehdata['total_amount_factor'],
                        			'user_order_pay_mode' => 'digital',
						'user_order_status' => 'pending',
		                                'user_paylink_response' => json_encode($response),
		                        ];
	                        	$userPaylink = UserPayments::create($data);
	                        	if($userPaylink){
	                        		$updateOrderId = CustomerBookTrip::where('trip_transaction_id','=',$bookdata['trip_transaction_id'])            
	            					->update(['payment_type' => 'digital','pay_order_id' => $userPaylink['user_order_id'], 'is_paylink_send' => 1,'trip_notification_id' => $notificationId]); 
	                        	}else{

	                        	}
	                        	$returnResponse = ['status' => 'Success', 'linkid' => $response['id'],'statusCode' => Response::HTTP_OK];
	                			return response()->json(['response'=>$returnResponse]);
	                		}else{
	                			$returnResponse = ['status' => 'Failed', 'message' => 'empty response from payment gateway','statusCode' => Response::HTTP_BAD_REQUEST];
	                			return response()->json(['response'=> $returnResponse]);
	                		}
	                	}else{
	                		$returnResponse = ['status' => 'Failed', 'message' => 'notification data not found','statusCode' => Response::HTTP_BAD_REQUEST];
	                		return response()->json(['response'=> $returnResponse]);
	                	}   
	            	}else{
	                	$returnResponse = ['status' => 'Failed', 'message' => 'notification data not found','statusCode' => Response::HTTP_BAD_REQUEST];
	                	return response()->json(['response'=> $returnResponse]);
	            	}
	        	}catch(Exception $e){
	            	Log::error($e);
	            	return $e;
	        	} 
	    		}else{
	    			//return empty notification id
	    			$returnResponse = ['status' => 'Failed', 'message' => 'empty notification id','statusCode' => Response::HTTP_BAD_REQUEST];
		        	return response()->json(['response'=> $returnResponse]);
	    		}
    		}
    	}else{
    		$returnResponse = ['status' => 'Failed', 'message' => 'empty request','statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
    	}
    }

    public function getRazorpayPaymentLink($fields) {
		Log::info('fields: ', $fields); Log::warning($this->razor_payment_link_api);
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->razor_payment_link_api);
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
    /*--------------------------make trip payment end here---------------------*/

    /*--------------------------get trip link payment response---------------------*/
    public function getPaymentLinkResponse($invoiceId,$notiId){
		$api = new Api($this->razor_key, $this->razor_secret);
        $link = $api->invoice->fetch($invoiceId);
        if(isset($link['payment_id'])){
		$notificationDetails = UserNotificationMessages::where('notification_msg_id',$notiId)->first();
    		$notificationDetails = json_decode($notificationDetails->message_payload,true);
		$getbooktype = gettype($notificationDetails['booking_details']);
                if($getbooktype == 'string'){
	        $bookdata = json_decode($notificationDetails['booking_details'],true);
		}else{
		$bookdata = $notificationDetails['booking_details'];
		}

        	$pay_order_id = CustomerBookTrip::select('pay_order_id')->where('trip_transaction_id',$bookdata['trip_transaction_id'])->get()->first();
		//add invoice details start
            	$getMaxCustInv = CustomerBookTrip::max('cust_invoice_no');
            	if($getMaxCustInv){
               		$getMaxCustInv++;
            	}else{
                	$getMaxCustInv = 'CINV000001';
            	}
            	//end here
            	$updateTrip = CustomerBookTrip::where('trip_transaction_id',$bookdata['trip_transaction_id'])->update([
                	'cust_invoice_no' => $getMaxCustInv,
            	]);
        	$updatePayment = UserPayments::where('user_order_id',$pay_order_id->pay_order_id)->update([
	                'user_order_status' => 'approved',
	            ]);
        	$returnResponse = ['status' => 'Success', 'payId' => $link['payment_id'], 'payDate' => $link['paid_at'], 'statusCode' => Response::HTTP_OK];
	    	return response()->json(['response'=>$returnResponse]);
        }else{
        	$returnResponse = ['status' => 'Failed', 'message' => 'not paid yet','statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
        }
    }
    /*--------------------------get trip link payment end here---------------------*/

    public function sendPaymentLink($fields){
  		$url = $this->razor_payment_link_api.$fields.'/notify_by/sms';
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_USERPWD, $this->razor_key . ':' . $this->razor_secret);

	    $response = curl_exec ($ch);
	    $err = curl_error($ch);  //if you need
	    curl_close ($ch);
	    return $response;
	}

	public function sendTripInvoice(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			//get trip data from notification id
			$tripDetails = UserNotificationMessages::select('message_payload')->where('notification_msg_id', $postData['notiId'])->get()->first();
			$tripDetails = json_decode($tripDetails->message_payload, true);
			$getbooktype = gettype($tripDetails['booking_details']);
                        if($getbooktype == 'string'){
			$tripBookDetails = json_decode($tripDetails['booking_details'], true);
			$tripVehDetails = json_decode($tripDetails['vehicle_detail'], true);
			}else{
			$tripBookDetails = $tripDetails['booking_details'];
                        $tripVehDetails = $tripDetails['vehicle_detail'];
			}
			$ride_status = CustomerBookTrip::select('ride_status')->where('trip_transaction_id',$tripBookDetails['trip_transaction_id'])->get()->first();
	            if($ride_status->ride_status == 'success'){
			$email_Array[] = $postData['email'];
			$email_Subject = 'Your Trip '.$tripBookDetails['trip_transaction_id'].' Bill Details.';
			$email_Body = array(
				'booking_details' => $tripBookDetails,
                'trip_details' => $tripVehDetails,
				'gstn' => $postData['gstn'],
            );
			$send_email = $this->aws->sendEmailTo($email_Array, $email_Subject, $email_Body ,'generate-trip-bill');
			$returnResponse = ['status' => 'Success', 'message' => 'email sent','statusCode' => Response::HTTP_OK];
	        return response()->json(['response'=>$returnResponse]);
		}else{
                $returnResponse = ['status' => 'TripNotCompleted', 'message' => 'trip not completed yet','statusCode' => Response::HTTP_BAD_REQUEST];
                return response()->json(['response'=>$returnResponse]);
	            }
		}else{
			$returnResponse = ['status' => 'Failed', 'message' => 'not paid yet','statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
		}	
	}

	/*-------------------------book trip--------------------------*/
	public function bookTrip(Request $request){
		$postData = $request->all();
		if(!empty($postData)){
			$tripDetails = UserNotificationMessages::select('message_payload')->where('notification_msg_id', $postData['notiId'])->get()->first();
			$tripDetails = json_decode($tripDetails->message_payload, true);
			$getbooktype = gettype($tripDetails['booking_details']);
			if($getbooktype == 'string'){
			$tripBookDetails = json_decode($tripDetails['booking_details'], true);
			$tripVehDetails = json_decode($tripDetails['vehicle_detail'], true);
			}else{
			$tripBookDetails = $tripDetails['booking_details'];
                        $tripVehDetails = $tripDetails['vehicle_detail'];
			}
			$getPayment = CustomerBookTrip::select('pay_order_id')->where('trip_transaction_id',$tripBookDetails['trip_transaction_id'])->get()->first();
			if($getPayment->pay_order_id == null || $getPayment->pay_order_id == ''){
				$response = ['status' => 'payFailed', 'message' => 'no payment made','statusCode' => Response::HTTP_BAD_REQUEST];
			    return response()->json(['response' => $response]);
			}else{
		    if(!empty($tripBookDetails)){
		        $currentDate = Carbon::now()->toDateTimeString();
		        $user_data = Customer::select('user_first_name','email','user_mobile_no')->where('user_id',$tripDetails['user_id'])->first();
		        //update trip data with ride status and pay_id
		        if(isset($tripBookDetails['trip_transaction_id'])){
		            $tripdetail = CustomerBookTrip::select('op_driver_id','op_veh_id','op_id')->where('trip_transaction_id',$tripBookDetails['trip_transaction_id'])->get()->first();
		            $updateTrip = CustomerBookTrip::where('trip_transaction_id',$tripBookDetails['trip_transaction_id'])->update([
		                'ride_status' => 'not_started',
				'is_trip_booked' => 1,
		            ]);

		            $customertripdata = CustomerBookTrip::select('user_id','op_id','trip_transaction_id','op_veh_id','book_date')->where('trip_transaction_id',$tripBookDetails['trip_transaction_id'])->first();
		            if(!empty($customertripdata->op_id) && !empty($customertripdata->user_id)){
		                //update book date
		                $book_datesArr = array();
		                $updatedata = array();
		                $updateBookDate = OperatorVehicles::where('veh_id',$customertripdata->op_veh_id)->first();
		                $book_datesArr = json_decode($updateBookDate->book_dates_json,true);
		                if(empty($book_datesArr)){
		                    $book_datesArr = array(
		                        'key' =>$customertripdata->trip_transaction_id,
		                        'value' =>$customertripdata->book_date);
		                    $updatedata[] = $book_datesArr;
		                }else{
		                    $book_datesArr[] = array(
		                        'key' =>$customertripdata->trip_transaction_id,
		                        'value' =>$customertripdata->book_date);
		                    $updatedata = $book_datesArr;
		                }
		                $updateBookDate = OperatorVehicles::where('veh_id',$customertripdata->op_veh_id)->update(['book_dates_json' =>json_encode($updatedata,true)]);
		                
		                $driverId = isset($postData->driver_id) ? $postData->driver_id:null;
		                if(!empty($driverId)){
		                    $updateDriverBookDate = Driver::where('driver_id',$driverId)->update(['book_dates_json' =>json_encode($updatedata,true)]);
		                }
		                //end book date

		                //update user and operator account 
		                $order_amount = isset($tripVehDetails['total_amount']) ? $tripVehDetails['total_amount'] : null;
		                $op_user_id = isset($customertripdata->op_id) ? $customertripdata->op_id : null;
		                $user_id = isset($customertripdata->user_id) ? $customertripdata->user_id : null;
		                $payment_purpose = 'Trip_payment';
		                $this->updateOperatorDebitAccounts($payment_purpose,$order_amount,$op_user_id,$user_id);
		            }else{
		                Log::error('error in user paymnet or operator account creation');
		            }

		            //send confirmation notification and sms start
		            $token = array();
		            $getData = CustomerBookTrip::
		            join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_user_book_trip.op_id')
			    ->join('ggt_user','ggt_user.user_id','=','ggt_user_book_trip.user_id')
			    ->join('ggt_op_vehicles','ggt_op_vehicles.veh_id','=','ggt_user_book_trip.op_veh_id')
		            ->select('ggt_operator_users.op_notification_token','ggt_operator_users.op_mobile_no','ggt_user_book_trip.*','ggt_user.user_mobile_no','ggt_user.user_notification_token','ggt_op_vehicles.veh_registration_no')
		            ->where('ggt_user_book_trip.trip_transaction_id',$tripBookDetails['trip_transaction_id'])
		            ->get()
		            ->first();
		            $destinationAddressArr = [];
		            $destinationAddressArr = json_decode($getData->intermediate_address);
		            $destinationAddressArr = end($destinationAddressArr);
		            
		            array_push($token, $getData->op_notification_token);
		            $driverAmt = $getData->actual_amount - $getData->ggt_factor;
		            $notiMessage = 'Congratulation Your Trip Is Confirmed By Customer. Pick up Date and Time: '.$getData->book_date.' Pickup : '.$getData->start_address_line_1.' .Drop : '.$destinationAddressArr->dest_address_line_1.' Material: '.$getData->material_type.' Vehicle: '.$getData->veh_registration_no.' Your trip amount will be credited in your account in three working days. Check Your Upcoming Trips.';
		            $data = [
		                'message' => $notiMessage,
		                'notification_types' => 'trip-confirmed',
		            ]; 
		            $msgdata = array(
		                'title' => 'Trip Confirmed',
		                'message' => 'Your Trip Is Confirmed By Customer',
		                'message_type' => 'CNF',
		                'message_pattern' => 'C-D',
		                'message_sender_id' => $user_id,
		                'message_from' => 'customer',
		                'vibrate' => [1000, 1000, 1000],
		                'url' => Config::get('custom_config_file.notification_url'),
		                'message_payload' => json_encode($data),
		            );
		            $createNotification = DriverNotificationMessages::create($msgdata);
		            $messagedata = array(
		                'op_user_id' => $op_user_id,
		                'message_id' => $createNotification->notification_msg_id,
		                'message_receiver_id' => $op_user_id,
		            );
		            $notification = DriverNotifications::create($messagedata);
		            $notification = [
		                'title'=>'Trip Confirmed',
		                'body'=>'Your Trip Is Confirmed By Customer.',
		                'sound' => 'default',
		                'vibrate' => [1000, 5000, 1000],
		                'notification_types' => 'trip-confirmed',
		                'click_action' => Config::get('custom_config_file.notification_url').'all-notifications-details/'.$createNotification->notification_msg_id,
		                'icon' => '/assets/icons/notification-icon.png',
		            ];
		            $payload = [
		                'registration_ids' => $token,
		                'notification' => $notification,
		                'data' => $data,
		                'fcmId' => 'web',
		            ];
		            $pushNotification = $this->notification->sendPushNotification($payload);
		            //update message response
		            if($pushNotification != 'error'){
		                $updateUserAccount = DriverNotificationMessages::where('notification_msg_id',$createNotification->notification_msg_id)->update([
		                    'message_response' => $pushNotification,
		                ]);
		            }
		            //end here
		            //send sms
			    $cust_amt = $getData->actual_amount + ($getData->user_adjustment);
		            $countrycode = '+91';
		            $mobile = $getData->op_mobile_no;
		            $mob_country_code = $countrycode;
		            $mob_otp_phone_number = $mobile;
		            $otp_phone_number = $mob_country_code.$mob_otp_phone_number;
		            $otp_message = 'Congratulation Your Trip Is Confirmed By Customer. Pick up Date and Time: '.$getData->book_date.' Pickup : '.$getData->start_address_line_1.' .Drop : '.$destinationAddressArr->dest_address_line_1.' Material: '.$getData->material_type.' Vehicle: '.$getData->veh_registration_no.' Your trip amount will be credited in your account in three working days. Check Your Upcoming Trips.';
		            //$otp_message = nl2br($otp_message);
		           ////////// $otp = $this->aws->sendSmsOTP($otp_phone_number,$otp_message);
		            //send sms end 
			   
			    //send sms
			            $countrycode = '+91';
			            $mobile = $getData->user_mobile_no;
			            $mob_country_code = $countrycode;
			            $mob_otp_phone_number = $mobile;
			            $otp_phone_number = $mob_country_code.$mob_otp_phone_number;
			            $otp_message = 'Your booking is Confirmed. Thankyou! for booking GOGOTRUX. Pick up Date and Time: '.$getData->book_date.' Pickup location: '.$getData->start_address_line_1.' Delivery location: '.$destinationAddressArr->dest_address_line_1.' Material: '.$getData->material_type.' Loader: '.$getData->loader_count.' Vehicle: '.$getData->veh_registration_no.' Total trip charge: '.$cust_amt.'.
 							Note:  Please keep your shipment ready. Maximum allowed waiting time is 40 mins.';
			            //$otp_message = nl2br($otp_message);
			            ////////// $otp = $this->aws->sendSmsOTP($otp_phone_number,$otp_message);
			            //send sms end 
		            //end here
		        }
                    $overtimecharges = DB::table('ggt_overtime_charges')->get();
                    $overtimechargestext = '<select class="form-control">';
                    foreach($overtimecharges as $oc){
                        $overtimechargestext = $overtimechargestext.'<option>'.ucwords(str_replace("_", " ", $oc->overtime)).' (Rs. '.$oc->charges.')</option>';
                    }
                    $overtimechargestext = $overtimechargestext.'</select>';
                    if(count($overtimecharges) == 0){
                        $overtimechargestext = '';
                    }
		        if(!empty($getData)){
		             //$response = ['status' => 'success', 'user_first_name' => $user_data->user_first_name,'order_details' => $userOrderDetail, 'trip_id' => $postData->tripID,'statusCode' => Response::HTTP_OK];
			     $driverName = Driver::select('driver_first_name','driver_last_name')->where('driver_id',$tripVehDetails['drivers'])->get()->first();
			        	$tripVehDetails['veh_owner_name'] = $driverName->driver_first_name.' '.$driverName->driver_last_name;
			     $response = ['status' => 'success','overtimechargestext'=>$overtimechargestext ,'booking_details' => $tripBookDetails, 'vehicle_details' => $tripVehDetails, 'trip_id' => $tripBookDetails['trip_transaction_id'],'statusCode' => Response::HTTP_OK];
		             return response()->json(['response' => $response]);
		        }else{
		            $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
		            return response()->json(['response' => $response]);
		        }
		    }else{
		        $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
		        return response()->json(['response' => $response]);
		    }
		}
		}else{
			$response = ['status' => 'Failed', 'message' => 'empty request','statusCode' => Response::HTTP_BAD_REQUEST];
		    return response()->json(['response' => $response]);
		}
	}

    public function otherCharges(Request $request){
        $postData = $request->all();
        $updateTrip = CustomerBookTrip::where('trip_transaction_id',$postData['trip_transaction_id'])->update([
            'cust_waiting_charges' => $postData['cust_waiting_charges'],
            'partner_waiting_charges' => $postData['partner_waiting_charges'],
            'incidental_charges' => $postData['incidental_charges'],
            'accidental_charges' => $postData['accidental_charges'],
            'other_charges' => $postData['other_charges']
        ]);
        if($updateTrip){
            return 'success';
        }else{
            return 'oops';
        }
    }
	public function updateOperatorDebitAccounts($payment_purpose,$order_amount,$op_user_id,$user_id=null){
        if(!empty($payment_purpose) && !empty($order_amount) && !empty($op_user_id)) {
            $op_account_id =  OperatorAccounts::where('op_user_id',$op_user_id)->value('account_id');
            $op_account = OperatorAccounts::find($op_account_id);
            if($payment_purpose == "Trip_payment"){
                $op_account->trip_pay_amount = $op_account->trip_pay_amount + $order_amount;
                $op_account->total_debits = $op_account->total_debits + $order_amount;
                $op_account->total_balance = $op_account->total_credits - $op_account->total_debits;
                $op_account->save();

                //update user account
                /*if(!empty($user_id)){
                    $user_account_id = UserAccounts::where('user_id',$user_id)->value('id');
                    $user_account = UserAccounts::find($user_account_id);
                    $user_account->credit_trip_payment = $user_account->credit_trip_payment + $order_amount;
                    $user_account->total_credits = $user_account->total_credits + $order_amount;
                    $user_account->total_balance = $user_account->total_credits - $user_account->total_debits;
                    $user_account->save();
                }*/

            }else{
                //no purpose found
            }
        }else{
            Log::error('error in updation of operator accounts');
        }
    }
	public function verifyOtp(Request $request){
    	$postdata = $request->all();
    	if(!empty($postdata)){
		$notificationId = $postdata['notifiId'];
    		$notificationDetails = UserNotificationMessages::where('notification_msg_id',$notificationId)->first();
    		$notificationDetails = json_decode($notificationDetails->message_payload,true);
		$getbooktype = gettype($notificationDetails['booking_details']);
		if($getbooktype == 'string'){
	        $bookdata = json_decode($notificationDetails['booking_details'],true);
	        $vehdata = json_decode($notificationDetails['vehicle_detail'],true);
		}else{
		$bookdata = $notificationDetails['booking_details'];
                $vehdata = $notificationDetails['vehicle_detail'];
		}
			$formOtp = $postdata['cashotp'];
			$dbotp = CustomerBookTrip::select('otp')->where('trip_transaction_id',$bookdata['trip_transaction_id'])->first();
			if($formOtp == $dbotp->otp){
				//add invoice details start
            			$getMaxCustInv = CustomerBookTrip::max('cust_invoice_no');
            			if($getMaxCustInv){
                			$getMaxCustInv++;
            			}else{
                			$getMaxCustInv = 'CINV000001';
            			}
            			//end here
				$updateTrip = CustomerBookTrip::where('trip_transaction_id',$bookdata['trip_transaction_id'])->update([
	                'ride_status' => 'ongoing',
			'cust_invoice_no' => $getMaxCustInv,
	            ]);
	            $pay_order_id = CustomerBookTrip::select('pay_order_id')->where('trip_transaction_id',$bookdata['trip_transaction_id'])->first();
	            $updatePayment = UserPayments::where('user_order_id',$pay_order_id->pay_order_id)->update([
	                'user_order_status' => 'approved',
	            ]);
				$returnResponse = ['status' => 'otpsuccess', 'message' => 'correct otp','statusCode' => Response::HTTP_BAD_REQUEST];
	        	return response()->json(['response'=> $returnResponse]);
			}else{
				$returnResponse = ['status' => 'Failed', 'message' => 'incorrect otp','statusCode' => Response::HTTP_BAD_REQUEST];
	        	return response()->json(['response'=> $returnResponse]);
			}
    	}else{
    		$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
    	} 
    }

    public function closeTripPin(Request $request){
    	$postData = $request->all();
    	if(!empty($postData)){
    		$notificationId = $postData['notiId'];
    		$notificationDetails = UserNotificationMessages::where('notification_msg_id',$notificationId)->first();
    		$notificationDetails = json_decode($notificationDetails->message_payload,true);
		$getbooktype = gettype($notificationDetails['booking_details']);
		if($getbooktype == 'string'){
                        $bookdata = json_decode($notificationDetails['booking_details'],true);
		}else{
			$bookdata = $notificationDetails['booking_details'];
		}
	        $close_pin = rand(10,10000);
			$input = array(
                'delivery_pin'=> $close_pin
            );
            $pin = [];
            $pin[] = $input;
            $pin = json_encode($pin);
            $updateTrip = CustomerBookTrip::where('trip_transaction_id',$bookdata['trip_transaction_id'])->update([
                'close_trip_response' => $pin,
            ]);
            if($updateTrip){
                $response = ['status' => 'success','pin' => $close_pin,'statusCode' => Response::HTTP_OK];
                    return response()->json(['response'=> $response]);
            }else{
                $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
                return response()->json(['response'=> $response]);
            }
    	}else{
    		$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
    	} 
    }

	    /*------------------save trip start-------------*/
    public function saveTrip(Request $request){
    	$saveData = $request->all();
    	if(!empty($saveData)){
    		$tripDetails = UserNotificationMessages::select('message_payload')->where('notification_msg_id', $saveData['notiId'])->get()->first();
			$tripDetails = json_decode($tripDetails->message_payload, true);
			$getbooktype = gettype($tripDetails['booking_details']);
                	if($getbooktype == 'string'){
			$tripBookDetails = json_decode($tripDetails['booking_details'], true);
			}else{
			$tripBookDetails = $tripDetails['booking_details'];
			}
			//check for is payment made
			$getPayment = CustomerBookTrip::select('pay_order_id')->where('trip_transaction_id',$tripBookDetails['trip_transaction_id'])->get()->first();
			if($getPayment->pay_order_id == null || $getPayment->pay_order_id == ''){
				$response = ['status' => 'payFailed', 'message' => 'no payment made','statusCode' => Response::HTTP_BAD_REQUEST];
			    return response()->json(['response' => $response]);
			}else{
				//update ride status as saved
				$updateTrip = CustomerBookTrip::where('trip_transaction_id',$tripBookDetails['trip_transaction_id'])->update([
	                'ride_status' => 'saved',
	                'is_trip_saved' => 1,
	            ]);
	            if($updateTrip){
	            	$response = ['status' => 'success', 'message' => 'trip saved successfully','statusCode' => Response::HTTP_OK];
			    	return response()->json(['response' => $response]);	
	            }else{
	            	$returnResponse = ['status' => 'Failed', 'message' => 'query failed','statusCode' => Response::HTTP_BAD_REQUEST];
	        		return response()->json(['response'=> $returnResponse]);
	            }
			}
    	}else{
    		$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);	
    	} 
    }
    /*------------------save trip end---------------*/

	    /*-----------------edit add trip start----------------*/
    public function editAddTrip(Request $request){
    	$editTripData = $request->all();
    	if(!empty($editTripData)){
    		$tripId = $editTripData['tripTransactionId'];
		$bookPartner = isset($editTripData['bookPartner']) ? $editTripData['bookPartner'] : '';
    		if(empty($bookPartner)){

    		
    		//get trip data
    		$tripData = DB::table('ggt_user')
            ->join('ggt_user_book_trip', 'ggt_user.user_id', '=', 'ggt_user_book_trip.user_id')
//	    ->join('ggt_user_book_trip', 'ggt_user_book_trip.op_id', '=', 'ggt_operator_users.op_user_id')
		   ->join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_user_book_trip.op_id')
            ->join('ggt_user_payments', 'ggt_user_book_trip.pay_order_id', '=', 'ggt_user_payments.user_order_id')
            ->join('ggt_drivers', 'ggt_user_book_trip.op_driver_id', '=', 'ggt_drivers.driver_id')
            ->join('ggt_op_vehicles', 'ggt_user_book_trip.op_veh_id', '=', 'ggt_op_vehicles.veh_id')
            ->where('ggt_user_book_trip.trip_transaction_id',$tripId)
            ->select('trip_notification_id','trip_transaction_id','id','cust_waiting_charges','partner_waiting_charges','incidental_charges','accidental_charges','other_charges','start_address_line_1','start_address_line_2','start_address_line_3','start_address_line_4','start_address_lat','start_address_lan','start_pincode','dest_address_line_1','dest_address_line_2','dest_address_line_3','dest_address_line_4','user_first_name','user_middle_name','user_last_name','user_uid','loader_count','material_type','weight','user_order_pay_mode','user_order_amount','user_order_status','driver_first_name','driver_last_name','intermediate_address','user_mobile_no','book_date','driver_mobile_number','material_acceptance','bill_details','veh_code','veh_base_charge','veh_wheel_type','vehicle_type','veh_capacity','veh_dimension','veh_city','veh_charge_per_person','veh_registration_no','veh_model_name','veh_make_model_type','is_bid','actual_amount','base_amount','loader_price','is_trip_booked','user_order_paylink_id','payment_type','is_trip_saved','op_uid','user_adjustment','op_adjustment','ggt_adjustment')
	    ->get()
            ->first();
	}else{
		$tripData = DB::table('ggt_user')
            ->join('ggt_user_book_trip', 'ggt_user.user_id', '=', 'ggt_user_book_trip.user_id')
//          ->join('ggt_user_book_trip', 'ggt_user_book_trip.op_id', '=', 'ggt_operator_users.op_user_id')
                   ->join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_user_book_trip.op_id')
            //->join('ggt_user_payments', 'ggt_user_book_trip.pay_order_id', '=', 'ggt_user_payments.user_order_id')
            ->join('ggt_drivers', 'ggt_user_book_trip.op_driver_id', '=', 'ggt_drivers.driver_id')
            ->join('ggt_op_vehicles', 'ggt_user_book_trip.op_veh_id', '=', 'ggt_op_vehicles.veh_id')
            ->where('ggt_user_book_trip.trip_transaction_id',$tripId)
            ->select('trip_notification_id','trip_transaction_id','id','cust_waiting_charges','partner_waiting_charges','incidental_charges','accidental_charges','other_charges','start_address_line_1','start_address_line_2','start_address_line_3','start_address_line_4','start_address_lat','start_address_lan','start_pincode','dest_address_line_1','dest_address_line_2','dest_address_line_3','dest_address_line_4','user_first_name','user_middle_name','user_last_name','user_uid','loader_count','material_type','weight','driver_first_name','driver_last_name','intermediate_address','user_mobile_no','book_date','driver_mobile_number','material_acceptance','bill_details','veh_code','veh_base_charge','veh_wheel_type','vehicle_type','veh_capacity','veh_dimension','veh_city','veh_charge_per_person','veh_registration_no','veh_model_name','veh_make_model_type','is_bid','actual_amount','base_amount','loader_price','is_trip_booked','payment_type','is_trip_saved','op_uid','user_adjustment','op_adjustment','ggt_adjustment')
            ->get()
            ->first();
	}
	    $dateTime = $tripData->book_date;
	    $dateTime = explode(' ',$dateTime);
	    $bookDate = $dateTime[0];
	    $bookTime = $dateTime[1];
	    $bookDate = explode('-',$bookDate);
	    $bookDate = $bookDate[1].'/'.$bookDate[2].'/'.$bookDate[0];
	    $tripData->book_date = $bookDate;
	    $tripData->book_time = $bookTime;
	    $intermediate_address = json_decode($tripData->intermediate_address);
            $tripData->intermediate_address = $intermediate_address;
	    $tripData->actual_amount = $tripData->actual_amount + ($tripData->user_adjustment);
	    $tripData->base_amount = $tripData->base_amount + ($tripData->op_adjustment);
	    if(!empty($tripData->veh_model_name)){
                $vehModelName = Vehicles::where('veh_id',$tripData->veh_model_name)->value('veh_model_name');
                $tripData->veh_model_name = $vehModelName;
            }
            else{
                $tripData->veh_model_name = null;
            }
            if($tripData){
		if($tripData->is_trip_booked == 1){
            		Session::push('editTripIsBooked', 'true');	
            	}
		if($tripData->is_trip_saved == 1){
            		Session::push('editTripIsSaved', 'true');	
            	}  
            	Session::push('editTripData', $tripData);

            	$response = ['status' => 'success', 'message' => 'trip data in session','statusCode' => Response::HTTP_OK];
			    return response()->json(['response' => $response]);	
            }else{
            	$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        	return response()->json(['response'=> $returnResponse]);
            }
    	}else{
    		$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
    	}
    }
    /*-----------------edit add trip end------------------*/

    public function closeTripStatus(Request $request){
    	$postData = $request->all();
    	if(!empty($postData)){
    		$notificationId = $postData['notiId'];
    		$notificationDetails = UserNotificationMessages::where('notification_msg_id',$notificationId)->first();
    		$notificationDetails = json_decode($notificationDetails->message_payload,true);
    		$getbooktype = gettype($notificationDetails['booking_details']);
    		if($getbooktype == 'string'){
    			$bookdata = json_decode($notificationDetails['booking_details'],true);	
    		}else{
    			$bookdata = $notificationDetails['booking_details'];
    		}
	      
            $updateTrip = CustomerBookTrip::where('trip_transaction_id',$bookdata['trip_transaction_id'])->update([
                 'ride_status' => $postData['input']
            ]);
            if($updateTrip){
                $response = ['status' => 'success', 'message' => 'trip status updated','statusCode' => Response::HTTP_OK];
                    return response()->json(['response'=> $response]);
            }else{
                $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
                return response()->json(['response'=> $response]);
            }
    	}else{
    		$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
    	} 
    }
	
	public function postTrip(Request $request){
    	$postData = $request->all();
    	if(!empty($postData)){
    		$notificationId = $postData['notiId'];
    		$notificationDetails = UserNotificationMessages::where('notification_msg_id',$notificationId)->first();
    		$notificationDetails = json_decode($notificationDetails->message_payload,true);
    		$getbooktype = gettype($notificationDetails['booking_details']);
    		if($getbooktype == 'string'){
    			$bookdata = json_decode($notificationDetails['booking_details'],true);	
    		}else{
    			$bookdata = $notificationDetails['booking_details'];
    		}
	        
            $updateTrip = CustomerBookTrip::where('trip_transaction_id',$bookdata['trip_transaction_id'])->update([
                'is_post_trip' => 1
            ]);
            if($updateTrip){
                $response = ['status' => 'success', 'message' => 'trip post successfully','statusCode' => Response::HTTP_OK];
                    return response()->json(['response'=> $response]);
            }else{
                $response = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
                return response()->json(['response'=> $response]);
            }
    	}else{
    		$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
    	} 
    }

	public function bgFbData($id){
    	if(!empty($id)){
    		$notificationDetails = UserNotificationMessages::where('notification_msg_id',$id)->first();
                $notificationDetails = json_decode($notificationDetails->message_payload,true);
                $getbooktype = gettype($notificationDetails['booking_details']);
            if($getbooktype == 'string'){
                    $bookdata = json_decode($notificationDetails['booking_details'],true);
            }else{
                    $bookdata = $notificationDetails['booking_details'];
            }

    		$tripId = $bookdata['trip_transaction_id'];
    		//get trip data
    		 $tripData = DB::table('ggt_user')
            ->join('ggt_user_book_trip', 'ggt_user.user_id', '=', 'ggt_user_book_trip.user_id')
//          ->join('ggt_user_book_trip', 'ggt_user_book_trip.op_id', '=', 'ggt_operator_users.op_user_id')
                   ->join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_user_book_trip.op_id')
           // ->join('ggt_user_payments', 'ggt_user_book_trip.pay_order_id', '=', 'ggt_user_payments.user_order_id')
            ->join('ggt_drivers', 'ggt_user_book_trip.op_driver_id', '=', 'ggt_drivers.driver_id')
            ->join('ggt_op_vehicles', 'ggt_user_book_trip.op_veh_id', '=', 'ggt_op_vehicles.veh_id')
            ->where('ggt_user_book_trip.trip_transaction_id',$tripId)
            ->select('trip_notification_id','trip_transaction_id','id','start_address_line_1','start_address_line_2','start_address_line_3','start_address_line_4','start_address_lat','start_address_lan','start_pincode','dest_address_line_1','dest_address_line_2','dest_address_line_3','dest_address_line_4','user_first_name','user_middle_name','user_last_name','user_uid','loader_count','material_type','weight','driver_first_name','driver_last_name','intermediate_address','user_mobile_no','book_date','driver_mobile_number','material_acceptance','bill_details','veh_code','veh_base_charge','veh_wheel_type','vehicle_type','veh_capacity','veh_dimension','veh_city','veh_charge_per_person','veh_registration_no','veh_model_name','veh_make_model_type','is_bid','actual_amount','base_amount','loader_price','is_trip_booked','payment_type','is_trip_saved','op_uid')
            ->get()
            ->first();
            if($tripData->trip_notification_id == null){
		$tripData->trip_notification_id = $id;
	    }
            $dateTime = $tripData->book_date;
            $dateTime = explode(' ',$dateTime);
            $bookDate = $dateTime[0];
            $bookTime = $dateTime[1];
            $bookDate = explode('-',$bookDate);
            $bookDate = $bookDate[1].'/'.$bookDate[2].'/'.$bookDate[0];
            $tripData->book_date = $bookDate;
            $tripData->book_time = $bookTime; 
            $intermediate_address = json_decode($tripData->intermediate_address);
            $tripData->intermediate_address = $intermediate_address;
            if(!empty($tripData->veh_model_name)){
                $vehModelName = Vehicles::where('veh_id',$tripData->veh_model_name)->value('veh_model_name');
                $tripData->veh_model_name = $vehModelName;
            }
            else{
                $tripData->veh_model_name = null;
            }
            if($tripData){
            	if($tripData->is_trip_booked == 1){
            		Session::push('editTripIsBooked', 'true');	
            	}
            	if($tripData->is_trip_saved == 1){
            		Session::push('editTripIsSaved', 'true');	
            	} 
            	Session::push('editTripData', $tripData);
            	return view('admin.addtrip.index');
            }else{
            	$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        	return response()->json(['response'=> $returnResponse]);
            }
    	}else{
    		$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
    	}
    }
   
    //generate cash payment transaction id
    public function generateCustomerPaymentTransactionID(){
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$string = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 14; $i++) {
			$n = rand(0, $alphaLength);
			$string[] = $alphabet[$n];
		}
		$tranx_id = implode($string);
		$new_tax_id = 'pay_'.$tranx_id;
		$isExists = UserPayments::where('user_order_transaction_id', $new_tax_id)->exists();
		if($isExists){
			$this->generateCustomerPaymentTransactionID();
		}
		else{
			return $new_tax_id;
		}
	}
	
}


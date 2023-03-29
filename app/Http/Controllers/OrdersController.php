<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\CustomerBookTrip;
use App\Models\Customer;
use App\Models\Driver;
use DB;
use Config;
use File;
use Session;
use Response;
use App\Http\Controllers\CustomAwsController;


class OrdersController extends Controller 
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	 public function __construct()
    {
        $this->aws = new CustomAwsController;
        $this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
    }
	public function index()
	{
		
		if (! Gate::allows('trip_manage') && ! Gate::allows('trip_view')) {
			return abort(401);
		}
		else{
			// ggt_user ggt_drivers ggt_user_book_trip ggt_user_payments
			$orderdetail = CustomerBookTrip::
            select('ggt_user_book_trip.book_date','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.actual_amount','ggt_user_book_trip.payment_type','ggt_user_book_trip.ride_status','ggt_user_book_trip.trip_assigned_driver','ggt_user.user_mobile_no')
            ->join('ggt_user','ggt_user_book_trip.user_id','=','ggt_user.user_id')
            ->get()
            ->toArray();
			// dd($orderdetail);
			foreach ($orderdetail as $key => $value){
				// dd($value);
	            if($value['book_date'] != null || $value['book_date'] != ''){
	                $datetime = (explode(" ",$value['book_date']));
	                $orderdetail[$key]['time'] = date("g:i a", strtotime($datetime[1]));
	                $orderdetail[$key]['date'] = $datetime[0];
	            }
	            else{
	                $orderdetail[$key]['time'] = '';
	                $orderdetail[$key]['date'] = '';
	            }
	            if($value['trip_assigned_driver'] != null || $value['trip_assigned_driver'] != ''){
	            	$drivarDetails = json_decode($value['trip_assigned_driver'],true);
	            	$orderdetail[$key]['drivername'] = $drivarDetails[0]['driver_first_name'];
	            }else{
	            	$orderdetail[$key]['drivername'] = '';
	            }
        	}
			Session::forget('editTripData');Session::forget('editTripIsBooked');
			Session::forget('editTripIsSaved');Session::forget('bookPartnerId');
			return view('admin.orders.index', compact('orderdetail'));
			
		}
	}

	public function ordersAjaxData(Request $request)
	{
		if (! Gate::allows('trip_manage') && ! Gate::allows('trip_view')) {
			return abort(401);
		}else{
			$draw = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $row = $request['start'];
            $rowperpage = $request['length'];
            $filter = $request->get('search');
            $search = (isset($filter['value']))? $filter['value'] : false;
            $columnIndex = $request['order'][0]['column'];
            $columnName = $request['columns'][$columnIndex]['data'];
            $columnSortOrder = $request['order'][0]['dir'];

			$totalRecordsWithFilter = CustomerBookTrip::
            select('ggt_user_book_trip.book_date','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.actual_amount','ggt_user_book_trip.payment_type','ggt_user_book_trip.ride_status','ggt_user_book_trip.trip_assigned_driver','ggt_user.user_mobile_no')
            ->join('ggt_user','ggt_user_book_trip.user_id','=','ggt_user.user_id')
            ->join('ggt_user_payments','ggt_user_book_trip.pay_order_id','=','ggt_user_payments.user_order_id')
	    ->whereNull('ggt_user_book_trip.deleted_at')
            ->get()
			->count();

            $totalRecordsWithoutFilter = CustomerBookTrip::
            select('ggt_user_book_trip.book_date','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.actual_amount','ggt_user_book_trip.payment_type','ggt_user_book_trip.ride_status','ggt_user_book_trip.trip_assigned_driver','ggt_user.user_mobile_no')
            ->join('ggt_user','ggt_user_book_trip.user_id','=','ggt_user.user_id')
            ->join('ggt_user_payments','ggt_user_book_trip.pay_order_id','=','ggt_user_payments.user_order_id')
	    ->whereNull('ggt_user_book_trip.deleted_at')
            ->get()
			->count();

			if($search){
				
				/*$orderdetailQuery = DB::table('ggt_user')
				->join('ggt_user_book_trip', 'ggt_user.user_id', '=', 'ggt_user_book_trip.user_id')
				->join('ggt_user_payments', 'ggt_user.user_id', '=', 'ggt_user_payments.user_id')
				->join('ggt_drivers', 'ggt_user_book_trip.op_driver_id', '=', 'ggt_drivers.driver_id')
				->select('ggt_user.user_id','ggt_user.user_first_name','ggt_user.user_middle_name','ggt_user.user_last_name','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.book_date','ggt_drivers.driver_first_name','ggt_drivers.driver_last_name','ggt_user_payments.user_order_pay_mode','ggt_user_payments.user_order_amount','ggt_user_payments.user_order_status','ggt_user_book_trip.ride_status');*/
				/*$orderdetailQuery = CustomerBookTrip::
                select('ggt_user_book_trip.book_date','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.actual_amount','ggt_user_book_trip.payment_type','ggt_user_book_trip.ride_status','ggt_user_book_trip.is_trip_booked','ggt_user_book_trip.trip_assigned_driver','ggt_user.user_mobile_no','ggt_user_payments.user_order_status','ggt_op_vehicles.veh_code')
                ->join('ggt_user','ggt_user_book_trip.user_id','=','ggt_user.user_id')
                ->join('ggt_op_vehicles', 'ggt_user_book_trip.op_veh_id', '=', 'ggt_op_vehicles.veh_id')
                ->join('ggt_user_payments','ggt_user_book_trip.pay_order_id','=','ggt_user_payments.user_order_id');
			
				$orderdetailQuery = $orderdetailQuery->where('ggt_user_book_trip.book_date', 'like', '%'.$search.'%');
				$orderdetailQuery = $orderdetailQuery->where('ggt_user.user_mobile_no', 'like', '%'.$search.'%');dd($orderdetailQuery);
				if($columnName == 'book_date')
                {
                    $orderdetailQuery = $orderdetailQuery->orderBy('ggt_user_book_trip.'.$columnName, $columnSortOrder);
                }
                // elseif($columnName == 'user_mobile_no' || ($columnName == 'user_first_name'))
                // { 
                //     $orderdetailQuery = $tempUserDataQuery->orderBy('ggt_user.'.$columnName, $columnSortOrder);
                // }
                else
                {
                    $orderdetailQuery = $orderdetailQuery->orderBy('ggt_user_book_trip.id', 'desc');
                }
                $orderdetailQuery = $orderdetailQuery->offset($row)->limit($rowperpage);
                $orderdetail = $orderdetailQuery->get()->toArray();*/
			/*$orderdetailQuery = CustomerBookTrip::select('ggt_user_book_trip.is_trip_booked','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.ride_status','ggt_user_book_trip.book_date','ggt_user_book_trip.intermediate_address','ggt_user_book_trip.id','ggt_user_book_trip.user_id','ggt_user_book_trip.start_address_line_1','ggt_user_book_trip.start_address_line_2','ggt_user_book_trip.start_address_line_3','ggt_user_book_trip.start_address_line_4','ggt_user_book_trip.dest_address_line_1','ggt_user_book_trip.dest_address_line_2','ggt_user_book_trip.dest_address_line_3','ggt_user_book_trip.dest_address_line_4','ggt_user_book_trip.is_bid','ggt_user_book_trip.created_at','ggt_user.user_first_name','ggt_user.user_middle_name','ggt_user.user_last_name','ggt_user.user_mobile_no')->join('ggt_user','ggt_user_book_trip.user_id', '=','ggt_user.user_id');*/
		$orderdetailQuery = CustomerBookTrip::
            	select('ggt_user_book_trip.op_driver_id','ggt_user_book_trip.book_date','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.actual_amount','ggt_user_book_trip.payment_type','ggt_user_book_trip.ride_status','ggt_user_book_trip.is_trip_booked','ggt_user_book_trip.trip_assigned_driver','ggt_user.user_first_name','ggt_user.user_middle_name','ggt_user.user_last_name','ggt_user.user_mobile_no','ggt_user_payments.user_order_status','ggt_op_vehicles.veh_code','ggt_user_book_trip.user_adjustment')
            	->join('ggt_user','ggt_user_book_trip.user_id','=','ggt_user.user_id')
            	->join('ggt_op_vehicles', 'ggt_user_book_trip.op_veh_id', '=', 'ggt_op_vehicles.veh_id')
            	->join('ggt_user_payments','ggt_user_book_trip.pay_order_id','=','ggt_user_payments.user_order_id');

                $orderdetailQuery = $orderdetailQuery->whereNull('ggt_user_book_trip.deleted_at');
		$orderdetailQuery = $orderdetailQuery->where('ggt_user_book_trip.book_date', 'like', '%'.$search.'%');
		$orderdetailQuery = $orderdetailQuery->orWhere('ggt_user_book_trip.trip_transaction_id', 'like', '%'.$search.'%');
		$orderdetailQuery = $orderdetailQuery->orWhere('ggt_user_book_trip.ride_status', 'like', '%'.$search.'%');
		$orderdetailQuery = $orderdetailQuery->orWhere('ggt_user_book_trip.payment_type', 'like', '%'.$search.'%');
                $orderdetailQuery = $orderdetailQuery->orWhere('ggt_user_book_trip.start_address_line_1','like', '%'.$search.'%');

                $orderdetailQuery = $orderdetailQuery->orWhere('ggt_user_book_trip.intermediate_address','like', '%'.$search.'%');

		$orderdetailQuery = $orderdetailQuery->orWhere('ggt_user.user_first_name','like', '%'.$search.'%');
		$orderdetailQuery = $orderdetailQuery->orWhere('ggt_user.user_middle_name','like', '%'.$search.'%');
		$orderdetailQuery = $orderdetailQuery->orWhere('ggt_user.user_last_name','like', '%'.$search.'%');

                $orderdetailQuery = $orderdetailQuery->orWhere('ggt_user.user_mobile_no','like', '%'.$search.'%');

                if($columnName == 'book_date')
                {
                    $orderdetailQuery = $orderdetailQuery->orderBy('ggt_user_book_trip.'.$columnName, $columnSortOrder);
                }
                elseif($columnName == 'user_mobile_no' || ($columnName == 'user_first_name'))
                { 
                    $orderdetailQuery = $orderdetailQuery->orderBy('ggt_user.'.$columnName, $columnSortOrder);
                }
                else
                {
                    $orderdetailQuery = $orderdetailQuery->orderBy('ggt_user_book_trip.id', 'desc');
                }
                $orderdetailQuery = $orderdetailQuery->offset($row)->limit($rowperpage);
                $orderdetail = $orderdetailQuery->get()->toArray();
		
			}
			else{
				$orderdetailQuery = CustomerBookTrip::
            	select('ggt_user_book_trip.op_driver_id','ggt_user_book_trip.book_date','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.actual_amount','ggt_user_book_trip.payment_type','ggt_user_book_trip.ride_status','ggt_user_book_trip.is_trip_booked','ggt_user_book_trip.trip_assigned_driver','ggt_user.user_first_name','ggt_user.user_middle_name','ggt_user.user_last_name','ggt_user.user_mobile_no','ggt_user_payments.user_order_status','ggt_op_vehicles.veh_code','ggt_user_book_trip.user_adjustment')
            	->join('ggt_user','ggt_user_book_trip.user_id','=','ggt_user.user_id')
		->join('ggt_op_vehicles', 'ggt_user_book_trip.op_veh_id', '=', 'ggt_op_vehicles.veh_id')
            	->join('ggt_user_payments','ggt_user_book_trip.pay_order_id','=','ggt_user_payments.user_order_id');
            			$orderdetailQuery = $orderdetailQuery->whereNull('ggt_user_book_trip.deleted_at');
				$orderdetailQuery = $orderdetailQuery->where('ggt_user_book_trip.book_date', 'like', '%'.$search.'%');

				if($columnName == 'book_date')
				{
				    $orderdetailQuery = $orderdetailQuery->orderBy('ggt_user_book_trip.'.$columnName, $columnSortOrder);
				}
				else
				{
				    $orderdetailQuery = $orderdetailQuery->orderBy('ggt_user_book_trip.id', 'desc');
				}
				$orderdetailQuery = $orderdetailQuery->offset($row)->limit($rowperpage);
				$orderdetail = $orderdetailQuery->get()->toArray();
				}

				if(!empty($orderdetail)){
					foreach ($orderdetail as $key => $value){
					$userAmount = $value['actual_amount'] + ($value['user_adjustment']);
			           	if($value['book_date'] != null || $value['book_date'] != ''){
			               $datetime = (explode(" ",$value['book_date']));
			               $orderdetail[$key]['time'] = date("g:i a", strtotime($datetime[1]));
			               $orderdetail[$key]['date'] = $datetime[0];
			           	}
			           	else{
			               $orderdetail[$key]['time'] = '';
			               $orderdetail[$key]['date'] = '';
			           	}
			           	if(!empty($value['user_first_name']) || !empty($value['user_last_name'])){
	                        $orderdetail[$key]['customername'] = $value['user_first_name'].' '.$value['user_middle_name'].' '.$value['user_last_name'];
	                    }
	                    else{
	                        $orderdetail[$key]['customername'] = '-';
	                    }
	                    if(!empty($value['driver_first_name']) || !empty($value['driver_last_name'])){
	                        $orderdetail[$key]['drivername'] = $value['driver_first_name'].' '.$value['driver_last_name'];
	                    }
	                    else{
	                        $orderdetail[$key]['drivername'] = '';
	                    }
						/*if($value['ride_status'] == "pending" || $value['ride_status'] == "not_start" || $value['ride_status'] == "Ongoing"){
							$orderdetail[$key]['rideStatus'] = '<td><button class="btn btn-ongoing" id="cancel_verified_'.$value['trip_transaction_id'].'" value="'.$value['trip_transaction_id'].'" onclick="openModel(\''.$value['trip_transaction_id'].'\'	)">Ongoing</button></td>';
						}
						elseif($value['ride_status'] == "disputed"){
							$orderdetail[$key]['rideStatus'] = '<td><button class="btn btn-disputed">Disputed</button></td>';

						}
						elseif ($value['ride_status'] == "started"){
							$orderdetail[$key]['rideStatus'] = '<td><button class="btn btn-ongoing" id="cancel_verified_'.$value['trip_transaction_id'].'" value="'.$value['trip_transaction_id'].'" onclick="openModel(\''.$value['trip_transaction_id'].'\')">Booked</button></td>';
						}
						else{
							$orderdetail[$key]['rideStatus'] = '<td><button class="btn btn-closed">closed</button></td>';
						}*/
						if($value['ride_status'] == 'success'){
                                                                $value['ride_status'] = 'closed';
                                                        }

						if($value['ride_status'] == 'not_started'){
							$orderdetail[$key]['rideStatus'] = '<td><button class="btn btn-ongoing" id="cancel_verified_'.$value['trip_transaction_id'].'" value="'.$value['trip_transaction_id'].'" onclick="openModel(\''.$value['trip_transaction_id'].'\'	)">'.$value['ride_status'].'</button></td>';
						}else{
							$orderdetail[$key]['rideStatus'] = '<td><button class="btn btn-ongoing" id="cancel_verified_'.$value['trip_transaction_id'].'" value="'.$value['trip_transaction_id'].'">'.$value['ride_status'].'</button></td>';
						}
						/*$orderdetail[$key]['rideStatus'] = '<td><button class="btn btn-ongoing" id="cancel_verified_'.$value['trip_transaction_id'].'" value="'.$value['trip_transaction_id'].'" onclick="openModel(\''.$value['trip_transaction_id'].'\'	)">'.$value['ride_status'].'</button></td>';*/
						$orderdetail[$key]['tripId'] = '<td><a href="' . url('/transaction_detail',$value['trip_transaction_id']) . '">'.$value['trip_transaction_id'].'</a></td>';
			    if($value['ride_status'] == 'saved' || $value['is_trip_booked'] == 1){
				if($value['ride_status'] == 'success'){
					$orderdetail[$key]['action'] = '<td>-</td>';
				}else{
	           	    	$orderdetail[$key]['action'] = '<td><button onclick="gotoAddTrip(\''.$value['trip_transaction_id'].'\')"><i class="fa fa-edit"></i></button></td>'; 
				}
			    }else{
			    	$orderdetail[$key]['action'] = '<td>-</td>';
			    }
	                    $orderdetail[$key]['MTS'] = '<td>'.$value['veh_code'].'</td>';
	                    $orderdetail[$key]['Dvr'] = '<td><i class="fa fa-star-o"></i></td>';
	                    $orderdetail[$key]['Serv'] = '<td><i class="fa fa-star"></i></td>';
	                    //
	                    $orderdetail[$key]['user_order_amount'] = '<td>'.$userAmount.'</td>';
	                    $orderdetail[$key]['user_order_pay_mode'] = '<td>'.$value['payment_type'].'</td>';
	                    $orderdetail[$key]['username'] = '<td>'.$value['user_mobile_no'].'</td>';
			    if($value['op_driver_id'] != null || $value['op_driver_id'] != ''){
	                    	$driverName = Driver::select('driver_first_name','driver_last_name')->where('driver_id',$value['op_driver_id'])->first();
	                    	$orderdetail[$key]['drivername'] = '<td>'.$driverName->driver_first_name.' '.$driverName->driver_last_name.'</td>';
	                    }else{
                                $orderdetail[$key]['drivername'] = '<td>-</td>';
                            }
	                    /*if($value['trip_assigned_driver'] != null || $value['trip_assigned_driver'] != ''){
	                    	$driverName = json_decode($value['trip_assigned_driver'],true);
	                    	$orderdetail[$key]['drivername'] = '<td>'.$driverName[0]['driver_first_name'].'</td>';
	                    }else{
	                    	$orderdetail[$key]['drivername'] = '<td></td>';
	                    }*/
	                    //
				    }
				   
		     		$data = array(
						'draw' => $draw,
						'recordsTotal' => $totalRecordsWithoutFilter,
						'recordsFiltered' => $totalRecordsWithFilter,
						'data' => $orderdetail
		            );
	                return json_encode($data);
				}
				else{
					$orderdetail = array();
				 	$data = array('data' => $orderdetail);
	                return json_encode($data);
				}
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Orders  $orders
	 * @return \Illuminate\Http\Response
	 */


	public function show(Request $request,$id)
	{        
		// dd($request);	

		$orders=DB::table('ggt_user_book_ride_details')
		->where('ggt_user_book_ride_details.book_ride_details_id','=',$id)
		->leftjoin('ggt_operator_users','ggt_user_book_ride_details.op_id','=','ggt_operator_users.op_user_id')
		->leftjoin('ggt_drivers','ggt_user_book_ride_details.op_driver_id','=','ggt_drivers.driver_id')
		->leftjoin('ggt_vehicles','ggt_vehicles.veh_id','=','ggt_user_book_ride_details.op_veh_id')
		->leftjoin('ggt_user_book_ride','ggt_user_book_ride.ride_id','=','ggt_user_book_ride_details.ride_id')->get();
		// dd($orders);

		return view('admin.orders.view', compact('orders'));
	}
	public function gettripdetail($id){
		// dd($id);
		if(!empty($id)){

			$tripdetail = DB::table('ggt_user')
			            ->join('ggt_user_book_trip', 'ggt_user.user_id', '=', 'ggt_user_book_trip.user_id')
			            ->join('ggt_user_payments', 'ggt_user_book_trip.pay_order_id', '=', 'ggt_user_payments.user_order_id')
			            ->join('ggt_drivers', 'ggt_user_book_trip.op_driver_id', '=', 'ggt_drivers.driver_id')
			            ->join('ggt_op_vehicles', 'ggt_user_book_trip.op_veh_id', '=', 'ggt_op_vehicles.veh_id')
			            ->where('ggt_user_book_trip.trip_transaction_id',$id)
			            ->select('trip_transaction_id','id','start_address_line_1','start_address_line_2','start_address_line_1','start_address_line_3','start_address_line_4','dest_address_line_1','dest_address_line_2','dest_address_line_3','dest_address_line_4','user_first_name','user_middle_name','user_last_name','loader_count','material_type','weight','user_order_pay_mode','user_order_amount','user_order_status','driver_first_name','driver_last_name','intermediate_address','user_mobile_no','book_date','driver_mobile_number','material_acceptance','bill_details','veh_code','veh_registration_no','veh_model_name','veh_make_model_type','is_bid','actual_amount','base_amount','loader_price','user_adjustment','op_adjustment','ggt_adjustment')
			            ->get()
			            ->first();
			if(!empty($tripdetail->veh_model_name)){
                $vehModelName = DB::table('ggt_vehicles')->where('veh_id',$tripdetail->veh_model_name)->value('veh_model_name');
                $tripdetail->veh_model_name = $vehModelName;
            }
            
            
	            if($tripdetail->is_bid == 0){
	            	$tripdetail->is_bid = 'ENQ';
	            }else{
	            	$tripdetail->is_bid = 'BID';
	            }
	        
			$tripdetail->actual_amount = $tripdetail->actual_amount + ($tripdetail->user_adjustment);
			$tripdetail->base_amount = $tripdetail->base_amount + ($tripdetail->op_adjustment);
			if(!empty($tripdetail->material_acceptance)){
				$material = json_decode($tripdetail->material_acceptance,true);
				foreach ($material as $key1 => $value1){
					if(!empty($value1['material_image'])){
						$saveAsPath = '/tmp/';
	                    $filename_array = explode('/', $value1['material_image']);
	                    $download_url = $filename_array[count($filename_array) - 1];
	                    $tempFileName = end($filename_array);
	                    $filename = $this->aws->downloadUserFromS3($value1['material_image'], $saveAsPath);
	                    if($filename){
	                        $path = $saveAsPath.$filename;
	                        $file = File::get($path);
	                        $type = File::mimeType($path);
	                        $response = Response::make($file, 200);
	                        $response->header("Content-Type", $type);
	                        $b64image = base64_encode(file_get_contents($path));
	                        $tripdetail->destination[$key1]['material']= $b64image;
						}
					}
					else{
                        $tripdetail->destination[$key1]['material']= null;
                    }

                    if(!empty($value1['building_image'])){	
		                $saveAsPath = '/tmp/';
		                $filename_array = explode('/', $value1['building_image']);
		                $download_url = $filename_array[count($filename_array) - 1];
		                $tempFileName = end($filename_array);
		                $filename = $this->aws->downloadUserFromS3($value1['building_image'], $saveAsPath);
		                if($filename){
		                    $path = $saveAsPath.$filename;
		                    $file = File::get($path);
		                    $type = File::mimeType($path);
		                    $response = Response::make($file, 200);
		                    $response->header("Content-Type", $type);
		                    $b64image = base64_encode(file_get_contents($path));
		                   	$tripdetail->destination[$key1]['building_image'] = $b64image;
						}
					}
			  		else{
	    			 	$tripdetail->destination[$key1]['building_image'] = null;
					}

					if(!empty($value1['receiver_image'])){	
	                    $saveAsPath = '/tmp/';
	                    $filename_array = explode('/', $value1['receiver_image']);
	                    $download_url = $filename_array[count($filename_array) - 1];
	                    $tempFileName = end($filename_array);
	                    $filename = $this->aws->downloadUserFromS3($value1['receiver_image'], $saveAsPath);
	                    if($filename){
	                        $path = $saveAsPath.$filename;
	                        $file = File::get($path);
	                        $type = File::mimeType($path);
	                        $response = Response::make($file, 200);
	                        $response->header("Content-Type", $type);
	                        $b64image = base64_encode(file_get_contents($path));
	                       	$tripdetail->destination[$key1]['receiver_image'] = $b64image;
                        }
                    }
                    else{
                        $tripdetail->destination[$key1]['receiver_image'] = null;
                    }
				}
			}
			else{
				$tripdetail->destination[0]['material']= null;
                $tripdetail->destination[0]['building_image'] = null;
                $tripdetail->destination[0]['receiver_image'] = null;

			}
			// dd($tripdetail);
			if(!empty($tripdetail->book_date) || $tripdetail->book_date != null || $tripdetail->book_date != ''){
                $datetime = (explode(" ",$tripdetail->book_date));
                $tripdetail->time = date("g:i a", strtotime($datetime[1]));
                $tripdetail->date = $datetime[0];						  	
            }else{
                $tripdetail->time = '';
                $tripdetail->date = '';
            }
			if(!empty($tripdetail->material_acceptance)){
			    $material = json_decode($tripdetail->material_acceptance,true);
			    // dd(gettype($tripdetail->material_acceptance),gettype($material));
			    if(!empty($material)){
				    foreach ($material as $key => $value){

				            $receiverdetail[$key]['rec_name'] = $value['rec_name'];
				            $receiverdetail[$key]['rec_phone_no'] = $value['rec_phone_no'];
				            $receiverdetail[$key]['remark'] = $value['remark'];
				            $receiverdetail[$key]['delivery_time'] = $value['delivery_time'];
				    }
			    }
			} 
			else{
				 $receiverdetail[0]['rec_name'] = null;
			     $receiverdetail[0]['rec_phone_no'] = null;
			     $receiverdetail[0]['remark'] = null;
			     $receiverdetail[0]['delivery_time'] = null;
			}  
			if(!empty($tripdetail->intermediate_address)){
			    $address = json_decode($tripdetail->intermediate_address,true);
			    // dd($tripdetail->intermediate_address,$address);
			    if(!empty($address)){
				    foreach ($address as $key => $value){
					
			            $receiverdetail[$key]['dest_address_line_1'] = (isset($value['dest_address_line_1']))? $value['dest_address_line_1'] : null;
			            $receiverdetail[$key]['dest_address_line_2'] = (isset($value['dest_address_line_2']))? $value['dest_address_line_2'] : null;
			            $receiverdetail[$key]['dest_address_line_3'] = (isset($value['dest_address_line_3']))? $value['dest_address_line_3'] : null;
			            $receiverdetail[$key]['dest_address_line_4'] = (isset($value['dest_address_line_4']))? $value['dest_address_line_4'] : null;
				    $receiverdetail[$key]['rec_name'] = null;
			     		$receiverdetail[$key]['rec_phone_no'] = null;
			     		$receiverdetail[$key]['remark'] = null;
			     		$receiverdetail[$key]['delivery_time'] = null;
				    }			    	
			    }
			} 
			else{
				$receiverdetail[0]['dest_address_line_1'] = null;
				$receiverdetail[0]['dest_address_line_2'] = null;
				$receiverdetail[0]['dest_address_line_3'] = null;
				$receiverdetail[0]['dest_address_line_4'] = null;
			}
	        // dd($tripdetail,$receiverdetail);
		return view('admin.orders.transaction_detail', compact('tripdetail','receiverdetail'));
		}
		
	}

	public function updatedisputed(Request $request){
		$postData = $request->all();dd($postData);
        if(!empty($postData)){	
        	$data = $postData['data'];
        	$checkbox = (explode(" checkbox=",(str_replace('&', ' ', $data))));
        	$reason =explode(' reason=',$checkbox[1]);
        	$disputedfrom = $reason[0];
        	$disputedreason = $reason[1]; 
        	$value = array(
        		"cancelBy" => $disputedfrom,
        		"cancelreason" => $disputedreason,
        		"tripId" => $postData['tripTransactionId']
        	);
        	$disputedtripdata = json_encode($value);
        	// dd($disputedtripdata); 
        	$updatedDisputedTrip = CustomerBookTrip::where('trip_transaction_id', $postData['tripTransactionId'])
            ->update(['disputed_trip_response' => $disputedtripdata,'ride_status' =>'cancelled']);
			return json_encode(['status' => 'success', 'message' => 'Trip cancelled successfully!']);   
		}
	}	
}

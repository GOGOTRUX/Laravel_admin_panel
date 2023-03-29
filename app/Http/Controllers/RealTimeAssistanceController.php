<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\TempCustomerBookTrip;
use App\Models\CustomerBookTrip;
use App\Models\Customer;
use App\Models\Operator;
use DB;
use Session;
use Symfony\Component\HttpFoundation\Response;

class RealTimeAssistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('realtimeassistance_view')) {
            return abort(401);
        }
        else{
            $tempUserData = TempCustomerBookTrip::select('temp_ggt_user_book_trip.created_at','temp_ggt_user_book_trip.order_date','temp_ggt_user_book_trip.order_time','temp_ggt_user_book_trip.intermediate_address','temp_ggt_user_book_trip.id','temp_ggt_user_book_trip.user_id','temp_ggt_user_book_trip.user_search_id','temp_ggt_user_book_trip.start_address_line_1','temp_ggt_user_book_trip.start_address_line_2','temp_ggt_user_book_trip.start_address_line_3','temp_ggt_user_book_trip.start_address_line_4','temp_ggt_user_book_trip.dest_address_line_1','temp_ggt_user_book_trip.dest_address_line_2','temp_ggt_user_book_trip.dest_address_line_3','temp_ggt_user_book_trip.dest_address_line_4','temp_ggt_user_book_trip.is_bid','temp_ggt_user_book_trip.created_at','ggt_user.user_first_name','ggt_user.user_middle_name','ggt_user.user_last_name','ggt_user.user_mobile_no','temp_ggt_user_book_trip.user_booked_location')->join('ggt_user','temp_ggt_user_book_trip.user_id', '=','ggt_user.user_id')->orderBy('temp_ggt_user_book_trip.id','desc')->get()->toArray(); //->limit(100)
            // $tempUserData = $tempUserData->unique(function ($item) {
            //     return $item['user_mobile_no'].$item['user_first_name'];
            // });
            // dd($tempUserData);

            /*$output = Array();
            $sort = array();
            foreach($tempUserData as  $value)
            {
                $output_element = &$output[$value['user_mobile_no']];   ;
                // $output_element['created_at'] = $value['created_at'];
                if (isset($output_element['created_at']))
                {
                    if($output_element['created_at'] < $value['created_at'])
                    {
                        $output_element['created_at'] = $value['created_at'];
                        $output_element['order_date'] = $value['order_date'];
                        $output_element['order_time'] = $value['order_time'];
                        $output_element['intermediate_address'] = $value['intermediate_address'];
                        $output_element['id'] = $value['id'];
                        $output_element['user_id'] = $value['user_id'];
                        $output_element['user_search_id'] = $value['user_search_id'];
                        $output_element['start_address_line_1'] = $value['start_address_line_1'];
                        $output_element['start_address_line_2'] = $value['start_address_line_2'];
                        $output_element['start_address_line_3'] = $value['start_address_line_3'];
                        $output_element['start_address_line_4'] = $value['start_address_line_4'];
                        $output_element['dest_address_line_1'] = $value['dest_address_line_1'];
                        $output_element['dest_address_line_2'] = $value['dest_address_line_2'];
                        $output_element['dest_address_line_3'] = $value['dest_address_line_3'];
                        $output_element['dest_address_line_4'] = $value['dest_address_line_4'];
                        $output_element['is_bid'] = $value['is_bid'];
                        $output_element['user_first_name'] = $value['user_first_name'];
                        $output_element['user_middle_name'] = $value['user_middle_name'];
                        $output_element['user_last_name'] = $value['user_last_name'];
                        $output_element['user_mobile_no'] = $value['user_mobile_no'];
                        $output_element['user_booked_location'] = $value['user_booked_location'];
                    }
                }
                else
                {
                    $output_element['created_at'] = $value['created_at'];
                    $output_element['order_date'] = $value['order_date'];
                    $output_element['order_time'] = $value['order_time'];
                    $output_element['intermediate_address'] = $value['intermediate_address'];
                    $output_element['id'] = $value['id'];
                    $output_element['user_id'] = $value['user_id'];
                    $output_element['user_search_id'] = $value['user_search_id'];
                    $output_element['start_address_line_1'] = $value['start_address_line_1'];
                    $output_element['start_address_line_2'] = $value['start_address_line_2'];
                    $output_element['start_address_line_3'] = $value['start_address_line_3'];
                    $output_element['start_address_line_4'] = $value['start_address_line_4'];
                    $output_element['dest_address_line_1'] = $value['dest_address_line_1'];
                    $output_element['dest_address_line_2'] = $value['dest_address_line_2'];
                    $output_element['dest_address_line_3'] = $value['dest_address_line_3'];
                    $output_element['dest_address_line_4'] = $value['dest_address_line_4'];
                    $output_element['is_bid'] = $value['is_bid'];
                    $output_element['user_first_name'] = $value['user_first_name'];
                    $output_element['user_middle_name'] = $value['user_middle_name'];
                    $output_element['user_last_name'] = $value['user_last_name'];
                    $output_element['user_mobile_no'] = $value['user_mobile_no'];
                    $output_element['user_booked_location'] = $value['user_booked_location'];  
                }
            }*/
            // dd($output);
            if(!empty($tempUserData)){
                foreach ($tempUserData as $key => $value) {
                     if($value['order_date'] != null || $value['order_date'] != ''){
                        $tempUserData[$key]['order_date'] = $value['order_date'];
                    }
                    else{
                        $orderdetail[$key]['order_date'] = '';
                    }
                    if($value['order_time'] != null || $value['order_time'] != ''){
                        $tempUserData[$key]['order_time'] = date("g:i a", strtotime($value['order_time']));
                    }
                    else{
                        $tempUserData[$key]['order_time'] = '';
                    }
                    if(!empty($value['start_address_line_1'])){
                        $from = (explode(' ',trim($value['start_address_line_1'])))[0];   
                        $tempUserData[$key]['from']= $from;

                    }
                    else{
                        $tempUserData[$key]['from']= '';
                    }
                    if(!empty($value['intermediate_address'])){
                        $destinationaddress= json_decode($value['intermediate_address'],true);
                        $temp = [];
                        foreach ($destinationaddress as $key1 => $value1) {
                            // $firstWord = explode(' ',trim($value1['intermediate_location']))[0];
                            // array_push($temp, $firstWord);

                            // $test = array_map(function($value1  ) {
                            //     for($i=0;$i<6;$i++){ 
                            //         return explode(',', $value1['intermediate_location'])[$i];
                            //     }
                            // }, $destinationaddress);

                            // dd(implode(', ', $test));

                            $test = array_map(function ( $item ) {
                            $arr = explode(',', $item['intermediate_location']);
                            $offset = (count($arr) > 3) ? - 3 : -2;

                            array_splice($arr, $offset);

                            return implode(',', $arr);
                            }, $destinationaddress);
                            // dd($test);
                            // dd('here',implode(', ', $test));
                        }
                        $to = implode(',',$test);
                        $tempUserData[$key]['to']= $to;
                    }
                    else{
                        $tempUserData[$key]['to']= '';   
                    }
                }
            
            return view('admin.realtime-assistant.index', compact('tempUserData'));
            }
            else{
                $tempUserData = null;
                return view('admin.realtime-assistant.index', compact('tempUserData'));
            }
        }
    }

    public function realtimeAjaxData(Request $request)
    {
        if (! Gate::allows('realtimeassistance_view')) {
            return abort(401);
        }
        else{
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

            $totalRecordsWithoutFilter = TempCustomerBookTrip::join('ggt_user','temp_ggt_user_book_trip.user_id', '=','ggt_user.user_id')
            ->where('temp_ggt_user_book_trip.start_address_line_1','like', '%'.$search.'%')
            ->get()
            ->count();

            $totalRecordsWithFilter = TempCustomerBookTrip::join('ggt_user','temp_ggt_user_book_trip.user_id', '=','ggt_user.user_id')
            ->get()
            ->count();

            if($search){

                $tempUserDataQuery = TempCustomerBookTrip::select('temp_ggt_user_book_trip.created_at','temp_ggt_user_book_trip.order_date','temp_ggt_user_book_trip.order_time','temp_ggt_user_book_trip.intermediate_address','temp_ggt_user_book_trip.id','temp_ggt_user_book_trip.user_id','temp_ggt_user_book_trip.user_search_id','temp_ggt_user_book_trip.start_address_line_1','temp_ggt_user_book_trip.start_address_line_2','temp_ggt_user_book_trip.start_address_line_3','temp_ggt_user_book_trip.start_address_line_4','temp_ggt_user_book_trip.dest_address_line_1','temp_ggt_user_book_trip.dest_address_line_2','temp_ggt_user_book_trip.dest_address_line_3','temp_ggt_user_book_trip.dest_address_line_4','temp_ggt_user_book_trip.is_bid','temp_ggt_user_book_trip.created_at','ggt_user.user_first_name','ggt_user.user_middle_name','ggt_user.user_last_name','ggt_user.user_mobile_no','temp_ggt_user_book_trip.user_booked_location')->join('ggt_user','temp_ggt_user_book_trip.user_id', '=','ggt_user.user_id');

                $tempUserDataQuery = $tempUserDataQuery->where('temp_ggt_user_book_trip.order_date', 'like', '%'.$search.'%');

                $tempUserDataQuery = $tempUserDataQuery->orWhere('temp_ggt_user_book_trip.order_time','like', '%'.$search.'%');

                $tempUserDataQuery = $tempUserDataQuery->orWhere('temp_ggt_user_book_trip.start_address_line_1','like', '%'.$search.'%');

                $tempUserDataQuery = $tempUserDataQuery->orWhere('temp_ggt_user_book_trip.intermediate_address','like', '%'.$search.'%');

                $tempUserDataQuery = $tempUserDataQuery->orWhere('ggt_user.user_mobile_no','like', '%'.$search.'%');

                if($columnName == 'user_search_id' || $columnName == 'order_date'|| $columnName == 'order_time' || $columnName == 'user_booked_location')
                {
                    $tempUserDataQuery = $tempUserDataQuery->orderBy('temp_ggt_user_book_trip.'.$columnName, $columnSortOrder);
                }
                elseif($columnName == 'user_mobile_no' || ($columnName == 'user_first_name'))
                { 
                    $tempUserDataQuery = $tempUserDataQuery->orderBy('ggt_user.'.$columnName, $columnSortOrder);
                }
                else
                {
                    $tempUserDataQuery = $tempUserDataQuery->orderBy('temp_ggt_user_book_trip.id', 'desc');
                }
                $tempUserDataQuery = $tempUserDataQuery->offset($row)->limit($rowperpage);
                $tempUserData = $tempUserDataQuery->get()->toArray();
            }
            else{

                $tempUserDataQuery = TempCustomerBookTrip::select('temp_ggt_user_book_trip.created_at','temp_ggt_user_book_trip.order_date','temp_ggt_user_book_trip.order_time','temp_ggt_user_book_trip.intermediate_address','temp_ggt_user_book_trip.id','temp_ggt_user_book_trip.user_id','temp_ggt_user_book_trip.user_search_id','temp_ggt_user_book_trip.start_address_line_1','temp_ggt_user_book_trip.start_address_line_2','temp_ggt_user_book_trip.start_address_line_3','temp_ggt_user_book_trip.start_address_line_4','temp_ggt_user_book_trip.dest_address_line_1','temp_ggt_user_book_trip.dest_address_line_2','temp_ggt_user_book_trip.dest_address_line_3','temp_ggt_user_book_trip.dest_address_line_4','temp_ggt_user_book_trip.is_bid','temp_ggt_user_book_trip.created_at','ggt_user.user_first_name','ggt_user.user_middle_name','ggt_user.user_last_name','ggt_user.user_mobile_no','temp_ggt_user_book_trip.user_booked_location')->join('ggt_user','temp_ggt_user_book_trip.user_id', '=','ggt_user.user_id');
                

                if($columnName == 'user_search_id' || $columnName == 'order_date' || $columnName == 'order_time' || $columnName == 'user_booked_location')
                {
                    $tempUserDataQuery = $tempUserDataQuery->orderBy('temp_ggt_user_book_trip.'.$columnName, $columnSortOrder);
                }
                elseif($columnName == 'user_mobile_no' || ($columnName == 'user_first_name'))
                {
                    $tempUserDataQuery = $tempUserDataQuery->orderBy('ggt_user.'.$columnName, $columnSortOrder);
                }
                else
                {
                    $tempUserDataQuery = $tempUserDataQuery->orderBy('temp_ggt_user_book_trip.id', 'desc');
                }
                $tempUserDataQuery = $tempUserDataQuery->offset($row)->limit($rowperpage);
                $tempUserData = $tempUserDataQuery->get()->toArray();
            }

            
            if(!empty($tempUserData)){
                $emptyMobile = []; 
                $emptyName =[];
                foreach ($tempUserData as $key => $value) {
                    $check_key = !in_array($value['user_mobile_no'],$emptyMobile);
                    if ($check_key){
                        if(!empty($value['user_first_name'] || !empty($value['user_last_name']))){
                        $tempUserData[$key]['username'] = $value['user_first_name'].' '.$value['user_middle_name'].' '.$value['user_last_name'];
                        }
                        else{
                            $tempUserData[$key]['username'] = '';
                        }
                    }
                    else{
                        $tempUserData[$key]['username'] = '';
                    }
                    
                    if ($check_key)
                    {
                        array_push($emptyMobile,$value['user_mobile_no']);
                        $tempUserData[$key]['mobile'] = $value['user_mobile_no'];
                    }
                    else{
                        $tempUserData[$key]['mobile'] = '';
                    }
                    
                    if($value['is_bid'] === null){
                        $bid = '';
                        $tempUserData[$key]['Bid'] = $bid;
                    }
                    else{
                        $bid = $value['is_bid'];
                        if($bid == 0){
                            $tempUserData[$key]['Bid'] = "ENQ";
                        }
                        else{
                            $tempUserData[$key]['Bid'] = "BID";
                        }    
                    }
                     if($value['order_date'] != null || $value['order_date'] != ''){
                        $tempUserData[$key]['order_date'] = $value['order_date'];
                    }
                    else{
                        $orderdetail[$key]['order_date'] = '';
                    }
                    if($value['order_time'] != null || $value['order_time'] != ''){
                        $tempUserData[$key]['order_time'] = date("g:i a", strtotime($value['order_time']));
                    }
                    else{
                        $tempUserData[$key]['order_time'] = '';
                    }
                    if(!empty($value['start_address_line_1'])){
                        $from = (explode(' ',trim($value['start_address_line_1'])))[0];   
                        $tempUserData[$key]['from']= $from;

                    }
                    else{
                        $tempUserData[$key]['from']= '';
                    }
                    if(!empty($value['intermediate_address'])){
                        $destinationaddress= json_decode($value['intermediate_address'],true);
                        $temp = [];
                        foreach ($destinationaddress as $key1 => $value1) {
                            $test = array_map(function ( $item ) {
                            $arr = explode(',', $item['intermediate_location']);
                            $offset = (count($arr) > 3) ? - 3 : -2;

                            array_splice($arr, $offset);

                            return implode(',', $arr);
                            }, $destinationaddress);
                        }
                        $to = implode(',',$test);
                        $tempUserData[$key]['to']= $to;
                    }
                    else{
                        $tempUserData[$key]['to']= '';   
                    }

                    // if(!empty($value['user_first_name'] || !empty($value['user_last_name']))){
                    //     $tempUserData[$key]['name'] = $value['user_first_name'].' '.$value['user_middle_name'].' '.$value['user_last_name'];
                    // }
                    // else{
                    //     $tempUserData[$key]['name'] = '';
                    // } 
                    $tempUserData[$key]['searchid'] = '<td><a class="sid" data-toggle="modal" data-target="#search_details" onclick="showCustomerDetail(\''.$value['user_search_id'].'\')">'.$value['user_search_id'].'</a></td>';
                    $tempUserData[$key]['MTs'] = '<td>0</td>';
                   // $tempUserData[$key]['LocateMts'] = '<td><i class="fa fa-map-marker" aria-hidden="true"></i></td>';
$tempUserData[$key]['LocateMts'] = '<td><button onclick="gotoAddTrip(\''.$value['id'].'\')"><i class="fa fa-map-marker" aria-hidden="true"></i></button></td>';
                    $tempUserData[$key]['MT1'] = '<td><a class="mt-modal" data-toggle="modal" data-target="#mt_details"><i class="fa fa-square txt_violet" aria-hidden="true"></i><i class="fa fa-times-circle-o" aria-hidden="true"></i></a></td>';
                    $tempUserData[$key]['MT2'] = '<td><i class="fa fa-square txt_violet" aria-hidden="true"></i><i class="fa  fa-square-o" aria-hidden="true"></i></td>';
                    $tempUserData[$key]['MT3'] = '<td><i class="fa fa-square txt_yellow" aria-hidden="true"></i><i class="fa fa-times-circle-o" aria-hidden="true"></i></td>';
                    $tempUserData[$key]['MT4'] = '<td><i class="fa fa-square txt_yellow" aria-hidden="true"></i><i class="fa fa-times-circle-o" aria-hidden="true"></i></td>';
                    $tempUserData[$key]['MT5'] = '<td><i class="fa fa-square txt_violet" aria-hidden="true"></i><i class="fa  fa-square-o" aria-hidden="true"></i></td>';
                    $tempUserData[$key]['MT6'] = '<td><i class="fa fa-square txt_yellow" aria-hidden="true"></i><i class="fa fa-times-circle-o" aria-hidden="true"></i></td>';
                    $tempUserData[$key]['NotifyAll'] = '<td></td>';
                    $tempUserData[$key]['BidSent'] = '<td><i class="fa fa-check txt_green" aria-hidden="true"></i></td>';
                    $tempUserData[$key]['BidAccepted'] = '<td><i class="fa fa-check txt_green" aria-hidden="true"></i></td>';
                    $tempUserData[$key]['Payment'] = '<td><i class="fa fa-check txt_green" aria-hidden="true"></i></td>';
                    $tempUserData[$key]['OrderStatus'] = '<td><i class="fa fa-check txt_green" aria-hidden="true"></i></td>';
                    $tempUserData[$key]['OrderCreatedat'] = '<td>'.date('h:i:s a m/d/Y', strtotime($value['created_at']))
.'</td>';                  
                }
                // dd($tempUserData);
                $data = array(
                    'draw' => $draw,
                    'recordsTotal' => $totalRecordsWithoutFilter,
                    'recordsFiltered' => $totalRecordsWithoutFilter,
                    'data' => $tempUserData
                );
                return json_encode($data);
            }
            else{
                $tempUserData = array();
                $data = array('data' => $tempUserData);
                return json_encode($data);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showTripDetail(request $request)
    {
        // dd($request->all());
        if($request->ajax()){
            $postdata = $request->all();
            $searchId = $postdata['searchId'];
            if($searchId != null){
                $userDetail = TempCustomerBookTrip::select('*')->where('user_search_id',$searchId)->limit(1)->orderBy('id','desc')->get()->toArray();
                if(!empty($userDetail)){
                    foreach ($userDetail as $key => $value) {
			$getOpIds = CustomerBookTrip::select('op_id')->where('temp_book_trip_id',$value['id'])->get()->toArray();
                        if(!empty($getOpIds)){
                            $getOpName = Operator::select('op_user_id','op_first_name','op_last_name','op_uid')->whereIn('op_user_id',$getOpIds)->get()->toArray();
                        }else{
                            $getOpName = null;
                        }
			$userDetail[$key]['opdetails'] = $getOpName;	
                        if(!empty($value['start_address_line_1'])){
                            // dd($value['start_address_line_1']);
                            // $from = (explode(' ',trim($value['start_address_line_1'])))[0];   
                            $userDetail[$key]['from']= $value['start_address_line_1'];   
                        }
                        else{
                            $userDetail[$key]['from']= "";
                        }
                        if(!empty($value['intermediate_address'])){
                            $destinationaddress= json_decode($value['intermediate_address'],true);
                            $temp = [];
                            if(!empty($destinationaddress)){
                                foreach ($destinationaddress as $key1 => $value1) {
                                    // $firstWord = explode(' ',trim($value1['intermediate_location']))[0];
                                    // array_push($temp, $value1['intermediate_location']);
                                    $test = array_map(function ( $item ) {
                                    $arr = explode(',', $item['intermediate_location']);
                                    $offset = (count($arr) > 3) ? - 3 : -2;

                                    array_splice($arr, $offset);

                                    return implode(',', $arr);
                                    }, $destinationaddress);

                                }
                                // dd($value1['intermediate_location']);
                                $to = implode(',',$test);
                                $userDetail[$key]['to']= $to;
                            }

                        }
                        else{
                            $userDetail[$key]['to']= "";
                        }
                    }
                    // dd($userDetail);
                    return response()->json($userDetail);
                }
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }  
    }

    /*-----------------edit add trip from realtime assistance start----------------*/
    public function addTripEditFromRealtime(Request $request){
        $editTripData = $request->all();
        if(!empty($editTripData)){
            $id = $editTripData['id'];
            //get trip data
            $tripData = DB::table('ggt_user')
            ->join('temp_ggt_user_book_trip', 'ggt_user.user_id', '=', 'temp_ggt_user_book_trip.user_id')
            ->where('temp_ggt_user_book_trip.id',$id)
            ->select('id',
            'start_address_line_1',
            'start_address_line_2',
            'start_address_line_3',
            'start_address_line_4',
            'start_address_lat',
            'start_address_lan',
            'start_pincode',
            'dest_address_line_1',
            'dest_address_line_2',
            'dest_address_line_3',
            'dest_address_line_4',
            'dest_pincode',
            'user_first_name',
            'user_middle_name',
            'user_last_name',
            'user_uid',
            'loader_count',
            'material_type',
            'vehicle_type',
            'weight','intermediate_address',
            'user_mobile_no',
            'order_date','order_time',
            'is_bid','loader_price',
            'payment_type')
            // ->get()
            ->first();
        // $dateTime = $tripData->order_date;
        // $dateTime = explode(' ',$dateTime);
        $bookDate = $tripData->order_date;
        $bookTime = $tripData->order_time;
        $bookDate = explode('-',$bookDate);
        $bookDate = $bookDate[1].'/'.$bookDate[2].'/'.$bookDate[0];
        $tripData->book_date = $bookDate;
        $tripData->book_time = $bookTime;
        $intermediate_address = json_decode($tripData->intermediate_address);
        $tripData->intermediate_address = $intermediate_address;
            if($tripData){
                if(!isset($tripData->intermediate_address[0]->dest_address_line_1)){
                    $tripData->intermediate_address[0]->dest_address_line_1 = '';
                }
                if(!isset($tripData->intermediate_address[0]->dest_address_lat)){
                    $tripData->intermediate_address[0]->dest_address_lat = '';
                }
                if(!isset($tripData->intermediate_address[0]->dest_address_lan)){
                    $tripData->intermediate_address[0]->dest_address_lan = '';
                }
                // echo "<pre>";print_r($tripData->intermediate_address);exit;
                $tripDataArray = array();
                array_push($tripDataArray,$tripData);
                Session::put('editTripData', $tripDataArray);
                Session::put('editTripDataFromRealtime', 'true');
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

}

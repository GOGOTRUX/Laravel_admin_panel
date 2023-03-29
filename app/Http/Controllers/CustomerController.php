<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Customer;
use App\Models\CitiesMaster;
use App\Models\PincodesMaster;
use App\Models\CustomerBookTrip;
use DB;
use Carbon\Carbon;
use Config;
use File;
use Response;
use App\Http\Requests\Admin\updateCustomer;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\CommonController;
use App\Models\CustomerAccounts;
use Session;

class CustomerController extends Controller
{
    
    public function __construct()
    {
        $this->aws = new CustomAwsController;
        $this->commonFunction = new CommonController;
        $this->bucketname = Config::get('custom_config_file.bucket-name-user');
        $this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        if (!Gate::allows('customer_manage'))
        {
            return abort(401);
        }
        else{
            
            $customers = Customer::orderByDesc('created_at')->get()->toArray();

            foreach ($customers as $key => $value) {
                $user_first_name = (isset($value['user_first_name']) && (!empty($value['user_first_name']))) ? $value['user_first_name'] : '';
                $user_last_name = (isset($value['user_last_name']) && (!empty($value['user_last_name']))) ? $value['user_last_name'] : '';
                
                $customers[$key]['full_name'] = $user_first_name.' '.$user_last_name;
                $created_at = Carbon::parse($value['created_at']);
                $customers[$key]['created_date'] = Carbon::createFromFormat('Y-m-d', $created_at->toDateString())->toFormattedDateString();

                /*
                $base_lati_long = json_decode($value['current_location'],true);
                $customers[$key]['lati'] = $base_lati_long[0]['lati'];
                $customers[$key]['long'] = $base_lati_long[0]['long'];
                
                $url  = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$customers[$key]['lati'].",".$customers[$key]['long']."&key=AIzaSyArZ5oTxpSuxQhaaNyJmKK94fPLKynjVPk&amp";
                
                $json = @file_get_contents($url);
                $data = json_decode($json);
                $status = $data->status;
                $address = '';
                if($status == "OK")
                {
                    $addressArr = explode(',', $data->results[0]->formatted_address);
                    if(!empty($addressArr) && count($addressArr) > 2){
                        $shortAddr = array_slice($addressArr, 2); 
                        $shortAddr = implode(',', $shortAddr);
                        $customers[$key]['short_location'] = $shortAddr;
                        $customers[$key]['full_location'] = $data->results[0]->formatted_address;
                    }
                }
                else
                {
                    $customers[$key]['full_location'] = 'No location found';   
                    $customers[$key]['short_location'] = 'No location found';
                } 
                */

            }
            return view('admin.customer.index', compact('customers'));
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
    public function show(Request $request,$id)
    {
        if (!Gate::allows('customer_view'))
        {
            return abort(401);
        }
        else{

            $customer = Customer::where('user_id',$id)->first();
            if(!empty($customer)){
                if(isset($customer->user_profile_pic)){
                    $saveAsPath = '/tmp/';
                    $filename_array = explode('/', $customer->user_profile_pic);
                    $download_url = $filename_array[count($filename_array) - 1];
                    $tempFileName = end($filename_array);
                    $is_file_exists = File::exists($saveAsPath.$tempFileName);
                    if($is_file_exists){
                        $filename = $tempFileName;
                    }
                    else{
                        $filename = $this->aws->downloadUserFromS3($customer->user_profile_pic, $saveAsPath);
                    }
                    $user_profile_pic_base64 = null;
                    if($filename){
                        $path = $saveAsPath.$filename;
                        $file = File::get($path);
                        $type = File::mimeType($path);
                        $response = Response::make($file, 200);
                        $response->header("Content-Type", $type);
                        $b64image = base64_encode(file_get_contents($path));
                        $customer->user_profile_pic = $b64image;
                    }
                    else{
                        $b64image = null;
                    }
                }

                $address=CitiesMaster::join('ggt_master_states','ggt_master_states.id','=','ggt_master_cities.state_id')->select('ggt_master_cities.state_id','ggt_master_states.state','ggt_master_cities.id','ggt_master_cities.city')->where('ggt_master_cities.id','=',$customer->address_city)->get();

                $order=Customer::Join('ggt_user_book_trip','ggt_user_book_trip.user_id','=','ggt_user.user_id')
                ->where('ggt_user.user_id','=',$id)
                ->orderBy('ggt_user_book_trip.user_id','desc')
                ->first();

                $booktrip_count=Customer::Join('ggt_user_book_trip','ggt_user_book_trip.user_id','=','ggt_user.user_id')
                ->where('ggt_user.user_id','=',$id)
                ->orderBy('ggt_user_book_trip.user_id','desc')
                ->count();
            }else{
                $customer = null;
                $order = null;
                $address = null;
            }
            return view('admin.customer.view', compact('customer','address','order','booktrip_count'));
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
        if (! Gate::allows('customer_edit')){
            return abort(401);
        }
        else{
            $customer = Customer::where('user_id',$id)->first();
            $pincodeslist=PincodesMaster::select('id','pincode')->get();
            $address=CitiesMaster::join('ggt_master_states','ggt_master_states.id','=','ggt_master_cities.state_id')->select('ggt_master_cities.state_id','ggt_master_states.state','ggt_master_cities.id','ggt_master_cities.city')->where('ggt_master_cities.id','=',$customer->address_city)->get();

            if(isset($customer->user_profile_pic)){
                $saveAsPath = '/tmp/';
                $filename_array = explode('/', $customer->user_profile_pic);
                $download_url = $filename_array[count($filename_array) - 1];
                $tempFileName = end($filename_array);
                $is_file_exists = File::exists($saveAsPath.$tempFileName);
                if($is_file_exists){
                    $filename = $tempFileName;
                }
                else{
                    $filename = $this->aws->downloadUserFromS3($customer->user_profile_pic, $saveAsPath);
                }
                $user_profile_pic_base64 = null;
                if($filename){
                    $path = $saveAsPath.$filename;
                    $file = File::get($path);
                    $type = File::mimeType($path);
                    $response = Response::make($file, 200);
                    $response->header("Content-Type", $type);
                    $b64image = base64_encode(file_get_contents($path));
                    $customer->user_profile_pic = $b64image;
                }
                else{
                    $b64image = null;
                }
            }

            return view('admin.customer.edit', compact('customer','address','pincodeslist'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
    */

    public function update(updateCustomer $request, $id /*Request $request, $id*/)
    {
        if (! Gate::allows('customer_edit'))
        {
            return abort(401);
        }
        else{
            $validated = $request->validated();
            if(isset($request->user_profile_pic))
            {            
                $dir = Config::get('custom_config_file.dir_user_profile_img');
                $image_url = null; 
                    
                    if(!file_exists($dir))
                    {
                        mkdir($dir);
                    }
                    $user_profile_pic = $request->user_profile_pic;
                    $user_mobile_no = $request->user_mobile_no;
                    $data = date('Y_m_d_H_i_s');
                    $file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->user_profile_pic->getClientOriginalName());
                    $file_name = str_replace('-', '_',$file_name);
                    $new_file_name = str_replace(' ', '',$user_mobile_no."-profile-".$file_name);
                    $image_path = "$dir/$new_file_name";                
                    $user_profile_pic->move($dir,$new_file_name);
                    $image_url =$this->aws->uploadUserToS3($new_file_name ,$image_path, $this->bucketname);
                    $new_user_path=$this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
                                                    
                    $setpath = Customer::where('user_id',$request->user_id)->update(['user_profile_pic' => $new_user_path]);
            }

            if(!empty($request->user_dob)){
                $user_dob = Carbon::parse($request->user_dob)->format('Y-m-d');
            }else{
                $user_dob = null;
            }

             $data = array(
                'user_first_name'=>$request->user_first_name,
                'user_middle_name' => $request->user_middle_name,
                'user_last_name' => $request->user_last_name,
                'user_mobile_no'=> $request->user_mobile_no,
                'user_dob'=>$user_dob,
                'user_gender'=>$request->user_gender,
                'email'=>$request->email,
                'address_pin_code'=>$request->address_pin_code,
                'address_state'=>$request->address_state,
                'address_city'=>$request->address_city,
                'user_address_line'=>$request->user_address_line,
                'user_address_line_1'=>$request->user_address_line_1,
                'user_address_line_2'=>$request->user_address_line_2,
                'user_address_line_3'=>$request->user_address_line_3,
            );
            
            $updateCustomer = Customer::where('user_id',$request->user_id)->update($data);
            if($updateCustomer || $setpath){
                return redirect()->route('customer.index')->with('success', 'Customer information has been updated successfully.');
            }else{
                return redirect()->route('customer.index')->with('error', 'Failed to update customer '.$request->user_mobile_no.' information');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(! Gate::allows('customer_delete')) 
        {
            return abort(401);
        }else{
            $customer = Customer::find($request->id)->delete();
            if($customer){
                return json_encode(['status'=> 'success', 'response'=> true,'message' => 'customer deleted successfully']);
            }else{
                return json_encode(['status'=> 'failed', 'response'=> false,'message' => 'failed to delete customer']);
            }
        }
    }

    /**
    * @param reason,customer_id
    * @return 
    *
    **/
    public function customerBlocked(Request $request){
        try{
            if (! Gate::allows('customer_block')) {
                return abort(401);
            }
            else{
                $blockedstatus=Customer::where('user_id','=',$request->customer_id)
                ->update([
                    'user_account_block_note' =>$request->reason,
                    'is_blocked' => 1
                ]);   
                if($blockedstatus == 1)
                {
                    if($request->customer_pagetype == 'edit'){
                        return redirect()->route('customer.edit',$request->customer_id)->with('success', 'Customer has been blocked successfully.');
                    }else{
                        return redirect()->route('customer.index')->with('success', 'Customer has been blocked successfully.');
                    }
                }
                else
                {
                    if($request->customer_pagetype == 'edit'){
                        return redirect()->route('customer.edit',$request->customer_id)->with('error', 'Failed to block customer.');
                    }else{
                        return redirect()->route('customer.index')->with('error', 'Failed to block customer.');
                    }
                }
            }
        }catch (Exception $e) {
            report($e);
        }
    }

    /** 
    * @param customer_id
    * @return 
    *
    **/
    public function customerUnblocked(Request $request){
        try{
            if (! Gate::allows('customer_block')) {
                return abort(401);
            }
            else{
                $blockedstatus = Customer::where('user_id','=',$request->customer_id)
                            ->update([
                                'user_account_block_note' => null,
                                'is_blocked' => 0
                            ]);   
                if($blockedstatus == 1)
                {
                    if($request->customer_pagetype == 'edit'){
                        return redirect()->route('customer.edit',$request->customer_id)->with('success', 'Customer has been unblocked successfully.');
                    }else{
                        return redirect()->route('customer.index')->with('success', 'Customer has been unblocked successfully.');
                    }
                }
                else
                {
                    if($request->customer_pagetype == 'edit'){
                        return redirect()->route('customer.edit',$request->customer_id)->with('error', 'Failed to unblock customer.');
                    }else{
                        return redirect()->route('customer.index')->with('error', 'Failed to unblock customer.');
                    }
                }
            }
        }
        catch (Exception $e) {
            report($e);
        }
    }

    /** 
    * @param customer_id
    * @return 
    *
    **/
    public function verifyCustomer(Request $request)
    {
        /*$customerbookTrip=Customer::Join('ggt_user_book_trip','ggt_user_book_trip.user_id','=','ggt_user.user_id')
        ->where('ggt_user.user_id','=',$request->id)
        ->exists();
        if($customerbookTrip){
            $verifyCustomer = Customer::where('user_id',$request->id)->update(['user_verified' => 1]);
            if($verifyCustomer)
            {
                return json_encode(['status'=> 'success', 'response'=> true,'message' => 'verifed successfully']);
            }else{
                return json_encode(['status'=> 'failed', 'response'=> false,'message' => 'failed to verify customer']);
            }    
        }else{
            return json_encode(['status'=> 'failed', 'response'=> false,'message' => 'require atleast one booking']);
        }*/
	/*$customerbookTrip=Customer::Join('ggt_user_book_trip','ggt_user_book_trip.user_id','=','ggt_user.user_id')
        ->where('ggt_user.user_id','=',$request->id)
        ->where('ggt_user_book_trip.ride_status','=','success')
        ->exists();*/
	$customerbookTrip=Customer::where('ggt_user.user_id','=',$request->id)->exists();

        if($customerbookTrip){
            $getMaxUserID = Customer::max('user_uid'); //'C000AA0'; 
            $getMaxUserID++;
            $verifyCustomer = Customer::where('user_id',$request->id)->update(['user_verified' => 1,'user_uid' => $getMaxUserID]);

            $updateAccounts = CustomerAccounts::where('user_id',$request->id)->update(['user_uid' => $getMaxUserID]);
            if($verifyCustomer)
            {
                return json_encode(['status'=> 'success', 'response'=> true,'message' => 'verifed successfully']);
            }else{
                return json_encode(['status'=> 'failed', 'response'=> false,'message' => 'failed to verify customer']);
            }    
        }else{
            return json_encode(['status'=> 'failed', 'response'=> false,'message' => 'require atleast one booking']);
        }
    }

    public function bookCustomer(Request $request){
        $cust_id = $request->id;
        $CustomerData = Customer::select('user_first_name','user_middle_name','user_last_name','user_uid','user_mobile_no')->where('user_id',$cust_id)->get()->first();
        //Session::push('editTripData', $CustomerData);
        $response = ['status' => 'success', 'message' => 'customer data in session'];
        return response()->json(['response' => $response]);
    }
}

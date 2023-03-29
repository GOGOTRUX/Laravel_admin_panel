<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\CustomerInformationBoard;
use App\Models\CustomerQuoteBoard;
use App\Models\CustomerDynamicImages;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Validator;
use Config;
use File;
use Response;
use Log;
use Carbon\Carbon;

class CustomerInformationBoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->aws = new CustomAwsController;        
        $this->commonFunction = new CommonController;        
        $this->middleware('auth');
        $this->bucketname = Config::get('custom_config_file.public-bucket');
        $this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
        $this->SERVER_API_KEY="AIzaSyBN_cnv9SV90CDmeNFsMo7pMBaiE7Iux34";
        $this->todays = Carbon::now();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('information_board_screen_manage')) {
            return abort(401);
        }
        else{
            $result = CustomerInformationBoard::whereNull('deleted_at')->count();
            if($result==0){
                $info_board = null;
                $latest_info = null;
            }
            else{
            $info_board = CustomerInformationBoard::select('id','info_board_text','updated_at')->whereNull('deleted_at')->orderBy('id', 'desc')->get()->toArray();
            $latest_info = CustomerInformationBoard::select('id','info_board_text','updated_at')->whereNull('deleted_at')->latest('updated_at')->first()->toArray();
            }
            $quote = CustomerQuoteBoard::whereNull('deleted_at')->count();
            if($quote==0){
                $quote_board_text = null;
                $latest_quote = null;
            }
            else{
            $quote_board_text = CustomerQuoteBoard::select('id','quote_board_text','updated_at')->whereNull('deleted_at')->orderBy('id', 'desc')->get()->toArray();
            $latest_quote = CustomerQuoteBoard::select('id','quote_board_text','updated_at')->whereNull('deleted_at')->latest('updated_at')->first()->toArray();
            }
            //get customer dynamic images
            $get_dynamic_images = CustomerDynamicImages::select('id','image_name','image_type')->whereNull('deleted_at')->get()->toArray();
            $dashboardImages = [];
            $myluckySingleImages = [];
            $accuralImages = [];
            $myluckyMultipleImages = [];
            foreach ($get_dynamic_images as $key => $value){
                if($value['image_type'] == 'DASHBOARD'){
                    array_push($dashboardImages, [
                        'image_name' => $value['image_name'],
                        'id' => $value['id'],
                    ]);
                }elseif($value['image_type'] == 'MYLUCKYSINGLE'){
                    array_push($myluckySingleImages, [
                        'image_name' => $value['image_name'],
                        'id' => $value['id'],
                    ]);
                }elseif($value['image_type'] == 'ACCURAL'){
                    array_push($accuralImages, [
                        'image_name' => $value['image_name'],
                        'id' => $value['id'],
                    ]);
                }else{
                    array_push($myluckyMultipleImages, [
                        'image_name' => $value['image_name'],
                        'id' => $value['id'],
                    ]);
                }
            }
            //end here
            return view('admin.customerinformationboard.index', compact('info_board','latest_info','quote_board_text','latest_quote','dashboardImages','myluckySingleImages','accuralImages','myluckyMultipleImages'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $postman= $request->all();
        if($postman['type'] == "informationBoard"){
            return view('admin.customerinformationboard.create');
        }
        else{
            return view('admin.customerinformationboard.quotecreate');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $postData = $request->all();
        if(!empty($postData)){
            if($postData['type'] == 'informationBoard'){
                    // $user = Auth::user();
                    $user = Auth::getUser();
                    $admin_id = $user->id;
                    $admin_email = $user->email;
                    $input = array(
                                'admin_id' => $admin_id,
                                'admin_id' => 1,
                                'info_board_text' => $request->info_board_text,
                                'created_by' => $admin_email,
                            );
                    $info_board = CustomerInformationBoard::create($input);
                    return redirect()->route('customerinformationboard.index')->with('success', 'Customer Information board added successfully.');

                }
                else{
                     $user = Auth::getUser();
                    $admin_id = $user->id;
                    $admin_email = $user->email;
                    $input = array(
                                'admin_id' => $admin_id,
                                'admin_id' => 1,
                                'quote_board_text' => $request->quote_board_text,
                                'created_by' => $admin_email,
                            );
                    $info_board = CustomerQuoteBoard::create($input);
                    return redirect()->route('customerinformationboard.index')->with('success', 'Customer Quote board added successfully.');
                }
        }
        else{
             Log::warning("empty request:CustomerInformationBoardController");  
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$type)
    {
        $postdata = $request->all();  
        $id = $postdata['id'];
        if(!empty($type)){
            if($type == "infoBoard"){
                if(!empty($id)){
                    $info_board = CustomerInformationBoard::select('id','info_board_text')->where('id',$id)->first();
                    return view('admin.customerinformationboard.edit', compact('info_board'));
                }
                else{
                    Log::warning("empty request:CustomerInformationBoardController");   
                }
            }
            else{
                if(!empty($id)){
                    $quote_board = CustomerQuoteBoard::select('id','quote_board_text')->where('id',$id)->first();
                    return view('admin.customerinformationboard.editquote', compact('quote_board'));
                }
                else{
                    Log::warning("empty request:CustomerInformationBoardController");   
                }
            }
            
        }
        else{
            Log::warning("empty request:CustomerInformationBoardController");   
        }    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(request $request,$id)
    {
        $postdata = $request->all();
        $type = $postdata['type'];
        if(!empty($type)){ 
            if($type == 'informationBoard'){
                if(!empty($id)){
                    $user = Auth::getUser();
                    $admin_id = $user->id;
                    $admin_email = $user->email;
                    $input = array(
                        'admin_id' => $admin_id,
                        'info_board_text' => $postdata['info_board_text_edit'],
                        'created_by' => $admin_email,
                    );
                    $informationboard = CustomerInformationBoard::where('id',$id)->update($input);         
                    return redirect()->route('customerinformationboard.index')->with('success', 'Customer Information board updated successfully.');
                }
                else{
                    Log::warning("empty request:CustomerInformationBoardController");
                }
            }
            else{
                if(!empty($id)){
                    $user = Auth::getUser();
                    $admin_id = $user->id;
                    $admin_email = $user->email;
                    $input = array(
                        'admin_id' => $admin_id,
                        'quote_board_text' => $postdata['quote_board_edit'],
                        'created_by' => $admin_email,
                    );
                    $quoteboard = CustomerQuoteBoard::where('id',$id)->update($input);         
                    return redirect()->route('customerinformationboard.index')->with('success', 'Customer quote board updated successfully.');
                }
                else{
                    Log::warning("empty request:CustomerInformationBoardController"); 
                }
            }
        }
        else{
            Log::warning("empty request:CustomerInformationBoardController");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {   $postdata = $request->all();
        if(! Gate::allows('customer_delete')) 
        {
            return abort(401);
        }else{
            $InfoBoardDel=DB::table("ggt_customer_information_board")
                ->where('id','=',$postdata['id'])            
                ->update([
                            'deleted_at'=>date('Y-m-d H:i:s'),
                        ]); 
            if($InfoBoardDel){
                return json_encode(['status'=> 'success','message' => 'Customer Info  deleted successfully']);
            }else{
                return json_encode(['status'=> 'failed','message' => 'failed to delete customer']);
            }
        }
      
    }
    public function deletequote(Request $request){
        $postdata = $request->all();
        if(! Gate::allows('quote_delete')) 
        {
            return abort(401);
        }else{
            $QuoteBoardDel=DB::table("ggt_customer_quote_board")
                ->where('id','=',$postdata['id'])            
                ->update([
                            'deleted_at'=>date('Y-m-d H:i:s'),
                        ]); 
            if($QuoteBoardDel){
                return json_encode(['status'=> 'success','message' => 'Quote Info  deleted successfully']);
            }else{
                return json_encode(['status'=> 'failed','message' => 'failed to delete quote']);
            }
        }
    }
}

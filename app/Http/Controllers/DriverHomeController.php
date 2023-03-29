<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperatorHomeBanner;
use App\Models\Operator;
use App\Models\DriverOfMonth;
use App\Models\DriverKatta;
use App\Models\GogotruxMitr;
use App\Models\InformationBoard;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Gate;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Validator;
use Config;
use DB;
use File;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use Carbon\Carbon;

class DriverHomeController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('home_screen_manage')) {
            return abort(401);
        }
        else{
            //get home screen banner images
            $data = OperatorHomeBanner::select('id','banner_image','created_at')->where('is_deleted',0)->get()->toArray();
            foreach($data as $key => $values){
                $names = explode('/',$data[$key]['banner_image']);
                $data[$key]['banner_image'] = end($names);
            }
            $drivermittr = GogotruxMitr::select('id','ggt_mitr_text','ggt_mitr_image')->where('deleted_at',null)->orderByDesc('id')->take(1)->get()->toArray();
            if(!empty($drivermittr)){
                $mittr_id = $drivermittr[0]['id'];
            }
            else{
                $mittr_id = "";
            }
            //dd($drivermittr);
            //get information board data
            $info_board = InformationBoard::select('id','info_board_text')->whereNull('deleted_at')->get()->toArray();

            //get driver of the month list
            $drivers_of_month = DriverOfMonth::select('ggt_driver_of_month.id','ggt_driver_of_month.op_mobile_no','ggt_drivers.driver_first_name','ggt_drivers.driver_last_name')->join('ggt_drivers', 'ggt_drivers.driver_mobile_number', '=', 'ggt_driver_of_month.op_mobile_no')->whereNull('ggt_driver_of_month.deleted_at')->get()->toArray();
            //get driver of the driver katta
            $driver_katta = DriverKatta::select('id','katta_up_slider','katta_useful_up_link','katta_bottom_slider','katta_useful_down_link','katta_mt_tips_image','katta_bd_assistance','katta_load_unload_image','katta_essential_tool_image')->where('deleted_at',null)->orderByDesc('id')->take(1)->get()->toArray();
            if(sizeof($driver_katta)>0){
            	$edit_id = $driver_katta[0]['id'];
                //driver katta upslider                 
                if(isset($driver_katta[0]['katta_up_slider'])){
	                $type = 'katta_up_slider';
	                $katta_up_slider = json_decode($driver_katta[0]['katta_up_slider']);
	                $driverkatta['url_katta_up_slider']['driverkatta_img_data'] =  $this->getKattaImages($katta_up_slider,$type);
	                $name = $driverkatta['url_katta_up_slider']['driverkatta_img_data'];
	                $imagedriverkatta['upslider_image'] =$name;	
                }
                else{
                	$imagedriverkatta['upslider_image']['driverkatta_img_data'] = "";
                	$imagedriverkatta['upslider_image'] = "";
                }
                //driver katta bottom slider(LAUGHTER corner)
                if(isset($driver_katta[0]['katta_bottom_slider'])){
	                $type = 'katta_bottom_slider';
	                $katta_bottom_slider = json_decode($driver_katta[0]['katta_bottom_slider']);
	                $driverkatta['katta_bottom_slider']['driverkatta_img_data'] =  $this->getKattaImages($katta_bottom_slider,$type);
	                $name = $driverkatta['katta_bottom_slider']['driverkatta_img_data'];
	                $imagedriverkatta['laughter_image'] =$name;
                }
                else {
                 	$imagedriverkatta['laughter_image']['driverkatta_img_data'] = "";
                 	$imagedriverkatta['laughter_image'] = "";

                }
                //driver katta maintance tip
                if(isset($driver_katta[0]['katta_mt_tips_image'])){
                    $type = 'katta_mt_tips_image';
                    $katta_mt_tips_image = json_decode($driver_katta[0]['katta_mt_tips_image']);
                    $driverkatta['url_katta_mt_tips_image'] =  $this->getKattaImages($katta_mt_tips_image,$type);
                    $name = $driverkatta['url_katta_mt_tips_image']['driverkatta_img_data'][0]['img_preview'];
                    //$mt_image_name =$driverkatta['url_katta_mt_tips_image']['driverkatta_img_data'][0]['img_name'];
                    $imagedriverkatta['url_katta_mt_tips_image'] = $name;
                    //$imagedriverkatta['katta_mt_tips_name']=$mt_image_name;

                }
                else{
                    $imagedriverkatta['url_katta_mt_tips_image'] = "";
                }
                //driver katta loading and unloading 
                if(isset($driver_katta[0]['katta_load_unload_image'])){
                    $type = 'katta_load_unload_image';
                    $katta_load_unload_image = json_decode($driver_katta[0]['katta_load_unload_image']);
                    $driverkatta['url_katta_load_unload_image'] =  $this->getKattaImages($katta_load_unload_image,$type);
                    $name = $driverkatta['url_katta_load_unload_image']['driverkatta_img_data'][0]['img_preview'];
                    //$load_image_name =$driverkatta['url_katta_load_unload_image']['driverkatta_img_data'][0]['img_name'];
                    $imagedriverkatta['url_katta_load_unload_image'] = $name;
                    //$imagedriverkatta['katta_load_tips_name']=$load_image_name;

                }
                else{
                    $imagedriverkatta['url_katta_load_unload_image'] = "";
                }
                //driver katta essential tool 
                if(isset($driver_katta[0]['katta_essential_tool_image'])){
                    $type = 'katta_essential_tool_image';
                    $katta_essential_tool_image = json_decode($driver_katta[0]['katta_essential_tool_image']);
                    $driverkatta['katta_essential_tool_image'] =  $this->getKattaImages($katta_essential_tool_image,$type);
                    $name = $driverkatta['katta_essential_tool_image']['driverkatta_img_data'][0]['img_preview'];
                    //$essential_tool_image_name =$driverkatta['katta_essential_tool_image']['driverkatta_img_data'][0]['img_name'];
                    //$imagedriverkatta['katta_essential_tips_name']=$essential_tool_image_name;
                    $imagedriverkatta['katta_essential_tool_image'] = $name;
                }
                else{
                $imagedriverkatta['katta_essential_tool_image'] = "";
                }
		
                //usefull link data 
                if(isset($driver_katta[0]['katta_useful_up_link'])){
                    $driverkatta['katta_useful_up_link'] = json_decode($driver_katta[0]['katta_useful_up_link'],true);
                }
                else{
                    $driverkatta['katta_useful_up_link'] = null;
                }
                                    
                //external link 
                if(isset($driver_katta[0]['katta_useful_down_link'])){
                    $driverkatta['katta_useful_down_link'] = json_decode($driver_katta[0]['katta_useful_down_link'],true);
                }
                else{
                    $driverkatta['katta_useful_down_link'] = null;
                }
                
                if(isset($driver_katta[0]['katta_bd_assistance'])){
                    $driverkatta['katta_bd_assistance'] = json_decode($driver_katta[0]['katta_bd_assistance'],true);
                }
                else{
                    $driverkatta['katta_bd_assistance'] = null;
                }
            }
        	else
            {
                $edit_id = null;
                $imagedriverkatta = null;
                $driver_katta = null;
            }        
            // dd($imagedriverkatta);
            //$header = "All Operators Numbers";
            return view('admin.driverhome.index', compact('data', 'operators_numbers','drivers_of_month','info_board','imagedriverkatta', 'driver_katta','edit_id','drivermittr','mittr_id'));
        }   
    }
    public function getKattaImages($imgArray,$type){
         if(!empty($imgArray)){
                $rows = [];
                $row = [];
                foreach ($imgArray as $key => $imgs) {
			
                    //$saveAsPath = '/tmp/';
                    //$filename_array = explode('/', $imgs);
                    //$download_url = $filename_array[count($filename_array) - 1];
                    //$tempFileName = end($filename_array);
                    //$filename = $this->aws->downloadFromS3($imgs, $saveAsPath);

                    if(isset($imgArray)){
                        //$path = $saveAsPath.$filename;
                        //$file = File::get($path);
                        //$type = File::mimeType($path);
                        //$fcontent= base64_encode($file);
                        //$images['driverkatta_img_preview'] = $fcontent;
                       // $images['driverkatta_img_type'] = $type;
                       // $fname = explode('-', $filename);
                        //$images['driverkatta_img_name'] = end($fname);
                        //$img_frmt = 'data:'.$type.';base64,';
                        array_push($rows, [
                            //'img_name' => $images['driverkatta_img_name'],
                            //'img_type' => $images['driverkatta_img_type'],
                            'img_preview' => $imgs
                        ]);
                    }else{
                        $driverkatta['driverkatta_img_preview'] = null;
                        $driverkatta['driverkatta_img_type'] = null;
                        $driverkatta['driverkatta_img_name'] = null;
                    }
                    //$driverkatta['driverkatta_pic_url'] = $saveAsPath.$tempFileName;
                    //$driverkatta['driverkatta_img_data'] = $rows;
                }
                $driverkatta['driverkatta_img_data'] = $rows;

                return $driverkatta;

        }
        else{
            Log::warning("empty request:DriverHomeController");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.driverhome.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //get admin details
        $user = Auth::getUser();
        $admin_id = $user->id;
        $admin_email = $user->email;
        //function to upload home banner (operator side) images 
        $bucketname = Config::get('custom_config_file.public-bucket');
        $amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');

        if(isset($request->home_banner_pic))
        {            
            $dir = Config::get('custom_config_file.home_banner_img');
            $image_url = null; 
                
            if(!file_exists($dir))
            {
                mkdir($dir);
            }
            $home_banner_pic = $request->home_banner_pic;
            $data = date('Y_m_d_H_i_s');
            $home_banner = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->home_banner_pic->getClientOriginalName());
            $home_banner = str_replace('-', '_',$home_banner);
            $new_file_name = str_replace(' ', '',$data."-"."homebanner"."-".$home_banner);
            $image_path = "$dir/$new_file_name";                
            $home_banner_pic->move($dir,$new_file_name);
            $this->commonFunction->compressImage($image_path);
            $image_url =$this->aws->uploadToPublicS3($new_file_name ,$image_path, $this->bucketname);
            // $image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);
            $new_op_path=$this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
            
            if(!empty($image_url)){                              
                
                $input = array(
                    'admin_id' => $admin_id,
                    'admin_email' => $admin_email,
                    'banner_image' => $new_op_path,
                    );
                $user = OperatorHomeBanner::create($input);

                return redirect()->route('driverhome.index')->with('success', 'Banner images uploaded successfully.');
    
            }else{
                return view('admin.driverhome.create')->with('Failed', 'Something went wrong please try again.');
            }                    
        }
        else{
            return view('admin.driverhome.create')->with('Failed', 'Something went wrong please try again.');   
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    
        if (! Gate::allows('driverhome_edit')) 
        {
            return abort(401);
        }else{
            $bucketname = Config::get('custom_config_file.public-bucket');
            $amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');

            $header = "Operators";
            $verification_status = [];

            $image = OperatorHomeBanner::select('banner_image')->where('id','=',$id)->where('is_deleted',0)->first();
            
            if(!empty($image)){
                $verification_status['status'] = true;
                if(isset($image->banner_image)){
                    $saveAsPath = '/tmp/';
                    $filename_array = explode('/', $image->banner_image);
                    $download_url = $filename_array[count($filename_array) - 1];
                    $tempFileName = end($filename_array);
                    $filename = $this->aws->downloadFromS3($image->banner_image, $saveAsPath);
                    
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
                    $image->banner_image = $b64image;
                }
                else{
                    $image->banner_image = null;
                }
            }else{
                $image->banner_image = null;
            }

            $image = OperatorHomeBanner::findOrFail($id);
            return view('admin.driverhome.edit', compact('image','b64image'));   
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
        
        //delete previous image from s3 then upload new  
        // dd($request->all());
        $old_image = OperatorHomeBanner::select('banner_image')->where('id',$id)->first();
        if(!empty($old_image->banner_image)){
                $deletedfile = $this->aws->deleteFileFromS3($old_image->banner_image);
        }
        
        //function to update home banner (operator side) images 
        $bucketname = Config::get('custom_config_file.public-bucket');
        $amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');

        if(isset($request->home_banner_pic))
        {            
            $dir = Config::get('custom_config_file.home_banner_img');
            $image_url = null; 
                
            if(!file_exists($dir))
            {
                mkdir($dir);
            }
            
            $home_banner_pic = $request->home_banner_pic;
            $data = date('Y_m_d_H_i_s');
            // $home_banner = $request->home_banner_pic->getClientOriginalName();
            $home_banner = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->home_banner_pic->getClientOriginalName());
            $home_banner = str_replace('-', '_',$home_banner);
            $new_file_name = str_replace(' ', '',$data."-"."homebanner"."-".$home_banner);
            $image_path = "$dir/$new_file_name";                
            $home_banner_pic->move($dir,$new_file_name);
            $this->commonFunction->compressImage($image_path);
            $image_url =$this->aws->uploadToPublicS3($new_file_name ,$image_path, $this->bucketname);
            $new_op_path=$this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
            
            if(!empty($image_url)){                              
                
                $dataArr = array(
                'banner_image' => $new_op_path,
                );
                $image = OperatorHomeBanner::where('id',$id)->update($dataArr);
                                
                return redirect()->route('driverhome.index')->with('success', 'Banner images updated successfully.');
    
            }else{
                return view('admin.driverhome.create')->with('Failed', 'Something went wrong please try again.');
            }                    
        }
        else{
            return view('admin.driverhome.create')->with('Failed', 'Something went wrong please try again.');   
        }
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        
        if(! Gate::allows('driverhome_destroy')) {
            return abort(401);
        }else{
            if ($req->delete_type == 'banner'){
                $id = $req->id;
                $image = OperatorHomeBanner::findOrFail($id);
                if(!empty($image->banner_image)){
                    $deletedfile = $this->aws->deleteFileFromS3($image->banner_image);
                }
                $image->delete();    
            }elseif ($req->delete_type == 'infoboard') {
                $id = $req->id;
                $data = InformationBoard::findOrFail($id);
                $data->delete(); 
            }elseif ($req->delete_type == 'driverofmonth') {
                $id = $req->id;
                $driver = DriverOfMonth::findOrFail($id);
                $driver->delete();  
            }       
            
            return json_encode(['status' => 'success', 'message' => 'Deleted successfully!']);
        }  
    }

    //function to delete image from s3
    public function deleteFileFromS3($url) {
        $s3_bucket_array = explode('/',$url);
        $ar_count = count($s3_bucket_array);
        if(!empty($s3_bucket_array) && $ar_count > 3) 
        {
            $bucketname = $s3_bucket_array[3]; //bucketname
            $key = '';
            $key = $s3_bucket_array[4]; //key
            $filename_array = explode('/', $key);
            $filename = $filename_array[count($filename_array) - 1];  //filename
            
            $s3 = S3Client::factory(array(
                'version' => 'latest',
                'region' => 'us-east-1'
            ));
            try
            {
                $result = $s3->deleteObject(['Bucket' => $bucketname, 'Key' => $key]);
                return true;
            }
            catch(Exception1 $e)
            {
                return false;
            }
        }
    }

    //function to create information board
    public function createInformationBoard()
    {
        return view('admin.informationboard.create');
    }

    //function to store information board
    public function storeInformationBoard(Request $request){
        $postData = $request->all();
        if(!empty($postData)){
            $user = Auth::getUser();
            $admin_id = $user->id;
            $admin_email = $user->email;

            $input = array(
                        'admin_id' => $admin_id,
                        'info_board_text' => $request->info_board_text,
                        'created_by' => $admin_email,
                    );
            $driver_katta_data = InformationBoard::create($input);
            return redirect()->route('driverhome.index')->with('success', 'Information board added successfully.');

        }else{
            Log::warning("empty request:DriverHomeController");   
        }
    }

    //function to edit information board
    public function editInformationBoard($id){
        if(!empty($id)){
            $info_board = InformationBoard::select('id','info_board_text')->where('id',$id)->first();
            return view('admin.informationboard.edit', compact('info_board'));
        }else{
            Log::warning("empty request:DriverHomeController");   
        }
    }

    //function to update information board
    public function updateInformationBoard(Request $request, $id){
        $postData = $request->all();
        if(!empty($postData)){
            $user = Auth::getUser();
            $admin_id = $user->id;
            $admin_email = $user->email;
            $input = array(
                'admin_id' => $admin_id,
                'info_board_text' => $request->info_board_text_edit,
                'created_by' => $admin_email,
            );
            $image = InformationBoard::where('id',$id)->update($input);
                            
            return redirect()->route('driverhome.index')->with('success', 'Information board updated successfully.');
        }else{
            Log::warning("empty request:DriverHomeController");
        }
        
    }
 
    //function to store ggt driver katta
    public function driverKatta(Request $request){
        // dd($request->all());
        $postData = $request->all();
        // if(count($_FILES['katta_up_slider'])) {
        //     foreach ($_FILES['katta_up_slider'] as $file) {
        //     //do your upload stuff here
        //     // dd($file);
        //     }
        // }
        if(!empty($postData)){
             $update_data = array();
            if(isset($postData['useful_link'])){
                $useful_links = $postData['useful_link'];
                $useful_links = array_values($useful_links);
                $update_data['katta_useful_up_link'] = $useful_link = json_encode($useful_links); 
            }else{
                $useful_link = null;
                $update_data['katta_useful_up_link'] = null;
            }
            if(isset($postData['useful_down_link'])){
                $useful_down_links = $postData['useful_down_link']; 
                 $useful_down_links = array_values($useful_down_links);
               $update_data['katta_useful_down_link']= $useful_down_link = json_encode($useful_down_links);
            }else{
                $useful_down_link = null;
                $update_data['katta_useful_down_link'] = null;
            }
            if(isset($postData['bd_assistance'])){
                $bd_assistance = $postData['bd_assistance'];
                $bd_assistance = array_values($bd_assistance);
                $update_data['katta_bd_assistance'] = $bd_assistances = json_encode($bd_assistance);

            }else{
                $bd_assistances = null;
                $update_data['katta_bd_assistance'] = null;
            }

            if(isset($postData['katta_up_slider'])){
                $type = 'katta_up_slider'; 
                //$this->uploadKattaImages($postData['katta_up_slider'],$type);
                $update_data['katta_up_slider'] = $url_katta_up_slider = json_encode($this->uploadKattaImages($postData['katta_up_slider'],$type));

            }
            if(isset($postData['katta_bottom_slider'])){
                $type = 'katta_bottom_slider';
                //$this->uploadKattaImages($postData['katta_bottom_slider'],$type);
                $update_data['katta_bottom_slider'] = $url_katta_bottom_slider = json_encode($this->uploadKattaImages($postData['katta_bottom_slider'],$type));
            }
            if(isset($postData['katta_mt_tips'])){
                $type = 'katta_mt_tips';
                //$this->uploadKattaImages($postData['katta_mt_tips'],$type);
                $update_data['katta_mt_tips_image'] = $url_katta_mt_tips = json_encode($this->uploadKattaImages($postData['katta_mt_tips'],$type));
            }
            if(isset($postData['katta_load_unload_image'])){
                $type = 'katta_load_unload_image';
                //$this->uploadKattaImages($postData['katta_load_unload_image'],$type);
                $update_data['katta_load_unload_image'] = $url_katta_load_unload_image = json_encode($this->uploadKattaImages($postData['katta_load_unload_image'],$type));
            }
            if(isset($postData['katta_essential_tool_image'])){
                $type = 'katta_essential_tool_image';
                //$this->uploadKattaImages($postData['katta_essential_tool_image'],$type);
               $update_data['katta_essential_tool_image'] = $url_katta_essential_tool_image = json_encode($this->uploadKattaImages($postData['katta_essential_tool_image'],$type));
            }

            //upload to database
            $user = Auth::getUser();
            $admin_id = $user->id;

            //check update or insert
            if(isset($postData['edit_id']) && !empty($postData['edit_id'])){
            	// dd($postData['edit_id']);
                $update_data['admin_id'] = $admin_id;
                $update_data['driver_katta_text'] = 'edit_text';
                $update_data['updated_at'] = $this->todays;
//                dd($update_data);
                $update_katta=DriverKatta::where("id",$postData['edit_id'])->update($update_data);

                // dd($update_data,$update_katta);
                return redirect()->route('driverhome.index')->with('success', 'Driver Katta updated successfully.');
            }else{
                    $input = array(
                    'admin_id' => $admin_id,
                    'katta_up_slider' => isset($url_katta_up_slider) ? $url_katta_up_slider : null,
                    'driver_katta_text' => '',
                    'katta_bottom_slider' =>isset($url_katta_bottom_slider) ? $url_katta_bottom_slider:null,
                    'katta_mt_tips_image' => isset($url_katta_mt_tips) ? $url_katta_mt_tips:null,
                    'katta_useful_up_link' => $useful_link,
                    'katta_useful_down_link' => $useful_down_link,
                    'katta_bd_assistance' => $bd_assistances,
                    'katta_load_unload_image' =>isset($url_katta_load_unload_image) ?  $url_katta_load_unload_image:null,
                    'katta_essential_tool_image' => isset($url_katta_essential_tool_image) ? $url_katta_essential_tool_image:null,
                );
                $driver_katta = DriverKatta::create($input);
            }
            return redirect()->route('driverhome.index')->with('success', 'Driver Katta updated successfully.');

        }else{
            Log::warning("empty request:DriverHomeController");
        }
    }

    //function to store gogotrux mitr 
    public function gogotruxMitr(Request $request){
        $postData = $request->all();
        if(!empty($postData)){
            $user = Auth::getUser();
            $admin_id = $user->id;
            $admin_email = $user->email;
            //upload gogotrux mitr image to s3
            if(!empty($postData['mittr_image'])){
                $bucketname = Config::get('custom_config_file.public-bucket');
                $amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
                $imgArray = $postData['mittr_image'];
                if(!empty($imgArray) && $imgArray != 'null'){
                    $dir = Config::get('custom_config_file.home_banner_img');
                    $image_url = null; 
                    if(!file_exists($dir))
                    {
                        mkdir($dir);
                    }
                    $mitr_image = $imgArray;
                    $data = date('Y_m_d_H_i_s');
                    $ggt_mitr = preg_replace('/[^a-zA-Z0-9_.]/', '', $imgArray->getClientOriginalName());
                    $ggt_mitr = str_replace('-', '_',$ggt_mitr);
                    $new_file_name = str_replace(' ', '',$data."-"."driver-katta"."-".$ggt_mitr);
                    $image_path = "$dir/$new_file_name";                
                    $mitr_image->move($dir,$new_file_name);
                    $this->commonFunction->compressImage($image_path);
                    $image_url =$this->aws->uploadToPublicS3($new_file_name ,$image_path, $this->bucketname);
                    $new_op_path=$this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
                    if(!empty($image_url))
                    {
                        if(!empty($postData['mittr_id'])){
                        $input = array(
                                'admin_id' => $admin_id,
                                'ggt_mitr_text' => $postData['mitr_text'],
                                 'ggt_mitr_image' => $new_op_path,
                                'created_by' => $admin_email,
                        );      
                        $mitr_data = GogotruxMitr::where("id",$postData['mittr_id'])->update($input);
                        return redirect()->route('driverhome.index')->with('success', 'Gogotrux mitr updated successfully.');
                        }
                        else{
                        $filearray = $amazon_s3_url.$image_url;
                        $input = array(
                            'admin_id' => $admin_id,
                            'ggt_mitr_text' => $postData['mitr_text'],
                            'ggt_mitr_image' => $new_op_path,
                            'created_by' => $admin_email,
                        );
                        $mitr_data = GogotruxMitr::create($input);
                        return redirect()->route('driverhome.index')->with('success', 'Gogotrux mitr updated successfully.');
                        }
                    }
                }
            }
            else{
                 $input = array(
                    'admin_id' => $admin_id,
                    'ggt_mitr_text' => $postData['mitr_text'],
                    'created_by' => $admin_email,
                );
                $mitr_data = GogotruxMitr::where("id",$postData['mittr_id'])->update($input);
                return redirect()->route('driverhome.index')->with('success', 'Gogotrux mitr updated successfully.');
            }
        }           
        else{
            return redirect()->route('driverhome.index')->with('error', 'Some thing went wrong try again.');
        }     
        Log::warning("empty request:DriverHomeController");
        return redirect()->route('driverhome.index')->with('error', 'Some thing went wrong try again.');
        }
            
    
    public function uploadKattaImages($imgArray,$type){
            $bucketname = Config::get('custom_config_file.public-bucket');
            $amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
            if(isset($imgArray))
            {            
                $dir = Config::get('custom_config_file.home_banner_img');
                $image_url = null; 
                $i = 0;                    
                if(!file_exists($dir))
                {
                    mkdir($dir);
                }
                $filearray = array();
                foreach ($imgArray as $key => $img) {
                    if(!empty($img) && $img != 'null'){
                        $driver_katta_image = $img;
                        $data = date('Y_m_d_H_i_s');
                        $driver_katta = preg_replace('/[^a-zA-Z0-9_.]/', '', $img->getClientOriginalName());
                        $driver_katta = str_replace('-', '_',$driver_katta);
                        $new_file_name = str_replace(' ', '',$data."-"."driver-katta"."-".$driver_katta);
                        $image_path = "$dir/$new_file_name";                
                        $driver_katta_image->move($dir,$new_file_name);
                        $this->commonFunction->compressImage($image_path);
                        $image_url =$this->aws->uploadToPublicS3($new_file_name ,$image_path, $this->bucketname);
                        if(!empty($image_url ))
                        {
                            $filearray[$i] = $amazon_s3_url.$image_url;
                        }
                        else
                        {
                            $filearray = NULL;
                        } 
                        $i++;
                    }                
                    // $new_op_path=$this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
                }
                if($type == "katta_up_slider" || $type == "katta_bottom_slider")
                {
                    $merged_array = array_merge($filearray);
                    $merged_array = str_replace('\/','',$merged_array);
                    return $merged_array;
                }
                else
                {
                    return  $new_op_path = $filearray;
                }
            }
            else{
               Log::warning("empty request:DriverHomeController"); 
            }
        }     
}
 

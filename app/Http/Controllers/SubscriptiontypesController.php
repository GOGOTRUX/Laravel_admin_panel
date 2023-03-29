<?php

namespace App\Http\Controllers;

use App\Models\Subscriptiontypes;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Validator;
use DB;
use App\Http\Requests\Admin\storeSubscriptionType;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use File;
use Config;
use App\Http\Controllers\CustomAwsController;

class SubscriptiontypesController extends Controller
{
	public function __construct()
	{
		// if (! Gate::allows('subscription_scheme_create')) {
		// 	return abort(401);
		// }
		// else{
			$this->aws = new CustomAwsController;        
			$this->bucketname = Config::get('custom_config_file.bucket-name');
			$this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
			$this->commonFunction = new CommonController;
		// }
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		if (! Gate::allows('subscription_type_manage')) 
		{
			return abort(401);
		}
		else{
			$subtypes=Subscriptiontypes::where('is_deleted','=',0)->get();

			if(!empty($subtypes)){
				foreach ($subtypes as $key => $value) {
					$parse_date_from = Carbon::parse($value['created_at']);
        			$subtypes[$key]['created_at'] = Carbon::createFromFormat('Y-m-d', $parse_date_from->toDateString())->toFormattedDateString();
				}
			}else{
				$subtypes = array();				
			}

			return view('admin.subscriptiontype.index', compact('subtypes'));        
		}

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if (! Gate::allows('subscription_type_manage')) {
			return abort(401);
		}
		else{
			
			return view('admin.subscriptiontype.create');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(storeSubscriptionType $request)
	{
		if (! Gate::allows('subscription_type_manage')) {
			return abort(401);            
		}
		else{

			$validated = $request->validated();
			
			if($request->has('subscription_type_name')){
				$subscription_type_name = $request->input('subscription_type_name');
				$subscription_type_name = trim(preg_replace('/\s+/',' ', $subscription_type_name));
			}
			$created_by = Auth::User()->name;
			$subtypes = new Subscriptiontypes();
			$subtypes->subscription_type_name = $subscription_type_name;
			$subtypes->subscription_created_by = $created_by;
			$subtypes->is_active = $request->input('is_active');
			$subtypes->save();

			if(isset($request->subscription_type_image))
			{            
				$dir = Config::get('custom_config_file.dir_profile_img');
				$image_url = null; 
					
				if(!file_exists($dir))
				{
					mkdir($dir);
				}
				
				$subscription_type_image = $request->subscription_type_image;
				$data = date('Y_m_d_H_i_s');
				// $image_name = $request->file('subscription_type_image')->getClientOriginalName();
				// $image_name = str_replace('-', '_',$image_name);
				$image_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->file('subscription_type_image')->getClientOriginalName());
				$new_file_name = str_replace(' ', '',$subtypes->id."-"."subscription_scheme"."-".$image_name);
				$image_path = "$dir/$new_file_name";                
				$subscription_type_image->move($dir,$new_file_name);
				$this->commonFunction->compressImage($image_path);
				$image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);
				if($image_url){

					$new_op_path = $this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
				}else{
					$new_op_path = null;
				}
				$subtypes->subscription_type_image = $new_op_path;
				$subtypes->save();
			}

			return redirect()->route('subscriptions.index')->with('success', 'New subscription types has been created successfully!');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\subscriptiontypes  $subscriptiontypes
	 * @return \Illuminate\Http\Response
	 */
	public function show(subscriptiontypes $subscriptiontypes)
	{
		if (! Gate::allows('subscription_type_manage')) {
			return abort(401);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\subscriptiontypes  $subscriptiontypes
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request , $id)
	{
		if (! Gate::allows('subscription_type_manage')) {
			return abort(401);            
		}
		else{
			//find recored on subscription_type_id releated data
			$subtypes = Subscriptiontypes::find($id, ['subscription_type_id', 'subscription_type_name','subscription_type_image','subscription_created_by','is_active','created_at']);
			// $created_at = $subtypes['created_at']->toFormattedDateString();

			if(isset($subtypes->subscription_type_image)){
				$saveAsPath = '/tmp/';
				$filename_array = explode('/', $subtypes->subscription_type_image);
				$download_url = $filename_array[count($filename_array) - 1];
				$tempFileName = end($filename_array);
				$is_file_exists = File::exists($saveAsPath.$tempFileName);
				if($is_file_exists){
					$filename = $tempFileName;
				}
				else{
					$filename = $this->aws->downloadFromS3($subtypes->subscription_type_image, $saveAsPath);
				}
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
				$subtypes->subscription_type_image = $b64image;
			}
			return view('admin.subscriptiontype.edit', compact('subtypes'));
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\subscriptiontypes  $subscriptiontypes
	 * @return \Illuminate\Http\Response
	 */
	public function update(storeSubscriptionType $request, $id)
	{
		if (! Gate::allows('subscription_type_manage')) {
			return abort(401);
		}
		else{

			$validated = $request->validated();
			
			if($request->has('subscription_type_name')){
				$subscription_type_name = $request->input('subscription_type_name');
				$subscription_type_name = trim(preg_replace('/\s+/',' ', $subscription_type_name));
			}

			$subtypes = Subscriptiontypes::find($id);
			$subtypes->subscription_type_name = $subscription_type_name;
			$subtypes->is_active = $request->input('is_active');
			$subtypes->save();

		 	if(isset($request->subscription_type_image))
			{            
				$dir = Config::get('custom_config_file.dir_profile_img');
				$image_url = null; 
					
				if(!file_exists($dir))
				{
					mkdir($dir);
				}
				
				$subscription_type_image = $request->subscription_type_image;
				$data = date('Y_m_d_H_i_s');
				// $image_name = $request->file('subscription_type_image')->getClientOriginalName();
				// $image_name = str_replace('-', '_',$image_name);
				$image_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->file('subscription_type_image')->getClientOriginalName());
				$new_file_name = str_replace(' ', '',$subtypes->id."-"."subscription_scheme"."-".$image_name);
				$image_path = "$dir/$new_file_name";                
				$subscription_type_image->move($dir,$new_file_name);
				$this->commonFunction->compressImage($image_path);
				$image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);
				if($image_url){

					$new_op_path = $this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
				}else{
					$new_op_path = null;
				}
				$subtypes = Subscriptiontypes::find($id);
				$subtypes->subscription_type_image = $new_op_path;
				$subtypes->save();
			}


		 	// $subtypes=DB::table('subscription_types')
				// ->where("subscription_types.subscription_type_id", '=',$request->subscription_type_id)
				// ->update(['subscription_type_name'=> $subscription_type_name,
				// 	  'is_active'=>$request->is_active,                  
				// 	  // 'subscription_created_by'=> $request->subscription_created_by,
		 	// ]);

			return redirect()->route('subscriptions.index')->with('success', 'Subscription types has been updated successfully!');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\subscriptiontypes  $subscriptiontypes
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request,$id)
	{
		if (! Gate::allows('subscription_type_manage')) {
			return abort(401);
		}
		else{
			$subtypes=DB::table('subscription_types')
				->where("subscription_types.subscription_type_id", '=',$id)
				->update(['is_deleted'=>1]);

			return redirect()->route('subscriptions.index')->with('success', 'Subscription Types has been deleted successfully!');
		}
	}

	public function deleteSelected(Request $request)
	{
		if (! Gate::allows('subscription_type_manage'))
		{
			return abort(401);
		}
		else{
			foreach ($request->selectid as $deleteid)
			{                        
				$vehicles=DB::table("subscription_types")
						->where('subscription_type_id','=',$deleteid)            
						->update(['deleted_at'=>date('Y-m-d H:i:s')]);  
			}        
			return "Multiple Vehicle Types deleted successfully"; 
		}   
	}

	public function checkSubscriptionScheme(Request $request){
		if($request->has('subscription_type_name')){
			$subscription_type_name = $request->input('subscription_type_name');
			$subscription_type_name = trim(preg_replace('/\s+/',' ', $subscription_type_name));
		}

		$is_scheme_exist = Subscriptiontypes::where('subscription_type_id','!=',$request->input('subscription_type_id'))->where('subscription_type_name',  '=', $subscription_type_name)->exists();

		if($is_scheme_exist){
			$isAvailable = FALSE;	
		}else{
			$isAvailable = TRUE;
		}

		echo json_encode($isAvailable);
	}

	public function getSubscriptionLogo(Request $request){
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
}


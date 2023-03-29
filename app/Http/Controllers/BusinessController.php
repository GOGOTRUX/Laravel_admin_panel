<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\UpdateBusinessRequest;

use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CommonController;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use App\Models\Operator;

use Log;
use DB;
use Config;

class BusinessController extends Controller
{

	public function __construct()
	{
		$this->aws = new CustomAwsController;
		$this->documentController = new DocumentController;
		$this->commonFunction = new CommonController; 
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateBusinessRequest $request, $id)
	{
		try{
			// dd($request->all());
			if(!empty($request) && isset($request->op_user_id)){

				$op_data = Operator::select('op_user_id', 'op_mobile_no', 'op_type_id')->where('op_user_id','=', $request->op_user_id)->first();
				if(!empty($op_data)){
					$op_mobile_no = $op_data->op_mobile_no;
				}
				else{
					$op_mobile_no = null;
				}
				
				if (isset($request->bu_pan_image)) {
					$this->uploadBusinessPAN($request->bu_pan_image, $request->op_user_id, $op_mobile_no);
					sleep(3);
				}
				else{
					Log::warning("empty business pan iamge");
				}

				if (isset($request->op_bu_gstn_available)) {
					if($request->op_bu_gstn_available == 'yes'){
						$request->op_bu_gstn_available = 1;
					}
					else{
						$request->op_bu_gstn_available = 0;
					}
				}

				$dataArr = array(
					'op_bu_name' => $request->op_bu_name,
					'op_bu_email' => $request->op_bu_email,
					'op_payment_mode' => $request->op_payment_mode,
					'op_bu_address_line_1' => $request->op_bu_address_line_1,
					'op_bu_address_line_2' => $request->op_bu_address_line_2,
					'op_bu_address_line_3' => $request->op_bu_address_line_3,
					'op_bu_landmark' => $request->op_bu_landmark,
					'op_bu_address_pin_code' => $request->op_bu_address_pin_code,
					'op_bu_address_state' => $request->op_bu_address_state,
					'op_bu_address_city' => $request->op_bu_address_city,
					'op_bu_gstn_available' => $request->op_bu_gstn_available,
					'op_bu_gstn_no' => $request->op_bu_gstn_no,
					'op_bu_pan_no' => $request->op_bu_pan_no,
					'op_is_verified' => 0, //update operator status -added by nayana(13-nov-2k19)
				);
				$updateBankDoc = Operator::where('op_user_id',$request->op_user_id)->update($dataArr);

				if(isset($request->additional_documents))
				{
					$op_user_id = isset($request->op_user_id) ? $request->op_user_id : null;
					$response = $this->documentController->saveAdditionalDocuments($request->additional_documents, $op_mobile_no, $driver_id = null, $veh_id = null, $op_user_id);
				}
			}
			else{
				Log::warning("empty request:business Controller line:96");	
			}
			return redirect()->route('operators.edit', $request->op_user_id)->with('success', 'Business Information has been updated successfully!');
		}
		catch(Exception $e){
			Log::warning($e);
		}
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

	public function uploadBusinessPAN($pan_image, $op_id, $op_mobile_no){
		if(!empty($pan_image))
		{
			$bucketname = Config::get('custom_config_file.bucket-name');
			$amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
			$dir = Config::get('custom_config_file.dir_profile_img');
			$image_url = null;
			if(!file_exists($dir))
			{
				mkdir($dir);
			}

			// get previous files from s3 and delete it.
			$old_bupan = Operator::where('op_mobile_no', '=',$op_mobile_no)->value('op_bu_pan_no_doc');

			if(!empty($old_bupan)){
				$filedeleteed = $this->aws->deleteFileFromS3($old_bupan);
			}

			// $pan_name = $pan_image->getClientOriginalName();
			$pan_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $pan_image->getClientOriginalName());
			$pan_name = str_replace('-', '_',$pan_name);
			$new_file_name = str_replace(' ', '',$op_mobile_no."-"."bupan"."-".$pan_name);
			$image_path = "$dir/$new_file_name";
			$pan_image->move($dir,$new_file_name);
			$this->commonFunction->compressImage($image_path);
			$image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $bucketname);

			if(!empty($image_url))
			{
				$op_bu_pan_no_doc  = $amazon_s3_url.$image_url;
			}
			else
			{
				$op_bu_pan_no_doc  = null;
			}

			$updateBankDoc = Operator::where('op_user_id',$op_id)->update([
				'op_bu_pan_no_doc' => $op_bu_pan_no_doc,
			]);
		}
		else{
			Log::warning("business pan image empty: php line 180-business Controller");
		}
	}
}

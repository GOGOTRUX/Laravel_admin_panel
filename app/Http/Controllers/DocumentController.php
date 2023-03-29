<?php

namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\CommonController;
use App\Models\DocumentMaster;
use Illuminate\Support\Facades\Gate;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Support\Arr;
use Hash;
use Validator;
use Config;
use DB;
use File;
use Carbon\Carbon;
use Log;
use Response;

class DocumentController extends Controller
{
	
	/**
	* Create a new controller instance.
	*/

	public $bucketname;
	public $amazon_s3_url;

	public function __construct()
	{
		$this->aws = new CustomAwsController;        
		$this->middleware('auth');
		$this->bucketname = Config::get('custom_config_file.bucket-name');
		$this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
		$this->commonFunction = new CommonController;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$document = Document::all();
		return view('admin.document.index', compact('document'));
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
		dd($request->all());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Document  $document
	 * @return \Illuminate\Http\Response
	 */
	public function show(Document $document)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Document  $document
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request,$id)
	{
		 $doc=DB::table('ggt_op_document_details')
				  ->where('ggt_op_document_details.doc_id','=',$request->id)
				  ->first();
									
		
		if(!empty($doc))
		{

			if(isset($doc->doc_images)){
				$saveAsPath = '/tmp/';
				$filename_array = explode('/', $doc->doc_images);
				$download_url = $filename_array[count($filename_array) - 1];
				$tempFileName = end($filename_array);
				$filename = $this->aws->downloadFromS3($doc->doc_images, $saveAsPath);
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
				$doc->doc_images = $b64image;
			}
		}
		if(isset($request->typeof)){
			if($request->typeof == 'driver'){
				$doc_list = $this->getDocList();
			}
			elseif($request->typeof == 'vehicle'){
				$doc_list = $this->getVehicleDocList();
			}
			elseif($request->typeof == 'business'){
				$doc_list = $this->getBusinessDocList();
			}
			else{
				Log::warning("type not match : Document controller: line 129");	
			}
		}
		else{
			Log::warning("type not set");
		}
		// dd($doc_list);
		return view('admin.document.edit', compact('doc','doc_list'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Document  $document
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		// dd($request->all());
		  if(isset($request->is_verified))
				$state=1;
			else
				$state=0;

		if(isset($request->operator_id)){
			$op_data = DB::table("ggt_operator_users")->select('op_user_id', 'op_mobile_no', 'op_type_id')->where('op_user_id','=',$request->operator_id)->first();
			if(!empty($op_data)){
				$op_mobile_no = $op_data->op_mobile_no;
			}
			else{
				$op_mobile_no = null;
			}

			if(!empty($request->doc_expiry)){
				$doc_expiry = Carbon::parse($request->doc_expiry)->format('Y-m-d HH:m:s');
			}else{
				$doc_expiry = null;
			}

			if(isset($request->doc_images) && !empty($request->doc_images))
			{            
				
				$doc_label = DocumentMaster::where('doc_type_id', $request->doc_type_id)->value('doc_label');
				$dir = Config::get('custom_config_file.dir_profile_img');
				$bucketname=config('custom_config_file.bucket-name');
				$amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');

				if(!file_exists($dir))
				{
					mkdir($dir);
				}
				$file = $request->doc_images;
				$file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $file->getClientOriginalName());
				// $file_name = $file->getClientOriginalName();
				$file_name = str_replace('-', '_',$file_name);
				$new_file_name = str_replace(' ', '',$op_mobile_no."-".$doc_label."-".$file_name);

				$image_path = "$dir/$new_file_name";
				$file->move($dir,$new_file_name);
				$this->commonFunction->compressImage($image_path);
				$image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $bucketname);
				sleep(3);
				if($image_url){
					$img_path = $amazon_s3_url.$image_url;
				}
				else{
					$img_path = null;
				}
				$data = [
					'doc_type_id'=> $request->doc_type_id,
					'doc_expiry'=> $doc_expiry,
					'doc_number'=> $request->doc_number,
					'is_verified'=> $state,
					'doc_images'=> $img_path
				];
			}
			else{
				Log::warning("image not available");
				$data = [
					'doc_type_id'=> $request->doc_type_id,
					'doc_expiry'=> $doc_expiry,
					'doc_number'=> $request->doc_number,
					'is_verified'=> $state,
				];
			}
		}
		else{
			Log::warning("operator id not available");
		}

		$document=DB::table('ggt_op_document_details')
					->where("ggt_op_document_details.doc_id", '=',$request->doc_id)
					->update($data);
			
	  	return redirect()->route('operators.edit', $request->operator_id);         
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Document  $document
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{

		$document = Document::findOrFail($request->id);
		$document->delete();
		return json_encode(['status' => 'success', 'message' => 'Document has been deleted successfully!']);
	}

	public function verify(Request $request)
	{
		$verifyDoc = DB::table('ggt_op_document_details')->where('doc_id','=',$request->id)->update(['is_verified'=>1]); 
		if($verifyDoc)
		{
			return json_encode(['status'=> 'success', 'response'=> true]);
		}
		else
		{
			return json_encode(['status'=> 'failed', 'response'=> false]);
		}    
	}

	public function save(Request $request){
		dd($request->all());
	}

	public function saveAdditionalDocuments($request, $op_mobile_no, $driver_id, $veh_id, $op_user_id ){
		if(!empty($request)){
			foreach ($request as $key => $value) {
				if(!empty($value['doc_type_id'])){
					if(!empty($value['doc_images'])){ //upload doc file to s3
						if(!empty($value['doc_images'])) {
							$doc_label = DocumentMaster::where('doc_type_id', $value['doc_type_id'])->value('doc_label');
							$dir = Config::get('custom_config_file.dir_profile_img');
							$bucketname=config('custom_config_file.bucket-name');
							$amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');

							if(!file_exists($dir))
							{
								mkdir($dir);
							}
							// $file_name = $value['doc_images']->getClientOriginalName();
							$file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $value['doc_images']->getClientOriginalName());
							$file_name = str_replace('-', '_',$file_name);
							$new_file_name = str_replace(' ', '',$op_mobile_no."-".$doc_label."-".$file_name);

							$image_path = "$dir/$new_file_name";
							$value['doc_images']->move($dir,$new_file_name);
							$this->commonFunction->compressImage($image_path);
						    $image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $bucketname);
						}
						else{
							Log::warning("image not available");
						}

						if(!empty($image_url)) {
							$imageName  = $amazon_s3_url.$image_url;
						}
						else {
							$imageName  = NULL;
						}
					}
					else{
						Log::warning("image not available");
					}
					if(!empty($value['doc_expiry'])){
						$value['doc_expiry'] = Carbon::parse($value['doc_expiry'])->format('Y-m-d');
					}
					else{
						$value['doc_expiry'] = null;
					}
					// dd($value['doc_expiry']);
					$data = array(
						'doc_type_id' => isset($value['doc_type_id']) ? $value['doc_type_id'] : null,
						'doc_driver_id' => isset($driver_id) ? $driver_id : null,
						'doc_user_id' => isset($op_user_id) ? $op_user_id : null,
						'doc_veh_id' => isset($veh_id) ? $veh_id : null,
						'doc_number' => isset($value['doc_number']) ? $value['doc_number'] : null,
						'doc_images' => isset($imageName) ? $imageName : null, //make default as null-need to change after code of upload image
						'doc_expiry' => $value['doc_expiry'],
						);
					$result = Document::create($data);
					
					// if(empty($result)){
					//  return false;
					// }
				}
				else{
					Log::warning("type id not available");
				} 
			}
			sleep(5);
			return true;
		}
		else{
			return false;
		}
	}

	public function getDocList(){
		$data = DocumentMaster::select('doc_type_id','doc_type', 'doc_label', 'doc_validation', 'is_mandatory', 'is_expiry', 'visibility_step')->whereNotIn('doc_type_id', [1,2])->where('visibility_step', 'driver')->get()->toArray();
		
		return $data;
	}

	public function getBusinessDocList(){
		$data =	DocumentMaster::select('doc_type_id','doc_type', 'doc_label', 'doc_validation', 'is_mandatory', 'is_expiry', 'visibility_step')->whereNotIn('doc_type_id', [1,2])->where('visibility_step', 'business')->get()->toArray();
		return $data;
	}

	public function getVehicleDocList(){
		$data =	DocumentMaster::select('doc_type_id','doc_type', 'doc_label', 'doc_validation', 'is_mandatory', 'is_expiry', 'visibility_step')->whereNotIn('doc_type_id', [1,2])->where('visibility_step', 'vehicle')->get()->toArray();

		if(!empty($data)){
			// sort element by doc_label
			$data = array_values(Arr::sort($data, function ($value) {
	            return $value['doc_label'];
	        }));
			return $data;
		}else
		{
			return $data = null;
		}

	}

	public function getdocImage($doc_image){
		$saveAsPath = '/tmp/';
		$filename_array = explode('/', $doc_image);
		$download_url = $filename_array[count($filename_array) - 1];
		$tempFileName = end($filename_array);
		$filename = $this->aws->downloadFromS3($doc_image, $saveAsPath);
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

	//function for getiing business uploded documents
	public function getBusinessDoc($user_id){
		$Additional_doc = array();
		$add_document_details = DB::table('ggt_op_document_details')
					->join('ggt_doc_types_master','ggt_doc_types_master.doc_type_id','=','ggt_op_document_details.doc_type_id')
					->where('ggt_op_document_details.deleted_at','=',null)
					->where('doc_user_id',$user_id)
					->get()->toArray();

		//need to change query
		if(!empty($add_document_details)){
			foreach ($add_document_details as $key => $value) {
				if(($value->doc_type_id !=14) && ($value->doc_type_id !=15) ){
					unset($add_document_details[$key]);
				}
				elseif(($value->doc_type_id ==1) && ($value->doc_type_id ==2) ){
					unset($add_document_details[$key]);
				}
			}
		}

		// $add_document_details = Document::select('doc_type_id','doc_id','doc_number','doc_images','doc_expiry','doc_veh_id','doc_driver_id')->whereIn('doc_type_id', [14, 15])->where('doc_user_id',$user_id)->whereNotIn('doc_type_id', [1, 2])->get()->toArray();
		return $add_document_details;
	}
	//function for getiing business uploded documents end 

	public function uploadLicImage($image, $op_mobile_no, $img){
		$dir = Config::get('custom_config_file.dir_profile_img');

		if(!file_exists($dir))
		{
			mkdir($dir);
		}
		// $lic_name = $image->getClientOriginalName();
		$lic_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $image->getClientOriginalName());
		$lic_name = str_replace('-', '_',$lic_name);
		$new_lic_file_name = str_replace(' ', '',$op_mobile_no."-".$img."-".$lic_name);
		$image_path = "$dir/$new_lic_file_name";
		$image->move($dir,$new_lic_file_name);
		$this->commonFunction->compressImage($image_path);
		$image_url =$this->aws->uploadToS3($new_lic_file_name ,$image_path, $this->bucketname);
		sleep(3);
		
		if(!empty($image_url))
        {
            $img_path = $this->amazon_s3_url.$image_url;
        	// unlink($image_path);
        }
        else{
            $img_path = $this->amazon_s3_url;
        }
        return $img_path;
	}
}

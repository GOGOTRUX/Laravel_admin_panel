<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomAwsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Hash;
use Validator;
use Config;
use DB;
use File;
use App\Models\User;
use Response;

class ProfileController extends Controller
{

    /**
     * Create a new controller instance.
     */
    
    public function __construct()
    {
        $this->aws = new CustomAwsController;        
        $this->middleware('auth');
    }
     

    /**
     * Where to redirect users after password is changed.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = '/my_profile';

    /**
     * My profile form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMyProfileForm()
    {
        $user = Auth::getUser();
        
        if(isset($user->profile_image)){
            $saveAsPath = '/tmp/';
            $filename_array = explode('/', $user->profile_image);
            $download_url = $filename_array[count($filename_array) - 1];
            $tempFileName = end($filename_array);
            $is_file_exists = File::exists($saveAsPath.$tempFileName);
            if($is_file_exists){
                $filename = $tempFileName;
            }
            else{
                $filename = $this->aws->downloadFromS3($user->profile_image, $saveAsPath);
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
            $user->profile_image = $b64image;
        }

        return view('auth.my_profile', compact('user'));
    }    

    /**
     * Get a validator for an incoming edit profile request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    
    public function editProfile(Request $request)
    {
        $user = Auth::getUser();
        $this->validator($request->all())->validate();
        if ($request->hasFile('profileimage')) 
        {
                
                $bucketname = Config::get('custom_config_file.bucket-name');
                $amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');

                $dir = Config::get('custom_config_file.dir_profile_img');                  
                $image_url = null; 

                if(!file_exists($dir))
                {
                    mkdir($dir);
                }
                
                $doc_pan = $request['profileimage'];  

                $op_mobile_no = date('Y_m_d_H_i_s');

                $pan_name = $doc_pan->getClientOriginalName();
                $pan_name = str_replace('-', '_',$pan_name);

                $new_file_name = str_replace(' ', '',$op_mobile_no."-"."admin-profile"."-".$pan_name);
                $image_path = "$dir/$new_file_name";                
                $doc_pan->move($dir,$new_file_name);
                $image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $bucketname);
                $new_profile_path=$amazon_s3_url.$bucketname.'/'.$new_file_name;
                $setpath=DB::table('ggt_admins')->Where('ggt_admins.id','=',$user->id)->update(['profile_image'=>$new_profile_path]);            
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return redirect($this->redirectTo)->with('success', 'Profile has been updated successfully!');
    }

    /**
     * Get a validator for an incoming edit profile request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email',

        ]);
    }

    /**
        get the admin profile images
    */
    public function getAdminProfile(){
        $user = Auth::getUser();
        $profile_info = null;
        if(isset($user->profile_image)){
            $saveAsPath = '/tmp/';
            $filename_array = explode('/', $user->profile_image);
            $download_url = $filename_array[count($filename_array) - 1];
            $tempFileName = end($filename_array);
            $is_file_exists = File::exists($saveAsPath.$tempFileName);
            if($is_file_exists){
                $filename = $tempFileName;
            }
            else{
                $filename = $this->aws->downloadFromS3($user->profile_image, $saveAsPath);
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
            $user->profile_image = $b64image;
            $profile_info['img_path'] = $b64image;
            $profile_info['img_type'] = $type;
        }else{
            $user->profile_image = null;
            $profile_info['img_path'] = null;
            $profile_info['img_type'] = null;
        }

        if(!empty($user->profile_image)){
            return json_encode(['status' => 'success', 'profile_info' => $profile_info]);
        }else{
            return json_encode(['status' => 'failed', 'profile_info' => null]);
        }
    }
}



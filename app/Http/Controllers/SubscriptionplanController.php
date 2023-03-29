<?php

namespace App\Http\Controllers;

use App\Models\Subscriptionplan;
use App\Models\Subscriptiontypes;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\storeSubscriptionPlan;
use File;
use Config;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\NotificationController;
use Session;
use Response;
use App\Models\User;
use DateTime;
use App\Models\AdminNotificationMessages;
use App\Http\Controllers\CommonController;
use Log;
use App\Models\AdminNotification;
use App\Models\OperatorPayments;

class SubscriptionplanController extends Controller
{
	public function __construct()
	{
		$this->aws = new CustomAwsController;        
		$this->notifiy = new NotificationController;        
		$this->commonFunction = new CommonController;
		$this->bucketname = Config::get('custom_config_file.bucket-name');
		$this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
		$this->todays = Carbon::today()->toDateString();
		
	}

	public function index()
	{
		if (! Gate::allows('subscription_manage')) 
		{
			return abort(401);
		}
		else{
			// All permissions which apply on the user (inherited and direct)
			// $users = User::select('id')->role('Super Admin')->first(); 
			// $users = Auth::user()->getAllPermissions();
			// $result = Auth::user()->hasPermissionTo('subscription_final_approval');
			// dd($users);

			//user created by plans and the plan which are approved i.e =1;
			$subplan = Subscriptionplan::select('subscription_id','subscription_type_name','subscription_amount','subscription_validity_type','subscription_business_rs','subscription_expected_enquiries','subscription_veh_wheel_type','subscription_no_of_veh_allowed','subscription_validity_days','subscription_validity_from','subscription_validity_to','is_free_trial','is_active','is_sent_for_approval','is_approved','is_approved_by','subscription_plan_created_by')->orderBy('subscription_id', 'desc')->get()->toArray();
			// dd($subplan);
			$free_plan_exist = 0;
			$subplanExist = subscriptionplan::where('is_free_trial', '=', 1)->count();
			if ($subplanExist) {
			   $free_plan_exist = 1;
			}

			//->where('subscription_plan_created_by', Auth::user()->id)->where('is_approved', 1)
			if(!empty($subplan)){
				foreach ($subplan as $key => $value) {
					$subplan[$key]['subplan_purchase_count'] = 0;
					if($value['is_free_trial']==1){
						$subplan[$key]['subscription_validity_from'] = null;	
						$subplan[$key]['subscription_validity_to'] = null;	
						$subplan[$key]['subscription_expired'] = 3; 
						$subplan[$key]['subscription_trial_present'] = 1; 
					}else{
						$subplan[$key]['subscription_validity_from'] = Carbon::createFromFormat('Y-m-d', Carbon::parse($value['subscription_validity_from'])->toDateString())->toFormattedDateString();
        			
	        			$subplan[$key]['subscription_validity_to'] = Carbon::createFromFormat('Y-m-d', Carbon::parse($value['subscription_validity_to'])->toDateString())->toFormattedDateString();
	        			$to = $value['subscription_validity_to'];
						$from = $value['subscription_validity_from'];
						if($from > $this->todays){
							$subplan[$key]['subscription_expired'] = 2; //plan yet to be active
							$subplan[$key]['subscription_active_indays'] = Carbon::parse($from)->diffInDays($this->todays);
						}else if($this->todays > $to || $this->todays > $to){
							$subplan[$key]['subscription_expired'] = 0; //plan expired
						}else{
							$subplan[$key]['subscription_expired'] = 1; //plan running
						}
					}
					
					$subplan[$key]['subscription_plan_created_name'] = User::where('id',$value['subscription_plan_created_by'])->value('name');
        			
        			$isSuperAdmin = Auth::User()->hasRole('Super Admin');
        			$result = Auth::user()->hasPermissionTo('subscription_final_approval');

        			//is-editable
					$subplan[$key]['subscription_approve_permission'] = $result;
					$subplan[$key]['isSuperAdmin'] = $isSuperAdmin;
					// dd($result);
        			//is-editable

        			if(!$isSuperAdmin && $value['subscription_plan_created_by']!=Auth::User()->id) {
        				if($value['is_sent_for_approval']==0){
                                unset($subplan[$key]);  
                        }else if($value['is_sent_for_approval']==1 && $result !=true && $value['is_approved']==0){
                                //check sub_verify permission
                                unset($subplan[$key]);  
                        }else{
                        	Log::warning('in else');
                        }   
        			}
        			//account specific subscriptions list end

        			//is-deletable
	        			// $op_payment = OperatorPayments::where('op_order_payment_p_details->sub_scheme_name', 39)->get();
	        			// dd($op_payment);
    				// is_deletable

    				$op_payment = OperatorPayments::get()->toArray();
                 	foreach ($op_payment as $pkey => $pvalue) {
                        $op_order_payment_p_details = json_decode($pvalue['op_order_payment_p_details'],true);
                        if($pvalue['op_order_payment_purpose']=='subscription'){
                        	if($op_order_payment_p_details['sub_scheme_name'] == $value['subscription_id']){
                                $subplan[$key]['subplan_purchase_count'] = 1;
	                                break;
	                        }
                        }
                    }
                    // $isStepPresent = User::whereNull('op_registration_step')->where('op_user_id',$request['id'])->count();
        			//not deletable
				}
				
			}else{
				$subplan = array();				
			}
			return view('admin.subscriptionplan.index', compact('subplan','free_plan_exist'));
		}
	}

	public function create()
	{
		if (! Gate::allows('subscription_create')) 
		{
			return abort(401);
		}
		else{
			
		  	$subscriptionSchemeList = Subscriptiontypes::select('subscription_type_id', 'subscription_type_name')->where('is_active',1)->where('is_deleted','=',0)->get()->toArray();
		  	
			// return view('admin.subscriptionplan.create');
			return redirect()->route('subscriptions.index');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */

	// storeSubscriptionPlan

	public function store(storeSubscriptionPlan $request)
	{
		if (! Gate::allows('subscription_create')) {
			return abort(401);
		}
		else{

			// dd($request->all());
			$validated = $request->validated();
			
			if($request->has('subscription_type_name')){
				$subscription_type_name = $request->input('subscription_type_name');
				$subscription_type_name = trim(preg_replace('/\s+/',' ', $subscription_type_name));
			}

			if($request->input('subscription_validity_type')=='yes'){
				$subscription_validity_type = 'by enquiry';
			}else{
				$subscription_validity_type = 'by value';
			}
			$created_by = Auth::User()->id;

			if(!empty($request->input('sub_multi_wheel_type'))){
				$subscription_no_of_veh_allowed = $request->input('sub_multi_wheel_type');
			}else{
				$subscription_no_of_veh_allowed = 0;
			}
			
			// if(!empty($request->input('sub_multi_wheel_type'))){
			// 	$subscription_veh_wheel_type = $request->input('sub_multi_wheel_type');
			// }else{
			// 	$subscription_veh_wheel_type = $request->input('subscription_veh_wheel_type');
			// }

			$is_approved = $request->has('is_approved') ? 1 : 0;

			$subplan = new Subscriptionplan();
			$subplan->subscription_type_name = $request->input('subscription_type_name');
			$subplan->subscription_amount = $request->input('subscription_amount');
			$subplan->subscription_validity_type = $subscription_validity_type;
			$subplan->subscription_business_rs = $request->has('subscription_business_rs') ? $request->input('subscription_business_rs') : null;
			$subplan->subscription_expected_enquiries = $request->has('subscription_expected_enquiries') ? $request->input('subscription_expected_enquiries') : null;
			$subplan->subscription_veh_wheel_type = $request->input('subscription_veh_wheel_type');
			$subplan->subscription_no_of_veh_allowed = $subscription_no_of_veh_allowed;
			$subplan->subscription_validity_days = $request->has('subscription_validity_days') ? $request->input('subscription_validity_days') : null;
			$subplan->subscription_validity_from = $request->has('subscription_validity_from') ? $request->input('subscription_validity_from') : null; 
			$subplan->subscription_validity_to = $request->has('subscription_validity_to') ? $request->input('subscription_validity_to') : null; 
			// $subplan->is_active = $request->input('is_active');
			$subplan->subscription_plan_created_by = $created_by;
			
			if($request->has('is_free_trial')){
				$subplan->is_free_trial = $request->has('is_free_trial') ? 1 : 0;
			}

			if($is_approved){
				$to = $request->input('subscription_validity_to');
				$from = $request->input('subscription_validity_from');
				if($from > $this->todays && $this->todays < $to){
					$subplan->is_active = 0;
				}else{
					$subplan->is_active =1;
				}
				$subplan->is_sent_for_approval = 1;
				$subplan->is_approved = 1;
				$subplan->is_approved_by = Auth::User()->name;
			}
			// dd($subplan);
			$subplan->save();

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
				$new_file_name = str_replace(' ', '',$subplan->subscription_id."-"."subscription_scheme"."-".$image_name);
				$image_path = "$dir/$new_file_name";                
				$subscription_type_image->move($dir,$new_file_name);
				$this->commonFunction->compressImage($image_path);
				$image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);
				if($image_url){

					$new_op_path = $this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
				}else{
					$new_op_path = null;
				}
				$subplan->subscription_type_image = $new_op_path;
				$subplan->save();
			}
			return redirect()->route('subscriptions.index')->with('success', 'New subscription plan has been created successfully!');

		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\subscriptionplan  $subscriptionplan
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request,$id)
	// public function show(subscriptionplan $subscriptionplan)
	{
		//dd($request->all());
	 	if (! Gate::allows('subscription_view')) {
			return abort(401);
		}else{
			$editsubplan = Subscriptionplan::select('subscription_id','subscription_type_name','subscription_amount','subscription_validity_type','subscription_business_rs','subscription_expected_enquiries','subscription_veh_wheel_type','subscription_validity_days','subscription_validity_from','subscription_validity_to','subscription_type_image','subscription_plan_created_by','is_free_trial','is_active','is_sent_for_approval','is_approved','is_approved_by')->where('subscription_id', $id)->first();
			if(!empty($editsubplan)){
				if(isset($editsubplan->subscription_type_image)){
			    	$b64image = $this->commonFunction->getimageBase64($editsubplan->subscription_type_image);
			    	// $editsubplan->subscription_type_image = $b64image;
			    	$data['sub_image_b64'] = $b64image;
			    }

			    $data['created_by'] = User::where('id',$editsubplan->subscription_plan_created_by)->value('name');
		    	$parse_date_from = Carbon::parse($editsubplan->subscription_validity_from);
		    	$parse_date_to = Carbon::parse($editsubplan->subscription_validity_to);

    			$data['validity_from'] = Carbon::createFromFormat('Y-m-d', $parse_date_from->toDateString())->toFormattedDateString();
    			$data['validity_to'] = Carbon::createFromFormat('Y-m-d', $parse_date_to->toDateString())->toFormattedDateString();
    			$data['created_date'] = Carbon::createFromFormat('Y-m-d', Carbon::parse($editsubplan->created_at)->toDateString())->toFormattedDateString();

    			/* if($parse_date_to > $this->todays){
					$data['subscription_expired'] = 0;
				}else{
					$data['subscription_expired'] = 1;
				} */

				$to = $editsubplan->subscription_validity_to;
				$from = $editsubplan->subscription_validity_from;

				if($from > $this->todays){
					$data['subscription_expired'] = 2; //plan yet to be active
					$data['subscription_active_indays'] = Carbon::parse($from)->diffInDays($this->todays);
				}else if($this->todays > $to || $this->todays > $to){
					$data['subscription_expired'] = 0; //plan expired
				}else{
					$data['subscription_expired'] = 1; //plan running
				}
			}else{
				$editsubplan = null;
			}
			
		}
		return view('admin.subscriptionplan.showdetails', compact('editsubplan','data'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\subscriptionplan  $subscriptionplan
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request,$id)
	{
		if (! Gate::allows('subscription_edit')) 
		{
			return abort(401);
		}
		else{
			
			$editsubplan = Subscriptionplan::select('subscription_id','subscription_type_name','subscription_amount','subscription_validity_type','subscription_business_rs','subscription_expected_enquiries','subscription_veh_wheel_type','subscription_no_of_veh_allowed','subscription_validity_days','subscription_validity_from','subscription_validity_to','subscription_type_image','subscription_plan_created_by','is_free_trial','is_active','is_sent_for_approval','is_approved','is_approved_by')->where('subscription_id', $id)->first();

			if(isset($editsubplan->subscription_type_image)){
		    	$b64image = $this->commonFunction->getimageBase64($editsubplan->subscription_type_image);
		    	$editsubplan->subscription_type_image = $b64image;
		    }

			Session::flash('subscription_id', $editsubplan->subscription_id);
			Session::flash('subscription_type_name', $editsubplan->subscription_type_name);
			Session::flash('subscription_amount', $editsubplan->subscription_amount);
			Session::flash('subscription_validity_type', $editsubplan->subscription_validity_type);
			Session::flash('subscription_business_rs', $editsubplan->subscription_business_rs);
			Session::flash('subscription_expected_enquiries', $editsubplan->subscription_expected_enquiries);
			Session::flash('subscription_veh_wheel_type', $editsubplan->subscription_veh_wheel_type);
			Session::flash('subscription_no_of_veh_allowed', $editsubplan->subscription_no_of_veh_allowed);
			Session::flash('subscription_validity_days', $editsubplan->subscription_validity_days);
			Session::flash('subscription_validity_from', $editsubplan->subscription_validity_from);
			Session::flash('subscription_validity_to', $editsubplan->subscription_validity_to);
			Session::flash('subscription_plan_created_by', $editsubplan->subscription_plan_created_by);
			Session::flash('is_free_trial', $editsubplan->is_free_trial);
			Session::flash('is_active', $editsubplan->is_active);
			Session::flash('subscription_type_image', $editsubplan->subscription_type_image);
			Session::flash('is_sent_for_approval', $editsubplan->is_sent_for_approval);
			Session::flash('is_approved', $editsubplan->is_approved);
			Session::flash('is_approved_by', Auth::User()->name);
			return redirect()->route('subscriptions.index')->with(['success' ,'updated successfully']);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\subscriptionplan  $subscriptionplan
	 * @return \Illuminate\Http\Response
	 */
	public function update(storeSubscriptionPlan $request, $id)
	{
		if (! Gate::allows('subscription_edit'))
		{
			return abort(401);
		}

		$validated = $request->validated();
		
		// dd($request->all());
		if($request->has('subscription_type_name')){
			$subscription_type_name = $request->input('subscription_type_name');
			$subscription_type_name = trim(preg_replace('/\s+/',' ', $subscription_type_name));
		}
		if($request->input('subscription_validity_type')=='yes'){
			$subscription_validity_type = 'by enquiry';
		}else{
			$subscription_validity_type = 'by value';
		}
		if(!empty($request->input('sub_multi_wheel_type'))){
			$subscription_no_of_veh_allowed = $request->input('sub_multi_wheel_type');
		}else{
			$subscription_no_of_veh_allowed = 0;
		}

		// if(!empty($request->input('sub_multi_wheel_type'))){
		// 	$subscription_veh_wheel_type = $request->input('sub_multi_wheel_type');
		// }else{
		// 	$subscription_veh_wheel_type = $request->input('subscription_veh_wheel_type');
		// }
		
		$is_approved = $request->has('is_approved') ? 1 : 0;

		$subplan = Subscriptionplan::find($id);
		$subplan->subscription_type_name = $subscription_type_name;
		$subplan->subscription_amount = $request->input('subscription_amount');
		$subplan->subscription_validity_type = $subscription_validity_type;
		$subplan->subscription_business_rs = $request->has('subscription_business_rs') ? $request->input('subscription_business_rs') : null;
		$subplan->subscription_expected_enquiries = $request->has('subscription_expected_enquiries') ? $request->input('subscription_expected_enquiries') : null;
		$subplan->subscription_veh_wheel_type = $request->input('subscription_veh_wheel_type');
		$subplan->subscription_no_of_veh_allowed = $subscription_no_of_veh_allowed;
		$subplan->subscription_validity_days = $request->input('subscription_validity_days');
		$subplan->subscription_validity_from = $request->input('subscription_validity_from');
		$subplan->subscription_validity_to = $request->input('subscription_validity_to');
		// $subplan->is_active = $request->input('is_active');
		$subplan->is_free_trial = isset($request->is_free_trial_hidden) ? $request->is_free_trial_hidden : 0;
		if($is_approved){
			$to = $request->input('subscription_validity_to');
			$from = $request->input('subscription_validity_from');
			if($from > $this->todays && $this->todays < $to){
				$subplan->is_active = 0;
			}else{
				$subplan->is_active =1;
			}
			$subplan->is_sent_for_approval = 1;
			$subplan->is_approved = 1;
			$subplan->is_approved_by = Auth::User()->name;
		}
		$subplan->save();

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
			$new_file_name = str_replace(' ', '',$subplan->subscription_id."-"."subscription_scheme"."-".$image_name);
			$image_path = "$dir/$new_file_name";                
			$subscription_type_image->move($dir,$new_file_name);
			$this->commonFunction->compressImage($image_path);
			$image_url =$this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);
			if($image_url){

				$new_op_path = $this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
			}else{
				$new_op_path = null;
			}
			$subplan = Subscriptionplan::find($id);
			$subplan->subscription_type_image = $new_op_path;
			$subplan->save();
		}

		Session::forget('redirect_url');
		Session::forget('redirect_id');

		return redirect()->route('subscriptions.index')->with('success', 'Subscription Plan has been updated successfully!');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\subscriptionplan  $subscriptionplan
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request) {
		if (! Gate::allows('subscription_delete')) 
		{
			return abort(401);
		}
		$subplan = Subscriptionplan::find($request->id)->delete();
		return redirect()->route('subscriptions.index')->with('success', 'Subscription plan has been deleted successfully!');
	}

	/**
	 * Check Subscription Plan is already exist
	 *
	 * @param  \App\subscriptionplan  $subscription_type_name, $subscription_veh_wheel_type
	 * @return \Illuminate\Http\Response
 	*/
	public function checkSubscriptionPlan(Request $request){
		if($request->has('subscription_type_name') && $request->has('subscription_veh_wheel_type'))
		{
			$subscription_type_name = $request->input('subscription_type_name');
			$subscription_type_name = trim(preg_replace('/\s+/',' ', $subscription_type_name));
			$veh_wheel_type = $request->input('subscription_veh_wheel_type');
			$subscription_id = $request->input('subscription_id');

			if(!empty($subscription_type_name) && $request->has('subscription_veh_wheel_type')) 
			{
				$subscription_type_name = $request->input('subscription_type_name');
				$subscription_type_name = trim(preg_replace('/\s+/',' ', $subscription_type_name));

				if(!empty($subscription_id)){
					//update
					$is_scheme_exist = Subscriptionplan::where('subscription_id','!=',$subscription_id)->where('subscription_type_name',$subscription_type_name)->where('subscription_veh_wheel_type',$veh_wheel_type)->where('subscription_validity_to' ,'>', $this->todays)->exists();	
				}else{
					//add
					$is_scheme_exist = Subscriptionplan::where('subscription_type_name',$subscription_type_name)->where('subscription_veh_wheel_type',$veh_wheel_type)->where('subscription_validity_to' ,'>', $this->todays)->exists();	
				}
				if($is_scheme_exist){
					$isAvailable = FALSE;
				}else{
					$isAvailable = TRUE;
				}
			}else{
				$isAvailable = TRUE;	
			}
		}else{
			$isAvailable = TRUE;
		}
		echo json_encode($isAvailable);
	}

	public function getAutoCompleteSubTypeName(Request $request){
		if($request->has('query')){
			$subscription_type_name = $request->input('query');
			$subscription_type_name = trim(preg_replace('/\s+/',' ', $subscription_type_name));
		}
		
		$result = Subscriptionplan::select('subscription_type_name')->where('is_active',1)->where('subscription_type_name', 'like', '%'.$subscription_type_name. '%')->groupBy('subscription_type_name')->limit(100)->get();

		$data = array();
		foreach ($result as $result) {
			array_push($data, $result->subscription_type_name);
		}
		$suggestions = array('suggestions' => $data);
		$data = json_encode($suggestions);
		return $data;
	}

	public function checkSubScriptionType(Request $request){

		if($request->has('query')){
			$subscription_type_name = $request->input('query');
			$subscription_type_name = trim(preg_replace('/\s+/',' ', $subscription_type_name));
		}
		
		$subcount = Subscriptionplan::select('subscription_type_name')->where('is_active',1)->where('subscription_type_name', $subscription_type_name)->count();

		if($subcount){
			$subscription_type_name = Subscriptionplan::where('subscription_type_name', $subscription_type_name)->where('is_active',1)->value('subscription_type_name');
		}else{
			$subscription_type_name = 0;
		}
		return $subscription_type_name;
	}

    public function verifySubPlanBySuperAdmin(Request $request){
    	if(!empty($request->all())){
    		$subplan = Subscriptionplan::find($request->id);
	    	$created_by = User::where('id',$subplan->subscription_plan_created_by)->value('name');
			$subplan->is_sent_for_approval = 1;
			$subplan->save();

	    	//send admin notification:madhuri
	    	$adminNotification = array(
	        	'subject' => 'Approve Subscription Plan',
	        	'message' => 'You have new plan added by '.$created_by.', approve the plan',
	        	'type' => 'subplan_verify',
	        	'message_view_id' => isset($request->id) ? $request->id : null,
	        	'message_pattern' => 'A-A',
	        	'message_sender_id' => Auth::user()->id,
	        	'message_from' => Auth::user()->name ,
	        	'url' => 'Subscription.edit',
	        	);
	    	$data = $this->notifiy->sendNotificationToAdmin($adminNotification);
	     	//admin notification:madhuri code ends
    	}else{
    		return redirect()->route('subscriptions.index')->with('error', 'Something went wrong in sent verification to admin');
    	}	
    }

    public function ApproveSubPlanBySuperAdmin(Request $request){
		$subplan = Subscriptionplan::find($request->id);
		$created_by = User::where('id',$subplan->subscription_plan_created_by)->value('name');
		$result = Auth::user()->hasPermissionTo('subscription_final_approval');
		$is_sent_for_approval_byAuthorised = $result ? 1 : 0; 

		if($subplan->is_sent_for_approval == 1 || $is_sent_for_approval_byAuthorised) {
			if($is_sent_for_approval_byAuthorised){ //only if user hase permission to approve plan
				$subplan->is_sent_for_approval = 1;
			} 
			//plan active/inactive
			$parse_date_to = $subplan->subscription_validity_to;
			$parse_date_from = $subplan->subscription_validity_from;
			if($parse_date_from > $this->todays || $this->todays > $parse_date_to){
				$subplan->is_active = 0;	
			}else{
				$subplan->is_active = 1;	
			}
			$subplan->is_approved = 1;
			$subplan->is_approved_by = Auth::user()->name;
			$subplan->save();

	    	$adminNotification = array(
	        	'subject' => 'Subscription Plan Approved',
	        	'message' => 'Your subscription plan has been approved by '.Auth::user()->name.'.',
	        	'type' => 'subplan_approved',
	        	'message_view_id' => isset($request->id) ? $request->id : null,
	        	'message_pattern' => 'A-A',
	        	'message_sender_id' => Auth::user()->id,
	        	'message_from' => Auth::user()->name ,
	        	'receiver_id' => $subplan->subscription_plan_created_by,
	    		);
	    	$data = $this->notifiy->sendNotificationToAdmin($adminNotification);

	    	// send email to creater about plans has been verified
			// $firstname = User::where('op_user_id', '=', $user->op_user_id)->value('op_first_name');

			$email_Array = ['madhuri@e-arth.in'];
			$email_Subject = 'Congratulation!, Subscription plan has been approved';
			$email_Body = array(
				'created_by' => $created_by,
				'plan_name' => $subplan->subscription_type_name,
				'wheel_type' => $subplan->subscription_veh_wheel_type,
			);
			// $send_email = $this->aws->sendEmailTo($email_Array, $email_Subject, $email_Body ,'subplan_approved');
		}
    }

    public function redirectToSubplanNotification(Request $request){
 		if(!empty($request->all())){
 			$data = array();
 			$notification_msg_id = $request->id;
 			$message_view_id = AdminNotificationMessages::where('notification_msg_id',$notification_msg_id)->value('message_view_id');
 			$admin_notification = AdminNotification::where('message_receiver_id',Auth::user()->id)->where('message_id',$notification_msg_id)->update(['is_read' => 1]);
 			
 			$notification_details = Subscriptionplan::join('ggt_admin_notification_messages', 'subscription_plans.subscription_id', '=', 'ggt_admin_notification_messages.message_view_id')
		    ->select('subscription_plans.subscription_id', 'subscription_plans.subscription_type_name', 'subscription_plans.subscription_amount','subscription_plans.subscription_validity_type','subscription_plans.subscription_business_rs','subscription_plans.subscription_expected_enquiries','subscription_plans.subscription_veh_wheel_type','subscription_plans.subscription_validity_days','subscription_plans.subscription_validity_from','subscription_plans.subscription_validity_to','subscription_plans.subscription_type_image','subscription_plans.is_approved_by','subscription_plans.is_approved','subscription_plans.subscription_plan_created_by','ggt_admin_notification_messages.message','ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.notification_msg_id')->where('subscription_plans.subscription_id','=',$message_view_id)->where('ggt_admin_notification_messages.notification_msg_id',$notification_msg_id)->first();
		    
		    if($notification_details){
			    // if(!empty($notification_details->subscription_type_image)){
			    // 	$b64image = $this->commonFunction->getimageBase64($notification_details->subscription_type_image);
			    // 	$data['sub_image_b64'] = $b64image;
			    // }	
			    $data['created_by'] = User::where('id',$notification_details->subscription_plan_created_by)->value('name');
		    	$parse_date_from = Carbon::parse($notification_details->subscription_validity_from);
		    	$parse_date_to = Carbon::parse($notification_details->subscription_validity_to);

    			$data['validity_from'] = Carbon::createFromFormat('Y-m-d', $parse_date_from->toDateString())->toFormattedDateString();
    			$data['validity_to'] = Carbon::createFromFormat('Y-m-d', $parse_date_to->toDateString())->toFormattedDateString();
		    }else{
		    	$notification_details = null;
		    }
		     // dd($notification_details);
		    
	    	return view('admin.subscriptionplan.view',compact('notification_details','data'));
 		}else{
 			Log::error('subplan notification id not found');
 		}
    }
}

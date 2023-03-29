<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operator;
use Illuminate\Support\Facades\Gate;
use Config;
use Log;
use App\Models\AdminNotification;
use App\Models\AdminNotificationMessages;
use App\Models\DriverNotificationMessages;
use App\Models\DriverNotifications;
use App\Models\User;
use Spatie\Permission\Models\Role;

class NotificationController extends Controller
{
	public function index()
	{
		if (! Gate::allows('information_manage'))
		{
			return abort(401);
		}
		else{
			return view('admin.information.notification.index');
		}
	}

	public function create()
	{
		if (! Gate::allows('notification_create'))
		{
			return abort(401);
		}
		else{
			$operators_numbers = Operator::select('op_mobile_no')->whereNull('deleted_at')->get();
				$header = "All Operators Numbers";
			
			return view('admin.information.notification.create', ['operators_numbers' => $operators_numbers, 'header' => $header]);
		}
	}

	public function store(Request $request)
	{
		if (! Gate::allows('notification_create'))
		{
			return abort(401);
		}
		else{
			$tokens = [];
			foreach($request->selected_numbers as $key => $value){
				$op_tokens = Operator::select('op_notification_token')->whereNull('deleted_at')->where('op_mobile_no',$value)->first();
				array_push($tokens, $op_tokens->op_notification_token);
			}
			
			$to = $tokens;

			$notification = [
				'title'=>$request->notification_title,
				'body'=>$request->notification_body
			];

			$payload = [
				'registration_ids' => $to,
				'notification' => $notification,
			];
			
			$send_notification = $this->sendPushNotification($payload);
			$header = "Notifications";
			
			return redirect()->route('information.index')->with('success', 'Notification send successfully.');
		}        
	}

	public function sendPushNotification($fields) {
		if (! Gate::allows('information_manage'))
		{
			return abort(401);
		}
		else{
			$this->firebase_api_key = Config::get('custom_config_file.firebase-api-key');
			$this->firebase_url = Config::get('custom_config_file.fcm-url');
			Log::info('fields: ', $fields); Log::warning($this->firebase_api_key); Log::warning($this->firebase_url);
			$header=[
				'Authorization: key='.$this->firebase_api_key,
				'Content-Type: application/json'
			];

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => $this->firebase_url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => json_encode( $fields ),
				CURLOPT_HTTPHEADER => $header,
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				Log::warning( $err);
			  // echo "cURL Error #:" . $err;
			} else {
				Log::warning( $response);
			  // echo $response;
			}
			return 1;
		}
	}

	function sendNotificationToAdmin($req) {
		$data = array(
			'title' => isset($req['subject']) ? $req['subject'] : null,
			'message' => isset($req['message']) ? $req['message'] : null,
			'message_type' => isset($req['type']) ? $req['type'] : null,
			'message_view_id' => isset($req['message_view_id']) ? $req['message_view_id'] : null,
			'message_pattern' => isset($req['message_pattern']) ? $req['message_pattern'] : null,
			'message_sender_id' => isset($req['message_sender_id']) ? $req['message_sender_id'] : null,
			'message_from' => isset($req['message_from']) ? $req['message_from'] : null,
			'url' => isset($req['url']) ? $req['url'] : null,
		);

		$result = AdminNotificationMessages::create($data);
		//code added by nayana
		$super_admins = [];
		$admins = User::get();
		if(!empty($admins)){
			foreach ($admins as $key => $value) {
				if($value->hasRole('Super Admin')){
					array_push($super_admins, $value->id);
				}
				else{
					Log::warning("not a super admin". $key);
				}
			}
		}
		else{
			Log::warning("no super admin");
		}
		//end-nayana

		if($result) 
		{
			if($req['type']=='subplan_verify'){
				$users = User::select('id')->role('Super Admin')->first(); 
				$SuperAdminId = $users->id;
				$results = User::permission('subscription_final_approval')->get();  //get users having permission as verify subplan

				foreach ($results as $key => $value) {
					$notifications = array(
						'message_id' => $result->notification_msg_id,
						'message_receiver_id' => $value['id'],
						// 'message_receiver_id' => isset($SuperAdminId) ? $SuperAdminId : null,
					);
					$admin_notifications = AdminNotification::create($notifications);
				}
			
			}elseif($req['type']=='subplan_approved'){
			  $notifications = array(
				  'message_id' => $result->notification_msg_id,
				  'message_receiver_id' => isset($req['receiver_id']) ? $req['receiver_id']: null,
			  );
			  $admin_notifications = AdminNotification::create($notifications);
			}
			elseif($req['type']=='approve_payment_request' || $req['type']=='op_verification_request'){
				if(!empty($admins)){
					foreach ($admins as $key => $value) {
						$notifications = array(
							'message_id' => $result->notification_msg_id,
							'message_receiver_id' => $value->id,
						);
						$admin_notifications = AdminNotification::create($notifications);
					}
				}else{
					Log::warning("no super admin");
				}
			}elseif ($req['type']=='subplan_expiry') {
				$notifications = array(
					  'message_id' => $result->notification_msg_id,
					  'message_receiver_id' => isset($req['receiver_id']) ? $req['receiver_id']: null,
				  );
			  	$admin_notifications = AdminNotification::create($notifications);
			}
			else{
				$admin_notifications = null;
			}

			if (!empty($admin_notifications)) 
			{
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function sendNotificationToOperator($request){
		$data = array(
			'title' => isset($request['subject']) ? $request['subject'] : null,
			'message' => isset($request['message']) ? $request['message'] : null,
			'message_type' => isset($request['type']) ? $request['type'] : null,
			'message_view_id' => isset($request['message_view_id']) ? $request['message_view_id'] : null,
			'message_pattern' => isset($request['message_pattern']) ? $request['message_pattern'] : null,
			'message_sender_id' => isset($request['message_sender_id']) ? $request['message_sender_id'] : null,
			'message_from' => isset($request['message_from']) ? $request['message_from'] : null,
		);

		$result = DriverNotificationMessages::create($data);
		if($result) 
		{
			$op_mobile_no = Operator::where('op_user_id', $request['receiver_id'])->value('op_mobile_no');
			if($request['type']=='payment_approved'){
				
					$notifications = array(
						'message_id' => $result->id,
						'op_user_id' => $request['receiver_id'],
						'op_mobile_no' => $op_mobile_no,
					);
					$driver_notifications = DriverNotifications::create($notifications);
			}
			else if($request['type']=='subplan_op_expiry'){
				$notifications = array(
					'message_id' => $result->id,
					'op_mobile_no' => $op_mobile_no,
					'message_receiver_id' => $request['receiver_id'],
				);
				$driver_notifications = DriverNotifications::create($notifications);	
			}
			else{
				$driver_notifications = null;
			}

			if (!empty($driver_notifications)) 
			{
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	} 
}

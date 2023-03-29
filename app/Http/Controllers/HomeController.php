<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\AdminNotification;
use App\Models\AdminNotificationMessages;
//use App\Models\Role;
use App\Models\Driver;
use App\Models\UserDetail;
use App\Models\UserBookRideDetails;
use App\Models\Operator;
use DB;
use Session;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\CommonController;
use Auth;
use Config;
use Carbon\Carbon;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CustomerBookTrip;


class HomeController extends Controller
{
	// use SoftDeletes;
	public $super_admin_email;
	

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$this->commonFunction = new CommonController;
		$this->super_admin_email = Config::get('custom_config_file.su_admin_email');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		if(!((Gate::allows('dashboard_manage')) && (Gate::allows('dashboard_view')))) 
		{
			return view('guesthome');
		}
		else{
			//$online_operators = Driver::where('driver_is_online','=',1)->get()->count();
			$online_operators = Operator::join('ggt_drivers','ggt_drivers.op_user_id','=','ggt_operator_users.op_user_id')->where('ggt_operator_users.op_is_verified',1)->where('ggt_drivers.driver_is_online','=',1)->get()->count();
			$active_customers = UserDetail::where('user_verified','=',1)->get()->count();
			$total_bookings = CustomerBookTrip::join('ggt_user_payments','ggt_user_book_trip.pay_order_id','=','ggt_user_payments.user_order_id')
            ->join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_user_book_trip.op_id')
            ->where('ggt_user_book_trip.ride_status','success')
            ->count();
			return view('home',compact('online_operators','active_customers','total_bookings'));
		}
	}

	public function getNotification(Request $request){
		//code modified by madhuri
		$offset = $request->offset;
		$getUserId = Auth::user()->hasRole('Super Admin');
		$users = User::select('id')->role('Super Admin')->first(); 
		$SuperAdminId = $users->id;
		
		$admin_notifications = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->skip($offset)->take(5)->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
		// dd($admin_notifications);
		$notification_count = AdminNotification::where('is_read', 0)->where('message_receiver_id',Auth::user()->id)->count();
		$last_notification_time = AdminNotification::where('message_receiver_id',Auth::user()->id)->orderby('created_at', 'desc')->value('created_at');
		$last_notification_time = Carbon::parse($last_notification_time)->format('Y-m-d H:i:s');
		// dd($admin_notifications);
		if(!empty($admin_notifications)){
			foreach ($admin_notifications as $key => $value)
			{
				if($value['message_type'] == 'approve_payment_request' && ! Gate::allows('payment_approve')){
					unset($admin_notifications[$key]);
				}
				else{
					$data_time = $value['created_at'];
					$duration = $this->commonFunction->get_timeago($data_time);
					$admin_notifications[$key]['duration']  = $duration;
				}
			}
			$admin_notifications = array_values($admin_notifications);
			return json_encode(['status' => 'success', 'data' => $admin_notifications, 'notification_count' => $notification_count, 'last_notification_time' => $last_notification_time]);
		}
		else{
			$admin_notifications = [];
			$notification_count = [];
			return json_encode(['status' => 'failed', 'data' => $admin_notifications, 'notification_count' => $notification_count, 'last_notification_time' => $last_notification_time]);
		}
		// code end by madhuri
	}

	public function getLatestNotification(Request $request){
		//code added by nayana
		$offset = $request->offset;
		// dd($offset);
		$notification_available = AdminNotification::select('notification_id', 'created_at')->where('message_receiver_id',Auth::user()->id)->where('created_at', '>',$offset)->exists();
		
		return json_encode(['status' => $notification_available]);
	}

	public function markAsReadUserNotification(Request $request)
	{
		$postdata = $request->all();
		$message_id = $request->message_id;
		if($message_id != null)
		{
			$markNotification['mark_as_read'] = AdminNotification::where('notification_id',$message_id)->update(['is_read' => 1]);
			$markNotification['count'] = AdminNotification::where('is_read', 0)->count();
		}
		return response($markNotification);
	}
	public function viewNotification(Request $request){
		if($request->ajax()){
			return $request->all();
			$postdata = $request->all();
			// dd($postdata);
			$message_type = $postdata['message_type'];
			$message_view_id = $postdata['message_view_id'];
			$notification_msg_id = $postdata['notification_msg_id'];
			if (substr($message_type, 0, 7) === 'subplan'){ 
				$check_type = substr($message_type, 0, 7);
				if($message_type == 'subplan_payment_receive'){
					$redirect_url = route('home');
				}
				elseif ($message_type == 'subplan_verify' || $message_type == 'subplan_approved') {
					// return $str_type = substr($message_type, strpos($message_type, "_") + 1);
					$redirect_url = route('view-subplan-notification', ['id' => $notification_msg_id ]);
				}
			}
			elseif ($message_type == 'approve_payment_request') {
				$redirect_url = route('payment-view', ['pay_id' => encrypt($message_view_id)]);
			}
			else{ 
				$redirect_url = route('home');
			}
			$response = ['success'=> true,'redirect_url'=> $redirect_url];
			return response($response);
		}else{
			return false;
		}
	}

	public function viewNotificationBox(Request $request)
	{
		$offset = $request->offset;
		$getUserId = Auth::user()->hasRole('Super Admin');
		$users = User::select('id')->role('Super Admin')->first(); 
		$SuperAdminId = $users->id;
		$admin_notifications = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.deleted_at','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where('ggt_admin_notification.deleted_at' , '=' , null )->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
			// dd($admin_notifications);
		$delete_admin_notifications = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.deleted_at','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->whereNotNull('deleted_at')->skip($offset)->take(5)->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
		// dd($delete_admin_notifications);
		$notification_count = AdminNotification::where('is_read', 0)->where('message_receiver_id',Auth::user()->id)->count();
		// dd($notification_count);
		if(!empty($admin_notifications)){
			foreach ($admin_notifications as $key => $value)
			{
				$data_time = $value['created_at'];
				$duration = $this->commonFunction->get_timeago($data_time);
				$admin_notifications[$key]['duration']  = $duration;
			}
				
				$totalmail = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where('ggt_admin_notification.deleted_at' , '=' , null )->count();
				$trashmail = count($delete_admin_notifications);
				$unreadmail = AdminNotification::select('ggt_admin_notification_messages.notification_msg_id')->join('ggt_admin_notification_messages', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where('is_read', 0)->count();
		        $readmail = AdminNotification::select('ggt_admin_notification_messages.notification_msg_id')->join('ggt_admin_notification_messages', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where('is_read', 1)->count();			
				return view('notification-box',compact('admin_notifications','notification_count','data_time','totalmail','readmail','unreadmail','trashmail','delete_admin_notifications'));
		}		
	}
}

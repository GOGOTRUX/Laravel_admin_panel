<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\AdminNotification;
use App\Models\AdminNotificationMessages;
use Illuminate\Support\Facades\URL;
//use App\Models\Role;
use App\Models\Driver;
use App\Models\UserDetail;
use App\Models\UserBookRideDetails;
use App\Models\Subscriptionplan;
use App\Models\OperatorPayments;
use App\Models\UserPayments;
use App\Models\Operator;
use App\Models\Customer;
use DB;
use Session;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\CommonController;
use Auth;
use Config;
use Carbon\Carbon;

class NotificationToDoController extends Controller
{

    public $super_admin_email;
    private $URL;
    public function __construct()
    {
        $this->middleware('auth');
        $this->commonFunction = new CommonController;
        $this->super_admin_email = Config::get('custom_config_file.su_admin_email');
        $this->todays = Carbon::today()->toDateString();
    }

    public function index()
    {
       
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
    public function show()
    {
        $this->URL = URL::current();
        $this->URL = strtolower(explode("/", $this->URL)[4]);
        $admin_notifications = [];
        if($this->URL == 'read'){
            $admin_notifications = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message','ggt_admin_notification_messages.url', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.is_read', 1)->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
         
        }elseif($this->URL == 'approved'){

            $approved_subscribe = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.url', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->join('subscription_plans', 'subscription_plans.subscription_id', '=', 'ggt_admin_notification_messages.message_view_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where ('subscription_plans.is_approved','=',1)->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();

            $approved_payment = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.url', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->join('ggt_op_payments', 'ggt_op_payments.op_order_id', '=', 'ggt_admin_notification_messages.message_view_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where ('ggt_op_payments.op_order_status','=','approved')->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
            $admin_notifications = array_merge($approved_subscribe,$approved_payment);
        }
        else{ //for archive notifications
            $archive_subscribe = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.url', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->join('subscription_plans', 'subscription_plans.subscription_id', '=', 'ggt_admin_notification_messages.message_view_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where ('subscription_plans.is_approved','=',1)->orwhere('subscription_plans.is_approved','=',2)->orwhere('subscription_plans.is_approved','=',0)->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
            $archive_payment = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.url', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->join('ggt_op_payments', 'ggt_op_payments.op_order_id', '=', 'ggt_admin_notification_messages.message_view_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where ('ggt_op_payments.op_order_status','=','hold')->orwhere('ggt_op_payments.op_order_status','=','reject')->orwhere('ggt_op_payments.op_order_status','=','approved')->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
            $admin_notifications = array_merge($archive_subscribe,$archive_payment);
        }

        $notification_count = AdminNotification::where('is_read', 0)->where('message_receiver_id',Auth::user()->id)->count();
        
        if(!empty($admin_notifications)){
            $read = $this->getIsReadNotificationCount();
            $approved = $this->getApprovedNotificationCount();  
            $archive = $this->getArchiveNotificationCount();

            foreach ($admin_notifications as $key => $value) {
                $message_type = $value['message_type'];
                $message_view_id = $value['message_view_id'];
                $notification_msg_id = $value['notification_msg_id'];

                $data_time = $value['created_at'];
                $duration = $this->commonFunction->get_timeago($data_time);
                $admin_notifications[$key]['duration']  = $duration;

                if (substr($message_type, 0, 7) === 'subplan'){
                    $check_type = substr($message_type, 0, 7);
                    if($message_type == 'subplan_payment_receive'){
                        $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                        $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                    }
                    elseif ($message_type == 'subplan_verify' || $message_type == 'subplan_approved') {
                        // return $str_type = substr($message_type, strpos($message_type, "_") + 1);
                        $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                        $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                    }
                }
                elseif (substr($message_type, 0, 7) === 'approve'){
                    if($message_type == 'approve_payment_request' && (strpos($value['url'], 'operator') !== false))
                    {
                        $value['op_order_status'] = OperatorPayments::where('op_order_id',$value['message_view_id'])->value('op_order_status');
                        $admin_notifications[$key]['op_order_status'] = $value['op_order_status'];
                    }
                    else{
                        $value['op_order_status'] = UserPayments::where('user_order_id',$value['message_view_id'])->value('user_order_status');
                        $admin_notifications[$key]['op_order_status'] = $value['op_order_status'];
                    }
                }
            } 
            return view('admin.notifications.notification-box',compact('admin_notifications','notification_count','read','approved','archive'));    
        }
        else{
            $read = 0; 
            $approved = 0;
            $archive = 0;
            $admin_notifications=[];
            return view('admin.notifications.notification-box',compact('admin_notifications','notification_count','read','approved','archive'));
        }
    }
    
    public function viewNotificationBox(Request $request){
        $offset = $request->offset;
        $getUserId = Auth::user()->hasRole('Super Admin');
        $users = User::select('id')->role('Super Admin')->first(); 
        $SuperAdminId = $users->id;

        $admin_notifications = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message','ggt_admin_notification_messages.url', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
         

        $notification_count = AdminNotification::where('is_read', 0)->where('message_receiver_id',Auth::user()->id)->count();

        if(!empty($admin_notifications)){
            $read = $this->getIsReadNotificationCount();
            $approved = $this->getApprovedNotificationCount();  
            $archive = $this->getArchiveNotificationCount();

            foreach ($admin_notifications as $key => $value) {
                $message_type = $value['message_type'];
                $message_view_id = $value['message_view_id'];
                $notification_msg_id = $value['notification_msg_id'];

                $data_time = $value['created_at'];
                $duration = $this->commonFunction->get_timeago($data_time);
                $admin_notifications[$key]['duration']  = $duration;

                if (substr($message_type, 0, 7) === 'subplan'){
                    $check_type = substr($message_type, 0, 7);
                    if($message_type == 'subplan_payment_receive'){
                        $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                        $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                    }
                    elseif ($message_type == 'subplan_verify' || $message_type == 'subplan_approved') {
                        // return $str_type = substr($message_type, strpos($message_type, "_") + 1);
                        $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                        $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                    }
                }
                elseif (substr($message_type, 0, 7) === 'approve'){
                    if($message_type == 'approve_payment_request' && (strpos($value['url'], 'operator') !== false))
                    {
                        $value['op_order_status'] = OperatorPayments::where('op_order_id',$value['message_view_id'])->value('op_order_status');
                        $admin_notifications[$key]['op_order_status'] = $value['op_order_status'];
                    }
                    else{
                        $value['op_order_status'] = UserPayments::where('user_order_id',$value['message_view_id'])->value('user_order_status');
                        $admin_notifications[$key]['op_order_status'] = $value['op_order_status'];
                    }
                }
            } 
            // dd($admin_notifications);
            return view('admin.notifications.notification-box',compact('admin_notifications','notification_count','read','approved','archive'));    
        }
        else{
            $read = 0; 
            $approved = 0;
            $archive = 0;
            $admin_notifications=[];
            return view('admin.notifications.notification-box',compact('admin_notifications','notification_count','read','approved','archive'));
        }
    }

    private function getIsReadNotificationCount(){
        $read_count = AdminNotification::where('is_read', 1)->where('message_receiver_id',Auth::user()->id)->count();
        return $read_count;
    }

    private function getApprovedNotificationCount(){
        $approved_subscribe = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->join('subscription_plans', 'subscription_plans.subscription_id', '=', 'ggt_admin_notification_messages.message_view_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where ('subscription_plans.is_approved','=',1)->count();
        $approved_payment = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->join('ggt_op_payments', 'ggt_op_payments.op_order_id', '=', 'ggt_admin_notification_messages.message_view_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where ('ggt_op_payments.op_order_status','=','approved')->count();
        $approved_notification_count = $approved_subscribe +  $approved_payment;
        return $approved_notification_count;
    }

    private function getArchiveNotificationCount(){
        $archive_subscribe = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->join('subscription_plans', 'subscription_plans.subscription_id', '=', 'ggt_admin_notification_messages.message_view_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where ('subscription_plans.is_approved','=',1)->orwhere('subscription_plans.is_approved','=',2)->orwhere('subscription_plans.is_approved','=',0)->count();
        $archive_payment = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->join('ggt_op_payments', 'ggt_op_payments.op_order_id', '=', 'ggt_admin_notification_messages.message_view_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->where ('ggt_op_payments.op_order_status','=','hold')->orwhere('ggt_op_payments.op_order_status','=','reject')->orwhere('ggt_op_payments.op_order_status','=','approved')->count();
        $archive_notification_count = $archive_subscribe +  $archive_payment;
        return $archive_notification_count;
    }

    public function viewNotificationMail(request $request)
    {
        // dd($request);
        // dd($request->ajax());
        if($request->ajax()){
            //return $request->all();
            $postdata = $request->all();
            // dd($postdata);
            $message_type = $postdata['message_type'];
            $message_view_id = $postdata['message_view_id'];
            // dd($message_view_id);
            $notification_msg_id = $postdata['notification_msg_id'];

            
            if($message_view_id != null)
            {
                $markNotification['mark_as_read'] = AdminNotification::where('notification_id',$message_view_id)->update(['is_read' => 1]);
                // dd($markNotification['mark_as_read']);
                $markNotification['count'] = AdminNotification::where('is_read', 0)->count();
            }

            if (substr($message_type, 0, 7) === 'subplan'){ 
                $check_type = substr($message_type, 0, 7);
                if($message_type == 'subplan_payment_receive'){
                    $redirect_url = route('home');
                }
                elseif ($message_type == 'subplan_verify') {
                    // return $str_type = substr($message_type, strpos($message_type, "_") + 1);
                    $redirect_url = route('view-subplan-notification', ['id' => $notification_msg_id ]);
                }
                elseif ($message_type == 'subplan_approved')
                {
                    $redirect_url = route('view-subplan-notification', ['id' => $notification_msg_id ]);
                }
                
            }
            elseif ($message_type == 'approve_payment_request') {
                // /dd('approve_payment_request');
                // return $str_type = substr($message_type, strpos($message_type, "_") + 1);
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

    public function viewNotificationDetail(Request $request)
    {
        if($request->ajax()){
            //return $request->all();
            $postdata = $request->all();
            // dd($postdata);
            $message_type = $postdata['message_type'];
            $message_view_id = $postdata['message_view_id'];
            $from = $postdata['from'];
            $title = $postdata['title'];
            $duration = $postdata['duration'];
            $message_id = $postdata['message_id'];
            // dd($subscriptionDetail); 
            AdminNotification::where('notification_id',$message_id)->update(['is_read' => 1]);
            if($title == "Approve Subscription Plan" || $title == "Subscription Plan Approved" )
            {
                 $subscriptionDetail = Subscriptionplan::select('subscription_id','subscription_type_name','subscription_amount','subscription_validity_type','subscription_business_rs','subscription_expected_enquiries','subscription_veh_wheel_type','subscription_validity_days','subscription_validity_from','subscription_validity_to','subscription_type_image','subscription_plan_created_by','is_free_trial','is_active','is_sent_for_approval','is_approved','is_approved_by')->where('subscription_id', $message_view_id)->first();
                 if(!empty($subscriptionDetail))
                {
                    $data['created_by'] = User::where('id',$subscriptionDetail->subscription_plan_created_by)->value('name');
                    $parse_date_from = Carbon::parse($subscriptionDetail->subscription_validity_from);
                    $parse_date_to = Carbon::parse($subscriptionDetail->subscription_validity_to);

                    $data['validity_from'] = Carbon::createFromFormat('Y-m-d', $parse_date_from->toDateString())->toFormattedDateString();
                    $data['validity_to'] = Carbon::createFromFormat('Y-m-d', $parse_date_to->toDateString())->toFormattedDateString();
                    $data['created_date'] = Carbon::createFromFormat('Y-m-d', Carbon::parse($subscriptionDetail->created_at)->toDateString())->toFormattedDateString();
                    $to = $subscriptionDetail->subscription_validity_to;
                    $from = $subscriptionDetail->subscription_validity_from;
                    if($from > $this->todays){
                        $data['subscription_expired'] = 2; //plan yet to be active
                        $data['subscription_active_indays'] = Carbon::parse($from)->diffInDays($this->todays);
                    }
                    else if($this->todays > $to || $this->todays > $to){
                        $data['subscription_expired'] = 0; //plan expired
                    }
                    else{
                        $data['subscription_expired'] = 1; //plan running
                    }
                      $result = array(
                            'orderType' => "Subscription",
                            'data' => $data,
                            'subscriptionDetail' => $subscriptionDetail,
                            'from' => $from,
                            'title' => $title,
                            'duration' => $duration,
                            );
                    }
                    else{
                        $result = [];
                    }
            }
            else
            {
                // dd('payment');
                // dd($message_view_id);
                $payment_details = OperatorPayments::where('op_order_id', $message_view_id)->first();
                // dd($payment_details);
                if(!empty($payment_details)){
                    if(!empty($payment_details['op_user_id'])){
                        $op_details = Operator::select('op_first_name', 'op_uid')->where('op_user_id', $payment_details['op_user_id'])->first();
                        $payment_details['op_name'] = $op_details['op_first_name'];
                    }
                    else{
                        $payment_details['op_name'] = null;
                    }
                    if(!empty($payment_details['op_order_payment_p_details'])){
                        $payment_p_details = json_decode($payment_details['op_order_payment_p_details'], true);
                            if(!empty($payment_p_details['sub_scheme_name'])){
                                $plan_name = subscriptionplan::where('subscription_id', $payment_p_details['sub_scheme_name'])->value('subscription_type_name');
                                $payment_p_details['sub_scheme_name'] = $plan_name;
                            }
                        $payment_details['payment_p_details'] = $payment_p_details;
                    }
                    else{
                        $payment_details['payment_p_details'] = null;
                    }
                    $result = array(
                        'orderType' => "payment",
                        'payment_details' => $payment_p_details,
                        'payment_details' => $payment_details,
                        'from' => $from,
                        'title' => $title,
                        'duration' => $duration,
                        'message_view_id' => $message_view_id

                    );
                }
                else{
                   $result = [];

                }
            }

            $response = ['success'=> true, 'result'=> $result];
            return json_encode($response);
        }
        else{
            return false;
        }
    }
    public function alldetailmail(Request $request)
    {
        if(!empty($request))
        {
            // dd($request->all());
            if($request['mailtype'] == "read")
            {
                    $offset = $request->offset;
                    $getUserId = Auth::user()->hasRole('Super Admin');
                    $users = User::select('id')->role('Super Admin')->first(); 
                    $SuperAdminId = $users->id;
    
                    $admin_notifications = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
                    foreach ($admin_notifications as $key => $value) 
                    {
                    //dd($value);
                        $message_type = $value['message_type'];
                        $message_view_id = $value['message_view_id'];
                        $notification_msg_id = $value['notification_msg_id'];

                        if (substr($message_type, 0, 7) === 'subplan'){
                            $check_type = substr($message_type, 0, 7);
                            if($message_type == 'subplan_payment_receive'){
                                $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                                $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                            }
                            elseif ($message_type == 'subplan_verify' || $message_type == 'subplan_approved') {
                                // return $str_type = substr($message_type, strpos($message_type, "_") + 1);
                                $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                                $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                            }
                        }
                        elseif (substr($message_type, 0, 7) === 'approve'){
                            if($message_type == 'approve_payment_request')
                            {
                                 $value['op_order_status'] = OperatorPayments::where('op_order_id',$value['message_view_id'])->value('op_order_status');
                                 $admin_notifications[$key]['op_order_status'] = $value['op_order_status'];
                            }
                        }
                    
                    }       
                    if(!empty($admin_notifications))
                    {
                    foreach ($admin_notifications as $key => $value)
                    {
                        $data_time = $value['created_at'];
                        $duration = $this->commonFunction->get_timeago($data_time);
                        $admin_notifications[$key]['duration']  = $duration;
                    }           
                        $result = array(
                        'mailtype' => "inbox",
                        'admin_notifications' => $admin_notifications,
                        );
                        return response()->json($result);                       
                    }
            }
            else if($request['mailtype'] == "approved")
            {
                // dd('unread');
                $offset = $request->offset;
                $getUserId = Auth::user()->hasRole('Super Admin');
                $users = User::select('id')->role('Super Admin')->first(); 
                $SuperAdminId = $users->id;
                $admin_notifications = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
                // dd($admin_notifications);
                    foreach ($admin_notifications as $key => $value) 
                    {
                        // dd($admin_notifications);
                        $message_type = $value['message_type'];
                        $message_view_id = $value['message_view_id'];
                        $notification_msg_id = $value['notification_msg_id'];

                        if (substr($message_type, 0, 7) === 'subplan')
                        {
                            $check_type = substr($message_type, 0, 7);
                            if($message_type == 'subplan_payment_receive'){
                                $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                                $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                            }
                            elseif ($message_type == 'subplan_verify' || $message_type == 'subplan_approved'){
                                $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                                $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                            }
                        }
                        elseif (substr($message_type, 0, 7) === 'approve'){
                            if($message_type == 'approve_payment_request')
                            {
                                 $value['op_order_status'] = OperatorPayments::where('op_order_id',$value['message_view_id'])->value('op_order_status');
                                  $admin_notifications[$key]['op_order_status'] = $value['op_order_status'];
                            }
                    
                        }   
                    }
                
                    foreach ($admin_notifications as $key => $value)
                    {
                        $data_time = $value['created_at'];
                        $duration = $this->commonFunction->get_timeago($data_time);
                        $admin_notifications[$key]['duration']  = $duration;
                    }   
                        $result = array(
                        'mailtype' => "approved",
                        'admin_notifications' => $admin_notifications,
                        );
                        return response()->json($result);                                              
            }
            else if ($request['mailtype'] == "archive")
            {
                $offset = $request->offset;
                $getUserId = Auth::user()->hasRole('Super Admin');
                $users = User::select('id')->role('Super Admin')->first(); 
                $SuperAdminId = $users->id;
                $admin_notifications = AdminNotificationMessages::select('ggt_admin_notification_messages.notification_msg_id','ggt_admin_notification_messages.title','ggt_admin_notification_messages.message', 'ggt_admin_notification_messages.message_type','ggt_admin_notification_messages.message_view_id','ggt_admin_notification_messages.message_pattern','ggt_admin_notification_messages.message_response','ggt_admin_notification_messages.message_sender_id','ggt_admin_notification_messages.message_from','ggt_admin_notification.username','ggt_admin_notification.notification_id','ggt_admin_notification.message_id','ggt_admin_notification.is_read','ggt_admin_notification_messages.created_at','ggt_admin_notification.message_receiver_id')->join('ggt_admin_notification', 'ggt_admin_notification.message_id', '=', 'ggt_admin_notification_messages.notification_msg_id')->where('ggt_admin_notification.message_receiver_id', '=',Auth::user()->id)->orderBy('ggt_admin_notification_messages.notification_msg_id','desc')->get()->toArray();
                foreach ($admin_notifications as $key => $value) 
                {
                //dd($value);
                    $message_type = $value['message_type'];
                    $message_view_id = $value['message_view_id'];
                    $notification_msg_id = $value['notification_msg_id'];

                    if (substr($message_type, 0, 7) === 'subplan'){
                        $check_type = substr($message_type, 0, 7);
                        if($message_type == 'subplan_payment_receive'){
                            $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                            $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                        }
                        elseif ($message_type == 'subplan_verify' || $message_type == 'subplan_approved') {
                            // return $str_type = substr($message_type, strpos($message_type, "_") + 1);
                            $value['is_approved'] = subscriptionplan::where('subscription_id',$value['message_view_id'])->value('is_approved');
                            $admin_notifications[$key]['is_approved'] = $value['is_approved'];
                        }
                    }
                    elseif (substr($message_type, 0, 7) === 'approve'){
                        if($message_type == 'approve_payment_request')
                        {
                             $value['op_order_status'] = OperatorPayments::where('op_order_id',$value['message_view_id'])->value('op_order_status');
                             $admin_notifications[$key]['op_order_status'] = $value['op_order_status'];
                        }
                    }
                
                }       
                if(!empty($admin_notifications))
                {
                    foreach ($admin_notifications as $key => $value)
                    {
                        $data_time = $value['created_at'];
                        $duration = $this->commonFunction->get_timeago($data_time);
                        $admin_notifications[$key]['duration']  = $duration;
                    }           
                    $result = array(
                    'mailtype' => "inbox",
                    'admin_notifications' => $admin_notifications,
                    );
                    return response()->json($result);                       
                }     
                $result = array(
                        'mailtype' => "read",
                        'admin_notifications' => $admin_notifications,                        
                    );
                return response()->json($result);
            }
        }
        else
        {
                return false;
        }
    }

    public function delete_admin_notification(Request $request)
    {
        // dd($request->all()); 
        if(!empty($request))
        {
        
        $ids = explode(",", $request->notification_id);
        // dd($ids);
        foreach ($ids as $id)
        {

           $adminNotification = AdminNotification::find($id)->delete(); 
        }  
        return redirect()->back();
        }   
        else
        {
            return false;
        }  
    }

    public function updateSubcriptionDetail(Request $request)
    {
        AdminNotification::where('notification_id', $request->id)->update(['is_read' => 1]);
        $notif_details = AdminNotification::join('ggt_admin_notification_messages', 'ggt_admin_notification_messages.notification_msg_id', '=', 'ggt_admin_notification.message_id')->where('ggt_admin_notification.notification_id', '=',$request->id)->first();
        $plan_id = isset($notif_details['message_view_id']) ? $notif_details['message_view_id'] : null;
        if(!empty($request))
        {
            if($request['orderType'] == "Subscription")
            {
                // dd($request->all());
                if($request['val'] == 0)
                {
                    $update = Subscriptionplan::where('subscription_id', $plan_id)->update(['is_approved' => 1]);
                    if($update == 1){
                        $result =  array('status' => 'success', 'msg' => 'Plan has been approved');
                    }
                    else{
                        $result =  array('status' => 'failed', 'msg' => 'Failed');
                    }
                    
                }
                else if($request['val'] == 1)
                {
                    $update = Subscriptionplan::where('subscription_id', $plan_id)->update(['is_approved' => 0]);
                    if($update == 1){
                        $result =  array('status' => 'success', 'msg' => 'Success');
                    }
                    else{
                        $result =  array('status' => 'failed', 'msg' => 'Failed');
                    }
                }
                else
                {
                    $update = Subscriptionplan::where('subscription_id', $plan_id)->update(['is_approved' => 2]);
                    if($update == 1){
                        $result =  array('status' => 'success', 'msg' => 'Success');
                    }
                    else{
                        $result =  array('status' => 'failed', 'msg' => 'Failed');
                    }
                }
            }
            else{
                $update = AdminNotification::where('notification_id', $request->id)->update(['is_read' => 1]);
                if($update == 1){
                    $result =  array('status' => 'success', 'msg' => 'Success');
                }
                else{
                    $result =  array('status' => 'failed', 'msg' => 'Failed');
                }
            }   
        }else{
            $result =  array('status' => 'failed', 'msg' => 'Something Went Wrong');
        }
        return json_encode($result);
    }

    public function viewNotification($notification_id){
        // $notification_id = decrypt($notification_id);
        // dd($notification_id);
        $read = $this->getIsReadNotificationCount();
        $approved = $this->getApprovedNotificationCount();  
        $archive = $this->getArchiveNotificationCount();

        $details = AdminNotification::join('ggt_admin_notification_messages', 'ggt_admin_notification_messages.notification_msg_id', '=', 'ggt_admin_notification.message_id')->where('ggt_admin_notification.notification_id', '=',$notification_id)->first();
        // dd($details);
        AdminNotification::where('notification_id',$notification_id)->update(['is_read' => 1]);
        if($details['message_type'] == "Approve Subscription Plan" || $details['message_type'] == "Subscription Plan Approved" )
        {
            $subscriptionDetail = Subscriptionplan::select('subscription_id','subscription_type_name','subscription_amount','subscription_validity_type','subscription_business_rs','subscription_expected_enquiries','subscription_veh_wheel_type','subscription_validity_days','subscription_validity_from','subscription_validity_to','subscription_type_image','subscription_plan_created_by','is_free_trial','is_active','is_sent_for_approval','is_approved','is_approved_by')->where('subscription_id', $message_view_id)->first();
            if(!empty($subscriptionDetail))
            {
                $data['created_by'] = User::where('id',$subscriptionDetail->subscription_plan_created_by)->value('name');
                $parse_date_from = Carbon::parse($subscriptionDetail->subscription_validity_from);
                $parse_date_to = Carbon::parse($subscriptionDetail->subscription_validity_to);

                $data['validity_from'] = Carbon::createFromFormat('Y-m-d', $parse_date_from->toDateString())->toFormattedDateString();
                $data['validity_to'] = Carbon::createFromFormat('Y-m-d', $parse_date_to->toDateString())->toFormattedDateString();
                $data['created_date'] = Carbon::createFromFormat('Y-m-d', Carbon::parse($subscriptionDetail->created_at)->toDateString())->toFormattedDateString();
                $to = $subscriptionDetail->subscription_validity_to;
                $from = $subscriptionDetail->subscription_validity_from;
                if($from > $this->todays){
                    $data['subscription_expired'] = 2; //plan yet to be active
                    $data['subscription_active_indays'] = Carbon::parse($from)->diffInDays($this->todays);
                }
                else if($this->todays > $to || $this->todays > $to){
                    $data['subscription_expired'] = 0; //plan expired
                }
                else{
                    $data['subscription_expired'] = 1; //plan running
                }
                $result = array(
                    'orderType' => "Subscription",
                    'data' => isset($data) ? $data : null,
                    'subscriptionDetail' => isset($subscriptionDetail) ? $subscriptionDetail : null,
                    'from' => isset($from) ? $from : null,
                    'title' => $details['message_type'],
                    'duration' => $details['created_at'],
                );
            }
            else{
                $result = [];
            }
        }
        else if($details['message_type'] == 'op_verification_request' || $details['message_type'] == 'op_registered'){
            $op_details = Operator::select('op_user_id', 'op_first_name', 'op_last_name', 'op_mobile_no', 'op_email', 'op_type_id')->where('op_user_id', $details['message_view_id'])->first();
            if($details['message_type'] == 'op_verification_request'){
                $orderType = 'op_verification';
            }
            else{
                $orderType = 'op_registration';
            }
            $result = array(
                'orderType' => $orderType,
                'operator_details' => $op_details,
                'from' => $details['message_from'],
                'title' => $details['title'],
                'duration' => $details['created_at'],
            );
        }
        else
        {
            if(strpos($details['url'], 'operator') !== false){ //operator payment notification
                $payment_details = OperatorPayments::where('op_order_id', $details['message_view_id'])->first();
                if(!empty($payment_details)){
                    if(!empty($payment_details['op_user_id'])){
                        $op_details = Operator::select('op_first_name', 'op_uid')->where('op_user_id', $payment_details['op_user_id'])->first();
                        $payment_details['op_name'] = $op_details['op_first_name'];
                    }
                    else{
                        $payment_details['op_name'] = null;
                    }
                    if(!empty($payment_details['op_order_payment_p_details'])){
                        $payment_p_details = json_decode($payment_details['op_order_payment_p_details'], true);
                            if(!empty($payment_p_details['sub_scheme_name'])){
                                $plan_name = subscriptionplan::where('subscription_id', $payment_p_details['sub_scheme_name'])->value('subscription_type_name');
                                $payment_p_details['sub_scheme_name'] = $plan_name;
                            }
                        $payment_details['payment_p_details'] = $payment_p_details;
                    }
                    else{
                        $payment_details['payment_p_details'] = null;
                    }
                    $result = array(
                        'for' => "operator_payment",
                        'orderType' => "approve_payment",
                        'payment_p_details' => isset($payment_p_details) ? $payment_p_details : null,
                        'payment_details' => isset($payment_details) ? $payment_details : null,
                        'from' => $details['message_from'],
                        'title' => $details['message_type'],
                        'duration' => $details['created_at'],
                        'message_view_id' => $details['message_view_id']
                    );
                }
                else{
                   $result = [];
                }
            }
            else{ //customer payment notification
                $payment_details = UserPayments::where('user_order_id', $details['message_view_id'])->first();
                // dd($payment_details);
                 if(!empty($payment_details)){
                    if(!empty($payment_details['user_id'])){
                        $user_details = Customer::select('user_first_name', 'user_uid')->where('user_id', $payment_details['user_id'])->first();
                        $payment_details['user_name'] = $user_details['user_first_name'];
                    }
                    else{
                        $payment_details['user_name'] = null;
                    }
                    $result = array(
                        'for' => "customer_payment",
                        'orderType' => "approve_payment",
                        'payment_details' => isset($payment_details) ? $payment_details : null,
                        'from' => $details['message_from'],
                        'title' => $details['message_type'],
                        'duration' => $details['created_at'],
                        'message_view_id' => $details['message_view_id']
                    );
                }
                else{
                   $result = [];
                }
            }
        }
        if(!empty($result)){
            $result['notification_id'] = isset($notification_id) ? $notification_id : null;
        }
        // dd($result);
        return view('admin.notifications.notification-details',compact('read','approved','archive', 'result'));
    }
} 
     

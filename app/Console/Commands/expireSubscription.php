<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CustomAwsController;
use Carbon\Carbon;
use Config;
use Log;
use App\Models\Subscriptionplan;
use App\Models\OperatorPayments;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use App\Models\Operator;

class expireSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:expireSubscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify user about subscription expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->aws = new CustomAwsController;
        $this->todays = Carbon::today()->toDateString();
        $this->notifiy = new NotificationController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //subplan expired send notification to admin about subscription scheme is going to expired in 3 days.
        $subplan = Subscriptionplan::select('subscription_id','subscription_type_name','subscription_amount','subscription_validity_type','subscription_business_rs','subscription_expected_enquiries','subscription_veh_wheel_type','subscription_validity_days','subscription_validity_from','subscription_validity_to','is_free_trial','is_active','is_sent_for_approval','is_approved','is_approved_by','subscription_plan_created_by')->where('is_free_trial','=',0)->where('is_active',1)->where('is_approved',1)->orderBy('subscription_id', 'desc')->get()->toArray();
        
        $superAdminId = User::role('Super Admin')->value('id');
    /*
        if (!empty($subplan)) {
            foreach ($subplan as $key => $value) {
                $to = $value['subscription_validity_to'];
                //send daily notification within 3 days to purchase new plan
                $daysRemaining = Carbon::parse($to)->diffInDays($this->todays);
                if(0 <= $daysRemaining && $daysRemaining <=3 ){
                    $day = ($daysRemaining) == 0 ? 'todays' : $daysRemaining;
                    $admin = User::select('id','email')->where('id',$value['subscription_plan_created_by'])->first();

                    //send notification
                    $operatorNotification = array(
                        'subject' => 'Subscription plan expire in '.$day.'days.',
                        'message' => 'Your Subscription plan is going to expire in '.$day.' days buy new subscription to get benefited',
                        'type' => 'subplan_expiry',
                        'message_view_id' => isset($value['subscription_id']) ? $value['subscription_id'] : null,
                        'message_pattern' => 'A-A',
                        'message_sender_id' => $superAdminId,
                        'message_from' => 'Super Admin',
                        'receiver_id' => isset($admin->id) ? $admin->id : null,
                        );
                    $data = $this->notifiy->sendNotificationToAdmin($operatorNotification);
                    //end send notification

                    //send-email
                        // $emailArray[] = $admin->email;
                        $emailArray[] = 'madhuri@e-arth.in';
                        $email_subject = 'your subscription is going to expire in '.$day.' days';
                        $email_body = array(
                            'days' => $day,
                            'plan_name' => $value['subscription_type_name'],
                            'wheel_type' => $value['subscription_veh_wheel_type'],
                        );
                        $send_email = $this->aws->sendEmailTo($emailArray, $email_subject, $email_body,'subplan_expiry');
                    //end send-email

                    Log::warning('plans expired in 3 days cron executed successfully');
                    
                }
                else{
                    Log::warning('no plans is going to expired today');
                }

                if($value['subscription_validity_to'] < $this->todays){
                    $updateSubplan = subscriptionplan::where('is_active',1)->where('subscription_id',$value['subscription_id'])->update(array('is_active' => 0));
                    Log::warning('plan expired todays:command:expireSubscription');
                }else{
                    Log::warning('failed to update plan expired todays:command:expireSubscription');
                }
            }
        }else{
            Log::warning('plans is empty,command:expireSubscription ');
        }
        */
        //end of subplan expired: Admin

        //inform operator about subscription plan is going to expired in three days
         
            $superAdminId = User::role('Super Admin')->value('id');
            $payments = OperatorPayments::where('op_order_payment_purpose','subscription')->orderBy('created_at', 'desc')->get()->toArray();
            if(!empty($payments)){
                foreach ($payments as $key => $value) {
                    if(!empty($value['op_order_payment_p_details'])){
                        $payment_details = json_decode($value['op_order_payment_p_details']);
                        
                        //only if expiry date is grater than or equal to todays date
                        if($payment_details->sub_expiry >= $this->todays){
                            if(!empty($payment_details->sub_expiry)){
                                $daysRemain = Carbon::parse($payment_details->sub_expiry)->diffInDays($this->todays);
                            }
                            if(0 <= $daysRemain && $daysRemain <= 3 ){
                                $days = ($daysRemain) == 0 ? 'todays' : $daysRemain;
                                
                                if(!empty($payment_details->sub_scheme_name)){
                                    $subplan_details = Subscriptionplan::where('subscription_id',$payment_details->sub_scheme_name)->first();
                                }
                                $operatorNotification = array(
                                    'subject' => 'Subscription plan expire in '.$days.'days.',
                                    'message' => 'Your Subscription plan is going to expire in few days buy new subscription to get benefited',
                                    'type' => 'subplan_op_expiry',
                                    'message_view_id' => isset($payment_details->sub_scheme_name) ? $payment_details->sub_scheme_name : null,
                                    'message_pattern' => 'A-D',
                                    'message_sender_id' => $superAdminId,
                                    'message_from' => 'Super Admin',
                                    'receiver_id' => isset($value['op_user_id']) ? $value['op_user_id'] : null,
                                    );
                                $data = $this->notifiy->sendNotificationToOperator($operatorNotification);
                                //send-email
                                $op_email = Operator::where('op_user_id',$value['op_user_id'])->value('op_email');
                                $emailArray[] = $op_email;
                                $email_subject = 'your subscription is going to expire in 3 days';
                                $email_body = array(
                                    'days' => $days,
                                    'plan_name' => $subplan_details->subscription_type_name,
                                    'wheel_type' => $subplan_details->subscription_veh_wheel_type,
                                    );
                                // $send_email = $this->aws->sendEmailTo($emailArray, $email_subject, $email_body,'subplan_op_expiry');
                                //end send-email
                                Log::warning('operator plan is going to expired in'.$days);
                            }else{
                                Log::error('no operator plan is found to be expired in todays');
                            }
                        }else{
                            Log::error('do nothing on already expired plans');
                        }
                       
                    }else{
                        Log::error('operator payments details are empty,command:expireSubscription');
                    }
                }
            }else{
                Log::error('no payment had made yet,command:expireSubscription');
            }
            
        //end of subplan 
    }
}



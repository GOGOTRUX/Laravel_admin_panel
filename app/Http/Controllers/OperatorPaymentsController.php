<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Request;
use App\Http\Requests\Admin\VerifyOperatorUid;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CustomerPaymentsController;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AdminNotification;
use App\Models\OperatorPayments;
use App\Models\Operator;
use App\Models\subscriptionplan;
use App\Models\BankMaster;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Models\OperatorVehicles;
use Razorpay\Api\Api;
use Session;
use Redirect;
use Log;
use Config;
use Crypt;
use Carbon\Carbon;
use App\Models\OperatorAccounts;
use App\Models\UserPayments;

class OperatorPaymentsController extends Controller
{
	private $today;
	public $bucketname;
	public $amazon_s3_url;
	private $api;
	private $group;

	public function __construct()
	{
		$path = Request::capture()->path();
		if(!(substr_count($path,"/") == 0)){
			$this->group = strtolower(explode("/", $path)[1]);
		}
		// dd($path);
		$this->aws = new CustomAwsController;
		$this->notifiy = new NotificationController;
		$this->commonFunction = new CommonController;
		$this->customerPayments = new CustomerPaymentsController;

		$this->today = Carbon::today()->toDateString();
		$this->bucketname = Config::get('custom_config_file.bucket-name');
		$this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
		$this->api = new Api(config('custom_config_file.razor_key'), config('custom_config_file.razor_secret'));
	}

	public function index()
	{
	   $req_type = $this->group;
		//operator payments details
		// $sub_plan_list = subscriptionplan::with('subscription_types')->get()->toArray();
		$payments = OperatorPayments::orderBy('created_at', 'desc')->get()->toArray();
		if(!empty($payments)){
			foreach ($payments as $key => $value) {
				if(!empty($value['op_user_id'])){
					$payments[$key]['op_uid'] = Operator::where('op_user_id', $value['op_user_id'])->value('op_uid');
				}
				else{
					$payments[$key]['op_uid'] = null;
					Log::warning('operator id not set: index');
				}
			}
		}
		else{
			Log::warning('payments empty: index');
		}

		$op_details = $this->operator_details();

		// dd($payments);
		// $sub_plan_list = subscriptionplan::with(['subscription_types' => function($query){
		//     $query->select(['subscription_type_id','subscription_type_name']);
		// }])->get(['subscription_id', 'subscription_type_id', 'subscription_amount', 'subscription_desc'])->toArray();
		$sub_plan_list = subscriptionplan::where('is_approved', 1)->where('subscription_validity_from', '<=', $this->today)->where('subscription_validity_to', '>=', $this->today)->where('is_active', 1)->get()->toArray();

		$banks = BankMaster::select('id','op_bank_name')->get()->toArray();

		//customer payments details
		$customer_payments = UserPayments::orderBy('updated_at', 'desc')->get()->toArray();
		$customer_details = $this->customerPayments->customer_details();
		return view('admin.payments.index', compact('sub_plan_list', 'banks', 'payments', 'op_details', 'customer_payments', 'customer_details', 'req_type'));
	}

	public function operator_details(){
		$op_details = Operator::select('op_user_id','op_mobile_no', 'op_uid')->where('op_uid', '!=', null)->get()->toArray();
		// if(!empty($op_details)){
			$op_details = json_encode($op_details);
		// }
		return $op_details;
	}

	private function formatUploadedData($request){
		if(isset($request->cheque_img)){
			$dir = Config::get('custom_config_file.dir_cheque_img');
			$image_url = null; 
				
				if(!file_exists($dir))
				{
					mkdir($dir);
				}
				
				$cheque_img = $request->cheque_img;
				$op_mobile_no = $request->op_mobile_no;
				$data = date('Y_m_d_H_i_s');
				// $file_name = $request->cheque_img->getClientOriginalName();
				$file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->cheque_img->getClientOriginalName());
				$file_name = str_replace('-', '_',$file_name);
				// $new_file_name = str_replace(' ', '',$data."-"."bupan"."-".$file_name);
				$new_file_name = str_replace(' ', '',$op_mobile_no."-chequeImg-".$file_name);
				$image_path = "$dir/$new_file_name";                
				$cheque_img->move($dir,$new_file_name);
				$this->commonFunction->compressImage($image_path);
				$image_url = $this->aws->uploadToS3($new_file_name ,$image_path, $this->bucketname);

				//$cheque_img_path = $this->amazon_s3_url.$this->bucketname.'/'.$new_file_name;
				$cheque_img_path = $image_url;
		}
		else{
			$cheque_img_path = null;
		}

		if(!empty($request->op_payment_response)){
			$op_payment_gateway_response = $request->op_payment_response[0];
			$response = json_decode($op_payment_gateway_response, true);
			if(!empty($response['amount'])){
				$request->order_amount = $response['amount'];
			}
		}
		else{
			$op_payment_gateway_response = null;
		}

		if(!empty($request->order_payment_purpose) && ($request->order_payment_purpose == 'subscription')){
			$sub_dates = subscriptionplan::select('subscription_validity_to', 'subscription_validity_from', 'subscription_validity_days')->where('subscription_id', $request->sub_scheme_name)->first();
			// if(($sub_dates['subscription_validity_from'] <= $this->today) && ($sub_dates['subscription_validity_to'] >= $this->today)){
			// 	$sub_expiry = strtotime($this->today . '+ '.$request->sub_expiry.'days');
			// }
			// elseif($sub_dates['subscription_validity_from'] > $this->today){
			// 	$sub_expiry = strtotime($sub_dates['subscription_validity_from'] . '+ '.$request->sub_expiry.'days');
			// }
			$days = $sub_dates['subscription_validity_days'];

			$sub_expiry = Carbon::parse($this->today)->addDays($days)->format('Y-m-d');

			if($request->sub_valid_for == '3W'){
				$request->sub_valid_for = 3;
			}
			else if($request->sub_valid_for == '4W'){
				$request->sub_valid_for = 4;
			}
			else if($request->sub_valid_for == 'All'){
				$request->sub_valid_for = 1;
			}
			else{
				$request->sub_valid_for = 0;
			}
			$payment_purpose_details = array(
				'sub_scheme_name' => $request->sub_scheme_name,
				'sub_valid_for' => $days,
				'sub_expiry' => $sub_expiry,
			);
		}
		else{
			$payment_purpose_details = [];
		}
		$payment_bank = [];
		if(!empty($request->op_bank_name)){
			$payment_bank['op_bank_name'] = $request->op_bank_name;
		}
		if(!empty($request->op_payment_bank_branch)){
			$payment_bank['op_payment_bank_branch'] = $request->op_payment_bank_branch;
		}
		if(!empty($request->op_payment_bank_account_no)){
			$payment_bank['op_payment_bank_account_no'] = $request->op_payment_bank_account_no;
		}
		if(!empty($request->op_payment_bank_cheque_name)){
			$payment_bank['op_payment_bank_cheque_name'] = $request->op_payment_bank_cheque_name;
		}
		if(!empty($request->op_payment_bank_cheque_no)){
			$payment_bank['op_payment_bank_cheque_no'] = $request->op_payment_bank_cheque_no;
		}
		if(!empty($request->credit_id)){
			$payment_bank['credit_id'] = $request->credit_id;
		}
		if(!empty($request->credit_date)){
			$payment_bank['credit_date'] = $request->credit_date;
		}
		if(!empty($request->name_on_card)){
			$payment_bank['name_on_card'] = $request->name_on_card;
		}
		if(!empty($request->card_issued_by)){
			$payment_bank['card_issued_by'] = $request->card_issued_by;
		}
		if(!empty($request->card_no)){
			$payment_bank['card_no'] = $request->card_no;
		}
		if(!empty($cheque_img_path)){
			$payment_bank['cheque_img_path'] = $cheque_img_path;
		}
		if(!empty($credit_time)){
			$payment_bank['credit_time'] = $credit_time;
		}
		//commented for testing -nayana
		// $payment_bank = array(
			// 'op_bank_name' => isset($request->op_bank_name) ? $request->op_bank_name : null,
			// 'op_payment_bank_branch' => isset($request->op_payment_bank_branch) ? $request->op_payment_bank_branch : null,
			// 'op_payment_bank_account_no' => isset($request->op_payment_bank_account_no) ? $request->op_payment_bank_account_no : null,
			// 'op_payment_bank_cheque_name' => isset($request->op_payment_bank_cheque_name) ? $request->op_payment_bank_cheque_name : null,
			// 'op_payment_bank_cheque_no' => isset($request->op_payment_bank_cheque_no) ? $request->op_payment_bank_cheque_no : null,
			// 'credit_id' => isset($request->credit_id) ? $request->credit_id : null,
			// 'credit_date' => isset($request->credit_date) ? $request->credit_date : null,
			// 'cheque_img_path' => isset($cheque_img_path) ? $cheque_img_path : null,
			// 'credit_time' => isset($credit_time) ? $credit_time : null,
			// 'name_on_card' => isset($request->name_on_card) ? $request->name_on_card : null,
			// 'card_issued_by' => isset($request->card_issued_by) ? $request->card_issued_by : null,
			// 'card_no' => isset($request->card_no) ? $request->card_no : null,
		// );
		

		if (!empty($request->order_payment_instrument) && ($request->order_payment_instrument == 'digital')) {
			$request->order_date = $this->today;
			$request->trans_date = $this->today;
		}

		$data = array(
			'op_user_id' => $request->op_id,
			'op_uid' => $request->op_uid,
			'op_order_payment_purpose' => $request->order_payment_purpose,
			'op_order_amount' => $request->order_amount,
			'op_order_mobile_no' => $request->op_mobile_no,
			'op_order_email' => $request->op_email,
			'order_payee' => isset($request->order_payee) ? $request->order_payee : null,
			'order_receiver' => isset($request->order_receiver) ? $request->order_receiver : null,
			'order_reason' => isset($request->order_reason) ? $request->order_reason : null,
			'op_order_mode' => $request->order_payment_instrument,
			// 'op_bank_name' => $request->op_bank_name,
			// 'op_payment_bank_branch' => $request->op_payment_bank_branch,
			// 'op_payment_bank_account_no' => $request->op_payment_bank_account_no,
			// 'op_payment_bank_cheque_no' => $request->op_payment_bank_cheque_no,
			'op_order_date' => $request->order_date,// add time here : order_time
			'order_time' => $request->order_time,// add time here : order_time
			'created_by' => Auth::user()->name,
			// 'rp_trans_id' => $request->rp_trans_id,
			// 'trans_date' => $request->trans_date,
			// 'trans_time' => $request->trans_time,
			// 'card_no' => $request->card_no,
			// 'trans_status' => 'received',//default status
			// 'op_order_transaction_id' => $request->trans_id,
			'op_order_status' => 'received',//default status
			'op_payment_response' => $op_payment_gateway_response,
			'op_order_payment_p_details' => json_encode($payment_purpose_details),
			'op_order_payment_bank_details' => json_encode($payment_bank),
		);
		return $data;
	}

	public function store(Request $request)
	{
		// dd($request->all());

		$data = $this->formatUploadedData($request);
		// dd($data);
		$result = OperatorPayments::create($data);
		if($result){
			$tranx_id = $this->commonFunction->generateTransactionID();
			$update_id = OperatorPayments::where('op_order_id', $result->op_order_id)->update(['op_order_transaction_id' => $tranx_id]);

			$getBalance = OperatorAccounts::select('total_credits', 'total_balance')->where('op_user_id', $request->op_id)->first();
			$account_data = array(
				'total_credits' => $getBalance['total_credits'] + $data['op_order_amount'],
				'total_balance' => $getBalance['total_balance'] + $data['op_order_amount']
				);
			$updateAccount = OperatorAccounts::where('op_user_id', $request->op_id)->update($account_data);

			if($request->sms_check == 'on'){
				$mobile_no = $request->op_mobile_no;
				$message = '';
				$message .= 'Thankyou from GOGOTRUX ! ';
				$message .= 'Your payment is received against '.$request->order_payment_purpose.'. ';
				$message .= 'UID: '.$result['op_uid'];
				$message .= ' Transaction ID: '.$tranx_id;
				$message .= ' Amount: ₹ '.$result['op_order_amount'];
				$message .= ' Date & Time:'.$result['op_order_date'];;
				$message .= ' Please check ‘my accounts’ in your GOGOTRX app for more details.';
				$this->aws->sendSmsOTP($mobile_no, $message);
			}

			if($request->paymentInfo == 'Send For Approval'){
			    $result = $this->sendMethod($result->id);
			    $response = 'Payment successful!!! Your payment has been successfully send for approval.';
			}
			else{
				$response = 'Payment information has been saved!!!.';
			}
		}
		return redirect()->back()->with('success', $response);
	}

	public function edit($id){
		$payment_id = decrypt($id);
		$payment_details = OperatorPayments::where('op_order_id', $payment_id)->first();
		if(!empty($payment_details)){
			$op_balance = $this->getOperatorAccountBalance($payment_details['op_uid']);
			$payment_details['subscription'] = $this->getSubscriptionDetails($payment_details['op_uid']);
			$payment_details['credit_balance'] = $op_balance['credit_balance'];
			$payment_details['debit_balance'] = $op_balance['debit_balance'];

			$op_details = Operator::select('op_first_name')->where('op_user_id', $payment_details['op_user_id'])->first();
			$payment_details['op_name'] = isset($op_details['op_first_name']) ? $op_details['op_first_name'] : null;
			$plan_details = json_decode($payment_details['op_order_payment_p_details'], true);
			if(!empty($plan_details)){
				$payment_details['plan_details'] = subscriptionplan::select('subscription_validity_days', 'subscription_veh_wheel_type', 'subscription_amount')->where('subscription_id', $plan_details['sub_scheme_name'])->first();
				if(!empty($payment_details['plan_details'])){
					if($payment_details['plan_details']['subscription_veh_wheel_type'] == 3){
						$payment_details['plan_details']['subscription_veh_wheel_type'] = "3W";
					}elseif($payment_details['plan_details']['subscription_veh_wheel_type'] == 4){
						$payment_details['plan_details']['subscription_veh_wheel_type'] = "4W";
					}elseif($payment_details['plan_details']['subscription_veh_wheel_type'] == 1){
						$payment_details['plan_details']['subscription_veh_wheel_type'] = "All";
					}else{
						$payment_details['plan_details']['subscription_veh_wheel_type'] = "MW";
					}
				}
			}
			else{
				$payment_details['plan_details'] = [];
			}
			$bank_details = json_decode($payment_details['op_order_payment_bank_details'], true);
			if(!empty($bank_details)){
				$payment_details['bank_details'] = $bank_details;
			}
			else{
				$payment_details['bank_details'] = [];
			}
		}
		else{
			$payment_details['op_name'] = null;
			$payment_details['subscription'] = [];
			$payment_details['credit_balance'] = 0.00;
			$payment_details['debit_balance'] = 0.00;
		}
		
		$op_details = $this->operator_details();
		$sub_plan_list = subscriptionplan::where('is_approved', 1)->where('subscription_validity_from', '<=', $this->today)->where('subscription_validity_to', '>=', $this->today)->where('is_active', 1)->get()->toArray();

		$banks = BankMaster::select('id','op_bank_name')->get()->toArray();
		// dd($payment_details);
		return view('admin.payments.operatorPayments.edit', compact('payment_details', 'op_details', 'sub_plan_list', 'banks'));
	}

	public function update(Request $request, $id)
	{
		$payment_id = decrypt($id);
		// dd($request->all());
		$data = $this->formatUploadedData($request);
		// dd($data);
		$result = OperatorPayments::where('op_order_id', $payment_id)->update($data);

		if($result == 1){
			$pay_details = OperatorPayments::select('op_uid', 'op_order_date', 'op_order_amount', 'op_order_payment_purpose', 'op_order_transaction_id', 'op_order_date', 'order_time')->where('op_order_id', $payment_id)->first();
			
			if($request->paymentInfo == 'Send For Approval'){
				$response = 'Payment information updated and successfully send for approval.';
				$msg_status = 'Your payment is updated annd send for approval against ';
			}
			else{
				$msg_status = 'Your payment is updated against ';
				$response = 'Payment information updated successfully.';
			}
			if($request->sms_check == 'on'){
				$mobile_no = $request->op_mobile_no;
				$message = '';
				$message .= 'Thankyou from GOGOTRUX ! ';
				$message .= $msg_status.$pay_details['op_order_payment_purpose'].'. ';
				$message .= 'UID: '.$pay_details['op_uid'];
				$message .= ' Transaction ID: '.$pay_details['op_order_transaction_id'];
				$message .= ' Amount: ₹ '.$pay_details['op_order_amount'];
				$message .= ' Date & Time:'.$pay_details['op_order_date'].', '.$pay_details['order_time'];
				$message .= ' Please check ‘my accounts’ in your GOGOTRX app for more details.';
				$this->aws->sendSmsOTP($mobile_no, $message);
			}
		}
		else{
			$response = 'Something went wrong.';
		}
		return redirect('/payments/operator')->with('success', $response);
	}

	public function getOperatorPaymentDetails(Request $request)
	{
		$serching_param = $request->op_uid;
		$serching_type = $request->type;
		if ($serching_type == 'by_id') {
			$details = Operator::select('op_user_id','op_mobile_no','op_first_name','op_last_name','op_email','op_username', 'op_deposit', 'op_uid')
				->where(function($query) use ($serching_param) {
					$query->where('op_user_id', 'LIKE', '%'.$serching_param.'%');
				})
				->where('op_uid', '!=', null)
				->first();
		}
		else{
			$details = Operator::select('op_user_id','op_mobile_no','op_first_name','op_last_name','op_email','op_username', 'op_deposit', 'op_uid')
				->where(function($query) use ($serching_param) {
					$query->where('op_uid', 'LIKE', '%'.$serching_param.'%');
				})
				->where('op_uid', '!=', null)
				->first();
		}

			// dd($details);
		if(!empty($details['op_uid'])){
			
			// dd($operator_sub_details);
			$details['subscription'] = $this->getSubscriptionDetails($details['op_uid']);
			$details['avail_sub_plans'] = $this->getSubscriptionPlans($details['op_user_id']);
			$op_balance = $this->getOperatorAccountBalance($details['op_uid']);
			$details['credit_balance'] = $op_balance['credit_balance'];
			$details['debit_balance'] = $op_balance['debit_balance'];
		}
		else{
			$details['subscription'] = [];
			$details['avail_sub_plans'] = [];
			$details['credit_balance'] = null;
			$details['debit_balance'] = null;
			Log::warning("UID not Set");
		}
			 
		return json_encode($details);
	}

	private function getSubscriptionDetails($op_uid){
		$operator_sub_details = OperatorPayments::where('op_uid', $op_uid)->where('op_order_payment_purpose', 'subscription')->get()->toArray();
		$subscription_details = [];
		if(!empty($operator_sub_details)){
			foreach ($operator_sub_details as $key => $value) {
				if(!empty($value['op_order_payment_p_details'])){
					$plan_details = json_decode($value['op_order_payment_p_details'],true);
					if($plan_details['sub_expiry'] < $this->today){
						unset($operator_sub_details[$key]);
					}
					else{
						$plan_name = subscriptionplan::where('subscription_id', $plan_details['sub_scheme_name'])->value('subscription_type_name');

						$subscription_details = array(
							'plan_name' => $plan_name,
							'plan_validity' => $plan_details['sub_expiry'],
							'plan_status' => $value['op_order_status']
						);
					}
				}
				else{
					$subscription_details = array(
						'plan_name' => null,
						'plan_validity' => null,
						'plan_status' => null
					);
					Log::warning("plan details not available");
				}
			}
		}
		else{
			$subscription_details = array(
				'plan_name' => null,
				'plan_validity' => null,
				'plan_status' => null
			);
			Log::warning("subscription not available");
		}
		return $subscription_details;
	}

	private function getSubscriptionPlans($op_id=null){
        
        $plan_details1 = subscriptionplan::where('is_active', 1)->where('is_approved', 1)->where('is_free_trial', 0)->get(); 
        $plans = collect($plan_details1);
        // plan which are not expired
        $filterplans = $plans->filter(function ($value, $key) {
            $parse_date_to = Carbon::parse($value->subscription_validity_to);
            
            $today_date = $this->today;
            $end_date = $value->subscription_validity_to;
            // datetime
            $end_datetime = strtotime($value->subscription_validity_to);
            $today = strtotime($this->today);

            if($end_datetime > $today || $end_date==$today_date || ($value->is_active==1 && $value->is_free_trial==1 && $value->is_approved==1)){
                return $value;
            }
        });
        $filterFreePlans = $filterplans;
        // $plans = $filterplans;
        if(!empty($op_id)){
            $op_type_id =Operator::where('op_user_id',$op_id)->value('op_type_id');
            if(!empty($op_type_id) && $op_type_id==1){
                //individual users plan
                $veh_wheel_type = OperatorVehicles::where('veh_op_id', $op_id)->where('is_active', 1)->where('is_deleted', 0)->value('veh_wheel_type');
                $Ind_Plans = $filterFreePlans->filter(function ($value, $key) use($veh_wheel_type) {
                    return $value->subscription_veh_wheel_type == $veh_wheel_type || $value->subscription_veh_wheel_type==1;
                });

                $plans = $Ind_Plans;
            }else{
                $Bnd_Plans = $filterFreePlans->filter(function ($value, $key) {
                    return $value->subscription_veh_wheel_type == 0 || $value->subscription_veh_wheel_type==1;
                });
                $plans = $Bnd_Plans; //business user plans    
            }
        }else{
            $plans = $filterFreePlans;
        }   
        
        $plans_details = $plans->toArray();
        $plans = array();

        foreach ($plans_details as $key => $value) {
            array_push($plans, $value);
        }
        $total_count = 0;
        return $plans;
	}

	public function show(){
		$req_type = $this->group;

		//operator payments details
		// $sub_plan_list = subscriptionplan::with('subscription_types')->get()->toArray();
		$payments = OperatorPayments::orderBy('created_at', 'desc')->get()->toArray();
		if(!empty($payments)){
			foreach ($payments as $key => $value) {
				if(!empty($value['op_user_id'])){
					$payments[$key]['op_uid'] = Operator::where('op_user_id', $value['op_user_id'])->value('op_uid');
				}
				else{
					$payments[$key]['op_uid'] = null;
					Log::warning('operator id not set: index');
				}
			}
		}
		else{
			Log::warning('payments empty: index');
		}

		$op_details = $this->operator_details();

		// dd($payments);
		// $sub_plan_list = subscriptionplan::with(['subscription_types' => function($query){
		//     $query->select(['subscription_type_id','subscription_type_name']);
		// }])->get(['subscription_id', 'subscription_type_id', 'subscription_amount', 'subscription_desc'])->toArray();
		$sub_plan_list = subscriptionplan::where('is_approved', 1)->where('subscription_validity_from', '<=', $this->today)->where('subscription_validity_to', '>=', $this->today)->where('is_active', 1)->get()->toArray();

		$banks = BankMaster::select('id','op_bank_name')->get()->toArray();

		//customer payments details
		$customer_payments = UserPayments::orderBy('updated_at', 'desc')->get()->toArray();
		$customer_details = $this->customerPayments->customer_details();
		return view('admin.payments.index', compact('sub_plan_list', 'banks', 'payments', 'op_details', 'customer_payments', 'customer_details', 'req_type'));
	}

	public function getOperatorAccountBalance($op_uid){
		$result = [];
		$getBalance = OperatorAccounts::select('total_credits', 'total_balance', 'total_debits')->where('op_uid', $op_uid)->first();
		if(!empty($getBalance)){
			$result['credit_balance'] = $getBalance['total_credits'];
			$result['debit_balance'] = $getBalance['total_debits'];
		}
		else{
			$result['credit_balance'] = 0.00;
			$result['debit_balance'] = 0.00;	
		}
		return $result;
	}

	public function getOperatorList(Request $request)
	{
		$serching_param = $request->op_uid;
		$details = Operator::select('op_user_id','op_mobile_no', 'op_uid')
			->where(function($query) use ($serching_param) {
				$query->where('op_uid', 'LIKE', '%'.$serching_param.'%');
			})
			->orwhere(function($query) use ($serching_param) {
				$query->where('op_mobile_no', 'LIKE', '%'.$serching_param.'%');
			})
			->where('op_uid', '!=', null)
			->get()->toArray();
			 
		return json_encode($details);
	}

	public function getPlanDetails(Request $request){
		$plan_id = $request->sub_id;
		$plan_details = subscriptionplan::select('subscription_validity_days', 'subscription_veh_wheel_type', 'subscription_amount')->where('subscription_id', $plan_id)->first();
		return json_encode($plan_details);
	}

	public function deletePayment(Request $request){
		$payment = OperatorPayments::where('op_order_id', $request->payment_id);
		$payment->delete();
		return json_encode(['status' => 'success', 'message' => 'Payment has been deleted successfully!']);
	}

	public function markAsReceived(Request $request){
		$update_status = OperatorPayments::where('op_order_id', $request->payment_id)->update(['op_order_status' => 'received']);
		if($update_status == 1){
			$result =  array('status' => 'success', 'msg' => 'Payment has been mark as received');
		}else{
			$result =  array('status' => 'failed', 'msg' => 'Something went wrong.');
		}
		return json_encode($result);
	}

	public function sendForApproval(Request $request){
		// dd($request->all());
		$result = $this->sendMethod($request->payment_id);
		
		return json_encode($result);
	}

	private function sendMethod($payment_id){
		$update_status = OperatorPayments::where('op_order_id', $payment_id)->update(['op_order_status' => 'waiting_for_approval']);
		if($update_status == 1){
			$payment_info = OperatorPayments::select('op_order_id', 'created_by')->where('op_order_id', $payment_id)->first();
			
			$adminNotification = array(
				'subject' => 'Approve Payment',
				'message' => 'You have new payment created by'.$payment_info['created_by'].', approve the payment',
				'type' => 'approve_payment_request',
				'message_view_id' => isset($payment_id) ? $payment_id : null,
				'message_pattern' => 'A-A',
				'message_sender_id' => Auth::user()->id,
				'message_from' => Auth::user()->name ,
				'url' => '/payments/operator',
			);
			$data = $this->notifiy->sendNotificationToAdmin($adminNotification);
			
			$result =  array('status' => 'success', 'msg' => 'Payment has been send to approve successfully!');
		}else{
			$result =  array('status' => 'failed', 'msg' => 'Something went wrong.');
		}
		return $result;
	}

	public function viewPayment($id){
		$payment_id = decrypt($id);
		$payment_details = OperatorPayments::where('op_order_id', $payment_id)->first();
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
		}
		else{
			$payment_details['op_name'] = null;
			$payment_details['payment_p_details'] = null;
		}
		// dd($payment_details);
		return view('admin.payments.operatorPayments.view', compact('payment_details'));
	}

	public function ApprovePaymentBySuperAdmin(Request $request){
		// dd($request->all());
		$result = $this->updateStatus($request['id']);
		return json_encode($result);
	}

	public function updatePaymentStatus(Request $request){
		$update = AdminNotification::where('notification_id', $request->id)->update(['is_read' => 1]);
		date_default_timezone_set('Asia/Kolkata'); 
		if($request->status == 'approve'){
			$details = AdminNotification::join('ggt_admin_notification_messages', 'ggt_admin_notification_messages.notification_msg_id', '=', 'ggt_admin_notification.message_id')->where('ggt_admin_notification.notification_id', '=',$request->id)->first();
			if($request->payment_for == 'operator_payment'){
				$result = $this->updateStatus($details['message_view_id']);
			}
			else{
				$result = $this->customerPayments->updatePaymentOrderStatus($details['message_view_id']);
			}
		}
		else{ //code will not be in use-implemented in notificationToDoController
			//notification is hold or rejected
			if($update == 1){
                $result =  array('status' => 'success', 'msg' => 'Success');
            }
            else{
                $result =  array('status' => 'failed', 'msg' => 'Failed');
            }
		}
		return json_encode($result);
	}

	private function updateStatus($id){
		date_default_timezone_set('Asia/Kolkata'); 
		$data = array(
			'op_order_status' => 'approved', 
			'op_order_payment_is_approved' => 1,
			'op_order_payment_approved_by' => Auth::User()->name,
			'updated_at' => date('Y-m-d H:i:s'));
		$update_status = OperatorPayments::where('op_order_id', $id)->update($data);
		if($update_status == 1){
			$payment_info = OperatorPayments::select('op_order_id', 'op_user_id', 'created_by', 'op_order_payment_purpose', 'op_order_amount')->where('op_order_id', $id)->first();

			$operatorNotification = array(
	        	'subject' => 'Payment Approved',
	        	'message' => 'Your payment has been approved by '.Auth::user()->name.'.',
	        	'type' => 'payment_approved',
	        	'message_view_id' => isset($id) ? $id : null,
	        	'message_pattern' => 'A-P',
	        	'message_sender_id' => Auth::user()->id,
	        	'message_from' => Auth::user()->name ,
	        	'receiver_id' => isset($payment_info['op_user_id']) ? $payment_info['op_user_id'] : null,
	    		);
	    	$data = $this->notifiy->sendNotificationToOperator($operatorNotification);

	    	// send email to creater about plans has been verified
			// $firstname = User::where('op_user_id', '=', $user->op_user_id)->value('op_first_name');

			$email_Array = ['madhuri@e-arth.in'];
			$email_Subject = 'Congratulation!, Payment has been approved';
			$email_Body = array(
				'created_by' => $payment_info['created_by'],
				'payment_purpose' => $payment_info['op_order_payment_purpose'],
				'payment_amount' => $payment_info['op_order_amount'],
			);
			$send_email = $this->aws->sendEmailTo($email_Array, $email_Subject, $email_Body ,'payment_approved');
			$result =  array('status' => 'success', 'msg' => 'Payment has been approved');
		}else{
			$result =  array('status' => 'failed', 'msg' => 'Something went wrong.');
		}
		return $result;
	}

	public function payCreditDebitFun()
	{
		return view('admin.payments.operatorPayments.credit_debit_note');
	}

	public function isValidPlan(Request $request){
		// dd($request->all());
		$subscription_id = isset($request['subscription_id']) ? $request['subscription_id'] : null;
		$operator_id = isset($request['oprator_id']) ? $request['oprator_id'] : null;
        $op_details = Operator::select('op_first_name','op_last_name','op_user_id','op_uid','op_mobile_no','op_email','op_type_id')->where('op_uid',$operator_id)->first();
        $op_name = $op_details->op_first_name.''.$op_details->op_last_name;
        $sub_details = subscriptionplan::where('subscription_id',$subscription_id)->first();
        $vehData = OperatorVehicles::where('veh_op_id',$op_details['op_user_id'])->where('is_active',1)->where('is_deleted',0)->count();

        if($vehData==0){
            $response = ['status' => 'failed','message' => 'Please add vehicle to buy subscription' ,'statusCode' => Response::HTTP_BAD_REQUEST];
            return json_encode($response);          
        }else if($vehData > 0 && $op_details->op_type_id==2 && $vehData > $sub_details->subscription_no_of_veh_allowed && $sub_details->subscription_veh_wheel_type!=1){
            $response = ['status' => 'failed','message' => 'This plan is valid only for maximum '.$sub_details->subscription_no_of_veh_allowed.' vehicle' ,'statusCode' => Response::HTTP_BAD_REQUEST];
            return json_encode($response);
        }else{
            $response = ['status' => 'success','message' => ' ','statusCode' => Response::HTTP_OK];
            return json_encode($response);
        }
	}
}

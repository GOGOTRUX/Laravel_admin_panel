<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\CommonController;
use App\Models\CustomerBookTrip;
use App\Models\CustomerAccounts;
use App\Models\UserPayments;
use App\Models\Customer;

use Carbon\Carbon;
use Config;
use Log;

class CustomerPaymentsController extends Controller
{
	public function __construct()
	{
		
		// $path = Request::capture()->path();
		// if(!(substr_count($path,"/") == 0)){
		// 	$this->group = strtolower(explode("/", $path)[1]);
		// }
		// dd($path);
		$this->aws = new CustomAwsController;
		$this->notifiy = new NotificationController;
		$this->commonFunction = new CommonController;
		// $this->customerPayments = new CustomerPaymentsController;

		$this->today = Carbon::today()->toDateString();
		$this->bucketname = Config::get('custom_config_file.bucket-name');
		$this->amazon_s3_url = Config::get('custom_config_file.amazon_s3_url');
		// $this->api = new Api(config('custom_config_file.razor_key'), config('custom_config_file.razor_secret'));
	}

    public function customer_details(){
		$customer_details = Customer::select('user_id','user_mobile_no', 'user_uid')->where('user_uid', '!=', null)->get()->toArray();
		// if(!empty($customer_details)){
			$customer_details = json_encode($customer_details);
		// }
		return $customer_details;
	}

	public function getCustomerPaymentDetails(Request $request)
	{
		$searching_param = $request->user_uid;
		$searching_type = $request->type;
		if ($searching_type == 'by_id') {
			$details = Customer::select('user_id','user_mobile_no','user_first_name','user_last_name','email', 'user_uid')
				->where(function($query) use ($searching_param) {
					$query->where('user_id', 'LIKE', '%'.$searching_param.'%');
				})
				->where('user_id', '!=', null)
				->first();
		}
		else{
			$details = Customer::select('user_id','user_mobile_no','user_first_name','user_last_name','email', 'user_uid')
				->where(function($query) use ($searching_param) {
					$query->where('user_uid', 'LIKE', '%'.$searching_param.'%');
				})
				->where('user_uid', '!=', null)
				->first();
		}

			// dd($details);
		if(!empty($details['user_uid'])){
			$balnace = $this->getCustomerAccountBalance($details['user_uid']);
			$details['credit_balance'] = $balnace['credit_balance'];
			$details['debit_balance'] = $balnace['debit_balance'];
		}
		else{
			$details['credit_balance'] = null;
			$details['debit_balance'] = null;
			Log::warning("UID not Set");
		}
			 
		return json_encode($details);
	}

	public function getCustomerAccountBalance($user_uid){
		$result = [];
		$getBalance = CustomerAccounts::select('total_credits', 'total_balance', 'total_debits')->where('user_uid', $user_uid)->first();
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

	public function store(Request $request)
	{
		// dd($request->all());

		$data = $this->formatUploadedData($request);
		$result = UserPayments::create($data);
		if($result){
			$tranx_id = $this->generateTransactionID();
			$update_id = UserPayments::where('user_order_id', $result->user_order_id)->update(['user_order_transaction_id' => $tranx_id]);

			$getBalance = CustomerAccounts::select('total_credits', 'total_balance')->where('user_id', $request->user_id)->first();
			$account_data = array(
				'total_credits' => $getBalance['total_credits'] + $data['user_order_amount'],
				'total_balance' => $getBalance['total_balance'] + $data['user_order_amount']
				);
			$updateAccount = CustomerAccounts::where('user_id', $request->user_id)->update($account_data);

			if($request->sms_check == 'on'){
				$mobile_no = $request->user_mobile_no;
				$message = '';
				$message .= 'Thankyou from GOGOTRUX ! ';
				$message .= 'Your payment is received against '.$request->order_payment_purpose.'. ';
				$message .= 'UID: '.$result['user_cid'];
				$message .= ' Transaction ID: '.$tranx_id;
				$message .= ' Amount: ₹ '.$result['user_order_amount'];
				$message .= ' Date & Time:'.$result['user_order_date'];;
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

	private function formatUploadedData($request){
		if(isset($request->cheque_img)){
			$dir = Config::get('custom_config_file.dir_cheque_img');
			$image_url = null; 
				
				if(!file_exists($dir))
				{
					mkdir($dir);
				}
				
				$cheque_img = $request->cheque_img;
				$user_mobile_no = $request->user_mobile_no;
				$data = date('Y_m_d_H_i_s');
				// $file_name = $request->cheque_img->getClientOriginalName();
				$file_name = preg_replace('/[^a-zA-Z0-9_.]/', '', $request->cheque_img->getClientOriginalName());
				$file_name = str_replace('-', '_',$file_name);
				// $new_file_name = str_replace(' ', '',$data."-"."bupan"."-".$file_name);
				$new_file_name = str_replace(' ', '',$user_mobile_no."-chequeImg-".$file_name);
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

		if(!empty($request->user_payment_response)){
			$user_payment_gateway_response = $request->user_payment_response[0];
			$response = json_decode($user_payment_gateway_response, true);
			if(!empty($response['amount'])){
				$request->order_amount = $response['amount'];
			}
		}
		else{
			$user_payment_gateway_response = null;
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
		if(!empty($request->user_bank_name)){
			$payment_bank['user_bank_name'] = $request->user_bank_name;
		}
		if(!empty($request->user_payment_bank_branch)){
			$payment_bank['user_payment_bank_branch'] = $request->user_payment_bank_branch;
		}
		if(!empty($request->user_payment_bank_account_no)){
			$payment_bank['user_payment_bank_account_no'] = $request->user_payment_bank_account_no;
		}
		if(!empty($request->user_payment_bank_cheque_name)){
			$payment_bank['user_payment_bank_cheque_name'] = $request->user_payment_bank_cheque_name;
		}
		if(!empty($request->user_payment_bank_cheque_no)){
			$payment_bank['user_payment_bank_cheque_no'] = $request->user_payment_bank_cheque_no;
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
		
		if (!empty($request->order_payment_instrument) && ($request->order_payment_instrument == 'digital')) {
			$request->order_date = $this->today;
			$request->trans_date = $this->today;
		}

		$data = array(
			'user_id' => $request->user_id,
			'user_cid' => $request->user_uid,
			'user_order_payment_purpose' => $request->order_payment_purpose,
			'user_order_amount' => $request->order_amount,
			'user_order_mobile_no' => $request->user_mobile_no,
			'user_order_email' => $request->op_email,
			'order_payee' => isset($request->order_payee) ? $request->order_payee : null,
			'order_receiver' => isset($request->order_receiver) ? $request->order_receiver : null,
			'order_reason' => isset($request->order_reason) ? $request->order_reason : null,
			'user_order_pay_mode' => $request->order_payment_instrument,
			// 'op_bank_name' => $request->op_bank_name,
			// 'op_payment_bank_branch' => $request->op_payment_bank_branch,
			// 'op_payment_bank_account_no' => $request->op_payment_bank_account_no,
			// 'op_payment_bank_cheque_no' => $request->op_payment_bank_cheque_no,
			'user_order_date' => $request->order_date,// add time here : order_time
			'order_time' => $request->order_time,// add time here : order_time
			'created_by' => Auth::user()->name,
			// 'rp_trans_id' => $request->rp_trans_id,
			// 'trans_date' => $request->trans_date,
			// 'trans_time' => $request->trans_time,
			// 'card_no' => $request->card_no,
			// 'trans_status' => 'received',//default status
			// 'op_order_transaction_id' => $request->trans_id,
			'op_order_status' => 'received',//default status
			'user_payment_response' => $user_payment_gateway_response,
			'user_order_payment_p_details' => json_encode($payment_purpose_details),
			'user_order_payment_bank_details' => json_encode($payment_bank),
		);
		return $data;
	}

	public function generateTransactionID(){
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$string = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 14; $i++) {
			$n = rand(0, $alphaLength);
			$string[] = $alphabet[$n];
		}
		$tranx_id = implode($string);
		$new_tax_id = 'pay_'.$tranx_id;
		$isExists = UserPayments::where('user_order_transaction_id', $new_tax_id)->exists();
		if($isExists){
			$this->generateTransactionID();
		}
		else{
			return $new_tax_id;
		}
	}

	public function show($id){
		$customer_payment_id = decrypt($id);
		$payment_details = UserPayments::select('*')->where('user_order_id', $customer_payment_id)->first();
		if(!empty($payment_details)){
			$userdetails = Customer::select('user_id', 'user_first_name', 'user_middle_name', 'user_last_name', 'user_mobile_no', 'user_uid')->where('user_id', $payment_details['user_id'])->first();
			$payment_details['user_details'] = $userdetails;
		}
		else{
			$payment_details['user_details'] = null;
		}
		return view('admin.payments.customerPayments.view', compact('payment_details'));
	}

	public function sendForApproval(Request $request){
		// dd($request->all());
		$result = $this->sendMethod($request->payment_id);
		
		return json_encode($result);
	}

	private function sendMethod($payment_id){
		$update_status = UserPayments::where('user_order_id', $payment_id)->update(['user_order_status' => 'waiting_for_approval']);
		if($update_status == 1){
			$payment_info = UserPayments::select('user_order_id', 'created_by')->where('user_order_id', $payment_id)->first();
			
			$adminNotification = array(
				'subject' => 'Approve Payment',
				'message' => 'You have new payment created by'.$payment_info['created_by'].', approve the payment',
				'type' => 'approve_payment_request',
				'message_view_id' => isset($payment_id) ? $payment_id : null,
				'message_pattern' => 'A-A',
				'message_sender_id' => Auth::user()->id,
				'message_from' => Auth::user()->name ,
				'url' => '/payments/customer',
			);
			//$data = $this->notifiy->sendNotificationToAdmin($adminNotification);
			
			$result =  array('status' => 'success', 'msg' => 'Payment has been send to approve successfully!');
		}else{
			$result =  array('status' => 'failed', 'msg' => 'Something went wrong.');
		}
		return $result;
	}

	public function markAsReceived(Request $request){
		$update_status = UserPayments::where('user_order_id', $request->payment_id)->update(['user_order_status' => 'received']);
		if($update_status == 1){
			$result =  array('status' => 'success', 'msg' => 'Payment has been mark as received');
		}else{
			$result =  array('status' => 'failed', 'msg' => 'Something went wrong.');
		}
		return json_encode($result);
	}

	public function updatePaymentOrderStatus($id){
		date_default_timezone_set('Asia/Kolkata'); 
		$data = array(
			'user_order_status' => 'approved', 
			'user_order_payment_is_approved' => 1,
			'user_order_payment_approved_by' => Auth::User()->name,
			'updated_at' => date('Y-m-d H:i:s'));
		$update_status = UserPayments::where('user_order_id', $id)->update($data);
		if($update_status == 1){
			$payment_info = UserPayments::select('user_order_id', 'user_id', 'created_by', 'user_order_payment_purpose', 'user_order_amount')->where('user_order_id', $id)->first();

			$operatorNotification = array(
	        	'subject' => 'Payment Approved',
	        	'message' => 'Your payment has been approved by '.Auth::user()->name.'.',
	        	'type' => 'payment_approved',
	        	'message_view_id' => isset($id) ? $id : null,
	        	'message_pattern' => 'A-U',
	        	'message_sender_id' => Auth::user()->id,
	        	'message_from' => Auth::user()->name ,
	        	'receiver_id' => isset($payment_info['user_id']) ? $payment_info['user_id'] : null,
	    		);
	    	//add method to send notofcation to customer

	    	// send email to creater about plans has been verified
			// $firstname = User::where('user_id', '=', $user->user_id)->value('op_first_name');

			$email_Array = ['madhuri@e-arth.in'];
			$email_Subject = 'Congratulation!, Payment has been approved';
			$email_Body = array(
				'created_by' => $payment_info['created_by'],
				'payment_purpose' => $payment_info['user_order_payment_purpose'],
				'payment_amount' => $payment_info['user_order_amount'],
			);
			//$send_email = $this->aws->sendEmailTo($email_Array, $email_Subject, $email_Body ,'payment_approved');
			$result =  array('status' => 'success', 'msg' => 'Payment has been approved');
		}else{
			$result =  array('status' => 'failed', 'msg' => 'Something went wrong.');
		}
		return $result;
	}
	
	public function getCustomerTrips(Request $request){
		$postData = $request->all();
		$getTrips = CustomerBookTrip::join('ggt_user_payments','ggt_user_book_trip.pay_order_id','=','ggt_user_payments.user_order_id')
		->select('id','trip_transaction_id','user_order_status','ride_status')
		->where('ggt_user_book_trip.user_id',$postData['customer_id'])
		->where('ggt_user_payments.user_order_status','pending')
		->get()
		->toArray();
		//return ($getTrips);
		$html = '<select name="trip_transaction_id" class="m-l-5 form-control" id="trip_transaction_id">';
		foreach ($getTrips as $key => $value) {
			$html .= '<option value="'.$value['trip_transaction_id'].'">'.$value['trip_transaction_id'].'</option>';
		}
		$html .= '</select><label class="control-label"><b>Trip Id </b></label>';
		return $html;
		
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPayments extends Model
{
    protected $table = 'ggt_user_payments';
    protected  $primaryKey = 'user_order_id';
	protected $fillable = [
		'user_id', 
		'user_order_email', 
		'user_order_mobile_no', 
		'user_order_pay_mode', 
		'user_order_amount',
		'user_order_status',
		'user_order_transaction_id',
		'user_order_failure_id',
		'user_order_paylink_id',
		'user_payment_response',
		'user_payment_response',
		'user_paylink_response',
		'user_order_receipt_no',
		'user_order_cheque_no',
		'user_order_date',
		'user_order_cheque_amount',
		'user_order_remark',
		'deleted_at',
		'user_order_payment_purpose',
		'user_order_payment_is_approved',
		'user_order_payment_approved_by',
		'user_order_payment_cheque_img',
		'user_cid',
		'user_order_payment_bank_details',
		'user_order_payment_p_details',
		'user_order_payment_upi',

	];
}

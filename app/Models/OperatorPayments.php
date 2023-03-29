<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorPayments extends Model
{
    protected $table = 'ggt_op_payments';
    protected  $primaryKey = 'op_order_id';
	protected $fillable = [
		'op_user_id', 
		'op_uid', 
		'op_order_username', 
		'op_order_email', 
		'op_order_mobile_no', 
		'op_order_mode',
		'op_order_amount',
		'op_order_transaction_id',
		'op_order_failure_id',
		'op_payment_response',
		'op_order_receipt_no',
		'op_order_receipt_date',
		'op_order_status',
		'op_is_verified',
		'trip_transaction_id',
		'op_order_dep_type',
		'op_order_payment_purpose',
		'op_order_date',
		'order_time',
		'op_order_payment_p_details',
		'op_order_payment_bank_details',
		'created_by',
		'order_payee',
		'order_receiver',
		'order_reason',
		'op_order_payment_reason',
		'is_approved',
	];
}
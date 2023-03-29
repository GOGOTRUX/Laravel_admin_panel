<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepositePayment extends Model
{
    use Notifiable;
    use SoftDeletes;



	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $dates = ['deleted_at'];

    protected $table = 'ggt_op_deposite_payment';
    protected $primaryKey = 'op_order_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'op_order_id', 'op_user_id', 'op_order_username', 'op_order_email', 'op_order_mobile_no', 'op_order_mode', 'op_order_amount', 'op_order_transaction_id', 'op_order_failure_id', 'op_payment_response', 'op_order_receipt_no', 'op_order_receipt_date', 'op_order_status', 'op_is_verified', 'op_order_cheque_no', 'op_order_cheque_ifsc', 'op_order_cheque_date', 'op_order_cheque_amount', 'op_order_cheque_bank', 'updated_at', 'created_at', 'deleted_at'];
}

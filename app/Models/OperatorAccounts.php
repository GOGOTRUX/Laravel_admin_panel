<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorAccounts extends Model
{
    protected $primaryKey = 'account_id';
    protected $table = 'ggt_operator_accounts';
    protected $fillable = [
        'op_user_id', 'op_uid', 'op_mobile_no', 'total_debits', 'total_credits', 'total_balance','deposite_amount','subscription_credit_by_value','subscription_credit_by_enquiry','loyalty_bonus','adjustment_credit','credit_deposite_amount','trip_pay_amount','credit_trip_pay_amount','refund_amount','credit_refund_amount','subscription_amount','other_pay_amount','credit_other_pay_amount'
        ];
}

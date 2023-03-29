<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAccounts extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'ggt_user_accounts';
    protected $fillable = [
        'user_uid', 'user_id', 'user_mobile_no', 'total_debits', 'total_credits', 'total_balance'
        ];
}

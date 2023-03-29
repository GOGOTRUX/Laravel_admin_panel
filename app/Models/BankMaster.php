<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankMaster extends Model
{
    protected $table = 'ggt_op_bank_list';
	protected $fillable = [
		'op_bank_name', 'op_bank_ifsc', 'op_user_id', 'created_at', 'updated_at'
	];
}

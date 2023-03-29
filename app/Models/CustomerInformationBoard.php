<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInformationBoard extends Model
{
    protected $table = 'ggt_customer_information_board';
	protected $fillable = [
		'admin_id', 
		'info_board_text', 
		'created_by', 
	];
}


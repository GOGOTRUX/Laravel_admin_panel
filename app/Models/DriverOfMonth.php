<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverOfMonth extends Model
{
    protected $table = 'ggt_driver_of_month';
	protected $fillable = [
		'admin_id', 
		'admin_email', 
		'op_mobile_no', 
		'comment',
	];
}

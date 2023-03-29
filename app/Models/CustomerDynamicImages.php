<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDynamicImages extends Model
{
    protected $table = 'ggt_customer_dynamic_images';
	protected $fillable = [
		'admin_id',
		'created_by',
		'image_name',
		'image_type',
	];
}

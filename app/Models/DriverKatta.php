<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverKatta extends Model
{
    protected $table = 'ggt_driver_katta';
    protected $primaryKey = 'id';
	protected $fillable = [
		'admin_id', 
		'driver_katta_text', 
		'driver_katta_image',
		'katta_up_slider',
		'katta_useful_up_link',
		'katta_bottom_slider',
		'katta_useful_down_link',
		'katta_mt_tips_image',
		'katta_bd_assistance',
		'katta_load_unload_image',
		'katta_essential_tool_image',
		'created_at', 
		'updated_at', 
		'deleted_at', 
	];
} 

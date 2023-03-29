<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GogotruxMitr extends Model
{
    protected $table = 'ggt_mitr';
	protected $fillable = [
		'admin_id', 
		'ggt_mitr_text', 
		'ggt_mitr_image',
		'created_by', 
	];
}

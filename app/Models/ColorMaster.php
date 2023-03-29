<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ColorMaster extends Model
{
    use SoftDeletes;
    protected $table = 'ggt_master_color';
	protected $fillable = [
		'id', 'name', 'color'
	];
	protected $dates = ['deleted_at'];
}





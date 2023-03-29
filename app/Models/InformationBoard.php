<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformationBoard extends Model
{
    protected $table = 'ggt_information_board';
	protected $fillable = [
		'admin_id', 
		'info_board_text', 
		'created_by', 
	];
}

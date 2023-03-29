<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateFactor extends Model
{
    
    use SoftDeletes;
    protected $table = 'ggt_master_factor';
    protected $primaryKey = 'factor_id';
    protected $fillable = [
    'variable_name',
    'variable_value',
    'existing_value', 
    'new_value', 
    'revision_date',
    'created_by',
    'approval_date',
    'approved_by', 
	];
    protected $hidden = [
		'deleted_at','created_at','updated_at', 
	];
}

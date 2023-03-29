<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OvertimeCharges extends Model
{
    use Notifiable;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ggt_overtime_charges';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'admin_id','overtime','charges'
    ];
    protected $dates = ['created_at','updated_at'];

}

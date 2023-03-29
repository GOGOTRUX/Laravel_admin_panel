<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Driver extends Model
{
    use Notifiable;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ggt_drivers';
    protected $primaryKey = 'driver_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'op_user_id',
        'driver_first_name', 
        'driver_last_name',
        'driver_op_username',
        'driver_profile_pic',
        'driver_mobile_number',
        'working_shift_days',
        'working_shift_time',
        'driver_offline_hrs',
        'driver_last_location',
        'driver_is_online', 
        'is_active',
        'driver_is_verified', 
        'is_book',
    ];
}


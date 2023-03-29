<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use Notifiable;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table = 'ggt_user';
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         	'user_uid','user_code', 'user_first_name','user_middle_name','user_last_name','user_gender','email','user_type','address_pin_code','rememberToken','user_notification_token','user_address_line','user_address_line_1','user_address_line_2','user_address_line_3',
         	'user_gender', 'user_mobile_no', 'user_profile_pic', 
         	'user_dob', 'is_active', 'created_at', 'updated_at','address_city','address_state','current_location','user_verified','is_blocked'
    ];
    protected $dates = ['deleted_at'];
}


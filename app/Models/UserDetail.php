<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $table = 'ggt_user';
    protected $fillable = [
        'user_uid',
        'user_code',
        'user_first_name',
        'user_middle_name',
        'user_last_name',
        'user_gender',
        'user_mobile_no',
        'email',
        'user_type',
        'user_profile_pic',
        'user_dob',
        'address_pin_code',
        'address_city',
        'address_state',
        'current_location',
        'rememberToken',
        'user_notification_token',
        'is_active',
        'user_verified',
        'is_blocked',
        'user_account_block_note',
        'created_at',
        'updated_at',
        'deleted_at',
        'user_address_line',
        'user_address_line_1',
        'user_address_line_2',
        'user_address_line_3'
    ];

    /**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
        'created_at', 
        'updated_at',
        'deleted_at'
	];
}
<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
	use Notifiable;
    use SoftDeletes;
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ggt_operator_users';
    protected $primaryKey = 'op_user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'op_user_id',
        'op_first_name', 'op_last_name', 'op_mobile_no',
        'op_username', 'op_password', 'op_alternative_mobile_no',
        'op_email', 'op_gender', 'op_pet_name', 'op_city_name', 
        'op_type_id', 'op_address_line_1', 'op_address_line_2', 
        'op_address_line_3', 'op_address_city', 'op_address_pin_code', 
        'op_address_state', 'op_address_country', 'op_bank_name', 
        'op_bank_ifsc', 'op_bank_account_number', 'is_active', 'last_login_ip', 
        'op_registration_state', 'operator_selected', 'op_bu_address_city', 
        'op_bu_address_line_1', 'op_bu_address_line_2', 'op_bu_address_line_3', 
        'op_bu_address_pin_code', 'op_bu_address_state', 'op_bu_base_charge', 
        'op_bu_charge_per_person', 'op_bu_email', 'op_bu_gstn_no', 'op_bu_landmark', 
        'op_bu_loader_available', 'op_bu_name', 'op_bu_no_person', 'op_bu_pan_no', 
        'op_bu_per_km', 'op_bu_type', 'op_dob', 'op_profile_pic', 'op_status', 
        'op_registration_step', 'op_payment_mode', 'op_landmark', 'op_is_verified',
        'remember_token', 'is_op_bank_verified'
    ];
    
}

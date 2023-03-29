<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OperatorVehicles extends Model
{
    use Notifiable;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ggt_op_vehicles';
    protected $primaryKey = 'veh_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'veh_op_username', 'veh_owner_name', 'veh_owner_mobile_no', 'veh_type', 'veh_driver_id', 'veh_registration_no', 'veh_images', 'veh_capacity', 'veh_last_location', 'veh_is_online', 'is_active', 'is_deleted', 'veh_city', 'veh_op_id', 'veh_dimension', 'veh_wheel_type', 'veh_model_name', 'veh_make_model_type', 'veh_op_type', 'veh_op_ownership', 'veh_base_lat_lng','veh_color','veh_fuel_type','veh_base_charge','veh_per_km','veh_loader_available','veh_no_person','veh_charge_per_person','veh_base_charge_rate_per_km','book_dates_json','is_book_dates','veh_last_loc_lati','veh_last_loc_long','veh_base_lati','veh_base_long'
    ];
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class vehiclefacilitymaster extends Model
{
    use SoftDeletes;

	/**
     * The database table used by the model.
     *
     * @var string
     */    

    protected $table = 'ggt_vehicle_facility_master';
    protected $primaryKey = 'veh_fac_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = 
    [
    	 'veh_fac_id', 'veh_fac_type', 'veh_fac_desc','veh_fac_data_type', 'veh_fac_is_required', 'created_at', 'updated_at'
    ];
    protected $dates = ['deleted_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleFacilities extends Model
{
    //
    
    use SoftDeletes;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $dates = ['deleted_at'];

    protected $table = 'ggt_vehicle_facilities';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = 
    [
    	 'id', 'veh_id', 'veh_fac_master_id', 'veh_fac_value', 'created_at', 'updated_at'
    ];
    protected $dates = ['deleted_at'];
}

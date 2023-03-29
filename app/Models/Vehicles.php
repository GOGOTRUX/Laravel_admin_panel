<?php

namespace App;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicles extends Model
{
    use Notifiable;
    use SoftDeletes;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ggt_vehicles';
    protected $primaryKey = 'veh_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'veh_model_name','veh_type_name','is_active',
    ];
    protected $dates = ['deleted_at'];

}

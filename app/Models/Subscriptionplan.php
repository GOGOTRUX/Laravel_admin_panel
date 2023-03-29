<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\subscriptiontypes;

class subscriptionplan extends Model
{
    use Notifiable;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table = 'subscription_plans';
    protected $primaryKey = 'subscription_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = [
        
     'subscription_id', 'subscription_type_id', 'subscription_amount', 'subscription_validity_type', 'subscription_business_rs', 'subscription_expected_enquiries', 'subscription_veh_wheel_type', 'subscription_validity_days', 'subscription_validity_from', 'subscription_validity_to', 'subscription_desc', 'subscription_bid_advantage', 'subscription_priority_scale', 'subscription_efficency', 'is_active','Subscriptionplan','is_sent_for_approval','is_approved','is_approved_by','subscription_plan_created_by'
    ];

    protected $dates = ['deleted_at'];

    public function subscription_types()
    {
        return $this->belongsTo('App\Models\subscriptiontypes','subscription_type_id');
    }
}


            
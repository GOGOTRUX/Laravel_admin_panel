<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class subscriptiontypes extends Model
{
    use Notifiable;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subscription_types';
    protected $primaryKey = 'subscription_type_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'subscription_type_id','subscription_type_name', 'subscription_created_by', 'is_deleted', 'is_active', 'created_at', 'updated_at' 
    ];
}

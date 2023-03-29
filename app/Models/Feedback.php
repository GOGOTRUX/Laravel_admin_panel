<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Feedback extends Model
{
     use Notifiable;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ggt_driver_ride_feedback';
    protected $primaryKey = 'ride_feedback_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'ride_feedback_id', 'user_id', 'user_code', 'op_driver_id', 'rating', 'comments' 
        
    ];
}

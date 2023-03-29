<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBookRide extends Model
{
    protected $table = 'ggt_user_book_ride';
    protected $fillable = [
    	'user_id',
    	'requested_veh_type_id',
    	'start_address_line_1',
    	'start_address_line_2',
    	'start_address_line_3',
    	'start_city',
    	'start_country',
    	'start_state',
    	'start_pincode',
    	'start_address_lat',
    	'start_address_lan',
    	'dest_address_line_1',
    	'dest_address_line_2',
    	'dest_address_line_3',
    	'dest_city',
    	'dest_country',
    	'dest_state',
    	'dest_pincode',
    	'dest_address_lat',
    	'dest_address_lan',
    	'intermediate_address',
    	'schedule_for',
    	'trip_date',
    	'trip_time'
    ];
    /**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_at', 'updated_at',
	];
}

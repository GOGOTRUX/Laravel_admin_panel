<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class CustomerBookTrip extends Authenticatable
{
    protected $table = 'ggt_user_book_trip';
    protected $primaryKey = 'id';
	protected $fillable =  [
		'user_id' ,               
		'trip_transaction_id',    
		'op_driver_id',           
		'op_veh_id',              
		'op_id',                  
		'pay_order_id',           
		'book_date',              
		'start_address_line_1',   
		'start_address_line_2',   
		'start_address_line_3',   
		'start_address_line_4',   
		'start_pincode',          
		'start_address_lat',      
		'start_address_lan',      
		'dest_address_line_1',    
		'dest_address_line_2',    
		'dest_address_line_3',    
		'dest_address_line_4',    
		'dest_pincode',           
		'dest_address_lat',       
		'dest_address_lan',       
		'intermediate_address',   
		'material_type',          
		'vehicle_type',           
		'vehicle_fuel_type',      
		'weight',                 
		'is_bid',                 
		'loader_count',           
		'loader_price',           
		'payment_type',           
		'base_amount',            
		'actual_amount',
		'ggt_factor',          
		'note',                   
		'ride_status',            
		'trip_start_time',        
		'total_distance',         
		'arrival_time',           
		'total_time',             
		'otp',                    
		'destinations_ongoing',   
		'destinations_completed', 
		'material_acceptance',    
		'bill_details',           
		'op_bid_response',        
		'close_trip_response',    
		'disputed_trip_response', 
		'updated_at',
		'temp_book_trip_id',
		'is_trip_acp_rej',
		'user_adjustment',
		'op_adjustment',
		'ggt_adjustment',
    ];
    
    /**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'deleted_at','created_at',
	];
}

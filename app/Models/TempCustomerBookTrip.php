<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempCustomerBookTrip extends Model
{
     protected $table = 'temp_ggt_user_book_trip';
    protected $primaryKey = 'id';
	protected $fillable =  [   
	'id',                   
	'user_id',              
	'op_driver_id',         
	'op_veh_id',            
	'op_id',                
	'pay_order_id',         
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
	'weight',               
	'is_bid',               
	'loader_count',         
	'loader_price',         
	'payment_type',         
	'order_date',           
	'order_time',           
	'ride_status',          
	'is_booked',
	'created_at'
	];
	protected $hidden = [
		'deleted_at','updated_at', 
	];    
}

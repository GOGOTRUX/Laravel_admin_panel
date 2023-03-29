<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBookRideDetails extends Model
{
    protected $table = 'ggt_user_book_ride_details';
    protected $fillable = [
    	'ride_id',
    	'op_driver_id',
    	'op_veh_id',
    	'op_id',
    	'helper_count',
    	'helper_price',
    	'payment_mode',
    	'coupon_id',
    	'is_coupon_applied',
    	'total_discount_amount',
    	'base_amount',
    	'actual_amount',
    	'gst_tax',
    	'gst_tax_per',
    	'payment_gateway_name',
    	'payment_gateway_currency',
    	'payment_gateway_response',
    	'payment_gateway_transaction_id',
    	'order_date',
    	'note',
    	'ggt_admin_comission',
    	'op_payable_comission',
    	'ride_status',
    	'payment_order_status'
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

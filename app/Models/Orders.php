<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Orders extends Model
{
     use Notifiable;

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ggt_user_book_ride_details';
    protected $primaryKey = 'book_ride_details_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'book_ride_details_id', 'ride_id', 'op_driver_id', 'op_veh_id', 'op_id', 'helper_count', 'helper_price', 'payment_mode', 'coupon_id', 'is_coupon_applied', 'total_discount_amount', 'base_amount', 'actual_amount', 'gst_tax', 'gst_tax_per', 'payment_gateway_name', 'payment_gateway_currency', 'payment_gateway_response', 'payment_gateway_transaction_id', 'order_date', 'payment_order_status', 'note', 'ggt_admin_comission', 'op_payable_comission', 'ride_status',
    ];
}


	
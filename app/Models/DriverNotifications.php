<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverNotifications extends Model
{
    use SoftDeletes;
    protected $table = 'ggt_driver_notification';
    protected $primaryKey = 'notification_id';
    protected $fillable = [
		'message_id', 'op_mobile_no','op_user_id','is_read','username','message_receiver_id',
	];
	protected $dates = ['deleted_at'];
}

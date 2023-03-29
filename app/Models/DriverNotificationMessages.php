<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverNotificationMessages extends Model
{
    protected $table = 'ggt_driver_notification_messages';
    protected $primaryKey = 'notification_msg_id';
    protected $fillable = [
		'title', 'message', 'message_type', 'url','message_view_id','message_pattern','message_response','message_sender_id','message_from','message_payload'
	];
}

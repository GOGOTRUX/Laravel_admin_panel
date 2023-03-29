<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class AdminNotificationMessages extends Model
{
    // use SoftDeletes;
    protected $table = 'ggt_admin_notification_messages';
     protected $primaryKey = 'notification_msg_id';
    protected $fillable = [
		'title', 'message', 'message_type', 'url','message_view_id','message_pattern','message_response','message_sender_id','message_from',
	];
	// protected $dates = ['deleted_at'];
}



 

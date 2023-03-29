<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotificationMessages extends Model
{
    protected $table = 'ggt_user_notification_messages';
    protected $primaryKey = 'notification_msg_id';

    protected $fillable = [
    	'title',
        'message',
        'message_type',
        'message_view_id',
        'message_pattern',
        'message_response',
        'message_sender_id',
        'message_from',
        'message_payload',
        'url',
    ];

    public function UserNotification()
    {
        return $this->hasMany('App\Models\UserNotification','notification_msg_id');
    }
}

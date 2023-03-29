<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminNotification extends Model
{
	use SoftDeletes;
    protected $table = 'ggt_admin_notification';
    protected $fillable = [
		'is_read','message_id', 'username','message_receiver_id',
	];
     protected $primaryKey = 'notification_id';

	protected $dates = ['deleted_at'];
}


 

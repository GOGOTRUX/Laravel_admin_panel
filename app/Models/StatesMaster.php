<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatesMaster extends Model
{
    protected $table = 'ggt_master_states';

    /**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	*/
	protected $hidden = [
		'created_at', 'updated_at',
	];
}

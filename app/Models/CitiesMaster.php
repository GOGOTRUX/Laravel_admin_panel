<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitiesMaster extends Model
{
    protected $table = 'ggt_master_cities';

    /**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	*/
	protected $hidden = [
		'created_at', 'updated_at',
	];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterMaterial extends Model
{
    protected $table='ggt_master_material';
    protected $fillable=[
        'id',
        'material_type',
       
    ];
      /**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'deleted_at','created_at','updated_at',
	];
}

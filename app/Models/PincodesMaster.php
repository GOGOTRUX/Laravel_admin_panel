<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PincodesMaster extends Model
{
    protected $table = 'ggt_master_pincodes';

    public function state()
    {
        return $this->belongsTo('App\Models\StatesMaster');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\CitiesMaster');
    }
}

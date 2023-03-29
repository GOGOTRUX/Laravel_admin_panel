<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class GetaddressController extends Controller
{  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCityState(Request $request)
    {                
        
        $list=DB::table('ggt_master_pincodes')
                  ->join('ggt_master_states','ggt_master_states.id','=','ggt_master_pincodes.state_id')
                  ->join('ggt_master_cities','ggt_master_cities.id','=','ggt_master_pincodes.city_id')->select('ggt_master_cities.id','ggt_master_cities.city','ggt_master_pincodes.state_id','ggt_master_states.state')
                  ->where('ggt_master_pincodes.pincode','=',$request->op_address_pin_code)->get();   
        return $list;
    }    
}

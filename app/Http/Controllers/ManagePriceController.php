<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CreateFactor;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ManagePriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        if (! Gate::allows('price_manage')) 
        {
            return abort(401);
        }
        else{
        	$getFactors = CreateFactor::select('ggt_master_factor.*','ggt_admins.name','ggt_admins.email')
    		->join('ggt_admins','ggt_master_factor.created_by','=','ggt_admins.id')
    		->get()
    		->toArray();
        	return view('admin.price.index',['savedfactors' => $getFactors]);
        }
    }

    public function createFactor(Request $request){
    	$postdata = $request->all();
    	if(!empty($postdata)){
    		//get admin details
            $user = Auth::getUser();
            $admin_id = $user->id;
            $admin_email = $user->email;

    		$input = array(
                'variable_name' => isset($postdata['fac_variable']) ? $postdata['fac_variable']:null,
                'variable_value' => isset($postdata['fac_select']) ? $postdata['fac_select']:null,
                'new_value' => isset($postdata['fac_ggt']) ? $postdata['fac_ggt']:null,
                'created_by' => $admin_id,
            );
            $saveFactor = CreateFactor::create($input);
            $getFactors = CreateFactor::select('ggt_master_factor.*','ggt_admins.name','ggt_admins.email')
    				->join('ggt_admins','ggt_master_factor.created_by','=','ggt_admins.id')
    				->get()
    				->toArray();
            
            if($saveFactor){
            	return view('admin.price.index',['savedfactors' => $getFactors])->with('success', 'Factor created successfully!');
            }  
    	}else{
    		return view('admin.price.index')->with('error', 'Something went wrong try again!');  
    	}
    }
}

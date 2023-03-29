<?php

namespace App\Http\Controllers;

use App\Models\VehicleFacilities;
use App\Models\Vehiclefacilitymaster;
use App\Models\Vehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laracasts\Flash\Flash;
use DB;
use Validator;

class VehicleFacilitiesController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	/*      */
	public function index()
	{
		if (! Gate::allows('vehicle_facility_manage')) {
			return abort(401);
		}
		else{
			$Vehicles=Vehicles::whereNull('deleted_at')->get();
			$veh_fac_master=Vehiclefacilitymaster::all();
			return view('admin.vehiclesfacility.index',compact('Vehicles', 'veh_fac_master'));
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function create()
	{
		if (! Gate::allows('vehicle_facility_create')) {
			return abort(401);
		}
		else{
			$inputfields=DB::table('ggt_vehicle_facility_master')->get();
			return view('admin.vehiclesfacility.create',compact('inputfields')); 
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
	   
		if (! Gate::allows('vehicle_facility_create')) {
			return abort(401);
		}
		else{
			$veh_id=DB::table('ggt_vehicles')->insertGetId(
					['veh_model_name'=>$request->veh_model_name,
					'veh_type_name'=>$request->veh_type_name,
					'is_active'=>1,
					'created_at'=>date('Y-m-d H:i:s'),
					'updated_at'=>date('Y-m-d H:i:s'),
					]
				);
			
			if(isset($veh_id))  
			{  
				for($i=0;$i<count($request->veh_fac_value);$i++)
				{    
					$veh_facility_details=DB::table('ggt_vehicle_facilities')
						 ->insert([
									'veh_id'=>$veh_id,
									'veh_fac_master_id'=>$request->veh_fac_master_id[$i],
									'veh_fac_value'=>$request->veh_fac_value[$i],
									'created_at'=>date('Y-m-d H:i:s'),
									'updated_at'=>date('Y-m-d H:i:s'),
								]);
				}
				session()->flash('success', 'New vehicles model has been created successfully!');
				return redirect()->route('vehiclesfacility.index');
			}
			else
			{
				return redirect('vehiclesfacility/create');				
			}
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\VehicleFacilities  $vehicleFacilities
	 * @return \Illuminate\Http\Response
	 */
	public function show(VehicleFacilities $vehicleFacilities)
	{
		//
		
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	
	public function edit(Request $request,$id)
	{
	   
		if (! Gate::allows('vehicle_facility_edit')) {
			return abort(401);
		}
		else{
			$Vehicles=DB::table('ggt_vehicles')->where('veh_id','=',$id)->get();        
		 
			$VehicleFacilities=DB::table('ggt_vehicle_facilities')->
									join('ggt_vehicle_facility_master','ggt_vehicle_facility_master.veh_fac_id','=','ggt_vehicle_facilities.veh_fac_master_id')->where('veh_id','=',$id)->get();


			return view('admin.vehiclesfacility.edit',compact('Vehicles','VehicleFacilities'));
		}
	}

	public function deleteSelected(Request $request)
	{
		
		if (! Gate::allows('vehicle_facility_delete'))
		{
			return abort(401);
		}
		else{
			foreach ($request->selectid as $deleteid)
			{                        
				$vehicles=DB::table("ggt_vehicles")
						->where('veh_id','=',$deleteid)            
						->update(['deleted_at'=>date('Y-m-d H:i:s')]);  
			}        
			return "Multiple Vehicles deleted successfully"; 
		}	
	}

	
	 /**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function softdelete(Request $request)
	{
		
		if (! Gate::allows('vehicle_facility_delete'))
		{
			return abort(401);
		}
		else{
			$vehfac=DB::table('ggt_vehicle_facilities')->where('id','=',$request->selectid)
					->update(['deleted_at'=> 1]);
			 return 'Vehicles facility has been deleted successfully!';         
		}
	}
		

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\VehicleFacilities  $vehicleFacilities
	 * @return \Illuminate\Http\Response
	 */

	public function update(Request $request)
	{
	   if (! Gate::allows('vehicle_facility_edit')) {
			return abort(401);
		}
		else{
			$vehicles=DB::table('ggt_vehicles')->where('veh_id','=',$request->veh_id)
					 ->update([
								'veh_model_name'=>$request->veh_model_name,
								'is_active'=>$request->is_active,
								'veh_type_name'=>$request->veh_type_name,
								'updated_at'=>date('Y-m-d H:i:s'),
							 ]);
			//if($vehicles)
			{
				for($i=0;$i<count($request->veh_fac_value);$i++)
				{                                
					 $vehfac=DB::table('ggt_vehicle_facilities')
					->where("ggt_vehicle_facilities.id", '=',$request->id[$i])
					->update([                
					  'veh_fac_value'=>$request->veh_fac_value[$i],
					  'updated_at'=>date('Y-m-d H:i:s'),
					]);

				}
			}
			session()->flash('success', 'Vehicles model has been updated successfully!');
			return redirect()->route('vehiclesfacility.index');
		}
	}
	public function getcapacity(Request $request)
	{
		$details=DB::table('ggt_vehicle_facilities')->where('ggt_vehicle_facilities.veh_id','=',$request->modelname)->get();
		return $details;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\VehicleFacilities  $vehicleFacilities
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		
		if (! Gate::allows('vehicle_facility_delete')) {
			return abort(401);
		}
		else{
			$vehicles=DB::table("ggt_vehicles")
						->where('veh_id','=',$request->selectid)            
						->update(['deleted_at'=>date('Y-m-d H:i:s')]); 
			 
			return 'vehicles has been deleted successfully!';                     
		}
	}
}

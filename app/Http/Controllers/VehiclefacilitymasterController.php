<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Vehiclefacilitymaster;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Response;
use Validator;
use DB;
use File;


class VehiclefacilitymasterController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	
		if (! Gate::allows('vehicle_facility_master_manage')) {
			return abort(401);
		}
		else{
			$veh_fac_master=Vehiclefacilitymaster::all();
			return view('admin.vehiclemaster.index', compact('veh_fac_master'));
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if (! Gate::allows('vehicle_facility_master_manage')) {
			return abort(401);
		}
		else{
			return view('admin.vehiclemaster.create');   
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
	   if (! Gate::allows('vehicle_facility_master_manage')) {
			return abort(401);
		}
		else{

			$veh_fac_master = new Vehiclefacilitymaster();

			$veh_fac_master->veh_fac_type = $request->input('veh_fac_type');
			$veh_fac_master->veh_fac_desc = $request->input('veh_fac_desc');
			$veh_fac_master->veh_fac_data_type = $request->input('veh_fac_data_type');
			$veh_fac_master->veh_fac_is_required = $request->input('veh_fac_is_required');
			$veh_fac_master->save();
			return redirect()->route('vehiclesfacility.index')->with('success', 'New Vehicle facility master has been created successfully!');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\vehiclefacilitymaster  $vehiclefacilitymaster
	 * @return \Illuminate\Http\Response
	 */
	public function show(vehiclefacilitymaster $vehiclefacilitymaster)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\vehiclefacilitymaster  $vehiclefacilitymaster
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request,$id)
	{
		if (! Gate::allows('vehicle_facility_master_manage')) {
			return abort(401);
		}
		else{
			$veh_fac_master=Db::table('ggt_vehicle_facility_master')->where('veh_fac_id','=',$id)->get();

			return view('admin.vehiclemaster.edit', compact('veh_fac_master'));
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\vehiclefacilitymaster  $vehiclefacilitymaster
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request ,$id)
	{
		
		if (! Gate::allows('vehicle_facility_master_manage')) {
			return abort(401);
		}
		else{
			$veh_fac_master=DB::table('ggt_vehicle_facility_master')->where('veh_fac_id','=',$id)
							->update([
									'veh_fac_type'=>$request->veh_fac_type,
									'veh_fac_desc'=>$request->veh_fac_desc,
									'veh_fac_data_type'=>$request->veh_fac_data_type,
									'veh_fac_is_required'=>$request->veh_fac_is_required,
									]);
			return redirect()->route('vehiclesfacility.index')->with('success', 'Vehicle facility master has been updated successfully!'); 
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\vehiclefacilitymaster  $vehiclefacilitymaster
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(vehiclefacilitymaster $vehiclefacilitymaster)
	{
		//
	}
}

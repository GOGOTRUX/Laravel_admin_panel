<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Config;
use DB;
use Response;

class FeedbackController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		if (! Gate::allows('feedback_manage'))
		{
			return abort(401);
		}
		else{
			//$feedback=Feedback::all();
			$feedback=DB::table('ggt_driver_ride_feedback')
			->join('ggt_user','ggt_user.user_id','=','ggt_driver_ride_feedback.user_id')
			->join('ggt_drivers','ggt_drivers.driver_id','=','ggt_driver_ride_feedback.op_driver_id')->
			select('ggt_driver_ride_feedback.*','ggt_user.user_first_name','ggt_user.user_last_name','ggt_drivers.driver_last_name','ggt_drivers.driver_first_name')->get()->toArray();
			//echo dd($feedback);
			return view('admin.feedback.index', compact('feedback'));
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Feedback  $feedback
	 * @return \Illuminate\Http\Response
	 */
	public function show(Feedback $feedback)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Feedback  $feedback
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Feedback $feedback)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Feedback  $feedback
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Feedback $feedback)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Feedback  $feedback
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Feedback $feedback)
	{
		//
	}
}

<style type="text/css">
	.autocomplete-suggestions{
         background-color: white;
         cursor: pointer;
    }
</style>
@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
<h1>Manage Subscription Plans</h1>
<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">SubScription Plan</li>
</ol>
@endsection
<!-- Main Content -->
@section('content')
<div class="row">
	@if(session('success'))
	<div class="alert alert-success" id="message">
		{{ session('success') }}
	</div>
	@endif
	<div class="container-fluid">
		<div class="box">
			<div class="box-body">
				<!-- @can('subscription_create')
				<p><a href="{{ route('subscriptions.create') }}" class="btn btn-xs btn-default" id="createFrom">Create New Subscription</a></p>
				@endcan -->
				<!--New subscription screen as per client design-->
				<div class="panel-body p-0">
					<div class="view-op">
						<div class="row">
							<div class="col-sm-12 form-group section-title"><b>Subscription Plan Details</b></div>
							<div class="section p-l-5 p-b-0" id="toggleAddPlanForm">
								@if(Session::has('subscription_id'))
									<form method="POST" id="addSubscriptionPlanForm" action="{{ route('subscriptions.update', [ Session::get('subscription_id') ]) }}" enctype="multipart/form-data">
									@method('PUT')
								@else
									<form method="POST" id="addSubscriptionPlanForm" action="{{ route('subscriptions.store') }}" enctype="multipart/form-data">
								@endif
								<!-- <form method="POST" id="addSubscriptionPlanForm" action="{{ route('subscriptions.store') }}" enctype="multipart/form-data"> -->
									<!-- <form id="addSubscriptionPlanForm" enctype="multipart/form-data"> -->
									@csrf
									<div>
										<table class="table sub-plan">
											<thead>
												<tr>
													@role('Super Admin')
													<th class="sub-col">
														<label class="control-label">Free Plan</label>
													</th>
													@endrole
													<th class="sub-col-3">
														<input type="hidden" id="subscription_id" name="subscription_id" value="{{ Session::has('subscription_id') ? Session::get('subscription_id') : '' }}" required>
														<!-- <input type="hidden" id="subscription_type_id" name="subscription_type_id" value=""> -->
														<label class="control-label">{{ __('Sub. Scheme Name*') }}</label>
														<?php // dd(Session::get('subscription_type_name')); ?>
													</th>
													<th class="sub-col-2">
														<label class="control-label">{{ __('Sub. Amount* (Rs.)') }}</label>
													</th>
													<th class="sub-col-4">
														<label class="control-label">{{ __('Expected (Value/No)*') }}</label>
														<div class="row">
															<div class="exp-check">
																<input id="subscription_validity_type" class="subscription_validity_type" type="radio" name="subscription_validity_type" value="no" {{Session::has('subscription_validity_type') && Session::get('subscription_validity_type')=='by value' ? 'checked' : '' }}><span>Business (Rs.)</span>
															</div>
															<div class="exp-check">
																<input id="subscription_validity_type" class="subscription_validity_type" type="radio" name="subscription_validity_type" value="yes" {{Session::has('subscription_validity_type') && Session::get('subscription_validity_type')=='by enquiry' ? 'checked' : '' }}><span>Enquiries (Nos)</span>
															</div>
															<p class="help-block"></p>
															@if($errors->has('subscription_validity_type') || $errors->has('subscription_validity_type'))
															<p class="help-block text-red">
																{{ $errors->first('subscription_validity_type') }}
															</p>
															@endif
														</div>
													</th>
													<th class="sub-col-2">
														<label class="control-label">{{ __('Valid For*') }}</label>
													</th>
													<th class="sub-col-1">
														<label class="control-label">{{ __('Validity*') }}<br>(Days)</label>
													</th>
													<th class="sub-col-4">
														<label class="control-label col-sm-12 p-l-5">{{ __('Availability*') }}</label>
														<span class="pull-left m-l-20">From</span>
														<span class="m-l-20">To</span>
													</th>
													<!-- <th class="sub-col-5">
														<label for="is_active" class="control-label">Status*</label>
													</th> -->
													<th class="sub-col text-center">
														<label class="control-label">Upload Logo</label>
													</th>
													@can('subscription_final_approval')
													<th class="sub-col-1 text-center">
														<label class="control-label">Approve</label>
													</th>
													<th></th>
													@endcan
												</tr>
											</thead>
											<tbody>
												<tr>
													@role('Super Admin')
													<td class="sub-col">
														<input type="checkbox" checked data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" id="is_free_trial" name="is_free_trial">
														<div id="is-trial-console-event"></div>
														<input type="hidden" name="is_free_trial_hidden" id="is_free_trial_hidden" value="{{ Session::has('is_free_trial') ? Session:: get('is_free_trial') : old('is_free_trial') }}">
													</td>
													@endrole
													<td class="sub-col-3">
														<input id="subscription_type_name" type="text" class="form-control" name="subscription_type_name" value="{{ Session::has('subscription_type_name') ? Session:: get('subscription_type_name') : old('subscription_type_name') }}" onkeydown = "return validateAutoSuggest(this.id);" onkeyup="this.value = this.value.toUpperCase()">

														<p class="help-block"></p>
														@if($errors->has('subscription_type_name'))
														<p class="help-block text-red">
															{{ $errors->first('subscription_type_name') }}
														</p>
														@endif
													</td>
													<td class="sub-col-2">
														<input id="subscription_amount" type="text" class="form-control" name="subscription_amount" value="{{ Session::has('subscription_amount') ? Session:: get('subscription_amount') : old('subscription_amount') }}">
														<p class="help-block"></p>
														@if($errors->has('subscription_amount'))
														<p class="help-block text-red">
															{{ $errors->first('subscription_amount') }}
														</p>
														@endif
													</td>
													<td class="sub-col-4">
														<div id="business_rs_div">
															<!-- <input id="subscription_business_rs" type="text" class="form-control" name="subscription_business_rs"> -->
															<input id="subscription_business_rs" type="text" class="form-control" name="subscription_business_rs" value="{{ Session::has('subscription_business_rs') ? Session:: get('subscription_business_rs') : old('subscription_business_rs') }}">
															<label class="control-label m-t-5">{{ __('Business (Rs.)') }}</label>
														</div>
														<div id="enquiries_no" style="display: none;">
																<input id="subscription_expected_enquiries" type="text" class="form-control" name="subscription_expected_enquiries" value="{{ Session::has('subscription_expected_enquiries') ? Session:: get('subscription_expected_enquiries') : old('subscription_expected_enquiries') }}">
																<label class="control-label m-t-5">{{ __('Enquiries (Nos)') }}</label>		
														</div>
														<label id="subscription_validity_type-error" class="error" for="subscription_validity_type"></label>
														<div class="row">
															<!-- <div class="" id="business_rs" style="display: none;"> -->
																<!-- <input id="subscription_business_rs" type="text" class="form-control" name="subscription_business_rs" value="{{ Session::has('subscription_business_rs') ? Session:: get('subscription_business_rs') : old('subscription_business_rs') }}"> -->

																<!-- <label class="control-label">{{ __('Business (Rs.)') }}</label> -->
															<!-- </div> -->
															<!-- <div class="" id="enquiries_no" style="display: none;"> -->
																<!-- <input id="subscription_expected_enquiries" type="text" class="form-control" name="subscription_expected_enquiries" value="{{ Session::has('subscription_expected_enquiries') ? Session:: get('subscription_expected_enquiries') : old('subscription_expected_enquiries') }}"> -->
																
																<!-- <label class="control-label">{{ __('Enquiries (Nos)') }}</label> -->
															<!-- </div> -->
															<p class="help-block"></p>
															@if($errors->has('subscription_business_rs'))
															<p class="help-block text-red">
																{{ $errors->first('subscription_business_rs') }}
															</p>
															@endif
															<p class="help-block"></p>
															@if($errors->has('subscription_expected_enquiries'))
															<p class="help-block text-red">
																{{ $errors->first('subscription_expected_enquiries') }}
															</p>
															@endif
														</div>
														<label id="subscription_business_rs-error" class="error" for="subscription_business_rs"></label>
													</td>
													<td class="sub-col-2">
														<div class="row">
															<div class="sub-box m-b-10">
																<div class="valid-check" id="sub_veh_wheel_3">
																	<input id="subscription_veh_wheel_type" type="radio" name="subscription_veh_wheel_type" value="3" {{Session::has('subscription_veh_wheel_type') && Session::get('subscription_veh_wheel_type')==3 ? 'checked' : '' }}><span>3W</span>
																</div>
																<div class="valid-check" id="sub_veh_wheel_4">
																	<input id="subscription_veh_wheel_type" type="radio" name="subscription_veh_wheel_type" value="4" {{Session::has('subscription_veh_wheel_type') && Session::get('subscription_veh_wheel_type')==4 ? 'checked' : '' }}><span>4W</span>
																</div>
															</div>
															<div class="sub-box">
																<div class="valid-check" id="sub_veh_wheel_0">
																	<input id="subscription_veh_wheel_type" type="radio" name="subscription_veh_wheel_type" value="0" {{Session::has('subscription_veh_wheel_type') && Session::get('subscription_veh_wheel_type')==0 ? 'checked' : '' }}><span>MV</span>
																</div>
																<div class="valid-check" id="sub_veh_wheel_1">
																	<div class="valid-check">
																		<input id="subscription_veh_wheel_type" type="radio" name="subscription_veh_wheel_type" value="1" {{Session::has('subscription_veh_wheel_type') && Session::get('subscription_veh_wheel_type')==1 ? 'checked' : '' }}><span>All</span>
																	</div>
																<!-- <input id="" type="radio" name="" value="0"><span>All</span> -->
																</div>
															</div>
															<p class="help-block"></p>
															@if($errors->has('subscription_veh_wheel_type'))
															<p class="help-block text-red">
																{{ $errors->first('subscription_veh_wheel_type') }}
															</p>
															@endif
														</div>
														<label id="subscription_veh_wheel_type-error" class="error" for="subscription_veh_wheel_type"></label>
														<div class="row" style="display: none;" id="multi_vehicle">
															<div>
																<label class="control-label">{{ __('No. of Vehicle allowed*') }}</label>
																<input id="sub_multi_wheel_type" type="text" class="form-control" name="sub_multi_wheel_type" value="{{ Session::has('subscription_no_of_veh_allowed') ? Session::get('subscription_no_of_veh_allowed') : old('sub_multi_wheel_type') }}">
															</div>
														</div>
													</td>
													<td class="sub-col-1">
														<input id="subscription_validity_days" type="text" class="form-control" name="subscription_validity_days" value="{{ Session::has('subscription_validity_days') ? Session:: get('subscription_validity_days') : old('subscription_validity_days') }}">
														<p class="help-block"></p>
														@if($errors->has('subscription_validity_days'))
														<p class="help-block text-red">
															{{ $errors->first('subscription_validity_days') }}
														</p>
														@endif
													</td>
													<td class="sub-col-4">
														<div class="date-group p-r-5">
															<input id="subscription_validity_from" type="text" placeholder="dd/mm/yy" class="form-control date-picker" name="subscription_validity_from" value="{{ Session::has('subscription_validity_from') ? Session:: get('subscription_validity_from') : old('subscription_validity_from') }}" autocomplete="off">
															<p class="help-block"></p>
															@if($errors->has('subscription_validity_from'))
															<p class="help-block text-red">
																{{ $errors->first('subscription_validity_from') }}
															</p>
															@endif
														</div>
														<div class="date-group p-l-5">
															<input id="subscription_validity_to" type="text" placeholder="dd/mm/yy" class="form-control date-picker" name="subscription_validity_to" value="{{ Session::has('subscription_validity_to') ? Session:: get('subscription_validity_to') : old('subscription_validity_to') }}" autocomplete="off">
															
															<p class="help-block"></p>
															@if($errors->has('subscription_validity_to'))
															<p class="help-block text-red">
																{{ $errors->first('subscription_validity_to') }}
															</p>
															@endif
														</div>
													</td>
													<!-- <td class="sub-col-5">
														<select id="is_active" class="form-control" name="is_active" autofocus value="{{ old('is_active') }}">
															<option value="">Select Status</option>
															<option value="1" {{Session::has('is_active') && Session::get('is_active')==1 ? 'selected' : '' }}>Active</option>
															<option value="0" {{Session::has('is_active') && Session::get('is_active')==0 ? 'selected' : '' }}>Inactive</option>
														</select>
														<p class="help-block"></p>
														@if($errors->has('is_active'))
														<p class="help-block text-red">
															{{ $errors->first('is_active') }}
														</p>
														@endif
													</td> -->
													<td class="sub-col text-center">
														<div class="class="col-xs-6 p-0 form-group" id="upload_button">
															<label>
																<input id="subscription_type_image" type="file" class="form-control p-0" name="subscription_type_image" value="" onchange="preview_scheme_logo();">
																<span class="fa fa-camera"></span>
															</label>
														</div>
														<div id="logo_image_div" style="display: none;">
															<label for="view_veh_images" class="control-label">{{ __('View') }}</label><br>
															<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_logo" ></i>
														</div>
														<!-- style="display: none" -->
														@if(Session::has('subscription_type_image'))
									                    <div class="col-xs-6 p-l-0 p-r-5" id="edit_logo_img_div">
									                        <img src = "data:image/png;base64,{{ Session::get('subscription_type_image ') }}" width="100%" height="auto">
									                    </div>
									                    @endif
													</td>
													<td class="sub-col-1 text-center">
														@can('subscription_final_approval')
														<?php $is_set=0; ?>
															@if(Session::has('is_approved') && Session::get('is_approved'))
															<!-- disable if approved already -->
																<input type="checkbox" checked data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" id="is_approved" name="is_approved">
																<div id="console-event"></div>
															@else
															 <input type="checkbox" checked data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini" id="is_approved" name="is_approved">
																<div id="console-event"></div>
															@endif
														@endcan
													</td>
													<td>
														<button type="submit" class="btn btn-xs btn-success">{{Session::has('subscription_id') && Session::get('subscription_id') ? 'Update' : 'Save' }}</button>
														<!-- <button type="submit" class="btn btn-xs btn-success">{{ __('Save') }}</button> -->
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- <div>
												<button class="btn btn-xs btn-default"><i class="fa fa-plus"></i></button>
									</div>			 -->
								</form>
							</div>
						</div>
					</div>
				</div>
				<!--End new subscription screen -->
				<div class="table-responsive">
					<table id="subplan" class="table table-bordered table-striped {{ count($subplan) > 0 ? 'datatable' : '' }}" data-page-length="25">
						<thead>
							<tr>
								<th class="sub-col text-center">#</th>
								<th class="sub-col-3">Scheme Name</th>
								<th class="sub-col-1">Amount (Rs)</th>
								<th class="sub-col-1">Expected<br>Business (RS)</th>
								<th class="sub-col-1">Expected<br>Enquiries (Nos)</th>
								<th class="sub-col-5">Valid for<br> 3W/4W/MT</th>
								<th class="sub-col">Validity (Days)</th>
								<th class="sub-col-2">Availability <br>
									<span class="pull-left">From</span>
									<span class="pull-right">To</span>
								</th>
								<th class="sub-col-5">Created By</th>
								<th class="sub-col-2">Plan Status</th>
								<th class="sub-col-5">Action</th>
						 		<th class="sub-col-1">Approval</th>
							</tr>
						</thead>
						<tbody>
							@php($counter=1)
							@if(count($subplan) > 0)
							@foreach ($subplan as $subplan)
								<tr data-entry-id="{{ $subplan['subscription_id'] }}">
										<td class="sub-col text-center">
											{{$counter}}
											<?php $counter=$counter+1; ?>
											<!-- <input type="checkbox" id="subplanid" name="" value="" /> -->
											<!-- <input type="checkbox" id="subplanid" name="subplanid[]" value="{{ $subplan['subscription_id'] }}" /> -->
										</td>
										<td class="sub-col-3">{{ $subplan['subscription_type_name'] }} </td>
										<td class="sub-col-1 text-center">{{ $subplan['subscription_amount'] }}</td>
										<td class="sub-col-1 text-center">{{ $subplan['subscription_business_rs'] == null ? '-' : $subplan['subscription_business_rs'] }}</td>
										<td class="sub-col-1 text-center">{{ $subplan['subscription_expected_enquiries'] == null ? '-' : $subplan['subscription_expected_enquiries'] }}</td>
										<td class="sub-col-5 text-center">
											@if($subplan['subscription_veh_wheel_type']==0)
												Multivehicle ( {{ $subplan['subscription_no_of_veh_allowed'] }} )
											@elseif($subplan['subscription_veh_wheel_type']==1)
												All 
											@else
												{{ $subplan['subscription_veh_wheel_type'] }} W
											@endif
										</td>
										<td class="sub-col text-center">{{ $subplan['subscription_validity_days'] }}</td>
										<td class="sub-col-2">
											{{ $subplan['subscription_validity_from'] ? $subplan['subscription_validity_from'] : 'N. A.' }} - <br>
											{{ $subplan['subscription_validity_to'] ? $subplan['subscription_validity_to'] : 'N. A.' }}
										</td>
										<td class="sub-col-5"> {{ $subplan['subscription_plan_created_name'] }} </td>
										<td class="sub-col-2 smile">
											<span class="{{ $subplan['is_active']==1 ? 'text-info' : 'text-red' }}">{{ $subplan['is_active']==1 ? 'Active' : 'Inactive' }} </span>
											@if($subplan['subscription_expired']==0)
												<span><i class="fa fa-frown-o" data-toggle="tooltip" data-placement="top" title="plan expired" aria-hidden="true"></i></span>
											@elseif($subplan['subscription_expired']==1)
												<span><i class="fa fa-smile-o green" data-toggle="tooltip" data-placement="top" title="plan running" aria-hidden="true"></i></span>
											@elseif($subplan['subscription_expired']==3)
												<span><i class="fa fa-smile-o blue" data-toggle="tooltip" data-placement="top" title="plan has no expiry" aria-hidden="true"></i></span>
											@else
												<span><i class="fa fa-smile-o yellow" data-toggle="tooltip" data-placement="top" title="plan will active in {{ $subplan['subscription_active_indays'] }} days." aria-hidden="true"></i></span>
											@endif
											<span><i class="fa fa-user-plus" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Active User : {{$subplan['is_active']}}"></i></span>
										</td>
										<td class="sub-col-5 text-center">
											<a  href="{{ route('subscriptions.show',[$subplan['subscription_id']]) }}">
												<button class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></button>
											</a>
											@can('subscription_edit')
											<?php //@if($subplan['subscription_expired']==0 && $subplan['is_sent_for_approval']==0) ?>
											        @if($subplan['subscription_expired']==0 || $subplan['is_sent_for_approval']==1 && $subplan['isSuperAdmin']==false)
											        @else
											                @if($subplan['is_approved']==1)

											                @else
											                    <a href="{{ route('subscriptions.edit',[$subplan['subscription_id']]) }}">
											                            <button class="btn btn-xs btn-success"><i class="fa fa-edit"></i></button>
											                    </a>
											                @endif
											        @endif
											@endcan
											
											<!-- @can('subscription_edit')
											 <?php //@if($subplan['subscription_expired']==0 && $subplan['is_sent_for_approval']==0) ?>
										 		@if($subplan['subscription_expired']==0 || $subplan['is_sent_for_approval']!=1 || $subplan['subscription_approve_permission']==false)
												@else
													<a href="{{ route('subscriptions.edit',[$subplan['subscription_id']]) }}">
														<button class="btn btn-xs btn-success"><i class="fa fa-edit"></i></button>
													</a>
												@endif
											@endcan -->

											@can('subscription_delete')
												@if($subplan['subplan_purchase_count']== 0 && $subplan['subscription_expired']==0 || $subplan['is_sent_for_approval']==0 || $subplan['is_active']==0)
												<a href="#" onclick="deleteSubScription('{{ $subplan['subscription_id'] }}')">
													<button class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button>
												</a>
												@endif
											@endcan

											<!-- @if($counter==2)
													<i class="fa fa-unlock" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="verified by admin"></i>
												@else
											<i class="fa fa-lock" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="click to send for admin verification"></i></a>
											@endif -->
											<!-- <form method="POST" action="{{ route('subscriptions.destroy',[$subplan['subscription_id']]) }}" accept-charset="UTF-8" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
															@method('DELETE')
															@csrf
															<input class="btn btn-xs btn-danger" type="submit" value="Delete">
											</form>                                     -->
										</td>
										<td class="sub-col-1"> 
											@if(auth()->user()->can('subscription_final_approval'))
												
											@else
												<!-- show ? to 'created user' : else 'hide'-->
												@if(auth()->user()->id == $subplan['subscription_plan_created_by'])
													<a class="next-arrow right">
														<div id="arrow-wrapper">
															@if($subplan['is_sent_for_approval']==0 )
															<button class="aprv-btn" href="#" data-toggle="tooltip" data-placement="top" title="click to send for verification" onclick="sendtoVerification('{{ $subplan['subscription_id'] }}')"> 
																<div id="arrow-stem" class="not-aprv-steam"></div>
																<div id="arrow-head" class="not-aprv-head"></div>
															</button>
															@elseif($subplan['is_sent_for_approval']==1 && $subplan['is_approved']==0 )
																<div data-toggle="tooltip" data-placement="top" title="waiting! for approval">
																	<div id="arrow-stem"></div>
																	<div id="arrow-head"></div>
																</div>
															@elseif($subplan['is_approved']==1 )
																<i class="fa fa-check-circle" style="font-size:36px" data-toggle="tooltip" data-placement="top" title="plan approved"></i>
															@endif	
														</div>
													</a>
												@endif
											@endif
											<!-- only user has permission to verify subplan -->
											@can('subscription_final_approval')
										    	@if($subplan['is_sent_for_approval']==1)
										    		@if($subplan['is_approved'])	
														<a href="#" data-toggle="tooltip" data-placement="top" title="plan approved">
															<i class="fa fa-check-square-o aprv-fa" aria-hidden="true"></i>
														</a>
													@else
														<a href="#" onclick="verifyByAdmin('{{ $subplan['subscription_id'] }}')" data-toggle="tooltip" data-placement="top" title="click to approve plan">
															<i class="fa fa-share-square-o fa-aprv" aria-hidden="true"></i>
														</a>
													@endif
												@elseif($subplan['is_sent_for_approval']==0 && auth()->user()->id == $subplan['subscription_plan_created_by'])
													<a href="#" onclick="verifyByAdmin('{{ $subplan['subscription_id'] }}')" data-toggle="tooltip" data-placement="top" title="click to approve plan">
														<i class="fa fa-share-square-o fa-aprv" aria-hidden="true"></i>
													</a>
												@endif
											@endcan
											<!-- verify subplan end -->
										</td>
										<!-- <td>
											<a class="next-arrow">
												<div id="arrow-wrapper">
													@if($subplan['is_active'] ==0 && $subplan['is_sent_for_approval']==0 )
													<a href="#" onclick="sendtoVerification('{{ $subplan['subscription_id'] }}')"> 
														<div id="arrow-stem" class="not-aprv-steam"></div>
														<div id="arrow-head" class="not-aprv-head"></div>
													</a>
													@else
														<div id="arrow-stem"></div>
														<div id="arrow-head"></div>
													@endif
												</div>
											</a>
										</td> -->
								</tr>
							@endforeach
							@else
							<tr>
								<td colspan="12">No Subscription Plan Available</td>
							</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Driver photo modal -->
<div class="modal fade" id="scheme_logo">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Subscription Logo </h4>
			</div>
			<div class="modal-body">
				<div id="show_scheme_logo"></div>
			</div>
		</div>
	</div>
</div>
<!-- End Driver photo modal -->
<!-- view subscription modal -->
<div class="modal fade" id="view_logo">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">View Logo</h4>
			</div>
			<div class="modal-body" >
				<div id="view_logo_img">
					@if(Session::has('subscription_type_image'))
                    <div class="col-xs-3" id="edit_logo_div" style="display: none">
                        <img src = "data:image/png;base64,{{ Session::get('subscription_type_image ') }}" width="80px" height="80px">
                    </div>
                    @endif
				</div>
			</div>
		</div>
	</div>
</div>
<!-- view subscription modal end -->
@endsection
<!-- JS scripts for this page only -->
@section('javascript')
<!-- page script -->
<script language="javascript" type="text/javascript">

$(document).ready(function () {
	
	var get_approved_value = "{{Session::has('is_approved')}}";
	if(get_approved_value){
		var is_approved = "{{Session::get('is_approved')}}";
		if(is_approved==1){
			$('#is_approved').bootstrapToggle('on');
		}else{
			$('#is_approved').bootstrapToggle('off');	
		}	
	}

	var get_free_trial_value = "{{Session::has('is_free_trial')}}";
	if(get_free_trial_value){
		var is_free_trial = "{{Session::get('is_free_trial')}}";
		if(is_free_trial==1){
			$('#is_free_trial').bootstrapToggle('on');
			$('#is_free_trial').bootstrapToggle('disable');

			$("#sub_veh_wheel_3").hide();
        	$("#sub_veh_wheel_4").hide();
        	$("#sub_veh_wheel_0").hide();
        	$("#subscription_validity_days").prop('disabled', true);
    		$("#subscription_validity_from").prop('disabled', true);
    		$("#subscription_validity_to").prop('disabled', true);

		}else{
			$('#is_free_trial').bootstrapToggle('off');	
			$('#is_free_trial').bootstrapToggle('disable');
		}	
	}

	var free_plan_exist = <?php echo $free_plan_exist; ?>;
	if(free_plan_exist){
		$('#is_free_trial').bootstrapToggle('off');
		$('#is_free_trial').bootstrapToggle('disable');
	}else{
		$('#is_free_trial').bootstrapToggle('off');	
		$('#is_free_trial_hidden').val(0);		
	}
	
	//on update preserve old db value
	$('#is_free_trial').change(function() {
		$('#is_free_trial_hidden').val($(this).prop('checked'));
        // $('#is-trial-console-event').html('Toggle: ' + $(this).prop('checked'))
        if($(this).prop('checked')==true){
        	// $("input[name='subscription_veh_wheel_type'][value='3']").prop('disabled',true);
        	$("#sub_veh_wheel_3").hide();
        	$("#sub_veh_wheel_4").hide();
        	$("#sub_veh_wheel_0").hide();
        	$("#subscription_validity_days").prop('disabled', true);
    		$("#subscription_validity_from").prop('disabled', true);
    		$("#subscription_validity_to").prop('disabled', true);
        }else{
        	$("#sub_veh_wheel_3").show();
        	$("#sub_veh_wheel_4").show();
        	$("#sub_veh_wheel_0").show();
        	$("#subscription_validity_from").prop('disabled', false);
        	$("#subscription_validity_to").prop('disabled', false);
        	$("#subscription_validity_days").prop('disabled', false);
        }
    })
		
	$("#subscription_validity_from").datepicker({
	    changeMonth: true, 
	    changeYear: true, 
	    dateFormat: 'yy-mm-dd',
	    minDate: 0, // 0 days offset = today
	    maxDate: '',
	    onSelect: function(dateText) {
	        $sD = new Date(dateText);
	        $("#subscription_validity_to").datepicker('option', 'minDate', dateText);
	    }
	});

	$('[data-toggle="tooltip"]').tooltip();

	$('#message').fadeIn('slow', function()
	{
		$('#message').delay(1000).fadeOut(); 
	});

	// $('body').on('click','#createFrom' ,function(){
	// 	var f = document.getElementById("toggleAddPlanForm");
	// 	 if (f.style.display === "none") {
	// 	    f.style.display = "block";
	// 	  } else {
	// 	    f.style.display = "none";
	// 	  }
	// });

	$("#subscriptionplanselect").click(function(){
		var selectid=new Array();
		$('#subplanid:checked').each(function() {
			selectid.push(this.value);
		});
		$.ajax({
			url :"{{ route('subscriptiontypeselectdelete') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"selectids": selectid
			},    
			success : function(data){
				if(data==1)
				{
					alert("Multiple Subscriptions deleted successfully");
				}
			}
		});
	});

	$("#subscriptiontypesselect").click(function(){
		var selectid=new Array();
		$('#subtypeid:checked').each(function() {
			selectid.push(this.value);
		});
		if(selectid.length > 1){
			$.ajax({
				url :"{{ route('subscriptiontypeselectdelete') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"selectid": selectid
				},
				success : function(data){
					if(data==1)
					{
						alert("Multiple Operator deleted successfully");
					}
				},
			});
		}
		else{
			alert("please select atleast two");
		}
	});

	$('.datepicker').datepicker({
		todayBtn: "linked",
		clearBtn: true,
		dateFormat: 'dd-mm-yy'
	});
});


	$("#addSubscriptionPlanForm").validate({
		rules: {
			subscription_type_name: {
				required: true,
				remote: {
						headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
						type: 'post',
						url: '/CheckSubscriptionPlan',
						data: {
							'subscription_id': function () { return $('#subscription_id').val(); },
							'subscription_type_name': function () { return $('#subscription_type_name').val(); },
							'subscription_veh_wheel_type': function () { 
								if ($("#subscription_veh_wheel_type").is(':checked')) {
									return $("input[name='subscription_veh_wheel_type']:checked").val()
									// return $("#subscription_veh_wheel_type").val(); 
								}else{
									return null;
								}
							},
						},
						dataType: 'json'
				},
			},
			subscription_amount: {
				required: true,
				max:99999,
			},
			subscription_validity_type: {
				required : true,
			},
			subscription_business_rs :{
				required : {
					depends : function(){
						$("#subscription_validity_type").val()=='no';
						return true;
					}
				},
				digits: true,
				max:99999,
			},
			subscription_expected_enquiries: {
				required : {
					depends : function(){
						$("#subscription_validity_type").val()=='yes';
						return true;
					}
				},
				digits: true,
				max:99999,
			},
			subscription_veh_wheel_type: {
				required : true,
				remote: {
					headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
					type: 'post',
					url: '/CheckSubscriptionPlan',
					data: {
						'subscription_id': function () { return $('#subscription_id').val(); },
						'subscription_type_name': function () { return $('#subscription_type_name').val(); },
						'subscription_veh_wheel_type': function () { 
							return $("input[name='subscription_veh_wheel_type']:checked").val()
						},
					},
					dataType: 'json'
				},
			},
			sub_multi_wheel_type : {
				required : {
					depends : function(){
						$("#subscription_veh_wheel_type").val()=='0';
						return true;
					}
				},
				digits: true,
				min:1,
				max:9999,
			},
			subscription_validity_days : {
				required : true,
				min:1,
			},
			subscription_validity_from : {
				required : true,
			},
			subscription_validity_to :{
				required : true,
			},
			is_free_trial_hidden:{
				remote: {
						headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
						type: 'post',
						url: '/CheckSubscriptionPlan',
						data: {
							'subscription_id': function () { return $('#subscription_id').val(); },
							'subscription_type_name': function () { return $('#subscription_type_name').val(); },
							'subscription_veh_wheel_type': function () { 
								if ($("#subscription_veh_wheel_type").is(':checked')) {
									return $("input[name='subscription_veh_wheel_type']:checked").val()
									// return $("#subscription_veh_wheel_type").val(); 
								}else{
									return null;
								}
							},
						},
						dataType: 'json'
				},
			}
			// is_active: {
			// 	required : true,
			// }
		},  
		messages: {
			subscription_type_name: {
				required: "Please enter scheme name",
				remote: "Subscription plan already activate.",
			},
			subscription_amount: {
				required: "Please enter amount",
			},
			subscription_validity_type: {
				required: "Please enter expected value",
			},
			subscription_business_rs :{
				required : "Please enter business Rs.",
				digits:"Enter only numbers",
			},
			subscription_expected_enquiries : {
				required : "Please enter business enquiries",
				digits:"Enter only numbers"
			},
			subscription_veh_wheel_type : {
				required : "Please select wheel type",
				remote: "Subscription plan already activate.",
			},
			sub_multi_wheel_type : {
				required : "Specify number of wheel",
			},
			subscription_validity_days : {
				required : "Please enter number of days",
				min: "Days greater than zero"
			},
			subscription_validity_from : {
				required : "Please select from date",
			},
			subscription_validity_to :{
				required : "Please select to date",
			},
			is_free_trial_hidden :{
				remote: "Free plan exist",
			}
			// is_active: {
			// 	required : "Please select plan status",
			// }
		},
		invalidHandler: function(event, validator) {
			// console.log(event);
			// console.log(validator);
		},
	});

	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();  

		$('#subscription_type_name').autocomplete({
            serviceUrl: "/autoCompleteSubTypeName",
            onSelect: function (suggestion) {
                validateAutoSuggest('subscription_type_name');
            },
            minLength: 50,
        });

		var has_no_of_veh_allowed = "{{Session::has('subscription_no_of_veh_allowed')}}";
		if(has_no_of_veh_allowed){
			var subscription_no_of_veh_allowed = "{{Session::get('subscription_no_of_veh_allowed')}}";
			if(subscription_no_of_veh_allowed >0){
				$('#multi_vehicle').show();
			}else{
				$('#multi_vehicle').hide();
			}
		}else{
			$('#multi_vehicle').hide();
		}

		$('body').on('click','#subscription_veh_wheel_type' ,function(){
			// var subscription_wheel_type = $(this).val();
			var subscription_wheel_type = $("input[name='subscription_veh_wheel_type']:checked").val();
			if($(this).val() == 0){
				$('#multi_vehicle').show();
			}else{
				$('#multi_vehicle').hide();
				$('#sub_multi_wheel_type').val('');
			}
		});

		$('body').on('click','.subscription_validity_type' ,function()
		{
			var subscription_validity_type = $(this).val();
			if(subscription_validity_type == 'no'){
				// $('#business_rs').show();
				$('#enquiries_no').hide();
				$('#subscription_expected_enquiries').val('');
				$('#subscription_business_rs').show();
				$('#subscription_expected_enquiries').hide();
				$('#business_rs_div').show();
			}else if(subscription_validity_type == 'yes'){
				$('#enquiries_no').show();
				// $('#business_rs').hide();
				$('#subscription_business_rs').hide();
				$('#subscription_expected_enquiries').show();
				$('#subscription_business_rs').val('');
				$('#business_rs_div').hide();

			}else{
				$('#enquiries_no').hide();
				$('#business_rs').hide();
			}
		});

		// server-side validation 
		var has_error_business_rs = '{{ $errors->has("subscription_business_rs") }}';
		var has_session_business_rs = "{{ Session::has('subscription_business_rs') ? Session:: get('subscription_business_rs') : old('subscription_business_rs') }}";
		
		if(has_error_business_rs!=1 && has_session_business_rs==''){
			$('#business_rs').hide();
			// $('#business_rs_div').hide();
		}else{
			$('#business_rs').show();
			// $('#business_rs_div').show();
		}

		var has_error_expected_en = '{{ $errors->has("subscription_expected_enquiries") }}';
		var has_session_expected_en = "{{ Session::has('subscription_expected_enquiries') ? Session:: get('subscription_expected_enquiries') : old('subscription_expected_enquiries') }}";

		if(has_error_expected_en!=1 && has_session_expected_en==''){
			$('#enquiries_no').hide();
		}else{
			$('#enquiries_no').show();
			$('#business_rs_div').hide();
		}
		// end server-side validation
	});

	function preview_scheme_logo() 
	{
		$('#view_logo_img').html('');
		var output = document.getElementById("subscription_type_image");
		var total_file = document.getElementById("subscription_type_image").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#edit_logo_img_div').hide()
			$('#logo_image_div').show()
			$('#view_logo_img').append("<img src='"+output.src+"' class='veh-image'>");
		}else{
			$('#edit_logo_img_div').show()
			$('#logo_image_div').hide()
			$('#view_logo_img').html('');
		}
	}

 	function validateAutoSuggest(id)
    {
        var subscription_type_name = $('#subscription_type_name').val();

        $.ajax({
            url: "/checkSubType",
            type: "GET",
            data:{ 
                headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
                query : subscription_type_name,
            },
            success: function(result){
            	console.log(result);
                if(result == 0){
                    // $('#'+id).css('border-color', '#e73d4a');
                   // $('#subscription_type_name').val();
                }else{
                    $('#'+id).css('border-color', '#93a1bb');
                    //$('#subscription_type_name').val(result);
                }
            }
        }); 
    }

    function deleteSubScription(id)
	{
		swal({
			title: 'Are you sure?',
			text: "It will permanently deleted !",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('delete/subpan') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					swal({title: "Deleted!", text: "Your plan has been deleted.", type: "success"}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
	}

    function sendtoVerification(id)
	{
		swal({
			title: 'Are you sure?',
			text: "It will send for verification!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, send it!'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('verify/subplan') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					swal({title: "Sent!", text: "Your plan has been send for approval", type: "success"}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
	}

	function verifyByAdmin(id)
	{
		swal({
			title: 'Are you sure?',
			text: "It will approve the plan",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, approve it!'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('approve/subplan') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					swal({title: "Great!", text: "Plan has been approved", type: "success"}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
	}
</script>
@endsection
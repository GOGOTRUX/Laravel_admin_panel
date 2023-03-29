<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style type="text/css">
	.help-block-message {
		 display: block;
		margin-top: 5px;
		margin-bottom: 10px;
		color: #dd4b39;
}
</style>

@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
	<h1>Create Subscription Plan</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Subscription Management</li>
	</ol>
@endsection
@section('content')
	<!--New subscription screen as per client -->
	<div class="panel-body p-0">
		<div class="view-op">
            <div class="row">
                <div class="col-sm-12 form-group section-title"><b>Subscription Plan Details</b></div>
                <div class="section p-l-5">
                	<form method="POST" id="addSubscriptionPlanForm" action="{{ route('subscriptions.store') }}" enctype="multipart/form-data">  
					@csrf
						<div>
							<table class="table sub-plan">
								<thead>
									<tr>
										<th></th>
										<th>
											<input type="hidden" id="subscription_id" name="subscription_id" value="" required>
			            					<label class="control-label">{{ __('Sub. Scheme Name') }}</label>	
										</th>
										<th>
											<label class="control-label">{{ __('Sub. Amount (Rs.)') }}</label>
										</th>
										<th>
											<label class="control-label">{{ __('Expected (Value/No)') }}</label>
										</th>
										<th>
											<label class="control-label">{{ __('Valid For') }}</label>
										</th>
										<th>
											<label class="control-label">{{ __('Validity (Days)') }}</label>
										</th>
										<th>
											<label class="control-label col-sm-12 p-l-5">{{ __('Availability') }}</label>
											<span class="pull-left">From</span>
											<span>To</span>
										</th>
										<th>
											<label for="is_active" class="control-label">Status *</label>
										</th>
										<th>
											<label class="control-label">Upload Logo</label>
										</th>
										<th>
											<label class="control-label">Approve</label>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<input id="" type="checkbox">
										</td>
										<td>
											<input id="subscription_type_name" type="text" class="form-control" name="subscription_type_name" value="">
											<!-- @if(empty($subscriptionSchemeList))
												<p class="text-red">Please add subscription scheme</p>
											@endif -->
											<p class="help-block"></p>
											@if($errors->has('subscription_type_name'))
												<p class="help-block text-red">
													{{ $errors->first('subscription_type_name') }}
												</p>
											@endif
										</td>
										<td>
											<input id="subscription_amount" type="text" class="form-control" name="subscription_amount" value="">
				            				<p class="help-block"></p>
											@if($errors->has('subscription_amount'))
												<p class="help-block text-red">
													{{ $errors->first('subscription_amount') }}
												</p>
											@endif
										</td>
										<td>
											<div class="row">
						            			<div class="exp-check">
						            				<input id="subscription_validity_type" class="subscription_validity_type" type="radio" name="subscription_validity_type" value="no"><span>Business (Rs.)</span>
						            			</div>
						            			<div class="exp-check">
						            				<input id="subscription_validity_type" class="subscription_validity_type" type="radio" name="subscription_validity_type" value="yes"><span>Enquiries (Nos)</span>
						            			</div>
						            			<p class="help-block"></p>
												@if($errors->has('subscription_validity_type') || $errors->has('subscription_validity_type'))
													<p class="help-block text-red">
														{{ $errors->first('subscription_validity_type') }}
													</p>
												@endif
					            			</div>
					            			<label id="subscription_validity_type-error" class="error m-t-20" for="subscription_validity_type"></label>
					            			<div class="row">
					            				<div class="m-t-20" id="business_rs" style="display: none;">
					            					<label class="control-label">{{ __('Business (Rs.)') }}</label>
						            				<input id="subscription_business_rs" type="text" class="form-control" name="subscription_business_rs" value="{{ old('subscription_business_rs') }}">
						            			</div>
						            			<div class="m-t-20" id="enquiries_no" style="display: none;">
						            				<label class="control-label">{{ __('Enquiries (Nos)') }}</label>
						            				<input id="subscription_expected_enquiries" type="text" class="form-control" name="subscription_expected_enquiries" value="{{ old('subscription_expected_enquiries') }}">
						            			</div>
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
										</td>
										<td>
											<div class="row">
						            			<div class="valid-check">
						            				<input id="subscription_veh_wheel_type" type="checkbox" name="subscription_veh_wheel_type" value="3" ><span>3W</span>
						            			</div>
						            			<div class="valid-check">
						            				<input id="subscription_veh_wheel_type" type="checkbox" name="subscription_veh_wheel_type" value="4"><span>4W</span>
						            			</div>
						            			<div class="valid-check">
						            				<input id="subscription_veh_wheel_type" type="checkbox" name="subscription_veh_wheel_type" value="MT"><span>MT</span>
						            			</div>
						            			<p class="help-block"></p>
												@if($errors->has('subscription_veh_wheel_type'))
													<p class="help-block text-red">
														{{ $errors->first('subscription_veh_wheel_type') }}
													</p>
												@endif
					            			</div>
					            			<label id="subscription_veh_wheel_type-error" class="error m-t-20" for="subscription_veh_wheel_type"></label>
					            		</td>
					            		<td>
					            			<input id="subscription_validity_days" type="text" class="form-control" name="subscription_validity_days" value="{{ old('subscription_validity_days') }}">
				            				<p class="help-block"></p>
											@if($errors->has('subscription_validity_days'))
												<p class="help-block text-red">
													{{ $errors->first('subscription_validity_days') }}
												</p>
											@endif
										</td>
										<td>
											<div class="date-group p-r-5">
				            					<input id="subscription_validity_from" type="text" placeholder="dd/mm/yy" class="form-control date-picker" name="subscription_validity_from" value="{{ old('subscription_validity_from') }}" autofocus  >												
												<p class="help-block"></p>
												@if($errors->has('subscription_validity_from'))
													<p class="help-block text-red">
														{{ $errors->first('subscription_validity_from') }}
													</p>
												@endif
											</div>
											<div class="date-group p-l-5">
				            					<input id="subscription_validity_to" type="text" placeholder="dd/mm/yy" class="form-control date-picker" name="subscription_validity_to" value="{{ old('subscription_validity_to') }}" autofocus  >
												
												<p class="help-block"></p>
												@if($errors->has('subscription_validity_to'))
													<p class="help-block text-red">
														{{ $errors->first('subscription_validity_to') }}
													</p>
												@endif
											</div>
										</td>
										<td>
											<select id="is_active" class="form-control" name="is_active" autofocus value="{{ old('is_active') }}">
												<option value="">Select Status</option>
												<option value="1">Active</option>
												<option value="0">Inactive</option>
											</select>
											<p class="help-block"></p>
											@if($errors->has('is_active'))
												<p class="help-block text-red">
													{{ $errors->first('is_active') }}
												</p>
											@endif
										</td>
										<td></td>
										<td>
											<a class="next-arrow">
												<div id="arrow-wrapper">
										    		<div id="arrow-stem"></div>
										    		<div id="arrow-head"></div>
										  		</div>
											</a>
										</td>
										<td>
											<button type="submit" class="btn btn-xs btn-success">{{ __('Save') }}</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div>
							<button class="btn btn-xs btn-default"><i class="fa fa-plus"></i></button>
						</div>			
					</form>
                </div>
            </div>
        </div>
    </div>
    <!--End new subscription screen -->
@endsection
<!-- JS scripts for this page only -->
@section('javascript')
<script type="text/javascript" language="javascript">
	$("#addSubscriptionPlanForm").validate({
		rules: {
			subscription_type_name: {
				required: true,
				remote: {
						headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
						type: 'post',
						url: '/CheckSubscriptionPlan',
						data: {
							'subscription_type_name': function () { return $('#subscription_type_name').val(); },
							'subscription_veh_wheel_type': function () { 
								if ($("#subscription_veh_wheel_type").is(':checked')) {
									return $("#subscription_veh_wheel_type").val(); 
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
			},
			subscription_expected_enquiries: {
				required : {
					depends : function(){
						$("#subscription_validity_type").val()=='yes';
						return true;
					}
				},
				digits: true,
			},
			subscription_veh_wheel_type: {
				required : true,
				remote: {
					headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
					type: 'post',
					url: '/CheckSubscriptionPlan',
					data: {
						'subscription_type_name': function () { return $('#subscription_type_name').val(); },
						'subscription_veh_wheel_type': function () { 
						 	return $("#subscription_veh_wheel_type").val()
						},
					},
					dataType: 'json'
				},
			},
			subscription_validity_days : {
				required : true,
			},
			subscription_validity_from : {
				required : true,
			},
			subscription_validity_to :{
				required : true,
			},
			is_active: {
				required : true,
			}
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
			subscription_validity_days : {
				required : "Please enter number of days",
			},
			subscription_validity_from : {
				required : "Please select from date",
			},
			subscription_validity_to :{
				required : "Please select to date",
			},
			is_active: {
				required : "Please select plan status",
			}
		},

	});

	$('.datepicker').datepicker({
		todayBtn: "linked",
		clearBtn: true,
		dateFormat: 'dd-mm-yy'
	});

	$(document).ready(function() {

		$('body').on('click','#subscription_veh_wheel_type' ,function(){
			var subscription_wheel_type = $(this).val();
			$('#subscription_veh_wheel_type').val(subscription_wheel_type);
		});

		$('body').on('click','.subscription_validity_type' ,function()
		{
			var subscription_validity_type = $(this).val();
			if(subscription_validity_type == 'no'){
				$('#business_rs').show();
				$('#enquiries_no').hide();
				$('#subscription_expected_enquiries').val('');
			}else if(subscription_validity_type == 'yes'){
				$('#enquiries_no').show();
				$('#business_rs').hide();
				$('#subscription_business_rs').val('');
			}else{
				$('#enquiries_no').hide();
				$('#business_rs').hide();
			}
		});

		// server-side validation 
		var has_error_business_rs = '{{ $errors->has("subscription_business_rs") }}';
		if(has_error_business_rs!=1){
			$('#business_rs').hide();
		}else{
			$('#business_rs').show();
		}
		var has_error_expected_en = '{{ $errors->has("subscription_expected_enquiries") }}';
		if(has_error_expected_en!=1){
			$('#enquiries_no').hide();
		}else{
			$('#enquiries_no').show();
		}
		// end server-side validation
	});
</script>
@endsection






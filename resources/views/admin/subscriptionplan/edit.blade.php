<style type="text/css">
	.help-block-message {
		display: block;
		margin-top: 5px;
		margin-bottom: 10px;
		color: #dd4b39;
	}
</style>
@extends('layouts.app')
	@section('content-header')
		<h1>Edit Subscription Plan
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Roles</li>
		</ol>
	@endsection

@section('content')
	<!--New subscription screen aa per client -->
	<div class="panel-body p-0">
		<div class="view-op">
            <div class="row">
                <div class="col-sm-12 form-group section-title"><b>Subscription Plan Details</b></div>
                <div class="section">
                	<form method="POST" id="editSubscriptionPlanForm" action="{{ route('subscriptions.update', [$subplan->subscription_id]) }}" enctype="multipart/form-data">  
                		@method('PUT')
						@csrf			
		                <div class="row">
		            		<div class="col-sm-6 form-group">
		            			<div class="f-half">
		            				<input type="hidden" id="subscription_id" name="subscription_id" value="{{ $subplan->subscription_id }}" required>
		            				<label class="control-label">{{ __('Subscription Scheme Name') }}</label>
		            				<select class="form-control" id="subscription_type_name" name="subscription_type_name">
		            					<!-- select2  data-placeholder="Select Scheme Name"-->
										<option value="">Select Scheme Name</option>
										@if(!empty($subscriptionSchemeList))
											@foreach($subscriptionSchemeList as $subscription)
											<option value="{{ $subscription['subscription_type_id'] }}"  {{ $subplan->subscription_type_id==$subscription['subscription_type_id'] ? 'selected' : '' }}>
											{{ $subscription['subscription_type_name'] }}
											</option>                     
											@endforeach
										@endif
									</select>
									@if(empty($subscriptionSchemeList))
										<p class="text-red">Please add subscription scheme</p>
									@endif
									<p class="help-block"></p>
									@if($errors->has('subscription_type_name'))
										<p class="help-block text-red">
											{{ $errors->first('subscription_type_name') }}
										</p>
									@endif
		            			</div>
		            			<div class="f-half">
		            				<label class="control-label">{{ __('Subscription Amount (Rs.)') }}</label>
		            				<input id="subscription_amount" type="text" class="form-control" name="subscription_amount" value="{{ $subplan->subscription_amount }}">
		            				<p class="help-block"></p>
									@if($errors->has('subscription_amount'))
										<p class="help-block text-red">
											{{ $errors->first('subscription_amount') }}
										</p>
									@endif
		            			</div>
		            		</div>
		            		<div class="col-sm-6 form-group">
		            			<div class="f-half">
			            			<label class="control-label">{{ __('Expected (Value/No)') }}</label>
			            			<div class="row">
				            			<div class="valid-check">
				            				<input id="subscription_validity_type" class="subscription_validity_type" type="radio" name="subscription_validity_type" value="no" {{ $subplan->subscription_validity_type=="by value" ? 'checked' : ''}}><span>Business (Rs.)</span>
				            			</div>
				            			<div class="valid-check">
				            				<input id="subscription_validity_type" class="subscription_validity_type" type="radio" name="subscription_validity_type" value="yes" {{ $subplan->subscription_validity_type=="by enquiry" ? 'checked' : ''}}><span>Enquiries (Nos)</span>
				            			</div>
				            			<p class="help-block"></p>
										@if($errors->has('subscription_validity_type') || $errors->has('subscription_validity_type'))
											<p class="help-block text-red">
												{{ $errors->first('subscription_validity_type') }}
											</p>
										@endif
			            			</div>
		            			</div>
		            			<div class="f-half">
		            				<div class="f-half" id="business_rs" style="display: none;">
		            					<label class="control-label">{{ __('Business (Rs.)') }}</label>
			            				<input id="subscription_business_rs" type="text" class="form-control" name="subscription_business_rs" value="{{ $subplan->subscription_business_rs }}">
			            			</div>
			            			<div class="f-half" id="enquiries_no" style="display: none;">
			            				<label class="control-label">{{ __('Enquiries (Nos)') }}</label>
			            				<input id="subscription_expected_enquiries" type="text" class="form-control" name="subscription_expected_enquiries" value="{{ $subplan->subscription_expected_enquiries }}">
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
		            		</div>
		                </div>
		                <div class="row">
		            		<div class="col-sm-6 form-group">
		            			<div class="f-half">
			            			<label class="control-label">{{ __('Valid For') }}</label>
			            			<div class="row">
				            			<div class="valid-check">
				            				<input id="subscription_veh_wheel_type" type="checkbox" name="subscription_veh_wheel_type" value="3" {{ $subplan->subscription_veh_wheel_type==3 ? "checked" : ''}}><span>3W</span>
				            			</div>
				            			<div class="valid-check">
				            				<input id="subscription_veh_wheel_type" type="checkbox" name="subscription_veh_wheel_type" value="4" {{ $subplan->subscription_veh_wheel_type==4 ? "checked" : ''}}><span>4W</span>
				            			</div>
				            			<div class="valid-check">
				            				<input id="subscription_veh_wheel_type" type="checkbox" name="subscription_veh_wheel_type" value="MT" {{ $subplan->subscription_veh_wheel_type=="MT" ? 'checked' : ''}}><span>MT</span>
				            			</div>
				            			<p class="help-block"></p>
										@if($errors->has('subscription_veh_wheel_type'))
											<p class="help-block text-red">
												{{ $errors->first('subscription_veh_wheel_type') }}
											</p>
										@endif
			            			</div>
		            			</div>
		            			<div class="f-half">
		            				<label class="control-label">{{ __('Validity (Days)') }}</label>
		            				<input id="subscription_validity_days" type="text" class="form-control" name="subscription_validity_days" value="{{ $subplan->subscription_validity_days }}">
		            				<p class="help-block"></p>
									@if($errors->has('subscription_validity_days'))
										<p class="help-block text-red">
											{{ $errors->first('subscription_validity_days') }}
										</p>
									@endif
		            			</div>
		            		</div>
		            		<div class="col-sm-6 form-group">
		            			<label class="control-label col-sm-12 p-l-5">{{ __('Availability') }}</label>
		            			<div class="f-half valid-date">
		            				<label class="control-label">From</label>
		            				<div class="input-group">
		            					<input id="subscription_validity_from" type="text" class="form-control date-picker" name="subscription_validity_from" value="{{  $subplan->subscription_validity_from }}" autofocus  >
										<div class="input-group-addon calender">
											<i class="fa fa-calendar"></i>
										</div>
										<p class="help-block"></p>
										@if($errors->has('subscription_validity_from'))
											<p class="help-block text-red">
												{{ $errors->first('subscription_validity_from') }}
											</p>
										@endif
									</div>
		            			</div>
		            			<div class="f-half valid-date">
		            				<label class="control-label text-right p-r-5">To</label>
		            				<div class="input-group">
		            					<input id="subscription_validity_to" type="text" class="form-control date-picker" name="subscription_validity_to" value="{{ $subplan->subscription_validity_to }}" autofocus  >
										<div class="input-group-addon calender">
											<i class="fa fa-calendar"></i>
										</div>
										<p class="help-block"></p>
										@if($errors->has('subscription_validity_to'))
											<p class="help-block text-red">
												{{ $errors->first('subscription_validity_to') }}
											</p>
										@endif
									</div>
		            			</div>
		            		</div>    	
		                </div>
		                <div class="row">
		            		<div class="col-sm-6 form-group">
		            			<div class="f-half">
		            				<label for="is_active" class="control-label">Status *</label>
									<select id="is_active" class="form-control" name="is_active" autofocus value="{{ old('is_active') }}">
										<option value="">Select Status</option>
										<option value="1" {{ $subplan->is_active == 1 ? 'selected' : ''}}>Active</option>
										<option value="0" {{ $subplan->is_active == 0 ? 'selected' : ''}}>Inactive</option>
									</select>
									<p class="help-block"></p>
									@if($errors->has('is_active'))
										<p class="help-block text-red">
											{{ $errors->first('is_active') }}
										</p>
									@endif
		            			</div>
		            			<div class="f-half">
		            				<label for="is_active" class="control-label">Created By *</label>
		            				<input id="subscription_plan_created_by" type="hidde" class="form-control date-picker" name="subscription_plan_created_by" value="{{ $subplan->subscription_plan_created_by }}" disabled="true"  >
		            			</div>
		            		</div>  	
		                </div>
		                <div class="row">
							<div class="col-xs-12 form-group">
								<div class="btn-b-u">
									<a href="{{ route('operators.edit',[Request::get('op')])}}" class="btn btn-warning">Back</a>
									
									<a href="{{ URL::previous()}}" class="btn btn-warning">Back</a>
									<button type="submit" class="btn btn-success">
										{{ __('Save') }}
									</button>
								</div>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
    </div>
    <!--End new subscription screen -->
@endsection
@section('javascript')
<script type="text/javascript">
	$('.datepicker').datepicker({
		todayBtn: "linked",
		clearBtn: true,
		dateFormat: 'dd-mm-yy'
	});

	$("#editSubscriptionPlanForm").validate({
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
							 	return $("#subscription_veh_wheel_type").val();

								// if ($("#subscription_veh_wheel_type").is(':checked')) {
								// 	return $("#subscription_veh_wheel_type").val(); 
								// }else{
								// 	return null;
								// }
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
						'subscription_id': function () { return $('#subscription_id').val(); },
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
				required : "Please select to plan status",
			}
		},

	});

	$(document).ready(function() {
		// var checked_veh_wheel_type = [];
		// $.each($("input[name='subscription_veh_wheel_type']:checked"), function(){            
  //           checked_veh_wheel_type.push($(this).val());
  //       });
  //       $('#subscription_veh_wheel_type').val(checked_veh_wheel_type);

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

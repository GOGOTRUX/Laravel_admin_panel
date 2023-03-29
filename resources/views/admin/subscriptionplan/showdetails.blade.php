<?php //dd($editsubplan); ?>
@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
		<h1>&nbsp;</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">View Plan</li>
		</ol>
@endsection
<!-- Main Content -->
@section('content')
	@if(session('success'))
		<div class="row">
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		</div>
	@else
		<div class="panel-body p-0">
			<div class="view-op">
				<!-- subplan notification -->
				<div class="row">
					<div class="section">
						<div class="form-group section-title"> View Plan Details</div>
						<div class="detail-info m-t-20">					
							<!-- <small class="text-info">{{ $editsubplan->message}}</small> -->
							<div class="row">
								<div class="col-sm-3 form-group">
									<label for="sub_plan_name" class="control-label">{{ __('Sub Plan Name: ') }}</label>
									@if(!empty( $editsubplan->subscription_type_name ))                   
						 				{{ $editsubplan->subscription_type_name }}
					  				@else
						  				-
					  				@endif  
								</div>
								<div class="col-sm-3 form-group">
									<label for="sub_amount" class="control-label">{{ __('Amount:') }}</label> 
					 				@if(!empty( $editsubplan->subscription_amount ))                   
					 				{{ $editsubplan->subscription_amount }} <i class="fa fa-inr" aria-hidden="true" fa-lg></i>
					  				@else
						  				-
					  				@endif
								</div>
								<div class="col-sm-3 form-group">
									@if(!empty($editsubplan->subscription_business_rs))
										<label for="subscription_business_rs" class="control-label">{{ __('Business') }} <i class="fa fa-inr" aria-hidden="true" fa-lg></i>:</label>
										{{ $editsubplan->subscription_business_rs }}
									@elseif(!empty($editsubplan->subscription_expected_enquiries))
										<label for="subscription_expected_enquiries" class="control-label">{{ __('Expected Enquiries:') }}</label>
										{{ $editsubplan->subscription_expected_enquiries }}
									@endif
								</div>
								<div class="col-sm-3 form-group">
									<label for="op_gender" class="control-label">{{ __('Vehicle Type:') }}</label>  
										@if($editsubplan->subscription_veh_wheel_type==1)
					 						All Type
				 						@elseif($editsubplan->subscription_veh_wheel_type==0)
				 							Multivehicle
				 						@else
				 							{{ $editsubplan->subscription_veh_wheel_type }} wheeler
					 					@endif	
								</div>	
							</div>
							<div class="row">
								<div class="col-sm-3 form-group">
									<label for="op_email" class="control-label">{{ __('Validity:') }}</label>
									@if(!empty( $editsubplan->subscription_validity_days ))                   
					 				{{ $editsubplan->subscription_validity_days }} days
					  				@else
						  				-
					  				@endif
								</div>
								<div class="col-sm-3 form-group">
									<label for="op_mobile_no" class="control-label">{{ __('Availability:') }}</label>
									@if(!empty( $data['validity_to']) && !empty( $data['validity_from']))                   
					 				{{ $data['validity_from'] }} <b>-</b> {{ $data['validity_to'] }} 
					 				
						 				@if(!empty($data['subscription_expired']) && $data['subscription_expired']==2 && $data['subscription_active_indays'] > 0)
						 					<span class="text-warning"> active in ({{$data['subscription_active_indays'] }}) days</span>
					 					@elseif(!empty($data['subscription_expired'] == 1))
					 						<span class="text-success">(running) </span>
				 						@elseif(!empty($data['subscription_expired'] == 0))
				 							<span class="text-red">(expired) </span>
						 				@endif

					  				@else
						  				-
					  				@endif
								</div>
								<div class="col-sm-3 form-group">
									<label for="op_mobile_no" class="control-label">{{ __('Logo:') }}</label>
									@if(!empty($data['sub_image_b64']))
										<img src = "data:image/png;base64,{{ $data['sub_image_b64'] }}" class="img-responsive p-img">
									@else
										-
									@endif
								</div>
								<div class="col-sm-3 form-group">
									<label for="op_alternative_mobile_no" class="control-label">{{ __('Description:') }}</label>
									@if(!empty( $data['subscription_desc']))                   
					 				{{ $data['subscription_desc'] }}
					  				@else
						  				-
					  				@endif
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3 form-group">
									<label for="op_mobile_no" class="control-label">{{ __('Plan Added By:') }}</label>
									@if(!empty($data['created_by']))
										{{ $data['created_by'] }}
									@else
										-
									@endif
								</div>
								<div class="col-sm-3 form-group">
									<label for="is_free_plan" class="control-label">{{__('Plan Created date :')}}</label>
									@if(!empty($data['created_date']))
										{{ $data['created_date'] }}
									@endif
								</div>
								<div class="col-sm-3 form-group">
									<label for="op_mobile_no" class="control-label">{{ __('Plan Approved By:') }}</label>
									@if(!empty($editsubplan['is_approved_by']))
										{{ $editsubplan['is_approved_by'] }}
									@else
										-
									@endif
								</div>
								<div class="col-sm-3 form-group">
									<label for="is_free_plan" class="control-label">{{__('Is Plan Free :')}}</label>
									@if(!empty($editsubplan['is_free_trial']==1))
									<span class="text-success">Yes</span>
									@else
									  <span class="text-info">No</span>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 form-group">
									<a href="{{ route('subscriptions.index')}}"><button class="btn btn-xs btn-warning">Back</button></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End subplan notification-->	
			</div>
		</div>		
	@endif
@endsection
<!-- JS scripts for this page only -->
@section('javascript')

<script type="text/javascript">
</script>
@endsection


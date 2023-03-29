<?php //dd($notification_details->subscription_type_name); ?>
@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
		<!-- <h1>View Notification</h1> -->
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">view notification</li>
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
					<div class="col-sm-12 form-group section-title"> {{ $notification_details->message_type =='subplan_approved' ? 'Subscription Plan Approved' : "Verify Subscription Plan"}} 
					</div>

					<div class="section v-sub-plan">
						<!-- <small class="text-info">{{ $notification_details->message}}</small> -->
						<div class="row">
							<div>
								<div class="row">
									<div class="col-sm-6 form-group">
										<label for="op_first_name" class="control-label">{{ __('Subscription Plan Name: ') }}</label> 
										@if(!empty( $notification_details->subscription_type_name ))                   
							 				{{ $notification_details->subscription_type_name }}
						  				@else
							  				-
						  				@endif  
									</div>
									<div class="col-sm-6 form-group">
										<label for="op_last_name" class="control-label">{{ __('Amount:') }}</label> 
						 				@if(!empty( $notification_details->subscription_amount ))                   
						 				{{ $notification_details->subscription_amount }} <i class="fa fa-inr" aria-hidden="true" fa-lg></i>
						  				@else
							  				-
						  				@endif
									</div>
								</div>
								<div class="row">
								  	@if( !empty($notification_details->subscription_business_rs))

								  	@else

								  	@endif
								  	<div class="col-sm-6 form-group">
										@if(!empty($notification_details->subscription_business_rs))
											<label for="subscription_business_rs" class="control-label">{{ __('Business') }} <i class="fa fa-inr" aria-hidden="true" fa-lg></i>:</label>
											{{ $notification_details->subscription_business_rs }}
										@elseif(!empty($notification_details->subscription_expected_enquiries))
											<label for="subscription_expected_enquiries" class="control-label">{{ __('Expected Enquiries:') }}</label>
											{{ $notification_details->subscription_expected_enquiries }}
										@endif
									</div>
									<div class="col-sm-6 form-group">
										<label for="op_gender" class="control-label">{{ __('Vehicle Type:') }}</label>  
										@if(!empty( $notification_details->subscription_veh_wheel_type ))                   
						 				{{ $notification_details->subscription_veh_wheel_type }} wheeler
						  				@else
							  				-
						  				@endif
									</div>								
								</div>
								<div class="row">
									<div class="col-sm-6 form-group">
										<label for="op_email" class="control-label">{{ __('Validity:') }}</label>
										@if(!empty( $notification_details->subscription_validity_days ))                   
						 				{{ $notification_details->subscription_validity_days }} days
						  				@else
							  				-
						  				@endif
									</div>
									<div class="col-sm-6 form-group">
										<label for="op_mobile_no" class="control-label">{{ __('Availability:') }}</label>
										@if(!empty( $data['validity_to']) && !empty( $data['validity_from']))                   
						 				{{ $data['validity_from'] }} <b>-</b> {{ $data['validity_to'] }}
						  				@else
							  				-
						  				@endif
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6 form-group">
										<label for="op_mobile_no" class="control-label">{{ __('Logo:') }}</label>
										@if(!empty($data['sub_image_b64']))
											<img src = "data:image/png;base64,{{ $data['sub_image_b64'] }}" class="img-responsive p-img">
										@else
											-
										@endif
									</div>
									<div class="col-sm-6 form-group">
										<label for="op_alternative_mobile_no" class="control-label">{{ __('Description:') }}</label>
										@if(!empty( $data['subscription_desc']))                   
						 				{{ $data['subscription_desc'] }}
						  				@else
							  				-
						  				@endif
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6 form-group">
										<label for="op_mobile_no" class="control-label">{{ __('Plan Added By:') }}</label>
										@if(!empty($data['created_by']))
											{{ $data['created_by'] }}
										@else
											-
										@endif
									</div>
									<div class="col-sm-6 form-group">
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 form-group">
										<a href="{{ route('home.notificationbox')}}" class="btn btn-warning">Back</a>

										@if($notification_details->message_type=='subplan_verify')
										<button type="button" class="btn btn-success" id="verify" value="verify" onclick="verifyByAdmin('{{ $notification_details->subscription_id }}')" {{ ( $notification_details->is_approved == 0 ) ? '' : 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified" ' }} >{{ __('Approve') }}</button>
										@endif
									</div>
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
	function verifyByAdmin(id)
	{
		swal({
			title: 'Are you sure you want to approve?',
			text: "it will approve plan",
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
						window.location.href = "{{ route('home.notificationbox') }}"
					});
				}
			});
		})
	}
</script>
@endsection


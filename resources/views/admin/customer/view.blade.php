<?php
//echo dd($orders);
?>
@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
<h1>
View Customer Details
<!--<small>(Vendors)</small>-->
</h1>
<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
	<li>Roles</li>
	<li class="active">Edit</li>
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
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-3">
				BOOKED TRIPS: {{ $booktrip_count }}
			</div>
			<div class="col-sm-3">
				My Saving Rs: 0
			</div>
		</div>
	</div>
	<div class="panel-body p-0">
		<div class="view-op">
			<!-- Customer Information -->
			<div class="row">
				<div class="col-sm-12 form-group section-title">Customer Status</div>	
				<div class="section">
					<div class="row">
						<!-- <div class="col-sm-3">
							Active/Inactive:
							@if($customer->is_active)
								<span class="text-success">Active</span>
							@else
								<span class="text-danger">Inactive</span>
							@endif
						</div> -->
						<div class="col-sm-3">
							Blocked:
							@if($customer->is_blocked)
								Yes
							@else
								No
							@endif
						</div>
						<div class="col-sm-3">
							Verified:
							@if($customer->user_verified)
								Yes
							@else
								No
							@endif
						</div>
					</div>
				</div>
			</div>
			<!-- Customer Information End-->
			<!--Personal Information -->
				<div class="row">
					<div class="col-sm-12 form-group section-title">Personal Information</div>
					<div class="section">
						<div class="row">
							<div class="col-sm-3">
								<img src = 'data:image/png;base64,{{ $customer->user_profile_pic }}' class="img-responsive p-img">
							</div>
							<div class="col-sm-9">
								<div class="row">
									<div class="col-sm-3 form-group">
										<label for="user_first_name" class="control-label">{{ __('First Name:') }}</label> 
										@if(!empty( $customer->user_first_name ))                   
							 				{{ $customer->user_first_name }}
						  				@else
							  				N.A.
						  				@endif               
									</div>
									<div class="col-sm-3 form-group">
										<label for="user_middle_name" class="control-label">{{ __('Middle Name:') }}</label> 
							 				@if(!empty( $customer->user_middle_name )) 
												{{ $customer->user_middle_name }}
											@else
							  					N.A.
						  					@endif  					   
									</div>
									<div class="col-sm-3 form-group">
										<label for="user_last_name" class="control-label">{{ __('Last Name:') }}</label> 
							 				@if(!empty( $customer->user_last_name )) 
												{{ $customer->user_last_name }}
											@else
							  					N.A.
						  					@endif  					   
									</div>
								</div>
								<div class="row">
								  	<div class="col-sm-3 form-group">
										<label for="user_dob" class="control-label">{{ __('Date of Birth:') }}</label>
										@if(!empty( $customer->user_dob )) 
											{{ $customer->user_dob }}    
										@else
										  N.A.
									  	@endif                  
									</div>
									<div class="col-sm-3 form-group">
										<label for="user_gender" class="control-label">{{ __('Gender:') }}</label>  
										@if(isset($customer->user_gender)) 
											@if($customer->user_gender==0)
												Female
											@elseif($customer->user_gender==1)
												Male
											@else
												Other
											@endif 
										@endif  
									</div>								
								</div>
								<div class="row">
									<div class="col-sm-6 form-group">
										<label for="email" class="control-label">{{ __('Email:') }}</label>
										@if(!empty( $customer->email )) 
										{{ $customer->email }}
										@else
										  N.A.
									  @endif  
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6 form-group">
										<label for="user_mobile_no" class="control-label">{{ __('Mobile Number:') }}</label>
										@if(!empty( $customer->user_mobile_no )) 
											{{ $customer->user_mobile_no }}
										@else
											N.A.
									  	@endif  
									</div>
								</div>
							</div>
						</div>
						<h4 class="addr">Address</h4>
						<div class="row">
							<div class="col-sm-3 form-group">
								<label for="address_pin_code" class="control-label">{{ __('PIN Code:') }}</label>
								{{ $customer->address_pin_code ? $customer->address_pin_code : 'N.A.' }} 
							</div>
							<div class="col-sm-3 form-group">
								<label for="address_state" class="control-label">{{ __('State:') }}</label>
								@if(!empty($address->first()->state)) 
									{{ $address->first()->state }}
								@else
								  	N.A.
							  	@endif  
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_address_city" class="control-label">{{ __('City:') }}</label>
								@if(!empty($address->first()->city)) 
									{{ $address->first()->city }}
								@else
								  	N.A.
							  	@endif  
							</div>
							<div class="col-sm-3 form-group">
								<label for="user_address_line" class="control-label">{{ __('Location:') }}</label>
								@if(!empty($customer->user_address_line)) 
									{{ $customer->user_address_line }}
								@else
							  		N.A.
						  		@endif  
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4 form-group">
								<label for="user_address_line_1" class="control-label">{{ __('Flat/Shop/Place:') }}</label>
								@if(!empty($customer->user_address_line_1)) 
									{{ $customer->user_address_line_1 }}
								@else
									N.A.
							  	@endif  
							</div>
							<div class="col-sm-4 form-group">
								<label for="user_address_line_2" class="control-label">{{ __('Street/Area:') }}</label>
								@if(!empty($customer->user_address_line_2)) 
									{{ $customer->user_address_line_2 }}
								@else
							  		N.A.
						  		@endif  
							</div>
							<div class="col-sm-4 form-group">
								<label for="user_address_line_3" class="control-label">{{ __('Landmark:') }}</label>
								@if(!empty($customer->user_address_line_3)) 
									{{ $customer->user_address_line_3 }}
								@else
								  	N.A.
							  	@endif  
							</div>
						</div>
					</div>					
				</div>
			<!-- End Personal information -->	
			<!-- account information -->
			<div class="row">
				<div class="col-sm-12 form-group section-title">Account Information</div>
				<div class="row">
					<div class="col-xs-6 form-group">
						<label for="cashback_points" class="control-label">Cashback Points</label>
						0
					</div>
					<div class="col-xs-6 form-group">
						<label for="cashback_value" class="control-label">Cashback Value</label>
						0
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 form-group">
						<label for="bonus_points" class="control-label">Bonus Points</label>
						0
					</div>
					<div class="col-xs-6 form-group">
						<label for="bonus_value" class="control-label">Bonus Value</label>
						0
					</div>
				</div>
			</div>
			<!-- account information -->
			<!-- upcoming trip information -->
			<div class="row">
				<div class="col-sm-12 form-group section-title">Lastest Trip Information</div>
				<div class="row">
					<div class="col-xs-6 form-group">
						<label for="ride_status" class="control-label">Trip Status</label>
						@if(!empty( $customer->ride_status ))
						{{ $customer->ride_status }}
						@else
							'Incomplete Booking'
						@endif
					</div>
				</div>
			</div>
			<!-- upcoming trip information -->
			<div class="row">
				<div class="col-xs-12 form-group">
					<center><a href="{{ route('customer.index') }}" class="btn btn-danger">Back</a></center>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@endif
@endsection
<!-- JS scripts for this page only -->
@section('javascript')
@endsection
<style type="text/css">
	.help-block-message {
		display: block;
		margin-top: 5px;
		margin-bottom: 10px;
		color: #dd4b39;
	}
	.error{
		color: red;
	}
</style>

@extends('layouts.app')
@section('content-header')
		<h1>Customer Edit Profile	</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
			<li>customer</li>
			<li class="active">Edit</li>
		</ol>
@endsection

@section('content')
	@if(session('success'))
		<div class="row" id="successMessage">
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		</div>
	@endif

	<div class="panel-body p-0">
		<div class="view-op">
			<div class="row" id="customerinformation">
				<form method="POST" id="editCustomerForm" action="{{ route('customer.update', [$customer->user_id]) }}" enctype="multipart/form-data">
				@method('PUT')
				@csrf		
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
								@can('customer_block')
									@if($customer->is_blocked==1)
										Unblock Customer
										<button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#unblock-modal"><i class="fa fa-check-square-o" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Unblock Customer"></i></button>
									@else
										Block Customer
										<button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#blockedoperator"><i class="fa fa-ban" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Block Customer"></i></button>
									@endif
								@endcan
							</div>
							<div class="col-sm-3">
								Verified:
								@if(!empty($customer->user_verified) && $customer->user_verified==1)
									<a href="#" data-toggle="tooltip" data-placement="top" title="customer verified"><i class="fa fa-check-circle text-info"></i></a>
								@else
									<button class="btn btn-xs btn-warning" type="button" onclick="verifyModal('{{ $customer->user_id }}')" data-toggle="tooltip" data-placement="top" title="click to verify customer">Click to verify</button>
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
							<div class="col-sm-6 form-group">
								<div class="col-sm-4 name-f-half">
									<label for="user_first_name" class="control-label">{{ __('First Name*') }}</label>
									<input type="hidden" name="user_id" id="user_id" value="{{ $customer->user_id }}">
									<input id="user_first_name" type="text" class="form-control" name="user_first_name" value="{{ $customer->user_first_name }}"  autofocus>
									
									<p class="help-block-message"></p>
									@if($errors->has('user_first_name'))
										<p class="help-block-message">
											{{ $errors->first('user_first_name') }}
										</p>
									@endif
								</div>
								<div class=" col-sm-4 name-f-half">
									<label for="user_middle_name" class="control-label">{{ __('Middle Name') }}</label>
									<input id="user_middle_name" type="text" class="form-control" name="user_middle_name" value="{{ $customer->user_middle_name }}"  autofocus>
									
									<p class="help-block-message"></p>
									@if($errors->has('user_middle_name'))
										<p class="help-block-message">
											{{ $errors->first('user_middle_name') }}
										</p>
									@endif
								</div>
								<div class="col-sm-4 name-f-half">
									<label for="user_last_name" class="control-label">{{ __('Last Name*') }}</label>
									<input id="user_last_name" type="text" class="form-control" name="user_last_name" value="{{ $customer->user_last_name }}"  autofocus>
									
									<p class="help-block-message"></p>
									@if($errors->has('user_last_name'))
										<p class="help-block-message">
											{{ $errors->first('user_last_name') }}
										</p>
									@endif
								</div>
							</div>
							<div class="col-sm-6 form-group">
								<div class="f-half">
									<label for="user_dob" class="control-label">{{ __('Date Of Birth*') }}</label>
									<div class="input-group">
										<input id="user_dob" type="text" class="form-control date-picker" name="user_dob" value="{{ $customer->user_dob }}" autofocus  >
										<div class="input-group-addon calender">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
									<label id="op_dob-error" class="error" for="user_dob"></label>									
									<p class="help-block-message"></p>
									@if($errors->has('user_dob'))
										<p class="help-block-message">
											{{ $errors->first('user_dob') }}
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="user_gender" class="control-label">{{ __('Gender*') }}</label>
									<select id="user_gender" type="text" class="form-control" name="user_gender" autofocus >
										<option  value="">Select Gender</option>
										<option value="0" {{  $customer->user_gender == '0' ? 'selected' : '' }}>Female</option>
										<option value="1" {{  $customer->user_gender == '1' ? 'selected' : '' }}>Male</option>
										<option value="2" {{  $customer->user_gender == '2' ? 'selected' : '' }}>Other</option>
									</select>
									
									<p class="help-block-message"></p>
									@if($errors->has('user_gender'))
										<p class="help-block-message">
											{{ $errors->first('user_gender') }}
										</p>
									@endif	
								</div>								
							</div>
						</div>
					</div>
				</div>
				<!--End Personal Information -->
				<div class="row">
					<div class="col-sm-6 form-group">
						<div class="f-half">
							<label for="email" class="control-label">{{ __('Email') }}</label>
							<input id="email" type="text" class="form-control" name="email" value="{{ $customer->email }}" autofocus >
							<p class="help-block-message"></p>
							@if($errors->has('email'))
								<p class="help-block-message">
									{{ $errors->first('email') }}
								</p>
							@endif
						</div>
						<div class="f-half">
							<label for="user_mobile_no" class="control-label">{{ __('Registered mobile number*') }}</label>
							<input id="user_mobile_no" type="text" class="form-control" name="user_mobile_no" value="{{ $customer->user_mobile_no }}"  disabled="disabled">
							<input id="user_mobile_no" type="hidden" class="form-control" name="user_mobile_no" value="{{ $customer->user_mobile_no }}">
							<p class="help-block-message"></p>
							@if($errors->has('user_mobile_no'))
								<p class="help-block-message">
									{{ $errors->first('user_mobile_no') }}
								</p>
							@endif
						</div>
					</div>
				</div>
				<div class="add-block">
					<h4>Address</h4>
					<div class="row first-line">
						<div class="col-sm-4 form-group">
							<label for="address_pin_code" class="control-label">{{ __('Select PIN Code*') }}</label>
							<select class="form-control select2" id="address_pin_code" name="address_pin_code" onchange="getaddress()"  data-placeholder="Select PIN Code">
								<option value="">Select PIN Code</option>
								@if(!empty($pincodeslist))
									@foreach($pincodeslist as $pincodeslist)
									<option value="{{ $pincodeslist->pincode }}" {{ $customer->address_pin_code == $pincodeslist->pincode ? 'selected' : '' }}>
									{{ $pincodeslist->pincode }}
									</option>                     
									@endforeach
								@endif
							</select>
							<label id="address_pin_code-error" class="error" for="address_pin_code"></label>
							<p class="help-block-message"></p>
							@if($errors->has('address_pin_code'))
								<p class="help-block-message">
									{{ $errors->first('address_pin_code') }}
								</p>
							@endif
						</div>

						<div class="col-sm-4 form-group">
							<label for="address_state" class="control-label">{{ __('State*') }}</label>
							
							<select id="address_state" type="text" class="form-control" name="address_state" >
								@if(!empty($address->first()->state_id))
								<option value="{{ $address->first()->state_id }}">{{ $address->first()->state }}</option>
								@endif
							</select>
							@if($errors->has('address_state'))
								<p class="help-block-message">
									{{ $errors->first('address_state') }}
								</p>
							@endif
						</div>

						<div class="col-sm-4 form-group">
							<label for="address_city" class="control-label">{{ __('City*') }}</label>
							<select id="address_city" type="text" class="form-control" name="address_city">
								@if(!empty($address->first()->id))
								<option value="{{ $address->first()->id }}" selected="select">{{ $address->first()->city }}</option>
								@endif
							</select>

							<p class="help-block-message"></p>
							@if($errors->has('address_city'))
								<p class="help-block-message">
									{{ $errors->first('address_city') }}
								</p>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 form-group">
							<div class="f-half">
								<label for="user_address_line" class="control-label">{{ __('Location') }}</label>
								<input id="user_address_line" type="text" class="form-control" name="user_address_line" value="{{ $customer->user_address_line }}"  autofocus>
								<p class="help-block-message"></p>
								@if($errors->has('user_address_line'))
									<p class="help-block-message">
										{{ $errors->first('user_address_line') }}
									</p>
								@endif
							</div>
							<div class="f-half">
								<label for="user_address_line_1" class="control-label">{{ __('Flat/Shop/Place*') }}</label>
								<input id="user_address_line_1" type="text" class="form-control" name="user_address_line_1" value="{{ $customer->user_address_line_1 }}" autofocus >
								<p class="help-block-message"></p>
								@if($errors->has('user_address_line_1'))
									<p class="help-block-message">
										{{ $errors->first('user_address_line_1') }}
									</p>
								@endif
							</div>
						</div>
						<div class="col-sm-6 form-group">
							<div class="f-half">
								<label for="user_address_line_2" class="control-label">{{ __('Complex/Society/Market') }}</label>
								<input id="user_address_line_2" type="text" class="form-control" name="user_address_line_2" value="{{ $customer->user_address_line_2 }}"  autofocus>
								<p class="help-block-message"></p>
								@if($errors->has('user_address_line_2'))
									<p class="help-block-message">
										{{ $errors->first('user_address_line_2') }}
									</p>
								@endif		
							</div>
							<div class="f-half">
								<label for="user_address_line_3" class="control-label">{{ __('Landmark') }}</label>
								<input id="user_address_line_3" type="text" class="form-control" name="user_address_line_3" value="{{ $customer->user_address_line_3 }}"  autofocus>
								<p class="help-block-message"></p>
								@if($errors->has('user_address_line_3'))
									<p class="help-block-message">
										{{ $errors->first('user_address_line_3') }}
									</p>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="row first-line">
						<div class="col-sm-6 form-group">
							<label for="user_profile_pic" class="control-label">{{ __('Upload Profile Picture') }}</label>
							<input id="user_profile_pic" type="file" class="form-control p-0" name="user_profile_pic" value="{{ $customer->user_profile_pic }}" autofocus onchange="preview_profile_pic();">

							<p class="help-block-message"></p>
							@if($errors->has('user_profile_pic'))
								<p class="help-block-message">
									{{ $errors->first('user_profile_pic') }}
								</p>
							@endif
						</div>
						<div class="col-sm-6 form-group" id="edit_profile_images_div">
							@if($customer->user_profile_pic)
							<div>
								<img src = 'data:image/png;base64,{{ $customer->user_profile_pic }}' width="80px" height="80px">
							</div>
							@endif
						</div>
						<div class="col-sm-6 form-group" id="profile_images_div" style="display: none">
							<label for="view_veh_images" class="control-label">{{ __('View Profile Images') }}</label><br>
							<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_profile" ></i>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="btn-b-u form-group">
						<button type="submit" class="btn btn-success">
						{{ __('Update') }}
						</button>
						<a href="{{ route('customer.index') }}" class="btn btn-danger">Back</a>
					</div>
				</div>
			</form>	
			</div>
		</div>
	</div>
	<!-- view profile images modal -->
	<div class="modal fade" id="view_profile">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Profile Picture</h4>
				</div>
				<div class="modal-body" >
					<div id="view_profile_img">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- view profile images modal end -->

	<!-- block customer -->
	<div class="modal fade" id="blockedoperator">
		<div class="modal-dialog">
			<form method="POST" action="{{ route('customerBlocked')}}">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Reason for Blocking</h4>
					</div>
					<div class="modal-body">
						<div id="show_driver_image">
							<input type="hidden" name="customer_id" value="{{ $customer->user_id }}" id="customer_id">
							<input type="hidden" name="customer_pagetype" value="edit" id="customer_pagetype">
							<textarea id='blockingtext' name="reason" placeholder='Write reasons....' rows=5px cols=75px required></textarea>
						</div>
						<p id="blockedmessage"></p>
					</div>
					<div class="modal-footer">
						<center>
							<input type="submit" name="submit" value="Yes, block it!" class="btn btn-danger">
							<button type="submit" data-dismiss="modal" class="btn btn-success">Cancel</button>
						</center>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="unblock-modal">
		<div class="modal-dialog">
			<form method="POST" action="{{ route('customer-unblocked')}}">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Unblock Customer</h4>
					</div>
					<div class="modal-body">
						<div id="show_driver_image">
							<input type="hidden" name="customer_id" value="{{ $customer->user_id }}" id="unblock_customer_id">
							<input type="hidden" name="customer_pagetype" value="edit" id="customer_pagetype">
							Are you sure to unblock customer?
						</div>
					</div>
					<div class="modal-footer">
						<center>
							<input type="submit" name="submit" value="OK" class="btn btn-danger">
							<button type="submit" data-dismiss="modal" class="btn btn-success">Cancel</button>
						</center>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- block customer end -->
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<script language="javascript" type="text/javascript">
	$('.datepicker').datepicker({
		todayBtn: "linked",
		clearBtn: true,
	});
	
	$(document).ready(function () {
		$('#editCustomerForm').on('submit', function(e){
		});
		$("#editCustomerForm").validate({
			rules: {
				user_first_name: {
					required: true,
				},
				user_last_name: {
					required: true,
				},
				user_dob: {
					required: true,
				},
				user_gender: {
					required: true,
				},
				// email: {
				// 	required: true,
				// },
				user_mobile_no: {
					required: true,
				},
				address_pin_code:{
					required: true,
				},
				address_state:{
					required: true,
				},
				address_city:{
					required: true,
				},
				// user_address_line:{
				// 	required: true,
				// },
				user_address_line_1:{
				        required: true,
				},
				// user_address_line_2:{
				// 	required: true,
				// },
				// user_address_line_3:{
				// 	required: true,
				// },
				/*
				user_profile_pic:{
					required: {
                        depends: function (){ 
                            var edit_profile_image = '{{ $customer->user_profile_pic }}';
                            var filesArray = document.getElementById("user_profile_pic").files;
                            if( (edit_profile_image!='' && edit_profile_image!=null )&& filesArray.length==0){
                                    return false;
                            }
                            if((edit_profile_image=='' || edit_profile_image==null ) && filesArray.length==0){
                                    return true;
                            }
                        }
                    },
				} */
			},  
			messages: {
				user_first_name : {
					required:"Please enter first name",
				},
				user_last_name:{
					required:"Please enter last name",
				}, 
				user_dob:{
					required:"Please select dob",
				},
				user_gender:{
					required:"Please select gender",
				}, 
				// email:{
				// 	required:"Please enter email",
				// }, 
				user_mobile_no:{
					required:"Please enter mobile number",
				}, 
				address_pin_code:{
					required:"Please enter pincode",
				}, 
				address_state:{
					required:"Please enter state",
				}, 
				address_city:{
					required:"Please enter city",
				}, 
				// user_address_line:{
				// 	required:"Please enter location",
				// }, 
				user_address_line_1:{
				 	required:"Please enter address line 1",
				}, 
				// user_address_line_2:{
				// 	required:"Please enter address line 2",
				// }, 
				// user_address_line_3:{
				// 	required:"Please enter address line 3",
				// }, 
			},
			invalidHandler: function(event, validator) {
			},
		});
	});

	//view profile pic
	function preview_profile_pic() 
	{
		$('#view_profile_img').html('');
		var output = document.getElementById("user_profile_pic");
		var total_file = document.getElementById("user_profile_pic").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#edit_profile_images_div').hide()
			$('#profile_images_div').show()
			$('#view_profile_img').append("<img src='"+output.src+"' class='veh-image'>");
		}else{
			$('#edit_profile_images_div').show()
			$('#profile_images_div').hide()
			$('#view_profile_img').html('');
		}
	}
	//get pincodes
	function getaddress() 
	{  
		var address_pin_code=$("#address_pin_code").val();  
		$.ajax({
			url :"{{ route('getcitystate') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"op_address_pin_code": address_pin_code
			},
			success : function(data){
				$('#address_city').html("");
				$('#address_state').html("");
				$('#address_city').append('<option value="'+ data[0].id +'">' + data[0].city + '</option>');
				$("#address_state").append('<option value="'+ data[0].state_id +'">' + data[0].state + '</option>');
			}
		});
	}

	function openModel(id)
	{
		if(id!='')
		{
			$('#customer_id').val(id);
			$('#unblock_customer_id').val(id);
		}
	}

	function verifyModal(id)
	{
		swal({
			title: 'Are you sure?',
			text: "Verify this customer!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Verify'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('verify-customer') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.response){
						swal({title: "Verified!", text: "Customer Verified Successfully", type: result.status}).then(function()
						{ 
							location.reload();
						});
					}
					else{
						if(result.message=="require atleast one booking"){
							swal({title: "Oops!", text: "Customer should have atleast one booking to verify", type: result.status}).then(function()
							{
								location.reload();
							});
						}else{
							swal({title: "Oops!", text: "Failed to Verify Customer", type: result.status}).then(function()
							{
								location.reload();
							});
						}
					}
				}
			});
		})
	}

</script>
@endsection



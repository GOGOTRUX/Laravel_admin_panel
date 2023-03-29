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
		<h1>{{ $header }}	</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
			<li>{{ $header }}</li>
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
	@if(session('error'))
		<div class="row" id="failMessage">
			<div class="alert alert-error">
				{{ session('error') }}
			</div>
		</div>
	@endif

	<div class="panel-body p-0">
		<div class="view-op">
			<form method="POST" id="editPersonal" action="{{ route('operators.update', [$operator->op_user_id]) }}" enctype="multipart/form-data">
				@method('PUT')
				@csrf		
				<!--Personal Information -->
				<div class="row">
					<div class="col-sm-12 form-group section-title">Personal Information</div>
					<div class="section">
						<div class="row">
							<div class="col-sm-6 form-group">
								<div class="col-sm-4 name-f-half">
									<label for="op_first_name" class="control-label">{{ __('First Name*') }}</label>
									<input type="hidden" name="op_user_id" id="op_user_id" value="{{ $operator->op_user_id }}">
									<input id="op_first_name" type="text" class="form-control" name="op_first_name" value="{{ $operator->op_first_name }}"  autofocus>
									
									<p class="help-block-message"></p>
									@if($errors->has('op_first_name'))
										<p class="help-block-message">
											{{ $errors->first('op_first_name') }}
										</p>
									@endif
								</div>
								<div class=" col-sm-4 name-f-half">
									<label for="op_middle_name" class="control-label">{{ __('Middle Name') }}</label>
									<input id="op_middle_name" type="text" class="form-control" name="op_middle_name" value="{{ $operator->op_middle_name }}"  autofocus>
									
									<p class="help-block-message"></p>
									@if($errors->has('op_middle_name'))
										<p class="help-block-message">
											{{ $errors->first('op_middle_name') }}
										</p>
									@endif
								</div>
								<div class="col-sm-4 name-f-half">
									<label for="op_last_name" class="control-label">{{ __('Last Name*') }}</label>
									<input id="op_last_name" type="text" class="form-control" name="op_last_name" value="{{ $operator->op_last_name }}"  autofocus>
									
									<p class="help-block-message"></p>
									@if($errors->has('op_last_name'))
										<p class="help-block-message">
											{{ $errors->first('op_last_name') }}
										</p>
									@endif
								</div>
							</div>
							<div class="col-sm-6 form-group">
								<div class="f-half">
									<label for="op_dob" class="control-label">{{ __('Date Of Birth*') }}</label>
									<div class="input-group">
										<input id="op_dob" type="text" class="form-control date-picker" name="op_dob" value="{{ $operator->op_dob }}" autofocus  >
										<div class="input-group-addon calender">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
									<label id="op_dob-error" class="error" for="op_dob"></label>									
									<p class="help-block-message"></p>
									@if($errors->has('op_dob'))
										<p class="help-block-message">
											{{ $errors->first('op_dob') }}
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="op_gender" class="control-label">{{ __('Gender*') }}</label>
									<select id="op_gender" type="text" class="form-control" name="op_gender" autofocus >
										<option  value="">Select Gender</option>
										<option value="0" {{  $operator->op_gender == '0' ? 'selected' : '' }}>Female</option>
										<option value="1" {{  $operator->op_gender == '1' ? 'selected' : '' }}>Male</option>
										<option value="2" {{  $operator->op_gender == '2' ? 'selected' : '' }}>Other</option>
									</select>
									
									<p class="help-block-message"></p>
									@if($errors->has('op_gender'))
										<p class="help-block-message">
											{{ $errors->first('op_gender') }}
										</p>
									@endif	
								</div>								
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 form-group">
								<div class="f-half">
									<label for="op_email" class="control-label">{{ __('Email') }}</label>
									<input id="op_email" type="text" class="form-control" name="op_email" value="{{ $operator->op_email }}" autofocus >
									<p class="help-block-message"></p>
									@if($errors->has('op_email'))
										<p class="help-block-message">
											{{ $errors->first('op_email') }}
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="op_mobile_no" class="control-label">{{ __('Pet Name') }}</label>
									<input id="op_pet_name" type="text" class="form-control" maxlength="6" name="op_pet_name" value="{{ $operator->op_pet_name }}" autofocus>
									<p class="help-block-message"></p>
									@if($errors->has('op_pet_name'))
										<p class="help-block-message">
											{{ $errors->first('op_pet_name') }}
										</p>
									@endif
								</div>
							</div>
							<div class="col-sm-6 form-group">
								<div class="f-half">
									<label for="op_mobile_no" class="control-label">{{ __('Registered mobile number*') }}</label>
									<input id="op_mobile_no" type="text" class="form-control" name="op_mobile_no" value="{{ $operator->op_mobile_no }}"  disabled="disabled">
									<input id="op_mobile_no" type="hidden" class="form-control" name="op_mobile_no" value="{{ $operator->op_mobile_no }}">
									<p class="help-block-message"></p>
									@if($errors->has('op_mobile_no'))
										<p class="help-block-message">
											{{ $errors->first('op_mobile_no') }}
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="op_alternative_mobile_no" class="control-label">{{ __('Alternate Mobile Number') }}</label>
									<input id="op_alternative_mobile_no" type="text" class="form-control" name="op_alternative_mobile_no" value="{{ $operator->op_alternative_mobile_no }}"  autofocus pattern="[1-9]{1}[0-9]{9}" title="The Mobile Number not Valid.">
									<p class="help-block-message"></p>
									@if($errors->has('op_alternative_mobile_no'))
										<p class="help-block-message">
											{{ $errors->first('op_alternative_mobile_no') }}
										</p>
									@endif
								</div>
							</div>
						</div>
						<div class="add-block">
							<h4>Address</h4>
							<div class="row first-line">
								<div class="col-sm-4 form-group">
									<label for="op_address_pin_code" class="control-label">{{ __('Select PIN Code*') }}</label>
									<select class="form-control select2" id="op_address_pin_code" name="op_address_pin_code" onchange="getaddress()"  data-placeholder="Select PIN Code">
										<option value="">Select PIN Code</option>
										@if(!empty($pincodeslist))
											@foreach($pincodeslist as $pincodeslist)
											<option value="{{ $pincodeslist->pincode }}" {{ $operator->op_address_pin_code == $pincodeslist->pincode ? 'selected' : '' }}>
											{{ $pincodeslist->pincode }}
											</option>                     
											@endforeach
										@endif
									</select>
									<label id="op_address_pin_code-error" class="error" for="op_address_pin_code"></label>
									<p class="help-block-message"></p>
									@if($errors->has('op_address_pin_code'))
										<p class="help-block-message">
											{{ $errors->first('op_address_pin_code') }}
										</p>
									@endif
								</div>

								<div class="col-sm-4 form-group">
									<label for="op_address_state" class="control-label">{{ __('State*') }}</label>
									
									<select id="op_address_state" type="text" class="form-control" name="op_address_state" >
										@if(!empty($address->first()->state_id))
										<option value="{{ $address->first()->state_id }}">{{ $address->first()->state }}</option>
										@endif
									</select>
									@if($errors->has('op_address_state'))
										<p class="help-block-message">
											{{ $errors->first('op_address_state') }}
										</p>
									@endif
								</div>

								<div class="col-sm-4 form-group">
									<label for="op_address_city" class="control-label">{{ __('City*') }}</label>
									<select id="op_address_city" type="text" class="form-control" name="op_address_city">
										@if(!empty($address->first()->id))
										<option value="{{ $address->first()->id }}" selected="select">{{ $address->first()->city }}</option>
										@endif
									</select>

									<p class="help-block-message"></p>
									@if($errors->has('op_address_city'))
										<p class="help-block-message">
											{{ $errors->first('op_address_city') }}
										</p>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<label for="op_address_line_3" class="control-label">{{ __('Location') }}</label>
										<input id="op_address_line_3" type="text" class="form-control" name="op_address_line_3" value="{{ $operator->op_address_line_3 }}"  autofocus>
										<p class="help-block-message"></p>
										@if($errors->has('op_address_line_3'))
											<p class="help-block-message">
												{{ $errors->first('op_address_line_3') }}
											</p>
										@endif
									</div>
									<div class="f-half">
										<label for="op_address_line_1" class="control-label">{{ __('Flat/Shop/Place*') }}</label>
										<input id="op_address_line_1" type="text" class="form-control" name="op_address_line_1" value="{{ $operator->op_address_line_1 }}" autofocus >
										<p class="help-block-message"></p>
										@if($errors->has('op_address_line_1'))
											<p class="help-block-message">
												{{ $errors->first('op_address_line_1') }}
											</p>
										@endif
									</div>
								</div>
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<label for="op_address_line_2" class="control-label">{{ __('Street/Area') }}</label>
										<input id="op_address_line_2" type="text" class="form-control" name="op_address_line_2" value="{{ $operator->op_address_line_2 }}"  autofocus>
										<p class="help-block-message"></p>
										@if($errors->has('op_address_line_2'))
											<p class="help-block-message">
												{{ $errors->first('op_address_line_2') }}
											</p>
										@endif		
									</div>
									<div class="f-half">
										<label for="op_landmark" class="control-label">{{ __('Landmark') }}</label>
										<input id="op_landmark" type="text" class="form-control" name="op_landmark" value="{{ $operator->op_landmark }}"  autofocus>
										<p class="help-block-message"></p>
										@if($errors->has('op_landmark'))
											<p class="help-block-message">
												{{ $errors->first('op_landmark') }}
											</p>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="row first-line">
							<div class="col-sm-6 form-group">
								<label for="op_profile_pic" class="control-label">{{ __('Upload Profile Picture') }}</label>
								<input id="op_profile_pic" type="file" class="form-control p-0" name="op_profile_pic" value="{{ $operator->op_profile_pic }}" autofocus onchange="preview_profile_pic();">

								<p class="help-block-message"></p>
								@if($errors->has('op_profile_pic'))
									<p class="help-block-message">
										{{ $errors->first('op_profile_pic') }}
									</p>
								@endif
							</div>
							<div class="col-sm-6 form-group" id="edit_profile_images_div">
								@if($operator->op_profile_pic)
								<div>
									<img src = '{{ asset("images") }}/{{ $operator->op_profile_pic }}' width="80px" height="80px">
									<!-- <img src = 'data:image/png;base64,{{ $operator->op_profile_pic }}' width="80px" height="80px"> -->
								</div>
								@endif
							</div>
							<div class="col-sm-6 form-group" id="profile_images_div" style="display: none">
								<label for="view_veh_images" class="control-label">{{ __('View Profile Images') }}</label><br>
								<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_profile" ></i>
							</div>
						</div>
						<div class="add-block">
							@if($operator->op_type_id == 1)<!-- upload doc if individual operator -->
								<h4>Documents</h4>
								<div class="row">
									<div class="col-sm-6 form-group">
										<div class="f-half">
											<input type="hidden" name="driving_license_doc[doc_type_id]" id="lic_type_id" value="2">
											<label for="lic_number" class="control-label">{{ __('Driving License No*') }}</label>
											<input id="lic_number" type="text" class="form-control" name="driving_license_doc[lic_number]" value="{{ $operator->lic_number }}" placeholder="Enter Number"  autofocus onkeyup="this.value = this.value.toUpperCase();">
											<p class="help-block-message"></p>
											@if($errors->has('driving_license_doc[lic_number]'))
												<p class="help-block-message">
													{{ $errors->first('driving_license_doc[lic_number]') }}
												</p>
											@endif
										</div>
										<div class="f-half">
											<label for="lic_validity" class="control-label">{{ __('Validity*') }}</label>
											<div class="input-group">
												<input id="lic_validity" type="text" class="form-control date-picker" name="driving_license_doc[lic_validity]" value="{{ $operator->lic_expiry }}"  autofocus>
												<div class="input-group-addon calender">
													<i class="fa fa-calendar"></i>
												</div>
											</div>
											<label id="lic_validity-error" class="error" for="lic_validity"></label>
											<p class="help-block-message"></p>
											@if($errors->has('lic_validity'))
												<p class="help-block-message">
													{{ $errors->first('lic_validity') }}
												</p>
											@endif
										</div>
									</div>
									<div class="col-sm-6 form-group">
										<div class="f-half">
											<label for="lic_image" class="control-label">{{ __('Upload Driving License*') }}</label>
											<input id="lic_image" type="file" class="form-control p-0" name="driving_license_doc[lic_image]" value="" autofocus onchange="preview_lic();">
											<p class="help-block-message"></p>
											@if($errors->has('lic_image'))
												<p class="help-block-message">
													{{ $errors->first('lic_image') }}
												</p>
											@endif
										</div>
										<div class="col-xs-3" id="edit_lic_image_div">
											@if($operator->lic_image)
											<div>
												<img src = '{{ asset("images") }}/{{ $operator->lic_image }}' width="80px" height="80px">
												<!--<img src = 'data:image/png;base64,{{ $operator->lic_image }}' width="80px" height="80px"> -->
											</div>
											@endif
										</div>
										<div class="col-xs-3">
											@if($operator['lic_is_verify'] == 1)
												<a href="#" data-toggle="tooltip" data-placement="top" title="Document is Verified"><i class="fa fa-check-circle text-info v-top"></i></a>
											@elseif(empty($operator['lic_id']))
											@else
											<button class="btn btn-xs btn-success v-top" type="button" onclick="verify_doc('{{ $operator['lic_id'] }}')" <?php if (empty($operator['lic_id'])){ ?> disabled <?php   } ?>>Verify</button>
											@endif
										</div>
										<div class="col-xs-3" id="lic_image_div" style="display: none">
											<label for="view_veh_images" class="control-label">{{ __('View License') }}</label><br>
											<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_lic" ></i>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6 form-group">
										<div class="f-half">
											<input type="hidden" name="pan_doc[doc_type_id]" id="pan_type_id" value="1">
											<label for="pan_number" class="control-label">{{ __('PAN No') }}</label>
											<input id="pan_number" type="text" class="form-control" name="pan_doc[pan_number]" value="{{ $operator->pan_number }}" autofocus onkeyup="this.value = this.value.toUpperCase();">
											<p class="help-block-message" id="pan-error-msg"></p>
											@if($errors->has('pan_number'))
												<p class="help-block-message">
													{{ $errors->first('pan_number') }}
												</p>
											@endif
										</div>
										<div class="f-half">
											<label for="pan_image" class="control-label">{{ __('Upload PAN') }}</label>
											<input id="pan_image" type="file" class="form-control p-0" name="pan_doc[pan_image]" value=""  autofocus onchange="preview_pan();">
											<p class="help-block-message"></p>
											@if($errors->has('pan_image'))
												<p class="help-block-message">
													{{ $errors->first('pan_image') }}
												</p>
											@endif
										</div>
									</div>
									<div class="col-sm-6 form-group">
										<div class="col-xs-3" id="edit_pan_image_div">
											@if($operator->pan_image)
											<div>
												<!--<img src = 'data:image/png;base64,{{ $operator->pan_image }}' width="80px" height="80px"> -->
											 <img src = '{{ asset("images") }}/{{ $operator->pan_image }}' width="80px" height="80px">
											</div>
											@endif
										</div>
										<div class="col-xs-3">
											@if($operator['pan_is_verify'] == 1)
												<a href="#" data-toggle="tooltip" data-placement="top" title="Document is Verified"><i class="fa fa-check-circle text-info v-top"></i></a>
											@elseif(empty($operator['pan_id']))
											@else
											<button class="btn btn-xs btn-success v-top" type="button" onclick="verify_doc('{{ $operator['pan_id'] }}')">Verify</button>
											@endif
										</div>
										<div class="col-xs-3" id="pan_image_div" style="display: none">
											<label for="view_veh_images" class="control-label">{{ __('View PAN') }}</label><br>
											<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_pan" ></i>
										</div>
									</div>
								</div>
								<!--additional document listing start by shubham-->
								@if(!empty($driver_documents))
								<div class="row first-line">
									<table id="document" class="table view-d-op table-bordered table-striped">
										<thead>
											<tr>
												<th class="text-center">Sr.No</th>
												<th>Document Name</th>
												<th>Document <br> validity</th>
												<th>Document <br> Number</th>
												<th>Document <br> Image</th>
												<th>Document <br> Verification</th>
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php $count=1; ?>   
											@foreach ($driver_documents as $doc)
												<tr>
													<td class="text-center">{{ $count++ }}</td>
													<td> {{ $doc->doc_label }}</td>
													<td>{{ $doc->doc_expiry ? $doc->doc_expiry : 'N.A.' }}</td>
													<td>{{ $doc->doc_number }}</td>
													<td>
														<i class="fa fa-fw fa-image" onclick="showimage('{{ $doc->doc_images }}')" data-toggle="modal" data-target="#modal-default"></i>
													</td>
													<td class="text-center">
														@if(!empty($doc->is_verified) && $doc->is_verified==1)
															<a href="#" data-toggle="tooltip" data-placement="top" title="Document is Verified"><i class="fa fa-check-circle text-info"></i></a>
														@else
															<button class="btn btn-xs btn-success" type="button" onclick="verify_doc('{{ $doc->doc_id }}')">Verify</button>
														@endif
													</td>
													<td>
														<a href="{{ route('update/documentinfo/',[ $doc->doc_id, 'typeof'=>'driver', '_op' => $operator_id ]) }}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>

														<button class="btn btn-xs btn-danger" type="button" onclick="deletedocument('{{ $doc->doc_id }}')"><i class="fa fa-trash-o"></i></button>
													</td>
												</tr>
											@endforeach
										</tbody>                
									</table>
								</div>
								@endif
								<!--additional document listing end -->
								@if(!empty($driverAdditionalDoc))
								<div class="row">
									<div class="form-group">
										<div id="personal_add_doc" class="form-group m-form__group"></div>
										<div class="col-sm-3 m--margin-bottom-15">
											<button type="button" name="add" id="add_more_personal_doc" class="addMorePadding btn btn-sm btn-info m-l-5"><span><i class="la la-plus"></i>
												</span>Additional Documents</button>
										</div>
									</div>
								</div>
								@endif
							@endif
						</div>
						<div class="row">
							<div class="col-sm-12 form-group">
								<div class="btn-up-center">
									<button type="submit" class="btn btn-sm btn-success">
									{{ __('Update') }}
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--Ens Personal Information -->
			</form>	
			<!-- Business driver  multiple driver information -->
			<div class="row" id="BusinessDriver">
				<div class="col-sm-12 form-group section-title">Driver Information</div>
				<div class="section">
					<div class="row">
						@if($operator->op_type_id == 1 )
							<!-- <a href="{{ route('Driver.create', ['op' => $operator_id]) }}" class="btn btn-xs btn-success">Add new</a> -->
						@else
							<a href="{{ route('Driver.create', ['op' => $operator_id, 'op_type' => $operator_type]) }}" class="btn btn-xs btn-success add-new-btn">Add new</a>
						@endif
					</div>
					<div class="row">
						@if(count($driver)!=0)
							<table id="driver" class="table view-d-op table-bordered table-striped">
								<thead>
									<tr>
										<th class="text-center">Sr.No</th>
										<th>Driver Name</th>
										
										<th>Verification</th>
										<th>Status</th>
										<th>Driver Profile Photo</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php $count=1; ?>
									@foreach ($driver as $drivers)
										<tr>
											<td class="text-center">{{ $count++ }}</td>
											<td>
												@if(isset($drivers->driver_first_name))
												{{ $drivers->driver_first_name }}
												{{ $drivers->driver_last_name }} 
												@else
												N.A.
												@endif
											</td>
											<td>
												@if(isset($drivers->driver_is_verified))
												@if($drivers->driver_is_verified==1)
												Verified
												@else
												Not verified
												@endif
												@else
												N.A.
												@endif
											</td>
											<td>  
												@if(isset($drivers->is_active))
												@if($drivers->is_active==1)
													Active
												@else
													Inactive
												@endif
												@else
												N.A.
												@endif
											</td>
											<td> 
												<i class="fa fa-fw fa-image" onclick="showdriverimage('{{  $drivers->driver_profile_pic }}')" data-toggle="modal" data-target="#driver_image">
													
												</i>                     
											</td>
											<td class="text-center">
												<a href="{{ route('update/driverinfo',[ $drivers->driver_id, 'op' => $operator_id, 'op_type' => $operator_type ]) }}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>											@if($operator->op_type_id == 2 )	 
													<button class="btn btn-xs btn-danger" type="button" onclick="deletedriver('{{ $drivers->driver_id }}')"><i class="fa fa-trash-o"></i></button>
												@endif
											</td>
										</tr>      
									@endforeach
								</tbody>
							</table>
						@else
							<div class="col-sm-12 m-t-10 form-group">No Driver Available</div> 
						@endif       
					</div>
				</div>
			</div>
			<!-- End business driver information -->
			<!-- Business Vehicles Information -->
			<div class="row" id="BusinessVehicles">
				<div class="col-sm-12 form-group section-title">Vehicles Information</div>
				<div class="section">
					<div class="row">
						@if($operator_type == 1 && $isVehicleAvailable==0)
							<a href="{{ route('vehicles.create',['op' => $operator_id,'op_type' => $operator_type]) }}" class="btn btn-xs btn-success add-new-btn">Add new</a>
						@elseif($operator_type == 2)
							<a href="{{ route('vehicles.create',['op' => $operator_id,'op_type' => $operator_type]) }}" class="btn btn-xs btn-success add-new-btn">Add new</a>
						@endif
					</div>
					<div class="row">
						@if(count($operatorvehicles)!=0)
							<table id="driver" class="table view-d-op table-bordered table-striped">
								<thead>
								<tr>
									<th class="text-center">Sr.No</th>
									<th> Registration Number </th>
									<th> Vehicles owner Name </th>
									<th> Vehicles Image</th>
									<th> Status </th>
									<th> Actions</th>
								</tr>
								</thead>
								<tbody>
									<?php $count=1; ?>
									@foreach ($operatorvehicles as $operatorvehicle)
										<tr>
											<td class="text-center">{{ $count++ }}</td>
											<td>{{ $operatorvehicle['veh_registration_no']}}</td>                        
											<td>
												@if(!empty($operatorvehicle['veh_owner_name']))                            
													<span class="text-center">{{ $operatorvehicle['veh_owner_name'] }} </span>
												@else
													<span class="text-center">-</span>
												@endif
											</td> 
											<td>
												<i class="fa fa-fw fa-image" onclick="showVehimage('{{ $operatorvehicle['veh_images_array'] }}')" data-toggle="modal" data-target="#veh_imags" ></i>
											</td>
											<td>
												@if($operatorvehicle['is_active']==0)
													Deactive
												@else
													Active
												@endif
											</td>                      
											<td class="text-center">
												<a href="{{ route('update/Vehicles',[$operatorvehicle['veh_id'],$header, 'op' => $operator_id ]) }}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
												@if($operator->op_type_id == 2 ) 
													<button class="btn btn-xs btn-danger" type="button" onclick="deleteoperatorvehicle({{ $operatorvehicle['veh_id']}} )"><i class="fa fa-trash-o"></i></button>
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<div class="col-sm-12 m-t-10 form-group">No Vehicle Available</div>
						@endif
					</div>
				</div>
			</div>
			<!-- End business Vehicles Information -->
			<!-- document section -->
			<div class="row">
				<div class="col-sm-12 section-title form-group">Business Information</div>
				<div class="section">
					<form method="POST" action="{{ route('business.update', [$operator->op_user_id]) }}" enctype="multipart/form-data" id="editBusiness">
						@method('PUT')
						@csrf
						<input type="hidden" name="op_user_id" id="op_user_id" value="{{ $operator->op_user_id }}">
						<div class="row first-line">
							<div class="col-sm-4 form-group">
								<label for="" class="control-label">{{ __('Business Name*') }}</label>
								<input id="op_bu_name" type="text" class="form-control" name="op_bu_name" value="{{ $operator->op_bu_name }}" autofocus>
								<p class="help-block-message">
									@if ($errors->has('op_bu_name'))
										<div class="error">Business Name field is required</div>
									@endif
								</p>
							</div>
							<div class="col-sm-4 form-group">
								<label for="" class="control-label">{{ __('Email For Bills') }}</label>
								<input id="op_bu_email" type="text" class="form-control" name="op_bu_email" value="{{ $operator->op_bu_email }}" autofocus>
								<p class="help-block-message">
									@if ($errors->has('op_bu_email'))
										<div class="error">Business Email field is required</div>
									@endif
								</p>
							</div>
							<div class="col-sm-4 form-group">
								<label for="" class="control-label">{{ __('Select Payment Mode *') }}</label>
								<select id="op_payment_mode" type="text" class="form-control" name="op_payment_mode">
									<option value="">Select Payment Mode</option>
									<option value="3" {{ $operator->op_payment_mode == 3 ? 'selected' : '' }}>Both</option>
									<option value="1" {{ $operator->op_payment_mode == 1 ? 'selected' : '' }}>Cash</option>
									<option value="2" {{ $operator->op_payment_mode == 2 ? 'selected' : '' }}>Digital</option>                   
								</select>
								<p class="help-block-message">
									@if ($errors->has('op_payment_mode'))
										<div class="error">Business Payment Mode field is required</div>
									@endif
								</p>
							</div>
						</div>
						<div class="add-block">
							<h4>Address</h4>
							<div class="row">
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<label for="" class="control-label">{{ __('Flat/Shop/Place*') }}</label>
										<input id="op_bu_address_line_1" type="text" class="form-control" name="op_bu_address_line_1" value="{{ $operator->op_bu_address_line_1 }}"  autofocus>
										<p class="help-block-message">
											@if ($errors->has('op_bu_address_line_1'))
												<div class="error">Flat/Shop/Place field is required</div>
											@endif
										</p>										
									</div>
									<div class="f-half">
										<label for="" class="control-label">{{ __('Location*') }}</label>
										<input id="op_bu_address_line_3" type="text" class="form-control" name="op_bu_address_line_3" value="{{ $operator->op_bu_address_line_3 }}" autofocus>
										<p class="help-block-message">
											@if ($errors->has('op_bu_address_line_3'))
												<div class="error">Location field is required</div>
											@endif
										</p>
									</div>
								</div>
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<label for="" class="control-label">{{ __('Street/Area') }}</label>
										<input id="op_bu_address_line_2" type="text" class="form-control" name="op_bu_address_line_2" value="{{ $operator->op_bu_address_line_2 }}"  autofocus>
									</div>
									<div class="f-half">
										<label for="" class="control-label">{{ __('Landmark*') }}</label>
										<input id="op_bu_landmark" type="text" class="form-control" name="op_bu_landmark" value="{{ $operator->op_bu_landmark }}"  autofocus>
										<p class="help-block-message">
											@if ($errors->has('op_bu_landmark'))
												<div class="error">Landmark field is required</div>
											@endif
										</p>				
									</div>
								</div>
							</div>
							<div class="row first-line">
								<div class="col-sm-4 form-group">
									<label for="" class="control-label">{{ __('Select PIN Code*') }}</label>
									<select class="form-control select2" id="op_bu_address_pin_code" name="op_bu_address_pin_code" onchange="getBuAddress()" data-placeholder="Select PIN Code">
										<option value="">Select PIN Code</option>
										@if(!empty($bupincodeslist))
											@foreach($bupincodeslist as $pincodeslist)
											<option value="{{ $pincodeslist->pincode }}" {{ $operator->op_bu_address_pin_code == $pincodeslist->pincode ? 'selected' : '' }}>
											{{ $pincodeslist->pincode }}
											</option>
											@endforeach
										@endif
									</select>
									<label id="op_bu_address_pin_code-error" class="error" for="op_bu_address_pin_code"></label>
									<p class="help-block-message">
										@if ($errors->has('op_bu_address_pin_code'))
											<div class="error">Business Pincode field is required</div>
										@endif
									</p>
								</div>
								<div class="col-sm-4 form-group">
									<label for="" class="control-label">{{ __('State*') }}</label>			
									<select id="op_bu_address_state" type="text" class="form-control" name="op_bu_address_state">
										<!-- <option value="">Select State</option> -->
										@if(!empty($bu_address->state))
										<option value="{{ $bu_address->state_id }}">{{ $bu_address->state }}</option>
										@endif
									</select>
									<p class="help-block-message">
										@if ($errors->has('op_bu_address_state'))
											<div class="error">Business State field is required</div>
										@endif
									</p>
								</div>

								<div class="col-sm-4 form-group">
									<label for="" class="control-label">{{ __('Select City *') }}</label>
									<select id="op_bu_address_city" type="text" class="form-control" name="op_bu_address_city">
										<!-- <option value="">Select City</option> -->
										@if(!empty($bu_address->city))
										<option value="{{ $bu_address->id }}">{{ $bu_address->city }}</option>
										@endif
									</select>
									<p class="help-block-message">
										@if ($errors->has('op_bu_address_city'))
											<div class="error">Business City field is required</div>
										@endif
									</p>
								</div>
							</div>
						</div>
						<div class="add-block">
							<h4>Documents</h4>
							<div class="row">
								<div class="col-sm-8 form-group">
									<div class="row">
										<div class="col-sm-4 form-group p-l-5">
											<label for="op_bu_pan_no" class="control-label">{{ __('PAN*') }}</label>
											<input id="op_bu_pan_no" type="text" class="form-control" name="op_bu_pan_no" value="{{ $operator->op_bu_pan_no }}"  autofocus onkeyup="this.value = this.value.toUpperCase();">
											<p class="help-block-message">
												@if ($errors->has('op_bu_pan_no'))
													<div class="error">Business Pan Number field is required</div>
												@endif
											</p>
										</div>
										<div class="col-sm-4 form-group">
											<label for="bu_pan_image" class="control-label">{{ __('Upload PAN*') }}</label>
											<input id="bu_pan_image" type="file" class="form-control p-0" name="bu_pan_image" value="{{ $operator->op_bu_pan_no_doc }}" autofocus>
											<p class="help-block-message" onchange="preview_bupan();" >
												@if ($errors->has('bu_pan_image'))
													<div class="error">Business Pan Image field is required</div>
												@endif
											</p>
											@if($errors->has('bu_pan_image'))
												<p class="help-block-message">
													{{ $errors->first('bu_pan_image') }}
												</p>
											@endif
										</div>
										@if($operator->op_bu_pan_no_doc)
										<div class="col-sm-4 form-group" id="edit_bupan_image_div">
											<img src = '{{ asset("images") }}/{{ $operator->op_bu_pan_no_doc }}' width="80px" height="80px">
											<!-- <img src = 'data:image/png;base64,{{ $operator->op_bu_pan_no_doc }}' width="80px" height="80px"> -->
										</div>
										@endif
										<div class="col-sm-4" id="bupan_image_div" style="display: none">
											<label for="view_veh_images" class="control-label">{{ __('View PAN') }}</label><br>
											<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_bupan" ></i>
										</div>
									</div>
								</div>
								<div class="col-sm-4 form-group">
									<div class="g-half">
										<label for="" class="control-label">{{ __('GSTN*') }}</label>
										<div class="row">
											<div class="gstn">
												<input type="radio" class="gstn_availability" name="op_bu_gstn_available" id="op_bu_gstn_available" value="no" {{  $operator->op_bu_gstn_available == '0' ? 'checked' : '' }}>
												<span>No</span>
											</div>
											<div class="gstn">
												<input type="radio" class="gstn_availability" name="op_bu_gstn_available" id="op_bu_gstn_available" value="yes" {{  $operator->op_bu_gstn_available == '1' ? 'checked' : '' }}>
												<span>Yes</span>
											</div>
										</div>
									</div>
									<div class="f-half g-txt" id="gstn_no">
										<label class="control-label">&nbsp;</label>
										<input id="op_bu_gstn_no" type="text" class="form-control" name="op_bu_gstn_no" value="{{  $operator->op_bu_gstn_no}}"  autofocus>	
									</div>
								</div>
							</div>
							<!-- business information document listing start -->
							@if(!empty($businessDoc))
							<div class="row first-line">
								<table id="document" class="table view-d-op table-bordered table-striped">
									<thead>
										<tr>
											<th class="text-center">Sr.No</th>                
											<th>Document Name</th>
											<th>Document <br> validity</th>
											<th>Document <br> Number</th>
											<th>Document <br> Image</th>
											<th>Document <br> Verification</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php $count=1; ?>   
										@foreach ($businessDoc as $doc)
											<tr>
												<td class="text-center">{{ $count++ }}</td>
												<td> {{ $doc->doc_label }}</td>
												<td>{{ $doc->doc_expiry ? $doc->doc_expiry : 'N.A.' }}</td>
												<td>{{ $doc->doc_number }}</td>
												<td>
													<i class="fa fa-fw fa-image" onclick="showimage('{{ $doc->doc_images }}')" data-toggle="modal" data-target="#modal-default"></i>
												</td>
												<td>
													@if(!empty($doc->is_verified) && $doc->is_verified==1)
														<a href="#" data-toggle="tooltip" data-placement="top" title="Document is Verified"><i class="fa fa-check-circle text-info v-top"></i></a>
													@else
														<button class="btn btn-xs btn-success" type="button" onclick="verify_doc('{{ $doc->doc_id }}')">Verify</button>
													@endif
												</td>
												<td>
													<a href="{{ route('update/documentinfo/',[ $doc->doc_id, 'typeof'=>'business', '_op' => $operator_id ]) }}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>

													<button class="btn btn-xs btn-danger" type="button" onclick="deletedocument('{{ $doc->doc_id }}')"><i class="fa fa-trash-o"></i></button>
												</td>
											</tr>
										@endforeach
									</tbody>                
								</table>
							</div>
						@endif								
							<!-- business information document listing end-->
							@if(!empty($businessAdditionalDoc))
							<div class="row">
								<div id="business_doc" class="form-group m-form__group"></div>
								<div class="col-sm-3 m--margin-bottom-15">
									<button type="button" name="add" id="add_more_business_doc" class="addMorePadding btn btn-sm btn-info m-l-5"><span><i class="la la-plus"></i></span>Additional Documents</button>
								</div>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="col-sm-12 form-group">
								<div class="btn-up-center">
									<button type="submit" class="btn btn-sm btn-success">
									{{ __('Update') }}
									</button>
								</div>
							</div>
						</div>
					</form>						
				</div>
			</div>
			<!-- Payment Information -->
			<div class="row">
				<div class="col-sm-12 section-title form-group">Bank Information</div>
				<div class="section">
					<form method="POST" action="{{ route('editbankinfo')}}" id="editBankForm" enctype="multipart/form-data">

						@csrf
						<input type="hidden" name="op_user_id" id="op_user_id" value="{{ $operator->op_user_id }}">
						
						<div class="row">
							<div class="col-sm-3 form-group">
								<div class="first-line">
									<label for="op_bank_name" class="control-label">{{ __('Select Bank*') }}</label>
									<select id="op_bank_name" type="text" class="form-control select2" name="op_bank_name" data-placeholder="Select Bank Name">
										<option value="" >Select Bank</option>
										@if(!empty($bankslist))
											@foreach($bankslist as $bank)
											<option value="{{ $bank['id'] }}" {{ $operator->op_bank_name == $bank['id'] ? 'selected' : '' }}>
											{{ $bank['op_bank_name'] }}
											</option>                     
											@endforeach
										@endif
									</select>
									<label id="op_bank_name-error" class="error" for="op_bank_name"></label>
									<p class="help-block-message"></p>
									@if($errors->has('op_bank_name'))
										<p class="help-block-message">
											{{ $errors->first('op_bank_name') }}
										</p>
									@endif
								</div>
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_bank_account_number" class="control-label">{{ __('Enter Account Number*') }}</label>
								<input id="op_bank_account_number" type="text" class="form-control" name="op_bank_account_number" value="{{ $operator->op_bank_account_number }}" autofocus>
								<p class="help-block-message"></p>
								@if($errors->has('op_bank_account_number'))
									<p class="help-block-message">
										{{ $errors->first('op_bank_account_number') }}
									</p>
								@endif
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_bank_ifsc" class="control-label">{{ __('Enter IFSC Code*') }}</label>
								<input id="op_bank_ifsc" type="text" class="form-control" name="op_bank_ifsc" value="{{ $operator->op_bank_ifsc }}" autofocus>
								<p class="help-block-message"></p>
								@if($errors->has('op_bank_ifsc'))
									<p class="help-block-message">
										{{ $errors->first('op_bank_ifsc') }}
									</p>
								@endif
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_document_image" class="control-label">{{ __('Document Image*') }}</label>
								<!-- <div class="op-doc"> -->
									<div class="op_doc_image">
										<input id="op_document_image" type="file" class="form-control p-0" name="op_document_image" value="{{$operator->op_blank_cheque}}" autofocus onchange="preview_doc_pic();">

							            <p class="help-block-message"></p>
							            @if($errors->has('op_document_image'))
							                <p class="help-block-message">
							                    {{ $errors->first('op_document_image') }}
							                </p>
							            @endif
									</div>
									<div class="show_doc_image" id="edit_doc_images_div">
							    		@if(!empty($operator->op_blank_cheque))
							    		<img src = '{{ asset("images") }}/{{ $operator->op_blank_cheque }}' width="80px" height="80px">
							            	<!-- <img src = 'data:image/png;base64,{{ $operator->op_blank_cheque }}' width="80px" height="80px"> -->
							            @endif
									</div>
									<div class="view_doc_image" id="doc_images_div" style="display: none">
							    		<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_doc" ></i>
									</div>
								<!-- </div> -->
							</div>
							<!-- <div class="col-sm-3 form-group">
								<label for="op_razorpay_accid" class="control-label">{{ __('Enter Razorpay AccountId') }}</label>
								<input id="op_razorpay_accid" type="text" class="form-control" name="op_razorpay_accid" value="{{ $operator->op_razorpay_accid }}" autofocus>
								<p class="help-block-message"></p>
								@if($errors->has('op_razorpay_accid'))
									<p class="help-block-message">
										{{ $errors->first('op_razorpay_accid') }}
									</p>
								@endif
							</div> -->
						</div>

						<!-- business information document listing start -->
						@if(!empty($paymentDoc))
						<div class="row first-line">
							<table id="document" class="table view-d-op table-bordered table-striped">
								<thead>
									<tr>
										<th class="text-center">Sr.No</th>                
										<th>Document Name</th>
										<th>Document <br> validity</th>
										<th>Document <br> Number</th>
										<th>Document <br> Image</th>
										<th>Document <br> Verification</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php $count=1; ?>   
									@foreach ($businessDoc as $doc)
										<tr>
											<td class="text-center">{{ $count++ }}</td>
											<td> {{ $doc->doc_label }}</td>
											<td>{{ $doc->doc_expiry ? $doc->doc_expiry : 'N.A.' }}</td>
											<td>{{ $doc->doc_number }}</td>
											<td>
												<i class="fa fa-fw fa-image" onclick="showimage('{{ $doc->doc_images }}')" data-toggle="modal" data-target="#modal-default"></i>
											</td>
											<td class="text-center">
												@if(!empty($doc->is_verified) && $doc->is_verified==1)
													<a href="#" data-toggle="tooltip" data-placement="top" title="Document is Verified"><i class="fa fa-check-circle text-info"></i></a>
												@else
													<button class="btn btn-xs btn-success" type="button" onclick="verify_doc('{{ $doc->doc_id }}')">Verify</button>
												@endif
											</td>
											<td>
												<a href="{{ route('update/documentinfo/',[ $doc->doc_id, 'typeof'=>'business', '_op' => $operator_id ]) }}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>

												<button class="btn btn-xs btn-danger" type="button" onclick="deletedocument('{{ $doc->doc_id }}')"><i class="fa fa-trash-o"></i></button>
											</td>
										</tr>
									@endforeach
								</tbody>                
							</table>
						</div>
						@endif								
							<!-- business information document listing end-->
						@if(!empty($paymentAdditionalDoc))
						<div class="row">
							<div id="business_doc" class="form-group m-form__group"></div>
							<div class="col-sm-3 m--margin-bottom-15">
								<button type="button" name="add" id="add_more_business_doc" class="addMorePadding btn btn-sm btn-info m-l-5"><span><i class="la la-plus"></i></span>Additional Documents</button>
							</div>
						</div>
						@endif
						<div class="row">
							<div class="col-sm-12 form-group">
								<div class="btn-ver-up">
									@if(!empty($operator->is_op_bank_verified) && $operator->is_op_bank_verified==1)
										<button class="btn verified-btn" data-toggle="tooltip" data-placement="top" title="Bank details are Verified"><i class="fa fa-check-square text-info"></i></button>
									@else
										@if(!empty($operator->op_bank_name) && !empty($operator->op_bank_account_number) && !empty($operator->op_bank_ifsc))
										<button class="btn btn-info" type="button" onclick="verify_bank('{{ $operator->op_user_id }}')">Verify</button>
										@else
										<button class="btn btn-info" type="button" onclick="verify_bank('{{ $operator->op_user_id }}')" disabled  data-toggle="tooltip" data-placement="top" title="Please add bank details">Verify</button>
										@endif
									@endif
									<button type="submit" class="btn btn-success">
									{{ __('Update') }}
									</button>
								</div>
							</div>
						</div>							
					</form>						
				</div>
			</div>
			<!-- End payment Information -->
			<div class="row" >
				<div class="col-sm-12 form-group">
					<form action="{{ route('operators/status') }}" method="POST">
						@csrf

						<input id="op_user_id" type="hidden"  name="op_user_id" value="{{ $operator->op_user_id }}">
						<input id="op_mobile_no" type="hidden" class="form-control" name="op_mobile_no" value="{{ $operator->op_mobile_no }}">

						<input type="checkbox" name="op_is_verified" id="op_is_verified" {{ $operator->op_is_verified == 1 ? 'checked' : '' }}> 
						
						<label for="op_is_verified" class="control-label">Operator is Verified.</label>
						<div class="btn-b-u">
							<a href="{{ url('/operators') }}" class="btn btn-warning">Back</a>
							<button type="submit" class="btn btn-success">
								{{ __('Update') }}
							</button>
						</div>
					</form>
				</div>    
			</div>
		</div>
	</div>
	<!-- model code -->
	<!-- Driver photo modal -->
	<div class="modal fade" id="driver_image">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Driver profile photo </h4>
				</div>
				<div class="modal-body">
					<div id="show_driver_image"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Driver photo modal -->
	<!-- Document image modal -->
	<div class="modal fade" id="modal-default">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Document Images</h4>
				</div>
				<div class="modal-body">
					<div id="show_docuemnt_image"></div>
				</div>
			</div>
		</div>
	</div>
	<!--End Document image modal -->
	<!-- Document verification image modal -->
	<div class="modal fade" id="op_verification_modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Please check following things</h4>
				</div>
				<div class="modal-body">
					<div id="show_verification_error"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>	
				</div>
			</div>
		</div>
	</div>
	<!-- End Document verification image modal -->
	<!-- Vehicle image modal -->
	<div class="modal fade" id="veh_imags">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Vehicle Images</h4>
				</div>
				<div class="modal-body" >
					<div id="driver_veh_image">						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Vehicle image modal -->
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
	<!-- view lic images modal -->
	<div class="modal fade" id="view_lic">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Uploaded License</h4>
				</div>
				<div class="modal-body" >
					<div id="view_lic_img">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- view lic images modal end -->
	<!-- view pan images modal -->
	<div class="modal fade" id="view_pan">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Uploaded Pan</h4>
				</div>
				<div class="modal-body" >
					<div id="view_pan_img">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- view pan images modal end -->
	<!-- view bupan images modal -->
	<div class="modal fade" id="view_bupan">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Uploaded Pan</h4>
				</div>
				<div class="modal-body" >
					<div id="view_bu_pan_img">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- view bupan images modal end -->
	<div class="modal fade" id="view_doc">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">Uploaded Doc Image</h4>
	            </div>
	            <div class="modal-body" >
	                <div id="view_doc_img">
	                    
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

	// form validation
    $(document).ready(function () {
		
    	$('#editBankForm').on('submit', function(e){
			console.log('on bank form submit');
		});

    	$("#editBankForm").validate({
			rules: {
				op_bank_name: {
					required: true,
				},
				op_bank_account_number: {
					required: true,
					digits: true,
					check_account_number:true,
				},
				op_bank_ifsc: {
					required: true,
					check_ifsc:true,
				},
				op_document_image: {
                    required: {
                        depends: function (){ 
                            var edit_doc_image = '{{ $operator->op_blank_cheque }}';
                            var filesArray = document.getElementById("op_document_image").files;
                            if( (edit_doc_image!='' && edit_doc_image!=null )&& filesArray.length==0){
                                    return false;
                            }
                            if((edit_doc_image=='' || edit_doc_image==null ) && filesArray.length==0){
                                    return true;
                            }
                        }
                    },
                },
				// op_razorpay_accid: {
				// 	required: true,
				// },
			},  
			messages: {
				op_bank_name : {
					required:"Please select bank name",
				},
				op_bank_account_number:{
					required:"Please enter account number",
				}, 
				op_bank_ifsc:{
					required:"Please enter bank ifsc code",
				},
				op_document_image:{
                    required:"Please upload document",
                },
				
				// op_razorpay_accid:{
				// 	required:"Please enter razorpay account id",
				// }, 
			},
			invalidHandler: function(event, validator) {
			},
		});

		// check ifsc code & account number
		jQuery.validator.addMethod("check_ifsc", function(value, element) {
  			return value.match(/^[A-Za-z]{4}[a-zA-Z0-9]{7}$/)
		}, "Please enter valid ifsc code");

		jQuery.validator.addMethod("check_account_number", function(value, element) {
  			return value.match(/^[0-9]{9,18}$/)
		}, "Please enter valid account number");
		// check ifsc code & account number end
 	});	
	//form validation-end

	$('.datepicker').datepicker({
		todayBtn: "linked",
		clearBtn: true,
	});

	var verification_status = '{{json_encode($verification_status)}}';
    var verification_status = JSON.parse(verification_status.replace(/&quot;/g, '\"'));
    if(!verification_status.status){
        $("#op_is_verified").prop("checked", false);
    }

	$(document).ready(function () {
		$('[data-toggle="tooltip"]').tooltip();  
		//code for hiding success msg
		setTimeout(function() {
			$("#successMessage").addClass('hide');
			$("#failMessage").addClass('hide');
		}, 1000);
		//end

		// bank form
		// $('body').on('click','#op_bank_name' ,function()
		// {
		// 	var op_bank_name = $(this).val();
		// 	if(op_bank_name=='Select Bank'){
		// 		$("#op_bank_name").prop('required',true);
		// 	}else{
		// 		$("#op_bank_name").prop('required',false);
		//    }
		// });
		// bank form-end

		$("#op_first_name").keyup(function() {
            var op_first_name = $("#op_first_name").val();
            if(op_first_name.length <= 6){
            	$("#op_pet_name").val(op_first_name);
            }
        });

		$('input[name=op_is_verified]').click(function() {
			var html = html2 = '';
			var verification_status = '{{json_encode($verification_status)}}';
			var verification_status = JSON.parse(verification_status.replace(/&quot;/g, '\"'));
			if(!verification_status.status){
				$("#op_is_verified").prop("checked", false);
				if (typeof verification_status.driver_status !== 'undefined') {
					if (!verification_status.driver_status.status){
						if(typeof verification_status.driver_status.op_active_status !== 'undefined'){
							if(!verification_status.driver_status.op_active_status){
								html += verification_status.driver_status.op_active_msg;
								html += "<br>";
							}
						}
						if (typeof verification_status.driver_status.doc_status !== 'undefined'){
							var driver_status = verification_status.driver_status.doc_status;
							html += "<span id='driver_doc_list' class='hide'>Verify Driver Documents</span></br>";
							$.each(driver_status, function (key, value)
							{
								if(value.length != 0){
									$('#driver_doc_list').removeClass('hide');
									html += '<ul>';
									html += "Driver Name: "+value.driver_name+"<br>";
									if (typeof value.doc_list !== 'undefined'){
										var doc_name = value.doc_list;
										html += '<ul>';
										$.each(doc_name, function (key1, value1)
										{
											html += '<li>'+ value1 + '</li>';
										});
										html += '</ul>';
										html += '</ul>';
										html += '</br>';
									}
								}
							});
						}
						else{
							html += verification_status.driver_status.msg;
							html += "<br>";
						}
					}
					else
					{
						
					}
				}
				if (typeof verification_status.vehicle_status !== 'undefined') {
					if (!verification_status.vehicle_status.status){
						if(typeof verification_status.vehicle_status.veh_active_status !== 'undefined'){
							if(!verification_status.vehicle_status.veh_active_status){
								html += verification_status.vehicle_status.veh_active_msg;
								html += "<br>";
							}

						}
						if (typeof verification_status.vehicle_status.doc_status !== 'undefined'){
							var vehicle_status = verification_status.vehicle_status.doc_status;
							html += "<span id='vehicle_doc_list' class='hide'>Verify Vehicle Documents</span></br>";
							$.each(vehicle_status, function (key, value)
							{
								if(value.length != 0){
									$('#vehicle_doc_list').removeClass('hide');
									html += '<ul>';
									html += "Vehicle Registration No: "+value.veh_name+"<br>";
									if (typeof value.doc_list !== 'undefined'){
										var doc_name = value.doc_list;
										html += '<ul>';
										$.each(doc_name, function (key1, value1)
										{
											html += '<li>'+ value1 + '</li>';
										});
										html += '</ul>';
										html += '</ul>';
										html += '</br>';
									}
								}
							});
						}
						else{
							html += verification_status.vehicle_status.msg;
							html += "<br>";
						}
					}
					else{
						//
					}
				}
				if (typeof verification_status.bank_status !== 'undefined') {
					if(!(verification_status.bank_status.status)){
						html += verification_status.bank_status.msg;
						html += "<br>";
					}
				}

				$("#show_verification_error").html(html);

				$('#op_verification_modal').modal('show');
			}
			else{
				//all details are verified
			}
		});

		//additonal doc dynamic add div for business doc
		var i = 0;
		$('#add_more_business_doc').click(function(){
			$('#business_doc').append('<div id="row'+i+'" class="upload"><label>Upload More Documents (Optional)</label><div class="form-group m-form__group row"><div class="col-sm-6"><div class="f-half"><select id="select'+i+'" name="additional_documents['+i+'][doc_type_id]" class="select-2 dropdown-list-style form-control file_type select_documents fillItem" preview-name="Selected Documents"><option value="" disabled selected="selected">Select Document</option>@if(!empty($businessAdditionalDoc))	@foreach ($businessAdditionalDoc as $documents)<option class="" value="{{$documents["doc_type_id"]}}">{{$documents["doc_label"]}}</option> @endforeach  @endif</select></div><div class="f-half"><input class="form-control" type="" name="additional_documents['+i+'][doc_number]"></div></div><div class="col-sm-6"><div class="input-group"><input id="lic_validity_'+p_i+'" type="text" class="form-control date-picker" name="additional_documents['+i+'][doc_expiry]" autofocus><div class="input-group-addon calender"><i class="fa fa-calendar"></i></div><div class="f-half"><input class="form-control p-0" type="file" name="additional_documents['+i+'][doc_images]"></div><div class="f-half"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm r-doc-btn">Remove</button></div></div></div><div class="input-group-append"><div id="selectDoc'+i+'"></div></div></div>');
			i++;

			$(document).on('click', '.btn_remove', function(){
				var button_id = $(this).attr("id");
				$('#row'+button_id).remove();
			});
		});
		//end div

		//additonal doc dynamic add div for business doc
		var p_i = 0;
		$('#add_more_personal_doc').click(function(){
			$('#personal_add_doc').append('<div id="row'+p_i+'" class="upload"><label>Upload More Documents (Optional)</label><div class="form-group m-form__group row"><div class="col-sm-6"><div class="f-half"><select id="select'+p_i+'" name="additional_documents['+p_i+'][doc_type_id]" class="select-2 dropdown-list-style form-control file_type select_documents fillItem" preview-name="Selected Documents"><option value="" disabled selected="selected">Select Document</option>@if(!empty($driverAdditionalDoc))	@foreach ($driverAdditionalDoc as $documents)<option class="" value="{{$documents["doc_type_id"]}}">{{$documents["doc_label"]}}</option> @endforeach  @endif</select></div><div class="f-half"><input class="form-control" type="" name="additional_documents['+p_i+'][doc_number]"></div></div><div class="col-sm-6"><div class="input-group"><input id="lic_validity_'+p_i+'" type="text" class="form-control date-picker" name="additional_documents['+p_i+'][doc_expiry]" autofocus><div class="input-group-addon calender"><i class="fa fa-calendar"></i></div><div class="f-half"><input class="form-control p-0" type="file" name="additional_documents['+p_i+'][doc_images]"></div><div class="f-half"><button type="button" name="remove" id="'+p_i+'" class="btn btn-danger btn_remove btn-sm r-doc-btn">Remove</button></div></div></div><div class="input-group-append"><div id="selectDoc'+p_i+'"></div></div></div>');
			p_i++;

			$(document).on('click', '.btn_remove', function(){
				var button_id = $(this).attr("id");
				$('#row'+button_id).remove();
			});
		});
		//end div

		//set gstn 
		var gstn_available = $("input[name='op_bu_gstn_available']:checked").val();
		setGSTNInput(gstn_available);

		$('body').on('click','.gstn_availability' ,function()
		{
			var gstn_available = $(this).val();
			setGSTNInput(gstn_available);
		});
		//end gstn
	});
	
	function setGSTNInput(gstn_available){
		var gstn_available = $("input[name='op_bu_gstn_available']:checked").val();
		var div = $('#gstn_no');
		var gstn_no_id = div.find('#op_bu_gstn_no');
		if(gstn_available == 'yes'){
			div.removeClass('hide');
			document.getElementById("op_bu_gstn_no").required = true;
		}
		else{
			div.addClass('hide');
			gstn_no_id.val('');
			document.getElementById("op_bu_gstn_no").required = false;
		}
	}

	function showdriverimage(path)
	{
		if(path == ''){
			path = '<?php echo $operator->op_profile_pic; ?>';
			$("#show_driver_image").html(" ");
			var html = '';
			
			//html += '<img class="driver-image" src = "data:image/png;base64,'+path+'">';
			html += '<img class="driver-image" src = "{{ asset("images")}}/'+path+'">';
			
			$("#show_driver_image").append(html);
		}
		else{
			$.ajax({
					url :"{{ route('getdriverimage') }}",
					method:"POST",
					data: {
						"_token": "{{ csrf_token() }}",
						"img_path": path
					},
					success : function(data){
						$("#show_driver_image").html(" ");
						var html = '';
						if(data!="")
						{
							html += '<img class="driver-image" src = "data:image/png;base64,'+data+'">';
						}
						$("#show_driver_image").append(html);
					}
				});
		}
	}

	function showimage(path)
	{
		
		$("#show_docuemnt_image").html(" ");
		var html1 = '';
		if(path!="")
		{ 
			// html1 +='<img class="docuemnt-image" src = "data:image/png;base64,'+path+'">';
			html1 +='<img class="docuemnt-image" src = "{{ asset("images")}}/'+path+'">';
		}        
		$("#show_docuemnt_image").append(html1);
	}

	function showVehimage(images)
	{
		$("#driver_veh_image").html(" ");
		var html2 = '';
		
		if(images !== null)
		{
			var data = JSON.parse(images);
			$.each(data, function (key, value)
			{
				html2 += '<img class="veh-image" src = "{{ asset("images")}}/'+value+'">';
				// html2 += '<img class="veh-image" src = "data:image/png;base64,'+value+'">';
			});
			
			$("#driver_veh_image").append(html2);
		}
	}

	function getaddress() 
	{  
		var op_address_pin_code=$("#op_address_pin_code").val();  
		{
			$.ajax({
				url :"{{ route('getcitystate') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"op_address_pin_code": op_address_pin_code
				},
				success : function(data){
					$('#op_address_city').html("");
					$('#op_address_state').html("");
					$('#op_address_city').append('<option value="'+ data[0].id +'">' + data[0].city + '</option>');
					$("#op_address_state").append('<option value="'+ data[0].state_id +'">' + data[0].state + '</option>');
				}
			});
		}  
	}

	function getBuAddress() 
	{  
		var op_bu_address_pin_code=$("#op_bu_address_pin_code").val();  
		{
			$.ajax({
				url :"{{ route('getcitystate') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"op_address_pin_code": op_bu_address_pin_code
				},
				success : function(data){
					$('#op_bu_address_city').html("");
					$('#op_bu_address_state').html("");
					$('#op_bu_address_city').append('<option value="'+ data[0].id +'">' + data[0].city + '</option>');
					$("#op_bu_address_state").append('<option value="'+ data[0].state_id +'">' + data[0].state + '</option>');
					 console.log(data);
				}
			});
		}  
	}

	function deletedriver(id)
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
				url :"{{ route('deletedriver') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					swal({title: "Deleted!", text: "Your file has been deleted.", type: "success"}).then(function()
					{ 
							location.reload();
					});
				}
			});
		})
	}

	function deleteoperatorvehicle(id)
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
				url :"{{ route('deleteoperatorvehicle') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					swal({title: "Deleted!", text: "Your file has been deleted.", type: "success"}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
	}

	function deletedocument(id)
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
				url :"{{ route('deletedocument') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					swal({title: "Deleted!", text: "Your file has been deleted.", type: "success"}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
	}

	function verify_doc(id)
	{
		swal({
			title: 'Are you sure?',
			text: "Verify this document!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Verify'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('verify-doc') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.response){
						swal({title: "Verified!", text: "Your file has been verified.", type: result.status}).then(function()
						{ 
							location.reload();
						});
					}
					else{
						swal({title: "Oops!", text: "Something went wrong.", type: result.status}).then(function()
						{
							location.reload();
						});
					}
				}
			});
		})
	}

	function verify_bank(op_id)
	{
		swal({
			title: 'Are you sure?',
			text: "Verify bank details!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Verify'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('verify-op-bank-details') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": op_id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Verified!", text: result.response, type: result.status}).then(function()
						{ 
							location.reload();
						});
					}
					else{
						swal({title: "Oops!", text: result.response, type: 'error'}).then(function()
						{
							location.reload();
						});
					}
				}
			});
		})
	}
	
	function preview_profile_pic() 
	{
		$('#view_profile_img').html('');
		var output = document.getElementById("op_profile_pic");
		var total_file = document.getElementById("op_profile_pic").files.length;
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

	function preview_lic() 
	{
		$('#view_lic_img').html('');
		var output = document.getElementById("lic_image");
		var total_file = document.getElementById("lic_image").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#edit_lic_image_div').hide()
			$('#lic_image_div').show()
			$('#view_lic_img').append("<img src='"+output.src+"' class='veh-image'>");
		}else{
			$('#edit_lic_image_div').show()
			$('#lic_image_div').hide()
			$('#view_lic_img').html('');
		}
	}
	
	function preview_pan() 
	{
		$('#view_pan_img').html('');
		var output = document.getElementById("pan_image");
		var total_file = document.getElementById("pan_image").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#edit_pan_image_div').hide();
			$('#pan_image_div').show();
			$('#view_pan_img').append("<img src='"+output.src+"' class='veh-image'>");
		}else{
			$('#edit_pan_image_div').show();
			$('#pan_image_div').hide();
			$('#view_pan_img').html('');
		}
	}

	function preview_bupan(){
		$('#view_bu_pan_img').html('');
		var output = document.getElementById("bu_pan_image");
		var total_file = document.getElementById("bu_pan_image").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		console.log(total_file);
		if(total_file > 0){
			console.log('show');
			$('#edit_bupan_image_div').hide();
			$('#bupan_image_div').show()
			$('#view_bu_pan_img').append("<img src='"+output.src+"' class='veh-image'>");
		}else{
			console.log('hid');
			$('#edit_bupan_image_div').show();
			$('#bupan_image_div').hide()
			$('#view_bu_pan_img').html('');
		}	
	}

	function preview_doc_pic(){
        $('#view_doc_img').html('');
        var output = document.getElementById("op_document_image");
        var total_file = document.getElementById("op_document_image").files.length;
        output.src = URL.createObjectURL(event.target.files[0]);
        if(total_file > 0){
            $('#edit_doc_images_div').hide();
            $('#doc_images_div').show()
            $('#view_doc_img').append("<img src='"+output.src+"'class='veh-image'>");
        }else{
            $('#edit_doc_images_div').show();
            $('#doc_images_div').hide()
            $('#view_doc_img').html('');
        }   
    }

	
	$(document).ready(function () {
		
		//personal form client side validations
		$("#editPersonal").validate({
			rules: {
				op_first_name: {
					alpha: true,
					required: true,
				},
				op_last_name: {
					alpha: true,
					required: true,
				},
				op_dob: {
					required: true,
				},
				op_gender: {
					required: true,
				},
				op_email: {
					email_pattern: true,
				},
				op_pet_name: {
					alpha: true,
				},
				op_alternative_mobile_no: {
					number: true,
					maxlength: 10,
					minlength: 10
				},
				op_address_pin_code: {
					required: true,
				},
				op_address_state: {
					required: true,
				},
				op_address_city: {
					required: true,
				},
				op_address_line_1: {
					required: true,
					alpha_numbers: true,
				},
				op_address_line_2: {
					alpha_numbers: true,
				},
				op_address_line_3: {
					alpha_numbers: true,	
				},
				op_landmark: {
					alpha_numbers:true,	
				},
				"driving_license_doc[lic_number]": {
					required: true,
				},
				"driving_license_doc[lic_validity]": {
					required: true,
				},
				"driving_license_doc[lic_image]" :{
					required: {
                        depends: function (){ 
                            var edit_lic_image = '{{ $operator->lic_image }}';
                            var filesArray = document.getElementById("lic_image").files;
                            if( (edit_lic_image!='' && edit_lic_image!=null )&& filesArray.length==0){
                                    return false;
                            }
                            if((edit_lic_image=='' || edit_lic_image==null ) && filesArray.length==0){
                                    return true;
                            }
                        }
                    },
				},
				"pan_doc[pan_number]": {
					required: {
                        depends: function (){ 
                        	var edit_pan_image = '{{ $operator->pan_image }}';
                            var filesArray = document.getElementById("pan_image").files;
                            var pan_no = $("#pan_number").val();
                            if(pan_no==null || pan_no == ''){
                            	if((edit_pan_image!='' && edit_pan_image!=null ) || filesArray.length!=0){
	                                // console.log('edit image is present');
	                                    return true;
	                            }
	                            if((edit_pan_image=='' || edit_pan_image==null ) && filesArray.length==0){
	                                    // console.log('no images uploaded');
	                                    return false;
	                            }
                            }
                            else{
                            	return false;
                            }
                        }
                    },
                    pan_pattern : true,
				},
				"pan_doc[pan_image]" :{
					required: {
                        depends: function (){ 
                    	 	var edit_pan_image = '{{ $operator->pan_image }}';
                            var filesArray = document.getElementById("pan_image").files;
                             var pan_no = $("#pan_number").val();
                            if(pan_no!=null && pan_no != ''){
                                // console.log('pan no is empty');
                                if( (edit_pan_image!='' && edit_pan_image!=null ) || filesArray.length!=0){
                                        return false;
                                    }
                                    if((edit_pan_image=='' || edit_pan_image==null ) && filesArray.length==0){
                                        // console.log('pan img is empty');
                                        return true;
                                    }
                            }else{
                                // console.log('pan img else');
                                return false;
                            }
                        }
                    },
				},
				op_profile_pic: {
					required: {
                        depends: function (){ 
                            var edit_profile_image = '{{ $operator->op_profile_pic }}';
                            var filesArray = document.getElementById("op_profile_pic").files;
                            if( (edit_profile_image!='' && edit_profile_image!=null )&& filesArray.length==0){
                                    return false;
                            }
                            if((edit_profile_image=='' || edit_profile_image==null ) && filesArray.length==0){
                                    return true;
                            }
                        }
                    },
				},
			},
			messages: {
				op_first_name: {
					required:"Please enter first name",
					alpha:"Please enter valid name",
				},
				op_last_name: {
					required:"Please enter last name",
					alpha:"Please enter valid name",
				},
				op_dob: {
					required:"Please select date of birth",
				},
				op_gender: {
					required:"Please select gender",
				},
				op_email: {
					email_pattern:"Please enter valid email address",
				},
				op_pet_name: {
					alpha:"Please enter valid name",
				},
				op_alternative_mobile_no: {
					number:"Please enter valid mobile number",
					maxlength:"Please enter valid mobile number",
					minlength:"Please enter valid mobile number",
				},
				op_address_pin_code: {
					required:"Please select pin code",
				},
				op_address_state: {
					required:"Please select state",
				},
				op_address_city: {
					required:"Please select city",
				},
				op_address_line_1: {
					required:"Please enter flat/shop/place",
					alpha_numbers:"Please enter valid address",
				},
				op_address_line_2: {
					alpha_numbers:"Please enter valid street or area",
				},
				op_address_line_3: {
					alpha_numbers:"Please enter valid location",
				},
				op_landmark: {
					alpha_numbers:"Please enter valid landmark",	
				},
				"driving_license_doc[lic_number]": {
					required:"Please enter license number",
				},
				"driving_license_doc[lic_validity]": {
					required:"Please select license validity",
				},
				"driving_license_doc[lic_image]": {
					required:"Please upload license",
				},
				"pan_doc[pan_number]": {
					required:"Please enter pan number",
					pan_pattern:"Please enter valid pan number",
				},
				"pan_doc[pan_image]": {
					required:"Please upload pan",
				},
			 	op_profile_pic:{
                    required:"Please upload profile picture",
                }, 
			},
			invalidHandler: function(event, validator) {
				// console.log(event);
				// console.log(validator);
			},		
		});
		//end here

		//business form client side validations
		$("#editBusiness").validate({
			rules: {
				op_bu_name: {
					required: true,
					alpha_numbers: true,
				},
				op_bu_email: {
					email_pattern: true,
				},
				op_payment_mode: {
					required: true,
				},
				op_bu_address_line_1: {
					required: true,
					alpha_numbers: true,
				},
				op_bu_address_line_2: {
					alpha_numbers: true,
				},
				op_bu_address_line_3: {
					required: true,
					alpha_numbers: true,
				},
				op_bu_landmark: {
					required: true,
					alpha_numbers: true,
				},
				op_bu_address_pin_code: {
					required: true,
				},
				op_bu_address_state: {
					required: true,
				},
				op_bu_address_city: {
					required: true,
				},
				op_bu_pan_no: {
					//required: true,
					pan_pattern:true,
				},
				op_bu_gstn_no: {
					required: true,
				},
				bu_pan_image: {
					/*required: {
                        depends: function (){ 
                            var edit_bupan_image = '{{ $operator->op_bu_pan_no_doc }}';
                            var filesArray = document.getElementById("bu_pan_image").files;
                            if( (edit_bupan_image!='' && edit_bupan_image!=null )&& filesArray.length==0){
                                    return false;
                            }
                            if((edit_bupan_image=='' || edit_bupan_image==null ) && filesArray.length==0){
                                    return true;
                            }
                        }
                    },*/
				},
			},
			messages: {
				op_bu_name: {
					required:"Please enter business name",
					alpha_numbers:"Please enter valid business name"
				},
				op_bu_email: {
					email_pattern:"Please enter valid email address"
				},
				op_payment_mode: {
					required:"Please select payment mode",
				},
				op_bu_address_line_1: {
					required:"Please enter flat/shop/place",
					alpha_numbers:"Please enter valid flat/shop/place"
				},
				op_bu_address_line_2: {
					alpha_numbers:"Please enter valid street/area",
				},
				op_bu_address_line_3: {
					required:"Please enter location",
					alpha_numbers:"Please enter valid location"
				},
				op_bu_landmark: {
					required:"Please enter landmark",
					alpha_numbers:"Please enter valid landmark"
				},
				op_bu_address_pin_code: {	
					required:"Please select pin code",
				},
				op_bu_address_state: {
					required:"Please select state",
				},
				op_bu_address_city: {
					required:"Please select city",
				},
				op_bu_pan_no: {
					//required:"Please enter pan number",
					pan_pattern:"Please enter valid pan number",
				},
				op_bu_gstn_no: {
					required:"Please enter GSTN",
				},
				bu_pan_image: {
					//required:"Please upload pan",
				},
			},
			invalidHandler: function(event, validator) {
				// console.log(event);
				// console.log(validator);
			},		
		});
		//end here

		//jquery custom methods
		$.validator.addMethod("alpha_numbers", function(value, element) 
		{
			return this.optional(element) || /^\d*[a-zA-Z]{1,}\d*/.test(value);
		});

		$.validator.addMethod("alpha", function(value, element)
    	{
        	return this.optional(element) || /^([\s\.\s\]?[a-zA-Z]+)+$/.test(value);
    	});

    	jQuery.validator.addMethod("email_pattern", function(value, element) {
            return this.optional( element ) || /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test( value );
        });
    	
    	// $.validator.addMethod("email_pattern", function(value, element)
    	// {
     //    	return this.optional(element) || /^(?=.{1,254}$)(?=.{1,64}@)[-!#$%&'*+/0-9=?A-Z^_`a-z{|}~]+(\.[-!#$%&'*+/0-9=?A-Z^_`a-z{|}~]+)*@[A-Za-z0-9]([A-Za-z0-9-]{0,61}[A-Za-z0-9])?(\.[A-Za-z0-9]([A-Za-z0-9-]{0,61}[A-Za-z0-9])?)*$/.test(value);
    	// });
    	$.validator.addMethod("pan_pattern", function(value, element)
    	{
        	if(value!=='' || value !=null){
        		// console.log(this.optional(element) || /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/.test(value));
        		return this.optional(element) || /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/.test(value);
        	}else{
        		return true;
        	}
    	});
    	

  //   	$.validator.addMethod("remove_space", function(value, element) 
		// {
		// 	 var val = /^\s+|\s+$/.test(value);
		// 	 console.log('remove space');
		// 	 console.log(val);
		// 	 if(val==true){
		// 	 	value = $.trim(value);
		// 	 	return false;
		// 	 }else{
		// 	 	return true;
		// 	 }
			 
		// });
		//end

		//if pan entered then validation
		// $('#pan_number').keyup(function() {
		// 	var panVal = $('#pan_number').val();
		// 	var regpan = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/;

		// 	if(regpan.test(panVal)){
		// 	   console.log('correct');
		// 	   $('#pan-error-msg').empty();
		// 	} else {
		// 	   console.log('incorrect');
		// 	   $('#pan-error-msg').html('PAN is invalid')
		// 	}
		// });

        var edit_lic_image = '{{ $operator['lic_image'] }}';
        // add lic-doc-img validation
        jQuery.validator.addMethod("check_lic_image_count", function(value, element) {
                var filesArray = document.getElementById("lic_image").files;
                if(edit_lic_image!=null){
                        if(filesArray.length==0){
                                return true;
                        }
                        if(filesArray.length > 1){
                                return false;
                        }
                        
                console.log('not null');
                        return true;
                }
                else{
                        return false;
                }
        // lic-doc-img validation end
        });
      
	});
</script>
@endsection



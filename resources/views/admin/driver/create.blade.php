<style type="text/css">
	.error{
		color: red;
	}
</style>
@extends('layouts.app')
@section('content-header')
	<h1>Driver Information</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Driver</li>
		<li class="active">Create</li>
	</ol>
@endsection

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
				<form method="POST" id="myDriverForm" action="{{ route('Driver.store') }}" enctype="multipart/form-data">  
					@csrf
					<div class="row">
						<div class="col-sm-12 form-group section-title">Add Driver</div>
						<div class="section">
							<?php $operator_id = Request::get('op'); ?>
							<?php $op_type = Request::get('op_type'); ?>
							<div class="row">
								<div class="col-sm-6 form-group">
									<input id="operator_id" type="hidden" class="form-control" name="operator_id" value="{{$operator_id}}" required autofocus>					
									<div class="f-half">
										<label for="driver_first_name" class="control-label">{{ __('First Name*') }}</label>						
										<input id="driver_first_name" type="text" class="form-control" name="driver_first_name" value="" required autofocus >						
										<p class="help-block"></p>
										@if($errors->has('driver_first_name'))
											<p class="help-block">
												<div class="error">Please enter driver first name</div>
											</p>
										@endif
									</div>
									<!-- <div class="col-xs-6 form-group">
										<label for="driver_last_name" class="control-label">{{ __('Last Name*') }}</label>
										<input id="driver_last_name" type="text" class="form-control" name="driver_last_name" value=""  required autofocus>
										<p class="help-block"></p>
										@if($errors->has('driver_last_name'))
											<p class="help-block">
												{{ $errors->first('driver_last_name') }}
											</p>
										@endif
									</div> -->
									<div class="f-half">
										<label for="driver_mobile_number" class="control-label">{{ __('Mobile Number*') }}</label>
										<input id="driver_mobile_number" type="text" class="form-control" name="driver_mobile_number" value="" required autofocus pattern="[1-9]{1}[0-9]{9}"   title="The mobile number must be 10 digits.">
										<p class="help-block"></p>
										@if($errors->has('driver_mobile_number'))
											<p class="help-block">
												<div class="error">Please enter driver mobile number</div>
											</p>
										@endif
									</div>
								</div>
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<label for="driver_on_off" class="control-label">{{ __('Is Operator Online') }}</label>
										<div class="row">
											<div class="on-radio">
												<input id="driver_is_online_yes" type="radio" class="is_driver_online" name="driver_is_online" type="radio"  value="true" checked="true" required autofocus >
												<span>Yes</span>
											</div>
											<div class="on-radio">						
												<input id="driver_is_online_no" type="radio" class="is_driver_online" name="driver_is_online" type="radio" value="false" required autofocus>
												<span>No</span>
											</div>
										</div>
										<p class="help-block"></p>
										@if($errors->has('driver_is_online'))
											<p class="help-block">
												<div class="error">Please select online status</div>
											</p>
										@endif
									</div>
									<div class="f-half hide" id="offline_hrs">
										<label for="driver_offline_hrs" class="control-label">{{ __('Select Offline Hours*') }}</label>
										<select id="driver_offline_hrs" type="text" class="form-control" value=""  name="driver_offline_hrs" autofocus>
											<option value="">Select Offline Hours </option>
											@foreach($opOfflineHrs as $offlineHrs)
												<option value="{{ $offlineHrs['time'] }}">{{ $offlineHrs['time'] }}</option>  
											@endforeach
										</select>
										<p class="help-block"></p>
										@if($errors->has('driver_offline_hrs'))
											<p class="help-block">
												{{ $errors->first('driver_offline_hrs') }}
											</p>
										@endif
									</div>
								</div>
							</div>
							<div class="row first-line">
								<div class="col-xs-6 form-group">                
									<label for="working_shift_days" class="control-label col-sm-12 p-0">{{ __(' Working Days*') }}</label> 
									<div class="row">
										<div class="w-d-check">                  
											<input id="Mon" type="checkbox" name="working_shift_days[]" value="Mon"><span>Mon</span>
										</div> 
										<div class="w-d-check">
											<input id="Tue" type="checkbox" name="working_shift_days[]" value="Tue"><span> Tue</span>
										</div class="w-d-check">
										<div class="w-d-check">
											<input id="Wed" type="checkbox" name="working_shift_days[]" value="Wed"><span> Wed</span>
										</div>
										<div class="w-d-check">
											<input id="Thu" type="checkbox" name="working_shift_days[]" value="Thu"><span> Thu</span>
										</div>
										<div class="w-d-check">
											<input id="Fri" type="checkbox" name="working_shift_days[]" value="Fri"><span> Fri</span>
										</div>
										<div class="w-d-check">
											<input id="Sat" type="checkbox" name="working_shift_days[]" value="Sat"><span> Sat</span>
										</div>
										<div class="w-d-check">
											<input id="Sun" type="checkbox" name="working_shift_days[]" value="Sun"><span> Sun</span>
										</div>
									</div>
									<label id="working_shift_days[]-error" class="error" for="working_shift_days[]"></label>
									<p class="help-block"></p>
									@if($errors->has('working_shift_days'))
										<p class="help-block">
											<div class="error">Please select working days</div>
										</p>
									@endif
								</div>
								<div class="col-xs-6 form-group">
									<label for="working_shift_time" class="control-label col-sm-12 p-0">{{ __(' Working Shifts*') }}</label>
									<div class="row">
										<div class="w-t-check">
											<input id="Shift 1 (6AM to 2PM)" type="checkbox" name="working_shift_time[]" value="Shift 1 (6AM to 2PM)"><span>Shift 1 (6AM to 2PM)</span>
										</div>
										<div class="w-t-check">
											<input id="Shift 2 (2PM to 10 PM)" type="checkbox" name="working_shift_time[]" value="Shift 2 (2PM to 10 PM)"><span>Shift 2 (2PM to 10 PM)</span>
										</div>
										<div class="w-t-check">
											<input id="Shift 3 (10PM to 6AM)" type="checkbox" name="working_shift_time[]" value="Shift 3 (10PM to 6AM)"><span>Shift 3 (10PM to 6AM)</span>
										</div>
									</div>
									<label id="working_shift_time[]-error" class="error" for="working_shift_time[]"></label>
									<p class="help-block"></p>
									@if($errors->has('working_shift_time'))
										<p class="help-block">
											<div class="error">Please select working shifts</div>
										</p>
									@endif
								</div>
							</div>
							<div class="row first-line">
								<div class="col-xs-6 form-group">
									<label for="driver_profile_pic" class="control-label">{{ __('Upload Profile Photo*') }}</label>
									<input id="driver_profile_pic" type="file" class="form-control p-0" name="driver_profile_pic" value=""  autofocus required>
									<p class="help-block"></p>
									@if($errors->has('driver_profile_pic'))
										<p class="help-block">
											{{ $errors->first('driver_profile_pic') }}
										</p>
									@endif
								</div> 
							</div>
							<div class="add-block">
								@if($op_type == 2)<!-- upload doc if individual operator -->
									<h4>Documents</h4>
									<div class="row">
										<div class="col-xs-6 form-group">
											<div class="f-half">
												<input type="hidden" name="driving_license_doc[doc_type_id]" id="lic_type_id" value="2">
												<label for="lic_number" class="control-label">{{ __('Driving License No*') }}</label>
												<input id="lic_number" type="text" class="form-control" name="driving_license_doc[lic_number]" value=""  autofocus required>
												<p class="help-block-message"></p>
												@if($errors->has('lic_number'))
													<p class="help-block-message">
														<div class="error">Please enter driving license Number</div>
													</p>
												@endif
											</div>
											<div class="f-half">
												<label for="lic_validity" class="control-label">{{ __('Validity*') }}</label>
												<div class="input-group">
													<input id="lic_validity" type="text" class="form-control date-picker" name="driving_license_doc[lic_validity]" data-provide="datepicker" required autofocus>
													<div class="input-group-addon calender">
														<i class="fa fa-calendar"></i>
													</div>
												</div>
												<label id="lic_validity-error" class="error" for="lic_validity"></label>												
												<p class="help-block-message"></p>
												@if($errors->has('lic_validity'))
													<p class="help-block-message">
														<div class="error">Please select license validity</div>
													</p>
												@endif
											</div>
										</div>
										<div class="col-xs-6 form-group">
											<div class="f-half">
												<label for="lic_image" class="control-label">{{ __('Upload Driving License*') }}</label>
												<input id="lic_image" type="file" class="form-control p-0" name="driving_license_doc[lic_image]" value="" required autofocus>
												<p class="help-block-message"></p>
												@if($errors->has('lic_image'))
													<p class="help-block-message">
														{{ $errors->first('lic_image') }}
													</p>
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 form-group">
											<div class="f-half">
												<input type="hidden" name="pan_doc[doc_type_id]" id="pan_type_id" value="1">
												<label for="pan_number" class="control-label">{{ __('PAN No') }}</label>
												<input id="pan_number" type="text" class="form-control" name="pan_doc[pan_number]" value=""  autofocus>
												<p class="help-block-message"></p>
												@if($errors->has('pan_number'))
													<p class="help-block-message">
														{{ $errors->first('pan_number') }}
													</p>
												@endif
											</div>
											<div class="f-half">
												<label for="pan_image" class="control-label">{{ __('Upload PAN') }}</label>
												<input id="pan_image" type="file" class="form-control p-0" name="pan_doc[pan_image]" value=""  autofocus>
												<p class="help-block-message"></p>
												@if($errors->has('pan_image'))
													<p class="help-block-message">
														{{ $errors->first('pan_image') }}
													</p>
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group">
											<div id="add_more_doc" class="form-group m-form__group"></div>
											<div class="col-sm-3 m--margin-bottom-15">
												<button type="button" name="add" id="add_more_driver_doc" class="addMorePadding btn btn-sm btn-info m-l-5"><span><i class="la la-plus"></i>
													</span>Additional Documents</button>
											</div>
										</div>
									</div>
								@endif
							</div>
							<div class="row m-10">
								<div class="col-xs-6 form-group p-0">
									<div class="row">
										<div class="col-xs-2">
											 <label for="is_active" class="control-label">{{ __('Status*') }}</label>
										</div>									
										<div class="col-xs-3">
											<input id="is_active" type="radio" name="is_active" type="radio" checked value="1" required autofocus ><span class="st-radio">Active</span>
										</div>									
										<div class="col-xs-3">
											<input id="is_active" type="radio"  name="is_active" type="radio"  value="0" required autofocus><span class="st-radio">Inactive</span>
										</div>
									</div>
									<label id="is_active-error" class="error col-sm-12" for="is_active"></label>
									<p class="help-block"></p>
									@if($errors->has('is_active'))
										<p class="help-block">
											{{ $errors->first('is_active') }}
										</p>
									@endif
								</div>
							</div>
							<div class="row first-line">
								<div class="col-xs-12 form-group">
									<input id="driver_is_verified"  type="checkbox" name="driver_is_verified" autofocus>

									<label for="" class="control-label">{{ __('Verified') }}</label>
								  
									<p class="help-block"></p>
									@if($errors->has('driver_is_verified'))
										<p class="help-block">
											{{ $errors->first('driver_is_verified') }}
										</p>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 form-group">
									<div class="btn-b-u">
										<a href="{{ URL::previous()}}" class="btn btn-warning">Back</a>
										<button type="submit" class="btn btn-success">
											{{ __('Save') }}
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>		
	@endif
@endsection

<!-- JS scripts for this page only -->


@section('javascript')
<script type="text/javascript">

	$(document).ready(function() {
		$('body').on('click','.is_driver_online' ,function()
		{
			var is_online = $(this).val();
			var valueDiv = $('#driver_offline_hrs');
			if(is_online == 'true'){
				$('#offline_hrs').addClass('hide');
				$('#driver_offline_hrs').val('');
				document.getElementById("driver_offline_hrs").required = false;
				$("#driver_offline_hrs").rules("add", {
					required: true,
					messages: {
						required: "Please enter offline hours",
					}
				});
			}
			else{
				$('#offline_hrs').removeClass('hide');
				document.getElementById("driver_offline_hrs").required = true;
				$('#driver_offline_hrs').rules("remove",'required');
			}
		});

		//additional document create synamic input-nayana
		var p_i = 0;
		$('#add_more_driver_doc').click(function(){
			$('#add_more_doc').append('<div id="row'+p_i+'" class="upload"><label>Upload More Documents (Optional)</label><div class="form-group m-form__group row"><div class="col-sm-6"><div class="f-half"><select id="select'+p_i+'" name="additional_documents['+p_i+'][doc_type_id]" class="select-2 dropdown-list-style form-control file_type select_documents fillItem" preview-name="Selected Documents"><option value="" disabled selected="selected">Select Document</option>@if(!empty($additionalDocList))	@foreach ($additionalDocList as $documents)<option class="" value="{{$documents["doc_type_id"]}}">{{$documents["doc_label"]}}</option> @endforeach  @endif	</select></div><div class="f-half"><input class="form-control" type="" name="additional_documents['+p_i+'][doc_number]"></div></div><div class="col-sm-6"><div class="f-half"><div class="input-group"><input id="lic_validity_'+p_i+'" type="text" class="form-control date-picker" name="additional_documents['+p_i+'][doc_expiry]" autofocus><div class="input-group-addon calender"><i class="fa fa-calendar"></i></div></div></div><div class="f-half v-lic"><input class="form-control p-0 in-file-w" type="file" name="additional_documents['+p_i+'][doc_images]"><!--<button type="button" name="remove" id="'+p_i+'" class="btn btn-info btn-sm eye-btn"><i class="fa fa-eye"></i></button>--><button type="button" name="remove" id="'+p_i+'" class="btn btn-danger btn_remove btn-sm r-doc-btn">Remove</button></div></div></div><div class="input-group-append"><div id="selectDoc'+p_i+'"></div></div></div>');
			p_i++;

			$(document).on('click', '.btn_remove', function(){
				var button_id = $(this).attr("id");
				$('#row'+button_id).remove();
			});
		});
		//end-additional doc

		//dropdown list manage code
		// $('.select-2').on('change', function(e){
		// 	console.log(this.value,
		// 	this.options[this.selectedIndex].value,
		// 	$(this).find("option:selected").val(),);
		// });
		$( "#myDriverForm" ).validate({
			rules: {
				driver_first_name: {
					required: true,
					alpha: true,
				},
				driver_mobile_number: {
					required: true,
					number: true,
					maxlength: 10,
					minlength: 10
				},
				driver_is_online_yes: {
					required: true,
				},
				'working_shift_days[]': {
					required: true,
				},
				'working_shift_time[]': {
					required: true,
				},
				'driving_license_doc[lic_number]': {
					required: true,
				},
				'driving_license_doc[lic_validity]': {
					required: true,
				},
				'driving_license_doc[lic_image]': {
					required: true,
				},
				'driver_profile_pic': {
					required: true,
				},
				'is_active': {
					required: true,
				},
				'driver_offline_hrs': {
					required: true,
				},
				'pan_doc[pan_number]': {
					pan_pattern: true,
					required: {
						depends: function (){ 
							var filesArray = document.getElementById("pan_image").files;
							var pan_no = $("#pan_number").val();
							if(pan_no==null || pan_no == ''){
								if(filesArray.length!=0){
										return true;
								}
								if(filesArray.length==0){
										return false;
								}
							}
							else{
								return false;
							}
						}
					},
				},
				'pan_doc[pan_image]': {
					required: {
						depends: function (){ 
							var filesArray = document.getElementById("pan_image").files;
							var pan_no = $("#pan_number").val();
							if(pan_no!=null && pan_no != ''){
								// console.log('pan no is empty');
								if(filesArray.length!=0){
										return false;
								}
								else if(filesArray.length==0){
									// console.log('pan img is empty');
									return true;
								}
							}else{
								// console.log('pan img else');
								return false;
							}
						}
					},
				}
			},
			messages: {
				driver_first_name: {
					required: "Please enter driver first name",
					alpha: "Please enter valid name",
				},
				driver_mobile_number: {
					required: "Please enter driver mobile number",
					number:"Please enter valid mobile number",
					maxlength:"Please enter valid mobile number",
					minlength:"Please enter valid mobile number",
				},
				driver_is_online_yes: {
					required: 'Please select online status',
				},
				'working_shift_days[]': {
					required: 'Please select working days',
				},
				'working_shift_time[]': {
					required: 'Please select working shifts',
				},
				'driving_license_doc[lic_number]': {
					required: 'Please enter driving license number',
				},
				'driving_license_doc[lic_validity]': {
					required: 'Please select license validity',
				},
				'driving_license_doc[lic_image]': {
					required: 'Please select license image',
				},
				'driver_profile_pic': {
					required: 'Please select profile image',
				},
				'is_active': {
					required: 'Please select status',
				},
				'driver_offline_hrs': {
					required: 'Please select offline hours',
				},
				'pan_doc[pan_number]': {
					pan_pattern: 'Please enter valid pan number',
					required:"Please enter pan number",
				},
				'pan_doc[pan_image]': {
					required:"Please upload pan",
				}
			},
			submitHandler: function(e) {
				var i = $("#myDriverForm").valid();
				i.submit();
			}
		});

		//jquery custom methods
		$.validator.addMethod("alpha_numbers", function(value, element) 
		{
			return this.optional(element) || /^\d*[a-zA-Z]{1,}\d*/.test(value);
		});

		$.validator.addMethod("pan_pattern", function(value, element) 
		{
			return this.optional(element) || /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/.test(value);
		});

		$.validator.addMethod("alpha", function(value, element)
		{
			return this.optional(element) || /^([\s\.\s\]?[a-zA-Z]+)+$/.test(value);
		});

		//end
	});
</script>
@endsection



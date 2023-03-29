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
		<li>Roles</li>
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
			<?php $operator_id = Request::get('op'); ?>
			<?php $op_type = Request::get('op_type'); ?>
			<div class="row">
				<div class="col-sm-12 form-group section-title">Edit Driver</div>
				<div class="section">
					<form method="POST" id="EditDriverForm"  action="{{ route('updatebusiness') }}" enctype="multipart/form-data">  
					@csrf
						<input id="operator_id" type="hidden" class="form-control" name="operator_id" value="{{$operator_id}}" required autofocus>
						<input id="op_type" type="hidden" class="form-control" name="op_type" value="{{$op_type}}" required autofocus>				
						<div class="row">
							<div class="col-sm-6 form-group">
								<div class="f-half">
									<label for="driver_first_name" class="control-label">{{ __('First Name*') }}</label>
									<input id="driver_id" type="hidden" name="driver_id" value="{{ $driver['driver_id'] }}" required>			
									<input id="driver_first_name" type="text" class="form-control" name="driver_first_name" value="{{ $driver['driver_first_name'] }}" required autofocus>								
									<p class="help-block"></p>
									@if($errors->has('driver_first_name'))
										<p class="help-block">
											<div class="error">Please enter driver first name</div>
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="driver_mobile_number" class="control-label">{{ __('Mobile Number*') }}</label>
									<input id="driver_mobile_number" type="text" class="form-control" name="driver_mobile_number" value="{{ $driver['driver_mobile_number'] }}" required autofocus pattern="[1-9]{1}[0-9]{9}" title="The mobile number must be 10 digits." <?php if($op_type == 1) { echo 'readonly'; } ?>>
									<p class="help-block"></p>
									@if($errors->has('driver_mobile_number'))
										<p class="help-block">
											<div class="error">Please enter driver mobile number</div>
										</p>
									@endif
								</div>
							</div>
							<!-- <div class="col-sm-6 form-group">
								<label for="driver_last_name" class="control-label">{{ __('Last Name*') }}</label>
								<input id="driver_last_name" type="text" class="form-control" name="driver_last_name" value="{{ $driver['driver_last_name'] }}"  required autofocus>
								<p class="help-block"></p>
								@if($errors->has('driver_last_name'))
									<p class="help-block">
										{{ $errors->first('driver_last_name') }}
									</p>
								@endif
							</div> -->					
							<div class="col-sm-6 form-group">
								<div class="f-half">
									<label for="driver_profile_pic" class="control-label">{{ __('Is Operator Online') }}</label>
									<div class="row">
										<div class="on-radio">
											<input id="driver_is_online_yes" type="radio" class="is_driver_online" name="driver_is_online" type="radio"  value="true" checked="true" required autofocus {{ $driver['driver_is_online'] == '1' ? 'checked' : '' }} ><span>Yes</span>
										</div>
										<div class="on-radio">										
											<input id="driver_is_online_no" type="radio" class="is_driver_online" name="driver_is_online" type="radio"  value="false" required autofocus {{ $driver['driver_is_online'] == '0' ? 'checked' : '' }}><span>No</span>
										</div>
									</div>
									<p class="help-block"></p>
									@if($errors->has('driver_is_online'))
										<p class="help-block">
											<div class="error">Please select online status</div>
										</p>
									@endif
								</div>
								<div class="f-half" id="offline_hrs">
									<label for="driver_offline_hrs" class="control-label">{{ __('Select Offline Hours*') }}</label>
									<select id="driver_offline_hrs" type="text" class="form-control" value=""  name="driver_offline_hrs" autofocus>
										<option value="">Select Offline Hours </option>
										@foreach($opOfflineHrs as $offlineHrs)
											<option value="{{ $offlineHrs['time'] }}" {{  $driver['driver_offline_hrs'] == $offlineHrs['time'] ? 'selected' : '' }}>{{ $offlineHrs['time'] }}</option>  
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
						<div class="row">
							<div class="col-sm-6 form-group">
								<label for="working_shift_days" class="control-label">{{ __(' Working Days*') }}</label>
								<div class="row">
									<br>
									<?php
									$data = json_decode($driver['working_shift_days'], true);
									?>
								
									<?php
									for($i=0;$i<count($data);$i++)
									{
									?>
									<div class="w-d-check">
										<input id="{{ $data[$i]["name"] }}" type="checkbox" name="working_shift_days[]" value="{{$data[$i]['value'] }}"  autofocus {{ $data[$i]["checked"]==1 ? 'checked' : '' }} ><span>{{ $data[$i]["name"] }}</span>
									</div>
									<?php 
									}
									?>
								</div>
								<label id="working_shift_days[]-error" class="error" for="working_shift_days[]"></label>
								<p class="help-block"></p>
								
								@if($errors->has('working_shift_days'))
									<p class="help-block">
										<div class="error">Please select working days</div>
									</p>
								@endif
								
							</div>
							<div class="col-sm-6 form-group">
								<label for="working_shift_time" class="control-label">{{ __(' Working Shifts*') }}</label>
								<div class="row">
									<br>
									<?php
									$shifts = json_decode($driver['working_shift_time'], true);
									?>

									<?php
									for($i=0;$i<count($shifts);$i++)
									{
									?>
									<div class="w-t-check">
										<input id="{{ $shifts[$i]["name"] }}" type="checkbox" name="working_shift_time[]" value="{{$shifts[$i]['value'] }}"  autofocus {{ $shifts[$i]["checked"]==1 ? 'checked' : '' }} ><span>{{ $shifts[$i]["name"] }}</span>
									</div>
									<?php 
									}
									?> 
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
							<div class="col-sm-6 form-group">
								<label for="driver_profile_pic" class="control-label">{{ __('Upload Profile Photo') }}</label>
								<input id="driver_profile_pic" type="file" class="form-control p-0" name="driver_profile_pic" value="{{ $driver['driver_profile_pic'] }}" autofocus>
								<p class="help-block"></p>
								@if($errors->has('driver_profile_pic'))
									<p class="help-block">
										{{ $errors->first('driver_profile_pic') }}
									</p>
								@endif
							</div> 
							
							@if(isset($driver['driver_profile_pic']))
								<div class="col-sm-6 form-group">                
									<img src = 'data:image/png;base64,{{ $driver["driver_profile_pic"] }}' width="80px" height="80px">
								</div>
							@endif					
						</div>
						<div class="add-block">
							@if($op_type == 2) <!--  upload doc if individual operator -->
								<h4>Documents</h4>
								<div class="row">
									<div class="col-xs-6 form-group">
										<div class="f-half">
											<input type="hidden" name="driving_license_doc[doc_type_id]" id="lic_type_id" value="2">
											<label for="lic_number" class="control-label">{{ __('Driving License No*') }}</label>
											<input id="lic_number" type="text" class="form-control" name="driving_license_doc[lic_number]" value="{{ $driver['lic_number'] }}" required autofocus>
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
												<input id="lic_validity" type="text" class="form-control" name="driving_license_doc[lic_validity]" value="{{ $driver['lic_expiry'] }}" data-provide="datepicker" required autofocus required>
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
											<input id="lic_image" type="file" class="form-control p-0" name="driving_license_doc[lic_image]" value="" autofocus>
											<p class="help-block-message"></p>
											@if($errors->has('lic_image'))
												<p class="help-block-message">
													{{ $errors->first('lic_image') }}
												</p>
											@endif
										</div>
										<div class="col-xs-3">
											@if($driver['lic_image'])
											<div>
												<img src = 'data:image/png;base64,{{ $driver["lic_image"] }}' width="80px" height="80px">
											</div>
											@endif
										</div>
										<div class="col-xs-3">
											@if(isset($driver['lic_is_verify']) && $driver['lic_is_verify'] == 1)
												<a href="#" data-toggle="tooltip" data-placement="top" title="Document is Verified"><i class="fa fa-check-circle text-info v-top"></i></a>
											@elseif(isset( $driver['lic_id']))
											<button class="btn btn-xs btn-success v-top" type="button" onclick="verify_doc('{{ $driver['lic_id'] }}')">Verify</button>
											@endif
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-6 form-group">
										<div class="f-half">
											<input type="hidden" name="pan_doc[doc_type_id]" id="pan_type_id" value="1">
											<label for="pan_number" class="control-label">{{ __('PAN No') }}</label>
											<input id="pan_number" type="text" class="form-control" name="pan_doc[pan_number]" value="{{ $driver['pan_number'] }}"  autofocus>
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
									<div class="col-sm-6 form-group">
										<div class="col-xs-3">
											@if($driver['pan_image'])
											<div>
												<img src = 'data:image/png;base64,{{ $driver["pan_image"] }}' width="80px" height="80px">
											</div>
											@endif
										</div>
										<div class="col-xs-3">
											@if(isset($driver['pan_is_verify']) && $driver['pan_is_verify'] == 1)
												<a href="#" data-toggle="tooltip" data-placement="top" title="Document is Verified"><i class="fa fa-check-circle text-info v-top"></i></a>
											@elseif(isset($driver['pan_id']))
											<button class="btn btn-xs btn-success v-top" type="button" onclick="verify_doc('{{ $driver['pan_id'] }}')">Verify</button>
											@endif
										</div>
									</div>
								</div>
							@endif
							@if(!empty($driverDoc))
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
										@foreach ($driverDoc as $doc)
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
							<div class="row">
								<div class="form-group">
									<div id="add_more_doc" class="form-group m-form__group"></div>
									<div class="col-sm-3 m--margin-bottom-15">
										<button type="button" name="add" id="add_more_driver_doc" class="addMorePadding btn btn-sm btn-info m-l-5"><span><i class="la la-plus"></i>
											</span>Additional Documents</button>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row m-10">
							<div class="col-sm-6 form-group p-0">
								<div class="col-sm-2 form-group">
									 <label for="is_active" class="control-label">{{ __('Status*') }}</label>
								</div>
								
								<div class="col-sm-3 form-group">
									<input id="is_active" type="radio"  name="is_active" type="radio"  value="1" {{ $driver['is_active'] ==1 ? 'checked' : ''}} required autofocus ><span class="st-radio">Active</span>
								</div>
								
								<div class="col-sm-3 form-group">
									<input id="is_active" type="radio"  name="is_active" type="radio"  value="0" {{ $driver['is_active']==0 ? 'checked' : '' }} required autofocus><span class="st-radio">Inactive</span>
								</div>
								<p class="help-block"></p>
								@if($errors->has('is_active'))
									<p class="help-block">
										{{ $errors->first('is_active') }}
									</p>
								@endif
							</div>
						</div>
						<div class="row first-line">
							<div class="col-sm-12 form-group">
								<input id="driver_is_verified"  type="checkbox" name="driver_is_verified"  
								{{ $driver['driver_is_verified']==1 ? 'checked' : '' }}  autofocus>

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
							<div class="form-group">
								<div class="btn-b-u">
									<a href="{{ route('operators.edit',[$operator_id]) }}" class="btn btn-warning">Back</a>
									<button type="submit" class="btn btn-success">
										{{ __('Update') }}
									</button>
								</div>
							</div>
						</div>				
	 				</form>
				</div>
			</div>
		</div>
	</div>
	

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
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<script type="text/javascript">

	var verification_status = '{{json_encode($verification_status)}}';
    var verification_status = JSON.parse(verification_status.replace(/&quot;/g, '\"'));
   

	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();  
		//code for hiding success msg
		setTimeout(function() {
	        $("#successMessage").addClass('hide');
	    }, 1000);
		//end

		var driver_is_online = $("input[name='driver_is_online']:checked").val();
		setValidityInput(driver_is_online);

		$('body').on('click','.is_driver_online' ,function()
		{
			var driver_is_online = $(this).val();
			setValidityInput(driver_is_online);
		});

		//additional document create synamic input-nayana
		var p_i = 0;
		$('#add_more_driver_doc').click(function(){
			$('#add_more_doc').append('<div id="row'+p_i+'" class="upload"><label>Upload More Documents (Optional)</label><div class="form-group m-form__group row"><div class="col-sm-6"><div class="f-half"><select id="select_doc_type_'+p_i+'" name="additional_documents['+p_i+'][doc_type_id]" class="select-2 dropdown-list-style form-control file_type select_documents fillItem" preview-name="Selected Documents"><option value="" disabled selected="selected">Select Document</option>@if(!empty($additionalDocList))	@foreach ($additionalDocList as $documents)<option class="" value="{{$documents["doc_type_id"]}}">{{$documents["doc_label"]}}</option> @endforeach  @endif	</select></div><div class="f-half"><input class="form-control" id="doc_number_'+p_i+'" type="" name="additional_documents['+p_i+'][doc_number]"></div></div><div class="col-sm-6"><div class="f-half"><div class="input-group"><input id="lic_validity_'+p_i+'" type="text" class="form-control date-picker" name="additional_documents['+p_i+'][doc_expiry]" autofocus><div class="input-group-addon calender"><i class="fa fa-calendar"></i></div></div></div><div class="f-half v-lic"><input class="form-control p-0 in-file-w" type="file" id="doc_images_'+p_i+'" name="additional_documents['+p_i+'][doc_images]"><!--<button type="button" name="remove" id="'+p_i+'" class="btn btn-info btn-sm eye-btn"><i class="fa fa-eye"></i></button>--><button type="button" name="remove" id="'+p_i+'" class="btn btn-danger btn_remove btn-sm r-doc-btn m-l-10"><i class="fa fa-trash"></i></button></div></div></div><div class="input-group-append"><div id="selectDoc'+p_i+'"></div></div></div>');

			// $("#select_doc_type_"+p_i).rules("add", {
			// 	required: {
   //                  depends: function (){ 
   //                      var doc_number = $('#doc_number_'+p_i).val();
   //                      console.log('#doc_number_'+p_i);
   //                  	console.log(doc_number);
   //                      var doc_expiry = $('#lic_validity_'+p_i).val();
   //                      console.log(doc_expiry);
   //                      if( (doc_number!='' && doc_number!=null && doc_number!=undefined) || (doc_expiry!='' && doc_expiry!=null && doc_expiry!=undefined ) ){
   //                              return true;
   //                      }
   //                      else{
   //                      	return false;
   //                      }
   //                  }
   //              },
			// 	messages: {
			// 		required: "Please select document type",
			// 	}
			// });
			p_i++;

			$(document).on('click', '.btn_remove', function(){
				var button_id = $(this).attr("id");
				$('#row'+button_id).remove();
			});
		});
		//end-additional doc

		$( "#EditDriverForm" ).validate({
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
				'driver_profile_pic': {
					required: {
                        depends: function (){ 
                            var edit_profile_image = '{{ $driver["driver_profile_pic"] }}';
                            var filesArray = document.getElementById("driver_profile_pic").files;
                            if( (edit_profile_image!='' && edit_profile_image!=null )&& filesArray.length==0){
                                    return false;
                            }
                            if((edit_profile_image=='' || edit_profile_image==null ) && filesArray.length==0){
                                    return true;
                            }
                        }
                    },
				},
				"driving_license_doc[lic_image]" :{
					required: {
                        depends: function (){ 
                            var edit_lic_image = '{{ $driver['lic_image'] }}';
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
                    pan_pattern : true,
					required: {
                        depends: function (){ 
                        	var edit_pan_image = '{{ $driver['pan_image'] }}';
                            var filesArray = document.getElementById("pan_image").files;
                            var pan_no = $("#pan_number").val();
                            if(pan_no==null || pan_no == ''){
                            	if((edit_pan_image!='' && edit_pan_image!=null ) || filesArray.length!=0){
	                                    return true;
	                            }
	                            if((edit_pan_image=='' || edit_pan_image==null ) && filesArray.length==0){
	                                    return false;
	                            }
                            }
                            else{
                            	return false;
                            }
                        }
                    },
				},
				"pan_doc[pan_image]" :{
					required: {
                        depends: function (){ 
                    	 	var edit_pan_image = '{{ $driver['pan_image'] }}';
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
				'is_active': {
					required: true,
				},
				'driver_offline_hrs': {
					required: true,
				},
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
					required: 'Please upload profile image',
				},
				'is_active': {
					required: 'Please select status',
				},
				'driver_offline_hrs': {
					required: 'Please select offline hours',
				},
				'pan_doc[pan_number]': {
					required:"Please enter pan number",
					pan_pattern: 'Please enter valid pan number',
				},
				"pan_doc[pan_image]": {
					required:"Please upload pan",
				},
			},
			submitHandler: function(e) {
				var i = $("#EditDriverForm").valid();
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

	function setValidityInput(driver_is_online){
		var is_online = $("input[name='driver_is_online']:checked").val();
		if(is_online == 'true'){
			$('#offline_hrs').addClass('hide');
			$('#driver_offline_hrs').val('');
			document.getElementById("driver_offline_hrs").required = false;
		}
		else{
			$('#offline_hrs').removeClass('hide');
			document.getElementById("driver_offline_hrs").required = true;
		}
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

	function showimage(path)
	{
		
		$("#show_docuemnt_image").html(" ");
		var html1 = '';
		if(path!="")
		{ 
			html1 +='<img class="docuemnt-image" src = "data:image/png;base64,'+path+'">';
		}        
		$("#show_docuemnt_image").append(html1);
	}
</script>

@endsection







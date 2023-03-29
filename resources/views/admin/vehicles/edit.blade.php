<style type="text/css">
	.help-block1 {
		display: block;
		margin-top: 5px;
		margin-bottom: 10px;
		color: #dd4b39;
	}
</style>
@extends('layouts.app')

@section('content-header')
	<h1>Operator vehicles</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Roles</li>
		<li class="active">Edit</li>
	</ol>
@endsection

@section('content')
	@if(session('success'))
		<div class="row" id="msg">
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		</div>
	@else
		@foreach ($operatorvehicles as $operatorvehicles) 
		@endforeach
		<?php $operator_id = Request::get('op');?>
		<div class="panel-body p-0">
			<div class="view-op"> 
				<form id="editVehicle" method="POST" action="{{ route('operatorvehicles/update') }}" enctype="multipart/form-data"> 
				@csrf
					<div class="row">
						<div class="col-sm-12 form-group section-title">Edit Vehicle Information</div>
						<div class="section">
							@if(!empty($operatorvehicles))
						
							<div class="row">   
								<input id="veh_id" type="hidden" class="form-control" name="veh_id" value="{{ $operatorvehicles['veh_id'] }}" required >
								<input type="hidden" value="{{ $header }}" name="header" id="header" required="required">
								<input id="operator_id" type="hidden" class="form-control" name="operator_id" value="{{$operator_id}}">
								
							<!-- 	<div class="col-sm-6 form-group">
									<label for="veh_op_username" class="control-label">{{ __('Operator User Name*') }}</label>
									<input id="veh_op_username" type="text" class="form-control" name="veh_op_username" value="{{ $operatorvehicles['veh_op_username'] }}"  pattern="[1-9]{1}[0-9]{9}"   title="The operator User name must be 10 digits." required autofocus>
									
									<p class="help-block"></p>
									@if($errors->has('veh_op_username'))
										<p class="help-block">
											{{ $errors->first('veh_op_username') }}
										</p>
									@endif
								</div> --> 
							</div>
							<div class="row">
								@if($header=="Individual Operators")
									<div class="col-sm-4 form-group">
										<label for="veh_op_ownership" class="control-label">{{ __('Vehicles Operator Ownership*') }}</label>
										<div class="row">
											<div class="on-radio">	
												<input id="veh_op_ownership" type="radio" onclick="Ownershiptype(0)" name="veh_op_ownership" value="0" 
												{{ $operatorvehicles['veh_op_ownership']==0 ? 'checked' : ''}}
												required >
												<span>Self</span>
											</div>
											<div class="on-radio">	
												<input id="veh_op_ownership" type="radio"  onclick="Ownershiptype(1)" name="veh_op_ownership" value="1" 
												{{ $operatorvehicles['veh_op_ownership']==1 ? 'checked' : ''}}
												required >
												<span>Rented </span>
											</div>
										</div>							
											<p class="help-block"></p>
											@if($errors->has('veh_op_ownership'))
												<p class="help-block">
													{{ $errors->first('veh_op_ownership') }}
												</p>
											@endif
									</div>
								@endif
								@if($header=="Individual Operators")
									<div class="col-sm-6 form-group" id="Ownershiptype">
										<div class="f-half">
											<label for="veh_owner_name" class="control-label">{{ __('Owner Name*') }}</label>
											<input id="veh_owner_name" type="text" class="form-control" name="veh_owner_name" value="{{ $operatorvehicles['veh_owner_name'] }}"  autofocus>
											
											<p class="help-block"></p>
											@if($errors->has('veh_owner_name'))
												<p class="help-block">
													{{ $errors->first('veh_owner_name') }}
												</p>
											@endif
										</div>
										<div class="f-half">
											<label for="veh_owner_mobile_no" class="control-label">{{ __('Owner Mobile Number*') }}</label>
											<input id="veh_owner_mobile_no" type="text" class="form-control" name="veh_owner_mobile_no" value="{{ $operatorvehicles['veh_owner_mobile_no'] }}" pattern="[1-9]{1}[0-9]{9}" title="The Mobile no. must be 10 digits." autofocus>
											
											<p class="help-block"></p>
											@if($errors->has('veh_owner_mobile_no'))
												<p class="help-block">
													{{ $errors->first('veh_owner_mobile_no') }}
												</p>
											@endif
										</div>
									</div>
								@endif
							</div> 
							<div class="row"> 
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<label for="veh_make_model_type" class="control-label">{{ __(' Make*') }}</label>      
										<select id="veh_make_model_type" type="text" class="form-control" name="veh_make_model_type" required onclick="getmodeltype()">
											<option value=""> Select Make</option>
											@if(!empty($veh_type_list))
											@foreach($veh_type_list as $veh_type_list)
											<option value="{{ $veh_type_list->veh_type_name}}" {{ $operatorvehicles['veh_make_model_type']==$veh_type_list->veh_type_name ? 'selected' : '' }}> {{ $veh_type_list->veh_type_name}}</option>
											@endforeach
											@endif
										</select>
										<p class="help-block"></p>
										@if($errors->has('veh_make_model_type'))
											<p class="help-block">
												{{ $errors->first('veh_make_model_type') }}
											</p>
										@endif
									</div>
									<div class="f-half">
										<label for="veh_model_name" class="control-label">{{ __('Model*') }}</label>
										<select id="veh_model_name"  class="form-control" name="veh_model_name" required autofocus onclick="getcapacity()">									
											<?php 
											if(count($modelname)!=0)
											{
											?>
												<option value="{{ $operatorvehicles['veh_model_name'] }}">{{ $modelname->first()->veh_model_name }}</option>
											<?php
											} 
											else{ ?>
												<option value=""> Select Model</option>
											<?php 
												}
											?>

										</select> 
										<p class="help-block"></p>
										@if($errors->has('veh_model_name'))
											<p class="help-block">
												{{ $errors->first('veh_model_name') }}
											</p>
										@endif
									</div>
								</div> 
								<div class="col-sm-4 form-group">
									<label for="veh_wheel_type" class="control-label">{{ __('Vehicle Type*') }}</label>
									<div class="row">
										<div class="on-radio">
											<input id="veh_wheel_type" type="radio"  name="veh_wheel_type" value="3" 
											{{ $operatorvehicles['veh_wheel_type']==3 ? 'checked' : ''}}
											required autofocus> <span>Three Wheeler</span>
										</div> 
										<div class="on-radio">
											<input id="veh_wheel_type" type="radio"  name="veh_wheel_type" value="4" 
											{{ $operatorvehicles['veh_wheel_type']==4 ? 'checked' : ''}}
											required autofocus><span>Four Wheeler </span>
										</div>							
										<p class="help-block"></p>
										@if($errors->has('veh_wheel_type'))
											<p class="help-block">
												{{ $errors->first('veh_wheel_type') }}
											</p>
										@endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<label for="veh_capacity" class="control-label">{{ __('Carriage Capacity (KG)*') }}</label>
										<input id="veh_capacity" type="number" class="form-control" name="veh_capacity" value="{{ $operatorvehicles['veh_capacity'] }}" required autofocus>
										
										<p class="help-block"></p>
										@if($errors->has('veh_capacity'))
											<p class="help-block">
												{{ $errors->first('veh_capacity') }}
											</p>
										@endif
									</div>
									<div class="f-half">
										<label for="veh_dimension" class="control-label">{{ __('Carriage Dimensions (LxBxH) (Ft)*') }}</label>
										<input id="veh_dimension" type="text" class="form-control" name="veh_dimension" value="{{ $operatorvehicles['veh_dimension'] }}" required autofocus>
										
										<p class="help-block"></p>
										@if($errors->has('veh_dimension'))
											<p class="help-block">
												{{ $errors->first('veh_dimension') }}
											</p>
										@endif
									</div>
								</div>
								<div class="col-sm-6 form-group">
									<div class="f-half">                    
										<label for="veh_type" class="control-label">{{ __('Body Type *') }}</label>							
										<select id="veh_type" type="text" class="form-control" name="veh_type"required autofocus>
											<option value="">Select Body Type</option>
											<option value="1" {{ $operatorvehicles['veh_type']==1 ? 'selected' : ''}}>Open </option>
											<option value="2" {{ $operatorvehicles['veh_type']==2 ? 'selected' : ''}}>Closed (Hard top)</option>
											<option value="3" {{ $operatorvehicles['veh_type']==3 ? 'selected' : ''}}>Tarpaulin covered (Soft top)</option>
										</select>
										<p class="help-block"></p>
										@if($errors->has('veh_type'))
											<p class="help-block">
												{{ $errors->first('veh_type') }}
											</p>
										@endif
									</div>
									<div class="f-half">
										<label for="veh_city" class="control-label">{{ __('Base Station / Stand*') }}</label>
										<!--  pattern="^[a-zA-Z]+$"  -->
										<input id="veh_city" type="text" class="form-control" name="veh_city" value="{{ $operatorvehicles['veh_city'] }}" title="The Vehicles city Invalid" autofocus>
										
										<p class="help-block"></p>
										@if($errors->has('veh_city'))
											<p class="help-block">
												{{ $errors->first('veh_city') }}
											</p>
										@endif
										<input id="lat" type="hidden" name="lat" value="{{ $operatorvehicles['veh_base_lat'] }}" />
		    							<input id="lng" type="hidden" name="lng" value="{{ $operatorvehicles['veh_base_lng'] }}" />
										<p class="help-block"></p>
										@if($errors->has('lat'))
											<p class="help-block">
												{{ $errors->first('lat') }}
											</p>
										@endif
										<p class="help-block"></p>
										@if($errors->has('lng'))
											<p class="help-block">
												{{ $errors->first('lng') }}
											</p>
										@endif
									</div>
								</div>
							</div>              
							<div class="row">
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<input id="veh_driver_id" type="hidden" class="form-control" name="veh_driver_id" value="{{ $operatorvehicles['veh_driver_id'] }}" required autofocus>
									
										<label for="veh_registration_no" class="control-label">{{ __(' Registration No*') }}</label>
										<input id="veh_registration_no" type="text" class="form-control" name="veh_registration_no" value="{{ $operatorvehicles['veh_registration_no'] }}" pattern="(([A-Za-z]){2,3}(|-)(?:[0-9]){1,2}(|-)(?:[A-Za-z]){2}(|-)([0-9]){1,4})|(([A-Za-z]){2,3}(|-)([0-9]){1,4})" title="The Vehicles Registration No Invalid Format" required autofocus onkeyup="this.value = this.value.toUpperCase();">
										
										<p class="help-block"></p>
										@if($errors->has('veh_registration_no'))
											<p class="help-block">
												{{ $errors->first('veh_registration_no') }}
											</p>
										@endif
									</div>
									<div class="f-half">
										<label for="veh_color" class="control-label">{{ __('Vehicles Color*') }}</label>
										<select id="veh_color" name="veh_color" class="form-control select2" required autofocus  data-placeholder="Select Color">
										<option value="">Select Vehicle Color</option>
										@foreach($color as $color)
										<option style="background-color: {{ $color->name }} ;" value="{{ $color->id }}" {{ $operatorvehicles['veh_color']==$color->id ? 'selected' : ''}} >{{ $color->name }}
										</option>                        
										@endforeach    
										</select>
															
										<p class="help-block"></p>
										@if($errors->has('veh_color'))
											<p class="help-block">
												{{ $errors->first('veh_color') }}
											</p>
										@endif
									</div>
								</div>
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<label for="veh_fuel_type" class="control-label">{{ __('Select Type of Fuel*') }}</label>
										<select id="veh_fuel_type" class="form-control" name="veh_fuel_type" autofocus>
											<option value="">Select Type of Fuel</option>
											<option value="Electric"  {{ $operatorvehicles['veh_fuel_type']=='Electric' ? 'selected' : ''}}>Electric</option>
											<option value="Non-electric" {{ $operatorvehicles['veh_fuel_type']=='Non-electric' ? 'selected' : ''}}>Non-electric</option>
										</select>

										<p class="help-block"></p>
										@if($errors->has('veh_fuel_type'))
											<p class="help-block">
												{{ $errors->first('veh_fuel_type') }}
											</p>
										@endif
									</div>
								</div>						
							</div>
							<div class="add-block">
								<h4>Standard Charges</h4>
								<div class="row">
									<div class="col-sm-4 form-group">
										<div class="row">
											<div class="std">
												<label for="veh_base_charge" class="control-label">{{ __('Fixed Charge*') }}</label>
												<label>(Fixed for first 3Kms)</label>
											</div>
											<div class="std-input">
												<input id="veh_base_charge" type="number" class="form-control" name="veh_base_charge" value="{{ $operatorvehicles['veh_base_charge'] }}" autofocus>
												<label class="control-label">Rs</label>
											</div>
										</div>
										<div class="row">
											<label id="veh_base_charge-error" class="error" for="veh_base_charge"></label>
											<p class="help-block"></p>
											@if($errors->has('veh_base_charge'))
												<p class="help-block text-red">
													{{ $errors->first('veh_base_charge') }}
												</p>
											@endif
										</div>
										<div class="row">
											<div class="std">
												<label for="veh_3km_15km" class="control-label">{{ __('Additional per Km*') }}</label>
												<label>(Above 03 till 15 Kms)</label>
											</div>
											<div class="std-input">
												<input id="veh_3km_15km" type="number" class="form-control" name="veh_3km_15km" value="{{ $operatorvehicles['veh_3km_15km'] }}" required autofocus>
												<label class="control-label">Rs./Km</label>
											</div>
										</div>
										<div class="row">
											<label id="veh_3km_15km-error" class="error" for="veh_3km_15km"></label>
											<p class="help-block"></p>
											@if($errors->has('veh_3km_15km'))
												<p class="help-block text-red">
													{{ $errors->first('veh_3km_15km') }}
												</p>
											@endif
										</div>
										<div class="row">
											<div class="std">
												<label for="veh_above_15km" class="control-label">{{ __('Additional per Km*') }}</label>
												<label>(Above 15 Kms)</label>
											</div>
											<div class="std-input">
												<input id="veh_above_15km" type="number" class="form-control" name="veh_above_15km" value="{{ $operatorvehicles['veh_above_15km'] }}" autofocus>
												<label class="control-label">Rs./Km</label>
											</div>
										</div>
										<div class="row">
											<label id="veh_above_15km-error" class="error" for="veh_above_15km"></label>
											<p class="help-block"></p>
											@if($errors->has('veh_above_15km'))
												<p class="help-block text-red">
													{{ $errors->first('veh_above_15km') }}
												</p>
											@endif
										</div>	
									</div>
									<!-- <div class="col-sm-2 charge-btn">
										<div class="row">
							        		<button class="btn btn-save">save rates</button>
							        	</div>
							        	<div class="row">
							        		<a data-toggle="modal" class="rate" data-target="#rateChart" data-whatever="@fat"><button class="btn btn-chart"><span>show</span><br>Rate chart</button></a>
							        	</div>
									</div> -->
								</div>
								<hr class="line-spacer">
								<div class="row">
									<div class="col-sm-3 form-group">
										<div class="first-line">
											<label for="veh_loader_available" class="control-label">{{ __('Loader Available*') }}</label>
											<div class="row">
												<div class="on-radio">
													<input id="veh_loader_available" type="radio"  name="veh_loader_available" value="1" {{ $operatorvehicles['veh_loader_available']==1 ? 'checked' : ''}} required autofocus onclick="loadertype(1)">
													<span>Yes</span>
												</div>
												<div class="on-radio">
													<input id="veh_loader_available" type="radio"  name="veh_loader_available" value="0" 
													{{ $operatorvehicles['veh_loader_available']==0 ? 'checked' : ''}}
													required autofocus onclick="loadertype(0)"><span>No</span>
												</div>
											</div>						
											<p class="help-block"></p>
											@if($errors->has('veh_loader_available'))
												<p class="help-block">
													{{ $errors->first('veh_loader_available') }}
												</p>
											@endif
										</div>
									</div>
									<div class="col-sm-6 p-l-0" id="loader"> 
										<div class="f-half form-group">
											<label for="veh_no_person" class="control-label">{{ __('Number Of Person*') }}</label>
											<input id="veh_no_person" type="number" class="form-control" name="veh_no_person" value="{{ $operatorvehicles['veh_no_person'] }}"  autofocus>			
											<p class="help-block"></p>
											@if($errors->has('veh_no_person'))
												<p class="help-block">
													{{ $errors->first('veh_no_person') }}
												</p>
											@endif
										</div>
										<div class="f-half form-group">
											<label for="veh_charge_per_person" class="control-label">{{ __('Charge Per Person*') }}</label>
											<input id="veh_charge_per_person" type="number" class="form-control" name="veh_charge_per_person" value="{{ $operatorvehicles['veh_charge_per_person'] }}" autofocus>							
											<p class="help-block"></p>
											@if($errors->has('veh_charge_per_person'))
												<p class="help-block">
													{{ $errors->first('veh_charge_per_person') }}
												</p>
											@endif
										</div>
									</div> 
								</div>	
							</div>					
							<div class="row">						
								<div class="col-sm-6 form-group">
									<div class="f-half">
										<label for="veh_is_online" class="control-label">{{ __('Vehicles Online *') }}</label>
										<select id="veh_is_online" type="text" class="form-control" name="veh_is_online">
										<option value="1" {{ $operatorvehicles['veh_is_online']==1 ? 'selected' : ''  }}>Online</option>  
										<option value="0" {{ $operatorvehicles['veh_is_online']==0 ? 'selected' : ''  }}>Offline</option>  
										</select>
										<p class="help-block"></p>
										@if($errors->has('veh_is_online'))
											<p class="help-block">
												{{ $errors->first('veh_is_online') }}
											</p>
										@endif
									</div>
									<div class="f-half">
										<label for="veh_images" class="control-label">{{ __('Vehicles Image') }}</label>
										<input id="veh_images" type="file" class="form-control p-0" name="veh_images[]" value="" autofocus multiple="multiple" accept="image/*" onchange="preview_image();">
								
										<p class="help-block1" id="veh_imag"></p>
										@if($errors->has('veh_images'))
											<p class="help-block text-red">
												{{ $errors->first('veh_images') }}
											</p>
										@endif
									</div>
								</div>
								<div class="col-sm-6 form-group" id="edit_veh_images_div">
									<label for="veh_fuel_type" class="control-label">{{ __('View Vehicle Images') }}</label><br>
									<i class="fa fa-fw fa-image" onclick="showVehimage(' {{ $operatorvehicles['veh_images_array'] }} ')" data-toggle="modal" data-target="#veh_imags" ></i>
								</div>
								<div class="col-sm-6 form-group" id="veh_images_div" style="display: none">
									<label for="view_veh_images" class="control-label">{{ __('View Vehicle Images') }}</label><br>
									<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_veh_imags" ></i>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4 form-group">
									<div class="first-line"><br>
										<label for="is_active" class="control-label">{{ __('Vehicles Status*') }}</label>
										<div class="row">
											<div class="on-radio">
												<input id="is_active" type="radio"  name="is_active" value="1" 
												{{ $operatorvehicles['is_active']==1 ? 'checked' : ''}}
												required autofocus>
												<span>Active</span>
											</div>
											<div class="on-radio">
												<input id="is_active" type="radio"  name="is_active" value="0" 
												{{ $operatorvehicles['is_active']==0 ? 'checked' : ''}}
												required autofocus><span>Deactive</span>
											</div>
										</div>							
										<p class="help-block"></p>
										@if($errors->has('is_active'))
											<p class="help-block">
												{{ $errors->first('is_active') }}
											</p>
										@endif
									</div>
								</div>							
							</div>
							<div class="row">
								<div class="form-group">
									<div id="add_more_doc" class="form-group m-form__group"></div>
									<div class="col-sm-3 m--margin-bottom-15">
										<button type="button" name="add" id="add_more_veh_doc" class="addMorePadding btn btn-sm btn-info m-l-5"><span><i class="la la-plus"></i>
											</span>Additional Documents</button>
									</div>
								</div>
							</div><br>
							<div class="row">
								<div class="col-sm-6 form-group">
									<div class="ver-check">
										<input id="vehicle_is_verified" type="checkbox" name="vehicle_is_verified" {{ $operatorvehicles['vehicle_is_verified']==1 ? 'checked' : ''}} autofocus>
										<span>{{ __('Verified') }}</span>
									</div>
									<p class="help-block"></p>
									@if($errors->has('vehicle_is_verified'))
										<p class="help-block text-red">
											{{ $errors->first('vehicle_is_verified') }}
										</p>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6 form-group">
									<input id="veh_last_location" type="hidden" class="form-control" name="veh_last_location" value="{{ $operatorvehicles['veh_last_location'] }}" required autofocus>
										
									<p class="help-block"></p>
									@if($errors->has('veh_last_location'))
										<p class="help-block">
											{{ $errors->first('veh_last_location') }}
										</p>
									@endif
								</div>
							</div>
							<div class="row">
								@if(!empty($vehicleDoc))
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
												@foreach ($vehicleDoc as $doc)
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
																<a href="#" data-toggle="tooltip" data-placement="top" title="Document is Verified"><i class="fa fa-check-circle text-info"></i></a>
															@else
																<button class="btn btn-xs btn-success" type="button" onclick="verify_doc('{{ $doc->doc_id }}')">Verify</button>
															@endif
														</td>
														<td>
															<a href="{{ route('update/documentinfo/',[ $doc->doc_id, 'typeof'=>'vehicle', '_op' => $operator_id ]) }}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>

															<button class="btn btn-xs btn-danger" type="button" onclick="deletedocument('{{ $doc->doc_id }}')"><i class="fa fa-trash-o"></i></button>
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
							</div>
							<div class="row">
								<div class="col-sm-12 form-group">
									<div class="btn-b-u">
										<a href="{{ route('operators.edit',[Request::get('op')])}}" class="btn btn-warning">Back</a>
										<button type="submit" class="btn btn-success" id="update" value="Validate!">
										{{ __('Update') }}
										</button>
									</div>
								</div>
							</div>
							@endif  
						</div>
					</div>	
				</form>
			</div>
		</div> 

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
	@endif
	<!-- view vehicle images modal -->
	<div class="modal fade" id="view_veh_imags">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Vehicle Images</h4>
				</div>
				<div class="modal-body" >
					<div id="vehicle_view_image">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- view vehicle images end-->
	<!--Driver Rate chart-->
    <div class="modal view-chart" id="rateChart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<form class="form-body m-t-10">
	    			<div class="modal-header">
	    				<h5>Rate Chart</h5>
	    			</div>
	    			<div class="modal-body">
	    				<div class="head-btn">
			    			<button class="btn btn-edit">edit rates</button>
			    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    		</div>
			    		<div class="table-body">
		    				<table class="std_rate">
		    					<tr>
		    						<th>Kms</th>
		    						<th>STD Rate(Rs.)</th>
		    					</tr>
		    					<tr>
		    						<td>Up to 3 Kms</td>
		    						<td>
		    							<input type="number" id="veh_base_charge" class="form-control">
		    						</td>
		    					</tr>
		    					<tr>
		    						<td>03 to 15 Kms</td>
		    						<td>
		    							<input type="number" id="veh_3km_15km" class="form-control">
		    						</td>
		    					</tr>
		    					<tr>
		    						<td>Above 15 Kms</td>
		    						<td>
		    							<input type="number" id="veh_above_15km" class="form-control">
		    						</td>
		    					</tr>
		    				</table>
		    				<table class="trip_rate">
		    					<tr>
		    						<th colspan="8">TRIP RATE CHART (Rs.)</th>
		    					</tr>
		    					<tr>
		    						<th>Kms</th>
		    						<th></th>
		    					</tr>
		    					<tr>
							      <td></td>
		    					  <td></td>
							   </tr>
		    					<tr>
							      <td></td>
		    					  <td></td>
							   </tr>
		    				</table>	
		    			</div>
		    			<div class="footer-text">
							amount mentioned above is only for driver partner
							<h5>Do not share rate with customers</h5>
						</div>
	    			</div>
	    		</form>
    		</div>
    	</div>
    </div>
    <!--End chart-->
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<script type="text/javascript">
	
	 $(document).ready(function () {
    	$('#editVehicle').on('submit', function(e){
			console.log('on form submit');
		});

    	$("#editVehicle").validate({
			rules: {
				veh_op_ownership: {
					required: true,
				},
				veh_owner_name: {
					required: {
				        depends:function(){
				            // $(this).val($.trim($(this).val()));
				            return $("#veh_op_ownership").val() == 0;
				        }
				    },
				},
				veh_owner_mobile_no: {
					required: function(element){
			            return $("#veh_op_ownership").val() == 0;
			        },
				},
				veh_make_model_type :{
					required: true,
				},
				veh_model_name :{
					required: true,
				},
				veh_wheel_type :{
					required: true,
					// required: function(element){
			  //           return $("#veh_model_name").val() =="";
			  //       }
				},
				veh_capacity :{
					required: true,
					// required: function(element){
			  //           return $("#veh_model_name").val() =="";
			  //       }
				},
				veh_dimension :{
					required: true,
					// required: function(element){
			  //           return $("#veh_model_name").val() =="";
			  //       }
				},
				veh_type :{
					required: true,
				},
				veh_city :{
					required: true,
				},
				lat :{
					required: true,
				},
				lng :{
					required: true,
				},
				veh_registration_no :{
					required: {
				        depends:function(){
				            $(this).val($.trim($(this).val()));
				            return true;
				        }
				    },
				},
				veh_color :{
					required: true,
				},
				veh_fuel_type :{
					required: true,
				},
				veh_base_charge :{
					required: true,
				},
				// veh_per_km :{
				// 	required: true,
				// },
				veh_3km_15km :{
					required: true,
					digits: true,
				},
				veh_above_15km :{
					required: true,
					digits: true,
				},
				veh_is_online :{
					required: true,
				},
				"veh_images[]": {
				 	required: {
                     	depends: function (){ 
							var edit_veh_images = '{{ $operatorvehicles["veh_images_array"] }}';
							var filesArray = document.getElementById("veh_images").files;
							if( (edit_veh_images!='' && edit_veh_images!=null )&& filesArray.length==0){
								return false;
							}
							if((edit_veh_images=='' || edit_veh_images==null ) && filesArray.length==0){
								return true;
							}
                         }
                 	},
					check_veh_images_count :true,
		        },
				is_active :{
					required: true,
				},
				veh_loader_available :{
					required: true,
				},
				veh_no_person :{
					required: function(element){
			            return $("#veh_loader_available").val()==1;
			        }
				},
				veh_charge_per_person :{
					required: function(element){
			            return $("#veh_loader_available").val()==1;
			        }
				},
			},  
			messages: {
				veh_op_ownership : {
					required:"Please enter vehicle ownership",
				},
				veh_owner_name:{
					required:"Please enter vehicle owner name",
				}, 
				veh_owner_mobile_no:{
					required:"Please enter vehicle owner mobile number",
				},
				veh_make_model_type :{
					required:"Please select make",
				},
				veh_model_name :{
					required:"Please select model name",
				},
				veh_wheel_type :{
					required:"Please select wheel type",
				},
				veh_capacity :{
					required:"Please select capacity",
				},
				veh_dimension :{
					required:"Please select dimensions",
				},
				veh_type :{
					required:"Please select body type",
				},
				veh_city :{
					required:"Please enter base station / stand",
				},
				lat :{
					required:"Lat long not set please select base station again",
				},
				lng :{
					required:"Lat long not set please select base station again",
				},
				veh_registration_no :{
					required:"Please enter vehicles registration no",
				},
				veh_color :{
					required:"Please select vehicle color",
				},
				veh_fuel_type :{
					required:"Please select fuel type",
				},
				veh_base_charge :{
					required:"Please enter minimum charge",
				},
				// veh_per_km :{
				// 	required:"Please enter change per km",
				// },
				veh_3km_15km :{
					required:"Please enter charge",
				},
				veh_above_15km :{
					required:"Please enter charge",
				},
				veh_is_online :{
					required:"Please select vehicle offline / online status",
				},
				"veh_images[]":{ 
					// required:"Please upload vehicle images",
					check_veh_images_count : "You can select min 4 and max 5 vehicle images",
				}, 
				is_active :{
					required:"Please select vehicle active / inactive status",
				},
				veh_loader_available : {
					required:"Please select loader is available",	
				},
				veh_no_person : {
					required:"Please enter number of person",	
				},
				veh_charge_per_person : {
					required:"Please enter charge per person",	
				},
			},
			invalidHandler: function(event, validator) {
				// console.log(event);
				// console.log(validator);
			},
		});
 	});	

	 // form images-validation
        var limit = 5;
        $(document).ready(function() {
                var edit_veh_images = '{{ $operatorvehicles["veh_images_array"] }}';
                // add veh-img validation
                jQuery.validator.addMethod("check_veh_images_count", function(value, element) {
                        var filesArray = document.getElementById("veh_images").files;
                        if(edit_veh_images!=null){
                                if(filesArray.length==0){
                                        return true;
                                }
                                if(filesArray.length > 5 || filesArray.length < 4){
                                        return false;
                                }
                                
                        console.log('not null');
                                return true;
                        }
                        else{
                                return false;
                        }
                // veh-img validation end
                });
        });

	
	$(document).ready(function()
	{
		//code-start
		var loader_avaialble = '{{ $operatorvehicles["veh_loader_available"] }}';
		if( loader_avaialble == 1)
		{
			 $("#loader").show();
		}
		 else{
			$("#loader").hide();
		}
		//code-end

		//code-start
		var op_ownership = '{{ $operatorvehicles["veh_op_ownership"] }}';
		if( op_ownership == 0)
		{
			$("#Ownershiptype").hide();    
		}
		else{
			$("#Ownershiptype").show();
		}
		//code-end

		//code start
		// $('#veh_images').change(function(){
		// 	var files = $(this)[0].files;
		// 	if(files.length > 5 || files.length < 4){
		// 		$("#veh_imag").html("You can select min 4 and max "+limit+" Vehicle images.");
		// 		$('#veh_images').val('');
		// 		return false;
		// 	}else{
		// 		return true;
		// 	}
		// });
		//code-end

		//additional document create synamic input-nayana
		var p_i = 0;
		$('#add_more_veh_doc').click(function(){
			$('#add_more_doc').append('<div id="row'+p_i+'" class="upload"><label>Upload More Documents (Optional)</label><div class="form-group m-form__group row"><div class="col-sm-6"><div class="f-half"><select id="select'+p_i+'" name="additional_documents['+p_i+'][doc_type_id]" class="select-2 dropdown-list-style form-control file_type select_documents fillItem" preview-name="Selected Documents"><option value="" disabled selected="selected">Select Document</option>@if(!empty($vehicleAdditionalDoc))	@foreach ($vehicleAdditionalDoc as $documents)<option class="" value="{{$documents["doc_type_id"]}}">{{$documents["doc_label"]}}</option> @endforeach  @endif	</select></div><div class="f-half"><input class="form-control" type="" name="additional_documents['+p_i+'][doc_number]"></div></div><div class="col-sm-6"><div class="input-group"><input id="lic_validity_'+p_i+'" type="text" class="form-control date-picker" name="additional_documents['+p_i+'][doc_expiry]" autofocus><div class="input-group-addon calender"><i class="fa fa-calendar"></i></div><div class="f-half"><input class="form-control p-0" type="file" name="additional_documents['+p_i+'][doc_images]"></div><div class="f-half"><button type="button" name="remove" id="'+p_i+'" class="btn btn-danger btn_remove btn-sm r-doc-btn">Remove</button></div></div></div><div class="input-group-append"><div id="selectDoc'+p_i+'"></div></div></div>');

			p_i++;

			$(document).on('click', '.btn_remove', function(){
				var button_id = $(this).attr("id");
				$('#row'+button_id).remove();
			});
		});
		//end-additional doc
	});

	$(document).on('keypress','.onlyalpha',function(key)
	{
		if((key.charCode < 97 || key.charCode > 122 || key.charCode==32) && (key.charCode < 65 || key.charCode > 90) && (key.charCode != 45)) return false;
	});

	function Ownershiptype(type)
	{
		if(type==0)
		{
			$("#Ownershiptype").hide();
			$("#veh_owner_name").attr('required',false);            
			$("#veh_owner_mobile_no").attr('required',false); 
			$("#veh_owner_name").val('');
			$("#veh_owner_mobile_no").val(''); 
		}
		else
		{
			$("#Ownershiptype").show();
			$("#veh_owner_name").attr('required',true);
			$("#veh_owner_mobile_no").attr('required',true);   
		}
	}

	function loadertype(type)
	{
		if(type==0)
		{
			$("#loader").hide();
			$("#veh_no_person").attr('required',false);
			$("#veh_charge_per_person").attr('required',false); 
		}
		else
		{
			$("#loader").show();
			$("#veh_no_person").attr('required',true);
			$("#veh_charge_per_person").attr('required',true);   
		}
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
				// html2 += '<img class="veh-image" src = "data:image/png;base64,'+value+'">';
				html2 += '<img class="veh-image" src = "{{ asset("images")}}/'+value+'">';
			});
			
			$("#driver_veh_image").append(html2);
		}
	}
	function preview_image() 
	{
		$('#vehicle_view_image').html('');
		var total_file=document.getElementById("veh_images").files.length;
		if(total_file > 0){
			$('#edit_veh_images_div').hide();
			$('#veh_images_div').show();
			for(var i=0;i<total_file;i++)
			{
				$('#vehicle_view_image').append("<img src='"+URL.createObjectURL(event.target.files[i])+"' class='veh-image'>");
			}
		}else{
			$('#edit_veh_images_div').show();
			$('#veh_images_div').hide();
			$('#vehicle_view_image').html('');
		}
	}


	function getmodeltype()
	{
		$('#veh_model_name').val('');
		$('input[name="veh_wheel_type"]').prop('checked', false);
		$('#veh_capacity').val('');
		$('#veh_dimension').val('');
		var modeltypename=$("#veh_make_model_type").val();

		$.ajax({
			url :"{{ route('getmodelname') }}",
			method:"POST",
			data: {
			"_token": "{{ csrf_token() }}",
			"modeltypename": modeltypename
			},        
			success : function(data){
			 
			 $('#veh_model_name').html("");
			
			 $.each(data, function (key, value)
			{
			 $('#veh_model_name').append('<option value="'+ data[key]['veh_id'] +'">' + data[key]['veh_model_name'] + '</option>');

			});          

			}

		});
	}

	function getcapacity()
	{
		var modelname=$("#veh_model_name").val();
		$.ajax({
			url :"{{ route('getcapacity') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"modelname": modelname
			},
			success : function(data){
				$('#veh_dimension').html(" ");
				$('#veh_capacity').html(" ");
				$.each(data, function (key, value)
				{
					if(data[key]['veh_fac_master_id']==2){
						$("#veh_capacity").val(data[key]['veh_fac_value']);
					}
					if(data[key]['veh_fac_master_id']==4){
						$("#veh_dimension").val(data[key]['veh_fac_value']); 
					}
					if(data[key]['veh_fac_master_id']==1){
						$("input[name=veh_wheel_type][value=" + data[key]['veh_fac_value'] + "]").prop('checked', true);
						// $("input[name=veh_wheel_type][value=" + data[key]['veh_fac_value'] + "]").attr('checked', 'true');
					}
				});
			}
		});
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

	// googleapi location
	function initialize() {
	  var input = document.getElementById('veh_city');
	  var autocomplete = new google.maps.places.Autocomplete(input);
		// get base lat-long
  		google.maps.event.addListener(autocomplete, 'place_changed', function () {
          // infowindow.close();
          var place = autocomplete.getPlace();
          updateTextFields(place.geometry.location.lat(),place.geometry.location.lng());
      	});
		//end base lat-long
	}
	google.maps.event.addDomListener(window, 'load', initialize);

	function updateTextFields(lat, lng) {
	  $('#lat').val(lat);
	  $('#lng').val(lng);
	}
	// googleapi location end
</script>
@endsection

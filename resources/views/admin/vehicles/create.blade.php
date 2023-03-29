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
	<!-- <a class="btn btn-info" href="{{ URL::previous() }}">Back</a> -->
	<h1>Operator Vehicle</h1>
	<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
	<li>Roles</li>
	<li class="active">Add Vehicle</li>
	</ol>
@endsection
<!-- Main Content -->
@section('content')
	<script src="https://apis.mappls.com/advancedmaps/api/{{$mapToken}}/map_sdk?layer=vector&v=3.0&callback=initMap1"></script>
<script src="https://apis.mappls.com/advancedmaps/api/{{$mapToken}}/map_sdk_plugins?v=3.0"></script>
<script src="https://apis.mappls.com/advancedmaps/api/{{$mapToken}}/map_sdk_plugins?v=3.0&libraries=getPinDetails"></script>
	@if(session('success'))
		<!-- If password successfully show message -->
		<div class="row" id="msg">
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		</div>
	@endif
	<div class="panel-body p-0">
		<div class="view-op"> 
			<form id="AddVehicle" method="POST" action="{{ route('vehicles.store') }}" enctype='multipart/form-data'>
			@csrf
				<div class="row">
					<div class="col-sm-12 form-group section-title">Add Vehicle Information</div>
					<div class="section">
						<input type="hidden" name="veh_op_id" id="veh_op_id" value="{{ Request::get('op') }}">
						<input type="hidden" name="veh_op_type" id="veh_op_type" value="{{ Request::get('op_type') }}">
						@if(Request::get('op_type') == 1)
							<div class="row">
								<div class="col-sm-4 form-group">
									<label for="veh_op_ownership" class="control-label">{{ __('Vehicles Operator Ownership*') }}</label>
										<div class="row">
											<div class="on-radio">
												<input id="veh_op_ownership" type="radio" checked="true" onclick="Ownershiptype(0)" name="veh_op_ownership" value="0"><span>Self</span>
											</div>
											<div class="on-radio">
												<input id="veh_op_ownership" type="radio"  onclick="Ownershiptype(1)" name="veh_op_ownership" value="1"><span>Rented</span>
											</div>
										</div>
										@if($errors->has('veh_op_ownership'))
											<p class="help-block text-red">
												{{ $errors->first('veh_op_ownership') }}
											</p>
										@endif
								</div>
								<div class="col-sm-6 form-group" id="Ownershiptype">
									<div class="f-half">
										<label for="veh_owner_name" class="control-label">{{ __('Owner Name*') }}</label>
										<input id="veh_owner_name" type="text" class="form-control" name="veh_owner_name" value="" autofocus>
										<p class="help-block"></p>
										@if($errors->has('veh_owner_name'))
											<p class="help-block text-red">
												{{ $errors->first('veh_owner_name') }}
											</p>
										@endif
									</div>
									<div class="f-half">
										<label for="veh_owner_mobile_no" class="control-label">{{ __('Owner Mobile Number*') }}</label>
										<input id="veh_owner_mobile_no" type="text" class="form-control" name="veh_owner_mobile_no" value="" autofocus>
										<p class="help-block"></p>
										@if($errors->has('veh_owner_mobile_no'))
											<p class="help-block text-red">
												{{ $errors->first('veh_owner_mobile_no') }}
											</p>
										@endif
									</div>
								</div>
							</div>
						@endif
						<div class="row">
							<div class="col-sm-6 form-group">
								<div class="f-half">
									<label for="veh_make_model_type" class="control-label">{{ __(' Make*') }}</label> 
									<select id="veh_make_model_type" type="text" class="form-control" name="veh_make_model_type" onclick="getmodeltype()">
										<option value="" > Select Make</option>
										@if(!empty($vehicle_makes))
											@foreach($vehicle_makes as $vehicle_make)
												<option value="{{ $vehicle_make['veh_type_name']}}"> {{ $vehicle_make['veh_type_name']}}</option>
											@endforeach
										@endif
									</select>
									<label id="veh_make_model_type-error" class="error" for="veh_make_model_type"></label>
									<p class="help-block"></p>
									@if($errors->has('veh_make_model_type'))
										<p class="help-block text-red">
											{{ $errors->first('veh_make_model_type') }}
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="veh_model_name" class="control-label">{{ __('Model*') }}</label>
									<select id="veh_model_name"  class="form-control" name="veh_model_name" required autofocus onclick="getcapacity()">
										<option value="">Select Model </option>
									</select> 
									<label id="veh_model_name-error" class="error" for="veh_model_name"></label>
									<p class="help-block"></p>
									@if($errors->has('veh_model_name'))
										<p class="help-block text-red">
											{{ $errors->first('veh_model_name') }}
										</p>
									@endif
								</div>
							</div>
							<div class="col-sm-4 form-group">
								<label for="veh_wheel_type" class="control-label">{{ __('Vehicle Type*') }}</label>
								<div class="row">
									<div class="on-radio">
										<input id="veh_wheel_type" type="radio" name="veh_wheel_type" value="3" autofocus> <span>Three Wheeler </span>
									</div>
									<div class="on-radio">
										<input id="veh_wheel_type" type="radio" name="veh_wheel_type" value="4" autofocus> <span>Four Wheeler </span>
									</div>
								</div>
								<label id="veh_wheel_type-error" class="error" for="veh_wheel_type"></label>
								<p class="help-block"></p>
								@if($errors->has('veh_wheel_type'))
									<p class="help-block text-red">
										{{ $errors->first('veh_wheel_type') }}
									</p>
								@endif
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 form-group">
								<div class="f-half">
									<label for="veh_capacity" class="control-label">{{ __('Carriage Capacity*') }}</label>
									<input id="veh_capacity" type="number" class="form-control" name="veh_capacity" value=""  autofocus>
									<p class="help-block"></p>
									@if($errors->has('veh_capacity'))
										<p class="help-block text-red">
											{{ $errors->first('veh_capacity') }}
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="veh_dimension" class="control-label">{{ __('Carriage Dimensions (LxBxH) (Feet)*') }}</label>
									<input id="veh_dimension" type="text" class="form-control" name="veh_dimension" value=""  autofocus>
									<p class="help-block"></p>
									@if($errors->has('veh_dimension'))
										<p class="help-block text-red">
											{{ $errors->first('veh_dimension') }}
										</p>
									@endif
								</div>
							</div>
							<div class="col-sm-6 form-group">
								<div class="f-half">
									<input id="veh_id" type="hidden" class="form-control" name="veh_id" value="" required >
											   
									<label for="veh_type" class="control-label">{{ __('Body Type *') }}</label>
									<select id="veh_type" type="text" class="form-control" name="veh_type" autofocus>
										<option value="">Select Body Type</option>
										<option value="2">Closed (Hard top)</option>
										<option value="1">Open </option>
										<option value="3">Tarpaulin covered (Soft top)</option>
									</select>
									<p class="help-block"></p>
									@if($errors->has('veh_type'))
										<p class="help-block text-red">
											{{ $errors->first('veh_type') }}
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="veh_city" class="control-label">{{ __('Base Station / Stand*') }}</label>
									<!--  pattern="^[a-zA-Z]+$" -->
<input type="text" id="veh_city" name="veh_city" class="search-outer form-control as-input" placeholder="Enter Location" required="" spellcheck="false" title="The Vehicles city Invalid" autofocus>
									<p class="help-block"></p>
									@if($errors->has('veh_city'))
										<p class="help-block text-red">
											{{ $errors->first('veh_city') }}
										</p>
									@endif
									<input id="lat" type="hidden" name="lat" />
		    						<input id="lng" type="hidden" name="lng" />
								</div>
							</div>
						</div>
						<div class="row">
		 					<div class="col-sm-6 form-group">
		 						<div class="f-half">
									<input id="veh_driver_id" type="hidden" class="form-control" name="veh_driver_id" value="" required autofocus>
									<label for="veh_registration_no" class="control-label">{{ __(' Registration No*') }}</label>
									<input id="veh_registration_no" type="text" class="form-control" name="veh_registration_no" value="" pattern="(([A-Za-z]){2,3}(|-)(?:[0-9]){1,2}(|-)(?:[A-Za-z]){2}(|-)([0-9]){1,4})|(([A-Za-z]){2,3}(|-)([0-9]){1,4})" title="The Vehicles Registration No Invalid Format" onkeyup="this.value = this.value.toUpperCase();" autofocus>
									<p class="help-block"></p>
									@if($errors->has('veh_registration_no'))
										<p class="help-block text-red">
											{{ $errors->first('veh_registration_no') }}
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="veh_color" class="control-label">{{ __('Vehicle Color*') }}</label>
									<select id="veh_color" name="veh_color" class="form-control select2" autofocus data-placeholder="Select Color">
										<option value="">Select Vehicle Color</option>
									   @if(!empty($colors))
											@foreach($colors as $color)
												<option style="background-color: {{ $color['name'] }} ;" value="{{ $color['id'] }}">{{ $color['name'] }}</option>                        
											@endforeach    
										@endif
									</select>
									<label id="veh_color-error" class="error" for="veh_color">
									</label>
									<p class="help-block"></p>
									@if($errors->has('veh_color'))
										<p class="help-block text-red">
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
										<option value="Electric">Electric</option>
										<option value="Non-electric">Non-electric</option>
									</select>

									<p class="help-block"></p>
									@if($errors->has('veh_fuel_type'))
										<p class="help-block text-red">
											{{ $errors->first('veh_fuel_type') }}
										</p>
									@endif
								</div>
							</div>
						</div>
						<div class="add-block">
							<h4>STANDARD CHARGE</h4>
							<div class="row">
								<div class="col-sm-4 form-group">
									<div class="row">
										<div class="std">
											<label for="veh_base_charge" class="control-label"><b>{{ __('Fixed Charge*') }}</b></label><br>
											<label>(Fixed for first 3Kms)</label>
										</div>
										<div class="std-input">
											<input id="veh_base_charge" type="number" class="form-control" name="veh_base_charge" value="" autofocus>
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
											<label for="veh_3km_15km" class="control-label"><b>{{ __('Additional per Km*') }}</b></label><br>
											<label>(Above 03 till 15 Kms)</label>
										</div>
										<div class="std-input">
											<input id="veh_3km_15km" type="number" class="form-control" name="veh_3km_15km" value="" required autofocus>
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
											<label for="veh_above_15km" class="control-label"><b>{{ __('Additional per Km*') }}</b></label><br>
											<label>(Above 15 Kms)</label>
										</div>
										<div class="std-input">
											<input id="veh_above_15km" type="number" class="form-control" name="veh_above_15km" value="" autofocus>
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
						        		<button class="btn btn-save" id="saveRatesBtn">save rates</button>
						        	</div>
						        	<div class="row">
						        		<a data-toggle="modal" class="rate" data-target="#rateChart" data-whatever="@fat"><button class="btn btn-chart"><span>show</span><br>Rate chart</button></a>
						        	</div>
								</div> -->
									<!-- <div class="f-half">
										<label for="veh_per_km" class="control-label">{{ __('Charge Per Km*') }}</label>
										<input id="veh_per_km" type="number" class="form-control" name="veh_per_km" value="" required autofocus>
										<p class="help-block"></p>
										@if($errors->has('veh_per_km'))
											<p class="help-block text-red">
												{{ $errors->first('veh_per_km') }}
											</p>
										@endif
									</div>   -->
									 
							</div>
							<hr class="line-spacer">
							<!-- <div class="row">
								<div class="col-sm-6 form-group">
									
									<div class="f-half">
										<label for="veh_30km_above" class="control-label">{{ __('Per kms charges (>30kms)*') }}</label>
										<input id="veh_30km_above" type="number" class="form-control" name="veh_30km_above" value="" required autofocus>
										<p class="help-block"></p>
										@if($errors->has('veh_30km_above'))
											<p class="help-block text-red">
												{{ $errors->first('veh_30km_above') }}
											</p>
										@endif
									</div>  
								</div>
							</div> -->		
							<div class="row">
								<div class="col-sm-3 form-group">
									<div class="first-line">
										<label for="veh_loader_available" class="control-label">{{ __('Loader Available*') }}</label>
										<div class="row">
											<div class="on-radio">
												<input id="veh_loader_available" type="radio" checked="true" name="veh_loader_available" value="1" autofocus onclick="loadertype(1)"><span>Yes</span>
											</div>
											<div class="on-radio">
												<input id="veh_loader_available" type="radio"  name="veh_loader_available" value="0" autofocus onclick="loadertype(0)"><span>No</span>
											</div>
										</div>
										<p class="help-block"></p>
										@if($errors->has('veh_loader_available'))
											<p class="help-block text-red">
												{{ $errors->first('veh_loader_available') }}
											</p>
										@endif
									</div>
								</div>
								<div class="col-sm-6 p-l-0" id="loader">                     
									<div class="f-half form-group">
										<label for="veh_no_person" class="control-label">{{ __('Number Of Person*') }}</label>
										<input id="veh_no_person" type="number" class="form-control" name="veh_no_person" value="" autofocus>
										<p class="help-block"></p>
										@if($errors->has('veh_no_person'))
											<p class="help-block text-red">
												{{ $errors->first('veh_no_person') }}
											</p>
										@endif
									</div>
									<div class="f-half form-group">
										<label for="veh_charge_per_person" class="control-label">{{ __('Charge Per Person*') }}</label>
										<input id="veh_charge_per_person" type="number" class="form-control" name="veh_charge_per_person" value="" autofocus>
										<p class="help-block"></p>
										@if($errors->has('veh_charge_per_person'))
											<p class="help-block text-red">
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
									<label for="veh_is_online" class="control-label">{{ __('Vehicles Online/Offline *') }}</label>
									<select id="veh_is_online" type="text" class="form-control" name="veh_is_online" required>
										<option value="">Select Vehicle Offline/Online</option>  
										<option value="1">Online</option>  
										<option value="0">Offline</option>  
									</select>
									<p class="help-block"></p>
									@if($errors->has('veh_is_online'))
										<p class="help-block text-red">
											{{ $errors->first('veh_is_online') }}
										</p>
									@endif
								</div>
								<div class="f-half">
									<label for="veh_images" class="control-label">{{ __('Upload Vehicle Images') }}</label>
									<input id="veh_images" type="file" class="form-control p-0 vehicle-photo-add" name="veh_images[]" autofocus multiple="multiple" accept="image/*" onchange="preview_image();">
									<!-- <p class="help-block1" id="veh_imag"></p> -->
									@if($errors->has('veh_images'))
										<p class="help-block text-red">
											{{ $errors->first('veh_images') }}
										</p>
									@endif
								</div>
								<!-- <input type="file" multiple id="vehicle-photo-add"> -->
							</div>
							<div class="col-sm-6 form-group" id="veh_images_div" style="display: none">
								<label for="view_veh_images" class="control-label">{{ __('View Vehicle Images') }}</label><br>
								<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#veh_imags" ></i>
							</div>
						</div>			
						<div class="row">
							<div class="col-sm-4 form-group">
								<div class="first-line"><br>
									<label for="is_active" class="control-label">{{ __('Vehicles Active*') }}</label>
									<div class="row">
										<div class="on-radio">
											<input id="is_active" type="radio"  name="is_active" checked="true" value="1"  autofocus><span>Active</span>
										</div>
										<div class="on-radio">
											<input id="is_active" type="radio"  name="is_active" value="0" autofocus><span>Deactive</span>
										</div>
									</div>
									<p class="help-block"></p>
									@if($errors->has('is_active'))
										<p class="help-block text-red">
											{{ $errors->first('is_active') }}
										</p>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div id="vehicle_add_doc" class="form-group m-form__group"></div>
								<div class="col-sm-3 m--margin-bottom-15">
									<button type="button" name="add" id="add_more_vehicle_doc" class="addMorePadding btn btn-sm btn-info m-l-5"><span><i class="la la-plus"></i>
										</span>Additional Documents</button>
								</div>
							</div>
						</div> <br>
						<div class="row">
							<div class="col-sm-6 form-group">
								<div class="ver-check">
									<input id="vehicle_is_verified" type="checkbox" name="vehicle_is_verified"  autofocus>
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
						<!-- operator type business -->
						<div class="row">
							<div class="col-sm-12 form-group">
								<div class="btn-b-u">
									<a href="{{ route('operators.edit',[Request::get('op')])}}" class="btn btn-warning">Back</a>
									<button type="submit" class="btn btn-success" id="submitFormbtn" value="Validate!">{{ __('Save') }}</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- view vehicle images modal -->
	<div class="modal fade" id="veh_imags">
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


<!-- ***MapmyIndia Integration Begins***-->
<script>
    var marker;
    var flag=true;
    /Map Initialization/
    function initMap1(){
            var optional_config = {
                location: [28.61, 77.23],
                region: "IND",
                height:300,
                searchChars:4,
            };
            var veh_city = new mappls.search(document.getElementById("veh_city"), optional_config, callback,);
            
            function callback(data) {
                console.log('data',data);
                obj = data;
                if (data) {
                    var dt = data[0];
                    // console.log("DT",dt);
                    if (!dt) return false;
                    var placeAddress = dt.placeAddress;
                    var place = dt.placeName + ", " + dt.placeAddress; 
                	var elocdata =new mappls.getPinDetails({ pin: dt.eLoc},callback);
					$('#lat').val(obj.data.latitude);
					$('#lng').val(obj.data.longitude);
					console.log('lat',obj.data.latitude);
                	console.log('long',obj.data.longitude);
                }
        }
    }
</script>
<!-- **MapmyIndia Integration Ends***-->


<script type="text/javascript">
	// form validation
    $(document).ready(function () {
    	$("#submitFormbtn").attr("disabled", false);
    	$("#saveRatesBtn").attr("disabled", false);
    	
    	$('#AddVehicle').on('submit', function(e){
			if($("#AddVehicle").valid()){
				console.log('form is valid');
				 $("#submitFormbtn").attr("disabled", true);
				 $("#saveRatesBtn").attr("disabled", true);
			}else{
				console.log('form is invalid');
			}
		});

    	$("#AddVehicle").validate({
			rules: {
				veh_op_ownership: {
					required: true,
				},
				veh_owner_name: {
					required: {
				        depends:function(){
				            $(this).val($.trim($(this).val()));
				            return $("#veh_op_ownership").val() == 0;
				        }
				    },
				},
				veh_owner_mobile_no: {
					required: function(element){
			            return $("#veh_op_ownership").val() == 0;
			        },
			        check_mobile : true,
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
				veh_registration_no :{
					required: {
				        depends:function(){
				            $(this).val($.trim($(this).val()));
				            return true;
				        }
				    },
				},
				veh_color :{
					required: {
				        depends:function(){
				            $(this).val($.trim($(this).val()));
				            return true;
				        }
				    },
				},
				veh_fuel_type :{
					required: true,
				},
				veh_base_charge :{
					required: {
				        depends:function(){
				            $(this).val($.trim($(this).val()));
				            return true;
				        }
				    },
				},
				// veh_per_km :{
				// 	required: true,
				// 	digits: true,
				// },

				veh_3km_15km :{
					required: true,
					digits: true,
				},
				veh_above_15km :{
					required: true,
					digits: true,
				},
				// veh_30km_above :{
				// 	required: true,
				// 	digits: true,
				// },
				veh_is_online :{
					required: true,
				},
				"veh_images[]": {
					required: true,
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
				// veh_30km_above :{
				// 	required:"Please enter charge",
				// },
				veh_is_online :{
					required:"Please select vehicle offline / online status",
				},
				"veh_images[]":{ 
					required:"Please upload vehicle images",
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

    	jQuery.validator.addMethod("check_mobile", function(value, element) {
  			return value.match(/^((\\+91-?)|0)?[0-9]{10}$/)
		}, "Please enter valid mobile number");
 	});	
	//form validation-end

	// form images-validation
	var limit = 5;
	$(document).ready(function() {
		// add veh-img validation
		jQuery.validator.addMethod("check_veh_images_count", function(value, element) {
  			var filesArray = document.getElementById("veh_images").files;
  			console.log('vehicle images length');
  			console.log(filesArray.length);
			if(filesArray.length > 5 || filesArray.length < 4){
				return false;
			}else{
				return true;
			}
		});
		// veh-img validation end
	
		// vehicle images
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
		// vehicle images end

		// add-more documents
		var p_i = 0;
		$('#add_more_vehicle_doc').click(function(){
			$('#vehicle_add_doc').append('<div id="row'+p_i+'" class="upload"><label>Upload More Documents (Optional)</label><div class="form-group m-form__group row"><div class="col-sm-6"><div class="f-half"><select id="select'+p_i+'" name="additional_documents['+p_i+'][doc_type_id]" class="select-2 dropdown-list-style form-control file_type select_documents fillItem" preview-name="Selected Documents"><option value="" disabled selected="selected">Select Document</option>@if(!empty($additionalDocList))    @foreach ($additionalDocList as $documents)<option class="" value="{{$documents["doc_type_id"]}}">{{$documents["doc_label"]}}</option> @endforeach  @endif  </select></div><div class="f-half"><input class="form-control" type="" name="additional_documents['+p_i+'][doc_number]"></div></div><div class="col-sm-6"><div class="input-group"><input id="lic_validity_'+p_i+'" type="text" class="form-control date-picker" name="additional_documents['+p_i+'][doc_expiry]" autofocus><div class="input-group-addon calender"><i class="fa fa-calendar"></i></div><div class="f-half"><input class="form-control p-0" type="file" name="additional_documents['+p_i+'][doc_images]"></div><div class="f-half"><button type="button" name="remove" id="'+p_i+'" class="btn btn-danger btn_remove btn-sm r-doc-btn">Remove</button></div></div></div><div class="input-group-append"><div id="selectDoc'+p_i+'"></div></div></div>');
			p_i++;

			$(document).on('click', '.btn_remove', function(){
				var button_id = $(this).attr("id");
				$('#row'+button_id).remove();
			});

			/*
			$(document).on('click','#select'+p_i, function(){
				console.log('drop down selected');
				$('#select'+p_i+' option:eq(' + index + ')').remove();
			}); */
		});

		if($("#veh_op_ownership").val()==0){
			$("#Ownershiptype").hide();
			// $("#veh_owner_name").attr('required',false);            
			// $("#veh_owner_mobile_no").attr('required',false); 
			$("#veh_owner_name").val('');
			$("#veh_owner_mobile_no").val(''); 
		}else{
			$("#Ownershiptype").show();
			// $("#veh_owner_name").attr('required',true);
			// $("#veh_owner_mobile_no").attr('required',true);   
	   }

	   if($("#veh_loader_available").val()==0){
		   $("#loader").hide();
			$("#veh_no_person").attr('required',false);
			$("#veh_charge_per_person").attr('required',false); 
		}else {
			$("#loader").show();
			$("#veh_no_person").attr('required',true);
			$("#veh_charge_per_person").attr('required',true);   
		}
	});

	function Ownershiptype(type){   

		if(type==0){
			$("#Ownershiptype").hide();
			$("#veh_owner_name").attr('required',false);            
			$("#veh_owner_mobile_no").attr('required',false); 
			$("#veh_owner_name").val('');
			$("#veh_owner_mobile_no").val(''); 
		}else{
			$("#Ownershiptype").show();
			$("#veh_owner_name").attr('required',true);
			$("#veh_owner_mobile_no").attr('required',true);   
		}
	}

	function getmodeltype(){
		var modeltypename=$("#veh_make_model_type").val();
		$('#veh_dimension').val('');
		$('#veh_capacity').val('');
		// $('#veh_wheel_type').val('');
		// $('input[name="veh_wheel_type"]').html('');
		// alert($('input[name="veh_wheel_type"]').val());
		$('#veh_model_name').html("");
		$.ajax({
			url :"{{ route('getmodelname') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"modeltypename": modeltypename
			},        
			success : function(data){
				$('#veh_model_name').html("");
				$.each(data, function (key, value){
					$('#veh_model_name').append('<option value="'+ data[key]['veh_id'] +'"selected="selected">' + data[key]['veh_model_name'] + '</option>');
				});
			}
		});
	}

	function getcapacity(){
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
				$.each(data, function (key, value){
					console.log(data);

					if(data[key]['veh_fac_master_id']==2){
						$("#veh_capacity").val(data[key]['veh_fac_value']);
						console.log(data[key]['veh_fac_value']); 
					}
					if(data[key]['veh_fac_master_id']==4){
						$("#veh_dimension").val(data[key]['veh_fac_value']); 
					}
					if(data[key]['veh_fac_master_id']==1){
						// alert(data[key]['veh_fac_master_id']);
						// alert(JSON.stringify($('#veh_wheel_type').val('')));
						$("input[name=veh_wheel_type][value=" + data[key]['veh_fac_value'] + "]").attr('checked', 'true');
					}            
				});
			}
		});
	}

	function loadertype(type){
		if(type==0)
		{
		   $("#loader").hide();
			$("#veh_no_person").attr('required',false);
			$("#veh_charge_per_person").attr('required',false); 
		}else {
			$("#loader").show();
			$("#veh_no_person").attr('required',true);
			$("#veh_charge_per_person").attr('required',true);   
		}
	}

	function preview_image() 
	{
		$('#vehicle_view_image').html('');
		var total_file=document.getElementById("veh_images").files.length;
		if(total_file > 0){
			$('#veh_images_div').show()
			for(var i=0;i<total_file;i++)
			{
				$('#vehicle_view_image').append("<img src='"+URL.createObjectURL(event.target.files[i])+"' class='veh-image'>");
			}
		}else{
			$('#veh_images_div').hide()
			$('#vehicle_view_image').html('');
		}
	}

	// googleapi location
	/**
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
	}*/
	// googleapi location end

</script>
@endsection

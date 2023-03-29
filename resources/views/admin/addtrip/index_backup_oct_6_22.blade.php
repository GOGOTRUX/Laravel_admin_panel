
@extends('layouts.app')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Main Content -->
@section('content')
<!-- <script src="https://apis.mapmyindia.com/advancedmaps/v1/195e6ca20d5b1ff9fa427351b4aa0d9f/map_load?v=1.5"></script>
<script src="https://apis.mapmyindia.com/advancedmaps/api/86b3ab66-854b-4d28-a28f-cc8ce3fa5057/map_sdk_plugins"></script> -->

<script src="https://apis.mappls.com/advancedmaps/api/{{$mapToken}}/map_sdk?layer=vector&v=3.0&callback=initMap1"></script>
<script src="https://apis.mappls.com/advancedmaps/api/{{$mapToken}}/map_sdk_plugins?v=3.0"></script>
<script src="https://apis.mappls.com/advancedmaps/api/{{$mapToken}}/map_sdk_plugins?v=3.0&libraries=getPinDetails"></script>

    
<!-- <script src="https://apis.mappls.com/advancedmaps/api/1d80ea20-4f10-4ed2-871f-d1a766ab0c42/map_sdk?layer=vector&v=3.0&callback=initMap1"></script>
<script src="https://apis.mappls.com/advancedmaps/api/1d80ea20-4f10-4ed2-871f-d1a766ab0c42/map_sdk_plugins?v=3.0"></script> -->
    


<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success" id="message" style="display:none;">
				{{ session('success') }}
			</div>
		@endif
		<div class="panel-body p-0">
			<div class="view-trip">
				<div class="trip_section p-b-0">
					<!-- add trip design -->
					<div class="section-addTrip">
					    <div class="stepwizard">
					        <div class="stepwizard-row setup-panel">
					            <div class="stepwizard-step col-xs-6"> 
					                <a href="#step-1" type="button" class="btn btn-success btn-circle">1</a>
					                <p>Enter Booking Details / Search And Select</p>
					            </div>
					           <!--  <div class="stepwizard-step col-xs-4"> 
					                <a href="#step-2" type="button" id="searchTab" class="btn btn-default btn-circle" disabled="disabled">2</a>
					                <p><small>Search And Select</small></p>
					            </div> -->
					            <div class="stepwizard-step col-xs-6"> 
					                <a href="#step-3" type="button" id="confirmTab" class="btn btn-default btn-circle" disabled="disabled">2</a>
					                <p><small>Confirm</small></p>
					            </div>
					        </div>
					    </div>
					    <div class="setup-content" id="step-1">
					    	<div class="row">
					    		<div class="col-md-5 p-0">
					    			<form method="POST" id="enterdetailsForm" name="enter_details_form"> 
										@csrf
									
							            <div class="panel-body p-0">
							            	<div class="row detail-box">
							            		<div class="col-md-6">
													<div class="form-group">
											<input type="hidden" name="book_op_id" id="book_op_id" class="form-control" value="{{ session('bookPartnerId') }} "/>
								                    	<input type="text" name="cust_name" id="cust_name" class="form-control" value="{{ Session::has('editTripData') ? session('editTripData')[0]->user_first_name : ''}} {{ Session::has('editTripData') ? session('editTripData')[0]->user_last_name : ''}}"/>
								                    	<label class="control-label">Customer Name</label>
								                	</div>
													
								                	<div class="form-group">
								                    	<input type="text" name="cust_cid" id="cust_cid" class="form-control" value="{{ Session::has('editTripData') ? session('editTripData')[0]->user_uid : ''}}"/>
								                    	<label class="control-label">Customer CID</label>
								                	</div>
													
								                	<div class="form-group">
								                		<div class="input-group">
															<input id="pickup_date" type="text" class="form-control date-picker" name="pickup_date" data-provide="datepicker" value="{{ Session::has('editTripData') ? session('editTripData')[0]->book_date : ''}}">
															<div class="input-group-addon calender" id="calender-div">
																<i class="fa fa-calendar"></i>
															</div>
															<p class="help-block-message"></p>
															@if($errors->has('pickup_date'))
																<p class="help-block-message">
																	{{ $errors->first('pickup_date') }}
																</p>
															@endif
														</div>
								                    	<label class="control-label">Pick-Up Date <sup>*</sup></label>
														<label id="pickup_date-error" class="error" for="pickup_date"></label>
								                	</div>
								                	<div class="form-group">
								                    	<input type="text" name="pickup_location" id="pickup_location" class="form-control" data-toggle="modal" data-target="#pick-up" value="{{ Session::has('editTripData') ? session('editTripData')[0]->start_address_line_1 : ''}}"/>
								                    	<p class="help-block-message"></p>
														@if($errors->has('pickup_location'))
															<p class="help-block-message">
																{{ $errors->first('pickup_location') }}
															</p>
														@endif
								                    	<label class="control-label">Pick-Up Location <sup>*</sup></label>
								                    	<label id="pickup_location-error" class="error" for="pickup_location"></label>
								                	</div>
													<!-- <div class="form-group">
								                    	<input type="text" name="distance" id="distance" class="form-control" disabled value=""/>
								                    	<label class="control-label">Distance </label>
								                	</div> -->
								                	<div class="form-group">
								                    	<select class="form-control" id="material_type" name="material_type"  data-placeholder="Select Material Type">
															<option value="{{ Session::has('editTripData') ? session('editTripData')[0]->material_type : ''}}">{{ Session::has('editTripData') ? session('editTripData')[0]->material_type : ''}}</option>
															<option value="">Select Material Type</option>
															@if(!empty($materialModels))
																@foreach($materialModels as $materialModels)
																<option value="{{ $materialModels['material_type'] }}">
																{{ $materialModels['material_type'] }}
																</option>                     
																@endforeach
															@endif
														</select>
														@if($errors->has('material_type'))
															<p class="help-block-message">
																{{ $errors->first('material_type') }}
															</p>
														@endif
								                    	<label class="control-label">Material <sup>*</sup></label>
								                    	<label id="material_type-error" class="error" for="material_type"></label>
								                	</div>
								                	<div class="form-group">
								                    	<select class="form-control" name="vehicle_type" id="vehicle_type">
												<option value="{{ Session::has('editTripData') ? session('editTripData')[0]->vehicle_type : ''}}">{{ Session::has('editTripData') ? session('editTripData')[0]->vehicle_type : ''}}</option>
								                    		<option value="" disabled>Select Vehicle Type</option>
								                    		<option value="2">Closed</option>
								                    		<option value="1">Open</option>
								                    		<option value="0">Tarpaulin Covered (Soft Top)</option>
								                    	</select>
								                    	@if($errors->has('vehicle_type'))
															<p class="help-block-message">
																{{ $errors->first('vehicle_type') }}
															</p>
														@endif
								                    	<label class="control-label">Vehicle Type <sup>*</sup></label>
								                    	<label id="vehicle_type-error" class="error" for="vehicle_type"></label>
								                	</div>
								                	<div class="form-group">
								                		<div class="row">
								                    		<div class="col-xs-4 p-0">
								           						<label class="left-check-box">
								                    				<input type="checkbox" name="eTrux"/>e-Trux
								                    				<span class="check-mark"></span>
								                    			</label>
								                    			<input type="hidden" name="vehicle_fuel_type" value="Non-electric"/>
								                    		</div>
								                			<div class="col-xs-4 p-0">
								           						<label class="left-radio-box">Cash
								                    				<input type="radio" name="payment_mode" value="cash"/>				                	
								                    				<span class="radio-btn"></span>
								                    			</label>
								                			</div>
								                			<div class="col-xs-4 p-0">
								           						<label class="left-radio-box">InstaBid
								                    				<input type="radio" name="user_bid_mode" value="1" disabled/>                				
								                    				<span class="radio-btn"></span>
								                    			</label>
								                			</div>
								                    	</div>
								                    	<div class="row">
								                    		<div class="col-xs-4 p-0">
								           						<label class="left-check-box">
								                    				<input type="checkbox" name="offline" value="1" checked/>Offline
								                    				<span class="check-mark"></span>
								                    				@if($errors->has('offline'))
																		<p class="help-block-message">
																			{{ $errors->first('offline') }}
																		</p>
																	@endif
									                    			<label id="offline-error" class="error" for="offline"></label>
								                    			</label>
								                    		</div>
								                			<div class="col-xs-4 p-0">
								           						<label class="left-radio-box">Digital
								                					<input type="radio" name="payment_mode" value="digital"/>
								                    				<span class="radio-btn"></span>
								                    				@if($errors->has('payment_mode'))
																		<p class="help-block-message">
																			{{ $errors->first('payment_mode') }}
																		</p>
																	@endif
								                    				<label id="payment_mode-error" class="error" for="payment_mode"></label>			
								                				</label>				                			
								                			</div>
								                    		<div class="col-xs-4 p-0">
								           						<label class="left-radio-box">
								                    				<input type="radio" name="user_bid_mode" value="0" checked/>Enquiry
								                    				<span class="radio-btn"></span>
								                    				@if($errors->has('user_bid_mode'))
																		<p class="help-block-message">
																			{{ $errors->first('user_bid_mode') }}
																		</p>
																	@endif
								                    				<label id="user_bid_mode-error" class="error" for="user_bid_mode"></label>
								                    			</label>
								                    		</div>
								                    	</div>
								                	</div>
							            		</div>							            	
								            	<div class="col-md-6 right">
								            		<div class="form-group">
								                    	<!-- <input type="text" name="cust_base_location" id="cust_base_location" class="form-control"/> -->
								                    	<input type="text" id="cust_base_location" name="cust_base_location" class="search-outer form-control as-input" placeholder="Search places or eLoc's..." spellcheck="false"/>
								                    	<label for="cust_base_location" class="control-label">Customer Base Location</label>
								                	</div>
									                <div class="form-group">
									                    <input type="text" name="cust_mobile" id="cust_mobile" class="form-control" value="{{ Session::has('editTripData') ? session('editTripData')[0]->user_mobile_no : ''}}"/>
									                    @if($errors->has('cust_mobile'))
															<p class="help-block-message">
																{{ $errors->first('cust_mobile') }}
															</p>
														@endif
									                    <label class="control-label">Customer Regd. Mobile <sup>*</sup></label>
									                    <label id="cust_mobile-error" class="error" for="cust_mobile"></label>
									                </div>
									                <div class="form-group">
								                    	<div class="input-group clockpicker">
														    <input type="text" class="form-control" id="pickup_time" name="pickup_time" value="{{ Session::has('editTripData') ? session('editTripData')[0]->book_time : ''}}">
														    @if($errors->has('pickup_time'))
																<p class="help-block-message">
																	{{ $errors->first('pickup_time') }}
																</p>
															@endif
														    <span class="input-group-addon">
														        <span class="glyphicon glyphicon-time"></span>
														    </span>
														</div>
								                    	<label class="control-label">Pick-Up Time <sup>*</sup></label>
														<label id="pickup_time-error" class="error" for="pickup_time"></label>
								                	</div>
								                	<div class="form-group">
									                    <!--<input type="text" id="delivery_location" name="delivery_location" class="form-control" data-toggle="modal" data-target="#deliveryLoc" value="{{ Session::has('editTripData') ? session('editTripData')[0]->intermediate_address[0]->dest_address_line_1 : ''}}"/>-->
											    <input type="text" id="delivery_location" name="delivery_location" class="form-control" data-toggle="modal" data-target="#deliveryLoc" value="{{ Session::has('editTripData') ? session('editTripData')[0]->intermediate_address[0]->dest_address_line_1 : ''}}"/>

									                    @if($errors->has('delivery_location'))
															<p class="help-block-message" id="hide_delivery">
																{{ $errors->first('delivery_location') }}
															</p>
														@endif
									                    <label class="control-label">Delivery Location <sup>*</sup></label>
									                    <label id="delivery_location-error" class="error" for="delivery_location"></label>
									                </div>
													<!-- <div class="form-group">
								                    	<input type="text" name="arival_time" id="arival_time" class="form-control" disabled value=""/>
								                    	<label class="control-label">Arival Time</label>
								                	</div> -->
									                <div class="form-group">
								                    	<input type="text" name="weight" id="weight" class="form-control" value="{{ Session::has('editTripData') ? session('editTripData')[0]->weight : ''}}"/>
								                    	@if($errors->has('weight'))
															<p class="help-block-message">
																{{ $errors->first('weight') }}
															</p>
														@endif
								                    	<label class="control-label">Weight (Kgs) <sup>*</sup></label>
								                    	<label id="weight-error" class="error" for="weight"></label>
								                	</div>
								                	<div class="form-group">
								                    	<input type="text" name="loader_count" id="loader_count" class="form-control" value="0"/>
								                    	<label class="control-label">Helper</label>
								                	</div>
								                	<div class="form-group">
								                		<label class="check-box">
								                			Send Notification to Customer
								                    		<input type="checkbox" name="offline"/>
								                    		<span class="check-mark"></span>
								                    	</label>
								            			<button class="btn btn-primary nextBtn pull-right" type="submit" id='start_call'>SEARCH</button>
								                	</div>
									            </div>
									        </div>
							            </div>
							            <!--pick up location modal -->
										<div class="modal fade" id="pick-up" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									  		<div class="modal-dialog -lg" role="document">
									    		<div class="modal-content">
									    			<div class="modal-header add-modal-header">
														<h4 class="modal-title" id="modal-basic-title">Enter Detailed Address To Get Lowest Rates</h4>
														<span><i class="fa fa-map-marker"></i></span>
													</div>
													<div class="modal-body add-b-modal">
														<div class="card">
															<div>
																<div>
																	
																	<div class="row">
																		<div class="form-group col-l-half">
																			<!-- <input class="form-control" name="start_address_line_1" id="start_address_line_1" placeholder="Enter pin location" value="{{ Session::has('editTripData') ? session('editTripData')[0]->start_address_line_1 : ''}}"> 
																			@if($errors->has('start_address_line_1'))
																			<p class="help-block-message">
																				{{ $errors->first('start_address_line_1') }}
																			</p>
																			@endif		 -->
																			<input id="pickup_address_lat" type="hidden" name="start_address_lat" value="{{ Session::has('editTripData') ? session('editTripData')[0]->start_address_lat : ''}}" />
					    													<input id="pickup_address_lng" type="hidden" name="start_address_lng" value="{{ Session::has('editTripData') ? session('editTripData')[0]->start_address_lan : ''}}" /> 
        																	<input type="text" id="autopick" name="start_address_line_1" class="search-outer form-control as-input" placeholder="Search places or eLoc's..." required="" spellcheck="false">
																			<label for="nearesttxt">Nearest Location<sup class="sup-e">*</sup></label>
																			<!-- <div id="map"></div> -->
																		</div>
																		<div class="form-group col-r-half">
																			<input type="text" class="form-control" name="pickup_address_pin" id="pin1" placeholder="Enter pincode" value="{{ Session::has('editTripData') ? session('editTripData')[0]->start_pincode : ''}}"/>
																			<label for="">Area PIN Code</label>
																		</div>
																	</div>
																	<hr>
																	<label class="add-info">Enter Correct Address for Timely Pickup of Material</label> 
																	<div class="row">
																		<div class="form-group col-l-half">
																			<input type="text" class="form-control" name="pickup_user_name" id="cust_name"/>
																			@if($errors->has('pickup_user_name'))
																				<p class="help-block-message">
																					{{ $errors->first('pickup_user_name') }}
																				</p>
																			@endif	
																			<label for="">Name</label>
																		</div>
																		<div class="form-group col-r-half">
																			<input type="text" class="form-control" name="pickup_mobile" name="pickup_mobile" id="pickup_mobile" value=""/>
																			@if($errors->has('pickup_mobile'))
																				<p class="help-block-message">
																					{{ $errors->first('pickup_mobile') }}
																				</p>
																			@endif	
																			<label id="pickup_mobile" class="error" for="pickup_mobile"></label>
																			<label for="">Mobile No.<sup class="sup-e">*</sup></label>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-sm-12 form-group">
																			<input type="text" class="form-control" name="start_address_line_2" id="start_address_line_2" value="{{ Session::has('editTripData') ? session('editTripData')[0]->start_address_line_2 : ''}}"/>
																			@if($errors->has('start_address_line_2'))
																				<p class="help-block-message">
																					{{ $errors->first('start_address_line_2') }}
																				</p>
																			@endif	
																			<label id="pick_house_flat" class="error" for="start_address_line_2"></label>
																			<label for="">House/Flat/Shop No.<sup class="sup-e">*</sup></label>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-sm-12 form-group">
																			<input type="text" class="form-control" name="start_address_line_3" id="pick_complex_society" value="{{ Session::has('editTripData') ? session('editTripData')[0]->start_address_line_3 : ''}}"/>
																			<label for="">Complex/Society/Market</label>
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-sm-12">
																			<input type="text" class="form-control" name="start_address_line_4" id="pick_pickup_road" value="{{ Session::has('editTripData') ? session('editTripData')[0]->start_address_line_4 : ''}}"/>
																			<label for="">Area/Road</label>
																		</div>
																	</div>
																	<div class="row text-right p-0">
																		<button class="btn btn-success" data-dismiss="modal" type="button">Submit</button>
																		<button class="btn btn-warning m-l-10" data-dismiss="modal" type="button">Close</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
									    		</div>
									  		</div>
										</div>
										<!--pick up location modal End-->
										<!--Delivery location modal -->	
										<div class="modal fade" id="deliveryLoc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									  		<div class="modal-dialog" role="document">
									    		<div class="modal-content">
									    			<div class="modal-header add-modal-header">
														<h4 class="modal-title" id="modal-basic-title">Enter Detailed Address To Get Lowest Rates</h4>
														<span><i class="fa fa-map-marker"></i></span>
													</div>
													<div class="modal-body add-b-modal">
														<div class="card">
															<div>
																<div>
																	<div class="add_dest">
																		<button id="mult_destinations" class="btn add-on" type="button">Add More</button>
																	</div>
																	<div class="dest_detail">
																		<div class="row">
																			<div class="form-group col-l-half">
																				<!-- <input class="form-control" onclick="dropAddressLatLong('dest_address_line_1','delivery_address_lat','delivery_address_lng')" name="dest_address_line_1[]" id="dest_address_line_1" placeholder="Enter destination" >
																				@if($errors->has('dest_address_line_1'))
																					<p class="help-block-message">
																						{{ $errors->first('dest_address_line_1') }}
																					</p>
																				@endif
																				<label for="">Nearest Location<sup class="sup-e">*</sup></label> -->
																				<input id="delivery_address_lat" type="hidden" name="dest_address_lat[]" value="{{ Session::has('editTripData') ? session('editTripData')[0]->intermediate_address[0]->dest_address_lat : ''}}" />
					    														<input id="delivery_address_lng" type="hidden" name="dest_address_lan[]" value="{{ Session::has('editTripData') ? session('editTripData')[0]->intermediate_address[0]->dest_address_lan : ''}}" />
																				<input type="text" id="autodrop" name="dest_address_line_1[]"  class="search-outer form-control as-input" placeholder="Enter Destination" required="" spellcheck="false">
																				<label for="autodrop">Nearest Location<sup class="sup-e">*</sup></label>
																				<!-- <div id="mapdrop"></div> -->
																			</div>
																			<div class="form-group col-r-half">
																				<input type="text" class="form-control" name="delivery_address_pin" id="delivery_address_pin" placeholder="Enter pincode" />
																				<label for="">Area PIN Code</label>
																			</div>
																		</div>
																		<hr>
																		<label class="add-info">Enter Correct Address For Timely Deliver Of Material</label>
																		<div class="row">
																			<div class="form-group col-l-half">
																				<input type="text" class="form-control" name="delivery_user_name" id="cust_name"/>
																				<label for="">Name</label>
																			</div>
																			<div class="form-group col-r-half">
																				<input type="text" class="form-control" name="delivery_user_mobile" id="delivery_user_mobile"/>
																				@if($errors->has('delivery_user_mobile'))
																				<p class="help-block-message">
																					{{ $errors->first('delivery_user_mobile') }}
																				</p>
																				@endif	
																				<label id="delivery_user_mobile" class="error" for="delivery_user_mobile"></label>
																				<label for="">Mobile No.<sup class="sup-e">*</sup></label>
																			</div>
																		</div> 
																		<div class="row">
																			<div class="col-sm-12 form-group">
																				<input type="text" class="form-control" name="dest_address_line_2" id="dest_address_line_2"/>
																				@if($errors->has('dest_address_line_2'))
																				<p class="help-block-message">
																					{{ $errors->first('dest_address_line_2') }}
																				</p>
																				@endif	
																				<label id="dest_address_line_2" class="error" for="dest_address_line_2"></label>
																				<label for="">House/Flat/Shop No.<sup class="sup-e">*</sup></label>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-sm-12 form-group">
																				<input type="text" class="form-control" name="dest_address_line_3" id="complex_society" />
																				<label for="">Complex/Society/Market</label>
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-sm-12">
																				<input type="text" class="form-control" name="dest_address_line_4" id="pickup_road" />
																				<label for="">Area/Road</label>
																			</div>
																		</div>
																	</div>
																	<div id="add_more_dest" class="dest_detail"></div>
																	<div class="row text-right p-0">
																		<button class="btn btn-success" data-dismiss="modal" >Submit</button>
																		<button class="btn btn-warning m-l-10" data-dismiss="modal" >Close</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
									    		</div>
									  		</div>
										</div>
										<!--Delivery modal end here-->	
									</form>	
					        	</div>
					        	<div class="col-md-7 p-0">
							        <div class="panel-body search-result">
						                <!--search and select start-->
						                <div class="row" id="pickup-drop-distance">

						                </div>
							            <div class="veh-select" id="veh-select">
							            	
										</div>
									    <div id='loading'></div>
									</div>	
								</div>						        		
					    	</div>
					    </div>
						<div class="setup-content" id="step-3">
				            <div class="panel-body">
				            	<div class="row">
				            		<div class="col-sm-7 brd-1 p-0">
				            			<div class="row">
						            		<div class="col-md-4">
						            			<h5 class="mt-title">Selected MT Detail</h5>
						            			<div class="selected-mt">
							            			<div class="mt-info" id="mt-info">
									@if (Session::has('editTripIsSaved') || Session::has('editTripIsBooked'))
<input type="hidden" id="notiId" name="notiId" value="{{ session('editTripData')[0]->trip_notification_id }}"><input type="hidden" id="tripId" name="tripId" value="{{ session('editTripData')[0]->trip_transaction_id }}">
<table><tr><td>Make-Model</td><td>:</td><td>{{ session('editTripData')[0]->veh_make_model_type }} {{ session('editTripData')[0]->veh_model_name }}</td></tr><tr><td>Type</td><td>:</td><td>{{ session('editTripData')[0]->veh_wheel_type }} W</td></tr><tr><td>Capacity</td><td>:</td><td>{{ session('editTripData')[0]->veh_capacity }} Kg</td></tr><tr><td>Carriage</td><td>:</td><td>{{ session('editTripData')[0]->veh_dimension }} - Ft</td></tr><tr><td>Base</td><td>:</td><td>{{ session('editTripData')[0]->veh_city }}</td></tr><tr><td>Loader</td><td>:</td><td>Rs<span>{{ session('editTripData')[0]->veh_charge_per_person }}</span></td></tr><tr><td>Rate/ km</td><td>:</td><td>Rs {{ session('editTripData')[0]->veh_base_charge }}</td></tr><tr><td class="v-red">Amount</td><td>:</td><td class="v-red">Rs {{ session('editTripData')[0]->actual_amount }}</td></tr><tr><td>Tariff</td><td>:</td><td>Rs {{ session('editTripData')[0]->base_amount }}</td></tr><tr><td>Location</td><td>:</td><td>-</td></tr><tr><td class="v-red">Distance</td><td>:</td><td class="v-red"></td></tr><tr><td>UID</td><td>:</td><td>{{ session('editTripData')[0]->op_uid }}</td></tr><tr><td class="v-purpal">Name</td><td>:</td><td class="v-purpal">{{ session('editTripData')[0]->driver_first_name }} {{ session('editTripData')[0]->driver_last_name }}</td></tr><tr><td class="v-purpal">Mobile No</td><td>:</td><td class="v-purpal">{{ session('editTripData')[0]->driver_mobile_number }}</td></tr><tr><td>Balance</td><td>:</td><td>Rs <span class="v-red">0</span></td></tr></table>
                                                        @endif
													</div>
													<!-- <div class="mt-img">
														<div class="img-box">
															<img src="" alt="vehicle image" class="img-responsive">
														</div>
														<div class="img-box">
															<img src="" alt="vehicle image" class="img-responsive">
														</div>
														<div class="img-box">
															<img src="" alt="vehicle image" class="img-responsive">
														</div>
													</div> -->
												</div>
						            		</div>
						            		<div class="col-md-8 pay-info">
						            			<h5 class="pay-title">Payment</h5>
						            			<div class="row">
						            				<div class="col-sm-4 form-group">
									           			<label class="left-radio-box">Cash
						            						<input type="radio" name="send-link" value="cash"/>
									                    	<span class="radio-btn"></span>
						            					</label>
						            				</div>
						            				<div class="col-sm-4 form-group">
									           			<label class="left-radio-box">Send Link
						            						<input type="radio" name="send-link" value="razorpay"/>
									                    	<span class="radio-btn"></span>
						            					</label>
						            				</div>
						            				<div class="col-sm-4 form-group">
									           			<label class="left-radio-box">Later
						            						<input type="radio" name="send-link" value="later"/>
									                    	<span class="radio-btn"></span>
						            					</label>
						            				</div>
						            			</div>
						            			<div class="row">
						            				<div class="col-sm-6 form-group">
						            					<!--<input class="form-control" type="text" id="cash_otp" name="cash_otp" /> 
						            					<label>Cash OTP</label>-->
												<div class="col-sm-6">
						            							<input class="form-control" type="text" id="cash_otp" name="cash_otp" /> 
						            							<label>Cash OTP</label>
						            						</div>
						            						<div class="col-sm-6">
						            							<button class="btn btn-pay" id="verify_otp" style="width:105%;">Verify Otp</button>
						            						</div>
						            				</div>
						            				<div class="col-sm-6 form-group text-right p-r-0">
						            					<button id="pay-button" class="btn btn-pay">PAY</button>
						            				</div>
						            			</div>
						            			<div class="row">
						            				<div class="col-sm-5 form-group">
						            					<input class="form-control" type="text" id="razorpayId" name="razorpayId" disabled/> 
						            					<label>RP Payment ID</label>
						            				</div>
						            				<div class="col-sm-5 form-group">
						            					<input class="form-control" type="text" id="razorpayDate" name="razorpayDate" disabled/> 
						            					<label class="pull-right">Date</label>
						            				</div>
						            				<div class="col-sm-2 form-group text-right">
						            					<input type="hidden" id="linkid" name="linkid">
						            					<button id="get-pay-link-resp" class="btn btn-pay" style="width:50px;">GET</button>
						            				</div>
						            			</div>
						            			<div class="row">
						            				<div class="col-sm-12">
						            					<input class="form-control" type="text"/>
						            					<label>Notes</label>
						            				</div>
						            			</div>
						            			<div class="row">
						            				<div class="col-sm-6"></div>
						            				<div class="col-sm-6 form-group">
						            					<label class="left-check-box">Send Notifications to Customer
						            						<input type="checkbox" name="later"/>
									                    	<span class="check-mark"></span>
									                    </label>
						            				</div>
						            			</div>
						            			<div class="row">
						            				<div class="col-sm-6 form-group">
						            					<button class="btn btn-success" id="save_trip">Save</button>
						            				</div>
						            				<div class="col-sm-6 form-group text-right">
						            					<button class="btn btn-warning" id="book_trip">Book</button>
						            				</div>
						            			</div>
						            			<div class="row">
													
													<input type="hidden" name="id" id="user_mobile_no" value="{{ Session::has('editTripData') ? session('editTripData')[0]->user_mobile_no : ''}}">
													<div class="col-sm-6">
														<div class="form-group">
						            						<input class="form-control" type="text" id="tripemail" name="tripemail"/> 
						            						<label>Email</label>
						            					</div>
						            				</div>
						            			</div>
						            			<div class="row">
													<div class="col-sm-6 form-group">
					            						<input class="form-control" type="text" id="gstn"  name="gstn"  /> 
					            						<label>GSTN</label>
						            				</div>
						            				<div class="col-sm-6 form-group text-right">
						            					<button class="btn btn-info" id="sendTripInvoice">Invoice</button>
						            				</div>
						            			</div>
						            		</div>
						            	</div>				            			
				            		</div>
				            		<div class="col-sm-5 p-0" id="trip_details">
				            			@if (Session::has('editTripIsBooked'))
				            					<div class="row"><h4 class="trip-title">Trip Detail <span>{{ session('editTripData')[0]->trip_transaction_id }}</span></h4><div class="row"><div class="col-md-7 p-l-r-5"><div class="cust_detail"><h5>Customer</h5><table><tr><td>CID</td><td>:</td><td>{{ Session::has('editTripData') ? session('editTripData')[0]->user_uid : ''}}</td></tr><tr><td>Customer Name</td><td>:</td><td>{{ Session::has('editTripData') ? session('editTripData')[0]->user_first_name : ''}} {{ Session::has('editTripData') ? session('editTripData')[0]->user_last_name : ''}}</td></tr><tr><td>Mobile No</td><td>:</td><td>{{ Session::has('editTripData') ? session('editTripData')[0]->user_mobile_no : ''}}</td></tr><tr><td>Pick-Up Address</td><td>:</td><td>{{ Session::has('editTripData') ? session('editTripData')[0]->start_address_line_1 : ''}}</td></tr><tr><td>Pick-Up Time</td><td>:</td><td>{{ Session::has('editTripData') ? session('editTripData')[0]->book_time : ''}}</span></td></tr><tr><td>Pick-Up Date</td><td>:</td><td>{{ Session::has('editTripData') ? session('editTripData')[0]->book_date : ''}}</td></tr><tr><td>Delivery Address</td><td>:</td><td>{{ Session::has('editTripData') ? session('editTripData')[0]->intermediate_address[0]->dest_address_line_1 : ''}}</td></tr><tr><td>Ledger balance</td><td>:</td><td>Rs 0</td></tr></table></div></div><div class="col-md-5 p-l-r-5"><div class="cust_detail"><h5>Partner</h5><table><tr><td>Driver</td><td>:</td><td>{{ session('editTripData')[0]->driver_first_name }} {{ session('editTripData')[0]->driver_last_name }}</td></tr><tr><td>Mobile No</td><td>:</td><td>{{ session('editTripData')[0]->driver_mobile_number }}</td></tr><tr><td>Loaders</td><td>:</td><td>-</td></tr><tr><td>Tariff</td><td>:</td><td>Rs <span>{{ session('editTripData')[0]->base_amount }}</span></td></tr><tr><td>Loader</td><td>:</td><td>Rs {{ session('editTripData')[0]->veh_charge_per_person }}</td></tr><tr><td class="v-red">Amount</td><td>:</td><td class="v-red">Rs {{ session('editTripData')[0]->actual_amount }}</td></tr><tr><td>Balance</td><td>:</td><td>Rs <span class="v-red">0</span></td></tr><tr><td class="v-red">Payment</td><td>:</td><td class="v-red">{{ session('editTripData')[0]->payment_type }}</td></tr></table></div></div></div></div>
													<div class="row">
														<div class="col-sm-12">
															<div class="row">
																<div class="col-sm-3">
																	<label>Cust wt chrj</label>
																</div>
																<div class="col-sm-3">
																	<input type="number" class="form-control" id="cust_waiting_charges" value="{{ session('editTripData')[0]->cust_waiting_charges }}">
																</div>
																<div class="col-sm-3">
																	<label>Partner wt chrj</label>
																</div>
																<div class="col-sm-3">
																	<input type="number" class="form-control" id="partner_waiting_charges" value="{{ session('editTripData')[0]->partner_waiting_charges }}" >
																</div>
															</div>
															<div class="row">
																<div class="col-sm-3">
																	<label>Incidental</label>
																</div>
																<div class="col-sm-3">
																	<input type="number" class="form-control" id="incidental_charges" value="{{ session('editTripData')[0]->incidental_charges }}" >
																</div>
																<div class="col-sm-3">
																	<label>Accidental</label>
																</div>
																<div class="col-sm-3">
																	<input type="number" class="form-control" id="accidental_charges" value="{{ session('editTripData')[0]->accidental_charges }}" >
																</div>
															</div>
															<div class="row pin-input">
																<div class="col-sm-3">
																	<label>Other</label>
																</div>
																<div class="col-sm-3">
																	<input type="number" class="form-control" id="other_charges" value="{{ session('editTripData')[0]->other_charges }}" >
																</div>
																<div class="col-sm-6">
																	<button type="button" onclick="submitcharges('{{ session('editTripData')[0]->trip_transaction_id }}')" class="btn btn-primary">Submit</button>
																</div>
															</div>
														</div>
													</div>
<div class="row"><h4 class="trip-title p-t-10">Delivery</h4><div class="pin-input row"><div class="col-sm-4"> <input class="form-control" type="text" id="close_trip_pin" disabled/><label>Enter PIN</label></div> <button class="btn btn-primary" id="generate_pin" onclick="genCloseTripPin()">Generate Pin</button></div><div class="row"><div class="col-sm-8 p-0"><div class="row form-group"><div class="col-md-6"><div> <label class="left-radio-box">Cancelled <input type="radio" name="close-trip-opt" value="cancelled"/> <span class="radio-btn"></span> </label></div><div> <label class="left-radio-box">Disputed <input type="radio" name="close-trip-opt" value="disputed"/> <span class="radio-btn"></span> </label></div></div><div class="col-md-6"><div> <label class="left-radio-box">Success <input type="radio" name="close-trip-opt" value="success"/> <span class="radio-btn"></span> </label></div><div> <label class="left-radio-box">Unpaid <input type="radio" name="close-trip-opt" value="unpaid"/> <span class="radio-btn"></span> </label></div></div></div></div><div class="col-sm-4 p-0"><div class="trip-btn"> <button class="btn btn-primary" id="close_trip" onclick="closeTrip()">Close Trip</button></div><div class="trip-btn"> <button class="btn btn-success" onclick="postTrip()">Post Trip</button></div></div></div></div>
				            					@endif				            			
				            		</div>
				            	</div>
				            </div>
					    </div>					        	
					</div>
					<!-- add trip design end here -->
				</div>
			</div>
		</div>
		<!--All images modal -->
		<div class="modal fade" id="view-all-images" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body" id="all-images">

					</div>
	    		</div>
	  		</div>
		</div>
		<!--All images End-->
	</div>
@endsection
<style>
.ui-autocomplete {
  z-index:2147483647 !important;
}
#autopick { width: 270px; position: relative;z-index: 999;  font-size: 15px;  padding: 10px; border: 1px solid #ddd; outline: none !important;
            top: 5px;border-radius: 0px;margin-top: -5px;margin-bottom: 10px; }
#autodrop { width: 280px; position: relative;z-index: 999;  font-size: 15px;  padding: 10px; border: 1px solid #ddd; outline: none !important;
            top: 2px;border-radius: 0px;margin-top: -2px; }
#cust_base_location { width: 210px; position: relative;font-size: 15px;  padding: 10px; border: 1px solid #ddd; outline: none !important;
            top: -10px;border-radius: 0px; margin-top:10px;}
</style>

@section('javascript')
<script type="text/javascript">
	$("input.date-picker").datepicker({
	    minDate: 0  
	});

	$('#calender-div').click(function(){
		$("#pickup_date").focus();
	});
	/*This makes the timeout variable global so all functions can access it.*/
	var timeout;

	/*This is an example function and can be disreguarded
	This function sets the loading div to a given string.*/
	function loaded() {
	    $('#loading').html('');
	}

	function startLoad() {
	    /*This is the loading gif, It will popup as soon as startLoad is called*/
	    $('#loading').html('<img src="images/loader.gif"/>');
	    /*
	    /*This is an example and can be disreguarded
	    The clearTimeout makes sure you don't overload the timeout variable
	    with multiple timout sessions.*/
	    //clearTimeout(timeout);
	    /*Set timeout delays a given function for given miliseconds*/
	    //timeout = setTimeout(loaded, 1500);
	}
	/*This binds a click event to the refresh button*/
	//$('#start_call').click(startLoad);
	/*This starts the load on page load, so you don't have to click the button*/
	// startLoad();


	$(document).ready(function () {
		localStorage.removeItem('autodrop_locs');
		localStorage.removeItem('Dynamicpincode');
		localStorage.removeItem('Dynamic_complex_society');
		localStorage.removeItem('Dynamic_pickup_road');
		localStorage.removeItem('Dynamic_pickup_lat');
		localStorage.removeItem('Dynamic_pickup_lng');
    	var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');
    	allWells.hide();

	    navListItems.click(function (e) {
	    	clearTimeout(timeout);
	    	timeout = setTimeout(loaded, 1500);
	        e.preventDefault();
	        var $target = $($(this).attr('href')),
	        $item = $(this);
	    	if (!$item.hasClass('disabled')) {
	            navListItems.removeClass('btn-success').addClass('btn-default');
	            $item.addClass('btn-success');
	            allWells.hide();
	            $target.show();
	            $target.find('input:eq(0)').focus();
	    	}
	    });

	    /*allNextBtn.click(function () {
	        var curStep = $(this).closest(".setup-content"),
	            curStepBtn = curStep.attr("id"),
	            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
	            curInputs = curStep.find("input[type='text'],input[type='url']"),
	            isValid = true;

	        $(".form-group").removeClass("has-error");
	        for (var i = 0; i < curInputs.length; i++) {
	            if (!curInputs[i].validity.valid) {
	                isValid = false;
	                $(curInputs[i]).closest(".form-group").addClass("has-error");
	            }
	        }

	        if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
	    });*/

    	$('div.setup-panel div a.btn-success').trigger('click');
	//var changeTab = '<?php if(Session::has("editTripData")){ echo "true";}?>';
var changeTab = '<?php if(Session::has("editTripDataFromRealtime")){ echo ""; }elseif(Session::has("editTripData")){ echo "true";}?>';
	var payLinkId = '<?php if(isset(session('editTripData')[0]->user_order_paylink_id)){echo session('editTripData')[0]->user_order_paylink_id;}else{echo null;}?>';
    	$("#linkid").val(payLinkId);
		var freezeBook = '<?php echo Session::has("editTripIsBooked") ?>';
		var freezeSave = '<?php echo Session::has("editTripIsSaved") ?>';
    		if(freezeBook == 1){
    			$("#book_trip").prop("disabled", true);	
			$("#save_trip").prop("disabled", true);
    		}
		if(freezeSave == 1){
            $("#save_trip").prop("disabled", true); 
        }
                if(changeTab == "true"){
                        $('#confirmTab').trigger('click');
                }

	    //form validations start
		$("#enterdetailsForm").validate({
			rules: {
				pickup_date: {
					required: true,
				},
				pickup_location: {
					required: true,
				},
				material_type: {
					required: true,
				},
				vehicle_type: {
					required: true,
				},
				offline: {
					required: true,
				},
				payment_mode: {
					required: true,
				},
				user_bid_mode: {
					required: true,
				},
				cust_mobile: {
					required: true,
					maxlength: 10,
					minlength: 10
				},
				cust_mob: {
					required: true,
					maxlength: 10,
					minlength: 10
				},
				pickup_time: {
					required: true,
				},
				delivery_location: {
					required: true,
				},
				weight: {
					required: true,
				},
				start_address_line_1: {
					required: true,
				},
				pickup_user_name: {
					required: true,
				},
				pickup_mobile: {
					required: true,
					maxlength: 10,
					minlength: 10
				},
				delivery_user_mobile: {
					required: true,
					maxlength: 10,
					minlength: 10
				},
				start_address_line_2: {
					required: true,
				},
				dest_address_line_1: {
					required: true,
				},
				dest_address_line_2: {
					required: true,
				},
			},  
			messages: {
				pickup_date : {
					required:"Please select pickup data",
				},
				pickup_location:{
					required:"Please enter pickup location",
				}, 
				material_type:{
					required:"Please select material",
				},
				vehicle_type: {
					required: "Please select vehicle type",
				},
				offline: {
					required: "Please select booking type",
				},
				payment_mode: {
					required: "Please select payment mode",
				},
				user_bid_mode: {
					required: "Please select booking type",
				},
				cust_mobile: {
					required: "Please enter customer mobile",
					maxlength:"Please enter valid mobile number",
					minlength:"Please enter valid mobile number",
				},
				cust_mob: {
					required: "Please enter customer mobile",
					maxlength:"Please enter valid mobile number",
					minlength:"Please enter valid mobile number",
				},
				pickup_time: {
					required: "Please select pickup time",
				},
				delivery_location: {
					required: "Please enter delivery location",
				},
				weight: {
					required: "Please enter weight",
				},
				start_address_line_1: {
					required: "Please enter and select pickup location from google",
				},
				pickup_user_name: {
					required: "Please enter name",
				},
				pickup_mobile: {
					required: "Please enter mobile number",
					maxlength:"Please enter valid mobile number",
					minlength:"Please enter valid mobile number",
				},
				delivery_user_mobile: {
					required: "Please enter mobile number",
					maxlength:"Please enter valid mobile number",
					minlength:"Please enter valid mobile number",
				},
				start_address_line_2:{
					required:"Please enter valid location",
				}, 
				dest_address_line_2: {
					required:"Please enter valid location",
				},
			},
			submitHandler: function(form) {
				//e.preventDefault();
				startLoad();
				$.ajax({
		            headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
		            url :"{{ route('get-vehicle-list-withoutbid') }}",
		            method:"POST",
		            data: $('#enterdetailsForm').serialize(),
		            success : function(data)
		            {
		                //alert(data);
		                clearTimeout(timeout);
	    				timeout = setTimeout(loaded, 1500);
		                console.log('here after form submit',data)
		                var searchResult = data.response.vehicles; 
		                var bookData = data.response.bookingdata;
		                console.log('here after form submit',data.response.vehicles)
		                var driverData;
		                var imgData;
		                var datetime = new Date().toLocaleString();
		                $('#pickup-drop-distance').empty();
		                $('#veh-select').empty();
		                $('#pickup-drop-distance').append('<div class="col-sm-6"><div class="div-add-1">'+bookData.pickup+'</div><div class="div-add-2">to</div><div class="div-add-1">'+bookData.drop+'</div></div><div class="col-sm-4"><div class="div-add-3">'+Number(bookData.totaldistance).toFixed(2)+' Kms</div><div class="div-add-3">'+bookData.totaltime+' Mins</div></div><div class="col-sm-2 p-0"><h5 class="m-0">'+datetime+'</h5></div>');

		                $.each(searchResult,function(index,item){
		                	if (item.veh_base_charge_rate_per_km){                                             
	                            var kmchargedata = JSON.parse(item.veh_base_charge_rate_per_km);
	                            var chargeperkm = item.veh_base_charge+'/'+kmchargedata.veh_3km_15km+'/'+kmchargedata.veh_above_15km;
                            }
		                	if (item.drivers){
		                		$.each(item.drivers,function(indexD,itemD){
		  						driverData = '<tr><td class="v-purpal">Name</td><td>:</td><td class="v-purpal">'+itemD.op_first_name+' '+itemD.op_last_name+'</td></tr><tr><td class="v-purpal">Mobile No</td><td>:</td><td class="v-purpal">'+itemD.op_mobile_no+'</td></tr>'
		  						});
							}
							if(item.veh_single_image){
								$.each(item.veh_single_image,function(indexImg,itemImg){
								imgData = '<img src="images/'+itemImg.img_url+'" alt="GOGOTRUX">'
								});
							}else{
								imgData	= '<img src="images/trip_icon.jpg" alt="GOGOTRUX">'
							}
		                	console.log("Current: " + item);
		                	$('#veh-select').append('<div class="veh-info-box"><div class="veh-bg"><div class="row"><div class="veh-images"><div class="veh-img-box">'+imgData+'</div><div class="btn-box"><div class="text-center"><button class="btn btn-success btnview" onclick="getImages(\''+item.veh_id+'\')">View</button></div><div class="text-center"><button id="book_selected" class="btn btn-warning btnbook" onclick="bookSelected(\''+btoa(JSON.stringify(item))+'\',\''+btoa(JSON.stringify(data.response.tripdata))+'\','+index+')">Book</button></div></div></div></div><div class="veh-info"><table><tr><td>Make-Model</td><td>:</td><td>'+item.veh_make_model_type+' '+item.veh_model_name+'</td></tr><tr><td>Type</td><td>:</td><td>'+item.veh_wheel_type+' W </td></tr><tr><td>Capacity</td><td>:</td><td>'+item.veh_capacity+' Kg</td></tr><tr><td>Carriage</td><td>:</td><td>'+item.veh_dimension+' Ft</td></tr><tr><td>Base</td><td>:</td><td>'+item.veh_city+'</td></tr><tr><td>Loader</td><td>:</td><td>Rs<span>'+item.veh_charge_per_person+'</span></td></tr><tr><td>Rate/ km</td><td>:</td><td>Rs '+chargeperkm+'</td></tr><tr><td class="v-red">Amount</td><td>:</td><td class="v-red">Rs '+Number(item.total_amount_factor).toFixed(2)+'<input type="number" name="cust_adjust_'+index+'" id="cust_adjust_'+index+'" value="0" class="form-control"/></td></tr><tr><td>Tariff</td><td>:</td><td>Rs '+Number(item.total_amount).toFixed(2)+'<input type="number" name="op_adjust_'+index+'" id="op_adjust_'+index+'" value="0" class="form-control"/></td></tr><tr><td>Location</td><td>:</td><td>-</td></tr><tr><td>Distance</td><td>:</td><td>'+item.ascircle_distance+' km</td></tr><tr><td>UID</td><td>:</td><td>'+item.op_uid+'</td></tr>'+driverData+'<tr><td>Balance</td><td>:</td><td>Rs<span class="v-red">0</span></td></tr></table></div></div></div>');
		                });
		                $('#searchTab').trigger('click');
		            }
	        	});
	        	return false;
			}
		});
    	//form validations end here
	});
	$(document).on('change','#cust_mobile',function(){
		$('#pickup_mobile').val(this.value);
	})
</script>

<script type="text/javascript">
	let API_KEY = "gY9qIXIbdKsBf9YAza5mCJnPgAxfr9h2y99dQQ_J6EU";
	//let API_KEY = "9x3IHpde-Z7zmJQQaX5a8s9KrwQlRRxvD_N1_vP2gyA";
	
	$("#cust_base_location").autocomplete({
		source: addressAC,
    		minLength: 2,
	    	select: function (event, ui) {
		}
	});

	$("#start_address_line_1").autocomplete({
    source: addressAC,
    minLength: 2,
    select: function (event, ui) {
        console.log("Selected: " + ui.item.value + " with LocationId " + ui.item.id);
        $.getJSON("https://geocoder.ls.hereapi.com/6.2/geocode.json?locationid="+ui.item.id+"&jsonattributes=1&gen=9&apiKey="+API_KEY, function (data) {
        var place_details = data.response.view;
        var lat = place_details[0]['result'][0]['location']['displayPosition']['latitude'];
        var lng = place_details[0]['result'][0]['location']['displayPosition']['longitude'];
        console.log('lat',lat);
        console.log('lng',lng);
	updatePickupLatLong(lat,lng,ui.item.value);
        });
    }
});

	function addressAC(query, callback) {
    $.getJSON("https://autocomplete.geocoder.ls.hereapi.com/6.2/suggest.json?apiKey="+API_KEY+"&query="+query.term, function (data) {
        var addresses = data.suggestions;
        console.log('addresses',data);
        addresses = addresses.map(addr => {
            return {
                title: addr.label,
                value: addr.label,
                id: addr.locationId
            };
        });
        return callback(addresses);
    });
}
	// customer base location
	/*function initialize() {
		var input = document.getElementById('cust_base_location');
		var autocomplete = new google.maps.places.Autocomplete(input);
	}
	google.maps.event.addDomListener(window, 'load', initialize);*/
	//end here

	// pickup location lat long
	/*function pickupAddressLatLong() {
		var pickupinput = document.getElementById('start_address_line_1');
		var autocompletepickup = new google.maps.places.Autocomplete(pickupinput);
		// get lat-long
		google.maps.event.addListener(autocompletepickup, 'place_changed', function () {
		// infowindow.close();
		var pickupplace = autocompletepickup.getPlace();  	
		updatePickupLatLong(pickupplace.geometry.location.lat(),pickupplace.geometry.location.lng(),pickupplace.formatted_address);
		});
		//end lat-long
	}
	google.maps.event.addDomListener(window, 'load', pickupAddressLatLong);*/

	function updatePickupLatLong(lat, lng, address) {
		$('#pickup_address_lat').val(lat);
		$('#pickup_address_lng').val(lng);
		$('#pickup_location').val(address);
	}
	//end here

	//delivery location lat long
	/*function dropAddressLatLong(id,latid,lngid) {
		var dropinput = document.getElementById(id);
		var autocompletedrop = new google.maps.places.Autocomplete(dropinput);
		// get lat-long
		google.maps.event.addListener(autocompletedrop, 'place_changed', function () {
			// infowindow.close();
			var dropplace = autocompletedrop.getPlace();
			//alert(dropplace.formatted_address);
			updateDropLatLong(dropplace.geometry.location.lat(),dropplace.geometry.location.lng(),dropplace.formatted_address,id,latid,lngid);
		});
		//end lat-long
	}
	google.maps.event.addDomListener(window, 'load', dropAddressLatLong);*/
	
	function dropAddressLatLong(id,latid,lngid) {
		// var map = new MapmyIndia.Map('map', {center: [28.62, 77.09], zoom: 15, search: false});
                var dropinput = document.getElementById(id);
		$(dropinput).autocomplete({
    source: addressAC,
    minLength: 2,
    select: function (event, ui) {
        console.log("Selected: " + ui.item.value + " with LocationId " + ui.item.id);
        $.getJSON("https://geocoder.ls.hereapi.com/6.2/geocode.json?locationid="+ui.item.id+"&jsonattributes=1&gen=9&apiKey="+API_KEY, function (data) {
        var place_details = data.response.view;
        var lat = place_details[0]['result'][0]['location']['displayPosition']['latitude'];
        var lng = place_details[0]['result'][0]['location']['displayPosition']['longitude'];
        console.log('lat',lat);
        console.log('lng',lng);
	updateDropLatLong(lat,lng,ui.item.value,id,latid,lngid);
        
        });
    }
});

	}

	function updateDropLatLong(lat, lng, address,id,latid,lngid) {
		//var DestinationLocations = [];
		//var id=0;
		$('#'+latid).val(lat);
		$('#'+lngid).val(lng);
		$('#delivery_location').val(address);
		//DestinationLocations.push("id");
	}

	//Add multiple address - bhagyashri
	var cookieVal;
	var autodrop_locs;
	var d_i = 2;
	var d_j = 3;
	var d_k = 4;
	var d_l = 5;
	$('#mult_destinations').click(function(){
		// $('#add_more_dest').append('<div class="addrow" id="row'+d_i+'"><div class="row"><button type="button" name="remove" id="'+d_i+'" class="btn btn-danger btn_remove btn-sm r-add-btn">Remove</button></div><div class="row"><div class="form-group col-l-half"><input class="form-control" onclick="dropAddressLatLong(\'dest_address_line_'+d_i+'\',\'delivery_address_lat'+d_i+'\',\'delivery_address_lng'+d_i+'\')" name="dest_address_line_1[]" id="dest_address_line_'+d_i+'" placeholder="Enter destination"><input id="delivery_address_lat'+d_i+'" type="hidden" name="dest_address_lat[]" value="" /><input id="delivery_address_lng'+d_i+'" type="hidden" name="dest_address_lan[]" value="" /><label for="">Nearest Location<sup class="sup-e">*</sup></label></div><div class="form-group col-r-half"><input type="text" class="form-control" name="delivery_address_pin'+d_i+'" id="pin'+d_i+'" placeholder="Enter pincode"/><label for="">Area PIN Code</label></div></div><hr><label id="row'+d_i+'" class="add-info">Enter Correct Address For Timely Deliver Of Material</label><div class="row"><div class="form-group col-l-half"><input type="text" class="form-control" name="delivery_user_name'+d_i+'" id="cust_name'+d_i+'"/><label for="">Name</label></div><div class="form-group col-r-half"><input type="text" class="form-control" name="delivery_user_mobile'+d_i+'" id="cust_mob'+d_i+'"/><label for="">Mobile No.</label></div></div><div class="row"><div class="col-sm-12 form-group"><input type="text" class="form-control" name="dest_address_line_'+d_j+'" id="dest_address_line_2'+d_i+'"/><label for="">House/Flat/Shop No.<sup class="sup-e">*</sup></label></div></div><div class="row"><div class="col-sm-12 form-group"><input type="text" class="form-control" name="dest_address_line_'+d_k+'" id="complex_society'+d_i+'"/><label for="">Complex/Society/Market</label></div></div><div class="row"><div class="form-group col-sm-12"><input type="text" class="form-control" name="dest_address_line_'+d_l+'" id="pickup_road'+d_i+'"/><label for="">Area/Road</label></div></div></div>');
		$('#add_more_dest').append('<div class="addrow" id="row'+d_i+'"><div class="row"><button type="button" name="remove" id="'+d_i+'" class="btn btn-danger btn_remove btn-sm r-add-btn">Remove</button></div><div class="row"><div class="form-group col-l-half"><input type="text" id="autodrop'+d_i+'" name="dest_address_line_1[]"  class="search-outer form-control as-input" placeholder="Enter Destination" required="" spellcheck="false"><input id="delivery_address_lat'+d_i+'" type="hidden" name="dest_address_lat[]" value="" /><input id="delivery_address_lng'+d_i+'" type="hidden" name="dest_address_lan[]" value="" /><label for="">Nearest Location<sup class="sup-e">*</sup></label></div><div class="form-group col-r-half"><input type="text" class="form-control" name="delivery_address_pin'+d_i+'" id="delivery_address_pin'+d_i+'" placeholder="Enter pincode"/><label for="">Area PIN Code</label></div></div><hr><label id="row'+d_i+'" class="add-info">Enter Correct Address For Timely Deliver Of Material</label><div class="row"><div class="form-group col-l-half"><input type="text" class="form-control" name="delivery_user_name'+d_i+'" id="cust_name'+d_i+'"/><label for="">Name</label></div><div class="form-group col-r-half"><input type="text" class="form-control" name="delivery_user_mobile'+d_i+'" id="cust_mob'+d_i+'"/><label for="">Mobile No.</label></div></div><div class="row"><div class="col-sm-12 form-group"><input type="text" class="form-control" name="dest_address_line_'+d_i+'" id="dest_address_line_2'+d_i+'"/><label for="">House/Flat/Shop No.<sup class="sup-e">*</sup></label></div></div><div class="row"><div class="col-sm-12 form-group"><input type="text" class="form-control" name="dest_address_line_'+d_k+'" id="complex_society'+d_i+'"/><label for="">Complex/Society/Market</label></div></div><div class="row"><div class="form-group col-sm-12"><input type="text" class="form-control" name="dest_address_line_'+d_l+'" id="pickup_road'+d_i+'"/><label for="">Area/Road</label></div></div></div>');	
		// $('#add_more_dest').append('<div class="addrow" id="row'+d_i+'"><div class="row"><button type="button" name="remove" id="'+d_i+'" class="btn btn-danger btn_remove btn-sm r-add-btn">Remove</button></div><div class="row"><div class="form-group col-l-half"><input type="text" name="dest_address_line_1[]"  class="autodroptest search-outer form-control as-input" placeholder="Enter Destination" required="" spellcheck="false"><input id="delivery_address_lat'+d_i+'" type="hidden" name="dest_address_lat[]" value="" /><input id="delivery_address_lng'+d_i+'" type="hidden" name="dest_address_lan[]" value="" /><label for="">Nearest Location<sup class="sup-e">*</sup></label></div><div class="form-group col-r-half"><input type="text" class="form-control" name="delivery_address_pin'+d_i+'" id="pin'+d_i+'" placeholder="Enter pincode"/><label for="">Area PIN Code</label></div></div><hr><label id="row'+d_i+'" class="add-info">Enter Correct Address For Timely Deliver Of Material</label><div class="row"><div class="form-group col-l-half"><input type="text" class="form-control" name="delivery_user_name'+d_i+'" id="cust_name'+d_i+'"/><label for="">Name</label></div><div class="form-group col-r-half"><input type="text" class="form-control" name="delivery_user_mobile'+d_i+'" id="cust_mob'+d_i+'"/><label for="">Mobile No.</label></div></div><div class="row"><div class="col-sm-12 form-group"><input type="text" class="form-control" name="dest_address_line_'+d_j+'" id="dest_address_line_2'+d_i+'"/><label for="">House/Flat/Shop No.<sup class="sup-e">*</sup></label></div></div><div class="row"><div class="col-sm-12 form-group"><input type="text" class="form-control" name="dest_address_line_'+d_k+'" id="complex_society'+d_i+'"/><label for="">Complex/Society/Market</label></div></div><div class="row"><div class="form-group col-sm-12"><input type="text" class="form-control" name="dest_address_line_'+d_l+'" id="pickup_road'+d_i+'"/><label for="">Area/Road</label></div></div></div>');	
		// document.cookie = "autodrop_locs" +"="+ "autodrop"+d_i;
		// autodrop_locs = 'autodrop'+d_i;
		// Setting Dynamic ids in local storage and removing it on line 715 for removing conflict between ids.
		window.localStorage.removeItem('autodrop_locs');
		window.localStorage.removeItem('Dynamicpincode');

		window.localStorage.setItem('autodrop_locs', 'autodrop'+d_i);
		window.localStorage.setItem('Dynamicpincode', "delivery_address_pin"+d_i);
		window.localStorage.setItem('Dynamic_complex_society', "complex_society"+d_i);
		window.localStorage.setItem('Dynamic_pickup_road', "pickup_road"+d_i);
		window.localStorage.setItem('Dynamic_pickup_lat', "delivery_address_lat"+d_i);
		window.localStorage.setItem('Dynamic_pickup_lng', "delivery_address_lng"+d_i);
		d_i++;
		d_j++;
		

		$(document).on('click', '.btn_remove', function(){
			var button_id = $(this).attr("id");
			$('#row'+button_id).remove();
		});
		initMap1();
	});
	//end-Add multiple address

	$(document).ready(function () {
		$('.clockpicker').clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    donetext: 'Done'
		});

		//get token and update to db
		var token = window.localStorage.getItem('notification_token');
		console.log('local store token here',token);
		if(token != ""){
			sendTokenToServer(token);
		}else{
			getToken();
		}
	});
	//end here

	//*************MAPMYINDIA INTEGRATION BEGIN*************
	let flag = false;
    var marker,pickupELOC,dropELOC;
	let demo123;
    /*Map Initialization*/
		function initMap1(){
	//******* This code is for selecting between cities from admin settings commented for now******
			// console.log("selectedCity" ,window.localStorage.getItem('selectedCity'));
			// var cityVal = window.localStorage.getItem('selectedCity');
    //         if(cityVal == 'pune'){
	// 			alert('pune');
	// 			var optional_config = {
    //             location: [18.52, 73.85],  //Pune
    //             // location: [12.97, 77.59], //Banglore
	// 			// filter:'cop:'+a, //Pune
	// 			// filter:'cop:2UQY8X', //Banglore
    //             region: "IND",
    //             height:300,
	// 			hyperLocal:true,
	// 			searchChars:5,
    //         };
	// 	}else if(cityVal == 'banglore'){
	// 		alert('banglore');
	// 		var optional_config = {
    //             // location: [18.52, 73.85],  //Pune
    //             location: [12.97, 77.59], //Banglore
	// 			// filter:'cop:2YDC4O',  //Pune
	// 			// filter:'cop:2UQY8X', //Banglore
    //             region: "IND",
    //             height:300,
	// 			hyperLocal:true,
	// 			searchChars:5,
	// 	};
	// }
	// *******End city setting code does not need now*****
	
	var optional_config = {
                location: [18.52, 73.85],  //Pune
                // location: [12.97, 77.59], //Banglore
				// filter:'cop:2YDC4O',  //Pune
				// filter:'cop:2UQY8X', //Banglore
                region: "IND",
                height:300,
				hyperLocal:true,
				searchChars:5,
		};
			
            var autopick = new mappls.search(document.getElementById("autopick"),optional_config, callback);
            var autodrop = new mappls.search(document.getElementById("autodrop"),optional_config, callback);
            var cust_base_location = new mappls.search(document.getElementById("cust_base_location"),optional_config, callback);
			var autodrop_more_id ="#"+(window.localStorage.getItem('autodrop_locs'));
			let autodrop_more = new mappls.search(document.getElementById(window.localStorage.getItem('autodrop_locs')),optional_config, callback);
			
			$("#autopick").change(function(){   
				flag = 1;
			});
			$("#autodrop").change(function(){   
				flag = 2;
			});
			$("#cust_base_location").change(function(){   
				flag = 	3;
			});
			$(document).on('change',autodrop_more_id,function(){
				flag =5;
			// console.log('Hereee in '+ window.localStorage.getItem('autodrop_locs'));
			});

            function callback(data) {
				obj = data;
                if (data) {
                    var dt = data[0];
					// alert(JSON.stringify(dt));
                    // console.log("DT",dt);
                    if (!dt) return false;
                    var eloc = dt.eLoc;
                    var placeAddress = dt.placeAddress;
                    var place = dt.placeName + ", " + dt.placeAddress;
                    // console.log("Place :", dt.placeName);
                    // console.log("Address :", dt.placeAddress);
                    // console.log("alternateName :", dt.alternateName);

					// explode function to break the address to get pincode. 
					var newArray = placeAddress.split(",");
                    let pincodeVal=newArray[newArray.length-1]; 
					let society=newArray[newArray.length-4]; 
					let area=newArray[newArray.length-2]; 
					// console.log('Pincode :', pincodeVal);
					// console.log("Address :",society);
					

					if(flag == 1){
						// alert('Pickup Location');
						$('#pickup_location').val(dt?.placeName);
						$('#pin1').val(pincodeVal);
						$('#pick_complex_society').val(society);
						$('#pick_pickup_road').val(area);
						pickupELOC = dt.eLoc;
						// console.log('Pickup ELOC ',pickupELOC);
						var pick =new mappls.getPinDetails({ pin: dt.eLoc},callback);
						// console.log('Pickup obj',obj.data);
						$('#pickup_address_lat').val(obj.data.latitude);
						$('#pickup_address_lng').val(obj.data.longitude);
						console.log('pickup lat',obj.data.latitude);
						console.log('pickup long',obj.data.longitude);
					}
					if(flag == 2){
						// alert('Drop Location');
						$('#delivery_location').val(dt?.placeName);
						$("#delivery_address_pin").val(pincodeVal);
						$('#complex_society').val(society);
						$('#pickup_road').val(area);
						dropELOC = dt.eLoc;
						// console.log('Drop ELOC ',dropELOC);
						var drop =new mappls.getPinDetails({ pin: dt.eLoc},callback);
						// console.log('Drop obj',obj.data);
						$('#delivery_address_lat').val(obj.data.latitude);
						$('#delivery_address_lng').val(obj.data.longitude);
						console.log('Drop lat',obj.data.latitude);
						console.log('Drop long',obj.data.longitude);
					}
					if(flag == 3){
						// alert('Customer Location');
						$('#cust_base_location').val(dt?.placeAddress);
						custELOC = dt.eLoc;
						// console.log('Cust ELOC ',custELOC);
						var elocdata =new mappls.getPinDetails({ pin: dt.eLoc},callback);
						// console.log('obj',obj.data);
						console.log('cust lat',obj.data.latitude);
						console.log('cust long',obj.data.longitude);
					}
					if(flag == 5){
						// alert('Dynamic Location');
						// Dynamically settting map data in dynamic ids using local storage set on line on 1097
						var DynamicPincode= "#"+window.localStorage.getItem('Dynamicpincode');
						var Dynamic_complex_society= "#"+window.localStorage.getItem('Dynamic_complex_society');
						var Dynamic_pickup_road= "#"+window.localStorage.getItem('Dynamic_pickup_road');
						
						$( "#"+window.localStorage.getItem('Dynamicpincode')).val(pincodeVal);
						$("#"+window.localStorage.getItem('Dynamic_complex_society')).val(society);
						$("#"+window.localStorage.getItem('Dynamic_pickup_road')).val(area);
						var elocdata =new mappls.getPinDetails({ pin: dt.eLoc},callback);
						// console.log('obj',obj.data);
						console.log('cust lat',obj.data.latitude);
						console.log('cust long',obj.data.longitude);
						console.log('local lat',window.localStorage.getItem('Dynamic_pickup_lat'));
						console.log('local long',window.localStorage.getItem('Dynamic_pickup_lng'));
						$("#"+window.localStorage.getItem('Dynamic_pickup_lat')).val(obj.data.latitude);
						$("#"+window.localStorage.getItem('Dynamic_pickup_lng')).val(obj.data.longitude);
					}
					
					}
            }
		}
		
		//*********MAPMYINDIA INTEGRATION END************

	function sendTokenToServer(token){
		$.ajax({
			url :"{{ route('updatetoken') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"token":token,
			},
			success : function(data)
			{
				//alert(data);
				console.log(data)
			}
		});
	}

	//book selected vehicle
	function bookSelected(selected,bookdata,index){
		console.log('selected vehicle here',index);
		
		if(confirm("Are you sure?"))
	    {
		window.localStorage.removeItem('notificationdata');
		$('.btnbook').attr('disabled','disabled');
		$('.btnview').attr('disabled','disabled');
		$('#start_call').attr('disabled','disabled');
		//var cust_adjust = $('#cust_adjust').val();
		var cust_adjust = $("#cust_adjust_"+index).val();
	    	var op_adjust = $("#op_adjust_"+index).val();
		console.log('cust_adjust',cust_adjust);
	    	startLoad();
	        $.ajax({
				url :"{{ route('send-booking') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"userdetail": bookdata,
					"vehicledetail": selected,
					"cust_adjust": cust_adjust,
					"op_adjust": op_adjust,
				},
				success : function(data){
					var timesRun = 0;
					var interval = setInterval(() => {
						timesRun += 10;
						if(timesRun === 120){
							$('.btnbook').removeAttr('disabled');
							$('.btnview').removeAttr('disabled');
							$('#start_call').removeAttr('disabled');
							clearInterval(interval);
							timeout = setTimeout(loaded, 1500);
							swal({
								title: 'Selected GOGOTRUX Is Not Available. Please Try Another Vehicle.blah',
								type: 'warning',
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'try again'
							}).then(function() 
							{   

							})
							clearTimeout(timeout);
	    					timeout = setTimeout(loaded, 1500);
						}
						var data = JSON.parse(window.localStorage.getItem('notificationdata'));
						window.localStorage.removeItem('notificationdata');
						
						console.log('all notification data here',data);
						if(data != null){	
							$('.btnbook').removeAttr('disabled');
							$('.btnview').removeAttr('disabled');
							$('#start_call').removeAttr('disabled');
							clearInterval(interval);
							timeout = setTimeout(loaded, 1500);
							var booking_status = data.notification.title;
							var notificationId = data.data.notiId;
							window.localStorage.setItem('selectedVehicle', selected);
							if(booking_status == "Trip Accepted"){
								swal({
									title: 'Selected GOGOTRUX Is Available.',
									type: 'success',
									confirmButtonColor: '#3085d6',
							  		confirmButtonText: 'Go for payment',
								}).then(function() 
								{   
									//show data in confirm tab
									selected = JSON.parse(atob(selected));
                                	bookdata = JSON.parse(atob(bookdata));
                                	$('#mt-info').empty();
					if (selected.drivers){
		                		$.each(selected.drivers,function(indexD,itemD){
		  						driverData = '<tr><td class="v-purpal">Name</td><td>:</td><td class="v-purpal">'+itemD.driver_first_name+' '+itemD.driver_last_name+'</td></tr><tr><td class="v-purpal">Mobile No</td><td>:</td><td class="v-purpal">'+itemD.driver_mobile_number+'</td></tr>'
		  						});
							}
                                	if (selected.veh_base_charge_rate_per_km){                
                                		var kmchargedata = JSON.parse(selected.veh_base_charge_rate_per_km);
	                            		var chargeperkm = selected.veh_base_charge+'/'+kmchargedata.veh_3km_15km+'/'+kmchargedata.veh_above_15km;
                            		}
									$('#mt-info').append('<input type="hidden" id="notiId" name="notiId" value='+notificationId+'><table><tr><td>Make-Model</td><td>:</td><td>'+selected.veh_make_model_type+' '+selected.veh_model_name+'</td></tr><tr><td>Type</td><td>:</td><td>'+selected.veh_wheel_type+' W</td></tr><tr><td>Capacity</td><td>:</td><td>'+selected.veh_capacity+' Kg</td></tr><tr><td>Carriage</td><td>:</td><td>'+selected.veh_dimension+' - Ft</td></tr><tr><td>Base</td><td>:</td><td>'+selected.veh_city+'</td></tr><tr><td>Loader</td><td>:</td><td>Rs<span>'+selected.veh_charge_per_person+'</span></td></tr><tr><td>Rate/ km</td><td>:</td><td>Rs '+chargeperkm+'</td></tr><tr><td class="v-red">Amount</td><td>:</td><td class="v-red">Rs '+Number(selected.total_amount_factor).toFixed(2)+'<input type="number" name="cust_adjust_'+index+'" id="cust_adjust_'+index+'" value="'+cust_adjust+'" class="form-control" readonly/></td></tr><tr><td>Tariff</td><td>:</td><td>Rs '+Number(selected.total_amount).toFixed(2)+'<input type="number" name="op_adjust_'+index+'" id="op_adjust_'+index+'" value="'+op_adjust+'" class="form-control" readonly/></td></tr><tr><td>Location</td><td>:</td><td>-</td></tr><tr><td class="v-red">Distance</td><td>:</td><td class="v-red">'+selected.ascircle_distance+'</td></tr><tr><td>UID</td><td>:</td><td>'+selected.op_uid+'</td></tr>'+driverData+'<tr><td>Balance</td><td>:</td><td>Rs <span class="v-red">0</span></td></tr></table>');
									//
									$('#confirmTab').trigger('click');
									$("#linkid").val('');
									clearTimeout(timeout);
	    							timeout = setTimeout(loaded, 1500);
								})
							}else{
								//if(vehId != null){
									//this.sharedata.simpleFunction(this.vehId);
								//}
								swal({
									title: 'Selected GOGOTRUX Is Not Available. Please Try Another Vehicle.blah 2',
									type: 'warning',
									confirmButtonColor: '#3085d6',
									confirmButtonText: 'try again'
								}).then(function(){

								})
								clearTimeout(timeout);
								timeout = setTimeout(loaded, 1500);
							}
						}else{
							console.log('else');
							//this.spinner.hide();
						}
					},6000)
				}
			});
	    }else{
	        return false;
	    }
	}

	$('input[type=radio][name=send-link]').change(function() {
	    if (this.value == 'cash') {
	        $("#cash-otp").prop("disabled", false);
	    }else{
	        $("#cash-otp").prop("disabled", true);
	    }
	});

	/*--------------------on click pay-------------------*/
	$("#pay-button").click(function(){
		if($("input[name='send-link']:checked").val()) {
			startLoad();  
			var input = $("input[name='send-link']:checked").val();
			var notifiId = $("#notiId").val();
			var cashotp = $("#cash_otp").val();
			if(notifiId != '' || notifiId != null){
				//if already payment link sent
				var payLinkId = $("#linkid").val();	
		
				$.ajax({
					url :"{{ route('make-trip-pay') }}",
					method:"POST",
					data: {
						"_token": "{{ csrf_token() }}",
						"notifiId": notifiId,
						"israzorpay": input,
						"ispaylinksent": payLinkId,
						"cashotp": cashotp,
					},
					success : function(data){
						//console.log('paylink data ',data);
						console.log('paylink data ',data);
						clearTimeout(timeout);
		            	timeout = setTimeout(loaded, 1500);
						if(data.response.status == 'Success'){
							swal({
								title: 'Payment Link Sent Successfully!.',
								type: 'success',
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'Ok'
							}).then(function() 
							{   

							})
							$("#linkid").val(data.response.linkid);
						}else if(data.response.status == 'otpsuccess'){
							swal({
								title: 'Cash payment is saved.',
								type: 'success',
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'Ok'
							})
						}
					}
				});
			}else{
				alert('empty notification id');
			}
		}else{
			alert('Please select payment');
			return false;
		}
	});

	$("#get-pay-link-resp").click(function(){
		startLoad();
		var invoiceid = $("#linkid").val();
		var notiId = $("#notiId").val();
		$.ajax({
			url :"/get-trip-pay-link-response/"+invoiceid+"/"+notiId,
			method:"GET",
			success : function(data){
				console.log('data here',data);
				clearTimeout(timeout);
	            timeout = setTimeout(loaded, 1500);
				if(data.response.status == 'Success'){
					$("#razorpayId").val(data.response.payId);
					$("#razorpayDate").val(data.response.payDate);
				}else{
					swal({
						title: 'Payment pending!',
						type: 'warning',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ok'
					})
				}
			}
		});
	});
	
	/*--------------------on click pay end---------------*/

	/*--------------------get all images-----------------*/
	function getImages(id){
		var veh_id = id;
        startLoad();
        $.ajax({
            url :"/add-vehicle-images/"+id,
            method:"GET",
            success : function(data){
	            console.log('data here',data);
	            clearTimeout(timeout);
	            timeout = setTimeout(loaded, 1500);
	            if(data !== null)
	            {
	                    var imghtml='';
	                    $('#all-images').empty();
	                    var images = data.response.vehicles.veh_img_data;
	                    $.each(images, function (key, value)
	                    {
	                            imghtml += '<img src="images/'+value.img_url+'" alt="GOGOTRUX" class="veh-image">';
				
	                    });
	                    $('#view-all-images').modal('toggle');
	                    $("#all-images").append(imghtml);
	                    //$('#view-all-images').modal('toggle');
	            }
            }
        });
	}
	/*--------------------end here-----------------------*/
	
	
	$("#sendTripInvoice").click(function(){
		let pattern =new RegExp('^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$'); 
		var gstn = $("#gstn").val();
		let result = pattern.test(gstn);
		
		var email = $("#tripemail").val();
		
		var user_mobile_no = $("#user_mobile_no").val();
		var notiId = $("#notiId").val();
		if(email == '' || email == null || result== false){
			if(result == false){
				alert('Please enter Valid GSTN');
			}else{
				alert('Please enter email address');
			}
			return false;
		}else{
			$.ajax({
				url :"{{ route('send-trip-invoice') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"email": email,
					"gstn": gstn,
					"user_mobile_no": user_mobile_no,
					"notiId": notiId,
				},
				success : function(data){
					
					console.log('email data ',data);
				if(data['response']['status'] == 'Success'){
					swal({
						title: 'Email Sent Successfully!.',
						type: 'success',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ok'
					}).then(function() 
					{   

					})
					clearTimeout(timeout);
	        			timeout = setTimeout(loaded, 1500);
				}else if(data['response']['status'] == 'TripNotCompleted'){
						swal({
						title: 'Trip Not Yet Completed',
						type: 'warning',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ok'
						}).then(function() 
						{   

						})
				}
				}
			});
		}
			
	});


	$("#save_trip").click(function(){
		var notiId = $("#notiId").val();
		$.ajax({
			url :"{{ route('save-trip') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"notiId": notiId,
			},
			success : function(data){
				if(data.response.status == 'success'){
					swal({
						title: 'Booking Saved Successfully!',
						type: 'success',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ok'
					}).then(function() 
					{   
						window.location = '{{ url("Orders") }}';
					})	
				}else if(data.response.status == 'payFailed'){
					swal({
						title: 'Please Select Payment!',
						type: 'warning',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ok'
					})
				}
			}
		});
	});
	
	/*---------------------------verify cash otp start---------------------*/
	$("#verify_otp").click(function(){
		var cashotp = $("#cash_otp").val();
		var notifiId = $("#notiId").val();
		if(cashotp == '' || cashotp == null){
			alert('Please enter cash otp');
			return false;
		}
		$.ajax({
			url :"{{ route('verify-cash-otp') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"notifiId": notifiId,
				"cashotp": cashotp,
			},
			success : function(data){
				console.log('paylink data ',data);
				clearTimeout(timeout);
            	timeout = setTimeout(loaded, 1500);
				if(data.response.status == 'otpsuccess'){
                        swal({
                                title: 'Thank You, Your Payment has been Received.',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                        })
			$("#verify_otp").prop("disabled", true);
                }else{
                        swal({
                                title: 'Incorrect Otp.',
                                type: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                        })
                }
			}
		});
		
	});
	/*---------------------------verify cash otp end-----------------------*/

	/*---------------------------book trip start--------------------*/
	$("#book_trip").click(function(){
		if(confirm("Are you sure to book?")){
		startLoad();
		var notiId = $("#notiId").val();
		var bookdate = $("#pickup_date").val();
		var booktime = $("#pickup_time").val();
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();
		var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
		today = mm + '/' + dd + '/' + yyyy;
		if(bookdate >= today){
		//if(time <= booktime){
		if(bookdate == today){
		   if(time <= booktime){
		$.ajax({
			url :"{{ route('book-trip') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"notiId": notiId,
			},
			success : function(data){
				console.log('data after book',data);
				clearTimeout(timeout);
	    		timeout = setTimeout(loaded, 1500);
				if(data.response.status == 'success'){
				var tripId = data.response.trip_id;
	    			var bookingData = data.response.booking_details;
	    			var vehicleData = data.response.vehicle_details;
	    			var pickupDate = bookingData.pickup_date.day+'/'+bookingData.pickup_date.month+'/'+bookingData.pickup_date.year;
			$('#trip_details').append('<div class="row"><h4 class="trip-title">Trip Detail <span>'+tripId+'</span></h4><div class="row"><div class="col-md-7 p-l-r-5"><div class="cust_detail"><h5>Customer</h5><table><tr><td>CID</td><td>:</td><td>'+bookingData.cust_cid+'</td></tr><tr><td>Customer Name</td><td>:</td><td>'+bookingData.cust_name+'</td></tr><tr><td>Mobile No</td><td>:</td><td>'+bookingData.cust_mobile+'</td></tr><tr><td>Pick-Up Address</td><td>:</td><td>'+bookingData.pickup_location+'</td></tr><tr><td>Pick-Up Time</td><td>:</td><td>'+bookingData.pickup_time+'</span></td></tr><tr><td>Pick-Up Date</td><td>:</td><td>'+pickupDate+'</td></tr><tr><td>Delivery Address</td><td>:</td><td>'+bookingData.delivery_location+'</td></tr><tr><td>Ledger balance</td><td>:</td><td>Rs 0</td></tr></table></div></div><div class="col-md-5 p-l-r-5"><div class="cust_detail"><h5>Partner</h5><table><tr><td>Driver</td><td>:</td><td>'+vehicleData.veh_owner_name+'</td></tr><tr><td>Mobile No</td><td>:</td><td>'+vehicleData.veh_op_username+'</td></tr><tr><td>Loaders</td><td>:</td><td>'+vehicleData.veh_loader_available+'</td></tr><tr><td>Tariff</td><td>:</td><td>Rs <span>'+Number(vehicleData.total_amount).toFixed(2)+'</span></td></tr><tr><td>Loader</td><td>:</td><td>Rs '+vehicleData.loader_amount+'</td></tr><tr><td class="v-red">Amount</td><td>:</td><td class="v-red">Rs '+Number(vehicleData.total_amount_factor).toFixed(2)+'</td></tr><tr><td>Balance</td><td>:</td><td>Rs <span class="v-red">0</span></td></tr><tr><td class="v-red">Payment</td><td>:</td><td class="v-red">'+bookingData.payment_mode+'</td></tr></table></div></div></div></div>\
<div class="row">\
			<div class="col-sm-12">\
						<div class="row">\
					<div class="col-sm-12">'+data.response.overtimechargestext+'</div>\
						</div>\
				<div class="row">\
					<div class="col-sm-3">\
						<label>Cust wt chrj</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="cust_waiting_charges" >\
					</div>\
					<div class="col-sm-3">\
						<label>Partner wt chrj</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="partner_waiting_charges" >\
					</div>\
				</div>\
				<div class="row">\
					<div class="col-sm-3">\
						<label>Incidental</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="incidental_charges" >\
					</div>\
					<div class="col-sm-3">\
						<label>Accidental</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="accidental_charges" >\
					</div>\
				</div>\
				<div class="row pin-input">\
					<div class="col-sm-3">\
						<label>Other</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="other_charges" >\
					</div>\
					<div class="col-sm-6">\
						<button type="button" onclick="submitcharges(\''+tripId+'\')" class="btn btn-primary">Submit</button>\
					</div>\
				</div>\
			</div>\
		</div>\
<div class="row"><h4 class="trip-title p-t-10">Delivery</h4><div class="pin-input"> <input class="form-control" type="text" id="close_trip_pin"/> <button class="btn btn-primary" onclick="genCloseTripPin()">Generate Pin</button><label>Enter PIN</label></div><div class="row"><div class="col-sm-8 p-0"><div class="row form-group"><div class="col-md-6"><div> <label class="left-radio-box">Cancelled <input type="radio" name="close-trip-opt" value="cancelled"/> <span class="radio-btn"></span> </label></div><div> <label class="left-radio-box">Disputed <input type="radio" name="close-trip-opt" value="disputed"/> <span class="radio-btn"></span> </label></div></div><div class="col-md-6"><div> <label class="left-radio-box">Success <input type="radio" name="close-trip-opt" value="success"/> <span class="radio-btn"></span> </label></div><div> <label class="left-radio-box">Unpaid <input type="radio" name="close-trip-opt" value="unpaid"/> <span class="radio-btn"></span> </label></div></div></div></div><div class="col-sm-4 p-0"><div class="trip-btn"> <button class="btn btn-primary" onclick="closeTrip()">Close Trip</button></div><div class="trip-btn"> <button class="btn btn-success" onclick="postTrip()">Post Trip</button></div></div></div></div>');
			$("#book_trip").prop("disabled", true);
			$("#save_trip").prop("disabled", true);
			}else if(data.response.status == 'payFailed'){
	    			swal({
						title: 'Please Make Payment First!',
						type: 'warning',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ok'
					})
	    		}
			}
		});
		}else{
                    alert('Booking time expired!');
                   }                                                    
                }else{
			//advance booking
			$.ajax({
        url :"{{ route('book-trip') }}",
        method:"POST",
        data: {
                "_token": "{{ csrf_token() }}",
                "notiId": notiId,
        },
        success : function(data){
                console.log('data after book',data);
                clearTimeout(timeout);
        timeout = setTimeout(loaded, 1500);
                if(data.response.status == 'success'){
                var tripId = data.response.trip_id;
                var bookingData = data.response.booking_details;
                var vehicleData = data.response.vehicle_details;
                var pickupDate = bookingData.pickup_date.day+'/'+bookingData.pickup_date.month+'/'+bookingData.pickup_date.year;
        $('#trip_details').append('<div class="row"><h4 class="trip-title">Trip Detail <span>'+tripId+'</span></h4><div class="row"><div class="col-md-7 p-l-r-5"><div class="cust_detail"><h5>Customer</h5><table><tr><td>CID</td><td>:</td><td>'+bookingData.cust_cid+'</td></tr><tr><td>Customer Name</td><td>:</td><td>'+bookingData.cust_name+'</td></tr><tr><td>Mobile No</td><td>:</td><td>'+bookingData.cust_mobile+'</td></tr><tr><td>Pick-Up Address</td><td>:</td><td>'+bookingData.pickup_location+'</td></tr><tr><td>Pick-Up Time</td><td>:</td><td>'+bookingData.pickup_time+'</span></td></tr><tr><td>Pick-Up Date</td><td>:</td><td>'+pickupDate+'</td></tr><tr><td>Delivery Address</td><td>:</td><td>'+bookingData.delivery_location+'</td></tr><tr><td>Ledger balance</td><td>:</td><td>Rs 0</td></tr></table></div></div><div class="col-md-5 p-l-r-5"><div class="cust_detail"><h5>Partner</h5><table><tr><td>Driver</td><td>:</td><td>'+vehicleData.veh_owner_name+'</td></tr><tr><td>Mobile No</td><td>:</td><td>'+vehicleData.veh_op_username+'</td></tr><tr><td>Loaders</td><td>:</td><td>'+vehicleData.veh_loader_available+'</td></tr><tr><td>Tariff</td><td>:</td><td>Rs <span>'+Number(vehicleData.total_amount).toFixed(2)+'</span></td></tr><tr><td>Loader</td><td>:</td><td>Rs '+vehicleData.loader_amount+'</td></tr><tr><td class="v-red">Amount</td><td>:</td><td class="v-red">Rs '+Number(vehicleData.total_amount_factor).toFixed(2)+'</td></tr><tr><td>Balance</td><td>:</td><td>Rs <span class="v-red">0</span></td></tr><tr><td class="v-red">Payment</td><td>:</td><td class="v-red">'+bookingData.payment_mode+'</td></tr></table></div></div></div></div>\
<div class="row">\
			<div class="col-sm-12">\
						<div class="row">\
						<div class="col-sm-12">'+data.response.overtimechargestext+'</div>\
						</div>\
				<div class="row">\
					<div class="col-sm-3">\
						<label>Cust wt chrj</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="cust_waiting_charges" >\
					</div>\
					<div class="col-sm-3">\
						<label>Partner wt chrj</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="partner_waiting_charges" >\
					</div>\
				</div>\
				<div class="row">\
					<div class="col-sm-3">\
						<label>Incidental</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="incidental_charges" >\
					</div>\
					<div class="col-sm-3">\
						<label>Accidental</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="accidental_charges" >\
					</div>\
				</div>\
				<div class="row pin-input">\
					<div class="col-sm-3">\
						<label>Other</label>\
					</div>\
					<div class="col-sm-3">\
						<input type="number" class="form-control" id="other_charges" >\
					</div>\
					<div class="col-sm-6">\
						<button type="button" onclick="submitcharges(\''+tripId+'\')" class="btn btn-primary">Submit</button>\
					</div>\
				</div>\
			</div>\
		</div>\
<div class="row"><h4 class="trip-title p-t-10">Delivery</h4><div class="pin-input"> <input class="form-control" type="text" id="close_trip_pin"/> <button class="btn btn-primary" onclick="genCloseTripPin()">Generate Pin</button><label>Enter PIN</label></div><div class="row"><div class="col-sm-8 p-0"><div class="row form-group"><div class="col-md-6"><div> <label class="left-radio-box">Cancelled <input type="radio" name="close-trip-opt" value="cancelled"/> <span class="radio-btn"></span> </label></div><div> <label class="left-radio-box">Disputed <input type="radio" name="close-trip-opt" value="disputed"/> <span class="radio-btn"></span> </label></div></div><div class="col-md-6"><div> <label class="left-radio-box">Success <input type="radio" name="close-trip-opt" value="success"/> <span class="radio-btn"></span> </label></div><div> <label class="left-radio-box">Unpaid <input type="radio" name="close-trip-opt" value="unpaid"/> <span class="radio-btn"></span> </label></div></div></div></div><div class="col-sm-4 p-0"><div class="trip-btn"> <button class="btn btn-primary" onclick="closeTrip()">Close Trip</button></div><div class="trip-btn"> <button class="btn btn-success" onclick="postTrip()">Post Trip</button></div></div></div></div>');
        $("#book_trip").prop("disabled", true);
        $("#save_trip").prop("disabled", true);
        }else if(data.response.status == 'payFailed'){
                swal({
                                title: 'Please Make Payment First!',
                                type: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                        })
        }
        }
});
		}
		}else{
			alert('Booking date expired!');
		}
		}else{
			return false;
		}
	});
	/*---------------------------book trip end--------------------*/
	
	function submitcharges(tripId){
		if(tripId){
			$.ajax({
				url :"{{ route('other-charges') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					'trip_transaction_id': tripId,
					'cust_waiting_charges': $('#cust_waiting_charges').val(),
					'partner_waiting_charges': $('#partner_waiting_charges').val(),
					'incidental_charges': $('#incidental_charges').val(),
					'accidental_charges': $('#accidental_charges').val(),
					'other_charges': $('#other_charges').val(),
				},
				success : function(data){
					if(data == 'success'){
						alert('Charges Saved');
					}else{

					}
				}
			});
		}
	}
	function genCloseTripPin(){
		var notifiId = $("#notiId").val();
		$.ajax({
			url :"{{ route('close-trip-pin') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"notiId": notifiId,
			},
			success : function(data){
				$("#close_trip_pin").val(data.response.pin);
			}
		});
	}

	function postTrip(){
		var notifiId = $("#notiId").val();
		$.ajax({
			url :"{{ route('post-trip') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"notiId": notifiId,
			},
			success : function(data){
				swal({
                        title: 'Trip Post Successfully!',
                        type: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                }).then(function() 
                {   
                        window.location = '{{ url("Orders") }}';
                })
			}
		});
	}

	function closeTrip(){
        var input = $("input[name='close-trip-opt']:checked").val();
        if(input != undefined){
        var notifiId = $("#notiId").val();
		$.ajax({
			url :"{{ route('close-trip-status') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"notiId": notifiId,
				"input": input,
			},
			success : function(data){
				swal({
                title: 'Trip '+input+'!',
                type: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
        	}).then(function() 
            {   
               // window.location = '{{ url("Orders") }}';
            })
			}
		});
        }else{
                alert('Please select input');
        }
    }
</script>
@endsection




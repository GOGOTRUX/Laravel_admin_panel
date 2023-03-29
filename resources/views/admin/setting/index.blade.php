@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Settings
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">settings</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success" id="message">
				{{ session('success') }}
			</div>
		@endif

		<div class="col-xs-12">
			<ul class="nav nav-tabs">
				<li class="active" id="tab_1"><a data-toggle="tab" href="#notification" id="notification_href">Charge Setting</a></li>
				<li id="tab_2"><a data-toggle="tab" href="#Mail" id="mail_href">Bank Details</a></li>
				<li id="tab_3"><a data-toggle="tab" href="#switch_sms_gateway" id="switch_sms_gateway_href">SMS Gateway</a></li>
<li id="tab_4"><a data-toggle="tab" href="#waiting_charges" id="waiting_charges_href">Waiting Charges</a></li>
			</ul>

			<div class="tab-content">
				<div id="notification" class="tab-pane fade in active">
					<div class="box">
						<div class="box-body">
						<a href="#" class="btn btn-xs btn-success" id="formButton">Add Charge</a>
						@if(Session::has('setting_id'))
							<form method="POST" id="settingForm" action="{{ route('setting.update', [ Session::get('setting_id')]) }}" enctype="multipart/form-data">  
							@method('PUT')
						@else
							<form method="POST" id="settingForm" action="{{ route('setting.store') }}" enctype="multipart/form-data" style="display: none;">
						@endif
						  
							@csrf
							<div class="panel panel-default">
								<div class="panel-heading">
									Add Charge
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-6 col-sm-6 form-group" id="selectedChargeType">
											<div class="f-half">
												<input type="hidden" id="setting_id" name="setting_id" value="{{ Session::has('setting_id') ? Session::get('setting_id') : '' }}" required>

												<label for="setting_label" class="control-label">{{ __('Title *') }}</label>
												<input id="setting_label" type="text" class="form-control" name="setting_label" value="{{ Session::has('setting_label') ? Session::get('setting_label') : '' }}" {{ Session::has('setting_charge_type') ? 'readonly' : '' }}
												onkeyup="this.value = this.value.toUpperCase();" autofocus>
												<p class="help-block"></p>
												@if($errors->has('setting_label'))
													<p class="help-block text-red">
														{{ $errors->first('setting_label') }}
													</p>
												@endif

											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-sm-6 form-group">
											<label for="setting_charge_type" class="control-label">{{ __('Charge type*') }}</label>
												<div class="row">
													<div class="on-radio">
														<input id="setting_charge_type" type="radio" name="setting_charge_type" value="0" {{ Session::has('setting_charge_type') && (Session::get('setting_charge_type') == '0') ? 'checked' : '' }}><span>(By Percentage %)</span>
														<!-- onclick="selectChargeType(0)" -->
													</div>
													<div class="on-radio">
														<input id="setting_charge_type" type="radio" name="setting_charge_type" value="1" {{ Session::has('setting_charge_type') && (Session::get('setting_charge_type') == '1') ? 'checked' : '' }}><span>By value</span>
													</div>
												</div>
												@if($errors->has('setting_charge_type'))
													<p class="help-block text-red">
														{{ $errors->first('setting_charge_type') }}
													</p>
												@endif
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 col-sm-6 form-group" id="selectedChargeType">
											<div class="f-half">
												<label for="setting_charge_amount" class="control-label">{{ __('Charge Amount*') }}</label>
												<input id="setting_charge_amount" type="text" class="form-control" name="setting_charge_amount" value="{{ Session::has('setting_charge_amount') ? Session::get('setting_charge_amount') : '' }}" autofocus>
												<p class="help-block"></p>
												@if($errors->has('setting_charge_amount'))
													<p class="help-block text-red">
														{{ $errors->first('setting_charge_amount') }}
													</p>
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 form-group">
											<a href="{{ URL::previous() }}" class="btn btn-danger">Back</a>

											<button type="submit" class="btn btn-success">{{Session::has('setting_id') && Session::get('setting_id') ? 'Update' : 'Save' }}</button>
											<!-- <button type="submit" class="btn btn-danger">Submit</button> -->
										</div>
									</div>
								</div>
							</div>
						</form>
							<!-- list setting rows -->
							@if(!empty($setting_charges))
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive m-t-20">
											<table id="document" class="table table-bordered table-striped {{ count($setting_charges) > 0 ? 'datatable' : '' }} " data-page-length="25">
												<thead>
													<tr>
														<th class="text-center">Sr.No</th>
														<th>Title</th>
														<th>Charge Amount <br> (% or value))</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php $count=1; ?>   
													@foreach ($setting_charges as $charge)
														<tr>
															<td class="text-center">{{ $count++ }}</td>
															<td> {{ $charge['setting_label'] }}</td>
															<td>{{ $charge['setting_charge_amount'] ? $charge['setting_charge_amount'] : '0' }}</td>
															<td>
																<a href="{{ route('setting.edit',[$charge['id']]) }}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
																<!-- onclick="deletecharge()" -->
																<!-- <button class="btn btn-xs btn-danger" type="button"><i class="fa fa-trash-o"></i></button> -->
															</td>
														</tr>
													@endforeach
												</tbody>                
											</table>
										</div>
									</div>
								</div>
							@endif
							<!-- end setting rows -->
						</div>
					</div>
				</div>
				<div id="Mail" class="tab-pane fade">
					<div class="box">
						<div class="box-body">
							<form method="POST" id="driverKatta" name="driver_katta" action="{{ route ('save-admin-bank') }} " enctype="multipart/form-data" data-page-length="10">  
								@csrf
								<div class="row">
									<div class="col-md-12">
										<button type="button" name="add" id="add_banks" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i></button>
										<label for="katta_useful_links_name" class="control-label">Click to Add Bank Details</label>
									</div>
								</div>
								<div class="row" id="submit_bank_details_btn">
									<div class="col-md-12 text-right" id="append_bank_details">
										<input type="submit" class="btn btn-xs btn-success" value="Submit">
									</div>
								</div>
							</form>
							@if(Session::has('idEdit'))
								@if(Session::get('idEdit')==1)
									<form method="POST" class="form-body update_bank" id="updatebankInfo" name="updatebankInfo" action="{{ route ('update-bankinfo') }} " enctype="multipart/form-data" data-page-length="10">  
									@csrf
										<div class="row m-t-20">
											<div class="col-md-1 form-group">
												<input type="hidden" class="form-control" id="id" name="id" value="{{ Session::has('id') ? Session::get('id') : '' }}" required>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4 form-group">
												<label class="control-label">{{ __('Name *') }}</label>
												<input type="text" id="name" name="name" class="form-control" value="{{ Session::has('name') ? Session::get('name') : '' }}" required>
											</div>	
											<div class="col-md-4 form-group">
												<label class="control-label">{{ __('Account No *') }}</label>
												<input type="text" id="name" class="form-control" name="account_num" value="{{ Session::has('account_num') ? Session::get('account_num') : '' }}" required>
											</div>	
											<div class="col-md-4 form-group">
												<label class="control-label">{{ __('IFSC Code *') }}</label>
												<input type="text" id="name" class="form-control" name="ifsc_code" value="{{ Session::has('ifsc_code') ? Session::get('ifsc_code') : '' }}" required>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4 form-group">
												<label class="control-label">{{ __('Bank Name *') }}</label>
												<input type="text" id="name" class="form-control" name="bank_name" value="{{ Session::has('bank_name') ? Session::get('bank_name') : '' }}" required>
											</div>
											<div class="col-md-4 form-group">
												<label class="control-label">{{ __('Branch Name *') }}</label>
												<input type="text" id="name" class="form-control" name="branch_name" value="{{ Session::has('branch_name') ? Session::get('branch_name') : '' }}" required>
											</div>
											<div class="col-md-4 form-group">
												<label class="control-label">{{ __('City *') }}</label>
												<input type="text" id="name" class="form-control" name="city" value="{{ Session::has('city') ? Session::get('city') : '' }}" required>
											</div>
											<!-- <div class="col-md-4">
												<label class="control-label">{{ __('Is Choosed *') }}</label>
												<input type="radio" id="name" name="is_selected" value="{{ Session::has('is_selected') ? Session::get('is_selected') : '' }}" required>
											</div> -->
										</div>
										<div class="row" id="append_bank_details">
											<div class="col-md-12 text-right">
												<input type="submit" class="btn btn-xs btn-success" value="Update">
											</div>
										</div>
									</form>
								@else
								@endif
							@endif
							<!-- list banks -->
							@if(!empty($bank_details))
								<div class="row">
									<!-- <form method="POST" id="setdefaultbank" name="setdefaultbank" action="{{ route ('update-default-admin-bank') }} " enctype="multipart/form-data" data-page-length="10">  
										@csrf -->
									<div class="col-sm-12">
										<div class="table-responsive m-t-20">
											<table id="bank_details" class="table table-bordered table-striped bank_list {{ count($bank_details) > 0 ? 'datatable' : '' }} " data-page-length="25">
												<thead>
													<tr>
														<th>Sr.No</th>
														<th>Name</th>
														<th>Account No</th>
														<th>IFSC Code</th>
														<th>Bank</th>
														<th>Branch</th>
														<th>City</th>
														<th>Set Default</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php $count=1; ?>   
													@foreach ($bank_details as $bank)
														<tr>
															<td>{{ $count++ }}</td>
															<td>{{ $bank['name'] }}</td>
															<td>{{ $bank['account_num'] ? $bank['account_num'] : '-.' }}</td>
															<td>{{ $bank['ifsc_code'] ? $bank['ifsc_code'] : '-.' }}</td>
															<td>{{ $bank['bank_name'] ? $bank['bank_name'] : '-.' }}</td>
															<td>{{ $bank['branch_name'] ? $bank['branch_name'] : '-.' }}</td>
															<td>{{ $bank['city'] ? $bank['city'] : '-.' }}</td>
															<td>
																<input type="radio" name="is_selected" value="0" {{  $bank['is_selected'] == '1' ? 'checked' : '' }} onclick="setdefaultbank('{{ $bank['id'] }}','{{ $bank['name'] }}')">
															</td>
															<td>
																<a href="{{ route('edit-bankinfo',[$bank['id']]) }}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
																<button class="btn btn-xs btn-danger" type="button" onclick="deleteBank('{{ $bank['id'] }}','{{ $bank['name'] }}')"><i class="fa fa-trash-o"></i></button>
															</td>
														</tr>
													@endforeach
												</tbody>                
											</table>
											<!-- <form method="GET" id="setdefaultbank" name="setdefaultbank" action="{{ route ('update-default-admin-bank',[$bank['id']]) }} " enctype="multipart/form-data" data-page-length="10">  
											
												<div class="col-md-12">
													<input type="submit" class="btn btn-xs btn-success pull-right" value="Submit">
												</div>
											</form> -->
										</div>
									</div>
								</div>
							@endif
							<!-- end banks -->
						</div>
					</div>
				</div>
				<div id="switch_sms_gateway" class="tab-pane fade">
					<div class="box">
						<div class="box-body">
							<form method="POST" id="switchSmsGatewayForm" action="{{ route('switch-sms-gateway') }}">
								@csrf
								<label for="sms_gateway" class="control-label">{{ __('Select SMS Gateway *') }}</label>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-2">
											<label><input type="radio" name="sms_gateway" value="MSG91" {{ (Session::get('active_sms_gateway') == 'MSG91') ? 'checked' : '' }}/> Msg91</label>
										</div>
										<div class="col-md-2">
											<label><input type="radio" name="sms_gateway" value="NETCORE" {{ (Session::get('active_sms_gateway') == 'NETCORE') ? 'checked' : '' }}/> Netcore</label>
										</div>
										<div class="col-md-2">
											<input type="hidden" name="edit_id" value="{{ (Session::get('active_sms_gateway_id')) ? Session::get('active_sms_gateway_id') : null }}"/>
											<button type="submit" name="sms_switch_submit" class="btn btn-primary"> Submit</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div id="waiting_charges" class="tab-pane fade">
					<div class="box">
						<div class="box-body">
							<?php 
							$charges30min = 0;
							$charges1hour = 0;
							$charges130hour = 0;
							$charges2hour = 0;
							$charges230hour = 0;
							$charges3hour = 0;
							$charges330hour = 0;
							$charges4hour = 0;
							$charges430hour = 0;
							$charges5hour = 0;
							$charges530hour = 0;
							$charges6hour = 0;
							$charges630hour = 0;
							$charges7hour = 0;
							$charges730hour = 0;
							$charges8hour = 0;
							$charges830hour = 0;
							$charges9hour = 0;
							$charges930hour = 0;
							$charges10hour = 0;
							if(count($overtimecharges)){
								foreach($overtimecharges as $oc){
									if($oc->overtime == '30_min'){
										$charges30min = $oc->charges;
									}
									if($oc->overtime == '1_hour'){
										$charges1hour = $oc->charges;
									}
									if($oc->overtime == '1_30_hour'){
										$charges130hour = $oc->charges;
									}
									if($oc->overtime == '2_hour'){
										$charges2hour = $oc->charges;
									}
									if($oc->overtime == '2_30_hour'){
										$charges230hour = $oc->charges;
									}
									if($oc->overtime == '3_hour'){
										$charges3hour = $oc->charges;
									}
									if($oc->overtime == '3_30_hour'){
										$charges330hour = $oc->charges;
									}
									if($oc->overtime == '4_hour'){
										$charges4hour = $oc->charges;
									}
									if($oc->overtime == '4_30_hour'){
										$charges430hour = $oc->charges;
									}
									if($oc->overtime == '5_hour'){
										$charges5hour = $oc->charges;
									}
									if($oc->overtime == '5_30_hour'){
										$charges530hour = $oc->charges;
									}
									if($oc->overtime == '6_hour'){
										$charges6hour = $oc->charges;
									}
									if($oc->overtime == '6_30_hour'){
										$charges630hour = $oc->charges;
									}
									if($oc->overtime == '7_hour'){
										$charges7hour = $oc->charges;
									}
									if($oc->overtime == '7_30_hour'){
										$charges730hour = $oc->charges;
									}
									if($oc->overtime == '8_hour'){
										$charges8hour = $oc->charges;
									}
									if($oc->overtime == '8_30_hour'){
										$charges830hour = $oc->charges;
									}
									if($oc->overtime == '9_hour'){
										$charges9hour = $oc->charges;
									}
									if($oc->overtime == '9_30_hour'){
										$charges930hour = $oc->charges;
									}
									if($oc->overtime == '10_hour'){
										$charges10hour = $oc->charges;
									}
								}
							}
							?>
							<form method="POST" id="overtimeChargeForm" action="{{ route('overtime-charges') }}">
								@csrf
								<label for="sms_gateway" class="control-label">{{ __('Select Overtime Charges *') }}</label>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="30_min"/> 30 Min</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_30_min" placeholder="Charges" value="{{ $charges30min }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="1_hour"
													<?php 
													if($charges1hour > 0){
														echo "checked";
													}
													?>
													/> 1 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_1_hour" placeholder="Charges" value="{{ $charges1hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="1_30_hour"/> 1:30 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_1_30_hour" placeholder="Charges" value="{{ $charges130hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="2_hour"/> 2 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_2_hour" placeholder="Charges" value="{{ $charges2hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="2_30_hour"/> 2:30 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_2_30_hour" placeholder="Charges" value="{{ $charges230hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="3_hour"/> 3 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_3_hour" placeholder="Charges" value="{{ $charges3hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="3_30_hour"/> 3:30 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_3_30_hour" placeholder="Charges" value="{{ $charges330hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="4_hour"/> 4 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_4_hour" placeholder="Charges" value="{{ $charges4hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="4_30_hour"/> 4:30 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_4_30_hour" placeholder="Charges" value="{{ $charges430hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="5_hour"/> 5 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_5_hour" placeholder="Charges" value="{{ $charges5hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="5_30_hour"/> 5:30 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_5_30_hour" placeholder="Charges" value="{{ $charges530hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="6_hour"/> 6 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_6_hour" placeholder="Charges" value="{{ $charges6hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="6_30_hour"/> 6:30 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_6_30_hour" placeholder="Charges" value="{{ $charges630hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="7_hour"/> 7 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_7_hour" placeholder="Charges" value="{{ $charges7hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="7_30_hour"/> 7:30 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_7_30_hour" placeholder="Charges" value="{{ $charges730hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="8_hour"/> 8 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_8_hour" placeholder="Charges" value="{{ $charges8hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="8_30_hour"/> 8:30 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_8_30_hour" placeholder="Charges" value="{{ $charges830hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="9_hour"/> 9 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_9_hour" placeholder="Charges" value="{{ $charges9hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="9_30_hour"/> 9:30 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_9_30_hour" placeholder="Charges" value="{{ $charges930hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<label><input type="radio" name="overtime" value="10_hour"/> 10 Hour</label>
												</div>
												<div class="col-md-2">
													<input type="number" name="charges_10_hour" placeholder="Charges" value="{{ $charges10hour }}"/>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<button type="button" name="overtime_charge" onclick="overtimechargesubmit()" class="btn btn-primary"> Submit</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
<script language="javascript" type="text/javascript">
	$('#setting_label').keypress(function( e ) {
    if(e.which === 32) 
        return false;
	});
	$(document).ready(function () {
		//make tab active
		var idEditTab = "{{Session::get('idEditTab')}}";
		if(idEditTab==1){
			$("#tab_1").removeClass("active");
			$("#tab_2").addClass("active");
			$("#Mail").addClass("active in");
			$("#notification").removeClass("active in");
		}else{
			$("#tab_2").removeClass("active");
			$("#tab_1").addClass("active");
			$("#notification").addClass("active in");
			$("#Mail").removeClass("active in");
		}	

		var has_error = '{{ $errors->isEmpty() }}';
		if(has_error!=1){
			// form has error show message
			$("#settingForm").show();
			console.log('error message');
		}else{
			// no error message
		}
		$("#formButton").click(function(){
			$("#settingForm").show();
		 	// $("#settingForm").toggle();
	    });	
	});

	// add from validation
		$("#settingForm").validate({
			rules: {
				setting_label: {
					required: true,
					alpha_numbers: true,
				},
				setting_charge_type: {
					required: true,
				},
				setting_charge_amount: {
					required: true,
				},
			},
			messages: {
				setting_label: {
					required:'Please enter title',
				},
				setting_charge_type: {
					required:'Please select type',
				},
				setting_charge_amount: {
					required:'Please enter amount type',
				},
			},
			invalidHandler: function(event, validator) {
				// console.log(event);
				// console.log(validator);
			},		
		}); 
	// end form validation

	// Add Admin Bank Account 
	var i = 0;
	if(i==0){
		$("#submit_bank_details_btn").hide();
	}else{
		$("#submit_bank_details_btn").show();
	}
    $('#add_banks').click(function(){
        $('#append_bank_details').append('<div id="row'+i+'" class="m-t-20"><div class="row"><div class="col-md-4 form-group"><input type="text" class="form-control bank_name" name="bank_details['+i+'][name]" id="name['+i+']" placeholder="Name"></div><div class="col-md-4 form-group"><input type="text" class="form-control bank_acc_num" name="bank_details['+i+'][account_num]" id="account_num['+i+']" placeholder="Account Number"></div><div class="col-md-4 form-group"><input type="text" class="form-control bank_ifsc_code" name="bank_details['+i+'][ifsc_code]" id="ifsc_code['+i+']" placeholder="IFSC Code"></div></div><div class="row"><div class="col-md-4 form-group"><input type="text" class="form-control bank_bank_name" name="bank_details['+i+'][bank_name]" id="bank_name['+i+']" placeholder="Bank Name"></div><div class="col-md-4 form-group"><input type="text" class="form-control bank_branch_name" name="bank_details['+i+'][branch_name]" id="branch_name['+i+']" placeholder="Branch Name"></div><div class="col-md-4 form-group"><input type="text" class="form-control bank_city" name="bank_details['+i+'][city]" id="city['+i+']" placeholder="City"></div></div><div class="row"><div class="col-md-12 form-group"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-xs btn_remove"><i class="fa fa-times"></i></button></div></div></div>');
        i++;
        if(i==0){
			$("#submit_bank_details_btn").hide();
		}else{
			$("#submit_bank_details_btn").show();
		}
        $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id");
            $('#row'+button_id).remove();
            if(button_id==0){
				$("#submit_bank_details_btn").hide();
			}else{
				$("#submit_bank_details_btn").show();
			}
        });
    });

    function setdefaultbank(id,bank_name){
    	swal({
			title: 'Are you sure?',
			text: "You want to change bank information, this will change bank account details in partner app",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, make it!'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('update-default-admin-bank') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					swal({title: "Updated!", text: "Your bank "+"<strong class='text-success'>"+bank_name+"</strong> has set as default bank in partner app.", type: "success"}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
    }

    function deleteBank(id,bank_name){
    	$.ajax({
			url :"{{ route('check-bank-is-default-delete') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"id": id
			},
			success : function(data)
			{
				if(data == "false"){
				  	swal({
						title: 'Are you sure?',
						text: "It will delete your bank details!",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, do it!'
					}).then(function() 
					{
						$.ajax({
							url :"{{ route('delete-admin-bank') }}",
							method:"POST",
							data: {
								"_token": "{{ csrf_token() }}",
								"id": id
							},
							success : function(data)
							{
								swal({title: "Deleted!", text: "Your bank "+"<strong class='text-success'>"+bank_name+"</strong> has been deleted.", type: "success"}).then(function()
								{ 
									location.reload();
								});
							}
						});
					})
				}else if(data == "true"){
					swal({
						title: 'Warning!',
						text: "Can not delete bank info, choose another bank information as default before deleting this account",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, do it!'
					});
				}
				else {
					swal({
					  title: "Error!",
					  text : "Something went wrong."
					});
				}
			}
		});
    }
    //validate update bank details form
    $("#updatebankInfo").validate({
		rules: {
			name: {
				required: true,
			},
			account_num: {
				required: true,
				check_account_number:true,
			},
			ifsc_code:{
				required: true,
				check_ifsc:true,
			},
			bank_name: {
				required: true,
			},
			branch_name: {
				required: true,
			},
			city: {
				required: true,
			},
		},
		messages: {
			name: {
				required:'Please enter name',
			},
			account_num: {
				required:'Please enter account number',
			},
			ifsc_code:{
				required: 'Please enter IFSC code',
			},
			bank_name: {
				required:'Please enter bank name',
			},
			branch_name: {
				required:'Please enter branch name',
			},
			city: {
				required:'Please enter city',
			},
		},
		invalidHandler: function(event, validator) {
			// console.log(event);
			// console.log(validator);
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

	//validate add bank details form
	$('form#driverKatta').on('submit', function(event) {
        //Add validation rule for dynamically generated name fields
	    $('.bank_name').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Please enter name",
                }
            });
	    });
	    $('.bank_acc_num').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                digits: true,
                // min:15,
                // max:15,
                check_account_number:true,
                messages: {
                    required: "Please enter bank account number",
				}
            });
	    });
	    $('.bank_ifsc_code').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                check_ifsc:true,
                messages: {
                    required: "Please enter bank ifsc code",
				}
            });
	    });
	    $('.bank_bank_name').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Please enter bank name",
				}
            });
	    });
	    $('.bank_branch_name').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Please enter branch name",
				}
            });
	    });
	    $('.bank_city').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Please enter branch city",
				}
            });
	    });
	});
	$("#driverKatta").validate();
</script>
<script language="javascript" type="text/javascript">
function overtimechargesubmit(){
	let overtime = $("input[name='overtime']:checked").val();
	if(overtime){
		let charges = $("input[name='charges_"+overtime+"']").val();
		if(charges){
			$('#overtimeChargeForm').submit();
			// $.ajax({
			// 	url :"{{ route('overtime-charges') }}",
			// 	method:"POST",
			// 	data: {
			// 		"_token": "{{ csrf_token() }}",
			// 		"overtime": overtime,
			// 		"charges": charges
			// 	},
			// 	success : function(data)
			// 	{
			// 		swal({title: "Updated!", text: "Your details "+"<strong class='text-success'>changed</strong>.", type: "success"}).then(function()
			// 		{ 
			// 			location.reload();
			// 		});
			// 	}
			// });
		}else{
			alert('Please enter charges');
		}
	}else{
		alert('Please select any overtime with charges');
	}
}
</script>
@endsection


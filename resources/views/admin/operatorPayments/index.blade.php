@extends('layouts.app')
<!-- Content Header (Page header) -->
<style type="text/css">
	@media print {
		a[href]:after {
			content: none !important;
		}
	}
</style>
@section('content-header')
	<h1>Payments</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Payments</li>
		<li class="active">Receive Payment</li>
	</ol>
@endsection
<!-- Main Content -->
@section('content')
	@if(session('success'))
		<!-- If password successfully show message -->
		<div class="row">
			<div class="alert alert-success" id="success-message">
				{{ session('success') }}
			</div>
		</div>
	@else
		<!-- action="{{ route('deposite/store') }}" -->
	@endif
	<div class="panel-body p-0">
		<div class="view-op">
			<div class="row">
				<div class="section-title p-l-r-0">
					<div class="row">
						<div class="col-sm-8">
							<div class="form-group pay_note">
								<div class="radio">
									<label>
										<input type="radio" name="receive_from" id="pay_receive_from" value="option1" checked>
										Receive From
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="receive_from" id="pay_to_payment" value="option2">
										Pay To
									</label>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<!-- <div class="has-feedback"> -->
							<div class="input-group">
								<select class="form-control select2" data-placeholder="Search UID/Enter Mobile No." name="operator_id" id="operator_id" autofocus required>
									
								</select>
								<span class="input-group-addon inpt-adon" id="remove-select2"><i class="fa fa-remove"></i></span>
							</div>
						</div>
					</div>
				</div>
				<div class="section p-t-10">
					<form id="paymentInfo" method="POST" enctype='multipart/form-data' action="{{ route('payments.store') }}" >
						@csrf
						<div class="row">							
							<div class="pay-box">
								<table id="" class="table pay">
									<thead>
										<tr>
											<th>Unique ID</th>
											<!-- <th>Position</th> -->
											<th>Name</th>
											<th>Mobile No.</th>
											<!-- <th>Location</th> -->
											<th>Credit Balance</th>
											<th>Debit Balance</th>
											<th>Subscription</th>
											<th>Validity</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<input readonly name="op_email" id="op_email" type="hidden" class="form-control" 	value="">
											<td><input name="op_uid" id="op_uid" type="text" class="form-control" value=""></td>
											<!-- <td><input readonly name="op_position" id="op_position" type="text" class="form-control" value=""></td> -->
											<td><input readonly name="op_name" id="op_name" type="text" class="form-control" value=""></td>
											<td><input readonly name="op_mobile_no" id="op_mobile_no" type="text" class="form-control" value=""></td>
											<!-- <td><input readonly name="op_location" id="op_location" type="text" class="form-control" value=""></td> -->
											<td><input readonly name="op_credit_bal" id="op_credit_bal" type="text" class="form-control" value=""></td>
											<td><input readonly name="op_debit_bal" id="op_debit_bal" type="text" class="form-control" value=""></td>
											<td><input readonly name="op_subscription" id="op_subscription" type="text" class="form-control" value=""><span id="subscription-not-avail" class="hide" style="color:red;">No Plan Available</span></td>
											<td><input readonly name="op_validity" id="op_validity" type="text" class="form-control" value=""></td>
											<td><input readonly name="op_status" id="op_status" type="text" class="form-control" value=""></td>
										</tr>
									</tbody>
								</table>
								<!-- <div class="">
									<button class="btn btn-xs btn-default"><i class="fa fa-plus"></i></button>
								</div> -->
							</div>
						</div>
						<input type="hidden" name="op_id" id="op_id">
						<div class="row">
							<div class="pay-print">
								<!-- <button class="btn btn-xs btn-primary right" onclick="printPage()" type="button" title="Print"><i class="fa fa-print"></i></button>
								<strong>Print Invoice</strong> -->
							</div>
							<div class="pay-wrap">
								<div class="row" id="fetch_results">
									<div class="col-2 text-center">
										<div class="form-group">
											<label><b>Payment Purpose</b></label>
											<select id="payment_purpose" type="text" class="form-control" name="order_payment_purpose">
												<option value="">Select Payment Purpose</option>
												<option value="subscription">Subscription</option>
												<option value="registration_charges">Registration</option>
												<option value="trip_payments">Trip Payments</option>
												<option value="cash_trip_deposit">Cash Trip Deposit</option>
												<option value="penalty">Penalty</option>
												<option value="adjustment">Adjustments</option>
												<option value="balance">Balance</option>
												<option value="dispute">Dispute</option>
											</select>
										</div>
										<div class="form-group m-t-27">
											<label><b>Payment Instrument</b></label>
											<select id="payment_instrument" type="text" class="form-control" name="order_payment_instrument" onchange="changed_payment_instrument()">
												<!-- <option value="">Select Payment Instrument</option> -->
												<option value="cash">Cash</option>
												<option value="credit_card">Credit Card</option>
												<option value="debit_card">Debit Card</option>
												<option value="cheque">Cheque</option>
												<!-- <option value="digital">RazorPay</option> -->
												<option value="IMPS">IMPS</option>
												<option value="BHIM">BHIM</option>
												<option value="NEFT">NEFT</option>
												<option value="wallet">Wallet</option>
												<option value="other">Other</option>
											</select>
										</div>
										<div class="form-group hide" id="chequeImg">
											<div class="up-img" id="upload_button">
												<label>
													<b>Upload Cheque</b>
													<input id="cheque_img" type="file" class="custom-file-input form-control" name="cheque_img" onchange="preview_Image();"/>
													<span class="fa fa-camera"></span>
												</label>
											</div>
											<div id="logo_image_div" style="">
												<label for="view_cheque_images" class="control-label">{{ __('View') }}</label><br>
												<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_logo" ></i>
											</div>
										</div>
									</div>
									<div class="pay-section">
										<div class="pay-info">
											<div class="hide pay-sub" id="sub_details_div">
												<div class="col-3 form-group">
													<label><b>Subscription Scheme</b></label>
													<select id="sub_scheme_name" type="text" class="form-control" name="sub_scheme_name" onclick="is_plan_valid()">
														<option value="">No Subscription Scheme Available</option>
														<!-- @if(!empty($sub_plan_list))
															<option value="">Select Subscription Scheme</option>
															@foreach ($sub_plan_list as $plan)
																<option  value="{{ $plan['subscription_id'] }}">{{ $plan['subscription_type_name'] }}</option>
															@endforeach
														@else
														@endif -->
													</select>
													<label id="plan-not-valid" class="error"></label>
												</div>
												
												<div class="form-group col-3">
													<label>Valid For (3W/4W/MT/All)</label>	
													<input readonly id="sub_valid_for" type="text" class="form-control" name="sub_valid_for">
												</div>
												<div class="form-group col-3">
													<label>Validity (Days)</label>
													<input readonly id="sub_expiry" type="text" class="form-control" name="sub_expiry"> 
												</div>
											</div>
										</div>
										<div>
										<label class="m-l-5" id="bank_label_name"></label>	
											<div>
												<div class="form-group col-3">
													<input id="order_amount" type="text" class="form-control" name="order_amount">
													<label>Amount</label>
												</div>
											</div>
										</div>
										<div id="bank_info_div" class="pay-info">
											
											<div id="cheque_div" class="hide">
												<div class="form-group col-3">
													<input id="op_payment_bank_cheque_name" type="text" class="form-control" name="op_payment_bank_cheque_name">
													<label>Name in Cheque.</label>
												</div>
												<div class="form-group col-3">
													<input id="op_payment_bank_cheque_no" type="text" class="form-control" name="op_payment_bank_cheque_no">
													<label>Cheque No.</label>
												</div>
												<div class="form-group col-3">
													<input id="op_payment_bank_account_no" type="text" class="form-control" name="op_payment_bank_account_no">
													<label>Account No.</label>
												</div>
												<div class="form-group col-3">
													<select id="op_bank_name" type="text" class="form-control select2" name="op_bank_name" data-placeholder="Select Bank Name">
														<option value="" >Select Bank</option>
														@if(!empty($banks))
															@foreach($banks as $bank)
															<option value="{{ $bank['id'] }}">{{ $bank['op_bank_name'] }}</option>                     
															@endforeach
														@endif
													</select>
													<label id="op_bank_name-error" class="error m-0" for="op_bank_name"></label>
													<label>Select Bank</label>
												</div>
												<div class="form-group col-3">
													<input type="text" name="op_payment_bank_branch" id="op_bank_branch" class="form-control">
													<!-- <select id="op_bank_branch" type="text" class="form-control" name="op_payment_bank_branch">
													<option value="" >Select Branch</option>
														<option  value="baner">Baner</option>
														<option  value="aundh">Aundh</option>
													</select> -->
													<label>Enter Branch</label>
												</div>
											</div>

											<div id="cash_div" class="hide">
												<div class="form-group col-3">
													<input id="order_receiver" type="text" class="form-control" name="order_receiver">
													<label>Receiver</label>
												</div>
												<div class="form-group col-3">
													<input id="order_payee" type="text" class="form-control" name="order_payee">
													<label>Payee</label>
												</div>
											</div>
											<div id="card_div" class="hide">
												<div class="form-group col-3">
													<input id="name_on_card" type="text" class="form-control" name="name_on_card">
													<label>Name on Card</label>
												</div>
												<div class="form-group col-3">
													<input id="card_issued_by" type="text" class="form-control" name="card_issued_by">
													<label>Card Issued By</label>
												</div>
												<div class="form-group col-3">
													<input id="card_no" type="text" class="form-control" name="card_no">
													<label>Card No. (Last 4 digits only)</label>
												</div>
											</div>
										</div>
										<div class="pay-info" id="tanx_info_div">
											<label id="lab-2"><b>Transaction Information</b></label>
											<div class="row">
												<div id="date_time_div" class="hide">
													<div id="Tras_id_div" class="hide">
														<div class="form-group col-3">
															<input id="Tras_id" type="text" class="form-control" name="Tras_id">
															<label>Transaction ID</label>
														</div>
													</div>
													<div class="form-group col-3">
														<input id="order_date" type="text" class="form-control date-picker" name="order_date">
														<label>Date</label>
													</div>
													<div id="time_div" class="hide">
														<div class="form-group col-3">
															<input id="order_time" type="text" class="form-control timepicker" name="order_time">
															<label>Time</label>
														</div>
													</div>
													<div class="form-group col-3">
														<input id="order_reason" type="text" class="form-control" name="order_reason">
														<label>Reason</label>
													</div>
													<!-- <div class="form-group hide col-3" id="payee_div">
														<input id="order_payee2" type="text" class="form-control" name="order_payee">
														<label>Payee</label>
													</div> -->
												</div>
												<div id="Is_cheque_crdited_div" class="hide">
													<input id="cheque_credit" type="checkbox" class="" name="is_cheque_credit" onchange="doalert(this)">
													<span>Payment credited</span>
												</div>
											</div>
											<div id="cheque_credit_info" class="row">
												<div class="form-group col-3">
													<input id="credit_id" type="text" class="form-control" name="credit_id">
													<label class="error"></label>
													<label>Credit ID</label>
												</div>
												<div class="form-group col-3">
													<input id="credit_date" name="credit_date" type="text" class="form-control date-picker">
													<label>Credit Date</label>
												</div>
												<div class="form-group col-3">
													<input id="credit_time" type="text" class="form-control timepicker" name="credit_time">
													<label>Time</label>
												</div>
												<!-- <div class="form-group">
													<input id="card_no" type="text" class="form-control" name="card_no">
													<label>Card No</label>
												</div> -->
											</div>
											<div id="rp_info" class="hide">
												<div class="form-group">
													<input id="rp_trans_id" type="text" class="form-control" name="rp_trans_id">
													<label class="error"></label>
													<label>RP Trans ID</label>
												</div>
												<div class="form-group">
													<input id="trans_date" name="trans_date" type="text" class="form-control date-picker">
													<label>Date</label>
												</div>
												<div class="form-group">
													<input id="trans_time" type="text" class="form-control" name="trans_time">
													<label>Time</label>
												</div>
												<!-- <div class="form-group">
													<input id="card_no" type="text" class="form-control" name="card_no">
													<label>Card No</label>
												</div> -->
											</div>
										</div>
										<div class="" >
										<!-- <div class="" id="tranx_id_div"> -->
											<!-- <div class="form-group">
												<button type="button" id="generate_trnx_id" class="btn btn-block btn-flat bg-navy" onclick="generateID()">Generate Trans ID</button>
											</div> -->
											<!-- <div class="form-group">
												<button type="butoon" class="btn btn-block btn-flat bg-navy hide" id="paymentclick">Pay</button>
												<input type="hidden" name="op_payment_response[]" id="op_payment_response">
											</div> -->

											<div class="check-sms col-1">
												<label>
													<input id="sms_check" type="checkbox" class="" name="sms_check" checked>
													<span>SMS</span>
												</label>
											</div>
											<div class="aprv-pay" id="save_button_1">
												<input type="submit" name="paymentInfo" value="Save" class="btn bg-navy btn-sm su submit-btn">
											</div>
											<div class="aprv-pay" id="save_button">
												<input type="submit" name="paymentInfo" value="Send For Approval" class="btn btn-success btn-sm submit-btn">
											</div>
											<!-- <div class="form-group text-center">
												<input id="trans_id" type="text" class="form-control" name="trans_id">
												<label>Trans ID No</label>
											</div> -->
											<!-- <div class="form-group text-center">
												<select id="trans_status" type="text" class="form-control" name="trans_status">
												<option value="">Select Transaction Status</option>
													<option value="pending">Pending</option>
													<option value="received">Received</option>
												</select>
												<label>Status</label>
											</div>									 -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="aprv-pay" id="razor_pay_div">
						<!-- <div class="panel panel-default">
							<div class="panel-heading">Pay With Razorpay</div>
							<div class="panel-body text-center">
								<form action="{!!route('payments.store')!!}" method="POST" >
									<script src="https://checkout.razorpay.com/v1/checkout.js"
										data-key="{{ Config::get('custom_config_file.razor_key') }}"
										data-amount="50000"
										data-buttontext="Pay"
										data-name="Pay"
										data-description="Order Value"
										data-image="{{ asset('images/gogotrux-logo.png')}}"
										data-prefill.name="name"
										data-prefill.email="email"
										data-theme.color="#ff7529">
									</script>
									<input type="hidden" name="_token" value="{!!csrf_token()!!}">
								</form>
							</div>
						</div> -->
					</div>
				</div>
				<div class="table-responsive">
					<table id="payments" class="table pay-list table-bordered table-striped {{ count($payments) > 0 ? 'datatable' : '' }}" data-page-length="10">
						<thead>
							<tr>
								<th class="pay-col text-center">#</th>
								<th class="pay-col-1">Unique ID</th>
								<th class="pay-col-2">Payment Purpose</th>
								<th class="pay-col-2">Payment Mode</th>
								<th class="pay-col-2">Payment Amount</th>
								<th class="pay-col-3">Payment Date & Time</th>
								<th class="pay-col-4">Status</th>
								<th class="pay-col-5">Actions</th>
								<th class="pay-col-1">Approval</th>
								<!-- <th class="pay-col-2">Refund</th> -->
								<th class="pay-col-1">Print</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($payments))
								@foreach ($payments as $payment)
								<tr>
									<td class="pay-col">#</td>
									<td class="pay-col-1">{{ $payment['op_uid'] ? $payment['op_uid'] : '-' }}</td>
									<td class="pay-col-2">{{ $payment['op_order_payment_purpose'] ? $payment['op_order_payment_purpose'] : '-' }}</td>
									<td class="pay-col-2">{{ $payment['op_order_mode'] ? $payment['op_order_mode'] : '-' }}</td>
									<td class="pay-col-2">{{ $payment['op_order_amount'] ? $payment['op_order_amount'] : '-' }}</td>
									<td class="pay-col-3">{{ $payment['created_at'] ? \Carbon\Carbon::parse($payment['created_at'])->format('d-m-Y H:i a') : '-' }} </td>
									<td class="pay-col-4">{{ $payment['op_order_status'] ? $payment['op_order_status'] : '-' }}</td>
									<td class="pay-col-5">
									<a href="{{ route('payment-view', ['pay_id' => encrypt($payment['op_order_id'])]) }}"><button class="btn btn-xs btn btn-primary" type="button" data-toggle="tooltip" title="View!"><i class="fa fa-eye"></i></button></a>
									@if($payment['op_order_status'] == 'approved')
										<button class="btn btn-xs btn-success" type="button" data-toggle="tooltip" title="Approved!"><i class="fa fa-check"></i></button>
									@elseif($payment['op_order_status'] == 'received' || $payment['op_order_status'] == 'Success')
										<button class="btn btn-xs bg-orange" type="button" onclick="sendForApproval('{{ $payment['op_order_id'] }}')"><i class="fa fa-send-o" data-toggle="tooltip" title="Send for Approval"></i></button>
										<button class="btn btn-xs btn-danger" type="button" onclick="delete_payment('{{ $payment['op_order_id'] }}')"><i class="fa fa-trash" data-toggle="tooltip" title="Delete!"></i></button>
										@can('payment_edit')
										<a href="{{ route('payments.edit',[ encrypt($payment['op_order_id']) ]) }}"><button class="btn btn-xs bg-olive"><i class="fa fa-edit"></i></button></a>
										@endcan
									@elseif($payment['op_order_status'] == 'pending' || $payment['op_order_status'] == 'Pending')
										<button class="btn btn-xs btn-received" type="button" onclick="mark_as_receive_payment('{{ $payment['op_order_id'] }}')"><i class="fa fa-arrow-left" data-toggle="tooltip" title="Mark As Received!"></i></button>
									@elseif($payment['op_order_status'] == 'waiting_for_approval')
										<button class="btn btn-xs bg-purple" type="button" data-toggle="tooltip" title="Waiting For Approval!"><i class="fa fa-clock-o"></i></button>
									@else
										<button class="btn btn-xs btn-warning" type="button" data-toggle="tooltip" title="Falied!"><i class="fa fa-warning"></i></button>
									@endif
									</td>
									<td>
									@if($payment['op_order_status'] == 'waiting_for_approval' && $payment['op_order_payment_is_approved'] == 0)
									@can('payment_approve')
									<a href="#" onclick="verifyByAdmin('{{ $payment['op_order_id'] }}')" data-toggle="tooltip" data-placement="top" title="click to approve payment">
										<i class="fa fa-share-square-o fa-aprv" aria-hidden="true"></i>
									</a>
									@endcan
									@elseif($payment['op_order_status'] == 'approved')
									<a href="#" data-toggle="tooltip" data-placement="top" title="payment approved">
										<i class="fa fa-check-square-o aprv-fa" aria-hidden="true"></i>
									</a>
									@endif</td>
									<!-- <td>@if($payment['op_order_status'] == 'approved')
									<a href="{{ route('payment-view', ['pay_id' => encrypt($payment['op_order_id'])]) }}"><button class="btn btn-xs btn" type="button" data-toggle="tooltip" title="View!"><i class="fa fa-undo"></i></button></a>
									@endif</td> -->
									<td><a href="{{ route('pdfview',['download'=> encrypt($payment['op_order_id'])]) }}"><i class="fa fa-print"></i></a></td>
									<td></td>
								</tr>
								@endforeach
							@else
								<td colspan="10">No Payments yet</td>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="view_logo">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">View Image</h4>
				</div>
				<div class="modal-body" >
					<div id="view_logo_img">
						
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">

	 $('#order_time').timepicker();

	$(document).on("keypress",".onlynumeric",function (e) 
	{
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) return false;
	});

	$("#remove-select2").on("click", function() {
		// $("#operator_id-input").select2("val", "");	
		$('#operator_id').val(null).trigger("change");
		$('#op_uid').val('');
		$('#op_id').val('');
		$('#op_position').val();
		$('#op_name').val('');
		$('#op_mobile_no').val('');
		$('#op_email').val('');
		$('#op_location').val();
		$('#op_credit_bal').val('');
		$('#op_debit_bal').val('');
	});

	// $(document).on("focus keypress",".select2-search__field",function (e) 
	// {
	$("#operator_id-input").on("change", function() {
		// var op_uid = $(".select2-search__field").val();
		var op_uid = this.value;
		if($.trim($(".select2-search__field").val()).length != 0){
			$.ajax({
				url :"{{ route('get-operator-list') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"op_uid": op_uid
				},
				success : function(data){
					var html = '';
					$('#operator_id').empty();
					var result = JSON.parse(data);
					if(!(result.length == 0)){
						// html += '<option value="">Select UID/Select Mobile No</option>';
						$.each(result, function (key, value)
						{
							html += '<option value="'+value.op_user_id+'">'+value.op_mobile_no +' ('+value.op_uid+') </option>';
						});
					}
					else{
						html += '<option value="">No Matches Found</option>';
					}
					$('#operator_id').append(html);
					initSelect();
				}
			});
		}
	});

	$('#op_pay_mode').on('change', function() {
		if(this.value == "Cash") {
			$('#cash').show();
			$('#cheque').hide();
		} else if(this.value == "Cheque"){
			$('#cash').hide();
			$('#cheque').show();
		}
		else
		{
			$('#cash').hide();
			$('#cheque').hide();
		}	
	});

	$(document).ready(function() {
		set_operator_details();
		changed_payment_instrument();
		check_info_div();

		$('#order_time').timepicker();

		$("#order_date").datepicker({
			changeMonth: true, 
			changeYear: true, 
			dateFormat: 'yy-mm-dd',
			minDate: 0, // 0 days offset = today
			maxDate: '',
			onSelect: function(dateText) {
				$sD = new Date(dateText);
				$("#order_date").datepicker('option', 'minDate', dateText);
			}
		});

		$("#trans_date").datepicker({
			changeMonth: true, 
			changeYear: true, 
			dateFormat: 'yy-mm-dd',
			minDate: 0, // 0 days offset = today
			maxDate: '',
			onSelect: function(dateText) {
				$sD = new Date(dateText);
				$("#trans_date").datepicker('option', 'minDate', dateText);
			}
		});

		$('#success-message').fadeIn('slow', function()
		{
			$('#success-message').delay(5000).fadeOut(); 
		});

		$("#order_date").datepicker('setDate', new Date());

		$('input[id="cheque_credit"]').click(function() {
			check_info_div();
		});

		$(document.body).on("click","#paymentclick",function(){
			var options = {
				"key": "{{ Config::get('custom_config_file.razor_key') }}",
				"amount": 2000, // Example: 2000 paise = INR 20
				"name": "GoGoTrux",
				"description": "GGT",
				"image": "{{ asset('images/gogotrux-logo.png')}}",// COMPANY LOGO
				"handler": function (response) {
					var html = '';
					if(response != 'null'){
						$('#trans_id').val(response.razorpay_payment_id);
						html += '<option value="received" selected>Received</option>';
						getFullResponse(response.razorpay_payment_id);
					}
					else{
						$('#trans_id').val('');
						html += '<option value="pending" selected>Pending</option>';
					}
					$('#trans_id').attr('readonly', 'readonly');
					// $('#trans_status').attr('readonly', 'readonly');
					// $('#trans_status').append(html);
					// AFTER TRANSACTION IS COMPLETE YOU WILL GET THE RESPONSE HERE.
				},
				"prefill": {
					"name": "", // pass customer name
					"email": '',// customer email
					"contact": '' //customer phone no.
				},
				"notes": {
					"address": "address" //customer address 
				},
				"theme": {
					"color": "#15b8f3" // screen color
				}
			};
			var propay = new Razorpay(options);
			propay.open();
		});

		$(document.body).on("change","#operator_id",function(){
			$('#subscription-not-avail').addClass('hide');
			if(this.value != ''){
				var type='by_id';
				getPaymentDetails(this.value, type);
			}
		});

		$(document.body).on("blur keypress","#op_uid",function(){
			$(':input','#fetch_results')
				.not(':button, :submit, :reset, :hidden')
				.val('')
				.removeAttr('checked')
				.removeAttr('selected');

			$('#subscription-not-avail').addClass('hide');
			
			var op_uid = $('#op_uid').val();
			if(op_uid != ''){
				getPaymentDetails(op_uid, type ='by_uid');
			}
		});

		//jquery custom methods
		$.validator.addMethod("is_valid_op", function(value, element) 
		{
			var op_pay_uid_status = $('#op_uid').val().trim();
			if(op_pay_uid_status.length == 0 || op_pay_uid_status.length < 7 || op_pay_uid_status.length > 7){
				return false;
			}
			else{
				return true;
			}
		});

		$.validator.addMethod("noSpace", function(value, element) { 
			return value.indexOf(" ") < 0 && value != ""; 
		});

		$.validator.addMethod("alpha", function(value, element)
		{
			return this.optional(element) || /^([\s\.\s\]?[a-zA-Z]+)+$/.test(value);
		});
		//end -methods

		$( "#paymentInfo" ).validate({
			rules: {
				order_payment_purpose: {
					required: true,
				},
				op_uid: {
					required: true,
				},
				order_amount: {
					required: true,
					number: true,
				},
				order_payment_instrument: {
					required: true,
				},
				sub_scheme_name: {
					required: {
						depends: function (){ 
							var order_payment_purpose = $("#payment_purpose").val();
							if(order_payment_purpose == 'subscription'){
								return true;
							}
							else{
								return false;
							}
						}
					},
				},
				sub_valid_for: {
					required: {
						depends: function (){ 
							var order_payment_purpose = $("#payment_purpose").val();
							if(order_payment_purpose == 'subscription'){
								return true;
							}
							else{
								return false;
							}
						}
					},
				},
				sub_expiry: {
					required: {
						depends: function (){ 
							var order_payment_purpose = $("#payment_purpose").val();
							if(order_payment_purpose == 'subscription'){
								return true;
							}
							else{
								return false;
							}
						}
					},
				},
				op_bank_name: {
					required: {
						depends: function (){ 
							var payment_instrument = $("#payment_instrument").val();
							if(payment_instrument == 'cheque'){
								return true;
							}
							else{
								return false;
							}
						}
					},
				},
				op_payment_bank_branch: {
					required: {
						depends: function (){ 
							var payment_instrument = $("#payment_instrument").val();
							if(payment_instrument == 'cheque'){
								return true;
							}
							else{
								return false;
							}
						}
					},
					alpha: true,
				},
				// op_payment_bank_branch: {
				// 	required: true,
				// },
				op_payment_bank_account_no: {
					required: true,
				},
				order_reason: {
					required: true,
					alpha: true,
				},
				name_on_card: {
					required: {
						depends: function (){ 
							var payment_instrument = $("#payment_instrument").val();
							if(payment_instrument == 'debit_card' || payment_instrument == 'credit_card'){
								return true;
							}
							else{
								return false;
							}
						}
					},
					alpha: true,
				},
				card_issued_by: {
					required: {
						depends: function (){ 
							var payment_instrument = $("#payment_instrument").val();
							if(payment_instrument == 'debit_card' || payment_instrument == 'credit_card'){
								return true;
							}
							else{
								return false;
							}
						}
					},
					alpha: true,
				},
				card_no: {
					required: {
						depends: function (){ 
							var payment_instrument = $("#payment_instrument").val();
							if(payment_instrument == 'debit_card' || payment_instrument == 'credit_card'){
								return true;
							}
							else{
								return false;
							}
						}
					},
					number: true,
					maxlength: 4,
					minlength: 4
				},
				order_payee: {
					required: {
						depends: function (){ 
							var payment_instrument = $("#payment_instrument").val();
							if(payment_instrument == 'cash'){
								return true;
							}
							else{
								return false;
							}
						}
					},
					alpha: true,
				},
				order_receiver: {
					required: {
						depends: function (){ 
							var payment_instrument = $("#payment_instrument").val();
							if(payment_instrument == 'cash'){
								return true;
							}
							else{
								return false;
							}
						}
					},
					alpha: true,
				},
				op_payment_bank_cheque_no: {
					required: {
						depends: function (){ 
							var payment_instrument = $("#payment_instrument").val();
							if(payment_instrument == 'cheque'){
								return true;
							}
							else{
								return false;
							}
						}
					},
				},
				op_payment_bank_cheque_name: {
					required: {
						depends: function (){ 
							var payment_instrument = $("#payment_instrument").val();
							if(payment_instrument == 'cheque'){
								return true;
							}
							else{
								return false;
							}
						}
					},
					alpha: true,
				},
				order_date: {
					required: true,
				},
				// trans_status: {
				// 	required: true,
				// },
				// cheque_img: {
				// 	required: {
				// 		depends: function (){ 
				// 			var payment_instrument = $("#payment_instrument").val();
				// 			if(payment_instrument == 'cheque'){
				// 				return true;
				// 			}
				// 			else{
				// 				return false;
				// 			}
				// 		}
				// 	},
				// },
			},
			messages: {
				order_payment_purpose: {
					required: "Please enter valid payment purpose",
				},
				op_uid: {
					required: "Please enter operator UID",
				},
				order_amount: {
					required: "Please enter valid payment amount",
					number:"Please enter valid amount",
				},
				order_payment_instrument: {
					required: "Please enter payment instrument",
				},
				sub_scheme_name: {
					required: "Please enter subscription scheme name",
				},
				sub_valid_for: {
					required: "Please enter subscription scheme valid for",
				},
				sub_expiry: {
					required: "Please enter subscription scheme validity",
				},
				op_bank_name: {
					required: "Please enter bank name",
				},
				// op_bank_branch: {
				// 	required: "Please enter branch name",
				// },
				op_payment_bank_branch: {
					required: "Please enter branch name",
					alpha: "Please enter valid branch name",
				},
				op_payment_bank_account_no: {
					required: "Please enter bank account no.",
				},
				order_receiver: {
					required: "Please enter receiver name",
					alpha: "Please enter valid name",
				},
				order_payee: {
					required: "Please enter payee name",
					alpha: "Please enter valid name",
				},
				op_payment_bank_cheque_no: {
					required: "Please enter cheque no.",
				},
				op_payment_bank_cheque_name: {
					required: "Please enter name in cheque.",
					alpha: "Please enter valid name in cheque",
				},
				order_date: {
					required: "Please enter order date",
				},
				// trans_status: {
				// 	required: "Please select transaction status",
				// },
				order_reason: {
					required: "Please enter order reason",
					alpha: "Please enter valid reason",
				},
				name_on_card: {
					required: "Please enter name on card",
					alpha: "Please enter valid name on card",
				},
				card_issued_by: {
					required: "Please enter card issued by",
					alpha: "Please enter valid name",
				},
				card_no: {
					required: "Please enter card no",
					number:"Please enter valid card no",
					maxlength:"Please enter maximum 4 digits",
					minlength:"Please enter minimum 4 digits",
				},
				// cheque_img: {
				// 	required: "Upload cheque Image",
				// },
			},
		});

		$('#sub_scheme_name').change(function () {
			var sub_id = $(this).val();
			getSubscriptionDetails(sub_id);
		});

		$('#payment_purpose').change(function () {
			var payment_purpose = $(this).val();
			if(payment_purpose == 'subscription'){
				$('#sub_details_div').removeClass('hide');
				$("#order_amount").attr("readonly",true);
			}
			else{
				$('#sub_details_div').addClass('hide');
				$("#order_amount").attr("readonly",false);
			}
		});
	});
</script>

<script>
	function initSelect(){
		$("#select").select2({});
	}
	
	function check_info_div(){
		if ($("#cheque_credit").is(':checked')){
			$('#cheque_credit_info').removeClass('hide');
		}
		else{
			$('#cheque_credit_info').addClass('hide');
		}
	}

	function set_operator_details(){
		var html7 = '';var result = '{{ $op_details }}';
        var res = JSON.parse(result.replace(/&quot;/g,'"'));
        if(!(res.length == 0)){
            html7 += '<option value="">Select UID/Select Mobile No</option>';
            $.each(res, function (key, value)
            {
                html7 += '<option value="'+value.op_user_id+'">'+value.op_mobile_no +' ('+value.op_uid+') </option>';
            });
       }
       $('#operator_id').append(html7);
       initSelect();
	}

	function generateID(){
		$.ajax({
			url :"{{ route('getTransactionID') }}",
			method:"GET",
			success : function(data)
			{
				if(!(data == 'null')){
					$('#paymentInfo input select').attr('readonly', 'readonly');
					$('#trans_id').val(data);
				}
				else{
					//details not available or wrong id
				}
			}
		});
	}

	function preview_Image() 
	{
		$('#view_logo_img').html('');
		var output = document.getElementById("cheque_img");
		var total_file = document.getElementById("cheque_img").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#view_cheque_images').show()
			$('#view_logo_img').append("<img src='"+output.src+"' class='veh-image'>");
		}else{
			$('#view_cheque_images').show()
			$('#view_logo_img').html('');
		}
	}

	function changed_payment_instrument(){
		var payment_instrument = $('#payment_instrument').val();
		var html = '';
		$('#bank_label_name').empty();
		if(payment_instrument == 'cash'){
			html += '<b>Account Information</b>';
			$('#cheque_div').addClass('hide');
			$('#card_div').addClass('hide');
			$('#time_div').removeClass('hide');
			$('#date_time_div').removeClass('hide');
			$('#cash_div').removeClass('hide');
			$('#chequeImg').addClass('hide');
			$('#rp_info').addClass('hide');
			$('#paymentclick').addClass('hide');
			$('#generate_trnx_id').removeClass('hide');
			$('#bank_info_div').removeClass('hide');
			$('#tanx_info_div').removeClass('hide');
			$('#tranx_id_div').removeClass('hide');
			// $('#pay_div').addClass('hide');
			$('#payee_div').addClass('hide');
			$('#Is_cheque_crdited_div').addClass('hide');			
			$('#Tras_id_div').addClass('hide');
			$('#lab-2').removeClass('hide');
		}else if(payment_instrument == 'cheque'){
			html += '<b>Bank Information</b>';
			$('#cheque_div').removeClass('hide');
			$('#date_time_div').removeClass('hide');
			$('#cash_div').addClass('hide');
			$('#time_div').addClass('hide');
			$('#card_div').addClass('hide');
			$('#rp_info').addClass('hide');
			$('#chequeImg').removeClass('hide');
			$('#rp_info').addClass('hide');
			$('#paymentclick').addClass('hide');
			$('#bank_info_div').removeClass('hide');
			$('#tanx_info_div').removeClass('hide');
			$('#generate_trnx_id').removeClass('hide');
			$('#tranx_id_div').addClass('hide');
			// $('#pay_div').removeClass('hide');
			$('#payee_div').addClass('hide');
			$('#Is_cheque_crdited_div').removeClass('hide');
			$('#Tras_id_div').addClass('hide');
			$('#lab-2').removeClass('hide');
			// $('#pay_div').removeClass('dt-save');
		}else if(payment_instrument == 'credit_card' || payment_instrument == 'debit_card'){
			$('#cheque_div').addClass('hide');
			$('#date_time_div').removeClass('hide');
			$('#card_div').removeClass('hide');
			$('#time_div').removeClass('hide');
			$('#cash_div').addClass('hide');
			$('#chequeImg').addClass('hide');
			$('#rp_info').addClass('hide');
			$('#paymentclick').addClass('hide');
			$('#generate_trnx_id').removeClass('hide');
			$('#bank_info_div').removeClass('hide');
			$('#tanx_info_div').removeClass('hide');
			$('#tranx_id_div').removeClass('hide');
			$('#lab-2').removeClass('hide');
			// $('#pay_div').addClass('hide');
			$('#payee_div').addClass('hide');
			$('#Is_cheque_crdited_div').addClass('hide');
			$('#Tras_id_div').addClass('hide');
			html += '<b>Bank Information</b>';
		}
		else{
			$('#paymentclick').removeClass('hide');
			$('#generate_trnx_id').addClass('hide');
			$('#bank_info_div').addClass('hide');
			$('#date_time_div').removeClass('hide');
			$('#time_div').removeClass('hide');
			$('#tanx_info_div').removeClass('hide');
			$('#tranx_id_div').addClass('hide');
			// $('#pay_div').removeClass('hide');
			$('#payee_div').removeClass('hide');
						// $('#tranx_id_div').addClass('hide');
			// $('#save_button').addClass('hide');
			$('#chequeImg').addClass('hide');
			$('#trans_id').val('');
			// $('#razor_pay_div').removeClass('hide');
			$('#Is_cheque_crdited_div').addClass('hide');
			$('#lab-2').addClass('hide');
			// $('#pay_div').addClass('dt-save');
			$('#Tras_id_div').removeClass('hide');
			html += '<b>Tansaction Information</b>';
		}
		$('#bank_label_name').append(html);
	}

	function delete_payment(id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want to delete this payment!<br><span style='color:#d33;'>(This will permanently delete)</span>",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('delete-payment') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"payment_id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Deleted!", text: "Payment has been deleted.", type: result.status}).then(function()
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

	function mark_as_receive_payment(id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want to mark this payment as received!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Received'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('mark-as-received-payment') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"payment_id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Received!", text: result.msg, type: result.status}).then(function()
						{ 
							location.reload();
						});
					}
					else{
						swal({title: "Oops!", text: result.msg, type: result.status}).then(function()
						{
							location.reload();
						});
					}
				}
			});
		})
	}

	function printPage(){
		window.print();
	}

	function getifsccode()
	{
		var op_order_cheque_bank=$("#op_order_cheque_bank").val();

		$.ajax({
			url :"{{ route('getifsccodedb') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"op_order_cheque_bank": op_order_cheque_bank
			},
			success : function(data)
			{
				$("#op_order_cheque_ifsc").attr("value",data);
				$("#op_order_cheque_ifsc").attr("readonly",true);
			}
		});
	}

	function getFullResponse(payment_id)
	{
		$.ajax({
			url :"{{ route('getFullResponse') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"payment_id": payment_id
			},
			success : function(data)
			{
				$('#op_payment_response').val(data)
			}
		});
	}

	function getSubscriptionDetails(sub_id){
		$.ajax({
			url :"{{ route('getPlanDetails') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"sub_id": sub_id
			},
			success : function(data)
			{
				if(!(data == 'null')){
					var result = JSON.parse(data);
					$('#sub_expiry').val(result.subscription_validity_days);
					if(result.subscription_veh_wheel_type == 3){
						$('#sub_valid_for').val('3W');
					}
					else if(result.subscription_veh_wheel_type == 4){
						$('#sub_valid_for').val('4W');
					}
					else if(result.subscription_veh_wheel_type == 1)
					{
						$('#sub_valid_for').val('All');
					}
					else{
						$('#sub_valid_for').val('MT');
					}
					$('#order_amount').val(result.subscription_amount);
				}
				else{
					//details not available or wrong id
				}
			}
		});
	}

	function sendForApproval(order_id){
		swal({
			title: 'Are you sure?',
			text: "You want to send it for approval!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Send'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('send-for-approval-payment') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"payment_id": order_id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Send!", text: "Payment has been successfully send for approval.", type: result.status}).then(function()
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

	function doalert(checkboxElem) {
		if (checkboxElem.checked) {
			$('#tranx_id_div').removeClass('hide');
			// $('#pay_div').addClass('hide');
			$( "#credit_id" ).rules( "add", {
				required: true,
				messages: {
					required: "Please enter credit ID",
				}
			});
			$( "#credit_date" ).rules( "add", {
				required: true,
				messages: {
					required: "Please enter credit date",
				}
			});

			$( "#credit_time" ).rules( "add", {
				required: true,
				messages: {
					required: "Please enter credit time",
				}
			});
		} else {
			$('#tranx_id_div').addClass('hide');
			// $('#pay_div').removeClass('hide');
			$( "#credit_id" ).rules( "remove" );
			$( "#credit_date" ).rules( "remove" );
			$( "#credit_time" ).rules( "remove" );
		}
	}

	function verifyByAdmin(id)
	{
		// console.log(id);
		swal({
			title: 'Are you sure you want to approve?',
			text: "It will approve payment",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, approve it!'
		}).then(function() 
		{
			$.ajax({
				url :"approve/payment/"+id,
				method:"GET",
				success : function(data)
				{
					var result = JSON.parse(data);
					console.log(result);
					if(result.status == 'success'){
						swal({title: "Great!", text: result.msg, type: "success"}).then(function()
						{ 
							window.location.href = "{{ url('/payments') }}"
						});
					}
					else{
						swal({title: "Oops!", text: result.msg, type: result.status}).then(function()
						{
							location.reload(); 
						});
					}
				}
			});
		})
	}

	function is_plan_valid(){
		$('#plan-not-valid').empty();
		var plan_id = $('#sub_scheme_name').val();
		var op_id = $('#op_uid').val();
		$.ajax({
			url :"{{ route('is-plan-valid') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"subscription_id": plan_id,
				"oprator_id": op_id,
			},
			success : function(data){
				var result = JSON.parse(data);
				var html = '';
				console.log(result);
				if(result.status == "failed"){
					$('#plan-not-valid').append(result.message);
					$('.submit-btn').attr('disabled','disabled');
					$('.submit-btn').attr('disabled','disabled');
				}else{
					$('#plan-not-valid').empty();
					$('.submit-btn').removeAttr('disabled');
					$('.submit-btn').removeAttr('disabled');
				}
				
			}
		});
	}

	function getPaymentDetails(op_uid, type){
		$.ajax({
			url :"{{ route('get-operator-payment-details') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"op_uid": op_uid,
				"type": type,
			},
			success : function(data){
				var result = JSON.parse(data);
				var html = '';
				// console.log(result);
				if(!(result == null)){
					$('#op_uid').val(result.op_uid);
					$('#op_id').val(result.op_user_id);
					$('#op_position').val();
					$('#op_name').val(result.op_first_name);
					$('#op_mobile_no').val(result.op_mobile_no);
					$('#op_email').val(result.op_email);
					$('#op_location').val();
					$('#op_credit_bal').val(result.credit_balance);
					$('#op_debit_bal').val(result.debit_balance);
					if(result.subscription.length == 0){
						$('#subscription-not-avail').removeClass('hide');
						$('#op_subscription').val('');
						$('#op_validity').val('');
						$('#op_status').val('');
					}
					else{
						$('#subscription-not-avail').addClass('hide');
						$('#op_subscription').val(result.subscription.plan_name);
						$('#op_validity').val(result.subscription.plan_validity);
						$('#op_status').val(result.subscription.plan_status);
					}

					if(result.avail_sub_plans.length != 0){
						$('#sub_scheme_name').empty();
						html += '<option value="">Select Subscription Scheme</option>';
						$.each(result.avail_sub_plans, function(key, value) {
							html += '<option value="'+value['subscription_id']+'">'+value['subscription_type_name']+'</option>';
						});
					}
					else{
						html += '<option value="">No Subscription Scheme Available</option>';
					}
					$('#sub_scheme_name').html(html);
				}
				else{
					$('#subscription-not-avail').removeClass('hide');
					alert("No information available! Please choose valid operator.")
				}
			}
		});
	}
</script>
@endsection


@extends('layouts.app')
<!-- Content Header (Page header) -->
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
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		</div>
	@else
		<!-- action="{{ route('deposite/store') }}" -->
		<div class="panel-body p-0">
			<div class="view-op">
				<div class="row">
					<div class="col-sm-12 form-group section-title">Receive Payments</div>
					<div class="section">						
						<div class="row">
							<div class="pay-box">
								<table id="" class="table pay">
									<thead>
										<tr>
											<th class="text-center"><input type="checkbox" id="select-all" /></th>
											<th>Unique ID</th>
											<th>Position</th>
											<th>Name</th>
											<th>Mobile No.</th>
											<th>Location</th>
											<th>Credit Balance</th>
											<th>Debit Balance</th>
											<th>Subscription</th>
											<th>Validity</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<tr>
										  	<td><input type="checkbox" id="select-all" /></td>
										  	<td><input id="" type="text" class="form-control"></td>
										  	<td><input id="" type="text" class="form-control"></td>
										  	<td><input id="" type="text" class="form-control"></td>
										  	<td><input id="" type="text" class="form-control"></td>
										  	<td><input id="" type="text" class="form-control"></td>
										  	<td><input id="" type="text" class="form-control"></td>
											<td><input id="" type="text" class="form-control"></td>
											<td><input id="" type="text" class="form-control"></td>
											<td><input id="" type="text" class="form-control"></td>
											<td><input id="" type="text" class="form-control"></td>
										</tr>
									</tbody>
								</table>
								<div class="">
									<button class="btn btn-xs btn-default"><i class="fa fa-plus"></i></button>
								</div>
							</div>
						</div>
						<div class="row">
							<form>
								<div class="pay-wrap">
									<div class="first">
										<div class="row">
											<div class="pay-col col-1 text-center">
												<label><b>Amount<br>(Rs)</b></label>
												<div class="form-group">
													<input id="" type="text" class="form-control">
												</div>
											</div>
											<div class="pay-col col-2 text-center">
												<label><b>Payment<br>Purpose</b></label>
												<div class="form-group">
													<select id="" type="text" class="form-control">
														<option  value="">Select Payment Purpose</option>
														<option value="0">Subscription</option>
														<option value="1">Registraton</option>
														<option value="2">Trip Payments</option>
														<option value="3">Cash Trip Deposit</option>
														<option value="4">Penalty</option>
														<option value="5">Adjustments</option>
														<option value="6">Balance</option>
															<option value="7">Dispute</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="second">
										<div class="row divider">
											<div class="pay-col col-3 text-center">
												<label><b>Subscription<br> Scheme</b></label>
												<div class="form-group">
													<select id="" type="text" class="form-control">
														<option  value="">Select Subscription Scheme</option>
													</select>
												</div>
											</div>
											<div class="pay-col col-1 text-center">
												<label><b>Valid For<br> 3W/4W/MT</b></label>
												<div class="form-group">
													<input id="" type="text" class="form-control">
												</div>
											</div>
											<div class="pay-col col-1 text-center">
												<label><b>Validity<br> &nbsp;</b></label>
												<div class="form-group">
													<input id="" type="text" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="pay-col col-3 text-center">
												<label><b>Payment <br>Instrument</b></label>
												<div class="form-group">
													<select id="" type="text" class="form-control">
														<option  value="">Select Payment Instrument</option>
														<option value="0">Cash</option>
														<option value="1">Credit Card</option>
														<option value="2">Debit Card</option>
														<option value="3">Cheque</option>
														<option value="4">IMPS</option>
														<option value="5">BHIM</option>
														<option value="6">NEFT</option>
														<option value="7">Wallet</option>
													</select>
												</div>
											</div>
											<div class="pay-col col-3">
												<label>&nbsp;<br>&nbsp;</label>
												<div class="form-group">
													<select id="" type="text" class="form-control">
														<option  value=""></option>
													</select>
													<label>Select Bank</label>
												</div>
												<div class="form-group">
													<select id="" type="text" class="form-control">
														<option  value=""></option>
													</select>
													<label>Select Branch</label>
												</div>
												<div class="form-group">
													<input id="" type="text" class="form-control">
													<label>Account No.</label>
												</div>
												<div class="form-group">
													<input id="" type="text" class="form-control">
													<label>Cheque No.</label>
												</div>
												<div class="form-group">
													<input id="" type="text" class="form-control">
													<label>Date</label>
												</div>
											</div>
											<div class="pay-col col-3">
												<label>&nbsp;<br>&nbsp;</label>
												<div class="form-group">
													<input id="" type="text" class="form-control">
													<label>RP Trans ID</label>
												</div>
												<div class="form-group">
													<input id="" type="text" class="form-control">
													<label>Date</label>
												</div>
												<div class="form-group">
													<input id="" type="text" class="form-control">
													<label>Time</label>
												</div>
												<div class="form-group">
													<input id="" type="text" class="form-control">
													<label>Card No</label>
												</div>
												<div class="form-group">
													<input id="" type="text" class="form-control" placeholder="Credited/Received">
													<label>Status</label>
												</div>
											</div>
											<div class="pay-col col-3">
												<label>&nbsp;<br>&nbsp;</label>
												<div class="form-group">
													<button class="btn btn-flat bg-navy">Generate Trans ID</button>
												</div>
												<div class="check-sms">
													<input id="" type="checkbox" class="">
													<span>SMS</span>
												</div>
												<div class="form-group text-center">
													<input id="" type="text" class="form-control">
													<label>Trans ID No</label>
												</div>										
											</div>
											<div class="pay-col col-1 text-center">
												<label><b>Upload Image</b></label>
												<div class="form-group" id="upload_button">
													<label>
														<input id="" type="file" class="custom-file-input"/>
														<span class="fa fa-camera"></span>
													</label>
												</div>
												<div id="logo_image_div" style="">
													<label for="view_veh_images" class="control-label">{{ __('View') }}</label><br>
													<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_logo" ></i>
												</div>
												<div class="aprv-pay">
													<label><b>Approve</b></label>										
													<a class="next-arrow">
														<div id="arrow-wrapper">
												    		<div id="arrow-stem"></div>
												    		<div id="arrow-head"></div>
												  		</div>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
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
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<script type="text/javascript">

	$(document).on("keypress",".onlynumeric",function (e) 
	{
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) return false;
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
		$( "#depositForm" ).validate({
			rules: {
				op_pay_mobile_no: {
					required: true,
					is_valid_op: true,
					noSpace: true
				},
				op_deposit_type: {
					required: true,
				},
				op_pay_mode: {
					required: true,
				},
				op_pay_amount: {
					required: true,
				},
				op_pay_receipt_date: {
					required: true,
				},
				op_order_cheque_no: {
					required: {
						depends:function (){ 
							var pay_mode = $('#op_pay_mode').val();
							if(pay_mode == 'Cheque'){
								return true;
							}
							else{
								return false;
							}

						}
					},
				},
				op_order_cheque_bank: {
					required: {
						depends:function (){ 
							var pay_mode = $('#op_pay_mode').val();
							if(pay_mode == 'Cheque'){
								return true;
							}
							else{
								return false;
							}

						}
					},
				},
				op_order_cheque_ifsc: {
					required: {
						depends:function (){ 
							var pay_mode = $('#op_pay_mode').val();
							if(pay_mode == 'Cheque'){
								return true;
							}
							else{
								return false;
							}

						}
					},
				},
			},
			messages: {
				op_pay_mobile_no: {
					required: "Please enter registered mobile/name",
					is_valid_op: "Please enter registered mobile/name",
					noSpace: "No space please and don't leave it empty"
				},
				op_deposit_type: {
					required: "Please select deposit type",
				},
				op_pay_mode: {
					required: "Please select payment mode",
				},
				op_pay_amount: {
					required: "Please enter payment amount",
				},
				op_pay_receipt_date: {
					required: "Please select payment date",
				},
				op_order_cheque_no: {
					required: "Please enter cheque no",
				},
				op_order_cheque_bank: {
					required: "Please enter bank name",
				},
				op_order_cheque_ifsc: {
					required: "Please enter IFSC code",
				},
			},
			submitHandler: function(form) {
				if(form.valid){
					form.submit();
				}
			}
		});

		//jquery custom methods
		$.validator.addMethod("is_valid_op", function(value, element) 
		{
			var op_pay_mobile_no_status = $('#op_pay_mobile_no_status').val();
			if(op_pay_mobile_no_status == 0){
				return false;
			}
			else{
				return true;
			}
		});

		$.validator.addMethod("noSpace", function(value, element) { 
			return value.indexOf(" ") < 0 && value != ""; 
		});
		//end -methods

		
	});
</script>

<script>
	function checkMobileNo() 
	{
		var op_pay_mobile_no = $("#op_pay_mobile_no").val();
		{
			if($.trim($('#op_pay_mobile_no').val()).length != 0){
				$.ajax({
					url :"{{ route('get-operator-details') }}",
					method:"POST",
					data: {
					"_token": "{{ csrf_token() }}",
					"op_pay_mobile_no": op_pay_mobile_no
					},
					success : function(data){
						if(data.length != 0)
						{
							$('#op-details').removeClass('hide');
							$('#op-details0').removeClass('hide');
							$('#op-details-back').addClass('hide');
							$("#op_pay_mobile_no").attr("readonly", true);
							$("#op_pay_mode").attr("disabled", false);
							$("#op_pay_mobile_no_status").attr("value", 1);
							$("#op_user_id").attr("value", data['op_user_id']);
							$("#availble_deposit").html(data['op_deposit']);
							$("#op_first_name").attr("value", data['op_first_name']);
							$("#op_last_name").attr("value", data['op_last_name']);
							$("#op_email").attr("value", data['op_email']);
							$("#op_username").attr("value",data['op_first_name']);
							$("#op_email").attr("readonly", true);
							$("#op_last_name").attr("readonly", true);
							$("#op_first_name").attr("readonly", true);
							
							$("#mobilemessage").html("Registered.");
							$("#mobilemessage").addClass("text-green");
							$("#mobilemessage").removeClass("text-red");
						}
						else
						{
							// $("#mobilemessage").html("Sorry!!! You are not registered.Please register first.");
							$("#mobilemessage").addClass("text-red");
							$("#mobilemessage").removeClass("text-green");
						}
					}
				});
			}
		}
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
</script>
@endsection


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
	<h1 class="all-caps">Reports</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Reports</li>
		<li class="active">Customer/Partner</li>
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
			<form method="POST" id="generateReportForm" name="generate_report_form">
				<div class="row">
					<div class="col-sm-12 section-title m-b-10">
						<div class="col-md-4 p-l-0">
							<select id="report_type" type="text" class="form-control all-caps" name="select_report" autofocus onchange="changed_report_type()">
								<option value="customer">Customer Report</option>
								<option value="partner">Partner Report</option>
								<option value="subscription">Subscription Report</option>
								<option value="trip_revenue">Trip Revenue Report</option>
								<option value="customer_search">Customer Search</option>
							</select>
						</div>
					</div>
					<div class="section p-t-10">
						<div class="row">
							<div class="col-md-8">
								<div class="ledger_date">
									<div class="date">
									<label class="control-label">From</label>
										<input id="from_date" type="text" class="form-control date-picker" name="report_from_date" value="">
									<p class="help-block"></p>
									</div>
								</div>
								<div class="ledger_date text-right">
									<div class="date">
									<label class="control-label">To</label>
										<input id="to_date" type="text" class="form-control date-picker" name="report_to_date" value="">
									<p class="help-block"></p>
									</div>
								</div>
							</div>
							<div class="col-md-4 text-right">
								<button type="submit" class="btn btn_export" >Generate</button>
								<!-- <button type="button" class="btn btn_export">Export to Excel / PDF</button> -->
							</div>
						</div>
						<div id="r_customer">
							<div class="row">
								<div class="col-md-3 p-r-0">
									<table class="table report_table">
										<input type="hidden" id="report_input" name="report_input">
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="user_uid" type="checkbox" name="user_uid" value="user_uid">
													<label for="user_uid">Customer ID</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="user_first_name" type="checkbox" name="user_first_name" value="user_first_name,user_last_name">
													<label for="user_first_name">Customer Name</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="user_mobile_no" type="checkbox" name="user_mobile_no" value="user_mobile_no">
													<label for="user_mobile_no">Customer Phone</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="address_pin_code" type="checkbox" name="address_pin_code" value="address_pin_code">
													<label for="address_pin_code">Customer PIN Code</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust_created_at" type="checkbox" name="cust_created_at" value="ggt_user.created_at">
													<label for="cust_created_at">Created At</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust_verified" type="checkbox" name="cust_verified">
													<label for="cust_verified">Verified</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust_email" type="checkbox" name="cust_email" value="email">
													<label for="cust_email">Customer Email</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust_dob" type="checkbox" name="cust_dob" value="user_dob">
													<label for="cust_dob">Customer DOB</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust_city" type="checkbox" name="cust_city" value="address_city">
													<label for="cust_city">Customer City</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust_state" type="checkbox" name="cust_state" value="address_state">
													<label for="cust_state">Customer State</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust_deleted" type="checkbox" name="cust_deleted">
													<label for="cust_deleted">Deleted</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust_blocked" type="checkbox" name="cust_blocked">
													<label for="cust_blocked">Blocked</label>
												</div>
											</td>
										</tr>
									</table>		
								</div>
								<div class="col-md-9 p-r-0">
									<div class="table-responsive ledger" class="cust_report1" id="cust_report1">
										
									</div>
								</div>
							</div>
						</div>
						<div id="r_partner" class="hide">
							<div class="row">
								<div class="col-md-3 p-r-0">
									<table class="table report_table">
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_uid" type="checkbox" name="op_uid" value="op_uid">
													<label for="op_uid">UID</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="veh_make_model_type" type="checkbox" name="veh_make_model_type" value="veh_make_model_type">
													<label for="veh_make_model_type">Make</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="veh_model_name" type="checkbox" name="veh_model_name" value="veh_model_name">
													<label for="veh_model_name">Model</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="veh_registration_no" type="checkbox" name="veh_registration_no" value="veh_registration_no">
													<label for="veh_registration_no">Registration No.</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="veh_city" type="checkbox" name="veh_city" value="veh_city">
													<label for="veh_city">Base Station</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_first_name" type="checkbox" name="op_first_name" value="op_first_name,op_last_name">
													<label for="op_first_name">Partner Name</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_mobile_no" type="checkbox" name="op_mobile_no" value="op_mobile_no">
													<label for="op_mobile_no">Partner Phone</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_address_pin_code" type="checkbox" name="op_address_pin_code" value="op_address_pin_code">
													<label for="op_address_pin_code">Partner PIN Code</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="indv_partner" type="radio" name="op_type_id" value="1">
													<label for="indv_partner">Individual</label>
												</div>    
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="busi_partner" type="radio" name="op_type_id" value="2">
													<label for="busi_partner">Business</label>
												</div>    
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="both_partner" type="radio" name="op_type_id" value="both">
													<label for="both_partner">Both</label>
												</div>    
											</td>
											
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_address_city" type="checkbox" name="op_address_city" value="op_address_city">
													<label for="op_address_city">Address City</label>
												</div>    
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_address_state" type="checkbox" name="op_address_state" value="op_address_state">
													<label for="op_address_state">Address State</label>
												</div>    
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="partner_bank" type="checkbox" name="op_bank" value="op_bank_name,op_bank_ifsc,op_bank_account_number">
													<label for="partner_bank">Bank Details</label>
												</div>    
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_razoarpay_acc_id" type="checkbox" name="op_razoarpay_acc_id" value="op_razoarpay_acc_id">
													<label for="op_razoarpay_acc_id">RazorpayId</label>
												</div>    
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="created_at" type="checkbox" name="created_at" value="ggt_operator_users.created_at">
													<label for="created_at">Created At</label>
												</div>    
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="deleted_at" type="checkbox" name="deleted_at">
													<label for="deleted_at">Deleted At</label>
												</div>    
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_is_verified" type="radio" name="op_is_verified" value="1">
													<label for="op_is_verified">Verified</label>
												</div>    
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_is_not_verified" type="radio" name="op_is_verified" value="0">
													<label for="op_is_not_verified">Non-Verified</label>
												</div>    
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_is_blocked" type="checkbox" name="op_is_blocked">
													<label for="op_is_blocked">Blocked</label>
												</div>    
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_email" type="checkbox" name="op_email" value="op_email">
													<label for="op_email">Email</label>
												</div>    
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_android" type="checkbox" name="android_notification_token" value="android_notification_token">
													<label for="op_android">Android</label>
												</div>    
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="op_veh_location" type="checkbox" name="veh_last_location_updated_at" value="veh_last_location_updated_at">
													<label for="op_veh_location">Vehicle Last Location</label>
												</div>    
											</td>
										</tr>
									</table>		
								</div>
								<div class="col-md-9 p-r-0">
									<div class="table-responsive ledger" id="partner_report">
										
									</div>
								</div>
							</div>
						</div>
						<div id="r_subscription" class="hide">
							<div class="row">
								<div class="col-md-3 p-r-0">
									<table class="table report_table">
										<tr>
											<td>
												<div class="r_check">
													<input id="check1" type="checkbox" name="">
													<label for="check1">Partner ID</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check2" type="checkbox" name="">
													<label for="check2">Phone</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check3" type="checkbox" name="">
													<label for="check3">Name</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check4" type="checkbox" name="">
													<label for="check4">PIN</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check5" type="checkbox" name="">
													<label for="check5">Busin/Indv</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check6" type="checkbox" name="">
													<label for="check6">Veh Reg No</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check7" type="checkbox" name="">
													<label for="check7">Make</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check8" type="checkbox" name="">
													<label for="check8">Model</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check9" type="checkbox" name="">
													<label for="check9">Sub Type</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check10" type="checkbox" name="">
													<label for="check10">Sub Value</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check11" type="checkbox" name="">
													<label for="check11">Subs Start</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check12" type="checkbox" name="">
													<label for="check12">Subs Ends</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check13" type="checkbox" name="">
													<label for="check13">Digital/Cash/Cheque</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check14" type="checkbox" name="">
													<label for="check14">Payment ID</label>
												</div>
											</td>
										</tr>
									</table>		
								</div>
								<div class="col-md-9 p-r-0">
									<div class="table-responsive ledger">
										
									</div>
								</div>
							</div>
						</div>
						<div id="r_trip_revenue" class="hide">
							<div class="row">
								<div class="col-md-3 p-r-0">
									<table class="table report_table">
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="tripId" type="checkbox" name="tripId" value="trip_transaction_id">
													<label for="tripId">Trip ID</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="book_date" type="checkbox" name="book_date" value="book_date">
													<label for="book_date">Book Date Time</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust-name" type="checkbox" name="cust-name">
													<label for="cust-name">Customer Name</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cust-mobile" type="checkbox" name="cust-mobile">
													<label for="cust-mobile">Customer Mobile</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="partner-name" type="checkbox" name="op_first_name">
													<label for="partner-name">Partner Name</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="partner-mobile" type="checkbox" name="op_mobile_no">
													<label for="partner-mobile">Partner Mobile</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="digital-pay" type="radio" name="trip-pay" value="digital">
													<label for="digital-pay">Digital Pay</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="cash-pay" type="radio" name="trip-pay" value="cash">
													<label for="cash-pay">Cash Pay</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="both-pay" type="radio" name="trip-pay" value="both">
													<label for="cash-pay">Both</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="partner-charge" type="checkbox" name="base_amount" value="base_amount,op_adjustment">
													<label for="partner-charge">Partner Charge</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="trip-total" type="checkbox" name="actual_amount" value="actual_amount,user_adjustment">
													<label for="trip-total">Trip Total</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="ggt-factor" type="checkbox" name="ggt_factor" value="ggt_factor,ggt_adjustment">
													<label for="ggt-factor">GGT Factor</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="pickup-location" type="checkbox" name="start_address_line_1" value="start_address_line_1">
													<label for="pickup-location">Pickup</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="drop-location" type="checkbox" name="intermediate_address" value="intermediate_address">
													<label for="drop-location">Drop</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="weight" type="checkbox" name="weight" value="weight">
													<label for="weight">Weight</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="material_type" type="checkbox" name="material_type" value="material_type">
													<label for="material_type">Material Type</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="trip-success" type="radio" name="trip-status" value="success">
													<label for="trip-success">Trip Success</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="trip-disputed" type="radio" name="trip-status" value="disputed">
													<label for="trip-disputed">Trip Disputed</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="trip-cancelled" type="radio" name="trip-status" value="cancelled">
													<label for="check14">Trip Cancelled</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="trip-unpaid" type="radio" name="trip-status" value="unpaid">
													<label for="check16">Trip Unpaid</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="pay-id" type="checkbox" name="pay_order_id" value="pay_order_id">
													<label for="pay-id">Payment ID</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input class="cust_check" id="bill-details" type="checkbox" name="bill_details" value="bill_details">
													<label for="bill-details">Invoice Number</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input class="cust_check" id="enq-bid" type="checkbox" name="is_bid" value="is_bid">
													<label for="enq-bid">Enquiry/Bid</label>
												</div>
											</td>
										</tr>
									</table>		
								</div>
								<div class="col-md-9 p-r-0">
									<div class="table-responsive ledger" id="trip_report">
										
									</div>
								</div>
							</div>
						</div>
						<div id="r_customer_search" class="hide">
							<div class="row">
								<div class="col-md-12 p-r-0">
									<div class="table-responsive ledger" class="cust_search" id="cust_search">
										
									</div>
								</div>
							</div>
						</div>
						<div id="r_receive_payment" class="hide">
							<div class="row">
								<div class="col-md-3 p-r-0">
									<table class="table report_table">
										<tr>
											<td>
												<div class="r_check">
													<input id="check1" type="checkbox" name="">
													<label for="check1">Customer ID / Partner ID</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check2" type="checkbox" name="">
													<label for="check2">Phone</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check3" type="checkbox" name="">
													<label for="check3">Name</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check4" type="checkbox" name="">
													<label for="check4">Trip ID</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check5" type="checkbox" name="">
													<label for="check5">Enquiry/BID</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check6" type="checkbox" name="">
													<label for="check6">Trip Charge</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check7" type="checkbox" name="">
													<label for="check7">Loader Charge</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check8" type="checkbox" name="">
													<label for="check8">Cancel Charge</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check9" type="checkbox" name="">
													<label for="check9">Incidental Charge</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check10" type="checkbox" name="">
													<label for="check10">Dispute Charge</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check11" type="checkbox" name="">
													<label for="check11">Other charge</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check12" type="checkbox" name="">
													<label for="check12">Refund</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check13" type="checkbox" name="">
													<label for="check13">Incentive</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check14" type="checkbox" name="">
													<label for="check14">GST</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check15" type="checkbox" name="">
													<label for="check15">Total</label>
												</div>
											</td>
										</tr>
									</table>		
								</div>
								<div class="col-md-9 p-r-0">
									<div class="table-responsive ledger" id="trip_report">
										
									</div>
								</div>
							</div>
						</div>
						<div id="r_due_payment" class="hide">
							<div class="row">
								<div class="col-md-3 p-r-0">
									<table class="table report_table">
										<tr>
											<td>
												<div class="r_check">
													<input id="check1" type="checkbox" name="">
													<label for="check1">Customer ID / Partner ID</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check2" type="checkbox" name="">
													<label for="check2">Phone</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check3" type="checkbox" name="">
													<label for="check3">Name</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check4" type="checkbox" name="">
													<label for="check4">Trip ID</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check5" type="checkbox" name="">
													<label for="check5">Enquiry/BID</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check6" type="checkbox" name="">
													<label for="check6">Trip Charge</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check7" type="checkbox" name="">
													<label for="check7">Loader Charge</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check8" type="checkbox" name="">
													<label for="check8">Cancel Charge</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check9" type="checkbox" name="">
													<label for="check9">Incidental Charge</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check10" type="checkbox" name="">
													<label for="check10">Dispute Charge</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check11" type="checkbox" name="">
													<label for="check11">Other charge</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check12" type="checkbox" name="">
													<label for="check12">Refund</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check13" type="checkbox" name="">
													<label for="check13">Incentive</label>
												</div>
											</td>
											<td>
												<div class="r_check">
													<input id="check14" type="checkbox" name="">
													<label for="check14">GST</label>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="r_check">
													<input id="check15" type="checkbox" name="">
													<label for="check15">Total</label>
												</div>
											</td>
										</tr>
									</table>		
								</div>
								<div class="col-md-9 p-r-0">
									<div class="table-responsive ledger">
										<table id="report_6" class="table table-list" data-page-length="25">
											<thead>
												<tr>
													<th>Customer ID / Partner ID</th>
													<th>Phone</th>
													<th>Name</th>
													<th>Trip ID</th>
													<th>Enquiry/BID</th>
													<th>Trip Charge</th>
													<th>Loader Charge</th>
													<th>Cancel Charge</th>
													<th>Incidental Charge</th>
													<th>Dispute Charge</th>
													<th>Other Charge</th>
													<th>Refund</th>
													<th>Incentive</th>
													<th>GST</th>
													<th>Total</th>
												</tr>
											</thead>

											<tbody>
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="5"></td>
													<td>Total</td>
													<td>Total</td>
													<td>Total</td>
													<td>Total</td>
													<td>Total</td>
													<td>Total</td>
													<td>Total</td>
													<td>Total</td>
													<td>Total</td>
													<td>Total</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('javascript')
<script>
	$('.datepicker').datepicker({
		dateFormat: 'dd/mm/y',
		todayBtn: "linked",
		clearBtn: true,
		changeMonth: true,
    	changeYear: true
	});
	$(function () {
		$('#report_1').DataTable({
			'paging'      : true,
			'lengthChange': false,
			'searching'   : true,
			'ordering'    : true,
			'info'        : true,
			'autoWidth'   : true
		})
		$('#report_2').DataTable({'lengthChange': false})
		$('#report_3').DataTable({'lengthChange': false})
		$('#report_4').DataTable({'lengthChange': false})
		$('#report_5').DataTable({'lengthChange': false})
		$('#report_6').DataTable({'lengthChange': false})

	})
	$(document).ready(function() {
		changed_report_type();
	});
</script>
<script>
	function changed_report_type(){
		$('.ledger').empty();
		$('input[type=checkbox]').prop('checked',false);
		$('input[type=radio]').prop('checked',false);
		var report = $('#report_type').val();
		if(report == 'customer'){
			$('#r_customer').removeClass('hide');
			$('#r_partner').addClass('hide');	
			$('#r_subscription').addClass('hide');
			$('#r_trip_revenue').addClass('hide');
			$('#r_receive_payment').addClass('hide');
			$('#r_due_payment').addClass('hide');
		}else if(report == 'partner'){
			$('#r_customer').addClass('hide');
			$('#r_partner').removeClass('hide');	
			$('#r_subscription').addClass('hide');
			$('#r_trip_revenue').addClass('hide');
			$('#r_receive_payment').addClass('hide');
			$('#r_due_payment').addClass('hide');
		}else if(report == 'subscription'){
			$('#r_subscription').removeClass('hide');
			$('#r_customer').addClass('hide');
			$('#r_partner').addClass('hide');
			$('#r_trip_revenue').addClass('hide');
			$('#r_receive_payment').addClass('hide');
			$('#r_due_payment').addClass('hide');	
		}else if(report == 'trip_revenue'){
			$('#r_trip_revenue').removeClass('hide');
			$('#r_customer').addClass('hide');
			$('#r_partner').addClass('hide');
			$('#r_subscription').addClass('hide');
			$('#r_receive_payment').addClass('hide');
			$('#r_due_payment').addClass('hide');	
		}else if(report == 'receive_payment'){
			$('#r_receive_payment').removeClass('hide');
			$('#r_customer').addClass('hide');
			$('#r_partner').addClass('hide');
			$('#r_subscription').addClass('hide');
			$('#r_trip_revenue').addClass('hide');
			$('#r_due_payment').addClass('hide');		
		}else if(report == 'customer_search'){
			$('#r_customer_search').removeClass('hide');
			$('#r_due_payment').addClass('hide');
			$('#r_customer').addClass('hide');
			$('#r_partner').addClass('hide');
			$('#r_subscription').addClass('hide');
			$('#r_trip_revenue').addClass('hide');
			$('#r_receive_payment').addClass('hide');	
		}else {
			$('#r_due_payment').removeClass('hide');
			$('#r_customer').addClass('hide');
			$('#r_partner').addClass('hide');
			$('#r_subscription').addClass('hide');
			$('#r_trip_revenue').addClass('hide');
			$('#r_receive_payment').addClass('hide');	
		}
	}
</script>
<script type="text/javascript">
	$("#generateReportForm").validate({
		rules: {
			report_from_date: {
				required: true,
			},
			report_to_date: {
				required: true,
			},	
		},  
		messages: {
			report_from_date : {
				required:"Please select from date",
			},
			report_to_date : {
				required:"Please select to date",
			},
		},
		submitHandler: function(form) {
			var thisVal = [];
			var cust_input;
			$('input.cust_check:checkbox:checked').each(function () {
    			cust_input = $(this).val();
    			if(cust_input != 'on'){
    				thisVal.push(cust_input);
    			}
			});
			$("#report_input").val(thisVal);
			
			$.ajax({
	            headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
	            url :"{{ route('generate-report') }}",
	            method:"POST",
	            data: $('#generateReportForm').serialize(),
	            success : function(data)
	            {
	            	if(data.response.status == 'success'){
	            		if(data.response.type == 'customer'){
                            $('#cust_report1').html(data.response.custreport);
                        }else if(data.response.type == 'partner'){
                            $('#partner_report').html(data.response.custreport);
                        }else if(data.response.type == 'trip'){
                        	$('#trip_report').html(data.response.custreport);
                        }else if(data.response.type == 'customer_search'){
                        	$('#cust_search').html(data.response.custreport);
                        }
                        $('#'+data.response.tid).DataTable({
            				dom: 'Blfrtip',
                            lengthMenu: [10, 25, 50,100],
                            buttons: [
                                'excel'
                            ],
            			});
	            	}else{
	            		swal({
							title: 'No Data Match With Your Request.',
							type: 'warning',
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'try again'
						})
	            	}
	            }
	        })
		}
	});
</script>
@endsection


@extends('layouts.app')
@section('content-header')
	<h1>Payments</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Payments</li>
		<li class="active">Receive Payment</li>
	</ol>
@endsection
@section('content')
	<div class="panel-body p-0">
		<div class="view-op">
			<div class="row">
				<div class="section">
					<div class="form-group pdf-title">Payment Receipt</div>
					<div class="detail-info">
						<h5>Operator Details</h5>
						<div class="row">
							<div class="col-sm-3 form-group">
								<label for="op_mobile_no" class="control-label">{{ __('Operator Mobile Number: ') }}</label> 
								{{ $payment_details['op_order_mobile_no'] ? $payment_details['op_order_mobile_no'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_uid" class="control-label">{{ __('Operator UID: ') }}</label> 
				 				{{ $payment_details['op_uid'] ? $payment_details['op_uid'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_full_name" class="control-label">{{ __('Operator Name: ') }}</label> 
				 				{{ $payment_details['op_name'] ? $payment_details['op_name'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_payment_status" class="control-label">{{ __('Payment Status: ') }}</label> 
								@if($payment_details['op_order_status'] == 'waiting_for_approval')
				 				waiting for approval
				 				@else
				 				{{ $payment_details['op_order_status'] ? $payment_details['op_order_status'] : '-' }}
				 				@endif
							</div>
						</div>
						<h5>Payment Details</h5>
						<div class="row">
							<div class="col-sm-3 form-group">
								<label for="payment_trans_id" class="control-label">{{ __('Transaction Id: ') }}</label>
								{{ $payment_details['op_order_transaction_id'] ? $payment_details['op_order_transaction_id'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="payment_purpose" class="control-label">{{ __('Payment Purpose: ') }}</label> 
								{{ $payment_details['op_order_payment_purpose'] ? $payment_details['op_order_payment_purpose'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="payment_amount" class="control-label">{{ __('Payment Amount: ') }}</label> 
				 				{{ $payment_details['op_order_amount'] ? $payment_details['op_order_amount'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="payment_mode" class="control-label">{{ __('Payment Method: ') }}</label> 
				 				{{ $payment_details['op_order_mode'] ? $payment_details['op_order_mode'] : '-' }}
							</div>
						</div>
						@if($payment_details['op_order_payment_purpose'] == 'subscription')
							@if(!empty($payment_details['payment_p_details']))
								<div class="row">
								  	<div class="col-sm-3 form-group">
								  		<label for="sub_plan_name" class="control-label">{{ __('Sub Plan Name: ') }}</label>
										{{ $payment_details['payment_p_details']['sub_scheme_name'] ? $payment_details['payment_p_details']['sub_scheme_name'] : '-' }}
									</div>
									<div class="col-sm-3 form-group">
										<label for="sub_valid_for" class="control-label">{{ __('Sub valid for: ') }}</label>
										{{ $payment_details['payment_p_details']['sub_valid_for'] ? $payment_details['payment_p_details']['sub_valid_for']  : '0' }} Days
									</div>
									<div class="col-sm-3 form-group">
								  		<label for="sub_expiry_date" class="control-label">{{ __('Sub Expiry Date: ') }}</label>
								  		@if(isset($payment_details['payment_p_details']['sub_expiry']))
										{{ $payment_details['payment_p_details']['sub_expiry'] ? $payment_details['payment_p_details']['sub_expiry'] : '-' }}
										@else
										-
										@endif
									</div>
								</div>
								@else
									<div class="row">
									No Plan information available.
									</div>
								@endif
						@endif
						@if($payment_details['op_order_mode'] == 'cash')
							<div class="row">
							  	<div class="col-sm-3 form-group">
							  		<label for="payment_receiver" class="control-label">{{ __('Payment Receiver: ') }}</label>
									{{ $payment_details['order_receiver'] ? $payment_details['order_receiver'] : '-' }}
								</div>
								<div class="col-sm-3 form-group">
									<label for="payment_payee" class="control-label">{{ __('Payment Payee: ') }}</label>
									{{ $payment_details['order_payee'] ? $payment_details['order_payee'] : '-' }}
								</div>
								<div class="col-sm-3 form-group">
							  		<label for="payment_date" class="control-label">{{ __('Payment Date: ') }}</label>
									{{ $payment_details['op_order_date'] ? $payment_details['op_order_date'] : '-' }}
								</div>
								<div class="col-sm-3 form-group">
									<label for="payment_reason" class="control-label">{{ __('Payment Reason: ') }}</label>
									{{ $payment_details['order_reason'] ? $payment_details['order_reason'] : '-' }}
								</div>
							</div>
						@elseif($payment_details['op_order_mode'] == 'cheque')
							<div class="row">
								<div class="col-sm-3 form-group">
									<label for="" class="control-label"></label>
								</div>
								<div class="col-sm-3 form-group">
									<label for="" class="control-label"></label>
								</div>
							</div>
						@elseif($payment_details['op_order_mode'] == 'credit_card' || $payment_details['op_order_mode'] == 'credit_card')
							<div class="row">
								<div class="col-sm-6 form-group">
									<label for="" class="control-label"></label>
								</div>
								<div class="col-sm-6 form-group">
									<label for="" class="control-label"></label>
								</div>
							</div>
						@else
							<div class="row">
								<div class="col-sm-6 form-group">
									<label for="" class="control-label"></label>
								</div>
								<div class="col-sm-6 form-group">
								</div>
							</div>
						@endif
						<div class="row">
							<div class="form-group">
								<div class="btn-b-u">
									<a href="{{ url('/payments/operator') }}" class="btn btn-warning">Back</a>
								</div>
							</div>
						</div>
					</div>						
				</div>					
			</div>
		</div>
	</div>	
@endsection


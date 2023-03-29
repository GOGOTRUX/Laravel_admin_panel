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
						<h5>Customer Details</h5>
						<div class="row">
							<div class="col-sm-3 form-group">
								<label for="user_mobile_no" class="control-label">{{ __('Customer Mobile Number: ') }}</label> 
								{{ $payment_details['user_details'] ? $payment_details['user_details']['user_mobile_no'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="user_uid" class="control-label">{{ __('Customer UID: ') }}</label> 
				 				{{ $payment_details['user_details'] ? $payment_details['user_details']['user_uid'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_full_name" class="control-label">{{ __('Customer Name: ') }}</label> 
				 				{{ $payment_details['user_details'] ? $payment_details['user_details']['user_first_name'] : '-' }} {{ $payment_details['user_details'] ? $payment_details['user_details']['user_last_name'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_payment_status" class="control-label">{{ __('Payment Status: ') }}</label> 
								@if($payment_details['user_order_status'] == 'waiting_for_approval')
				 				waiting for approval
				 				@else
				 				{{ $payment_details['user_order_status'] ? $payment_details['user_order_status'] : '-' }}
				 				@endif
							</div>
						</div>
						<h5>Payment Details</h5>
						<div class="row">
							<div class="col-sm-3 form-group">
								<label for="payment_trans_id" class="control-label">{{ __('Transaction Id: ') }}</label>
								{{ $payment_details['user_order_transaction_id'] ? $payment_details['user_order_transaction_id'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="payment_purpose" class="control-label">{{ __('Payment Purpose: ') }}</label> 
								{{ $payment_details['user_order_payment_purpose'] ? $payment_details['user_order_payment_purpose'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="payment_amount" class="control-label">{{ __('Payment Amount: ') }}</label> 
				 				{{ $payment_details['user_order_amount'] ? $payment_details['user_order_amount'] : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="payment_mode" class="control-label">{{ __('Payment Method: ') }}</label> 
				 				{{ $payment_details['user_order_pay_mode'] ? $payment_details['user_order_pay_mode'] : '-' }}
							</div>
						</div>
						
						@if($payment_details['user_order_pay_mode'] == 'cash')
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
									{{ $payment_details['user_order_date'] ? $payment_details['user_order_date'] : '-' }}
								</div>
								<div class="col-sm-3 form-group">
									<label for="payment_reason" class="control-label">{{ __('Payment Reason: ') }}</label>
									{{ $payment_details['order_reason'] ? $payment_details['order_reason'] : '-' }}
								</div>
							</div>
						@elseif($payment_details['user_order_pay_mode'] == 'cheque')
							<div class="row">
								<div class="col-sm-3 form-group">
									<label for="" class="control-label"></label>
								</div>
								<div class="col-sm-3 form-group">
									<label for="" class="control-label"></label>
								</div>
							</div>
						@elseif($payment_details['user_order_pay_mode'] == 'credit_card' || $payment_details['user_order_pay_mode'] == 'credit_card')
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
									<a href="{{ url('/payments/customer') }}" class="btn btn-warning">Back</a>
								</div>
							</div>
						</div>
					</div>						
				</div>					
			</div>
		</div>
	</div>	
@endsection


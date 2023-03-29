
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
								{{ $op_order_mobile_no ? $op_order_mobile_no : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_uid" class="control-label">{{ __('Operator UID: ') }}</label> 
				 				{{ $op_uid ? $op_uid : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_full_name" class="control-label">{{ __('Operator Name: ') }}</label> 
				 				{{ $op_name ? $op_name : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_payment_status" class="control-label">{{ __('Payment Status: ') }}</label> 
								@if($op_order_status == 'waiting_for_approval')
				 				waiting for approval
				 				@else
				 				{{ $op_order_status ? $op_order_status : '-' }}
				 				@endif
							</div>
						</div>
						<h5>Payment Details</h5>
						<div class="row">
							<div class="col-sm-3 form-group">
								<label for="payment_trans_id" class="control-label">{{ __('Transaction Id: ') }}</label>
								{{ $op_order_transaction_id ? $op_order_transaction_id : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="payment_purpose" class="control-label">{{ __('Payment Purpose: ') }}</label> 
								{{ $op_order_payment_purpose ? $op_order_payment_purpose : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="payment_amount" class="control-label">{{ __('Payment Amount: ') }}</label> 
				 				{{ $op_order_amount ? $op_order_amount : '-' }}
							</div>
							<div class="col-sm-3 form-group">
								<label for="payment_mode" class="control-label">{{ __('Payment Method: ') }}</label> 
				 				{{ $op_order_mode ? $op_order_mode : '-' }}
							</div>
						</div>
						@if($op_order_payment_purpose == 'subscription')
							@if(!empty($payment_p_details))
								<div class="row">
								  	<div class="col-sm-3 form-group">
								  		<label for="sub_plan_name" class="control-label">{{ __('Sub Plan Name: ') }}</label>
										{{ $payment_p_details['sub_scheme_name'] ? $payment_p_details['sub_scheme_name'] : '-' }}
									</div>
									<div class="col-sm-3 form-group">
										<label for="sub_valid_for" class="control-label">{{ __('Sub valid for: ') }}</label>
										{{ $payment_p_details['sub_valid_for'] ? $payment_p_details['sub_valid_for']  : '0' }} Days
									</div>
									<div class="col-sm-3 form-group">
								  		<label for="sub_expiry_date" class="control-label">{{ __('Sub Expiry Date: ') }}</label>
								  		@if(isset($payment_p_details['sub_expiry']))
										{{ $payment_p_details['sub_expiry'] ? $payment_p_details['sub_expiry'] : '-' }}
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
						@if($op_order_mode == 'cash')
							<div class="row">
							  	<div class="col-sm-3 form-group">
							  		<label for="payment_receiver" class="control-label">{{ __('Payment Receiver: ') }}</label>
									{{ $order_receiver ? $order_receiver : '-' }}
								</div>
								<div class="col-sm-3 form-group">
									<label for="payment_payee" class="control-label">{{ __('Payment Payee: ') }}</label>
									{{ $order_payee ? $order_payee : '-' }}
								</div>
								<div class="col-sm-3 form-group">
							  		<label for="payment_date" class="control-label">{{ __('Payment Date: ') }}</label>
									{{ $op_order_date ? $op_order_date : '-' }}
								</div>
								<div class="col-sm-3 form-group">
									<label for="payment_reason" class="control-label">{{ __('Payment Reason: ') }}</label>
									{{ $order_reason ? $order_reason : '-' }}
								</div>
							</div>
						@elseif($op_order_mode == 'cheque')
							<div class="row">
								<div class="col-sm-3 form-group">
									<label for="" class="control-label"></label>
								</div>
								<div class="col-sm-3 form-group">
									<label for="" class="control-label"></label>
								</div>
							</div>
						@elseif($op_order_mode == 'credit_card' || $op_order_mode == 'credit_card')
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
					</div>						
				</div>					
			</div>
		</div>
	</div>		
<!-- JS scripts for this page only -->



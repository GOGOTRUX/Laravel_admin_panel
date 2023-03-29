@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
<h1>Notifications<!--<small>(Vendors)</small>--></h1><!--</br>
<p>
	<a href="{{ route('roles.create') }}" class="btn btn-success">Add new</a>
</p>-->
<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Notifications</li>
</ol>
@endsection
<!-- Main Content -->
@section('content')
	<div class="box p-10"> 
		<div class="row">
			<div class="col-md-2">
				<div class="box box-solid">
					<div class="box-header with-border">
						<h3 class="box-title">Notifications</h3>
						<div class="box-tools">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body no-padding" id= "nav">
						<ul class="nav nav-pills nav-stacked">
							<li class="active">
								<li>
									<a href="{{ url('notifications/read') }}">
										<i class="fa fa-inbox"></i> 
											Read
										<span class="label label-primary pull-right">
											{{ $read }}
										</span>
									</a>
								</li>	
							</li>
							<li class="active" >
								<li>
									<a href="{{ url('notifications/approved') }}">
										<i class="fa fa-envelope-o" >
										</i> 
											Approved 
										<span class="label label-primary pull-right">
											{{ $approved }}
										</span>
									</a>
								</li>
							</li>
							<li class="active">
								<li>
									<a href="{{ url('notifications/archive') }}">
										<i class="fa fa-file-text-o"></i> 
											Archive
										<span class="label label-primary pull-right">
											{{ $archive }}
										</span>
									</a>
								</li>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-10">
				<div class="box box-primary">					
					<div class="box-body">
						<table class="table">
							<thead>
								<tr>
									<th>From</th>
									<th>Subject</th>
									<th>Duration</th>
								</tr>
								<tbody>
									<tr class="text-center">
										<td>{{ $result['from']}}</td>
										<td>
											@if($result['title'] == 'approve_payment_request')
												Approve Payment Request
											@else
												{{ $result['title']}}
											@endif
										</td>
										<td>{{ $result['duration']}}</td>
									</tr>
								</tbody>
							</thead>
						</table>

						<div class="panel-body p-0">
							<div class="view-op">
								<div class="row">
									<section>
										@if($result['orderType'] == 'Subscription' || $result['title'] == 'subplan_expiry')
										<div class="detail-info m-t-20">
											<div class="row">
												<div class="col-sm-3 form-group">
													<label class="control-label">Sub Plan Name: {{ $result['subscriptionDetail']['subscription_type_name'] }}</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Amount: {{ $result['subscriptionDetail']['subscription_amount'] }}</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Business: {{ $result['subscriptionDetail']['subscription_business_rs'] }}</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Vehicle Type: {{ $result['subscriptionDetail']['subscription_veh_wheel_type'] }}</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3 form-group">
													<label class="control-label">Validity:</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Availability: {{ $result['data']['validity_from'] }} - {{ $result['data']['validity_to'] }}</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Logo:</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Description:</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3 form-group">
													<label class="control-label">Plan Added By: {{ $result['data']['created_by'] }}</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Plan Created date : {{ $result['data']['created_date'] }} </label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Plan Approved By: {{ $result['subscriptionDetail']['is_approved_by'] }} </label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Is Plan Free :</label>
												</div>
											</div>
										</div>
										@elseif($result['orderType'] == 'approve_payment')
										<div class="detail-info m-t-20">
											<div class="detail-info">
											@if($result['for'] == 'customer_payment')
												<h5>Customer Details</h5>
												<div class="row">
													<div class="col-sm-3 form-group">
														<label class="control-label">customer Mobile Number: {{ $result['payment_details']['user_order_mobile_no'] }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">customer UID: {{ $result['payment_details']['user_cid'] }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">customer Name: {{ $result['payment_details']['user_name'] }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Payment Status: {{ $result['payment_details']['user_order_status'] }}</label>
													</div>
												</div>
												<h5>Payment Details</h5>
												<div class="row">
													<div class="col-sm-3 form-group">
														<label class="control-label">Transaction Id: {{ $result['payment_details']['user_order_transaction_id'] ? $result['payment_details']['user_order_transaction_id'] : '-' }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Payment Purpose: {{ $result['payment_details']['user_order_payment_purpose'] ? $result['payment_details']['user_order_payment_purpose'] : '-' }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Payment Amount: {{ $result['payment_details']['user_order_amount'] ? $result['payment_details']['user_order_amount'] : '-' }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Payment Method: {{ $result['payment_details']['user_order_pay_mode'] ? $result['payment_details']['user_order_pay_mode'] : '-' }}</label>
													</div>
												</div>
											@else
												<h5>Operator Details</h5>
												<div class="row">
													<div class="col-sm-3 form-group">
														<label class="control-label">Operator Mobile Number: {{ $result['payment_details']['op_order_mobile_no'] }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Operator UID: {{ $result['payment_details']['op_uid'] }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Operator Name: {{ $result['payment_details']['op_name'] }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Payment Status: {{ $result['payment_details']['op_order_status'] }}</label>
													</div>
												</div>
												<h5>Payment Details</h5>
												<div class="row">
													<div class="col-sm-3 form-group">
														<label class="control-label">Transaction Id: {{ $result['payment_details']['op_order_transaction_id'] ? $result['payment_details']['op_order_transaction_id'] : '-' }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Payment Purpose: {{ $result['payment_details']['op_order_payment_purpose'] ? $result['payment_details']['op_order_payment_purpose'] : '-' }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Payment Amount: {{ $result['payment_details']['op_order_amount'] ? $result['payment_details']['op_order_amount'] : '-' }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Payment Method: {{ $result['payment_details']['op_order_mode'] ? $result['payment_details']['op_order_mode'] : '-' }}</label>
													</div>
												</div>
												<div class="row">
													@if(empty($result['payment_details']['payment_p_details']))
													<div class="col-sm-6 form-group">
														<label class="control-label">Sub Plan Details: No Plan details found</label>
													</div>
													@else
													<div class="col-sm-3 form-group">
														<label class="control-label">Sub Plan Name: {{ $result['payment_details']['payment_p_details']['sub_scheme_name'] }}</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Sub valid for: {{ $result['payment_details']['payment_p_details']['sub_valid_for'] }} Days</label>
													</div>
													<div class="col-sm-3 form-group">
														<label class="control-label">Sub Expiry Date: {{ $result['payment_details']['payment_p_details']['sub_expiry'] }}</label>
													</div>
													@endif
												</div>
											@endif
											</div>
										</div>
										@elseif($result['orderType'] == 'op_verification')
										<div class="detail-info m-t-20">
											<div class="row">
												<div class="col-sm-3 form-group">
													<label class="control-label">First Name: {{ $result['operator_details']['op_first_name'] }}</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Last Name: {{ $result['operator_details']['op_last_name'] }}</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Mobile Number : {{ $result['operator_details']['op_mobile_no'] }}</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Email: {{ $result['operator_details']['op_email'] ? $result['operator_details']['op_email'] : '-' }}</label>
												</div>
											</div>
										</div>
										@elseif($result['orderType'] == 'op_registration')
										<div class="detail-info m-t-20">
											<h5>Yor have A new operator registration, </h5>
											<div class="row">
												<div class="col-sm-3 form-group">
													<label class="control-label">Mobile Number: {{ $result['operator_details']['op_mobile_no'] }}</label>
												</div>
												<div class="col-sm-3 form-group">
													<label class="control-label">Type of Registartion: 
														@if($result['operator_details']['op_type_id'] == 1)
															Individual
														@else
															Business
														@endif
													</label>
												</div>
											</div>
										</div>
										@endif
									</section>
								</div>
							</div>
						</div>
						<div class="col-md-12 text-right">
							<a href="{{ route('home.notificationbox')}}" class="btn btn-xs btn-info">Back</a>
							@if($result['orderType'] == 'Subscription')
								<button class="btn btn-xs btn-success" onclick="verifyByAdmin('{{ $result['subscriptionDetail']['subscription_id'] }}',0)" data-toggle="tooltip" data-placement="top">{{ __('Approve') }}</button>
								<button type="submit" class="btn btn-xs btn-warning" onclick="verifyByAdmin('{{ $result['subscriptionDetail']['subscription_id'] }}',0)" data-toggle="tooltip" data-placement="top">{{ __('Hold') }}</button>
								<button type="submit" class="btn btn-xs btn-danger" onclick ="verifyByAdmin('{{ $result['subscriptionDetail']['subscription_id'] }}',2)" data-toggle="tooltip" data-placement="top">{{ __('Reject') }}</button>
							@elseif($result['orderType'] == 'subplan_expiry')
								<button type="submit" class="btn btn-xs btn-warning" onclick="updateNotificationStatus('{{ $result['notification_id'] }}', 'hold')" data-toggle="tooltip" data-placement="top">{{ __('Hold') }}</button>
								<button type="submit" class="btn btn-xs btn-danger" onclick ="updateNotificationStatus('{{ $result['notification_id'] }}', 'reject')" data-toggle="tooltip" data-placement="top">{{ __('Reject') }}</button>
							@elseif($result['orderType'] == 'approve_payment')
								@if($result['payment_details']['op_order_status'] == 'approved')
									<button class="btn btn-xs btn-success" disabled data-toggle="tooltip" data-placement="top" title="Already Approved">{{ __('Approve') }}</button>
									<button type="submit" class="btn btn-xs btn-warning" disabled data-toggle="tooltip" data-placement="top" title="Already Approved">{{ __('Hold') }}</button>
									<button type="submit" class="btn btn-xs btn-danger" disabled data-toggle="tooltip" data-placement="top" title="Already Approved">{{ __('Reject') }}</button>
								@else
									<button class="btn btn-xs btn-success" onclick="paymentNotificationStatusUpdate('{{ $result['notification_id'] }}','approve', '{{ $result['for'] }}')" data-toggle="tooltip" data-placement="top">{{ __('Approve') }}</button>
									<button type="submit" class="btn btn-xs btn-warning" onclick="updateNotificationStatus('{{ $result['notification_id'] }}', 'hold')" data-toggle="tooltip" data-placement="top">{{ __('Hold') }}</button>
									<button type="submit" class="btn btn-xs btn-danger" onclick ="updateNotificationStatus('{{ $result['notification_id'] }}', 'reject')" data-toggle="tooltip" data-placement="top">{{ __('Reject') }}</button>
								@endif
							@elseif($result['orderType'] == 'op_verification' || $result['orderType'] == 'op_registration')
								<a href="{{ route('operators.edit',[$result['operator_details']['op_user_id']]) }}"><button class="btn btn-xs btn-success">{{ __('View') }}</button></a>
								<button type="submit" class="btn btn-xs btn-warning" onclick="updateNotificationStatus('{{ $result['notification_id'] }}', 'hold')" data-toggle="tooltip" data-placement="top">{{ __('Hold') }}</button>
								<button type="submit" class="btn btn-xs btn-danger" onclick ="updateNotificationStatus('{{ $result['notification_id'] }}', 'reject')" data-toggle="tooltip" data-placement="top">{{ __('Reject') }}</button>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('javascript')
	<!-- page script -->
<script src="{{ asset('/bower_components/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!--getting value when we click in inbox read unread and trash  -->
<script language="javascript" type="text/javascript">
	function verifyByAdmin(id,val)
	{
		if(val == 0)
		{
			swal({
			title: 'Are you sure?',
			text: "It will approve the plan",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, approve it!'
		}).then(function() 
		{
			$.ajax({

				url :"{{ route('updateSubcriptionDetail') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"val":val,
					"id": id,
					"orderType" : "Subscription"

				},
				success : function(data)
				{
					swal({title: "Great!", text: "Plan has been approved", type: "success"}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
		}
		else if(val == 1)
		{
			swal({
			title: 'Are you sure?',
			text: "It will hold the plan",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Hold it!'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('updateSubcriptionDetail') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"val":val,
					"id": id,
					"orderType" : "Subscription"
				},
				success : function(data)
				{
					swal({title: "Great!", text: "Plan has been hold", type: "success"}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
		}
		else{
			swal({
			title: 'Are you sure?',
			text: "It will reject the plan",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, reject it!'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('updateSubcriptionDetail') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"val":val,
					"id": id,
					"orderType" : "Subscription"
				},
				success : function(data)
				{
					swal({title: "Great!", text: "Plan has been rejected", type: "success"}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
		}
		
	}

	function paymentNotificationStatusUpdate(notif_id,val, payment_for)
	{
		swal({
			title: 'Are you sure?',
			text: "It will "+val+" the payment",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, '+val+' it!'
		}).then(function() 
		{
			$.ajax({

				url :"{{ route('update-payment-status') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"status": val,
					"id": notif_id,
					"payment_for": payment_for,
				},
				success : function(data)
				{
					var details = JSON.parse(JSON.stringify(data));
					var data = JSON.parse(details.replace(/&quot;/g, '\"'));
					swal({title: "Great!", text: data.msg, type: data.status}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
	}

	function updateNotificationStatus(notif_id,val)
	{
		swal({
			title: 'Are you sure?',
			text: "It will "+val+" the plan",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, '+val+' it!'
		}).then(function() 
		{
			$.ajax({

				url :"{{ route('updateSubcriptionDetail') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"status": val,
					"id": notif_id,
				},
				success : function(data)
				{
					var details = JSON.parse(JSON.stringify(data));
					var data = JSON.parse(details.replace(/&quot;/g, '\"'));
					swal({title: "Great!", text: data.msg, type: data.status}).then(function()
					{ 
						location.reload();
					});
				}
			});
		})
	}
</script>

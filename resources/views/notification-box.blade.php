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
									<a href="#" onclick="getNotification('read')" >
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
									<a href="#" onclick="getNotification('approved')" >
										<i class="fa fa-envelope-o" >
										</i> 
											Approval 
										<span class="label label-primary pull-right">
											{{ $approved }}
										</span>
									</a>
								</li>
							</li>
							<li class="active">
								<li>
									<a href="#" onclick="getNotification('archive')" >
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
						@if (count($admin_notifications) > 0)
						<!-- <div class="mailbox-controls">
							<input type="checkbox" id = "maincheckbox" name="maincheckbox" class= "maincheckbox" >
							<div class="actionButton">
								<div class="btn-group">
									<button type="submit" onclick="delete_admin_notification();" id ="delete" class="btn btn-default btn-sm" ><i class="fa fa-trash-o"></i></button>
								</div>
								<a href="{{ route('home.notificationbox') }}">
									<button type="button" id= "referesh"class="btn btn-default btn-sm">
										<i class="fa fa-refresh">
										</i>
									</button>
								</a>
							</div>
						</div> -->
						<div class="table-responsive">
							<table id="mailplan" class="table table-hover-row table-striped-row {{ count($admin_notifications) > 0 ? 'datatable' : '' }}" data-page-length="25">
								<thead>
									<tr>
									<th>Sr No</th>
									<!-- <th>Select</th> -->
									<th>From</th>
									<th>Subject</th>
									<th>Duration</th>
									<th>open</th>
									</tr>
									<tbody>
										<?php $count=1; $key = 0; ?>
										@foreach ($admin_notifications as $admin_notification)
							
										<?php $notif_details = json_encode($admin_notifications) ?>
											<!-- ifcondition start -->

												@if($admin_notification['is_read'] == 1)			
													<tr class="table-dark-row" data-entry-id="{{ $admin_notification['notification_id'] }}">
														<td>
															{{ $count++ }}
														</td>
														<!-- <td><input type="checkbox" class="checkbox" name="checkbox" id="{{ $admin_notification['notification_id' ]}}"
															 value = "{{ $admin_notification['notification_id' ]}}" 
															></td> -->
														<td class="mailbox-name">
															{{ $admin_notification['message_from'] }}
														</td>
														<td class="mailbox-subject">
															{{ $admin_notification['title'] }}
														</td>
														<td class="mailbox-date">
															{{ $admin_notification['duration'] }}
														</td>													
														<td>
															<div class="btn-group">
															  	<button type="button" class="btn btn-xs btn-success" onclick="showNotificationDetail('{{ $key }}')">
															  		Action
															  	</button>
															  	<button type="button" class="btn btn-xs btn-success" data-toggle="dropdown">
															    	<span class="caret"></span>
															    	<span class="sr-only">
															    		Toggle Dropdown
															    	</span>
															  	</button>
															  	
																  <ul class="dropdown-menu not-menu" role="menu">
																   	@if($admin_notification['title'] == 'Approve Subscription Plan')

								 										<li>
								 											<button type="button" class="btn btn-xs btn-success" onclick="verifyByAdmin( {{ $admin_notification['message_view_id'] }},0)" {{ ( $admin_notification['is_approved'] == 1 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Approve') }}
								 											</button>
								 										</li>
																	    <li>
																	    	<button type="button" class="btn btn-xs btn-warning" onclick="verifyByAdmin({{ $admin_notification['message_view_id'] }},1)" {{ ( $admin_notification['is_approved'] == 0) ? 'disabled' : ' ' }}  data-toggle="tooltip" data-placement="top" title="Already Rejected">
																	    		{{ __('Hold') }}		
																	    	</button>
																	    </li>
																	    <li>
																	    	<button type="button" class="btn btn-xs btn-danger" onclick="verifyByAdmin({{ $admin_notification['message_view_id'] }},2)" {{ ( $admin_notification['is_approved'] == 2 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyRejected"' : ' ' }}>
																	    		{{ __('Reject') }}
																	    		
																	    	</button>
																	    </li>
																	    <li>
																	    	<button type="button" class="btn btn-xs btn-info" onclick="showNotificationDetail('{{ $key }}')">
																		    	Open
																	    	</button>
																		</li>
							 										@elseif($admin_notification['title'] == 'Approve Payment')
														 				<li>
														 					<button class="btn btn-xs btn-success" onclick="verifyPaymentByAdmin( {{ $admin_notification
														 						['message_view_id'] }},0)" {{ ( $admin_notification['op_order_status'] == "approved" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Approve') }}
								 											</button>
														 				</li>
																	    <li>
																	    	<button class="btn btn-xs btn-warning" onclick="verifyPaymentByAdmin({{ $admin_notification['message_view_id'] }},1)" {{ ( $admin_notification['op_order_status'] == "hold" || $admin_notification['op_order_status'] == "waiting_for_approval") ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Hold') }}
																	    	</button>
																	    </li>
																	    <li>
																	    	<button class="btn btn-xs btn-danger" onclick="verifyPaymentByAdmin({{ $admin_notification['message_view_id'] }},2)" {{ ( $admin_notification['op_order_status'] == "reject" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Reject') }}
																	    	</button>
																	    </li>
																	    <li>
																	    	<button class="btn btn-xs btn-info" onclick="showNotificationDetail('{{ $key }}')">
																	    		Open
																	    	</button>
																	    </li>
													 				@endif	
																  </ul>
															</div>
														</td>
													</tr>
												@else
													<tr data-entry-id="{{ $admin_notification['notification_id'] }}">
														<td>
															{{ $count++ }}
														</td>
														<!-- <td><input type="checkbox" class="checkbox" name="checkbox" id="{{ $admin_notification['notification_id' ]}}" value = "{{ $admin_notification['notification_id' ]}}"></td> -->
														<td class="mailbox-name">
															{{ $admin_notification['message_from'] }}
														</td>
														<td class="mailbox-subject">
															{{ $admin_notification['title'] }}
														</td>
														<td class="mailbox-date">
															{{ $admin_notification['duration'] }}
														</td>
														<td>
															<div class="btn-group">
															  	<button type="button" class="btn btn-xs btn-success" onclick="showNotificationDetail('{{ $key }}')">
															  		Action
															  	</button>
															  	<button type="button" class="btn btn-xs btn-success" data-toggle="dropdown">
															    	<span class="caret"></span>
															    	<span class="sr-only">Toggle Dropdown</span>
															  	</button>
																  <ul class="dropdown-menu not-menu" role="menu">
																  	@if($admin_notification['title'] == 'Approve Subscription Plan')
							 										<li>
							 											<button class="btn btn-xs btn-success" onclick="verifyByAdmin( {{ $admin_notification['message_view_id'] }},0)" {{ ( $admin_notification['is_approved'] == 1 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Approve') }}
								 											</button>
							 											</button>
							 										</li>
																    <li>
																    	<button class="btn btn-xs btn-warning" onclick="verifyByAdmin({{ $admin_notification['message_view_id'] }},1)" {{ ( $admin_notification['is_approved'] == 0) ? 'disabled' : ' ' }}  data-toggle="tooltip" data-placement="top" title="Already Rejected">
																	    		{{ __('Hold') }}		
																    	</button>
																    </li>
																    <li>
																    	<button class="btn btn-xs btn-danger" onclick="verifyByAdmin({{ $admin_notification['message_view_id'] }},2)" {{ ( $admin_notification['is_approved'] == 2 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyRejected"' : ' ' }}>
																	    		{{ __('Reject') }}
																    	</button>
																    </li>
																    <li>
																    	<button class="btn btn-xs btn-info" onclick="showNotificationDetail('{{ $key }}')">
																    		Open
																    	</button>
																    </li>
							 										@elseif($admin_notification['title'] == 'Approve Payment')
													 				<li>
													 					<button class="btn btn-xs btn-success" onclick="verifyPaymentByAdmin( {{ $admin_notification['message_view_id'] }},0)" {{ ( $admin_notification['op_order_status'] == "approved" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Approve') }}
													 					</button>	
													 				</li>
																    <li >
																    	<button class="btn btn-xs btn-warning" onclick="verifyPaymentByAdmin({{ $admin_notification['message_view_id'] }},1)" {{ ( $admin_notification['op_order_status'] == "hold" || $admin_notification['op_order_status'] == "waiting_for_approval") ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Hold') }}
																    	</button>
																    </li>
																    <li >
																    	<button class="btn btn-xs btn-danger" onclick="verifyPaymentByAdmin({{ $admin_notification['message_view_id'] }},2)" {{ ( $admin_notification['op_order_status'] == "reject" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Reject') }}
																    	</button>
																    </li>
																    <li>
																    	<button class="btn btn-xs btn-info" onclick="showNotificationDetail('{{ $key }}')">
																    		Open
																    	</button>
																    </li>
													 				@else
													 				<li>
													 					<button class="btn btn-xs btn-success" onclick="verifyPaymentByAdmin( {{ $admin_notification['message_view_id'] }},0)" {{ ( $admin_notification['is_approved'] == "approved" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Approve') }}
													 					</button>	
													 				</li>
																    <li >
																    	<button class="btn btn-xs btn-warning" onclick="verifyPaymentByAdmin({{ $admin_notification['message_view_id'] }},1)" {{ ( $admin_notification['is_approved'] == "hold" || $admin_notification['is_approved'] == "waiting_for_approval") ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Hold') }}
																    	</button>
																    </li>
																    <li >
																    	<button class="btn btn-xs btn-danger" onclick="verifyPaymentByAdmin({{ $admin_notification['message_view_id'] }},2)" {{ ( $admin_notification['is_approved'] == "reject" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >
								 												{{ __('Reject') }}
																    	</button>
																    </li>
																    <li>
																    	<button class="btn btn-xs btn-info" onclick="showNotificationDetail('{{ $key }}')">
																    		Open
																    	</button>
																    </li>
													 				@endif
																  </ul>
															</div>
														</td>
													</tr>
												@endif
												<?php $key++; ?>
											<!-- close if condition -->
										@endforeach	
									</tbody>
								</thead>
							</table>
						</div>
						@else
						<div>
							<span>No Subscription Plan Available</span>
						</div>
						@endif
					</div>
					<div class="modal modal-default fade" id="model-frame">
					  	<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">&times;</span>
									</button>
									<h4 class="modal-title">Delete Mail</h4>
								</div>
							   	<form action="{{ route('delete_admin_notification') }}" method="post">
									@csrf
									<input type="hidden" name="notification_id" id="notificationid">
									<div class="modal-body">
										<p class="text-center">Are you sure want to delete the mail?</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-danger">Delete</button>
									</div>
							  	</form>
							</div>
						</div> 
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
	<!-- JS scripts for this page only -->
@section('javascript')
	<!-- page script -->
<script src="{{ asset('/bower_components/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!--getting value when we click in inbox read unread and trash  -->
<script language="javascript" type="text/javascript">
	function showNotificationDetail(n_key)
	{	
		var details = JSON.parse(JSON.stringify('{{ $notif_details }}'));
		var notifications = JSON.parse(details.replace(/&quot;/g, '\"'));
		$.each(notifications, function (key, value){
			// console.log(value);
			if(key == n_key)
			{
				var token = "{{csrf_token()}}";
				$.ajax({
					headers: {'X-CSRF-TOKEN':token},
					type: "POST",
					url: "/viewNotificationDetail",
					data: {
						"message_id" : value.message_id,
						"message_type": value.message_type,
						"message_view_id": value.message_view_id,
						"notification_msg_id" :  value.notification_msg_id,
						"from" : value.message_from,
	 					"title": value.title,
						"duration":	value.duration
					},
					dataType: 'json',
					async: false,
					success: function(response)
					{	
						var detail = (response);
						console.log(detail);
						if(response['success']){
							if (response.result.orderType == "Subscription"){
								$('#mailplan').empty();
								var html = '';	
								html += '<div class="modal-body"><table class="table"><thead><tr><th>From</th><th>Subject</th><th>Duration</th></tr><tbody><tr class="text-center"><td>'+detail.result.data.created_by+'</td><td>'+detail.result.title+'</td><td>'+detail.result.duration+'</td></tr></tbody></thead></table></div><div class="panel-body p-0"><div class="view-op"><div class="row"><section><div class="detail-info m-t-20">';

								html += '<div class="row"><div class="col-sm-3 form-group"><label class="control-label">Sub Plan Name:'+detail.result.subscriptionDetail.subscription_type_name+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Amount:'+detail.result.subscriptionDetail.subscription_amount+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Business:'+detail.result.subscriptionDetail.subscription_business_rs+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Vehicle Type:'+detail.result.subscriptionDetail.subscription_veh_wheel_type+'</label></div></div>';
								html += '<div class="row"><div class="col-sm-3 form-group"><label class="control-label">Validity:</label></div><div class="col-sm-3 form-group"><label class="control-label">Availability:'+detail.result.data.validity_from+'-'+detail.result.data.validity_to+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Logo:</label></div><div class="col-sm-3 form-group"><label class="control-label">Description:</label></div></div>';
								html += '<div class="row"><div class="col-sm-3 form-group"><label class="control-label">Plan Added By:'+detail.result.data.created_by+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Plan Created date :'+detail.result.data.created_date+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Plan Approved By:'+detail.result.subscriptionDetail.is_approved_by+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Is Plan Free :</label></div></div>'; 
								html += '</div></div></div></div></section><div class="modal-footer">';
								// html +=	'<button type="submit" class="btn btn-success" onclick="clickButton('+ response.result.subscriptionDetail.subscription_id+','+0+','+response.result.orderType+')">Approve</button>';
								html +=	'<a href="{{ route('home.notificationbox')}}" class="btn btn-xs btn-info">Back</a>';
								html +=	'<button class="btn btn-xs btn-success" onclick="verifyByAdmin('+response.result.subscriptionDetail.subscription_id+','+0+')"  {{ ('+detail.result.subscriptionDetail.is_approved+' == 1 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="Already Approved"' : ' ' }}>{{ __('Approve') }}</button>';
								html +=	'<button type="submit" class="btn btn-xs btn-warning" onclick="verifyByAdmin('+ response.result.subscriptionDetail.subscription_id+','+1+')" {{ ('+detail.result.subscriptionDetail.is_approved+' == 0 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="Already Hold"' : ' ' }}>{{ __('Hold') }}</button>';
								html +=	'<button type="submit" class="btn btn-xs btn-danger" onclick ="verifyByAdmin('+ response.result.subscriptionDetail.subscription_id+','+2+')" {{ ('+detail.result.subscriptionDetail.is_approved+' == 2 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="Already Rejected" ' : '' }}>{{ __('Reject') }}</button>';
								html +=	'</div>';
								$('#mailplan').append(html);	
							}
							else{
								$('#mailplan').empty();
								var html = '';
								html += '<div class="modal-body"><table class="table"><thead><tr><th>From</th><th>Subject</th><th>Duration</th></tr><tbody><tr class="text-center"><td>'+detail.result.from+'</td><td>'+detail.result.title+'</td><td>'+detail.result.duration+'</td></tr></tbody></thead></table></div><div class="panel-body p-0"><div class="view-op"><div class="row"><section><div class="detail-info m-t-20">';
								html += '<div class="detail-info"><h5>Operator Details</h5>'
								html += '<div class="row"><div class="col-sm-3 form-group"><label class="control-label">Operator Mobile Number:'+detail.result.payment_details.op_order_mobile_no+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Operator UID:'+detail.result.payment_details.op_uid+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Operator Name:'+detail.result.payment_details.op_name+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Payment Status:'+detail.result.payment_details.op_order_status+'</label></div></div>';
								html += '<h5>Payment Details</h5>';
								html += '<div class="row"><div class="col-sm-3 form-group"><label class="control-label">Transaction Id:'+detail.result.payment_details.op_order_transaction_id+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Payment Purpose:'+detail.result.payment_details.op_order_payment_purpose+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Payment Amount:'+detail.result.payment_details.op_order_amount+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Payment Method:'+detail.result.payment_details.op_order_mode+'</label></div></div>';
								html += '<div class="row"><div class="col-sm-3 form-group"><label class="control-label">Sub Plan Name:'+detail.result.payment_details.payment_p_details.sub_scheme_name+'</label></div><div class="col-sm-3 form-group"><label class="control-label">Sub valid for:'+detail.result.payment_details.payment_p_details.sub_valid_for+'Days</label></div><div class="col-sm-3 form-group"><label class="control-label">Sub Expiry Date:'+detail.result.payment_details.payment_p_details.sub_expiry+'</label></div></div>';
								html +=	'</div>';
								html += '</div></div></div></div></section><div class="modal-footer">';
								html +=	'<a href="{{ route('home.notificationbox')}}" class="btn btn-xs btn-info">Back</a>';
								html +=	'<button class="btn btn-xs btn-success" onclick="verifyPaymentByAdmin('+ detail.result.message_view_id+','+0+')" {{ ('+detail.result.payment_details.op_order_status+' == "approved" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyApproved" ': ' ' }}>{{ __('Approve') }}</button>';
								html +=	'<button class="btn btn-xs btn-warning" onclick="verifyPaymentByAdmin('+ detail.result.message_view_id+','+1+')" {{ ('+detail.result.payment_details.op_order_status+' == "hold" ) ? 'disabled data-toggle="tooltip"data-placement="top" title="AlreadyHold"' : '' }}>{{ __('Hold') }}</button>';
								html +=	'<button class="btn btn-xs btn-danger" onclick ="verifyPaymentByAdmin('+ detail.result.message_view_id+','+2+')" {{ ('+detail.result.payment_details.op_order_status+' == "reject" ) ? ' disabled data-toggle="tooltip" data-placement="top" title="AlreadyRejected" ' : '' }}>{{ __('Reject') }}</button>';
								html +=	'</div>';
								$('#mailplan').append(html);
							}
						}		
					}
				});
			}
		});
	}
	function clickButton(message_view_id,type,orderType)
	{
		var token = "{{csrf_token()}}";
			$.ajax({
				headers: {'X-CSRF-TOKEN':token},
				type: "POST",
				url: "/updateSubcriptionDetail",
				data: {
					"orderType" : orderType,
					"type": type,
					"message_view_id": message_view_id
				},
				dataType: 'json',
				async: false, 
				success: function(response)
				{
					// if(response['success']){
					// 	// window.location = response['redirect_url'];	
					// 	console.log('update successfully');
					// }
					
				}
			});	
	}
	function showInDetail(message_type,message_view_id,notification_msg_id)
	{
		console.log(message_type);
		var token = "{{csrf_token()}}";
		$.ajax({
			headers: {'X-CSRF-TOKEN':token},
			type: "POST",
			url: "/viewNotificationMail",
			data: {
				"message_type": message_type,
				"message_view_id": message_view_id,
				"notification_msg_id" :  notification_msg_id
			},
			dataType: 'json',
			async: false,
			success: function(response)
			{
				if(response['success']){
					
					window.location = response['redirect_url'];	
				}	
			}
		});	
	}

	function getNotification(type)
	{
		var token = "{{csrf_token()}}";
		$.ajax({
			// alert('type');
			headers: {'X-CSRF-TOKEN':token},
			method: "POST",
			url: "/alldetailmail",
			data: {
				"mailtype":type,
			},
			dataType: 'json',
			async: false,
			success: function(response)
			{
				// console.log(response);
				showMailDetail(response);
				if(response['success']){
					window.location = response['redirect_url'];
				}
			}
		});
	}

	// it shows a dynamic data of the tables
	function showMailDetail(data)
	{
		
		$('#mailplan').empty();
		var html = '';
		html += '<div class="table-responsive">';
		html +='<table id="mailplan" class="table table-hover-row table-striped-row {{ '+(data.admin_notifications).length+' > 0 ? 'datatable' : '' }}" data-page-length="25">';
		html += '<thead><thead><tr>';
		html += '<th>Sr No</th>';
		html += '<th>From</th>';
		html += '<th>Subject</th>';
		html += '<th>Duration</th>';
		html += '<th>open</th>';
		html += '</tr>';
		html += '</thead><tbody>'; 
		var count  = 1;
		var key = 0;
		if((data.admin_notifications).length>0)
		{
			$.each(data.admin_notifications, function (key, value)
                {	
                	console.log(value);
                	if(value.is_read == 1)
                	{	
                		html +='<tr  class="table-dark-row"  data-entry-id="'+value.notification_id+'">';
	            		html += '<td>';
						html += count++ ;
						html +='</td>';
						html += '<td class="mailbox-name">';
						html += value.message_from;
						html +='</td>';
						html +='<td class="mailbox-subject">';
						html += value.title;
						html += '</td>';
						html += '<td class="mailbox-date">'+value.duration +'</td>';
						html += '<td><div class="btn-group"><a href="#" class="btn btn-xs btn-success" onclick="showNotificationDetail('+key+')">Action</a><button type="button" class="btn btn-xs btn-success" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu not-menu" role="menu">';
						if(value.title == 'Approve Subscription Plan')
						{
							html += '<li><button class="btn btn-xs btn-success" onclick="verifyByAdmin( '+value.message_view_id+','+0+')" {{ ( '+value.is_approved+' == 1 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >{{ __('Approve') }}</button></li><li><button class="btn btn-xs btn-warning "onclick="verifyByAdmin('+value.message_view_id+','+1+')"{{ ( '+value.is_approved+' == 0) ? 'disabled' : ' ' }}  data-toggle="tooltip" data-placement="top" title="Already Rejected">{{ __('Hold') }}</button></li><li><button class="btn btn-xs btn-danger" onclick="verifyByAdmin('+value.message_view_id+','+2+')" {{ ( '+value.is_approved+' == 2 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyRejected"' : ' ' }}>{{ __('Reject') }}</button></li><li><button class="btn btn-xs btn-info" onclick="showNotificationDetail('+key+')">Open</button></li>';
						}
						else
						{
						html += '<li ><button class="btn btn-xs btn-success" onclick="verifyPaymentByAdmin( '+value.message_view_id+','+0+')" {{ ( '+value.op_order_status+' == "approved" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >{{ __('Approve') }}</button></li><li ><button class="btn btn-xs btn-warning" onclick="verifyPaymentByAdmin('+value.message_view_id+','+1+')" {{ ( '+value.op_order_status+' == "hold") ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyHold"' : ' ' }} >{{ __('hold') }}</button></li><li ><button class="btn btn-xs btn-danger" onclick="verifyPaymentByAdmin('+value.message_view_id+','+2+')" {{ ( '+value.op_order_status+' == "reject" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyReject"' : ' ' }} >{{ __('Reject') }}</button></li><li ><button class="btn btn-xs btn-info" onclick="showNotificationDetail('+key+')">Open</button></li>';	
						}
						html += '</ul></div></td>';
						html += '</tr>';
                	}
                	else
                	{
                		html +='<tr  data-entry-id="'+value.notification_id+'">';
	            		html += '<td>';
						html += count++ ;
						html +='</td>';
						html += '<td class="mailbox-name">';
						html += value.message_from;
						html +='</td>';
						html +='<td class="mailbox-subject">';
						html += value.title;
						html += '</td>';
						html += '<td class="mailbox-date">'+value.duration +'</td>';
						html += '<td><div class="btn-group"><a href="#" class="btn btn-xs btn-success" onclick="showNotificationDetail('+key+')">Action</a><button type="button" class="btn btn-xs btn-success" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu not-menu" role="menu">';
						if(value.title == 'Approve Subscription Plan')
						{
							html += '<li><button class="btn btn-xs btn-success" onclick="verifyByAdmin( '+value.message_view_id+','+0+')" {{ ( '+value.is_approved+' == 1 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >{{ __('Approve') }}</button></li><li><button class="btn btn-xs btn-warning "onclick="verifyByAdmin('+value.message_view_id+','+1+')"{{ ( '+value.is_approved+' == 0) ? 'disabled' : ' ' }}  data-toggle="tooltip" data-placement="top" title="Already Rejected">{{ __('Hold') }}</button></li><li><button class="btn btn-xs btn-danger" onclick="verifyByAdmin('+value.message_view_id+','+2+')" {{ ( '+value.is_approved+' == 2 ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyRejected"' : ' ' }}>{{ __('Reject') }}</button></li><li><button class="btn btn-xs btn-info" onclick="showNotificationDetail('+key+')">Open</button></li>';
						}
						else
						{
						html += '<li ><button class="btn btn-xs btn-success" onclick="verifyPaymentByAdmin( '+value.message_view_id+','+0+')" {{ ( '+value.op_order_status+' == "approved" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >{{ __('Approve') }}</button></li><li ><button class="btn btn-xs btn-warning" onclick="verifyPaymentByAdmin('+value.message_view_id+','+1+')" {{ ( '+value.op_order_status+' == "hold") ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyHold"' : ' ' }} >{{ __('hold') }}</button></li><li ><button class="btn btn-xs btn-danger" onclick="verifyPaymentByAdmin('+value.message_view_id+','+2+')" {{ ( '+value.op_order_status+' == "reject" ) ? 'disabled data-toggle="tooltip" data-placement="top" title="AlreadyReject"' : ' ' }} >{{ __('Reject') }}</button></li><li ><button class="btn btn-xs btn-info" onclick="showNotificationDetail('+key+')">Open</button></li>';	
						}
						html += '</ul></div></td>';
						html += '</tr>';
                	}
            		
	            });
    	}
    	else
    	{
    		html+= '<tr>';
			html+= '<td colspan="12">No Subscription Plan Available</td>';
			html+= '</tr>';
    	}
    	html+='</thead>';
    	key++;
    	html += '</tbody>';
    	html +='</table>';
    	html += '</div>';
		$('#mailplan').append(html);
	}

	function delete_admin_notification()
	{
        if ($('.checkbox:checked').length == 0) 
        {
             alert("Please select atleast one row to perform this action");
        }
        else 
        {
         	var abc=[]; 
            $.each($("input[name='checkbox']:checked"), function()
            {            
            	abc.push($(this).val());
            });
            // alert("My favourite sports are: " + abc.join(", "));
            $('#notificationid').val(abc.join(", "));
			$("#model-frame").modal();
			
        }
    }
		
 	$(document).on('click','body *',function(){
   		if($('input[type="checkbox"]:checked').length > 0)
		{
			
			$(".checkbox").click(function () 
			{
			if ($(this).is(":checked")) 
				{
					if(!$('input:checkbox').prop('checked'))
					{
						if($('input[type="checkbox"]:checked').length > 0)
						{
	    					$(".actionButton").show();
						}
					}
				} 
			});
		}
		else 
		{
			$(".actionButton").hide();
		}
	});

	$(".maincheckbox").show();
	$(".actionButton").hide();
	// $('#delete, #referesh').hide();
	$(".checkbox").click(function () {
		if ($(this).is(":checked")) {
			if(!$('input:checkbox').prop('checked'))
			{
				$(".actionButton").show();
			}
		} 
		else 
		{
			$(".actionButton").hide();
		}
	});

	$('#maincheckbox:checkbox').change(function () 
	{
    	// if(! $('input:checkbox').is('checked')){
	    if(!$('.checkbox:checkbox').is(':checked')) 
	    {    	
	    	$('input:checkbox').prop('checked',true);
	    	$(".actionButton").show();
	    } 	
	    else 
	    {
	    	$('input:checkbox').prop('checked', false);
	    	$(".actionButton").hide();    	
	    }
	});
  	$(function () 
  	{
		$('.mailbox-messages input[type="checkbox"]').iCheck(
		{
		  checkboxClass: 'icheckbox_flat-blue',
		  radioClass: 'iradio_flat-blue'
		});

		//Enable check and uncheck all functionality
		$(".checkbox-toggle").click(function () 
		{
		  	var clicks = $(this).data('clicks');
			if (clicks) 
			{
				//Uncheck all checkboxes
				$(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
				$(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
			} 
			else 
			{
				//Check all checkboxes
				$(".mailbox-messages input[type='checkbox']").iCheck("check");
				$(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
			}
			$(this).data("clicks", !clicks);
		});

		//Handle starring for glyphicon and font awesome
		$(".mailbox-star").click(function (e) 
		{
			e.preventDefault();
			//detect type
			var $this = $(this).find("a > i");
			var glyph = $this.hasClass("glyphicon");
			var fa = $this.hasClass("fa");

		  	//Switch states
			if (glyph) 
			{
				$this.toggleClass("glyphicon-star");
				$this.toggleClass("glyphicon-star-empty");
			}
		  	if (fa) 
		  	{
				$this.toggleClass("fa-star");
				$this.toggleClass("fa-star-o");
		  	}
		});
  	});

  	function verifyByAdmin(id,val)
	{
		console.log(id,val)
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
	function verifyPaymentByAdmin(id,val)
	{
		console.log(id,val);
		if(val == 0)
		{

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
				url :"{{ route('updateSubcriptionDetail') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"val":val,
					"id": id,
					"orderType" : "payment"
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
		else if( val == 1)
		{

		swal({
			title: 'Are you sure you want to hold?',
			text: "It will hold payment",
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
					"orderType" : "payment"
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
		else
		{

		swal({
			title: 'Are you sure you want to reject?',
			text: "It will approve reject",
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
					"orderType" : "payment"
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
		
	}
</script>
@endsection
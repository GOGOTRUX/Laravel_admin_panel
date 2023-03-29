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
									<th>From</th>
									<th>Subject</th>
									<th>Duration</th>
									<th>open</th>
									</tr>
									<tbody>
										<?php $count=1; $key = 0; ?>
										@foreach ($admin_notifications as $admin_notification)
											<!-- ifcondition start -->
											@if($admin_notification['is_read'] == 1)			
												<tr class="table-dark-row" data-entry-id="{{ $admin_notification['notification_id'] }}">
											@else
												<tr data-entry-id="{{ $admin_notification['notification_id'] }}">
											@endif
													<td>
														{{ $count++ }}
													</td>
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
														  	<button type="button" class="btn btn-xs btn-success" >
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
							 											<button type="button" class="btn btn-xs btn-success" onclick="verifyByAdmin('{{ $admin_notification['notification_id'] }}',0)"  data-toggle="tooltip" data-placement="top" {{$admin_notification['is_approved'] == 1 ? 'disabled title=Approved' : ''}}>
							 												{{ __('Approve') }}
							 											</button>
							 										</li>
																    <li>
																    	<button type="button" class="btn btn-xs btn-warning" onclick="verifyByAdmin('{{ $admin_notification['notification_id'] }}',1)" data-toggle="tooltip" data-placement="top" {{$admin_notification['is_approved'] == 1 ? 'disabled title=Approved' : ''}}>
																    		{{ __('Hold') }}		
																    	</button>
																    </li>
																    <li>
																    	<button type="button" class="btn btn-xs btn-danger" onclick="verifyByAdmin('{{ $admin_notification['notification_id'] }}',2)" data-toggle="tooltip" data-placement="top" {{$admin_notification['is_approved'] == 1 ? 'disabled title=Approved' : ''}}>
																    		{{ __('Reject') }}
																    	</button>
																    </li>
																    <li>
																    	<button type="button" class="btn btn-xs btn-info"><a href="{{ url('/notification/view/'.$admin_notification['notification_id']) }}">Open</a>
																    	</button>
																	</li>
						 										@elseif($admin_notification['title'] == 'Approve Payment')
													 				<li>
													 					<button class="btn btn-xs btn-success" onclick="paymentNotificationStatusUpdate('{{ $admin_notification['notification_id'] }}','approve', '{{ $admin_notification['url'] }}')" data-toggle="tooltip" data-placement="top" {{$admin_notification['op_order_status'] == 'approved' ? 'disabled title=Approved' : ''}}>{{ __('Approve') }}
							 											</button>
													 				</li>
																    <li>
																    	<button class="btn btn-xs btn-warning" onclick="updateNotificationStatus('{{ $admin_notification['notification_id'] }}', 'hold')"  data-toggle="tooltip" data-placement="top" {{$admin_notification['op_order_status'] == 'approved' ? 'disabled title=Approved' : ''}}>
							 												{{ __('Hold') }}
																    	</button>
																    </li>
																    <li>
																    	<button class="btn btn-xs btn-danger" onclick="updateNotificationStatus('{{ $admin_notification['notification_id'] }}', 'reject')" data-toggle="tooltip" data-placement="top" {{$admin_notification['op_order_status'] == 'approved' ? 'disabled title=Approved' : ''}}>
							 												{{ __('Reject') }}
																    	</button>
																    </li>
																    <li>
																    	<button class="btn btn-xs btn-info">
																    		<a href="{{ url('/notification/view/'.$admin_notification['notification_id']) }}">Open</a>
																    	</button>
																    </li>
												 				@else
												 					<li>
																    	<button class="btn btn-xs btn-info">
																    		<a href="{{ url('/notification/view/'.$admin_notification['notification_id']) }}">Open</a>
																    	</button>
																    </li>
																    <!-- <li>
																    	<button class="btn btn-xs btn-info">
																    		{{ $admin_notification['message_type'] }}
																    	</button>
																    </li> -->
												 				@endif	
															  </ul>
														</div>
													</td>
												</tr>
											<?php $key++; ?>
											<!-- close if condition -->
										@endforeach	
									</tbody>
								</thead>
							</table>
						</div>
						@else
						<div>
							<span>No Notifications Available</span>
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
						html += '<td><div class="btn-group"><a href="#" class="btn btn-xs btn-success" on</a><button type="button" class="btn btn-xs btn-success" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu not-menu" role="menu">';
						if(value.title == 'Approve Subscription Plan')
						{
							html += '<li><button class="btn btn-xs btn-success" onclick="verifyByAdmin( '+value.message_view_id+','+0+')" {{ ( '+value.is_approved+' == 1 ) ? ' data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >{{ __('Approve') }}</button></li><li><button class="btn btn-xs btn-warning "onclick="verifyByAdmin('+value.message_view_id+','+1+')"{{ ( '+value.is_approved+' == 0) ? '' : ' ' }}  data-toggle="tooltip" data-placement="top" title="Already Rejected">{{ __('Hold') }}</button></li><li><button class="btn btn-xs btn-danger" onclick="verifyByAdmin('+value.message_view_id+','+2+')" {{ ( '+value.is_approved+' == 2 ) ? ' data-toggle="tooltip" data-placement="top" title="AlreadyRejected"' : ' ' }}>{{ __('Reject') }}</button></li><li><button class="btn btn-xs btn-info" </button></li>';
						}
						else
						{
						html += '<li ><button class="btn btn-xs btn-success" onclick="verifyPaymentByAdmin( '+value.message_view_id+','+0+')" {{ ( '+value.op_order_status+' == "approved" ) ? ' data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >{{ __('Approve') }}</button></li><li ><button class="btn btn-xs btn-warning" onclick="verifyPaymentByAdmin('+value.message_view_id+','+1+')" {{ ( '+value.op_order_status+' == "hold") ? ' data-toggle="tooltip" data-placement="top" title="AlreadyHold"' : ' ' }} >{{ __('hold') }}</button></li><li ><button class="btn btn-xs btn-danger" onclick="verifyPaymentByAdmin('+value.message_view_id+','+2+')" {{ ( '+value.op_order_status+' == "reject" ) ? ' data-toggle="tooltip" data-placement="top" title="AlreadyReject"' : ' ' }} >{{ __('Reject') }}</button></li><li ><button class="btn btn-xs btn-info" </button></li>';	
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
						html += '<td><div class="btn-group"><a href="#" class="btn btn-xs btn-success" on</a><button type="button" class="btn btn-xs btn-success" data-toggle="dropdown"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu not-menu" role="menu">';
						if(value.title == 'Approve Subscription Plan')
						{
							html += '<li><button class="btn btn-xs btn-success" onclick="verifyByAdmin( '+value.message_view_id+','+0+')" {{ ( '+value.is_approved+' == 1 ) ? ' data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >{{ __('Approve') }}</button></li><li><button class="btn btn-xs btn-warning "onclick="verifyByAdmin('+value.message_view_id+','+1+')"{{ ( '+value.is_approved+' == 0) ? '' : ' ' }}  data-toggle="tooltip" data-placement="top" title="Already Rejected">{{ __('Hold') }}</button></li><li><button class="btn btn-xs btn-danger" onclick="verifyByAdmin('+value.message_view_id+','+2+')" {{ ( '+value.is_approved+' == 2 ) ? ' data-toggle="tooltip" data-placement="top" title="AlreadyRejected"' : ' ' }}>{{ __('Reject') }}</button></li><li><button class="btn btn-xs btn-info" </button></li>';
						}
						else
						{
						html += '<li ><button class="btn btn-xs btn-success" onclick="verifyPaymentByAdmin( '+value.message_view_id+','+0+')" {{ ( '+value.op_order_status+' == "approved" ) ? ' data-toggle="tooltip" data-placement="top" title="AlreadyVerified"' : ' ' }} >{{ __('Approve') }}</button></li><li ><button class="btn btn-xs btn-warning" onclick="verifyPaymentByAdmin('+value.message_view_id+','+1+')" {{ ( '+value.op_order_status+' == "hold") ? ' data-toggle="tooltip" data-placement="top" title="AlreadyHold"' : ' ' }} >{{ __('hold') }}</button></li><li ><button class="btn btn-xs btn-danger" onclick="verifyPaymentByAdmin('+value.message_view_id+','+2+')" {{ ( '+value.op_order_status+' == "reject" ) ? ' data-toggle="tooltip" data-placement="top" title="AlreadyReject"' : ' ' }} >{{ __('Reject') }}</button></li><li ><button class="btn btn-xs btn-info" </button></li>';	
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
		
	}
	
	function paymentNotificationStatusUpdate(notif_id,val, url)
	{
		// url = '/payments/operator';
		if (url.indexOf("customer") !== -1) {
			payment_for = 'customer_payment';
		}
		else{
		    payment_for = 'operator_payment';
		}
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
@endsection
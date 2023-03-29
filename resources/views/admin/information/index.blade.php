@extends('layouts.app')
@section('content-header')
	<h1>
		Information
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">information</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success" id="success-message">
				{{ session('success') }}
			</div>
		@endif
		@if(session('error'))
			<div class="alert alert-error" id="fail-message">
				{{ session('error') }}
			</div>
		@endif
		
		@if(!auth()->user()->can('sms_create') && !auth()->user()->can('notification_create') && !auth()->user()->can('mail_create'))
		  	<div class="alert alert-error">
				<span> You don't have permission to view this page</span>	
			</div>
		@endif
		<div class="col-xs-12">
			<ul class="nav nav-tabs">
				@can('sms_create')<li class="active"><a data-toggle="tab" href="#sms">SMS</a></li>@endcan
				@can('notification_create')<li><a data-toggle="tab" href="#notification">Notification</a></li>@endcan
				@can('mail_create')<li><a data-toggle="tab" href="#Mail">Mail</a></li>@endcan
			</ul>

			<div class="tab-content">
				@can('sms_create')
					<div id="sms" class="tab-pane fade in active">
						<div class="box">
							<div class="box-body">
								<form method="POST" id="sendSmsForm" action="{{ route('sms.store') }}" enctype="multipart/form-data">
								@csrf
									<div class="panel panel-default">
										<div class="panel-heading">
											Send SMS
										</div>

										<div class="panel-body">
											<div class="row">
												<div class="col-md-6 form-group">
													<label for="notification_to" class="control-label">Upload Excel:</label>
													<input type="file" name="excelupload" id="excelupload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
													@if(session('failed'))
														<div class="alert alert-fail" id="message">
															{{ session('failed') }}
														</div>
													@endif
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 form-group">
													<label for="notification_to" class="control-label">Message:</label>
													<textarea type="text" name="sms_message" id="sms_message" class="form-control"></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 form-group">
													<input type="submit" class="btn btn-success" value="Send">
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				@endcan
				@can('notification_create')
				<div id="notification" class="tab-pane fade">
					<div class="box">
						<div class="box-body">
							<form method="POST" id="sendNotificationForm" action="{{ route('notification.store') }}" enctype="multipart/form-data">  
							@csrf
								<div class="panel panel-default">
									<div class="panel-heading">
										Create Notification
									</div>

									<div class="panel-body">
										<div class="row">
											<div class="col-md-6 form-group">
												<label for="notification_to" class="control-label">{{ __('To*') }}</label>
												@if(!empty($operators_numbers))
												<select class="form-control select2" multiple="multiple" data-placeholder="Please Select" name="selected_numbers[]" id="selected_numbers" required autofocus>
													@foreach ($operators_numbers as $mobile)
													<option>{{ $mobile->op_mobile_no }}</option>
													@endforeach
												</select> 
												@endif 
											</div>
										</div>
										<div class="row">
											<div class="col-md-6 form-group">
												<label for="notification_title" class="control-label">{{ __('Title*') }}</label>
												<input id="notification_title" type="text" class="form-control" name="notification_title" autofocus required>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6 form-group">
												<label for="notification_body" class="control-label">{{ __('Message*') }}</label>
												<textarea id="notification_body" type="text" class="form-control" name="notification_body" autofocus required></textarea>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12 form-group">
												<a href="{{ URL::previous() }}" class="btn btn-success">Back</a>
												<button type="submit" class="btn btn-danger">Send</button>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				@endcan
				@can('mail_create')
				<div id="Mail" class="tab-pane fade">
					<div class="box">
						<div class="box-body">
							<form method="POST" id="sendMailForm" action="{{ route('mail.store') }}">
							@csrf 
								<div class="panel panel-default">
									<div class="panel-heading">
										Create Mail
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-md-6 form-group"> 
												<label for="mail_to" class="control-label">{{ __('To*') }}</label>
												@if(!empty($operators_emails))
												<select class="form-control select2" multiple="multiple" data-placeholder="Please Select" name="selected_mails[]" id="selected_mails" required autofocus>
													@foreach ($operators_emails as $emails)
													<option>{{ $emails->op_email }}</option>
													@endforeach
												</select>
												@endif 
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 form-group"> 
												<label for="mail_subject" class="control-label">{{ __('Subject*') }}</label>
												<input id="mail_subject" type="text" class="form-control" name="subject_to" autofocus required>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 form-group"> 
												<label for="mail_message" class="control-label">{{ __('Message*') }}</label>
												<textarea id="mail_message" type="text" class="form-control" name="mail_message" autofocus required></textarea>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 pull-right">
												<a href="{{ URL::previous() }}" class="btn btn-success">Back</a>
												<button type="submit" class="btn btn-danger">Send</button>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				@endcan
			</div>
		</div>
	</div>
@endsection

@section('javascript')
<script language="javascript" type="text/javascript">
	
	//send sms form validations
	$("#sendSmsForm").validate({
		rules: {
			excelupload: {
				required: true,
			},
			sms_message: {
				required: true,	
			},
		},
		messages: {
			excelupload: {
				required: "Upload Excel File",
			},
			sms_message: {
				required: "Please Enter Message",
			},
		},
	});
	
	//send notification form validations
	$("#sendNotificationForm").validate({
		rules: {
			"selected_numbers[]": {
				required: true,
			},
			notification_title: {
				required: true,	
			},
			notification_body: {
				required: true,	
			},
		},
		messages: {
			"selected_numbers[]": {
				required: "Select Mobile Number",
			},
			notification_title: {
				required: "Please Enter Title",
			},
			notification_body: {
				required: "Please Enter Message",
			},
		},
	});

	//send mail form validations
	$("#sendMailForm").validate({
		rules: {
			"selected_mails[]": {
				required: true,
			},
			subject_to: {
				required: true,	
			},
			mail_message: {
				required: true,
			},
		},
		messages: {
			"selected_mails[]": {
				required: "Select Email",
			},
			subject_to: {
				required: "Please Enter Subject",
			},
			mail_message: {
				required: "Please Enter Message",	
			}
		},
	});	
	
	$(document).ready(function(){
		setTimeout(function() {
            $("#success-message").addClass('hide');
        }, 1000);
        setTimeout(function() {
            $("#fail-message").addClass('hide');
        }, 1000);	
	});
	
</script>

@endsection


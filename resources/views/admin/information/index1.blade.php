@extends('layouts.app')
@section('content-header')
	<h1>
		Message
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Message</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
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
			<div class="box">
				<div class="box-body p-l-r-0">
					<div class="row m-0">
						<div class="col-md-6">
							<div class="cret-msg">
								<h5>Create Msg</h5>
								<form method="POST">
									<div class="row m-0">
										<div class="col-md-2 p-l-0">
											<label>MSG ID</label>
											<input id="" class="form-control" type="text">
										</div>
										<div class="col-md-4 p-l-0">
											<label>Name</label>
											<input id="" class="form-control" type="text">
										</div>
										<div class="col-md-6 p-0">
											<label for="" class="control-label">Content</label>
											<textarea type="text" name="sms_message" id="sms_message" class="form-control"></textarea>
										</div>
									</div>
									<div class="create-btn">
										<button class="btn bg-navy btn-xs" type="button">Create MSG</button>
									</div>
								</form>
								<h5>events</h5>
								<table class="Evet-list">
									<thead>
										<tr>
											<th>Name</th>
											<th>Reference</th>
											<th>Link</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>					
						</div>
						<div class="col-md-6 p-l-0">
							<div class="row m-0">
								<div class="msg-check">
									<input id="sms" class="" type="checkbox">
									<span class="checkmark"></span>
									<span>SMS</span>
								</div>
								<div class="msg-check">
									<input id="sms" class="" type="checkbox">
									<span class="checkmark"></span>
									<span>NOT</span>
								</div>
								<div class="msg-check">
									<input id="sms" class="" type="checkbox">
									<span class="checkmark"></span>
									<span>MAIL</span>
								</div>
							</div>
							<div class="cret-msg m-t-10">
								<div class="row m-0">
									<div class="col-sm-6 p-0">
										<h5 class="m-0">select msg</h5>
									</div>
									<div class="col-sm-6 p-0">
										<div class="has-feedback">
											<input type="text" class="form-control input-sm">
											<span class="glyphicon glyphicon-search form-control-feedback"></span>
										</div>
									</div>
								</div>
								<div class="sel-sms">
									<table class="sms-list">
										<tbody>
											<tr>
												<td>
													<div class="msg-check">
														<input id="" class="" type="checkbox">
														<span class="checkmark"></span>
													</div>		
												</td>
												<td>01</td>
												<td>Welcome</td>
												<td>Welcome to GOGOTRUX. Have a Nice Day!</td>
											</tr>
										</tbody>	
									</table>
								</div>
								<h5>Sending Schedule</h5>
								<div class="sel-sms">
									<table class="sms-list">
										<thead>
										<tr>
											<th></th>
											<th>MSG ID</th>
											<th>Name</th>
											<th>Reference</th>
										</tr>
									</thead>
										<tbody>
											<tr>
												<td>	
												</td>
												<td>01</td>
												<td>Welcome</td>
												<td>Welcome to GOGOTRUX. Have a Nice Day!</td>
											</tr>
										</tbody>	
									</table>
								</div>
								<div class="m-t-10">
									<form method="POST">
										<div class="row m-0">
											<div class="col-1 p-r-5">
												<label>Select MT</label>
												<select id="" type="text" class="form-control">
													<option value="">Select </option>
												</select>
											</div>
											<div class="col-1 p-r-5">
												<label>Select Event</label>
												<select id="" type="text" class="form-control">
													<option value="">Select </option>
												</select>
											</div>
											<div class="col-1 p-r-5">
												<label>Dates</label>
												<select id="" type="text" class="form-control">
													<option value="">Select </option>
												</select>
											</div>
											<div class="col-1 p-r-5">
												<label>Frequency</label>
												<select id="" type="text" class="form-control">
													<option value="">Select </option>
												</select>
											</div>
											<div class="col-1">
												<label>Stop When</label>
												<select id="" type="text" class="form-control">
													<option value="">Select </option>
												</select>
											</div>
										</div>
										<div class="send-sms">
											<div class="sent-opt">
												<div class="row m-0">
													<div class="sel-check m-b-10">
														<input id="sms" class="" type="checkbox">
														<span class="checkmark"></span>
														<span>Remember Schedule</span>
													</div>
													<div class="sel-check m-b-10">
														<input id="sms" class="" type="checkbox">
														<span class="checkmark"></span>
														<span>Applicable to All</span>
													</div>
												</div>	
												<div class="row m-0">
													<div class="sel-check">
														<input id="sms" class="" type="checkbox">
														<span class="checkmark"></span>
														<span>For New Additions</span>
													</div>
													<div class="sel-check">
														<input id="sms" class="" type="checkbox">
														<span class="checkmark"></span>
														<span>Excluding Above</span>
													</div>
												</div>
											</div>
											<div class="send-btn">
												<button class="btn bg-navy btn-xs" type="button">send</button>
											</div>	
										</div>	
									</form>						
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- <div class="col-xs-12">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#sms">SMS</a></li>
				<li><a data-toggle="tab" href="#notification">Notification</a></li>
				<li><a data-toggle="tab" href="#Mail">Mail</a></li>
			</ul>

			<div class="tab-content">
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
			</div>
		</div> -->
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


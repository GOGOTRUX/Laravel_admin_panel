@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Send Mail
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>information</li>
		<li class="active">mail</li>
	</ol>
@endsection

@section('content')
	<form method="POST" id="mailForm" action="{{ route('mail.store') }}">
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
@endsection

@section('javascript')
<script language="javascript" type="text/javascript">

</script>

@endsection
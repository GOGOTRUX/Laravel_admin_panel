@extends('layouts.app')
<!-- Content Header (Page header) -->
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
	<form method="POST" id="mailForm" action="{{ route('information') }}"> 
		<div class="row">
			<div class="col-xs-6 form-group"> 
				<label for="mail_to" class="control-label">{{ __('To*') }}</label>
				<input id="mail_to" type="text" class="form-control" name="mail_to" autofocus required>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6 form-group"> 
				<label for="mail_subject" class="control-label">{{ __('Subject*') }}</label>
				<input id="mail_subject" type="text" class="form-control" name="subject_to" autofocus required>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6 form-group"> 
				<label for="mail_message" class="control-label">{{ __('Message*') }}</label>
				<input id="mail_message" type="text" class="form-control" name="mail_message" autofocus required>
			</div>
		</div>

	</form>
@endsection

@section('javascript')
<script language="javascript" type="text/javascript">

</script>

@endsection
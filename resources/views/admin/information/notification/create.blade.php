<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style type="text/css">
	.help-block-message {
		 display: block;
		margin-top: 5px;
		margin-bottom: 10px;
		color: #dd4b39;
}
</style>

@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Manage Notification
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Notification</li>
	</ol>
@endsection

@section('content')
	<form method="POST" id="addPlanForm" action="{{ route('notification.store') }}" enctype="multipart/form-data">  
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
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection
<script type="text/javascript">

</script>


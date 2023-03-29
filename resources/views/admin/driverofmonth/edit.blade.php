@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Driver Home
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Driver Of Month</li>
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
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					<h4>
						Edit Driver Of The Month
					</h4>
					<form method="POST" id="driverOfMonthForm" name="driver_of_month" action="{{ route ('driver_of_month.update', [$driver_data->id]) }} ">  
					@method('PUT')
					@csrf
						<div class="row">
							<div class="col-md-4">
								<label for="driver_of_month" class="control-label">Mobile Number:</label>
								@if(!empty($driver_data))
								<select class="form-control select2" data-placeholder="Select Mobile Number" name="selected_number" id="selected_number" autofocus required>
									<option>{{ $driver_data->op_mobile_no }}</option>
								@foreach ($driver_numbers as $mobile)
									<option>{{ $mobile->driver_mobile_number }}</option>
								@endforeach
								</select>
								@endif
							</div>
							<div class="col-md-6">
								<label for="driver_of_month" class="control-label">Comment:</label>
								<textarea type="text" class="form-control" name="driver_comment" required>{{ $driver_data->comment }}</textarea>
							</div>
							<div class="col-md-2">
								<input type="submit" class="btn btn-success s-top" value="Submit">
							</div>
						</div> 
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
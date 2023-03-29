
@extends('layouts.app')

@section('content-header')
	<h1>
		Feedback
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Feedback</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success" id="message" style="display:none;">
				{{ session('success') }}
			</div>
		@endif
	</div>
	<div class="col-xs-12">
		<div class="box">
			<div class="box-body">
				<p></p>
		  		<table id="operator" class="table table-bordered table-striped  {{ count($feedback) > 0 ? 'datatable' : '' }}">
					<thead>
						<tr>
							<th></th>
							<th>Trip ID</th>
							<th>User Code</th>
							<th>User Name</th>
							<th>Driver Name</th>
							<th>Driver Rating</th>
							<th>Service Rating</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($feedback))
						<?php $count = 0;?>
							@foreach ($feedback as $feedback)
							<?php $count +=1;?>
							<tr>
								<td>{{$count}}</td>
								<td>{{ $feedback->trip_transaction_id ? $feedback->trip_transaction_id : ''}}</td>
								<td>{{ $feedback->user_code ? $feedback->user_code : ''}}</td>
								<td>{{ $feedback->user_first_name ? $feedback->user_first_name : ''}} 
									{{ $feedback->user_last_name ? $feedback->user_last_name : ''}}
								</td>
								<td>{{ $feedback->driver_first_name ? $feedback->driver_first_name : ''}}
									{{ $feedback->driver_last_name ? $feedback->driver_last_name : ''}}
								</td>
								<td>{{ $feedback->driver_rating ? $feedback->driver_rating : ''}}</td>
								<td>{{ $feedback->service_rating ? $feedback->service_rating : ''}}</td>
							</tr>
							@endforeach
						@else
							<tr>
								<td></td>
								<td colspan="5">No Feedbacks Available</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<script>
	$(function () {
		//$('#operator').DataTable()
	})
</script>
@endsection


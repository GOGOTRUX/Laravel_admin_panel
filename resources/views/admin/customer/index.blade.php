@extends('layouts.app')

@section('content-header')
	<h1>
		Customer listing
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Customer Management</li>
	</ol>
@endsection

<!-- Main Content -->
@section('content')
	<div class="row">
		@if(session('success'))
			<div class="row" id="successMessage">
				<div class="alert alert-success">
					{{ session('success') }}
				</div>
			</div>
		@endif
		<div class="col-xs-12">
			<div class="box">
				<!-- /.box-header -->
				<div class="box-body">
					<table id="Vehicles" class="table table-bordered table-striped {{ count($customers) > 0 ? 'datatable' : '' }} ">
						<thead>
							<tr>
								<th style="text-align:center;">#</th>
								<th>CID</th>
								<th>Name</th>
								<th>Mobile Number</th>
								<th>Type</th>
								<th>Verified Status</th>
								<th>Created Date</th>
								<th>Pay</th>
								<th>Book</th>
								<th>Book<br> Status</th>
								<th>Trip<br> Status</th>
								<th>SMS</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if (count($customers) > 0)
							<?php $count = 0;?>
								@foreach ($customers as $customer)
									<?php $count +=1;?>
									<tr>
										<td>
											{{$count}}
										</td>
										<td>{{$customer['user_uid'] ? $customer['user_uid'] : '' }}</td>
										<td>{{ $customer['full_name'] ? $customer['full_name'] : '' }}</td>
										<td>{{ $customer['user_mobile_no'] ? $customer['user_mobile_no'] : '' }}</td>
										<td>
											{{ ($customer['user_type'] == 0) ? 'Normal' : 'Corporate' }}
										</td>
										<td>{{ ($customer['user_verified'] == 0) ? 'NV' : 'V' }}</td>
										<td>{{ $customer['created_date'] ? $customer['created_date'] : $customer['created_date'] }}</td>
										<td>
											<a href="{{ url('payments') }}">
												<button type="submit" class="btn btn-xs bg-olive" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Make Payments"><i class="fa fa-money" aria-hidden="true"></i></button>
											</a>
										</td>
										<td>
											<button type="button" onclick="bookCustomer('{{ $customer['user_id'] }}')" class="btn btn-xs btn-success" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Book"><i class="fa fa-truck"></i></button>
										</td>
										<td></td>
										<td></td>
										<td>
											<button type="submit" class="btn btn-xs btn-primary" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Send Message"><i class="fa  fa-envelope"></i></button>
										</td>
										<td>
											@can('customer_view')
												<a href="{{ route('customer.show',[$customer['user_id']]) }}"><button class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></button></a>
											@endcan
											@can('customer_edit')
												<a href="{{ route('customer.edit',[$customer['user_id']]) }}"><button class="btn btn-xs btn-info"><i class="fa fa-edit"></i></button></a>
											@endcan
											
											@can('customer_delete')
												@if(!empty($customer['deleted_at']))
													Deleted
												@else
													<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete-modal" onclick="deleteModal('{{ $customer['user_id'] }}')">
														<i class="fa fa-fw fa-trash" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Delete Customer"></i>
													</button>
												@endif
											@endcan

										 	@can('customer_block')
												@if($customer['is_blocked'] == 1)
													<button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#unblock-modal" onclick="openModel('{{ $customer['user_id'] }}')"><i class="fa fa-check-square-o" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Unblock Customer"></i></button>
												@else
													<button type="submit" class="btn btn-xs btn-danger" onclick="openModel('{{ $customer['user_id'] }}')" data-toggle="modal" data-target="#blockedoperator"><i class="fa fa-ban" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Block Customer"></i></button>
												@endif
											@endcan
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="13">No entries in table</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

		<!-- block customer -->
	<div class="modal fade" id="blockedoperator">
		<div class="modal-dialog">
			<form method="POST" action="{{ route('customerBlocked')}}">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Reason for Blocking</h4>
					</div>
					<div class="modal-body">
						<div id="show_driver_image">
							<input type="hidden" name="customer_id" value="" id="customer_id">
							<input type="hidden" name="customer_pagetype" value="index" id="customer_pagetype">
							<textarea id='blockingtext' name="reason" placeholder='Write reasons....' rows=5px cols=75px required></textarea>
						</div>
						<p id="blockedmessage"></p>
					</div>
					<div class="modal-footer">
						<center>
							<input type="submit" name="submit" value="Yes, block it!" class="btn btn-danger">
							<button type="submit" data-dismiss="modal" class="btn btn-success">Cancel</button>
						</center>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="unblock-modal">
		<div class="modal-dialog">
			<form method="POST" action="{{ route('customer-unblocked')}}">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Unblock Customer</h4>
					</div>
					<div class="modal-body">
						<div id="show_driver_image">
							<input type="hidden" name="customer_id" value="" id="unblock_customer_id">
							<input type="hidden" name="customer_pagetype" value="index" id="customer_pagetype">
							Are you sure to unblock customer?
						</div>
					</div>
					<div class="modal-footer">
						<center>
							<input type="submit" name="submit" value="OK" class="btn btn-danger">
							<button type="submit" data-dismiss="modal" class="btn btn-success">Cancel</button>
						</center>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- block customer end -->
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<!-- page script -->
<script>
	$(document).ready(function (){
		setTimeout(function() {
			$('#successMessage').fadeOut('fast');
		}, 1000);
		
		$('[data-toggle="tooltip"]').tooltip();
	});

	function openModel(id)
	{
		if(id!='')
		{
			$('#customer_id').val(id);
			$('#unblock_customer_id').val(id);
		}
	}

	function deleteModal(id){
		swal({
			title: 'Are you sure?',
			text: "Delete a customer!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('customer-delete') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.response){
						swal({title: "Deleted!", text: "Deleted customer", type: result.status}).then(function(){ 
							location.reload();
						});
					}
					else{
						swal({title: "Oops!", text: "Failed to delete customer", type: result.status}).then(function(){
							location.reload();
						});
					}
				}
			});
		})
	}

	function bookCustomer(id){
		swal({
			title: 'Are you sure?',
			text: "Book the customer!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirm'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('book-customer') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					/*if(data.response.status == 'success'){
						window.location = '{{ route("add-trip") }}';
					}*/
				}
			});
		})
	}

</script>
@endsection

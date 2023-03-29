@extends('layouts.app')

@section('content-header')
	<h1>Users</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Users</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success" id="message" style="display:none;">
				{{ session('success') }}
			</div>
		@endif
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					<p>
						@can('users_create')
						<a href="{{ route('users.create') }}" class="btn btn-create btn-success">Add new</a>
						@endcan
						<!-- @can('users_delete')
						<a id="delete_multiple_users" type="button" class="btn btn-xs btn-danger">Delete selected</a>
						@endcan -->
					</p>
					<table id="users" class="table users table-bordered table-striped {{ count($users) > 0 ? 'datatable' : '' }} ">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Email</th>
								<th>Role</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@if (count($users) > 0)
							<?php $count=1; ?>
								@foreach ($users as $user)
									<tr data-entry-id="{{ $user->id }}">
										<td>
											<!-- <a href="">{{ $count++ }}</a> -->
										</td>
										<td>{{ $user->name }}</td>
										<td>{{ $user->email }}</td>
										<td>
											@foreach ($user->roles()->pluck('name') as $role)
												<span class="label label-info label-many">{{ $role }}</span>
											@endforeach
										</td>
										<td>
											@can('users_edit')
											<a href="{{ route('users.edit',[$user->id]) }}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
											@endcan
											@can('users_delete')
												<button class="btn btn-xs btn-danger" type="button" onclick="delete_user('{{ $user->id }}')"><i class="fa fa-trash"></i></button>
											<!-- <form method="POST" action="{{ route('users.destroy',[$user->id]) }}" accept-charset="UTF-8" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">
												@method('DELETE')
												@csrf
												<input class="btn btn-xs btn-danger" type="submit" value="Delete">
											</form> -->
											@endcan
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="9">No entries in table</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<!-- page script -->
<script language="javascript" type="text/javascript">
	$( document ).ready(function()
	{
		$('#message').fadeIn('slow', function()
		{
			$('#message').delay(1000).fadeOut(); 
		});

		$("#delete_multiple_users").click(function(){
			var selectedId = [];
			$('#selected_user').each(function() {
				console.log("this.value");
				selectedId.push(this.value);
			});
			console.log("selectedId");
			console.log(selectedId);
			if(selectedId.length > 1){
				$.ajax({
					url :"{{ route('users.mass_destroy') }}",
					method:"POST",
					data: {
						"_token": "{{ csrf_token() }}",
						"selectedId": selectedId
					},
					success : function(data){
						if(data==1)
						{
							alert("Multiple Operator deleted successfully");
						}
					},
				});
			}
			else{
				alert("please select atleast two");
			}
		});
	});

	function delete_user(id)
	{
		console.log(id);
		swal({
			title: 'Are you sure?',
			text: "Delete this user!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('delete-user') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"user_id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Deleted!", text: "User has been deleted.", type: result.status}).then(function()
						{ 
							location.reload();
						});
					}
					else{
						swal({title: "Oops!", text: "Something went wrong.", type: result.status}).then(function()
						{
							location.reload();
						});
					}
				}
			});
		})
	}
</script>

@endsection
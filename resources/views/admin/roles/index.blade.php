@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
	<h1>Roles<!--<small>(Vendors)</small>--></h1><!--</br>
	<p>
		<a href="{{ route('roles.create') }}" class="btn btn-success">Add new</a>
	</p>-->
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Roles</li>
	</ol>
@endsection

<!-- Main Content -->
@section('content')
	<div class="row">
		@if(session('success'))
		   <div class="alert alert-success" id="message" style="display:none;">
			  {{ session('success') }}
		  </div>
		@endif
		<div class="col-xs-12">
			<div class="box">
				<!-- <div class="box-header">
				  <h3 class="box-title">Individual Operators</h3>
				</div> -->
				<!-- /.box-header -->
				<div class="box-body">
					<p>
						@can('role_create')
							<a href="{{ route('roles.create') }}" class="btn btn-xs btn-success">Add new</a>
						@endcan
					<!-- @can('role_delete')
					<a href="{{ route('roles.mass_destroy') }}" class="btn btn-xs btn-danger js-delete-selected">Delete selected</a>
					@endcan -->
					</p>
				  	<!-- <table id="roles" class="table table-bordered table-striped {{ count($roles) > 0 ? 'datatable' : '' }} dt-select"> -->
					<table id="roles" class="table roles table-bordered table-striped {{ count($roles) > 0 ? 'datatable' : '' }} ">
						<thead>
							<tr>
								<th style="text-align:left;">#
								<!-- <input type="checkbox" id="select-all" /> -->
								</th>
								<th>Name</th>
								<th>Permissions</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@if (count($roles) > 0)
								@foreach ($roles as $role)
									<tr data-entry-id="{{ $role->id }}">
										<td></td>
										<td>{{ $role->name }}</td>
										<td>
											@if ($role->name == "Super Admin")
											  <span class="label label-info label-many">All</span>
											@endif
											@foreach ($role->permissions()->pluck('name') as $permission)
												<span class="label label-info label-many">{{ $permission }}</span>
											@endforeach
										</td>
										<td>
											@can('role_edit')
												<a href="{{ route('roles.edit',[$role->id]) }}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
											@endcan
											@can('role_delete')
												<button class="btn btn-xs btn-danger" type="button" onclick="delete_role('{{ $role->id }}')"><i class="fa fa-trash"></i></button>
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
			<!-- /.box-body -->
		  	</div>
		  <!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<!-- page script -->
<script>       
	$( document ).ready(function()
	{
		$('#message').fadeIn('slow', function()
		{
		  $('#message').delay(1000).fadeOut(); 
		  });
	});

	function delete_role(id)
	{
		swal({
			title: 'Are you sure?',
			text: "Delete this role!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('delete-role') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"role_id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Deleted!", text: "Role has been deleted.", type: result.status}).then(function()
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
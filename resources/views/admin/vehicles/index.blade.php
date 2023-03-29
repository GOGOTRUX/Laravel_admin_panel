@extends('layouts.app')

@section('content-header')
	<h1>
		Vehicles		
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Roles</li>
	</ol>
@endsection

@section('content')
	<div class="row" id="msg">
		@if(session('success'))
			<div class="alert alert-success" id="message" style="display:none;">
				{{ session('success') }}
			</div>
		@endif
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					<p>
						<a href="{{ route('vehiclesfacility/create') }}" class="btn btn-xs btn-success">Add new</a>	
						<button class="btn btn-xs btn-danger" id="vehiclesdelete" name="submit">Delete selected</button>
					</p>
					<table id="Vehicles" class="table table-bordered table-striped {{ count($Vehicles) > 0 ? 'datatable' : '' }}">
						<thead>
							<tr>
								<th style="text-align:center;"></th>
								<th>Vehicles Model Name</th>
								<th>Vehicles Type Name</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						@if (count($Vehicles) > 0)
							@foreach ($Vehicles as $Vehicles)
								<tr data-entry-id="{{ $Vehicles->veh_id }}">
									<td>
										<input type="checkbox" id="vehiclesidelectdelete" name="vehiclesidelectdelete[]"  value="{{ $Vehicles->veh_id }}" />
									</td>
									<td>{{ $Vehicles->veh_model_name }}</td>
									<td>{{ $Vehicles->veh_type_name }}</td>
									<td>
										{{$Vehicles->is_active==1 ? 'Active' : 'Deactive' }}
									</td>								  
									<td>
										<a href="{{ route('Vehicles/edit/',[$Vehicles->veh_id]) }}" class="btn btn-xs btn-info">Edit</a>
										<input class="btn btn-xs btn-danger" type="submit" value="Delete" onclick="deleteon('{{ $Vehicles->veh_id }}')">
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
<script language="javascript" type="text/javascript">
	 
	$(document).ready(function () {
		$('#message').fadeIn('slow', function()
		{
			$('#message').delay(1000).fadeOut(); 
		});
		
		$("#vehiclesdelete").click(function()
		{
			if($('#vehiclesidelectdelete:checked').length!=0)
			{
				swal({
					title: 'Are you sure?',
					text: "It will permanently deleted !",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then(function() 
				{
					var selectid=new Array();
					$('#vehiclesidelectdelete:checked').each(function() {
						selectid.push(this.value);
					});
			
					$.ajax({
						url :"{{ route('Vehiclesselectdeleted') }}",
						method:"POST",
						data: {
							"_token": "{{ csrf_token() }}",
							"selectid": selectid
						},        
						success : function(data)
						{          
							swal({title: "Deleted!", text: "Your file has been deleted.", type: "success"}).
							then(function()
							{ 
								location.reload();
							});
						}
					});
				})
			} 
		});
	});

	function deleteon(id)
	{ 
		swal({
			title: 'Are you sure?',
			text: "It will permanently deleted !",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then(function() 
		{
			$.ajax({
				url :"vehiclesdelete",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"selectid": id
				},
				success : function(data)
				{
					swal({title: "Deleted!", text: "Your file has been deleted.", type: "success"}).
					then(function()
					{ 
						location.reload();
					});
				}
			});
		})
	}
</script>
@endsection


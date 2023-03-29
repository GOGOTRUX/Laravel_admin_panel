@extends('layouts.app')

@section('content-header')
	<h1>
		Vehicles Facility Master
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Roles</li>
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
						<a href="{{ route('vehiclemaster.create') }}" class="btn btn-xs btn-success">Add new</a>
					</p>
					<table id="Vehiclesmaster" class="table table-bordered table-striped {{ count($veh_fac_master) > 0 ? 'datatable' : '' }}">
						<thead>
							<tr>
								<th style="text-align:center;"></th>
								<th>Facility Type</th>
								<th>Description</th>
								<th>Datatype</th>
								<th>Required Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if (count($veh_fac_master) > 0)
								@foreach ($veh_fac_master as $veh_fac_master)
									<tr data-entry-id="{{ $veh_fac_master->veh_fac_id }}">
										<td style="text-align:center;"></td>
										<td>
											{{ $veh_fac_master->veh_fac_type ? $veh_fac_master->veh_fac_type : '-' }}
										</td>
										<td style="text-align:center;">
											{{ $veh_fac_master->veh_fac_desc ? $veh_fac_master->veh_fac_desc : '-' }}
										</td>
										<td>
											{{ $veh_fac_master->veh_fac_data_type ? $veh_fac_master->veh_fac_data_type : '-' }}</td>
										<td> 
											{{$veh_fac_master->veh_fac_is_required==1 ? 'Yes' : 'No'}}
											</td>
										<td>
											<a href="{{ route('vehiclemaster.edit',[$veh_fac_master->veh_fac_id]) }}" class="fa fa-fw fa-edit" style="font-size: 20px;"></a>
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

@section('javascript')
<script type="text/javascript">
	$( document ).ready(function()
	{
		$('#message').fadeIn('slow', function()
		{
			$('#message').delay(1000).fadeOut();
		});
	});
</script>
@endsection


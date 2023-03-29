@extends('layouts.app')

@section('content-header')
	<h1>
		Vehicles Facility		
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Roles</li>
	</ol>
@endsection

@section('content')
	<div class="row">
	<?php $set=1;?>
		@if(session('success') && $set==1)
		<div class="alert alert-success" id="message" style="display:none;">         
			{{ session('success') }}              
		</div>		
		@endif

		<div class="col-xs-12">
			<ul class="nav nav-tabs">
				@can('vehicle_facility_manage')
				<li class="active"><a data-toggle="tab" href="#vehicle_facility">Vehicles Facility</a></li>
				@endcan
				@can('vehicle_facility_master_manage')
				<li><a data-toggle="tab" href="#facility_master">Vehicles Facility Master</a></li>
				@endcan
			</ul>

			<div class="tab-content">
				<div id="vehicle_facility" class="tab-pane fade in active">
				    <div class="box">			  
						<div class="box-body">
							<p>
								@can('vehicle_facility_create')
								<a href="{{ route('vehiclesfacility/create') }}" class="btn btn-xs btn-success">Add new</a>
								@endcan
								<!-- <button class="btn btn-xs btn-danger" id="vehiclesdelete" name="submit">Delete selected</button> -->
							</p>
							<table id="Vehicles" class="table table-bordered table-striped {{ count($Vehicles) > 0 ? 'datatable' : '' }}">
								<thead>
									<tr>
										<th style="text-align:center;"></th>
										<th>Make</th>
										<th>Model Name</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@if (count($Vehicles) > 0)
										@foreach ($Vehicles as $Vehicles)
											<tr data-entry-id="{{ $Vehicles->veh_id }}">
												<td>
													<!-- <input type="checkbox" id="vehiclesidelectdelete" name="vehiclesidelectdelete[]"  value="{{ $Vehicles->veh_id }}" /> -->
												</td>
												<td>{{ $Vehicles->veh_type_name }}</td>
												<td>{{ $Vehicles->veh_model_name }}</td>
												<td>
													{{ $Vehicles->is_active==1 ? 'Active' : 'Deactive' }}
												</td>
												<td>
													@can('vehicle_facility_edit')
													<a href="{{ route('Vehicles/edit/',[$Vehicles->veh_id]) }}" class="fa fa-fw fa-edit" style="font-size: 20px;"></a>
													@endcan
													 <!-- <i class="fa fa-fw fa-trash" style="color:red; font-size: 20px;" onclick="deleteon('{{ $Vehicles->veh_id }}')")"></i> -->
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
				<div id="facility_master" class="tab-pane fade">
				    <div class="box">
						<div class="box-body">
							<p>
								@can('vehicle_facility_master_manage')
								<a href="{{ route('vehiclemaster.create') }}" class="btn btn-xs btn-success">Add new</a>
								@endcan
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
			
		</div>
	</div>
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<script type="text/javascript">
	$('#message').fadeIn('slow', function()
	{
		$('#message').delay(1000).fadeOut();                 
	});  
</script>
@endsection


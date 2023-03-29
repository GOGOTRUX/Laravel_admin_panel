@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        Vehicles
        <!--<small>(Vendors)</small>-->
    </h1><!--</br>
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
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
        @endif
        <div class="col-xs-12">
          <div class="box">

            <div class="box-body">
              <p>
                <a href="{{ route('vehicles.create') }}" class="btn btn-xs btn-success">Add new</a>
                
             
              </p>
              <table id="Vehicles" class="table table-bordered table-striped {{ count($Vehicles) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                <tr>
                  <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                  <th>Vehicles Model Name</th>
                  <th>Vehicles type Name</th>
                  <th>Status</th>
                  <th>Action</th>

                </tr>
                </thead>
                <tbody>
                    @if (count($Vehicles) > 0)
                        @foreach ($Vehicles as $Vehicles)
                            <tr data-entry-id="{{ $Vehicles->veh_id }}">
                                <td></td>

                                <td>{{ $Vehicles->veh_model_name }}</td>
                                <td>{{ $Vehicles->veh_type_name }}</td>
                                <td>
                                  @if(($Vehicles->is_active)==1)
                                  Active
                                  @else
                                  Deactive
                                  @endif
                                </td>
                              
                                <td>
                                    <a href="{{ route('Vehicles/edit/',[$Vehicles->veh_id]) }}" class="btn btn-xs btn-info">Edit</a>
                                    <a href="" class="btn btn-xs btn-danger">delete</a>                                 
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
  $(function () {
    //$('#roles').DataTable()
  })
</script>
@endsection
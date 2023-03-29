@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        Operator Deposite
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
          <div class="alert alert-success" id="message">
          {{ session('success') }}
        </div>
      @endif
    

        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
              <p>
                <a href="{{ route('deposite/create') }}" class="btn btn-xs btn-success">Add new</a>
                
              <!--   <a href="{{ route('roles.mass_destroy') }}" class="btn btn-xs btn-danger js-delete-selected">Delete selected</a> -->
              </p>
              <table id="Vehicles" class="table table-bordered table-striped {{ count($depositlist) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                <tr>
                  <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                  <th>User Name</th>
                  <th>Email</th>
                  <th>Mobile Number</th>
                  <th>Payment Mode</th>
                  <th>Action</th>

                </tr>
                </thead>
                <tbody>
                    @if (count($depositlist) > 0)
                        @foreach ($depositlist as $depositlist)
                            <tr data-entry-id="{{ $depositlist->op_order_id }}">
                              <td></td>
                              <td>{{ $depositlist->op_order_username }}</td>
                              <td>{{ $depositlist->op_order_email }}</td>
                              <td>{{ $depositlist->op_order_mobile_no }}</td>
                              <td>{{ $depositlist->op_order_mode }}</td>
                              <td>                                    
                                    <a href="{{ route('deposite/edit/',[$depositlist->op_order_id]) }}" class="btn btn-xs btn-info">Edit</a>
                                    
                                    <form method="get" action="{{ route('deposite/delete/',[$depositlist->op_order_id]) }}" accept-charset="UTF-8" style="display: inline-block;" onsubmit="return confirm('Are you sure?');">                                        
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-xs btn-danger" type="submit" value="Delete">
                                    </form>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>       
    $( document ).ready(function()
    {
        $('#message').fadeIn('slow', function()
        {
          $('#message').delay(1000).fadeOut(); 
          });
    });
</script>
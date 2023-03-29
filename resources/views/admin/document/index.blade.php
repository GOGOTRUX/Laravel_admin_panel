
@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        
        <!--<small>(Vendors)</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
    
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
                
                <a href="{{ route('roles.create') }}" class="btn btn-xs btn-success">Add new</a>
                <a href="{{ route('roles.mass_destroy') }}" class="btn btn-xs btn-danger js-delete-selected">Delete selected</a>
              </p>
              <table id="operator" class="table table-bordered table-striped  dt-select">
                <thead>
                <tr>
                  <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                  <th>Driver ID</th>
                  <th>Document Number</th>
                  <th>Document type</th>
                  <th>Document Image</th>
                  <th>Document Expiry</th>
                  <th>Document verified</th>
                  <th>Document verified_by</th>
                  <th>Created</th>
                  <th>Actions</th>
                  
                </tr>
                </thead>
                <tbody>

              
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
    //$('#operator').DataTable()
  })
</script>
@endsection

<script>
       
        $( document ).ready(function(){
            $('#message').fadeIn('slow', function(){
               $('#message').delay(500).fadeOut(); 
            });
        });
</script>
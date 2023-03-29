
@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        Vehicle Facilities
        <!--<small>(Vendors)</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
    <li>Roles</li>
    <li class="active">Edit</li>
    </ol>
@endsection

<!-- Main Content -->
@section('content')
    @if(session('success'))
        <!-- If password successfully show message -->
        <div class="row">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @else
    <form method="POST" action="{{ route('vehiclesfacility/store') }}">    
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            Create
        </div>

        <div class="panel-body">

            <div class="row">

                <div class="col-xs-6 form-group">
                    <label for="veh_model_name" class="control-label">{{ __(' Model *') }}</label>
                    <input id="veh_model_name" type="text" class="form-control" name="veh_model_name" value="{{ old('veh_model_name') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('veh_model_name'))
                        <p class="help-block text-red">
                            {{ $errors->first('veh_model_name') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="Vehicle Type" class="control-label">{{ __('Make*') }}</label>
                    <input id="veh_type_name" type="text" class="form-control" name="veh_type_name" value="{{ old('veh_type_name') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('veh_type_name'))
                        <p class="help-block text-red">
                            {{ $errors->first('veh_type_name')}}
                        </p>
                    @endif
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label class="control-label">Vehicles Facility</label>
                </div>
            </div>


        <div class="row">
        @if(!empty($inputfields))
        @foreach($inputfields as $inputfields)
        <div class="col-xs-12 form-group">
            
            <label  class="control-label">{{ ucwords($inputfields->veh_fac_type) }}*</label>

            <input type="hidden" name="veh_fac_master_id[]"  value="{{ $inputfields->veh_fac_id }}" required>
            <input type="text" name="veh_fac_value[]" value="" required class="form-control">        
        </div>
        @endforeach
        @else
        <div class="col-xs-12 form-group">
            N.A

         <script type="text/javascript">
        $(document).ready(function()
        {            
            $("#submit").hide();        
        });
</script>
        </div>

        @endif
</div>


            
    </div>
</div>
    <button type="submit" class="btn btn-danger" id="submit">
        {{ __('Save') }}
    </button>
    </form>
    @endif
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection
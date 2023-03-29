
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
    <form method="POST" action="{{ route('vehiclemaster.store') }}">    
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            Create
        </div>

        <div class="panel-body">

            <div class="row">

                <div class="col-xs-12 form-group">
                    <label for="veh_fac_type" class="control-label">{{ __('Facility Type*') }}</label>
                    <input id="veh_fac_type" type="text" class="form-control" name="veh_fac_type" value="{{ old('veh_fac_type') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('veh_fac_type'))
                        <p class="help-block text-red">
                            {{ $errors->first('veh_fac_type') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-12 form-group">
                    <label for="veh_fac_desc" class="control-label">{{ __(' Description*') }}</label>
                    <input id="veh_fac_desc" type="text" class="form-control" name="veh_fac_desc" value="{{ old('veh_fac_desc') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('veh_fac_desc'))
                        <p class="help-block text-red">
                            {{ $errors->first('veh_fac_desc')}}
                        </p>
                    @endif
                </div>

            </div>

            <div class="row">

                <div class="col-xs-12 form-group">
                    <label for="veh_fac_data_type" class="control-label">{{ __('  Datatype*') }}</label>
                    <input id="veh_fac_data_type" type="text" class="form-control" name="veh_fac_data_type" value="{{ old('veh_fac_data_type') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('veh_fac_data_type'))
                        <p class="help-block text-red">
                            {{ $errors->first('veh_fac_data_type') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-12 form-group">
                    <label for="veh_fac_is_required" class="control-label">{{ __('  Required status *') }}</label>
                    
                    <select id="veh_fac_is_required" type="text" class="form-control" name="veh_fac_is_required" required autofocus >
                        <option>Please Select</option>
                        <option value="1">Required</option>
                        <option value="0">Not Required</option>
                    </select>
                    <p class="help-block"></p>
                    @if($errors->has('veh_fac_is_required'))
                        <p class="help-block text-red">
                            {{ $errors->first('veh_fac_is_required')}}
                        </p>
                    @endif
                </div>

            </div>
            
    </div>
</div>
    <button type="submit" class="btn btn-danger">
        {{ __('Save') }}
    </button>
    </form>
    @endif
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection
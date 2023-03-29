
@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        Vehicle Management
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

    <form method="POST" action="{{ route('vehicles/updateveh') }}">          
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            Edit
        </div>

        <div class="panel-body">           
            <div class="row">

                <div class="col-xs-12 form-group">
                    <label for="veh_model_name" class="control-label">{{ __('Vehicle Model Name*') }}</label>
                    
                    <input id="veh_id" type="hidden" class="form-control" name="veh_id" value="{{  $Vehicles->first()->veh_id}}" required autofocus>
                    
                    <input id="veh_model_name" type="text" class="form-control" name="veh_model_name" value="{{  $Vehicles->first()->veh_model_name}}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('veh_model_name'))
                        <p class="help-block text-red">
                            {{ $errors->first('veh_model_name') }}
                        </p>
                    @endif
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="veh_type_name" class="control-label">{{ __('Vehicle Type*') }}</label>
                    
                    <input id="veh_type_name" type="text" class="form-control" name="veh_type_name" value="{{  $Vehicles->first()->veh_type_name   }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('veh_type_name'))
                        <p class="help-block text-red">
                            {{ $errors->first('veh_type_name') }}
                        </p>
                    @endif
                </div>
            </div>

             
            <div class="row">
                <div class="col-xs-12 form-group">
                    
                    @if(($Vehicles->first()->is_active)==1)
                    <input id="is_active" type="checkbox" name="is_active" value="1"  checked>
                    @else
                    <input id="is_active" type="checkbox" name="is_active" value="0"  autofocus>
                    @endif
                    <label for="is_active" class="control-label">{{ __('Vehicle Satus') }}</label>
                     
                    <p class="help-block"></p>
                    @if($errors->has('is_active'))
                        <p class="help-block text-red">
                            {{ $errors->first('is_active') }}
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
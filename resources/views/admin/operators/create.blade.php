@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        Operators
        <!--<small>(Vendors)</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">All Operators</li>
    </ol>
@endsection


<!-- Main Content -->
@section('content')
    <form method="POST"  action="{{ route('operators.store') }}"> 
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            Create
        </div>
        
        <div class="panel-body">
        	
            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="name" class="control-label">{{ __('First Name*') }}</label>
                    <input id="name" type="text" class="form-control" name="op_first_name" value="{{ old('name') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_first_name'))
                        <p class="help-block">
                            {{ $errors->first('op_first_name') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="name" class="control-label">{{ __('Last Name*') }}</label>
                    <input id="name" type="text" class="form-control" name="op_last_name" value="{{ old('name') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_last_name'))
                        <p class="help-block">
                            {{ $errors->first('op_last_name') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="name" class="control-label">{{ __('Mobile Number*') }}</label>
                    <input id="name" type="text" class="form-control" name="op_mobile_no" value="{{ old('name') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_mobile_no'))
                        <p class="help-block">
                            {{ $errors->first('op_mobile_no') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="name" class="control-label">{{ __('Mobile Number*') }}</label>
                    <input id="name" type="text" class="form-control" name="op_mobile_no" value="{{ old('name') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_mobile_no'))
                        <p class="help-block">
                            {{ $errors->first('op_mobile_no') }}
                        </p>
                    @endif
                </div>
            </div>

            
    <button type="submit" class="btn btn-danger">
        {{ __('Save') }}
    </button>
    </form>
@endsection


<!-- JS scripts for this page only -->
@section('javascript')
@endsection
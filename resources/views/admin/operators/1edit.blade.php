<?php 
//echo dd($operator);
?>

@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        {{ $header }}
        <!--<small>(Vendors)</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
    <li>{{ $header }}</li>
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
    <form method="POST" action="{{ route('operators.update', [$operator->op_user_id]) }}">
    @method('PUT')
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            Edit
        </div>
        
        <div class="panel-body">

            <div class="row">
                <div class="col-xs-12 form-group"><b>Personal Information</b></div>
                <div class="col-xs-6 form-group">
                    <label for="op_first_name" class="control-label">{{ __('First Name*') }}</label>
                    <input id="op_first_name" type="text" class="form-control" name="op_first_name" value="{{ $operator->op_first_name }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_first_name'))
                        <p class="help-block">
                            {{ $errors->first('op_first_name') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="op_last_name" class="control-label">{{ __('Last Name*') }}</label>
                    <input id="op_last_name" type="text" class="form-control" name="op_last_name" value="{{ $operator->op_last_name }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_last_name'))
                        <p class="help-block">
                            {{ $errors->first('op_last_name') }}
                        </p>
                    @endif
                </div>

            </div>

            <div class="row">
                <div class="col-xs-6 form-group">
                    <label for="op_mobile_no" class="control-label">{{ __('Mobile Number*') }}</label>
                    <input id="op_mobile_no" type="text" class="form-control" name="op_mobile_no" value="{{ $operator->op_mobile_no }}" required  disabled="disabled">
                    <p class="help-block"></p>
                    @if($errors->has('op_mobile_no'))
                        <p class="help-block">
                            {{ $errors->first('op_mobile_no') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="op_alternative_mobile_no" class="control-label">{{ __('Alternative Mobile Number*') }}</label>
                    <input id="op_alternative_mobile_no" type="text" class="form-control" name="op_alternative_mobile_no" value="{{ $operator->op_alternative_mobile_no }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_alternative_mobile_no'))
                        <p class="help-block">
                            {{ $errors->first('op_alternative_mobile_no') }}
                        </p>
                    @endif
                </div>

            </div>

            <div class="row">
                
                <div class="col-xs-6 form-group">
                    <label for="op_mobile_no" class="control-label">{{ __('Pet Name*') }}</label>
                    <input id="op_username" type="text" class="form-control" name="op_username" value="{{ $operator->op_username }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_username'))
                        <p class="help-block">
                            {{ $errors->first('op_username') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="op_dob" class="control-label">{{ __('date of birth*') }}</label>
                    <input id="op_dob" type="text" class="form-control" name="op_dob" value="{{ $operator->op_dob }}" required autofocus>
                    
                    <p class="help-block"></p>
                    @if($errors->has('op_dob'))
                        <p class="help-block">
                            {{ $errors->first('op_dob') }}
                        </p>
                    @endif
                </div>

            </div>

            <div class="row">
                
                <div class="col-xs-6 form-group">
                    <label for="op_email" class="control-label">{{ __('Email*') }}</label>
                    <input id="op_email" type="text" class="form-control" name="op_email" value="{{ $operator->op_email }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_email'))
                        <p class="help-block">
                            {{ $errors->first('op_email') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="op_gender" class="control-label">{{ __('Gender*') }}</label>
                    <select id="op_gender" type="text" class="form-control" name="op_gender" value="{{ $operator->op_gender }}" required autofocus>

                        <option selected="selected">Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>

                    <p class="help-block"></p>
                    @if($errors->has('op_gender'))
                        <p class="help-block">
                            {{ $errors->first('op_gender') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- address => contains op_address_line_1, op_address_line_2, op_address_line_3 -->
                <div class="col-xs-12 form-group">
                    <label for="op_address_line_1" class="control-label">{{ __('Address 1*') }}</label>
                    <input id="op_address_line_1" type="text" class="form-control" name="op_address_line_1" value="{{ $operator->op_address_line_1 }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_address_line_1'))
                        <p class="help-block">
                            {{ $errors->first('op_address_line_1') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-12 form-group">
                    <label for="op_address_line_2" class="control-label">{{ __('Address 2*') }}</label>
                    <input id="op_address_line_2" type="text" class="form-control" name="op_address_line_2" value="{{ $operator->op_address_line_2 }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_address_line_2'))
                        <p class="help-block">
                            {{ $errors->first('op_address_line_2') }}
                        </p>
                    @endif
                </div>
                <div class="col-xs-12 form-group">
                    <label for="op_address_line_3" class="control-label">{{ __('Address 3*') }}</label>
                    <input id="op_address_line_3" type="text" class="form-control" name="op_address_line_3" value="{{ $operator->op_address_line_3 }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_address_line_3'))
                        <p class="help-block">
                            {{ $errors->first('op_address_line_3') }}
                        </p>
                    @endif
                </div>
               
            </div>

            <!-- adddress information 
            address => contains op_address_line_1, op_address_line_2, op_address_line_3
            <div class="col-xs-12 form-group"><b>Address Information</b></div>    
            -->

            <div class="row">
                <!--
                <div class="col-xs-3 form-group">
                    <label for="" class="control-label">{{ __('Location*') }}</label>
                    <input id="" type="text" class="form-control" name="" value="{{ $operator->op_email }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_email'))
                        <p class="help-block">
                            {{ $errors->first('op_email') }}
                        </p>
                    @endif
                </div>
                -->
                <div class="col-xs-4 form-group">
                    <label for="op_address_city" class="control-label">{{ __('City*') }}</label>
                    <input id="op_address_city" type="text" class="form-control" name="op_address_city" value="{{ $operator->op_address_city }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_address_city'))
                        <p class="help-block">
                            {{ $errors->first('op_address_city') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-4 form-group">
                    <label for="op_address_pin_code" class="control-label">{{ __('ZIP Code*') }}</label>
                    <input id="op_address_pin_code" type="text" class="form-control" name="op_address_pin_code" value="{{ $operator->op_address_pin_code }}" required autofocus>
                    
                    <p class="help-block"></p>
                    @if($errors->has('op_address_pin_code'))
                        <p class="help-block">
                            {{ $errors->first('op_address_pin_code') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-4 form-group">
                    <label for="op_address_state" class="control-label">{{ __('State*') }}</label>
                    <input id="op_address_state" type="text" class="form-control" name="op_address_state" value="{{ $operator->op_address_state }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('op_address_state'))
                        <p class="help-block">
                            {{ $errors->first('op_address_state') }}
                        </p>
                    @endif
                </div>

                

                
                
                <div class="col-xs-12 form-group">
                <center><button type="submit" class="btn btn-danger">
                {{ __('Update') }}
                </button></center>
                </div>
        </form>       
        </div>

        <!-- End adddress information -->
        <br>

        <!-- Driver  information -->
        
         <div class="row">
         <form method="POST" action="{{ route('Driver.update', [$operator->op_user_id]) }}">
            @method('PUT')
            @csrf
                <div class="col-xs-12 form-group"><b>Driver Information</b></div>    
                
                <div class="col-xs-6 form-group">
                    <label for="driver_first_name" class="control-label">{{ __('First Name*') }}</label>
                    <input id="driver_first_name" type="text" class="form-control" name="driver_first_name" value="{{ $operator->driver_first_name }}" required autofocus>
                    
                    <p class="help-block"></p>
                    @if($errors->has('driver_first_name'))
                        <p class="help-block">
                            {{ $errors->first('driver_first_name') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="driver_last_name" class="control-label">{{ __('Last Name*') }}</label>
                    <input id="driver_last_name" type="text" class="form-control" name="driver_last_name" value="{{ $operator->driver_last_name }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('driver_last_name'))
                        <p class="help-block">
                            {{ $errors->first('driver_last_name') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="driver_op_username" class="control-label">{{ __('Operator User Name*') }}</label>
                    <input id="driver_op_username" type="text" class="form-control" name="driver_op_username" value="{{ $operator->driver_op_username }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('driver_op_username'))
                        <p class="help-block">
                            {{ $errors->first('driver_op_username') }}
                        </p>
                    @endif
                </div>


                <div class="col-xs-6 form-group">
                    <label for="driver_mobile_number" class="control-label">{{ __('Mobile Number*') }}</label>
                    <input id="driver_mobile_number" type="text" class="form-control" name="driver_mobile_number" value="{{ $operator->driver_mobile_number }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('driver_mobile_number'))
                        <p class="help-block">
                            {{ $errors->first('driver_mobile_number') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="driver_last_location" class="control-label">{{ __('Last Location*') }}</label>
                    <input id="driver_last_location" type="text" class="form-control" name="driver_last_location" value="{{ $operator->driver_last_location }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('driver_last_location'))
                        <p class="help-block">
                            {{ $errors->first('driver_last_location') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="driver_is_online" class="control-label">{{ __('Driver Online*') }}</label>
                    <input id="driver_is_online" type="text" class="form-control" name="driver_is_online" value="{{ $operator->driver_is_online }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('driver_is_online'))
                        <p class="help-block">
                            {{ $errors->first('driver_is_online') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="is_active" class="control-label">{{ __('Driver Active*') }}</label>
                    <input id="is_active" type="text" class="form-control" name="is_active" value="{{ $operator->is_active }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('is_active'))
                        <p class="help-block">
                            {{ $errors->first('is_active') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="" class="control-label">{{ __('Driver Verifiy*') }}</label>
                    <input id="driver_is_verified" type="text" class="form-control" name="driver_is_verified" value="{{ $operator->driver_is_verified }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('driver_is_verified'))
                        <p class="help-block">
                            {{ $errors->first('driver_is_verified') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-6 form-group">
                    <label for="driver_profile_pic" class="control-label">{{ __('Upload Profile Photo*') }}</label>
                    <input id="driver_profile_pic" type="file" class="form-control" name="driver_profile_pic" value="{{ $operator->driver_profile_pic }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('driver_profile_pic'))
                        <p class="help-block">
                            {{ $errors->first('driver_profile_pic') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-12 form-group">
                <center><button type="submit" class="btn btn-danger">
                {{ __('Update') }}
                </button></center>
                </div>
         </form>
        </div>
        
        <!-- End Driver  information -->

        <!-- Vehicles  information -->
        

        <!-- end document information -->
            

        </div>


    </div>

   
   
    @endif
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection
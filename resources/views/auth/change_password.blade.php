@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        Change password
        <!--<small>(Vendors)</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">Change password</li>
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
    @endif
    <form method="POST" action="{{ route('auth.change_password') }}">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            Edit
        </div>

        <div class="panel-body">

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="current_password" class="control-label">{{ __('Current password*') }}</label>
                    <input id="current_password" type="password" class="form-control" name="current_password" value="{{ old('current_password') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('current_password'))
                        <p class="help-block text-red">
                            {{ $errors->first('current_password') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="new_password" class="control-label">{{ __('New password*') }}</label>
                    <input id="new_password" type="password" class="form-control" name="new_password" value="{{ old('new_password') }}" required autofocus>
                    <p class="help-block"></p>
                    @if($errors->has('new_password'))
                        <p class="help-block text-red">
                            {{ $errors->first('new_password') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <label for="new_password_confirmation" class="control-label">{{ __('New password confirmation*') }}</label>
                    <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" required>
                    <p class="help-block"></p>
                    @if($errors->has('new_password_confirmation'))
                        <p class="help-block text-red">
                            {{ $errors->first('new_password_confirmation') }}
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
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection
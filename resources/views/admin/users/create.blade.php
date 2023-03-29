<style type="text/css">
    .error{
        color: red !important;
    }
</style>
@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>
        Users
        <!--<small>(Vendors)</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">Users</li>
    </ol>
@endsection

<!-- Main Content -->
@section('content')
    <div class="panel-body p-0">
        <div class="view-op">
            <div class="row">
                <form method="POST" action="{{ route('users.store') }}">
                @csrf
                    <div class="col-sm-12 form-group section-title">Create</div>
                    <div class="section">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 form-group">
                                <label for="name" class="control-label">{{ __('Name*') }}</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                                <p class="help-block"></p>
                                @if($errors->has('name'))
                                    <p class="help-block">
                                        {{ $errors->first('name') }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-6 form-group">
                                <label for="email" class="control-label">{{ __('Email*') }}</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                <p class="help-block email-err hide error">
                                    Email already exists
                                </p>
                                @if($errors->has('email'))
                                    <p class="help-block error" id="error-msg">
                                        {{ $errors->first('email') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 form-group">
                                <label for="password" class="control-label">{{ __('Password*') }}</label>
                                <input id="password" type="password" class="form-control" name="password" required>
                                <p class="help-block"></p>
                                @if($errors->has('password'))
                                    <p class="help-block">
                                        {{ $errors->first('password') }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-6 form-group">
                                <label for="roles" class="control-label">{{ __('Role*') }}</label>
                                <!-- <select class="form-control select2" required="" name="roles">
                                    <option value="">Please select Role</option>
                                    @if (count($roles) > 0)
                                      @foreach ($roles as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                      @endforeach
                                    @endif
                                </select> -->
                                <select class="form-control select2" required="" name="roles[]" multiple="">
                                    <option value="">Please select Role</option>
                                    @if (count($roles) > 0)
                                      @foreach ($roles as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                      @endforeach
                                    @endif
                                </select>
                                <p class="help-block"></p>
                                @if($errors->has('roles'))
                                    <p class="help-block">
                                        {{ $errors->first('roles') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 form-group">
                                <div class="btn-b-u">
                                    <a href="{{ URL::previous()}}" class="btn btn-warning">Back</a>
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>    
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#email").keyup(function() {
            var email = $("#email").val();
            $("#error-msg").empty();
            $.ajax({
                url :"{{ route('checkEmail') }}",
                method:"POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "user_email": email
                },
                success : function(data){
                    if(data == 'true'){
                        $('.email-err').addClass('hide');
                    }else{
                        $('.email-err').removeClass('hide');
                    }
                }
            });
        });
    });
</script>
@endsection
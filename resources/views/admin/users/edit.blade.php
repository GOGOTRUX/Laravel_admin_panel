@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1>Users<!--<small>(Vendors)</small>-->	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Users</li>
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
	    <div class="panel-body p-0">
        	<div class="view-op">
            	<div class="row">
					<form method="POST" action="{{ route('users.update', [$user->id]) }}">
					@method('PUT')
					@csrf
						<div class="col-sm-12 form-group section-title">Edit</div>
	                        <div class="section">
								<div class="row">
									<div class="col-xs-12 col-sm-6 form-group">
										<label for="name" class="control-label">{{ __('Name*') }}</label>
										<input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>
										<p class="help-block"></p>
										@if($errors->has('name'))
											<p class="help-block">{{ $errors->first('name') }}</p>
										@endif
									</div>
									<div class="col-xs-12 col-sm-6 form-group">
										<label for="email" class="control-label">{{ __('Email*') }}</label>
										<input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required autofocus>
										<p class="help-block"></p>
										@if($errors->has('email'))
											<p class="help-block">
												{{ $errors->first('email') }}
											</p>
										@endif
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-6 form-group">
										<label for="password" class="control-label">{{ __('Password*') }}</label>
										<input id="password" type="password" class="form-control" name="password">
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
												<option value="{{ $role }}" @if($user->hasRole($role)) selected @endif >{{ $role }}</option>
											  @endforeach
											@endif
										</select> -->

										<select class="form-control select2" required="" name="roles[]" multiple="" >
											<option value="">Please select Role</option>
											@if (count($roles) > 0)
											  @foreach ($roles as $role)
												<option value="{{ $role }}" @if($user->hasRole($role)) selected @endif >{{ $role }}</option>
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
									<div class="form-group">
	                                    <div class="btn-b-u">
	                                        <a href="{{ URL::previous()}}" class="btn btn-warning">Back</a>
	                                        <button type="submit" class="btn btn-success">
	                                            {{ __('Update') }}
	                                        </button>
	                                    </div>
	                                </div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endif
@endsection
<!-- JS scripts for this page only -->
@section('javascript')
@endsection
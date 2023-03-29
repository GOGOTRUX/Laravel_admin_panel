@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Subscription Types
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

<form method="POST" action="{{ route('subscriptiontypes.store') }}" name="form1">    
	@csrf
	<div class="panel panel-default">
		<div class="panel-heading">
			Create Subscription Types
		</div>

		<div class="panel-body">
		 
		 <div class="row">
				<div class="col-xs-12 form-group">
					

					<label for="subscription_type_name" class="control-label">{{ __('Subscription Type Name*') }}</label>

					 <input id="subscription_type_name" type="text" class="form-control" name="subscription_type_name" value="{{ old('subscription_type_name') }}" required autofocus>
					<p class="help-block"></p>
					@if($errors->has('subscription_type_name'))
						<p class="help-block text-red">
							{{ $errors->first('subscription_type_name') }}
						</p>
					@endif
				</div>
			</div>
			<div class="row">
				 <div class="col-xs-12 form-group">
					

					<label for="subscription_created_by" class="control-label">{{ __('Subscription Created By*') }}</label>

					 <input id="subscription_created_by" type="text" class="form-control" name="subscription_created_by" value="{{ old('subscription_created_by') }}" required autofocus>
					
					

					<p class="help-block"></p>
					@if($errors->has('subscription_created_by'))
						<p class="help-block text-red">
							{{ $errors->first('subscription_created_by') }}
						</p>
					@endif
				</div>
			</div>


			<div class="row">
				<div class="col-xs-12 form-group">
					
				   <label for="is_active" class="control-label">Status *</label>
			<select id="is_active"  class="form-control" name="is_active"  autofocus value="{{ old('is_active') }}">                   
				   <option value="1">Active</option>
				   <option value="0">Deactive</option>
			</select>

				 <p class="help-block"></p>
					@if($errors->has('is_active'))
						<p class="help-block text-red">
							{{ $errors->first('is_active') }}
						</p>
					@endif
				   
				</div>
			</div>
	<a href="{{ URL::previous() }}" class="btn btn-success">Back</a>
	<button type="submit" class="btn btn-danger" onClick='submitDetailsForm()'>
		{{ __('Save') }}
	</button>        
			
			</div>           
	</div>

	
</form>

<!--        Ajax control-label -->

@endif
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection


@extends('layouts.app')

@section('content-header')
	<h1>
		DriverHome
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Edit</li>
	</ol>
@endsection

@section('content')
	<form method="POST" action="{{ route('driverhome.update', [$image->id]) }}" enctype="multipart/form-data">   
		@method('PUT')
		@csrf
		<div class="panel panel-default">
			<div class="panel-heading">
				Edit Home Banner Image
			</div>

			<div class="panel-body">
				<div class="row">
					<div class="col-md-6 form-group">
						<label class="control-label">Previous Image:</label>
						<img src = 'data:image/png;base64,{{ $b64image }}' width="80px" height="80px">
					</div>
				</div> 
				<div class="row">
					<div class="col-md-6 form-group">
		                <label for="home_banner_pic" class="control-label">New Banner Image</label>
		                <input id="home_banner_pic" type="file" class="form-control p-0" name="home_banner_pic" autofocus="" required>
		            </div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<a href="{{ URL::previous() }}" class="btn btn-success">Back</a>
		            	<button type="submit" class="btn btn-danger">Update</button>
		            </div>
		        </div>
			</div>
		</div>
	</form>
@endsection
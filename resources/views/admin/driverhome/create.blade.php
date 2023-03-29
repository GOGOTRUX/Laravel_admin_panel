@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Manage DriverHome
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">driverhome</li>
	</ol>
@endsection
 
@section('content')
	<form method="POST" id="addPlanForm" action="{{ route('driverhome.store') }}" enctype="multipart/form-data">  
		@csrf
		<div class="panel panel-default">
			<div class="panel-heading">
				Add Home Banner Images
			</div>

			<div class="panel-body">
				<div class="row">
					<div class="col-md-6 form-group">
		                <label for="home_banner_pic" class="control-label">Banner Photo</label>
		                <input id="home_banner_pic" type="file" class="form-control p-0 ggt-img" name="home_banner_pic" value="" autofocus="" required>
		            </div>
		        </div>
		        <div class="row">
		            <div class="col-md-6">
		            	<a href="{{ URL::previous() }}" class="btn btn-xs btn-success">Back</a>
		            	<button type="submit" class="btn btn-xs btn-danger">Upload</button>
		            </div>
		        </div>
			</div>
		</div>
	</form>
@endsection

@section('javascript')
<script language="javascript" type="text/javascript">

</script>

@endsection
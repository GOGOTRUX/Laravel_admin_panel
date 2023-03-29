@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Information
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">information</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success" id="message" style="display:none;">
				{{ session('success') }}
			</div>
		@endif
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					<p>
						<a href="{{ route('notification.create') }}" class="btn btn-xs btn-success">Add new</a>
					</p>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
<script language="javascript" type="text/javascript">

</script>

@endsection


@extends('layouts.app')
	@section('content-header')
		<h1>Dashboard</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-home"></i>Home</a></li>
		</ol>
	@endsection
	<!-- Main Content -->
	@section('content')
		<div class="row">
			<div class="col-sm-12 col-md-12 main-content">
				<span>Welcome To GOGOTRUX</span>
			</div>
		</div>
		
	@endsection
<!-- JS scripts for this page only -->
@section('javascript')
@endsection
<?php
/*
echo "<pre>";
print_r($subtypes);
print_r(dd($subtypes));
*/
?>
@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Subscription Types
		<!--<small>(Vendors)</small>-->
	</h1><!--</br>
	<p>
		<a href="{{ route('roles.create') }}" class="btn btn-success">Add new</a>
	</p>-->
	<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Subscription Types</li>
	</ol>
@endsection

<!-- Main Content -->
@section('content')
	
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<!-- page script -->
<script>
  $(function () {
	//$('#roles').DataTable()
  })
</script>

<script language="javascript" type="text/javascript">

</script>

@endsection


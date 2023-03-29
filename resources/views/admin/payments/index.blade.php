@extends('layouts.app')
<!-- Content Header (Page header) -->
<style type="text/css">
	@media print {
		a[href]:after {
			content: none !important;
		}
	}
</style>
@section('content-header')
	<h1>Payments</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Payments</li>
		<li class="active">Receive Payment</li>
	</ol>
@endsection
@section('content')
	<div class="col-xs-12">
		<div class="box">
			<div class="box-body">
				<div class="nav-tabs-custom tab_manage_price">
					<ul class="nav nav-tabs price_tab">
						<li class="{{$req_type == 'operator' ? 'active' : ''}}" id="tab_1"><a href="{{ url('payments/operator') }}" >operator Payment</a></li>
						<li class="{{$req_type == 'customer' ? 'active' : ''}}" id="tab_2"><a href="{{ url('payments/customer') }}">Customer Payment</a></li>      
					</ul>
					<div class="tab-content">
						@if($req_type == 'operator')
						<div class="tab-pane {{$req_type == 'operator' ? 'active' : ''}}" id="operator_payment">
							@include('admin.payments.operatorPayments.index')
						</div>
						@else
						<div class="tab-pane {{$req_type == 'customer' ? 'active' : ''}}" id="customer_payment">
							@include('admin.payments.customerPayments.index')	
						</div>
						@endif
					</div>
					<!-- /.tab-content -->
				</div>					
			</div>
		</div>
	</div>
@endsection
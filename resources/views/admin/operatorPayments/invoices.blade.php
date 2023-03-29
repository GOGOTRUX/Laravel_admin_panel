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
	<h1>Invoices</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Payments</li>
		<li class="active">Invoices</li>
	</ol>
@endsection
<!-- Main Content -->
@section('content')
	@if(session('success'))
		<!-- If password successfully show message -->
		<div class="row">
			<div class="alert alert-success" id="success-message">
				{{ session('success') }}
			</div>
		</div>
	@else
		<!-- action="{{ route('deposite/store') }}" -->
	@endif
	<div class="panel-body p-0">
		<div class="view-op">
			<div class="row">
				<div class="col-sm-12 section-title m-b-10">
					<div class="col-md-3 p-l-0">
						<select id="invoice" type="text" class="form-control all-caps" name="pay_invoices" autofocus onchange="changed_invoice_type()">
							<option value="inward">Inward Invoices</option>
							<option value="outward">Outward Invoices</option>
						</select>
					</div>
				</div>
				<div class="section p-t-10">
					<div class="row">
						<div class="col-md-9">
							<div class="ledger_date">
								<label>From</label>
								<div class="date">
									<input id="from_date" type="text" class="form-control date-picker" name="inward_from_date" value="">
								</div>
							</div>
							<div class="ledger_date text-right">
								<label>To</label>
								<div class="date">
									<input id="to_date" type="text" class="form-control date-picker" name="inward_to_date" value="">
								</div>
							</div>
						</div>
						<div class="col-md-3 text-right">
							<button class="btn btn_export">Export to Excel / PDF</button>
						</div>
					</div>
					<div class="table-responsive ledger">
						<div id="inward">
							<table id="inword_invoice" class="table table-list" data-page-length="25">
								<thead>
									<tr>
										<th>Date</th>
										<th>Time</th>
										<th>Transaction ID</th>
										<th>Driver ID</th>
										<th>Invoice No</th>
										<th>Base</th>
										<th>Loader</th>
										<th>Incidental</th>
										<th>Extension</th>
										<th>Others</th>
										<th>GST</th>
										<th>Total</th>
										<th>Status</th>
										<th>Flag <i class="fa fa-flag"></i></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id="outward" class="hide">
							<table id="outword_invoice" class="table table-list" data-page-length="25">
								<thead>
									<tr>
										<th>Date</th>
										<th>Time</th>
										<th>Transaction ID</th>
										<th>Cust ID</th>
										<th>Invoice No</th>
										<th>Base</th>
										<th>Loader</th>
										<th>Incidental</th>
										<th>Extension</th>
										<th>Others</th>
										<th>GST</th>
										<th>GGT</th>
										<th>Total</th>
										<th>Status</th>
										<th>Flag <i class="fa fa-flag"></i></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
<script>
	$('.datepicker').datepicker({
		dateFormat: 'dd/mm/y',
		todayBtn: "linked",
		clearBtn: true,
		changeMonth: true,
    	changeYear: true
	});
	$(function () {
		$('#inword_invoice').DataTable({
			'lengthChange': false
		})
		$('#outword_invoice').DataTable({
			'lengthChange': false
		})
	})
	$(document).ready(function() {
		changed_invoice_type();
	});
</script>
<script>
	function changed_invoice_type(){
		var invoice = $('#invoice').val();
		if(invoice == 'inward'){
			$('#outward').addClass('hide');
			$('#inward').removeClass('hide');	
		}else{
			$('#outward').removeClass('hide');
			$('#inward').addClass('hide');	
		}
	}
</script>
@endsection


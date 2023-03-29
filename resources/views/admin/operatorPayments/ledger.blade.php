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
	<h1 class="all-caps">Ledger</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Payments</li>
		<li class="active">Ledger</li>
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
				<div class="section p-t-10">
					<div class="row">
						<div class="col-md-9">
							<div class="ledger_date">
								<label>From</label>
								<div class="date">
									<input id="from_date" type="text" class="form-control date-picker" name="ledger_from_date" value="">
								</div>
							</div>
							<div class="ledger_date text-right">
								<label>To</label>
								<div class="date">
									<input id="to_date" type="text" class="form-control date-picker" name="ledger_to_date" value="">
								</div>
							</div>
						</div>
						<div class="col-md-3 text-right">
							<button class="btn btn_export">Export to Excel / PDF</button>
						</div>
					</div>
					<div class="table-responsive ledger">
						<table id="ledger" class="table table-list" data-page-length="25">
							<thead>
								<tr>
									<th>Date</th>
									<th>Time</th>
									<th>Party ID</th>
									<th>Transaction ID</th>
									<th>Detail</th>
									<th>Reference</th>
									<th>Debit</th>
									<th>Credit</th>
									<th>Balance</th>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td>11/1/19</td>
									<td>14:15:45</td>
									<td>D423441</td>
									<td>211132424</td>
									<td>subscription</td>
									<td>UTR938364323</td>
									<td></td>
									<td>5000</td>
									<td>5000</td>
								</tr>
							</tbody>
						</table>
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
		$('#ledger').DataTable({
			'paging'      : true,
			'lengthChange': false,
			'searching'   : true,
			'ordering'    : true,
			'info'        : true,
			'autoWidth'   : false
		})
	})
</script>
@endsection


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
	<h1 class="all-caps">Loyalty</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Subscription</li>
		<li class="active">Loyalty</li>
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
				<div class="loyalty_left">
					<div class="col-md-12 text-right m-b-10">
						<button class="btn btn_export">Export to Excel / PDF</button>
					</div>
					<div class="">
						<table id="loyalty" class="table table-list" data-page-length="25">
							<thead>
								<tr>
									<th>Date</th>
									<th>Time</th>
									<th>Cust ID</th>
									<th>Transaction ID</th>
									<th>Cashback</th>
									<th>Bonus</th>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td>11/1/19</td>
									<td>14:15:45</td>
									<td>C423441</td>
									<td>211132424</td>
									<td>2</td>
									<td>23</td>
								</tr>
							</tbody>
						</table>
					</div>							
				</div>
				<div class="loyalty_right">
					<div class="section p-t-10">
						<h4>Allocate Loyalty</h4>
						<div class="row">
							<div class="col-md-2">
								<label>Allocate</label>
								<select id="" type="text" class="form-control" value=""  name="" autofocus>
									<option value="">Cashback</option>
									<option value="0">Bonus</option>
								</select>
							</div>
							<div class="col-md-2">
								<label>Allocate to</label>
								<select id="" type="text" class="form-control" value=""  name="" autofocus>
									<option value="">All Cust</option>
									<option value="0">New Cust</option>
									<option value="1">VIP</option>
								</select>
							</div>
							<div class="l-col-1">
								<label>Allocate Only To</label>
								<textarea class="form-control" rows="2" placeholder="Enter Mobile Nos." spellcheck="false"></textarea>
							</div>
							<div class="l-col-2">
								<label class="blue">Define VIP</label>
								<input id="" type="text" class="form-control">
							</div>
							<div class="col-md-2">
								<label>&nbsp;</label>
								<select id="" type="text" class="form-control" value=""  name="" autofocus>
									<option value="">Trip Value</option>
									<option value="0">No. of Trips</option>
								</select>
							</div>
							<div class="col-md-2">
								<label>&nbsp;</label>
								<select id="" type="text" class="form-control" value=""  name="" autofocus>
									<option value="">Per Month</option>
									<option value="0">Per Week</option>
								</select>
							</div>
						</div>
						<div class="row create">
							<div class="create_box">
								<div class="col-md-6">
									<div class="m-t-20">
										<h5>Cash Back Creation</h5>
										<div class="l-flex">
											<div class="l-flex-col">
												<label>Assign Value (<i class="fa fa-inr"></i>)</label>
												<input id="" type="text" class="form-control">
											</div>
											<div class="l-flex-col">
												<label>Available From</label>
												<input id="" type="text" class="form-control date-picker">
											</div>
											<div class="l-flex-col">
												<label>Available Till</label>
												<input id="" type="text" class="form-control date-picker">
											</div>
										</div>
									</div>
									<div class="m-b-10">
										<h5>Cash Back Redemption</h5>
										<div class="l-flex">
											<div class="l-flex-col">
												<label>% of Trip Value</label>
												<input id="" type="text" class="form-control">
											</div>
											<div class="l-flex-col">
												<label>Available From</label>
												<input id="" type="text" class="form-control">
											</div>
											<div class="l-flex-col">
												<label>Available Till</label>
												<input id="" type="text" class="form-control">
											</div>
										</div>
									</div>
									<div class="text-center cash-m-t">
										<button class="btn btn-approv">Approval</button>
									</div>
								</div>
								<div class="col-md-6 bod-left">
									<div class="m-t-20">
										<h5>Bonus Point Creation</h5>
										<div class="l-flex">
											<div class="b-col">
												<label>1 Bonus Point =</label>
											</div>
											<div class="b-col-1">
												<input id="" type="text" class="form-control">
											</div>
											<div class="b-col-2"> 
												<select id="" type="text" class="form-control" value=""  name="" autofocus>
													<option value="">No of Trips</option>
													<option value="0">Trip Value</option>
													<option value="1">Others</option>
												</select>
											</div>
										</div>
									</div>
									<div class="m-b-10">
										<h5>Bonus Point Redemption</h5>
										<div class="l-flex">
											<div class="b-col">
												<label>100 Bonus Point =</label>
											</div>
											<div class="b-col-1">
												<input id="" type="text" class="form-control">
											</div>
											<div class="b-col-2">
												<select id="" type="text" class="form-control" value=""  name="" autofocus>
													<option value="">Rs</option>
												</select>
											</div>
										</div>
									</div>
									<div class="l-flex m-b-10">
										<div class="l-flex-col">
											<label>% of Trip Value</label>
											<input id="" type="text" class="form-control">
										</div>
										<div class="l-flex-col">
											<label>Available From</label>
											<input id="" type="text" class="form-control">
										</div>
										<div class="l-flex-col">
											<label>Available Till</label>
											<input id="" type="text" class="form-control">
										</div>
									</div>
									<div class="text-center m-t-10">
										<button class="btn btn-approv">Approval</button>
									</div>
								</div>
							</div>
						</div>
						<div class="view_loyalty">
							<div class="table-responsive">
								<table class="table table-list">
									<thead>
										<tr>
											<th>Logic ID</th>
											<th>Variable</th>
											<th>Existing Value</th>
											<th>New Value</th>
											<th>Revision Date</th>
											<th>Created By</th>
											<th>Approval Date</th>
											<th>Approved By</th>
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
										</tr>
									</tbody>
								</table>
							</div>
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
		$('#loyalty').DataTable({
			'paging'      : true,
			'lengthChange': false,
			'searching'   : true,
			'ordering'    : true,
			'info'        : false,
			'autowidth'	  : true
		})
	})
</script>
@endsection


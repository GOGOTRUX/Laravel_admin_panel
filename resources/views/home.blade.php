@extends('layouts.app')
	<!-- Content Header (Page header) -->
	@section('content-header')
		<h1>Dashboard<!--<small></small>--></h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		</ol>
	@endsection

	<!-- Main Content -->
	@section('content')
		<div class="row">
			<div class="col-sm-10 col-md-10 main-content">
				<!-- second div tag -->
				<div class="row">
					<div class="col-xs-12">			
						<div class="col-lg-3 col-md-3">
							<!-- small box -->
							<div class="small-box bg-aqua">
								<div class="icon">
									<canvas id="dOnline" style="max-width:160px;"></canvas>
								</div>
								<div class="inner">
									<h3>{{ $online_operators }}</h3>
									<p>Operators Online</p>
								</div>						
							</div>
						</div>

						<!-- ./col -->
						<div class="col-lg-3 col-md-3">
							<!-- small box -->
							<div class="small-box bg-green">
								<div class="icon">
									<canvas id="cOnline" style="max-width:160px;"></canvas>
								</div>						
								<div class="inner">
									<h3>{{ $active_customers }}</h3>
									<p>Customers Verified</p>
								</div>
							</div>
						</div>
						
						<!-- ./col -->
						<div class="col-lg-3 col-md-3">
							<!-- small box -->
							<div class="small-box bg-yellow">
								<div class="icon area">
									<canvas id="tBooking" style="min-width: 190px; min-height: 100px;"></canvas>
								</div>								
								<div class="inner">
									<h3>{{ $total_bookings }}</h3>
									<p>Total Booking Nos &amp; revenue</p>
								</div>
							</div>
						</div>

						<!-- ./col -->
						<div class="col-lg-3 col-md-3">
							<!-- small box -->
							<div class="small-box bg-red">
								<div class="icon bar">
									<canvas id="booking" style="max-width: 180px; min-height: 100px;"></canvas>
								</div>						
								<div class="inner">
									<h3>{{ $total_bookings }}</h3>
									<p>Total Bookings</p>
								</div>
							</div>
						</div>        
						<!-- ./col -->								
					</div>
				</div>
				<!-- end second div tag -->
				
				<!-- first div -->
				<div class="panel trafic-pan">
					<div class="panel-header">
						<div class="left-p-header">
							<h3 class="panel-title">Traffic</h3>
							<span>January 2019</span>
						</div>
						<div class="right-p-header">
						 	<label>Choose Option</label>
						 	<select id="category_faq">
					             <option value="1">Days</option>
					             <option value="2">Months</option>
					             <option value="3">Years</option>
					        </select>
				        </div>
					</div>
					<div class="panel-body">							
						<div class="views-row-1 hid"> <canvas id="chart1" height="150" style="max-height: 140px;"></canvas></div>
							<!-- Hello {{ Auth::user()->name }}, You are logged in! -->
					</div>
					<div class="box-footer">
						<div class="row">
							<div class="box-1">
								<div class="description-block border-right">	
									<span class="description-text">Visits</span>
									<h5 class="description-header">0 Users (0%)</h5>
								</div>
								<div class="progress">
									<div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
										<span class="sr-only">0% Complete (success)</span>
									</div>
								</div>
							<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="box-1">
								<div class="description-block border-right">
									<span class="description-text">Unique</span>
									<h5 class="description-header">0 Unique Users(0%)</h5>
								</div>
								<div class="progress">
									<div class="progress-bar progress-bar-aqua" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
										<span class="sr-only">0% Complete</span>
									</div>
								</div>
						  <!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="box-1">
								<div class="description-block border-right">
									<span class="description-text">Page Views</span>
									<h5 class="description-header">0 Views (0%)</h5>
								</div>
								<div class="progress">
									<div class="progress-bar progress-bar-yellow" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
										<span class="sr-only">0% Complete (warning)</span>
									</div>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="box-1">
								<div class="description-block border-right">
									<span class="description-text">New Users</span>
									<h5 class="description-header">0 Users(0%)</h5>
								</div>
								<div class="progress">
									<div class="progress-bar progress-bar-red" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
										<span class="sr-only">0% Complete</span>
									</div>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="box-1">
								<div class="description-block">
									<span class="description-text">Bounce Rate</span>
									<h5 class="description-header">0%</h5>
								</div>
								<div class="progress">
									<div class="progress-bar progress-bar-aqua" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
										<span class="sr-only">0% Complete</span>
									</div>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
						</div>					
					</div>
				</div>
				<!-- end first div tag -->
				<div class="row">
					<div class="col-xs-12">
						<div class="col-sm-3">
							<div class="social-box">
								<div class="graph fb">
									<div class="graph-inner">
										<canvas id="fb-chart" style="height: 50px; width: 190px;"></canvas>
									</div>
									<div class="graph-txt">
										<span class="fa fa-facebook"></span>
									</div>
								</div>
								<div class="count">
									<div class="like">0</div>
									<div class="share">0</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="social-box">
								<div class="graph twit">
									<div class="graph-inner">
										<canvas id="twit-chart" style="height: 50px; min-width: 192px;"></canvas>
									</div>
									<div class="graph-txt">
										<span class="fa fa-twitter"></span>
									</div>
								</div>
								<div class="count">
									<div class="like">0</div>
									<div class="share">0</div>
								</div>
							</div>	
						</div>
						<div class="col-sm-3">
							<div class="social-box">
								<div class="graph linked">
									<div class="graph-inner">
										<canvas id="linked-chart" style="height: 50px; width: 190px;"></canvas>
									</div>
									<div class="graph-txt">
										<span class="fa fa-linkedin"></span>
									</div>
								</div>
								<div class="count">
									<div class="like">0</div>
									<div class="share">0</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="social-box">
								<div class="graph google">
									<div class="graph-inner">
										<canvas id="google-chart" style="height: 50px; min-width: 192px;"></canvas>
									</div>
									<div class="graph-txt">
										<span class="fa fa-google-plus"></span>
									</div>
								</div>
								<div class="count">
									<div class="like">0</div>
									<div class="share">0</div>
								</div>
							</div>
						</div>
					</div>
				</div> 				
			</div>
			<div class="col-sm-2 col-md-2">
				<aside class="right-sidebar">
					<h4>Add More</h4>
					<ul>
						<li>
							<a>
								<i class="fa fa-plus"></i><span>Total Revenue</span>
							</a>
						</li>
						<li>
							<a>
								<i class="fa fa-plus"></i><span>Cash Vs digital</span>
							</a>
						</li>
						<li>
							<a>
								<i class="fa fa-plus"></i><span>Total GST collected</span>
							</a>
						</li>
						<li>
							<a>
								<i class="fa fa-plus"></i><span>Total platform charge</span>
							</a>
						</li>
						<li>
							<a>
								<i class="fa fa-plus"></i><span>Total Subscription charges</span>
							</a>
						</li>
					</ul>
				</aside>
			</div>
		</div>
		
	@endsection
<!-- JS scripts for this page only -->
@section('javascript')
<script src="{{ url('js/home-chart.js')}}"></script>
@endsection
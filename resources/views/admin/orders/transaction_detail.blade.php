@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
	<h1 class="text-center">Transaction Detail</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Trips</li>
		<li class="active">Transaction Detail</li>
	</ol>
@endsection

<!-- Main Content -->
@section('content')
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success" id="message" style="display:none;">
				{{ session('success') }}
			</div>
		@endif
		<div class="panel-body">
			<div class="row trip_details">
				<div class="col-md-6">
					<div class="col-trip_details">
						<table>	
							<tr>
								<th>Transaction ID</th><td>{{$tripdetail->trip_transaction_id}}</td>
								<th>Trip Date</th><td>{{$tripdetail->date}}</td>
							</tr>
							<tr>
								<th>Customer ID</th><td>
								<th>Customer Name</th><td>{{$tripdetail->user_first_name}} {{$tripdetail->user_middle_name}} {{$tripdetail->user_last_name}}</td>	
							</tr>
							<tr>
								<th>Pick-up Location</th><td>{{$tripdetail->start_address_line_1}} {{$tripdetail->start_address_line_2}} {{$tripdetail->start_address_line_3}} {{$tripdetail->start_address_line_4}} </td>
							</tr>
							<tr>
								<th>Pick-up Time</th><td>{{$tripdetail->time}}</td>
								<th>Loaders</th><td>{{$tripdetail->loader_count}}</td>
							</tr>
							<tr>
								<th>Material</th><td>{{$tripdetail->material_type}}</td>
								<th>Weight</th><td>{{$tripdetail->weight}}</td>
							</tr>
							<tr>
								<th>MT ID</th><td>{{$tripdetail->veh_code}}</td>
								<th>MT Make</th><td>{{$tripdetail->veh_make_model_type}}</td>
							</tr>
							<tr>
								<th>MT Model</th><td>{{$tripdetail->veh_model_name}}</td>
								<th>Reg.No.</th><td>{{$tripdetail->veh_registration_no}}</td>
							</tr>
							<tr>
								<th>Bid/Enquiry</th><td>{{$tripdetail->is_bid}}</td>
							</tr>
							<tr>
								<th>Trip Charges</th>
								<td>
									<span><b>Base</b> : {{$tripdetail->base_amount}}</span><br>
									<span><b>Incidental</b></span><br>
									<span><b>GST</b></span>
								</td>
								<td>
									<span><b>Loader</b> : {{$tripdetail->loader_price}}</span><br>
									<span><b>Surcharge</b></span><br>
									<span><b>Total</b> : {{$tripdetail->actual_amount}}</span>
								</td>
								<td>
									<span><b>Extension</b></span><br>
									<span><b>Others</b></span>
								</td>
							</tr>
							<tr>
								<th>Payment Mode</th><td>{{$tripdetail->user_order_pay_mode}}</td>
								<th>Payment Status</th><td>{{$tripdetail->user_order_status}}</td>
							</tr>
							<tr>
								<th>Payment from DP</th><td></td>
								<th>Payment to DP</th><td></td>
							</tr>
							<tr>
								<th>Driver Name</th><td>{{$tripdetail->driver_first_name}} {{$tripdetail->driver_last_name}}</td>
		
							</tr>
							<tr>
								<th>Rating</th><td></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="col-md-1 p-0">
					<div class="receiv_photo">
						<h5>Receiver's Photo</h5>
						<div class="m-b-10">
							<ul class="enlarge">
	                       	<?php $count = 0;?>
	                            @foreach ($tripdetail->destination as $image)
	                            <?php $count +=1;?>
	                            <li>
	                            		@if(!empty($image['material']))
	                                    <img src='data:image/png;base64,{{ $image['material'] }}' width="50px" height="40px" alt="GoGoTRux" />
	                                    <span>
	                                    <img src='data:image/png;base64,{{ $image['material'] }}' alt="GoGoTRux" />
	                                    </span><br>
	                                    Destination {{$count}} Material Image
	                                    @else
											<img src='' width="50px" height="40px" alt="GoGoTRux" />
											<span>
											<img src='' alt="GoGoTRux" />
											</span><br>
											Destination {{$count}} Material Image
	                                    @endif
	                            </li>
	                            <li>
	                            		@if(!empty($image['building_image']))
	                                    <img src='data:image/png;base64,{{ $image['building_image'] }}' width="50px" height="40px" alt="GoGoTRux"/>
	                                    <span>
	                                    <img src='data:image/png;base64,{{ $image['building_image'] }}' alt="GoGoTRux"/>
	                                    <img src="" alt="GoGoTRux" />
	                                    </span><br>
	                                    Destination {{$count}} Building Image
	                                    @else
											<img src='' width="50px" height="40px" alt="GoGoTRux" />
											<span>
											<img src='' alt="GoGoTRux" />
											</span><br>
											Destination {{$count}} Building Image
	                                    @endif
	                            </li>
	                            <li>
	                            		@if(!empty($image['receiver_image']))
	                                    <img src='data:image/png;base64,{{ $image['receiver_image'] }}' width="50px" height="40px" alt="GoGoTRux"/>
	                                    <span>
	                                    <img src='data:image/png;base64,{{ $image['receiver_image'] }}' alt="GoGoTRux"/>
	                                    </span><br>
	                                    Destination {{$count}} Receiver Image
	                                    @else
											<img src='' width="50px" height="40px" alt="GoGoTRux" />
											<span>
											<img src='' alt="GoGoTRux" />
											</span><br>
											Destination {{$count}} Receiver Image
	                                    @endif
	                            </li>

	                            @endforeach
                			</ul>
						</div>
					</div>
				</div>
			<div class="col-md-5">
				<div class="col-trip_details">
					<div class="row add_details">
						<div class="col-md-6">
							<div class="dest">
							    <?php $count = 0;?>
							    @foreach ($receiverdetail as $key => $value)                             
								<table>
								    <?php $count +=1;?>
						            <h5>Destination {{$count}}</h5>
						            <tr><th>Address</th><td>
										{{$value['dest_address_line_1']}}
										{{$value['dest_address_line_2']}} 
										{{$value['dest_address_line_3']}}
										{{$value['dest_address_line_4']}}
						            </td></tr>
						            <tr><th>Receiver</th><td>{{$value['rec_name']}}</td></tr>
						            <tr><th>Contact</th><td>{{$value['rec_phone_no']}}</td></tr>
						            <tr><th>Delivery Time</th><td>{{$value['delivery_time']}}</td></tr>
						            <tr><th>Delivery Remark</th><td>{{$value['remark']}}</td></tr>
								</table>
								@endforeach
							</div>
						</div>
						<div class="col-md-6">
							<div class="extension">
								 <?php $count = 0;?>
									<table>
										<?php $count +=1;?>
										<h5 class="all-caps">Trip Extension {{$count}}</h5>
										<tr><th>Destination {{$count}}</th><td>
											{{$tripdetail->dest_address_line_1}}
										</td></tr>
										<tr><th>Start Time</th><td>{{$tripdetail->time}}</td></tr>
										<tr><th>Delivery Time</th><td></td></tr>
										<!--<tr><th>Trip Charges</th><td>{{$tripdetail->user_order_amount}}</td></tr>-->
										<tr><th>Trip Charges</th><td>{{ round($tripdetail->user_order_amount, 2) }}</td></tr>
										<tr><th>Payment Mode</th><td>{{$tripdetail->user_order_pay_mode}}</td></tr>
										<tr><th>Payment Status</th><td>{{$tripdetail->user_order_status}}</td></tr>
										<tr><th>Receiver's Name</th><td>{{$tripdetail->user_first_name}} {{$tripdetail->user_middle_name}} {{$tripdetail->user_last_name}}</td></tr>
										<tr><th>Receiver's Contact</th><td>{{$tripdetail->driver_mobile_number}}</td></tr>
										<tr><th>Payment to DP</th><td></td></tr>
										<tr><th>Payment from DP</th><td></td></tr>
									</table>
							</div>
						</div> 
					</div>				
				</div>
			</div>
			</div>
		</div>
				
	</div>
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<!-- page script -->
<script>

	// $(function () {
	// 	$('#orders').DataTable({
	// 		'paging'      : true,
	// 		'lengthChange': false,
	// 		'searching'   : true,
	// 		'ordering'    : true,
	// 		'info'        : false,
	// 		'autowidth'	  : true
	// 	})
	// })
</script>

@endsection

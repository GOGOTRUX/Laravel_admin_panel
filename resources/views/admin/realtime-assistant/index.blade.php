
@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>

<!-- Content Header (Page header) -->
@section('content-header')
	<h1></h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Trips</li>
		<li class="active">Real Time Assistance Panel</li>
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
			<div class="view-trip">
				<div class="section-title">
					<h3 class="m-0">Real Time Assistance Panel</h3>
					<audio style="display: none;" id="real_time_audio" src="{{ asset('sound/notification-sound.mp3') }}" ></audio>
				</div>
				<div class="trip_section p-10">
					<div class="trip_info m-0">
						<div class="table-responsive m-t-10">
							<table id="realtime" class="rt_table table" style="width:100%">
					        <thead>
					            <tr>
					            	<th>Order PlacedAt</th>
									<th>Search ID</th>
									<th>Date</th>
									<th>Time</th>
									<th>Name</th>
									<th>Mobile No.</th>
									<th>Location</th>
									<th>From</th>
									<th>To</th>
									<th>Enq Type</th>
									<th>MTs</th>
									<th>Locate Mts</th>
									<th class="txt_red">MT 1</th>
									<th class="txt_red">MT 2</th>
									<th class="txt_red">MT 3</th>
									<th class="txt_red">MT 4</th>
									<th class="txt_red">MT 5</th>
									<th class="txt_red">MT 6</th>
									<th>Notify All</th>
									<th>Bid Sent</th>
									<th>Bid Accepted</th>
									<th>Payment</th>
									<th>Order Status</th>
								</tr>
					        </thead>
					    </table>
						</div>
					</div>
				</div>
			</div>
		</div>
				
	</div>
	<!-- Cancel trip modal -->
	<div class="modal fade" id="search_details">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true" class="fa fa-times"></span></button>
					<h4 class="modal-title">Search Detail</h4>
				</div>
				<div class="modal-body">
					
				</div>
			</div>
		</div>
	</div>
	<!-- Cancel trip modal end -->
	<!-- MT Details modal -->
	<div class="modal fade" id="mt_details">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true" class="fa fa-times"></span></button>
					<h4 class="modal-title">MT Detail</h4>
				</div>
				<div class="modal-body">
					<table>
						<tr>
							<th>Name</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Mobile No.</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Present Location</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Pickup Point Distance</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Truck Model</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Capacity</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Min Charges</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Loader Charges</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Subscription</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Subscription Balance</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Approx Tariff for Trip</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<th>Base Location</th>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td>
								<button class="btn btn-info">Send Enquiry</button>
							</td>
							<td class="text-right">
								<button class="btn btn-warning">Send BID</button>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- MT Details modal end -->
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

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
<script>       
		
		$( document ).ready(function()
		{	
			var setintervaltime = 60000;
			var table = $('#realtime').DataTable( {
			    "processing": true,
			    "serverSide": true,
			    "ajax": 
				{
            "url" : "realtimeAjaxData",
            "dataSrc": function ( json ) {
                //Make your callback herei.
		console.log('json here',json);
		 console.log('realtime',json.recordsTotal);
		
		var preLng = window.localStorage.getItem('previousLng');
	console.log(preLng);	
		if(json.recordsTotal > preLng){
			var sound = document.getElementById("real_time_audio");
			sound.play();
			loadData(0);
		}
		window.localStorage.setItem('previousLng', json.recordsTotal);
                return json.data;
            }       
            },
			    "columns": [
			    	{ "data": "OrderCreatedat" },
			       	{ "data": "searchid" },
					{ "data": "order_date" },
					{ "data": "order_time" },
					{ "data": "username" },
					{ "data": "mobile" },
					{ "data": "user_booked_location" },
					{ "data": "from" },
					{ "data": "to" },
					{ "data": "Bid" }, 
					{ "data": "MTs" },
					{ "data": "LocateMts" },
					{ "data": "MT1" },
					{ "data": "MT2" },
					{ "data": "MT3" },
					{ "data": "MT4" },
					{ "data": "MT5" },
					{ "data": "MT6" },
					{ "data": "NotifyAll" },
					{ "data": "BidSent" },
					{ "data": "BidAccepted" },
					{ "data": "Payment" },
					{ "data": "OrderStatus" },					
        		]
			} );
			$('#message').fadeIn('slow', function()
			{
				$('#message').delay(1000).fadeOut(); 
			});
	
			camelize = function camelize(str) {
				return str.replace(/\W+(.)/g, function(match, chr)
				{
						return chr.toUpperCase();
				});
			}
			
			setInterval(realtimecall, setintervaltime);
		});

		function realtimecall()
		{
			$('#realtime').DataTable().ajax.reload();
		}

		function showCustomerDetail(searchId)
		{
			// alert('1');
			var token = "{{csrf_token()}}";
			$.ajax({
				headers: {'X-CSRF-TOKEN':token},
				type: "POST",
				url: "/showTripDetail",
				data: {
					"searchId": searchId,
				},
				dataType: 'json',
				async: false,
				success: function(response)
				{
					console.log(response);
					var res='';
					var opDetails = '';
					$.each (response, function (key, value) {
						if(value.opdetails){
						$.each(value.opdetails, function (key, value) {
							opDetails += value.op_first_name + ' ' + value.op_last_name + ' ' + value.op_uid + ',';
							//opDetails += '<li>'+value.op_first_name + ' ' + value.op_last_name + ' ' + value.op_uid +'<li>';
						});
						}else{
							opDetails = 'NA';
						}
						
						var bid = value.is_bid;
						if(bid == '0'){
						var	Bid="ENQ"
						}
						else{
						var	Bid = "BID"
						}
						res += '<div class="row"><div class="col-md-10"><table id="user_search_detail">'+
						'<tr><th>Booking Send To</th><td>' +opDetails+'</td></tr><tr><th>Location</th><td>'+value.user_booked_location+'</td></tr><tr><th>Date</th><td>'+value.order_date+'</td></tr><tr><th>Time</th><td>'+value.order_time+'</td></tr><tr><th>From</th><td>'+value.from+'</td></tr><tr><th>To</th><td>'+value.to+'</td></tr><tr><th>Material</th><td>'+value.material_type+'</td></tr><tr><th>Weight</th><td>'+value.weight+'</td></tr><tr><th>Vehicle Type</th><td>'+value.vehicle_type+'</td></tr><tr><th>Loaders</th><td>'+value.loader_count+'</td></tr><tr><th>Premium</th><td>-</td></tr><tr><th>Balance</th><td>-</td></tr><tr><th>Payment</th><td>'+value.payment_type+'</td></tr><tr><th>Distance</th><td>-</td></tr><tr><th>Approx Tariff</th><td>-</td></tr></table></div><div class="col-md-2"><div class="last-col"><button class="time">00:26</button><button class="bid">'+Bid+'</button></div></div></div>';
								});	
					// console.log(res);
					$('.modal-body').html(res);
					// console.log(response);
					
				}
			});
		}

	/*-----------------------goto add trip start--------------------*/
	function gotoAddTrip(id){
		$.ajax({
			url :"{{ route('add-trip-edit-from-realtime') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"id":id
			},
			success : function(data)
			{
				if(data.response.status == 'success'){
					window.open('{{ route("add-trip") }}','_blank');
				}
			}
		});
	}
	/*-----------------------goto add trip end----------------------*/

</script>
@endsection

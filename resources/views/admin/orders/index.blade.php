@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<!-- Content Header (Page header) -->
@section('content-header')
	<h1></h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Trips</li>
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
					<div class="row">
						<div class="col-md-2">
							<h2>Trips</h2>
						</div>
						<div class="col-md-10">
							<h5>Total Booked Trips<span>000</span></h5>
						</div>
					</div>
				</div>
				<div class="trip_section">
					<div class="row trip_top">
						<div class="col-md-2">
							<select id="tripType" type="text" class="form-control" value=""  name="" autofocus>
								<option value="all">All Trips</option>
								<option value="0">Booked Trips</option>
								<option value="1">Cancelled Trips</option>
								<option value="2">Rejected Trips</option>
								<option value="3">Closed Trips</option>
								<option value="4">Disputed Trips</option>
							</select>
						</div>
						<div class="col-md-2 text-center">
							<a href="{{ url('add-trip') }}" class="btn add_trip">Add Trip</a> 
							<!--<a href="#" class="btn add_trip">Add Trip</a>
						</div>
						<div class="col-md-4">
							<!-- <div class="has-feedback"> -->
							<!-- <div class="input-group"> -->
								<!-- <label>Search</label> -->
								<!-- <input class="form-control" data-placeholder="Search" id=""> -->
								<!-- <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span> -->
							<!-- </div> -->
						</div>
						<div class="col-md-1">
						</div>
						<div class="col-md-3">
							<select id="show_coloum" type="text" class="form-control all-caps" value=""  name="" autofocus>
								<option value="">Add Column master</option>
							</select>
						</div>
					</div>
					<div class="trip_info">
						<div class="table-responsive">
							<table id="orders" class="table table-list" style="width:100%">
								<thead>
									<tr>
										<th id="hidecolumn1">Trip ID <i data-thid="th1" id="hidecolumn1" class="fa fa-times close cross_html"></i></th>
<th id="hidecolumn14">Cust Name <i data-thid="th14" id="hidecolumn14" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn2">Cust Mobile <i data-thid="th2" id="hidecolumn2" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn3">MT ID <i data-thid="th3" id="hidecolumn3" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn4">Driver Name <i data-thid="th4" id="hidecolumn4" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn5">Trip Date <i data-thid="th5" id="hidecolumn5" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn6">Pick-Up Time <i data-thid="th6" id="hidecolumn6" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn7">Value (Rs.) <i data-thid="th7" id="hidecolumn7" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn8">Payment Mode <i data-thid="th8" id="hidecolumn8" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn9">Payment <i data-thid="th9" id="hidecolumn9" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn10">Trip Status <i data-thid="th10" id="hidecolumn10" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn11">  <i data-thid="th11" id="hidecolumn11" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn12">Dvr <i data-thid="th12" id="hidecolumn12" class="fa fa-times close cross_html"></i></th>
										<th id="hidecolumn13">Serv <i data-thid="th13" id="hidecolumn13" class="fa fa-times close cross_html"><i></th>
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
	<form method="POST" id="tripCancelForm" name="trip_cancel_form">
	<div class="modal fade trip_cancel" id="cancel_trip">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button> -->
					<h4 class="modal-title">Do You Want to Cancel the Booking?</h4>
				</div>
				
					<div class="modal-body" >
						<div class="txt">As Requested By</div>
						<div class="pem">
							<div class="pem_check">
								<input id="box1" type="checkbox" name="checkbox" value="Driver"/>
	  							<label for="box1">Driver Partner</label>
							</div>
							<div class="pem_check text-right">
								<input id="box2" type="checkbox" name="checkbox" value="Customer">
								<label for="box2">Customer</label>
							</div>
						</div>
						<div class="reason">
							<input type="text" class="form-control" name="reason"placeholder="Enter Reason for Cancellation" value="">
						</div>
						<div class="pem">
							<!-- <a class="yes_btn">Yes</a> -->
							<!--<a class="yes_btn" data-dismiss="modal">Yes</a>-->
						        <button type="submit" class="btn btn-danger">Yes</button>
							<a class="no_btn" data-dismiss="modal" aria-label="Close">No</a>
						</div>
					</div>
			</div>
		</div>
	</div>
	</form>
	<!-- Cancel trip modal end -->


@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<!-- page script -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="https://code.jquery.com/jquery-3.3.1.js"></script>
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
			var table = $('#orders').DataTable( {
			    "processing": true,
			    "serverSide": true,
			    "ajax": "ordersAjaxData",
			    "columns": [
					{ "data": "tripId" },
{ "data": "customername" },
					{ "data": "username" },
					{ "data": "MTS" },
					{ "data": "drivername" }, 
					{ "data": "date" },
					{ "data": "time" },
					{ "data": "user_order_amount" },
					{ "data": "user_order_pay_mode" },
					{ "data": "user_order_status" },
					{ "data": "rideStatus" },
					{ "data": "action" },  
					{ "data": "Dvr" },
					{ "data": "Serv" },					
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
			//setInterval(realtimecall, setintervaltime);
    	});

	function realtimecall()
	{
		$('#orders').DataTable().ajax.reload();
	}

	function openModel(id){
		$("#cancel_trip").modal({
		  keyboard: false,
		  backdrop: 'static'
		});
		
		//form validations start
		$("#tripCancelForm").validate({
			rules: {
				checkbox: {
					required: true,
				},
				reason: {
					required: true,
				}
			},  
			messages: {
				checkbox : {
					required:"Please select cancel by",
				},
				reason:{
					required:"Please enter reason",
				} 
			},
			submitHandler: function(form) {
				//e.preventDefault();
				var data = $("#tripCancelForm").serialize();
				
				$.ajax({
					url :"{{ route('updatedisputed') }}",
					method:"POST",
					data: {
						"_token": "{{ csrf_token() }}",
						"data":$("#tripCancelForm").serialize(),
						"tripTransactionId":id
					},
					success : function(data)
					{
						var result = JSON.parse(data);
						if(result.status == 'success'){
							swal({title: "Trip Cancelled!", text: "Trip cancelled successfully.", type: result.status}).
							then(function()
							{ 
								location.reload();
							});
						}
						else{
							swal({title: "Oops!", text: "Something went wrong.", type: result.status}).then(function()
							{
								location.reload();
							});
						}
					}
				});
	        	return false;
			}
		});
    	//form validations end here
	/*	var data = $("form").serialize();
		event.preventDefault();
		$.ajax({
			url :"{{ route('updatedisputed') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"data":data,
				"tripTransactionId":id
			},
			success : function(data)
			{
				var result = JSON.parse(data);
				if(result.status == 'success'){
					swal({title: "Disputed updated!", text: "Your Disputed has been Updated.", type: result.status}).
					then(function()
					{ 
						location.reload();
					});
				}
				else{
					swal({title: "Oops!", text: "Something went wrong.", type: result.status}).then(function()
					{
						location.reload();
					});
				}
			}
		});*/
	
	}

	function coloumnHeading(){
		var htmlcolheading='<thead><tr><th id="hidecolumn1">Trip ID <i data-thid="th1" id="hidecolumn1" class="fa fa-times close cross"></i></th><th id="hidecolumn2">Cust Name <i data-thid="th2" id="hidecolumn2" class="fa fa-times close cross"></i></th><th id="hidecolumn3">MT ID <i data-thid="th3" id="hidecolumn3" class="fa fa-times close cross"></i></th><th id="hidecolumn4">Driver Name <i data-thid="th4" id="hidecolumn4" class="fa fa-times close cross"></i></th><th id="hidecolumn5">Trip Date <i data-thid="th5" id="hidecolumn5" class="fa fa-times close cross"></i></th><th id="hidecolumn6">Pick-Up Time <i data-thid="th6" id="hidecolumn6" class="fa fa-times close cross"></i></th><th id="hidecolumn7">Value (Rs.) <i data-thid="th7" id="hidecolumn7" class="fa fa-times close cross"></i></th><th id="hidecolumn8">Payment Mode <i data-thid="th8" id="hidecolumn8" class="fa fa-times close cross"></i></th><th id="hidecolumn9">Payment <i data-thid="th9" id="hidecolumn9" class="fa fa-times close cross"></i></th><th id="hidecolumn10">Trip Status <i data-thid="th10" id="hidecolumn10" class="fa fa-times close cross"></i></th><th id="hidecolumn11"> <i data-thid="th11" id="hidecolumn11" class="fa fa-times close cross"></i></th><th id="hidecolumn12">Dvr <i data-thid="th12" id="hidecolumn12" class="fa fa-times close cross"></i></th><th id="hidecolumn13">Serv <i data-thid="th13" id="hidecolumn13" class="fa fa-times close cross"></i></th></tr></thead>';
		return htmlcolheading;
	}
	function coloumnData(value){
		if(value == "null"){
			var htmldata = '<tr><td colspan="12">No Data is available for Disputed Trip</td></tr>';
		}
		else{
			var html1 ='<tr data-entry-id=""><td><a href="{{ url('/transaction_detail') }}">'+value.id+'</a></td><td>'+value.user_first_name+' '+value.user_middle_name+' '+value.user_last_name+'</td><td></td><td>'+value.driver_first_name+' '+value.driver_last_name+'</td><td>'+value.date+'</td><td>'+value.time+'</td><td>'+value.user_order_amount+'</td><td>'+value.user_order_pay_mode+'</td><td>'+value.user_order_status+'</td>';

				if(value.ride_status == "Ongoing"){
					var html2 ='<td><button class="btn btn-ongoing">Ongoing</button></td>';
				} 
				else if(value.ride_status == "disputed"){
					html2 ='<td><button class="btn btn-disputed">Disputed</button></td>';
				}
				else if (value.ride_status == "pending" || value.ride_status == "started" || value.ride_status == "not_started"){
					html2='<td><button class="btn btn-ongoing">Booked<i class="fa fa-times txt-red" data-toggle="modal" data-target="#cancel_trip"></i></button></td>';
				}
				else if(value.ride_status == "closed"){
					html2='<td><button class="btn btn-closed" >closed</button></td><td><button class="btn btn-ongoing">'+value.ride_status+'</button></td>';
				}
			var html3='<td><button class="btn btn-xs btn-info"><i class="fa fa-edit"></i></button></td><td><i class="fa fa-star-o"></i></td><td><i class="fa fa-star"></i></td></tr>';
			var htmldata =  html1.concat(html2, html3);
			// console.log(htmldata);
		}
		return htmldata;
	}
	
	function addcoloummaster(){
	    $(document).on('click', '.cross', function(){
			var id = $(this).parent().index()+1;
			var col = $("table tr th:nth-child("+id+"), table tr td:nth-child("+id+")");
			var op_text = col[0].innerText;
			var op_id = col[0].id;
			col.hide();
			var htmloption = new Option("option text", op_text);
			$(htmloption).attr('id',op_id);
			/// jquerify the DOM object 'o' so we can use the html method
			$(htmloption).html(op_text);
	    	// $('#show_coloum').empty();
			$("#show_coloum").append(htmloption);
        }); 
	}

	$('.cross_html').click(function() { 
    	var id = $(this).parent().index()+1;
    	var col = $("table tr th:nth-child("+id+"), table tr td:nth-child("+id+")");
    	var op_text = col[0].innerText;
    	var op_id = col[0].id;
    	col.hide();
    	console.log(id, col, op_text, op_id);
    	var htmloption = new Option("option text", op_text);
    	$(htmloption).attr('id',op_id);
		/// jquerify the DOM object 'o' so we can use the html method
		$(htmloption).html(op_text);
		$("#show_coloum").append(htmloption);
	});

	$('#show_coloum').change(function(e){
		e.preventDefault();
		var opt = $("#show_coloum option:selected" ).attr("id");
		var selecteditem = $(this).children("option:selected").val();
		$("#show_coloum option:selected").remove();
		var html = $("#orders").find('#'+opt+'').children();
		var id = $(html).parent().index()+1;
    	var col=$("table tr th:nth-child("+id+"), table tr td:nth-child("+id+")");
		col.show();
	});

	$('#tripType').change(function(){
		table.destroy();
		var res='';
		var orders = <?php echo json_encode($orderdetail); ?>;
		console.log('orders data here',orders);
		// alert($(this).val());
		if($(this).val()== 'all'){
		    $.each (orders, function (key, value){
				if(value!=null || value!='' || value!=undefined){
					var htmldata1 = coloumnData(value);
					res+=htmldata1;
    			}else{
        			res+='<tr><td colspan="12">No entries in table</td></tr>';
        			return false;
    			}
			});
		    $('#orders tbody').html(res);
		    $('#orders').DataTable();
		    // $('#show_coloum').empty();
		   	// addcoloummaster();
		}
		else if($(this).val()==0){
		    $.each (orders, function (key, value) {
		    if(value.ride_status == "pending" || value.ride_status == "not_started" || value.ride_status == "ongoing" ){
		        if(value!=null || value!='' || value!=undefined){
		           var htmldata1 = coloumnData(value);
					res+=htmldata1;
		            }else{
		                    res+='<tr><td colspan="12">No entries in table</td></tr>';
		                    return false;
		            }
		        }else{
		            res+='<tr><td colspan="12">No Data is available for Booked Trip</td></tr>';
		            return false;
		        }
		    });
		    $('#orders tbody').html(res);
		    $('#orders').DataTable();
		    // $('#show_coloum').empty();
		   	// addcoloummaster();
		}

		else if($(this).val()==1){
			$.each (orders, function (key, value) {
					if(value.ride_status == "cancelled"){
						if(value!=null || value!='' || value!=undefined){
							var htmldata1 = coloumnData(value);
							res+=htmldata1;
    					}else{
    						res+='<tr><td colspan="12">No entries in table</td></tr>';
    						return false;
    					}
    				}else{
    					res+='<tr><td colspan="12">No Data is available for Cancelled Trip</td></tr>';
    					return false;
					}
			});   
			$('#orders tbody').html(res);
		    $('#orders').DataTable();
		    // $('#show_coloum').empty();
			// addcoloummaster();		
		}
		else if($(this).val()==2){
			$.each (orders, function (key, value) {
				if(value.ride_status == "removed"){
					if(value!=null || value!='' || value!=undefined){
						var htmldata1 = coloumnData(value);
						res+=htmldata1;
					}
					else{
						res+='<tr><td colspan="12">No entries in table</td></tr>';
						return false;
					}
				}
				else{
					res+='<tr><td colspan="12">No Data is available for Rejected Trip</td></tr>';
					return false;
				}
			});  
			$('#orders tbody').html(res);
		    $('#orders').DataTable();
		    // $('#show_coloum').empty();
			// addcoloummaster();			
		} 
		else if($(this).val()==3){
			console.log(orders);
			$.each (orders, function (key, value) {
				if(value.ride_status == "success"){
					if(value!=null || value!='' || value!=undefined){
						var htmldata1 = coloumnData(value);
						res+=htmldata1;
					} 
					else{
						res+='<tr><td colspan="12">No entries in table</td></tr>';
						return false;
					}
				}
				else{
					res+='<tr><td colspan="12">No Data is available for Closed Trip</td></tr>';
					return false;
				}	
			});
			$('#orders tbody').html(res);
		    $('#orders').DataTable();
		    // $('#show_coloum').empty();
			// addcoloummaster(); 			
		}
		else{
			$.each (orders, function (key, value) {
				if(value.ride_status == "disputed"){
					if(value!=null || value!='' || value!=undefined){
						var htmldata1 = coloumnData(value);
						res+=htmldata1;
					}
					else{
						res+='<tr><td colspan="12">No entries in table</td></tr>';
						return false;
					}
				}
				else{
					res+='<tr><td colspan="12">No Data is available for Disputed Trip</td></tr>';
					return false;
				}
			});  
			$('#orders tbody').html(res);
		    $('#orders').DataTable()
		    // $('#show_coloum').empty();
			// addcoloummaster(); 			
		}
	});

	/*-----------------------goto add trip start--------------------*/
	function gotoAddTrip(id){
		$.ajax({
			url :"{{ route('add-trip-edit') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"tripTransactionId":id
			},
			success : function(data)
			{
				if(data.response.status == 'success'){
					window.location = '{{ route("add-trip") }}';
				}
			}
		});
	}
	/*-----------------------goto add trip end----------------------*/
	
</script

@endsection



@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1></h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="{{ url('add-trip') }}">Add Trip</a></li>
		<li class="active">Trip Delivery Details</li>
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
						<div class="col-md-12">
							<h3 class="m-0">Trip Delivery Details</h3>
						</div>
					</div>
				</div>
				<div class="trip-del-detail">
					<div class="panel-body">
	            		<div class="row">
	            			<div class="col-md-8">
	            				<h4 class="trip-title">Trip Detail <span>TN1111</span></h4>
	            				<div class="row">
	            					<div class="col-md-7">
	            						<div class="cust_detail">
		            						<h5>Customer</h5>
		            						<table>
												<tr>
													<td>CID</td>
													<td>:</td>
													<td></td>
												</tr>
												<tr>
													<td>Type</td>
													<td>:</td>
													<td></td>
												</tr>
												<tr>
													<td>Customer Name</td>
													<td>:</td>
													<td></td>
												</tr>
												<tr>
													<td>Mobile No</td>
													<td>:</td>
													<td>100x200x50 Ft</td>
												</tr>
												<tr>
													<td>Pick-Up Address</td>
													<td>:</td>
													<td></td>
												</tr>
												<tr>
													<td>Pick-Up Time</td>
													<td>:</td>
													<td>@ Rs<span>100</span>/hr</td>
												</tr>
												<tr>
													<td>Pick-Up Date</td>
													<td>:</td>
													<td>Rs 20</td>
												</tr>
												<tr>
													<td>Delivery Address</td>
													<td>:</td>
													<td></td>
												</tr>
												<tr>
													<td>Ledger balance</td>
													<td>:</td>
													<td>Rs 200</td>
												</tr>
											</table>
										</div>
	            					</div>
	            					<div class="col-md-5">
	            						<div class="cust_detail">
	            							<h5>Partner</h5>
		            						<table>
												<tr>
													<td>Driver</td>
													<td>:</td>
													<td></td>
												</tr>
												<tr>
													<td>Mobile No</td>
													<td>:</td>
													<td>100x200x50 Ft</td>
												</tr>
												<tr>
													<td>Loaders</td>
													<td>:</td>
													<td></td>
												</tr>
												<tr>
													<td>Tariff</td>
													<td>:</td>
													<td>@ Rs<span>100</span>/hr</td>
												</tr>
												<tr>
													<td>Loader</td>
													<td>:</td>
													<td>Rs 20</td>
												</tr>
												<tr>
													<td class="v-red">Amount</td>
													<td>:</td>
													<td class="v-red">Rs 200</td>
												</tr>
												<tr>
													<td>Balance</td>
													<td>:</td>
													<td>Rs <span class="v-red">200</span></td>
												</tr>
												<tr>
													<td class="v-red">Payment</td>
													<td>:</td>
													<td class="v-red">Cash</td>
												</tr>
											</table>
										</div>
	            					</div>
	            				</div>
	            			</div>	
	            			<div class="col-md-4">
	            				<h4 class="trip-title">Delivery</h4>
	            				<div class="delv-detail-pic">
									<ul class="enlarge">
			                            <li>
			                                <img src='' alt="GoGoTRux" />
		                                    <span>
		                                    	<img src='' alt="GoGoTRux" />
		                                    </span>
			                            </li>
			                            <li>
			                            	<img src='' alt="GoGoTRux" />
		                                    <span>
		                                    	<img src='' alt="GoGoTRux" />
		                                    </span>
			                            </li>
			                            <li>
			                            	<img src='' alt="GoGoTRux" />
		                                    <span>
		                                    	<img src='' alt="GoGoTRux" />
		                                    </span>
			                            </li>
		                			</ul>
								</div>
								<div class="pin-input">
									<input class="form-control" type="text"/> 
				            		<label>Enter PIN</label>
								</div>
								<div class="row form-group">
									<div class="col-md-6">
										<div>
											<input type="checkbox" name="cancelled"/> <span>Cancelled</span>
										</div>
										<div>
											<input type="checkbox" name="disputed"/> <span>Disputed</span>
										</div>
									</div>
									<div class="col-md-6">
										<div>
											<input type="checkbox" name="success"/> <span>Success</span>
										</div>
										<div>
											<input type="checkbox" name="unpaid"/> <span>Unpaid</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="trip-btn">
										<button class="btn btn-primary">Close Trip</button>
									</div>
									<div class="trip-btn">
										<button class="btn btn-success">Past Trip</button>
									</div>
								</div>
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
$(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-success').addClass('btn-default');
            $item.addClass('btn-success');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    /*allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
    });*/

    $('div.setup-panel div a.btn-success').trigger('click');
});
</script>
<script type="text/javascript">
$(document).ready(function () {
	$('.clockpicker').clockpicker({
	    placement: 'top',
	    align: 'left',
	    donetext: 'Done'
	});


// customer base location
function initialize() {
  var input = document.getElementById('cust_base_location');
  var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);
//end here

// pickup location lat long
function pickupAddressLatLong() {
  var pickupinput = document.getElementById('start_address_line_1');
  var autocompletepickup = new google.maps.places.Autocomplete(pickupinput);
  	// get lat-long
	google.maps.event.addListener(autocompletepickup, 'place_changed', function () {
  	// infowindow.close();
  	var pickupplace = autocompletepickup.getPlace();
  	updatePickupLatLong(pickupplace.geometry.location.lat(),pickupplace.geometry.location.lng());
	});
	//end lat-long
}
google.maps.event.addDomListener(window, 'load', pickupAddressLatLong);

function updatePickupLatLong(lat, lng) {
	$('#pickup_address_lat').val(lat);
	$('#pickup_address_lng').val(lng);
}
//end here

//delivery location lat long
function dropAddressLatLong() {
  var dropinput = document.getElementById('dest_address_line_1');
  var autocompletedrop = new google.maps.places.Autocomplete(dropinput);
  	// get lat-long
  	google.maps.event.addListener(autocompletedrop, 'place_changed', function () {
    // infowindow.close();
    var dropplace = autocompletedrop.getPlace();
    updateDropLatLong(dropplace.geometry.location.lat(),dropplace.geometry.location.lng());
    });
	//end lat-long
}
google.maps.event.addDomListener(window, 'load', dropAddressLatLong);

function updateDropLatLong(lat, lng) {
	$('#delivery_address_lat').val(lat);
	$('#delivery_address_lng').val(lng);
}
});
//end here
</script>
@endsection




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container" style="border:1px solid">
		<div class="panel-body p-0">
			<div class="view-op">
				<div class="row">
					<div class="section">
						<!-- <div class="form-group pdf-title" style="text-align: center;">Invoice</div> -->
						<h3 style="text-align: center;">Invoice</h3>
						<div class="detail-info">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<p><b>{{ $op_first_name ? $op_first_name : '-' }}</b></p>
										<p>{{ $address ? $address : '-' }}</p>
										<p>GSTN:</p>
									</div>
									<div class="col-md-6">
										<!--<p style="text-align: center;"><b>Vendor Bank Details</b></p>-->
										<div class="row">
											<div class="col-md-6 col-sm-6">
												<p><b>Account No.:</b> {{ $op_bank_account_number ? $op_bank_account_number : '-' }}</p>
												<p><b>Bank Name:</b> {{ $op_bank_name ? $op_bank_name : '-' }}</p>
											</div>
											<div class="col-md-6 col-sm-6">
												<p><b>Bank IFSC:</b> {{ $op_bank_ifsc ? $op_bank_ifsc : '-' }}</p>
											</div>
										</div>
									</div>
								</div>
							
							</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<p><b>Customer Name:</b> GOGOTRUX( FortSatt Business Technologies Pvt. Ltd.)</p>
										<p><b>Address:</b> Vedanta House, 1st Floor, Plot No 6, Tejeswani 1, Near Medipoint Hospital, Aundh, Pune, 411 007</p>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<p><b>Invoice No.:</b> {{ $op_invoice_no ? $op_invoice_no : '-' }}</p>
											</div>
											<div class="col-md-6">
												<p><b>Invoice Date:</b> {{ $updated_at ? $updated_at : '-' }}</p>
											</div>
										</div>
										<p><b>GSTN:</b></p>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<p><b>Trip Transaction Id:</b> {{ $user_order_transaction_id ? $user_order_transaction_id : '-' }}</p>
												<p><b>Trip Date:</b> {{ $trip_date ? $trip_date : '-' }}</p>
											</div>
											<div class="col-md-6">
												<p><b>Trip Id:</b> {{ $trip_transaction_id ? $trip_transaction_id : '-' }}</p>
												<p><b>Trip Time:</b> {{ $trip_time ? $trip_time : '-' }}</p>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<p><b>Driver Partner:</b> {{ $op_first_name ? $op_first_name : '-' }}</p>
												<p><b>Booking Date:</b> {{ $book_date ? $book_date : '-' }}</p>
											</div>
											<div class="col-md-6">
												<p><b>Trip Payment Mode:</b> {{ $payment_type ? $payment_type : '-' }}</p>
												<p><b>Booking Time:</b> {{ $book_time ? $book_time : '-' }}</p>
											</div>
										</div>
									</div>
								</div>
							
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<p><b>Pickup Address:</b> {{ $start_address_line_1 ? $start_address_line_1 : '-' }}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<p><b>Material Type:</b> {{ $material_type ? $material_type : '-' }}</p>
												<p><b>Trip Distance (km):</b> {{ $total_distance ? $total_distance : '-' }}</p>
											</div>
											<div class="col-md-6">
												<p><b>Weight (kg):</b> {{ $weight ? $weight : '-' }}</p>
												<p><b>Amount (Rs):</b> {{ $base_amount ? $base_amount : '-' }}</p>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<p><b>Delivery Address:</b> {{ $dest_address_line_1 ? $dest_address_line_1 : '-' }}</p>
									</div>
								</div>
							
								<p><b>Total Amount (In Words):</b> {{ $amount_in_words ? $amount_in_words : '-' }}</p>

								<p style="text-align: center;"><small>This is a system generated Invoice. So no Signature is required.</small></p>
							</div>
						</div>					
					</div>					
				</div>
			</div>
		</div>	
	</div>	
</body>
</html>
<!-- JS scripts for this page only -->






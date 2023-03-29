<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<style>
		p{
			margin: 0px;
		}
	</style>  
</head>
<body>
	<div style="border: 1px solid;
    margin: 10px;
    padding: 10px;">
		<div>
			<div>
				<div class="pull-right">
					<img  src="{{ public_path('images/logo-blue-cmprx.png')}}" alt="GogoTRux">

				</div>
				<table style="width:100%;border-bottom: 2px solid black; margin-top: 50px;margin-bottom: 10px;">
					<tbody>
						<tr>
							<td>
								<div class="text-center">
									<p style="color:#FF8000"><b><u>Invoice</u></b></p>
									<p><b>FortSatt Business Technologies Pvt. Ltd.</b></p>
									<p>Vedanta House, 1st Floor, Plot No 6, Tejeswani 1, Near Medipoint Hospital, Aundh, Pune, 411007</p>
									<p><small><b>CIN:</b> U72900PN2018PTC178466</small></p>
								</div>
							</td>
							<td>
								<div>
									<table border="1" style="margin-bottom: 10px;">
										<tr>
										  <th colspan="4">Cheque/Digital Payment Bank Account Detail</th>
										</tr>
										<tr>
											<th style="padding:4px;">Account Name</th>
											<td style="padding:4px;" colspan="3">FortSatt Business Technologies Pvt. Ltd</td>
										</tr>
										<tr>
											<th style="padding:4px;">Bank</th>
											<td style="padding:4px;">State Bank of India</td>
											<th style="padding:4px;">Branch</th>
											<td style="padding:4px;">PBB Aundh, Pune</td>
										</tr>
										<tr>
											<th style="padding:4px;">Account No</th>
											<td style="padding:4px;">38527863582 </td>
											<th style="padding:4px;">IFSC code:</th>
											<td style="padding:4px;">SBIN0015707</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<table style="width:100%;border-bottom: 2px solid black;margin-top: 10px;margin-bottom: 10px;">
					<tbody>
						<tr>
							<td><b>Customer Name</b></td>
							<td colspan="3">{{ $user_first_name ? $user_first_name : '-' }}</td>
							<td><b>Invoice No:</b></td>
							<td>{{ $cust_invoice_no ? $cust_invoice_no : '-' }}</td>
							<td><b>Invoice Date</b></td>
							<td>{{ $updated_at ? $updated_at : '-' }}</td>
						</tr>
						<tr>
							<td><b>Address</b></td>
							<td colspan="3">{{ $cust_address ? $cust_address : '-' }}</td>
							<td><b>GSTIN</b></td>
							<td></td>
						</tr>
						<tr>
							<td><b>Contact Name</b></td>
							<td>{{ $user_first_name ? $user_first_name : '-' }}</td>
							<td><b>Mobile No</b></td>
							<td> {{ $user_mobile_no ? $user_mobile_no : '-' }}</td>
							<td><b>Email</b></td>
							<td colspan="3">{{ $email ? $email : '-' }}</td>
						</tr>
					</tbody>
				</table>
				<table style="width:100%;border-bottom: 2px solid black;margin-top: 10px;margin-bottom: 10px;">
					<tbody>
						<tr>
							<td><b>Transaction ID</b></td>
							<td>{{ $user_order_transaction_id ? $user_order_transaction_id : '-' }}</td>
							<td><b>Trip ID</b></td>
							<td>{{ $trip_transaction_id ? $trip_transaction_id : '-' }}</td>
							<td><b>Service Partner</b></td>
							<td colspan="3">{{ $op_first_name ? $op_first_name : '-' }}</td>
						</tr>
						<tr>
							<td><b>Trip Date</b></td>
							<td>{{ $trip_date ? $trip_date : '-' }}</td>
							<td><b>Trip Hour</b></td>
							<td>{{ $trip_time ? $trip_time : '-' }}</td>
							<td><b>Booking Date</b></td>
							<td>{{ $book_date ? $book_date : '-' }}</td>
							<td><b>Booking Time</b></td>
							<td>{{ $book_time ? $book_time : '-' }}</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><b>Payment Mode</b></td>
							<td colspan="3">{{ $payment_type ? $payment_type : '-' }}</td>
						</tr>
					</tbody>
				</table>
				<table style="width:100%;border-bottom: 2px solid black;margin-top: 10px;margin-bottom: 10px;">
					<tbody>
						<tr>
							<td><b>Pickup Address</b></td>
							<td colspan="9">{{ $start_address_line_1 ? $start_address_line_1 : '-' }}</td>
						</tr>
						<tr>
							<td><b>Delivery Address</b></td>
							<td colspan="9">{{ $dest_address_line_1 ? $dest_address_line_1 : '-' }}</td>
						</tr>
						<tr>
							<td><b>Material Type</b></td>
							<td colspan="3">{{ $material_type ? $material_type : '-' }}</td>
							<td><b>Material Weight Kg</b></td>
							<td colspan="3">{{ $weight ? $weight : '-' }}</td>
						</tr>
						<tr>
							<td><b>Incidental Charge</b></td>
							<td>{{ $incidental_charges ? $incidental_charges : '-' }}</td>
							<td><b>Other Charge</b></td>
							<td>{{ $other_charges ? $other_charges : '-' }}</td>
						</tr>
						<tr>
							<td><b>Distance(kms)</b></td>
							<td>{{ $total_distance ? $total_distance : '-' }}</td>
							<td><b>Amount (Rupees)</b></td>
							<td>{{ $actual_amount ? $actual_amount : '-' }}</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td><td></td>
						</tr>
					</tbody>
				</table>
				<table style="width:100%;margin-top: 10px;">
					<tbody>
						<tr>
							<td><b>Total Amount(in Words)</b></td>
							<td>{{ $amount_in_words_cust ? $amount_in_words_cust : '-' }}</td>
						</tr>
					</tbody>
				</table>
				<table style="width:100%;margin-top: 20px;">
					<tbody>
						<tr>
							<td colspan="10" align="center">
								<p style="font-size: 11px;"><b>Thank You for using GOGOTRUX Services. Logon to GOGOTRUX.COM</b></p>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<p style="font-size: 13px;">Billing Queries: 7350920881:Email: gogotrux@gmail.com: New Trip Booking 70305 70500/70305 80500: www.gogotrux.com</p>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<p style="font-size: 9px;">GOGOTRUXTM is owned by FortSatt Business Technologies Pvt. Ltd, a Registered Startup under DIPP, Govt. of India. Exempt from GST payment.</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>	
	</div>	
</body>
</html>
<!-- JS scripts for this page only -->

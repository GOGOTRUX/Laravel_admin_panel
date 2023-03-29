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
	<h1>Payments</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Payments</li>
		<li class="active">Credit/Debit Note</li>
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
				<form id="paymentNoteInfo" method="POST" enctype='multipart/form-data' action="{{ route('paymentsCreditDebit.store') }}" >
				@csrf
					<div class="section-title p-l-r-0">
						<div class="row">
							<div class="col-sm-9">
								<div class="form-group pay_note">
									<div class="radio">
										<label>
											<input type="radio" name="note_type" id="credit_note" value="credit_note">
											Credit Note
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="note_type" id="debit_note" value="debit_note">
											Debit Note
										</label>
									</div>
								</div>
								<label id="note_type-error" class="error" for="note_type"></label>
							</div>
							<div class="col-sm-3">
								<div class="has-feedback">
									<select class="form-control select2" data-placeholder="Search UID/Enter Mobile No." name="operator_id" id="operator_id" autofocus>
										
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="section p-t-10">
						<div class="row">							
							<div class="pay-box">
								<table id="" class="table pay">
									<thead>
										<tr>
											<th>Unique ID</th>
											<!-- <th>Position</th> -->
											<th>Name</th>
											<th>Mobile No.</th>
											<!-- <th>Location</th> -->
											<th>Credit Balance</th>
											<th>Debit Balance</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><input name="op_uid" id="op_uid" type="text" class="form-control" value=""></td>
											<td><input readonly name="op_name" id="op_name" type="text" class="form-control" value=""></td>
											<td><input readonly name="op_mobile_no" id="op_mobile_no" type="text" class="form-control" value=""></td>
											<td><input readonly name="op_credit_bal" id="op_credit_bal" type="text" class="form-control" value=""></td>
											<td><input readonly name="op_debit_bal" id="op_debit_bal" type="text" class="form-control" value=""></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<input type="hidden" name="op_id" id="op_id">
						<div class="pay-box">
							<div class="row">
								<div class="pay-col-6 form-group">
									<label>Reference</label>
									<input name="reference" id="reference" type="text" class="form-control" value="">
								</div>
								<div class="pay-col-6 form-group">
									<label>Amount</label>
									<input name="amount" id="amount" type="text" class="form-control" value="">
								</div>
								<div class="pay-col-6 form-group">
									<label>Reason</label>
									<input name="reason" id="reason" type="text" class="form-control" value="">
								</div>
								<div class="btn-div">
									<button type="submit" class="btn btn-sm bg-navy" name="send_for_approval">Send for Approval</button>
									<button type="submit" class="btn btn-sm btn-success">Save</button>
								</div>
							</div>

						</div>
					</div>
				</form>
			</div>
			<div class="credit_debit_list">
				<div class="table-responsive">
					<table id="paynote" class="table table-list" data-page-length="25">
						<thead>
							<tr>
								<th>Note No.</th>
								<th>Date</th>
								<th>Transaction ID</th>
								<th>Party ID</th>
								<th>Amount</th>
								<th>Created By</th>
								<th>Approved By</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody><?php $counter=1; $count=1; ?>
							@if(!empty($notesDetails))
								@foreach ($notesDetails as $note)
								<tr>
									<td>{{$counter}}
										<?php $counter=$counter+1; ?></td>
									<td>{{ $note['created_at'] }}</td>
									<td>{{ $note['transaction_id'] }}</td>
									<td>{{ $note['transaction_id'] }}</td>
									<td>{{ $note['amount'] }}</td>
									<td>{{ $note['created_by'] }}</td>
									<td>{{ $note['approved_by'] ? $note['approved_by'] : '-' }}</td>
									<td>
										@if($note['status'] == 'send_for_approval')
										<a><button class="btn btn-xs bg-success">Waiting for approval</button></a><button class="btn btn-xs btn-info" type="button" onclick="approve_payment_note('{{ $note['id'] }}')"><i class="fa fa-check" data-toggle="tooltip" title="Click to Approve!"></i></button>
										@elseif($note['status'] == 'approved')
										<a><button class="btn btn-xs bg-success">Approved</button></a>
										@else
										<a href="{{ route('paymentsCreditDebit.edit',[ encrypt($note['id']) ]) }}"><button class="btn btn-xs bg-olive"><i class="fa fa-edit"></i></button></a>
										<button class="btn btn-xs btn-danger" type="button" onclick="delete_payment('{{ $note['id'] }}')"><i class="fa fa-trash" data-toggle="tooltip" title="Delete!"></i></button>
										@endif
									</td>
								</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
<script>
	$(function () {
		$('#paynote').DataTable({
			'searching'   : true,
			'ordering'    : true,
			'lengthChange': false
		})
	})

	function initSelect(){
		$("#select").select2({});
	}

	$(document.body).on("change","#operator_id",function(){
		if(this.value != ''){
			var type='by_id';
			getPaymentDetails(this.value, type);
		}
	});

	$("#remove-select2").on("click", function() {
		// $("#operator_id-input").select2("val", "");	
		$('#operator_id').val(null).trigger("change");
	});

	// $(document).on("focus keypress",".select2-search__field",function (e) 
	// {
	$("#operator_id-input").on("change", function() {
		// var op_uid = $(".select2-search__field").val();
		var op_uid = this.value;
		if($.trim($(".select2-search__field").val()).length != 0){
			$.ajax({
				url :"{{ route('get-operator-list') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"op_uid": op_uid
				},
				success : function(data){
					var html = '';
					$('#operator_id').empty();
					var result = JSON.parse(data);
					if(!(result.length == 0)){
						// html += '<option value="">Select UID/Select Mobile No</option>';
						$.each(result, function (key, value)
						{
							html += '<option value="'+value.op_user_id+'">'+value.op_mobile_no +' ('+value.op_uid+') </option>';
						});
					}
					else{
						html += '<option value="">No Matches Found</option>';
					}
					$('#operator_id').append(html);
					initSelect();
				}
			});
		}
	});

	$(document.body).on("blur keypress","#op_uid",function(){
		$(':input','#fetch_results')
			.not(':button, :submit, :reset, :hidden')
			.val('')
			.removeAttr('checked')
			.removeAttr('selected');
		
		var op_uid = $('#op_uid').val();
		if(op_uid != ''){
			getPaymentDetails(op_uid, type ='by_uid');
		}
	});

	$(document).ready(function() {
		set_operator_details();
		setTimeout(function() {
            $("#success-message").addClass('hide');
        }, 1000);

        $( "#paymentNoteInfo" ).validate({
			rules: {
				note_type: {
					required: true,
				},
				op_uid: {
					required: true,
				},
				// op_name: {
				// 	required: true,
				// },
				// op_mobile_no: {
				// 	required: true,
				// },
				// op_credit_bal: {
				// 	required: true,
				// },
				// op_debit_bal: {
				// 	required: true,
				// },
				reference: {
					required: true,
				},
				amount: {
					required: true,
				},
				reason: {
					required: true,
				},
			},
			messages:{
				note_type: {
					required: "Please select note type",
				},
				op_uid: {
					required: "Please enter operator UID",
				},
				// op_name: {
				// 	required: "Please select note type",
				// },
				// op_mobile_no: {
				// 	required: "Please select note type",
				// },
				// op_credit_bal: {
				// 	required: "Please select note type",
				// },
				// op_debit_bal: {
				// 	required: "Please select note type",
				// },
				reference: {
					required: "Please enter reference",
				},
				amount: {
					required: "Please enter amount",
				},
				reason: {
					required: "Please enter reason",
				},
			}
		});
	});

	function set_operator_details(){
		var html7 = '';var result = '{{ $op_details }}';
        var res = JSON.parse(result.replace(/&quot;/g,'"'));
        if(!(res.length == 0)){
            html7 += '<option value="">Select UID/Select Mobile No</option>';
            $.each(res, function (key, value)
            {
                html7 += '<option value="'+value.op_user_id+'">'+value.op_mobile_no +' ('+value.op_uid+') </option>';
            });
       }
       $('#operator_id').append(html7);
       initSelect();
	}

	function getPaymentDetails(op_uid, type){
		$.ajax({
			url :"{{ route('get-operator-payment-details') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"op_uid": op_uid,
				"type": type,
			},
			success : function(data){
				var result = JSON.parse(data);
				var html = '';
				// console.log(result);
				if(!(result == null)){
					$('#op_uid').val(result.op_uid);
					$('#op_name').val(result.op_first_name);
					$('#op_mobile_no').val(result.op_mobile_no);
					$('#op_credit_bal').val(result.credit_balance);
					$('#op_debit_bal').val(result.debit_balance);
				}
				else{
					alert("No information available! Please choose valid operator.")
				}
			}
		});
	}

	function delete_payment(id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want to delete this payment note!<br><span style='color:#d33;'>(This will permanently delete)</span>",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then(function() 
		{
			$.ajax({
				// url :"paymentsCreditDebit.destroy",
				url: '/paymentsCreditDebit/'+id,
    			type: 'DELETE',
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"payment_id": id,
					 _method: 'DELETE'
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Deleted!", text: "Payment note has been deleted.", type: result.status}).then(function()
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
		})
	}

	function approve_payment_note(id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want to approve this payment note!<br><span style='color:#d33;'></span>",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Approve'
		}).then(function() 
		{
			$.ajax({
				url: '/paymentsCreditDebit/approve/'+id,
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Approved!", text: "Payment note has been Approved.", type: result.status}).then(function()
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
		})
	}
</script>
@endsection


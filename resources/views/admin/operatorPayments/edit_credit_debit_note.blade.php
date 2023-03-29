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
				<form id="paymentNoteInfo" method="POST" enctype='multipart/form-data' action="{{ route('paymentsCreditDebit.update', [encrypt($notesDetails->id)]) }}" >
				@method('PUT')
				@csrf
					<div class="section-title p-l-r-0">
						<div class="row">
							<div class="col-sm-9">
								<div class="form-group pay_note">
									<div class="radio">
										<label>
											<input type="radio" name="note_type" id="credit_note" value="credit_note" {{ $notesDetails->note_type == 'credit_note' ? 'checked' : ''}}>
											Credit Note
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="note_type" id="debit_note" value="debit_note" {{ $notesDetails->note_type == 'debit_note' ? 'checked' : ''}}>
											Debit Note
										</label>
									</div>
								</div>
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
											<td><input name="op_uid" id="op_uid" type="text" class="form-control" value="{{$notesDetails->op_uid}}"></td>
											<td><input readonly name="op_name" id="op_name" type="text" class="form-control" value="{{$op_user->op_first_name}}"></td>
											<td><input readonly name="op_mobile_no" id="op_mobile_no" type="text" class="form-control" value="{{$op_user->op_mobile_no}}"></td>
											<td><input readonly name="op_credit_bal" id="op_credit_bal" type="text" class="form-control" value="{{$op_user->credit_balance}}"></td>
											<td><input readonly name="op_debit_bal" id="op_debit_bal" type="text" class="form-control" value="{{$op_user->debit_balance}}"></td>
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
									<input name="reference" id="reference" type="text" class="form-control" value="{{$notesDetails->reference}}">
								</div>
								<div class="pay-col-6 form-group">
									<label>Amount</label>
									<input name="amount" id="amount" type="text" class="form-control" value="{{$notesDetails->amount}}">
								</div>
								<div class="pay-col-6 form-group">
									<label>Reason</label>
									<input name="reason" id="reason" type="text" class="form-control" value="{{$notesDetails->reason}}">
								</div>
								<div class="btn-div">
									<button type="submit" class="btn btn-sm bg-navy" name="send_for_approval">Send for Approval</button>
									<button type="submit" class="btn btn-sm btn-success">Update</button>
								</div>
							</div>
						</div>
					</div>
				</form>
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
			clearForm();
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

	function clearForm(){
		alert();
	}

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
</script>
@endsection


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
	<h1>Invoices</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li>Payments</li>
		<li class="active">Invoices</li>
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
			<form method="POST" id="generateInvoiceForm" name="generate_invoice_form">
				<div class="row">
					<div class="col-sm-12 section-title m-b-10">
						<div class="col-md-3 p-l-0">
							<select id="invoice_type" type="text" class="form-control all-caps" name="invoice_type" autofocus>
								<option value="inward">Inward Invoices</option>
								<option value="outward">Outward Invoices</option>
							</select>
						</div>
					</div>
					<div class="section p-t-10">
						<div class="row">
							<div class="col-md-9">
								<div class="ledger_date">
									<label>From</label>
									<div class="date">
										<input id="from_date" type="text" class="form-control date-picker" name="invoice_from_date" value="">
									</div>
								</div>
								<div class="ledger_date text-right">
									<label>To</label>
									<div class="date">
										<input id="to_date" type="text" class="form-control date-picker" name="invoice_to_date" value="">
									</div>
								</div>
							</div>
							<div class="col-md-3 text-right">
								<button type="submit" class="btn btn_export">Generate</button>
							</div>
						</div>
						<div class="table-responsive ledger">
							<div id="generate_invoices">
								
							</div>
						</div>
					</div>
				</div>
			</form>
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
    $("#invoice_type").change(function(){
    	$('#generate_invoices').empty();
    });
</script>
<script>
	$("#generateInvoiceForm").validate({
		rules: {
			invoice_from_date: {
				required: true,
			},
			invoice_to_date: {
				required: true,
			},	
		},  
		messages: {
			invoice_from_date : {
				required:"Please select from date",
			},
			invoice_to_date : {
				required:"Please select to date",
			},
		},
		submitHandler: function(form) {
			$.ajax({
	            headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
	            url :"{{ route('generate-invoice') }}",
	            method:"POST",
	            data: $('#generateInvoiceForm').serialize(),
	            success : function(data)
	            {
	            	if(data.response.status == 'success'){
	            		$('#generate_invoices').html(data.response.invoice);
                        $('#'+data.response.tid).DataTable({
            				dom: 'Blfrtip',
                            lengthMenu: [10, 25, 50,100],
                            buttons: [
                                'excel'
                            ],
            			});
	            	}else{
	            		swal({
							title: 'No Data Match With Your Request.',
							type: 'warning',
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'try again'
						})
	            	}
	            }
	        })
		}
	});
	function printInvoice(tripId){
		/*$.ajax({
            url :"{{ route('pdfview-partner') }}",
            method:"POST",
            data: { "_token": "{{ csrf_token() }}","trip_id": tripId },
            success : function(data)
            {
            	//alert(data);
            }
        })	*/
	
	/*let url = "{{ route('pdfview-partner', ':trip_id') }}";
    url = url.replace(':trip_id', tripId);
    document.location.href=url;
		*/
var invoice_type = $("#invoice_type option:selected").val();
var base = '{!! route('pdfview-partner') !!}';

var url = base+'?tripid='+tripId+'&invoice_type='+invoice_type ; console.log(url);

//window.location.href=url;
window.open(
	url,
  '_blank'
);
	}
</script>
@endsection


@extends('layouts.app')

@section('content-header')
	<h1>
		{{ $header }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">{{ $header }}</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success" id="message" style="display:none;">
				{{ session('success') }}
			</div>
		@endif
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					<p>
						<!-- <button class="btn btn-xs btn-danger" id="operatordelete" name="submit">Delete selected</button>                                 -->
					</p>
					<div class="table-responsive">
						<table id="operator" class="table op-list table-bordered table-striped {{ count($operators) > 0 ? 'datatable' : '' }} " data-page-length="25">
							<thead>
								<tr>
									<th>#</th>
									<th>UID</th>
									<th>Name</th>
									<th>Mobile</th>
									<th>Type</th>
									<th>Verified<br>Status</th>
									<th>Created</th>
									<th>Actions</th>
									<th>Sub</th>
									<th>Pay</th>
									<th>Approve</th>
									<th>Loc</th>
									<th>Book</th>
									<th>Book<br> Status</th>
									<th>Trip<br> Status</th>
									<th>SMS</th>
									<th>Rate</th>
									<!-- <th></th>
									<th></th> -->
									<th></th>	
								</tr>
							</thead>
							<tbody>
							<?php $counter=1; $count=1; ?>
							@if(!empty($operators))
								@foreach ($operators as $operator)
								<tr>
									<td>
										{{$counter}}
										<?php $counter=$counter+1; ?>
										<!-- <a href="{{ route('operators.edit',[$operator->op_user_id]) }}">{{ $count++ }}</a> -->
									</td>
									<td> {{ $operator->op_uid }} </td>
									<td>
										{{ $operator->op_first_name }} {{ $operator->op_last_name }}</td>					
									<td>{{ $operator->op_mobile_no }}</td>
									<td>@if ($operator->op_type_id == 1) <span class="label bg-navy">{{ __('IND') }}</span> @endif @if($operator->op_type_id == 2) <span class="label bg-purple">{{ __('BN') }}</span> @endif</td>
									<td>
										{{ $operator->op_is_verified==1 ? 'V' : 'NV'}}
									</td>
									<td>{{\Carbon\Carbon::parse($operator['created_date'])->format('d/m/y')}}</td>
									<td>                        
										<a  href="{{ route('operators.show',[$operator->op_user_id]) }}"><button class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></button></a>
										@can('operator_edit')
											<a href="{{ route('operators.edit',[$operator->op_user_id]) }}"><button class="btn btn-xs btn-info"><i class="fa fa-edit"></i></button></a>
										@endcan
										<!-- <i class="fa fa-fw fa-trash" style="color:red; font-size: 20px;" onclick="deleteon('{{ $operator->op_user_id }}')"></i>
									   -->
									    @can('operator_block')
											@if($operator->op_is_blocked==1)
												<button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#unblock-modal" onclick="openModel('{{ $operator->op_user_id }}')"><i class="fa fa-check-square-o" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="{{$operator->op_user_account_block_note}}"></i></button>
											@else
												<button type="submit" class="btn btn-xs btn-danger" onclick="openModel('{{ $operator->op_user_id }}')" data-toggle="modal" data-target="#blockedoperator"><i class="fa fa-ban" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Block Operator"></i></button>
											@endif
										@endcan
										@can('operator_delete')
										@if($operator->deleted_at==null)
											<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete-modal" onclick="deleteModal('{{ $operator->op_user_id }}')">
												<i class="fa fa-fw fa-trash" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Delete Partner"></i>
											</button>
										@endif
										@endcan
										<button class="btn btn-xs bg-maroon" type="button" onclick="checkVehRates('{{ $operator->op_user_id }}')" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Check Partner Rates">CR</button>
									</td>
									<td><button class="btn btn-xs bg-maroon" type="button" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="click to view more details">B</button></td>
									<td>
										<button type="submit" class="btn btn-xs bg-olive" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Make Payments"><i class="fa fa-money" aria-hidden="true"></i></button>
									</td>
									<td>
										<a class="next-arrow">
											<div id="arrow-wrapper">
									    		<div id="arrow-stem"></div>
									    		<div id="arrow-head"></div>
									  		</div>
										</a>
									</td>
									<td>
										<button type="submit" class="btn btn-xs btn-danger" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Driver Location"><i class="fa fa-map-marker" aria-hidden="true"></i></button>
									</td>
									<td>
										<button type="submit" class="btn btn-xs btn-success" onclick="bookPartner('{{ $operator->op_user_id }}')" data-toggle="modal" data-target="#bookpartner" data-backdrop="static"><i class="fa fa-truck" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Book Partner"></i></button>
									</td>
									<td></td>
									<td></td>
									<td>
										<button type="submit" class="btn btn-xs btn-primary" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Send Message"><i class="fa  fa-envelope"></i></button>
									</td>
									<td>
										<button type="submit" class="btn btn-xs btn-warning" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Driver and Service Rating"><i class="fa  fa-star"></i></button>
									</td>
									<!-- <td></td>
									<td></td> -->
									<td></td>
								</tr>
								@endforeach
							@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="blockedoperator">
		<div class="modal-dialog">
			<form method="POST" action="{{ route('operatorBlocked')}}">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Reason for Blocking</h4>
					</div>
					<div class="modal-body">
						<div id="show_driver_image">
							<input type="hidden" name="operator_id" value="" id="operator_id">
							<textarea id='blockingtext' name="reason" placeholder='Write reasons....' rows=5px cols=75px required></textarea>
						</div>
						<p id="blockedmessage"></p>
					</div>
					<div class="modal-footer">
						<center>
							<input type="submit" name="submit" value="Yes, block it!" class="btn btn-danger"><!--  onclick="blockedstate()" -->
							<button type="submit" data-dismiss="modal" class="btn btn-success">Cancel</button>
						</center>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="unblock-modal">
		<div class="modal-dialog">
			<form method="POST" action="{{ url('operator/unblock')}}">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Unblock Operator</h4>
					</div>
					<div class="modal-body">
						<div id="show_driver_image">
							<input type="hidden" name="unblock_op_id" value="" id="unblock_op_id">
							Are you sure to unblock operator?
						</div>
					</div>
					<div class="modal-footer">
						<center>
							<input type="submit" name="submit" value="OK" class="btn btn-danger">
							<button type="submit" data-dismiss="modal" class="btn btn-success">Cancel</button>
						</center>
					</div>
				</div>
			</form>
		</div>
	</div>   

	<!--All images modal -->
	<div class="modal fade" id="view-all-images" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" id="all-images">

				</div>
    		</div>
  		</div>
	</div>
	<!--All images End-->
@endsection

@section('javascript')
<!-- page script -->

<script language="javascript" type="text/javascript">
 
$(document).ready(function () 
{
	$('[data-toggle="tooltip"]').tooltip();
	$('#message').fadeIn('slow', function()
	{
		$('#message').delay(1000).fadeOut(); 
	});

	$("#operatordelete").click(function()
	{
		if($('#operatorselectdelete:checked').length!=0){
			swal({
				title: 'Are you sure?',
				text: "It will permanently deleted !",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then(function() 
			{	
				var selectid=new Array();
				$('#operatorselectdelete:checked').each(function() {
					selectid.push(this.value);
				});
				$.ajax({
					url :"{{ route('operatorselectdelete') }}",
					method:"POST",
					data: {
						"_token": "{{ csrf_token() }}",
						"selectid": selectid
					},
					success : function(data)
					{
						swal({title: "Deleted!", text: "Your file has been deleted.", type: "success"}).
						then(function()
						{ 
							location.reload();
						});
					}
				});
			})
		}
	});    
});
</script>

<script type="text/javascript">
	// function deleteon(id)
	// {
	// 	swal({
	// 		title: 'Are you sure?',
	// 		text: "It will permanently deleted !",
	// 		type: 'warning',
	// 		showCancelButton: true,
	// 		confirmButtonColor: '#3085d6',
	// 		cancelButtonColor: '#d33',
	// 		confirmButtonText: 'Yes, delete it!'
	// 	}).then(function() 
	// 	{
	// 		$.ajax({
	// 			url :"operatordelete",
	// 			method:"POST",
	// 			data: {
	// 				"_token": "{{ csrf_token() }}",
	// 				"selectid": id
	// 			},        
	// 			success : function(data)
	// 			{      
	// 				swal({title: "Deleted!", text: "Your file has been deleted.", type: "success"}).
	// 				then(function()
	// 				{ 
	// 					location.reload();       
	// 				});
	// 			}        
	// 		});
	// 	})
	// }

	function blockedstate()
	{  
		var operator_id=$('#operator_id').val();
		var text= $('#blockingtext').val();
		if(operator_id!='' && text!='')
		{
			$('#blockedoperator').modal('hide');
			$.ajax({
				url :"operatorBlocked",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"reason": text,
					"operator_id":operator_id
				},        
				success : function(data)
				{     
					alert(data);
				}	         
			});           
		}
	}

	function openModel(id)
	{
		if(id!='')
		{
			$('#operator_id').val(id);
			$('#unblock_op_id').val(id);
		}
	}
</script>
<script type="text/javascript">
	
	function sendTokenToServer(token){
		$.ajax({
			url :"{{ route('updatetoken') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"token":token,
			},
			success : function(data)
			{
				//alert(data);
				console.log(data)
			}
		});
	}

	function deleteModal(id){
		swal({
			title: 'Are you sure?',
			text: "Delete a partner!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('partner-delete') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.response){
						swal({title: "Deleted!", text: "Deleted partner", type: result.status}).then(function(){ 
							location.reload();
						});
					}
					else{
						swal({title: "Oops!", text: "Failed to delete partner", type: result.status}).then(function(){
							location.reload();
						});
					}
				}
			});
		})
	}

	function checkVehRates(id){
		$.ajax({
			url :"{{ route('get-partner-rates') }}",
			method:"POST",
			data: {
				"_token": "{{ csrf_token() }}",
				"id": id
			},
			success : function(data)
			{
				
				console.log(data);
				if(data.response.status == 'success'){
					var ratehtml = '';
                    $('#all-images').empty();
                    var vehRates = data.response.detail;
                    $.each(vehRates, function (key, value)
                    {
                        ratehtml += '<div class="row"><div class="col-md-3"><label>Veh Registration : '+value.veh_registration_no+'</label></div><div class="col-md-3"><label>Base Charge Rs: '+value.veh_base_charge+'</label></div><div class="col-md-3"><label>Rate 3 to 15 Km Rs: '+value.veh_3km_15km+'</label></div><div class="col-md-3"><label>Rate Above 15 Km Rs: '+value.veh_above_15km+'</label></div></div>';
                    });
                    $('#view-all-images').modal('toggle');
                    $("#all-images").append(ratehtml);
				}
				else{
					swal({title: "Oops!", text: "Failed to load partner charge"}).then(function(){
						//location.reload();
					});
				}
			}
		});
	}

	function bookPartner(id){
		swal({
			title: 'Are you sure?',
			text: "Book the partner!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Confirm'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('book-partner') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					if(data.response.status == 'success'){
						window.location = '{{ route("add-trip") }}';
					}
				}
			});
		})
	}
</script>
@endsection

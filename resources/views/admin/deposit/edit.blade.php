
@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
	<h1>Operator Deposite<!--<small>(Vendors)</small>--></h1>
	<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
	<li>Roles</li>
	<li class="active">Edit</li>
	</ol>
@endsection

<!-- Main Content -->
@section('content')
	@if(session('success'))
		<!-- If password successfully show message -->
		<div class="row">
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		</div>
	@else
		<div class="panel-body p-0">
			<div class="view-op">
				<div class="row"> 
					<div class="col-sm-12 form-group section-title"><b>Edit</b></div>
					<div class="section">
						<form method="POST" action="{{ route('deposite/update') }}">        
						@csrf
							<div class="row">
								<div class="col-xs-6 form-group">
									<div class="f-half">
										<label for="op_first_name" class="control-label">{{ __('First Name*') }}</label>
										<input id="op_first_name" type="text" class="form-control" name="op_first_name" value="{{ $depositlist->first()->op_first_name }}" required autofocus readonly>
										<p class="help-block"></p>
										@if($errors->has('op_first_name'))
											<p class="help-block text-red">
												{{ $errors->first('op_first_name') }}
											</p>
										@endif
									</div>
									<div class="f-half">
										<label for="op_last_name" class="control-label">{{ __('Last Name*') }}</label>
										<input id="op_last_name" type="text" class="form-control" name="op_last_name" value="{{ $depositlist->first()->op_last_name }}" required autofocus readonly>	

										<p class="help-block"></p>
										@if($errors->has('op_last_name'))
											<p class="help-block text-red">
												{{ $errors->first('op_last_name') }}
											</p>
										@endif
									</div>
								</div>
								<div class="col-xs-6 form-group">
									<div class="f-half">
										<label for="op_order_mobile_no" class="control-label">{{ __('Mobile Number*') }}</label>
										<input id="op_order_id" type="hidden" class="form-control" name="op_order_id" value="{{ $depositlist->first()->op_order_id }}" required autofocus>
										<input id="op_order_mobile_no" type="text" class="form-control" name="op_order_mobile_no" value="{{ $depositlist->first()->op_order_mobile_no }}" required autofocus readonly="readonly">
										<p class="help-block"></p>
										@if($errors->has('op_order_mobile_no'))
											<p class="help-block text-red">
												{{ $errors->first('op_order_mobile_no') }}
											</p>
										@endif
									</div>
									<div class="f-half">								
										<label for="op_order_email" class="control-label">{{ __('Email*') }}</label>
										<input id="op_order_email" type="text" class="form-control" name="op_order_email" value="{{ $depositlist->first()->op_order_email }}" required autofocus readonly>
										<p class="help-block"></p>
										@if($errors->has('op_order_email'))
											<p class="help-block text-red">
												{{ $errors->first('op_order_email') }}
											</p>
										@endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6 form-group">
									<div class="first-line">					
								   		<label for="op_order_mode" class="control-label">{{ __('Payment Mode*') }}</label>
										<select id="op_order_mode"  class="form-control" name="op_order_mode"  required autofocus readonly>
										   @if($depositlist->first()->op_order_mode=="Cash")
										   <option value="{{ $depositlist->first()->op_order_mode }}" selected="selected">Cash</option>
										   <option value="Cheque" >Cheque</option>
										   @else
										   <option value="Cash" >Cash</option>
										   <option value="{{ $depositlist->first()->op_order_mode }}" selected="selected">Cheque</option>
										   @endif                    
										</select>
									</div>							   
								</div>
							</div>			
							<div class="row" id="cash" style="display:none;">
								@if($depositlist->first()->op_order_mode=="Cash")
									<div class="first-line">
										<div class="col-xs-4 form-group">			
											<label for="op_order_receipt_no" class="control-label">{{ __('Receipt number*') }}</label>
										 	<input id="op_order_receipt_no" type="text" class="form-control" name="op_order_receipt_no" value="{{ $depositlist->first()->op_order_receipt_no }}" required autofocus>
											<p class="help-block"></p>
											@if($errors->has('op_order_receipt_no'))
												<p class="help-block text-red">
													{{ $errors->first('op_order_receipt_no') }}
												</p>
											@endif
										</div>
									 	<div class="col-xs-4 form-group">
											<label for="op_order_amount" class="control-label">{{ __('Amount*') }}</label>
											<input id="op_order_amount" type="number" class="form-control" name="op_order_amount" value="{{ $depositlist->first()->op_order_amount }}" required autofocus>					
											<p class="help-block"></p>
											@if($errors->has('op_order_amount'))
												<p class="help-block text-red">
													{{ $errors->first('op_order_amount') }}
												</p>
											@endif
										</div>
										<div class="col-xs-4 form-group">
											<label for="op_order_receipt_date" class="control-label">{{ __('Date *') }}</label>
											<input id="op_order_receipt_date" type="text" class="form-control" name="op_order_receipt_date" value="{{ $depositlist->first()->op_order_receipt_date }}" required autofocus>
											<p class="help-block"></p>
											@if($errors->has('op_order_receipt_date'))
												<p class="help-block text-red">
													{{ $errors->first('op_order_receipt_date') }}
												</p>
											@endif
										</div>
									</div>
								@endif
							</div>			
							<div class="row" id="cheque" style="display:none;">
								@if($depositlist->first()->op_order_mode=="Cheque")
									<div class="row">
										<div class="col-xs-6 form-group">
											<div class="f-half">					
												<label for="op_order_cheque_no" class="control-label">{{ __('Cheque number*') }}</label>
												<input id="op_order_cheque_no" type="text" class="form-control" name="op_order_cheque_no" value="{{ $depositlist->first()->op_order_cheque_no }}" required autofocus>
												<p class="help-block"></p>
												@if($errors->has('op_order_cheque_no'))
													<p class="help-block text-red">
														{{ $errors->first('op_order_cheque_no') }}
													</p>
												@endif
											</div>
											<div class="f-half">
												<label for="op_order_amount" class="control-label">{{ __('Amount*') }}</label>
												<input id="op_order_amount" type="text" class="form-control" name="op_order_amount"  value="{{ $depositlist->first()->op_order_amount }}"required autofocus>
												<p class="help-block"></p>
												@if($errors->has('op_order_amount'))
													<p class="help-block text-red">
														{{ $errors->first('op_order_amount') }}
													</p>
												@endif
											</div>
										</div>
									 	<div class="col-xs-6 form-group">
									 		<div class="f-half">
												<label for="op_order_cheque_bank" class="control-label">{{ __('Bank Name *') }}</label>		 
												<select id="op_order_cheque_bank" type="text" class="form-control" name="op_order_cheque_bank" autofocus onclick="getifsccode()">					
													@if(!empty($bankname))                    
													@foreach($bankname as $bankname)
													<option value="{{ $bankname->op_bank_name }}" >{{ $bankname->op_bank_name }}</option>
													@endforeach
													@endif					
												</select>
												<p class="help-block"></p>
												@if($errors->has('op_order_cheque_bank'))
													<p class="help-block text-red">
														{{ $errors->first('op_order_cheque_bank') }}
													</p>
												@endif
											</div>
											<div class="f-half">
												<label for="op_order_cheque_ifsc" class="control-label">{{ __('IFSC Code *') }}</label>
												<input id="op_order_cheque_ifsc" type="text" class="form-control" name="op_order_cheque_ifsc" value="{{ $depositlist->first()->op_order_cheque_ifsc }}" required autofocus>
												<p class="help-block"></p>
												@if($errors->has('op_order_cheque_ifsc'))
													<p class="help-block text-red">
														{{ $errors->first('op_order_cheque_ifsc') }}
													</p>
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 form-group">
											<div class="f-half">
												<label for="op_order_cheque_date" class="control-label">{{ __('Date *') }}</label>
												<input id="op_order_cheque_date" type="text" class="form-control pull-right" name="op_order_cheque_date" value="{{ $depositlist->first()->op_order_cheque_date }}" required autofocus>
												<p class="help-block"></p>
												@if($errors->has('op_order_cheque_date'))
													<p class="help-block text-red">
														{{ $errors->first('op_order_cheque_date') }}
													</p>
												@endif
											</div>
										</div>
									</div>
								@endif
							</div>
							<div class="row">
								<div class="btn-up-center form-group">
									<button type="submit" class="btn btn-success">
										{{ __('Update') }}
									</button>
								</div>
							</div>	
						</form>
					</div>
				</div>
			</div>
		</div>
		@if($depositlist->first()->op_order_mode=="Cash")		
			<script type="text/javascript">			
				$('#cash').show();
				$('#cheque').hide();
			</script>
		@else
			<script type="text/javascript">
				$('#cash').hide();
				$('#cheque').show();
			</script>		
		@endif
	 
		<script type="text/javascript">
		$('#op_order_cheque_date').datepicker({
			  format: 'yyyy-mm-dd'
		});
		$('#op_order_receipt_date').datepicker({
			  format: 'yyyy-mm-dd'
		});    
		</script>
		<script type="text/javascript">
		function getifsccode() 
		{  
		  
		  var op_order_cheque_bank=$("#op_order_cheque_bank").val();  
			$.ajax({
				url :"{{ route('getifsccodedb') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"op_order_cheque_bank": op_order_cheque_bank
					},
				success : function(data){
					$("#op_order_cheque_ifsc").attr("value",data);
				}
			});        
		}
		</script>
	@endif
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
@endsection


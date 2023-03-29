@extends('layouts.app')

@section('content-header')
	<h1>
		Price Management
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Price</li>
	</ol>
@endsection

<!-- Main Content -->
@section('content')
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		@endif
		@if(!auth()->user()->can('create_factor') && !auth()->user()->can('create_logic'))
		  	<div class="alert alert-error">
				<span> You don't have permission to view this page</span>	
			</div>
		@endif
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					<div class="nav-tabs-custom tab_manage_price">
            			<ul class="nav nav-tabs price_tab">
              				@can('create_logic')<li class="active" id="tab_1"><a href="#logic" data-toggle="tab">Create Logic</a></li>@endcan
              				@can('create_factor')<li id="tab_2"><a href="#factor" data-toggle="tab">Create Factor</a></li>@endcan             
            			</ul>
            			<div class="tab-content">
              				@can('create_logic')
              				<div class="tab-pane active" id="logic">
              					<div class="row logic_pane">
              						<div class="col-md-1">
              							<label>UID Prefix (A)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">3W</option>
											<option value="1">4W</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-2 p-l-10">
              							<label>Subscription (B)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0"></option>
											<option value="1"></option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>              							
              						</div>
              						<div class="col-md-1 p-l-8">
              							<label>Star Rating (C)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">*</option>
											<option value="1">**</option>
											<option value="2">***</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-2 p-l-8">
              							<label>Mobile No. (D)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0"></option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-1 p-l-8">
              							<label>GGT (E)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">Cash</option>
											<option value="1">Digital</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
									</div>
              						<div class="col-md-1">
              							<label>Credit Point (F)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">High</option>
											<option value="1">Medium</option>
											<option value="2">Low</option>
											<option value="3">Nil</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-1 p-l-8">
              							<label>Cash Back (G)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">High</option>
											<option value="1">Medium</option>
											<option value="2">Low</option>
											<option value="3">Nil</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-1 p-l-10">
              							<label>(H)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0"></option>
											<option value="1"></option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-1 p-l-10">
              							<label>Subscription Validity (I)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">16-30 Days</option>
											<option value="1">08-15 Days</option>
											<option value="2">02-07 Days</option>
											<option value="3">1 Day</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
									</div>
              						<div class="col-md-1 p-l-10">
              							<label>Status(J)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">Disabled</option>
											<option value="1">On Hold</option>
											<option value="1">Dispute</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
									</div>
              					</div>
              					<label class="f-label">Factor</label>
              					<div class="add_factor">
              						<div class="col_factor p-l-5">
              							<input type="text" name="" class="form-control">
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-1">
              							<input type="text" name="" class="form-control">
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">		
              							<input type="text" name="" class="form-control">
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-1">              							
              							<input type="text" name="" class="form-control">
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="" class="form-control">
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="" class="form-control">
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="" class="form-control">
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="" class="form-control">
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="" class="form-control">
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2 p-r-5">              						
              							<input type="text" name="" class="form-control">
              						</div>
              					</div>
              					<div class="row calculate">
              						<div class="col-md-1"></div>
              						<div class="col-md-8">
              							<div class="cal_factor">
	              							<div class="col_factor">
              									<label>Total (A-J)</label>
		              							<input type="text" name="" class="form-control">
		              						</div>
		              						<span class="fa fa-plus"></span>
		              						<div class="col_factor">
              									<label>Add Value</label>
		              							<input type="text" name="" class="form-control">
		              						</div>
		              						<span>
		              							<div class="equal">
			              							<i class="fa fa-minus"></i>
			              							<i class="fa fa-minus"></i>
			              						</div>
		              						</span>
		              						<div class="col_factor">
              									<label>Sub Total</label>
		              							<input type="text" name="" class="form-control">
		              						</div>
		              						<span class="fa fa-times"></span>
		              						<div class="col_factor">
              									<label>Multiply</label>
		              							<input type="text" name="" class="form-control">
		              						</div>
		              						<span>
		              							<div class="equal">
			              							<i class="fa fa-minus"></i>
			              							<i class="fa fa-minus"></i>
			              						</div>
		              						</span>
		              						<div class="col_factor">
              									<label>Sub Total</label>
		              							<input type="text" name="" class="form-control">
		              						</div>
		              						<span class="fa fa-minus"></span>
		              						<div class="col_factor">
              									<label>Subtract</label>
		              							<input type="text" name="" class="form-control">
		              						</div>
		              						<span>
		              							<div class="equal">
			              							<i class="fa fa-minus"></i>
			              							<i class="fa fa-minus"></i>
			              						</div>
		              						</span>
		              						<div class="col_factor">
              									<label>Final Factor</label>
		              							<input type="text" name="" class="form-control">
		              						</div>
		              					</div>
              						</div>	
              						<div class="col-md-3">
              							<div class="f-info">
              								<div class="col_factor">
              									<label>Date</label>
		              							<input type="text" name="" class="form-control">
		              						</div>
		              						<div class="col_factor">
              									<label>Time</label>
		              							<input type="text" name="" class="form-control">
		              						</div>
		              						<div class="col_factor">
              									<label>Created By</label>
		              							<input type="text" name="" class="form-control">
		              						</div>	
              							</div>
              						</div>
              					</div>
              					<div class="row">
              						<div class="col-md-9">
              							<div class="f-view">
		              						<table>
		              							<tr>
		              								<th>Logic ID</th>
		              								<th>Variable</th>
		              								<th>Existing Value</th>
		              								<th>New Value</th>
		              								<th>Revision Date</th>
		              								<th>Created By</th>
		              								<th>Approval Date</th>
		              								<th>Approved By</th>
		              							</tr>
		              							<tr>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              							</tr>
		              							<tr>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              								<td></td>
		              							</tr>
		              						</table>
		              					</div>
              						</div>
              						<div class="col-md-3">
              							<div class="final">
              								<div class="final_factor">
              									<label>100 Becomes</label>
              									<div class="value">119</div>
              								</div>
              								<div class="factor_appv">
              									<div class="fact_col">
              										<button class="btn btn-factor">Generate Logic ID</button>
              										<input type="text" name="" class="form-control">
              										<label>Revision ID No</label>
              									</div>
              									<div class="fact_col_1">
              										<span class="fa fa-times"></span>
              										<button class="btn btn-factor">Approval</button>
              									</div>
              								</div>
              							</div>
              						</div>
              					</div>
              				</div>
              				@endcan
              				@can('create_factor')
              				<div class="tab-pane" id="factor">
              					<form method="POST" id="saveFactor" action="{{ route('createfactor') }}">
								@csrf
              						<div class="row logic_pane">
              						<div class="col-md-1">
              							<label>UID Prefix (A)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">3W</option>
											<option value="1">4W</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-2 p-l-10">
              							<label>Subscription (B)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0"></option>
											<option value="1"></option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>              							
              						</div>
              						<div class="col-md-1 p-l-8">
              							<label>Star Rating (C)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">*</option>
											<option value="1">**</option>
											<option value="2">***</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-2 p-l-8">
              							<label>Mobile No. (D)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0"></option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-1 p-l-8">
              							<label>GGT (E)</label>
              							<input type="hidden" name="fac_variable" value="GGT(E)">
              							<select id="fac_select" name="fac_select" type="text" class="form-control" autofocus >
											<option value="">Select</option>
											<option value="cash">Cash</option>
											<option value="digital">Digital</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
									</div>
              						<div class="col-md-1">
              							<label>Credit Point (F)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">High</option>
											<option value="1">Medium</option>
											<option value="2">Low</option>
											<option value="3">Nil</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-1 p-l-8">
              							<label>Cash Back (G)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">High</option>
											<option value="1">Medium</option>
											<option value="2">Low</option>
											<option value="3">Nil</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-1 p-l-10">
              							<label>(H)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0"></option>
											<option value="1"></option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
              						</div>
              						<div class="col-md-1 p-l-10">
              							<label>Subscription Validity (I)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">16-30 Days</option>
											<option value="1">08-15 Days</option>
											<option value="2">02-07 Days</option>
											<option value="3">1 Day</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
									</div>
              						<div class="col-md-1 p-l-10">
              							<label>Status(J)</label>
              							<select id="" type="text" class="form-control" autofocus >
											<option value="0">Disabled</option>
											<option value="1">On Hold</option>
											<option value="1">Dispute</option>
										</select>
										<div class="text-right">
											<button class="btn"><i class="fa fa-times"></i></button>
										</div>
									</div>
              						</div>
              						<label class="f-label">Factor</label>
              						<div class="add_factor">
              						<div class="col_factor p-l-5">
              							<input type="text" name="" class="form-control">
              							<button class="btn btn_f_save hidebtn">save</button>
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-1">
              							<input type="text" name="" class="form-control">
              							<button class="btn btn_f_save hidebtn">save</button>
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">		
              							<input type="text" name="" class="form-control">
              							<button class="btn btn_f_save hidebtn">save</button>
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-1">              							
              							<input type="text" name="" class="form-control">
              							<button class="btn btn_f_save hidebtn">save</button>
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="fac_ggt" class="form-control">
              							<button class="btn btn_f_save">save</button>
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="" class="form-control">
              							<button class="btn btn_f_save hidebtn">save</button>
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="" class="form-control">
              							<button class="btn btn_f_save hidebtn">save</button>
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="" class="form-control">
              							<button class="btn btn_f_save hidebtn">save</button>
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2">              							
              							<input type="text" name="" class="form-control">
              							<button class="btn btn_f_save hidebtn">save</button>
              						</div>
              						<span class="fa fa-plus"></span>
              						<div class="col_factor-2 p-r-5">              						
              							<input type="text" name="" class="form-control">
              							<button class="btn btn_f_save hidebtn">save</button>
              						</div>
              						</div>
              					</form>
								<div class="row m-t-20">
									<div class="col-md-12">
										<div class="f-view">
		              						<table>
		              							<thead>
			              							<tr>
			              								<th>Variable</th>
			              								<th>Existing Value</th>
			              								<th>New Value</th>
			              								<th>Revision Date</th>
			              								<th>Created By</th>
			              								<th>Approval Date</th>
			              								<th>Approved By</th>
			              							</tr>
			              						</thead>
			              						<tbody>
			              							@if(!empty($savedfactors))
			              								@foreach ($savedfactors as $factors)
				              							<tr>
				              								<td>{{ $factors['variable_name'].'-'.$factors['variable_value'] }}</td>
				              								<td>{{ $factors['existing_value'] }}</td>
				              								<td>{{ $factors['new_value'] }}</td>
				              								<td>{{ $factors['revision_date'] }}</td>
				              								<td>{{ $factors['name'] }}</td>
				              								<td>{{ $factors['approval_date'] }}</td>
				              								<td>{{ $factors['approved_by'] }}</td>
				              							</tr>
				              							@endforeach
			              							@else
													<tr>
														<td colspan="9">No entries in table</td>
													</tr>
													@endif
			              						</tbody>
		              						</table>
		              					</div>		
									</div>
								</div>              					
              				</div>
              				@endcan
	              		</div>
            			<!-- /.tab-content -->
          			</div>					
				</div>
			</div>
		</div>
	</div>
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<!-- page script -->
<script>
	$(function () {
		$(document).ready(function () {
		var isCreateLogic = "{{ auth()->user()->can('create_logic')}}";
		if(isCreateLogic==1){
			$("#tab_1").addClass("active");
			$("#tab_2").removeClass("active");
			$("#logic").addClass("active in");
			$("#factor").removeClass("active in");
		}else{
			$("#tab_2").addClass("active");
			$("#tab_1").removeClass("active");
			$("#factor").addClass("active in");
			$("#logic").removeClass("active in");
		}	
		});
	})
</script>
<script>
	$(document).ready(function(){
		$(".hidebtn").prop('disabled', true);
	});
</script>
@endsection
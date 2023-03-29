
@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
		<h1>{{ $header }}<!--<small>(Vendors)</small>--></h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
			<li>{{ $header }}</li>
			<li class="active">Edit</li>
		</ol>
@endsection
<!-- Main Content -->
@section('content')
	@if(session('success'))
		<div class="row">
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		</div>
	@else
		<div class="panel-body p-0">
			<div class="view-op">
				<!--Personal Information -->
				<div class="row">
					<div class="col-sm-12 form-group section-title">Personal Information</div>
					<div class="section">
						<div class="row">
							<div class="col-sm-3">
								<img src = 'data:image/png;base64,{{ $operator->op_profile_pic }}' class="img-responsive p-img">
							</div>
							<div class="col-sm-9">
								<div class="row">
									<div class="col-sm-6 form-group">
										<label for="op_first_name" class="control-label">{{ __('First Name: ') }}</label> 
										@if(!empty( $operator->op_first_name ))                   
							 				{{ $operator->op_first_name }}
						  				@else
							  				N.A.
						  				@endif               
									</div>
									<div class="col-sm-6 form-group">
										<label for="op_last_name" class="control-label">{{ __('Last Name:') }}</label> 
							 				@if(!empty( $operator->op_last_name )) 
												{{ $operator->op_last_name }}
											@else
							  					N.A.
						  					@endif  					   
									</div>
								</div>
								<div class="row">
								  	<div class="col-sm-6 form-group">
										<label for="op_dob" class="control-label">{{ __('Date of Birth:') }}</label>
										@if(!empty( $operator->op_dob )) 
											{{ $operator->op_dob }}    
										@else
										  N.A.
									  	@endif                  
									</div>
									<div class="col-sm-6 form-group">
										<label for="op_gender" class="control-label">{{ __('Gender:') }}</label>  
										@if(isset($operator->op_gender)) 
											@if($operator->op_gender==0)
												Female
											@elseif($operator->op_gender==1)
												Male
											@else
												Other
											@endif 
										@endif  
									</div>								
								</div>
								<div class="row">
									<div class="col-sm-6 form-group">
										<label for="op_email" class="control-label">{{ __('Email:') }}</label>
										@if(!empty( $operator->op_email )) 
										{{ $operator->op_email }}
										@else
										  N.A.
									  @endif  
									</div>
									<div class="col-sm-6 form-group">
										<label for="op_mobile_no" class="control-label">{{ __('Pet Name:') }}</label>
										{{ $operator->op_pet_name ? $operator->op_pet_name : 'N.A.' }}
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6 form-group">
										<label for="op_mobile_no" class="control-label">{{ __('Registered Mobile Number:') }}</label>
										@if(!empty( $operator->op_mobile_no )) 
											{{ $operator->op_mobile_no }}
										@else
											N.A.
									  	@endif  
									</div>
									<div class="col-sm-6 form-group">
										<label for="op_alternative_mobile_no" class="control-label">{{ __('Alternative Mobile Number:') }}</label>
										@if(!empty( $operator->op_alternative_mobile_no )) 
											{{ $operator->op_alternative_mobile_no }}  
										@else
											N.A.
									  	@endif                
									</div>
								</div>
							</div>
						</div>
						<h4 class="addr">Address</h4>
						<div class="row">
							<div class="col-sm-3 form-group">
								<label for="op_address_pin_code" class="control-label">{{ __('PIN Code:') }}</label>
								{{ $operator->op_address_pin_code ? $operator->op_address_pin_code : 'N.A.' }} 
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_address_state" class="control-label">{{ __('State:') }}</label>
								@if(!empty($address->first()->state)) 
									{{ $address->first()->state }}
								@else
								  	N.A.
							  	@endif  
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_address_city" class="control-label">{{ __('City:') }}</label>
								@if(!empty($address->first()->city)) 
									{{ $address->first()->city }}
								@else
								  	N.A.
							  	@endif                    	
							</div>
							<div class="col-sm-3 form-group">
								<label for="op_address_line_3" class="control-label">{{ __('Location:') }}</label>
								@if(!empty($operator->op_address_line_3)) 
									{{ $operator->op_address_line_3 }}
								@else
							  		N.A.
						  		@endif  
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4 form-group">
								<label for="op_address_line_1" class="control-label">{{ __('Flat/Shop/Place:') }}</label>
								@if(!empty($operator->op_address_line_1)) 
									{{ $operator->op_address_line_1 }}
								@else
									N.A.
							  	@endif  
							</div>
							<div class="col-sm-4 form-group">
								<label for="op_address_line_2" class="control-label">{{ __('Street/Area:') }}</label>
								@if(!empty($operator->op_address_line_2)) 
									{{ $operator->op_address_line_2 }}
								@else
							  		N.A.
						  		@endif  
							</div>
							<div class="col-sm-4 form-group">
								<label for="op_landmark" class="control-label">{{ __('Landmark:') }}</label>
								@if(!empty($operator->op_landmark)) 
									{{ $operator->op_landmark }}
								@else
								  	N.A.
							  	@endif  
							</div>
						</div>
					</div>					
				</div>
				<!-- End Personal information -->	
				
				<!-- Driver  information -->
				<div class="row" id="BusinessDriver">
					<div class="col-sm-12 section-title">Driver Information</div>
					<div class="section">
						<div class="row">
							@if(count($driver)!=0)
								<table id="driver" class="table view-d-op table-bordered table-striped">
									<thead>
										<tr>
											<th class="text-center">Sr.No</th>
											<th>Driver Name</th>
											<th>Owner Name</th>
											<th>Verification</th>
											<th>Status</th>
											<th>Driver Profile Photo</th>                  
											<th>Driver Mobile No</th>						  
										</tr>
									</thead>
									<tbody>
										<?php $count=1; ?>
										@foreach ($driver as $drivers)
											<tr>
												<td><center>{{ $count++ }}</center></td>
												<td>{{ $drivers->driver_first_name }}
													{{ $drivers->driver_last_name }} </td>
												<td>empty</td>
												<td>
												  @if($drivers->driver_is_verified==1)
												  	verified 
												  @else
												  	Not verified
												  @endif
												</td>								
												<td>  
													@if($drivers->is_active==1)
														Active
													@else
														Inactive
													@endif
												</td>
												<td>                        
													<i class="fa fa-fw fa-image" onclick="showdriverimage('{{  $drivers->driver_profile_pic }}')" data-toggle="modal" data-target="#driver_image"></i>                        
												</td>
												<td>{{ $drivers->driver_mobile_number }}</td>							
											</tr>
										@endforeach	
									</tbody>
								</table>
					 		@else
					  			<div class="col-sm-12 m-t-10 form-group">No Driver Aailable</div> 
					 		@endif	
					 	</div>		   
					</div>
				</div>
				<!-- End Driver  information -->
				<!-- document section -->
				<div class="row">
					<div class="col-sm-12 section-title">Documents Information</div>
					<div class="section">
						<div class="row">
							@if(count($documents)!=0)
								<table id="document" class="table view-d-op table-bordered table-striped">
									<thead>
										<tr>
											<th class="text-center">Sr.No</th>								
											<th>Document Name</th>
											<th>Document <br> Validity</th>
											<th>Document <br> Number</th>
											<th>Document <br> Image</th>
											<th>Document <br> Verification</th> 								  
										</tr>
									</thead>
									<tbody>
								  		<?php $count=1; ?>   
								  		@foreach ($documents as $documents)
											<tr>
										  		<td><center>{{ $count++ }}</center></td>								  
										  		<td> {{ $documents->doc_label }}</td>
										  		<td>{{ $documents->doc_expiry }}</td>
										  		<td>{{ $documents->doc_number }}</td>                      
										  		<td><!-- <img src="{{ URL::asset('public/documents/$documents->doc_images')}}" width="50" height="50"> --><br>
													<i class="fa fa-fw fa-image" onclick="showimage('{{ $documents->doc_images }}')" data-toggle="modal" data-target="#modal-default"></i>										   
										  		</td>
										  		<td>{{ $documents->is_verified==1 ? 'verified':'Not verified' }}</td>
										  	</tr>								 
								  		@endforeach								 
									</tbody>                
							  </table>
						  	@else
						  		<div class="col-sm-12 m-t-10 form-group">No Document Available</div>
							@endif
						</div>
					</div>
				</div>
				<!-- end document section -->	
				<!-- Business Vehicles Information -->
			 	<div class="row" id="BusinessVehicles">
					<div class="col-sm-12 section-title">Vehicles Information</div>
					<div class="section">
						<div class="row">
							@if(count($operatorvehicles)!=0)
								<table id="driver" class="table view-d-op table-bordered table-striped">
									<thead>
										<tr>
											<th class="text-center">Sr.No</th>
											<th> Registration Number </th>
											<th> Vehicles owner Name </th>
											<th>Vehicles Image</th>
											<th> Status </th>              
										</tr>
									</thead>
									<tbody>
										<?php $count=1; ?>
										@foreach ($operatorvehicles as $operatorvehicle)							
											<tr>
												<td><center>{{ $count++ }}</center></td>
												<td>{{ $operatorvehicle['veh_registration_no']}}</td>                        
												<td>{{ $operatorvehicle['veh_owner_name']}}</td> 
												<td>
													<i class="fa fa-fw fa-image" onclick="showVehimage('{{ $operatorvehicle['veh_images_array'] }}')" data-toggle="modal" data-target="#veh_imags" ></i>
												</td>
												<td>
													@if($operatorvehicle['is_active']==0)
														Deactive
													@else
														Active
													@endif
												</td>                                              
											</tr> 
										@endforeach
									</tbody>
								</table>
							@else
								<div class="col-sm-12 m-t-10 form-group">No Vehicle Available</div>
							@endif
						</div>				
					</div>
				</div>
				<!-- End business Vehicles Information -->
				
				<div class="col-sm-12 form-group">
					<input type="checkbox" name="op_is_verified" id="op_is_verified"  {{ $operator->op_is_verified == 1 ? 'checked' : '' }} disabled> 
					<label for="op_is_verified" class="control-label">Operator is Verified.</label>
					<div class="btn-save-center">
						<a href="{{ URL::previous() }}" class="btn btn-success">Back</a>                
					</div>						
				</div>    
			</div>
		</div>		
		<!-- model code -->
		<!-- Driver photo modal -->
		<div class="modal fade" id="driver_image">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">Driver profile photo </h4>
					</div>
				  	<div class="modal-body">				
						<div id="show_driver_image"></div>
				  	</div>
				</div>
			</div>>
		</div>
		<!-- End Driver photo modal -->
		<!-- Document image modal -->
		<div class="modal fade" id="modal-default">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				  	<div class="modal-body">				
						<div id="show_docuemnt_image"></div>
				  	</div>
				</div>
			</div>
		</div>
		<!-- End document image modal -->
		<!-- Vehicle image modal -->
		<div class="modal fade" id="veh_imags">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">Vehicle Images</h4>
					</div>
					<div class="modal-body">
						<div id="driver_veh_image"></div>
					</div>
				</div>
			</div>
		</div>
		<!-- End vehicle image modal -->
		<!---model code end-->	
	@endif
@endsection

<!-- JS scripts for this page only -->
@section('javascript')

<script type="text/javascript">
function showdriverimage(path)
{
	if(path == ''){
		path = '<?php echo $operator->op_profile_pic; ?>';
		$("#show_driver_image").html(" ");
		var html = '';
		
		html += '<img class="driver-image" src = "data:image/png;base64,'+path+'">';
		
		$("#show_driver_image").append(html);
	}
	else{
		$.ajax({
				url :"{{ route('getdriverimage') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"img_path": path
				},
				success : function(data){
					$("#show_driver_image").html(" ");
					var html = '';
					if(data!="")
					{
						html += '<img class="driver-image" src = "data:image/png;base64,'+data+'">';
					}
					$("#show_driver_image").append(html);
				}
			});
	}
}

function showimage(path)
{
	   $("#show_docuemnt_image").html(" "); 
	   var html1 = '';
	   if(path!="")
	   {		 
			html1 +='<img class="docuemnt-image" src = "data:image/png;base64,'+path+'">';
	   }        
	   $("#show_docuemnt_image").append(html1);
}

function showVehimage(images)
{
		$("#driver_veh_image").html(" "); 
		var html2 = '';
		if(images !== null)
		{
			var data = JSON.parse(images);
			$.each(data, function (key, value)
			{
				html2 += '<img class="veh-image" src = "data:image/png;base64,'+value+'">';
			});
			
			$("#driver_veh_image").append(html2);
		}
}	
</script>
@endsection


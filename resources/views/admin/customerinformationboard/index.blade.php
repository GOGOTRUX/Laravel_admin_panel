<style type="text/css">
	.s-top{
		margin-top:22px;
	}
}
</style>
@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Customer Home
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Customer Board</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		@if(session('success'))
			<div class="alert alert-success" id="success-message">
				{{ session('success') }}
			</div>
		@endif
		@if(session('error'))
			<div class="alert alert-error" id="fail-message">
				{{ session('error') }}
			</div>
		@endif
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					<div class="box-group dr-home" id="accordion">
						 <!-- customer Information board start-->
						<div class="panel box box-primary">
							<div class="box-header with-border">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
									<h4 class="box-title">Customer Information Board</h4>
							  	</a>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in">
								<div class="box-body">
							  		<p>
										<a href="{{ route('customerinformationboard.create',['type' => 'informationBoard']) }}" class="btn btn-xs btn-success">Add new</a>
									</p>
									<table class="table table-bordered table-striped list {{ count($info_board) > 0 ? 'datatable' : '' }}" data-page-length="10">
										<thead>
											<tr>
												<th class="sr-col">Sr.No</th>
												<th class="d-col-1">Text</th>
												<th class="d-col-2">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php $count=1; ?>
											@if(count($info_board) > 0)
												@foreach ($info_board as $info)
												<tr>
													<td class="sr-col">{{ $count++ }}</td>
													<td class="d-col-1">
														{{ $info['info_board_text'] }}
													</td>
													@if(!empty($latest_info['id']))
													@if($latest_info['id'] == $info['id'])
														<td>
															<a href="{{ route('customerinformationboard.edit',['type' => 'infoBoard','id' => $info['id']]) }}">
																<button class="btn btn-xs btn-primary" type="button"><i class="fa fa-edit"></i></button>
															</a>
																<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete-modal" onclick="deleteModal('{{ $info['id'] }}')">
																	<i class="fa fa-fw fa-trash" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Delete Information Board"></i>
																</button>								
														</td>
													@else
													<td>
														<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete-modal" onclick="deleteModal('{{ $info['id'] }}')">
																	<i class="fa fa-fw fa-trash" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Delete Information Board"></i>
																</button>
													</td>
													@endif
													@endif
												</tr>
												@endforeach
											@else
												<tr>
													<td></td>
													<td colspan="9">No entries in table</td>
												</tr>
											@endif
										</tbody>
									</table>	
								</div>
								</div>
							</div>
						<!-- customer Information board end-->
						<!-- confirm Quote Information board start-->
		                <div class="panel box box-primary">
		                	<div class="box-header with-border">
		                    	<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
		                    		<h4 class="box-title">Quote for the day</h4>
		                      	</a>
		                  	</div>
		                  	<div id="collapseTwo" class="panel-collapse collapse">
		                    	<div class="box-body">
		                      		<p>
		                      			<?php $quateboard = "quateboard";?>
										<a href="{{ route('customerinformationboard.create',['type' => 'quoteBoard']) }}" class="btn btn-xs btn-success">Add new</a>
									</p>
									<table class="table table-bordered table-striped list {{ count($quote_board_text) > 0 ? 'datatable' : '' }}" data-page-length="10">
										<thead>
											<tr>
												<th class="sr-col">Sr.No</th>
												<th class="d-col-1">Text</th>
												<th class="d-col-2">Actions</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php $count=1; ?>
											@if(count($quote_board_text) > 0)
												@foreach ($quote_board_text as $quote)
												<tr>
													<td class="sr-col">{{ $count++ }}</td>
													<td class="d-col-1">
														{{ $quote['quote_board_text'] }}
													</td>

													@if($latest_quote['id'] == $quote['id'])
													<td>
														<a href="{{ route('customerinformationboard.edit',['type' => 'quoteBoard','id' => $quote['id']]) }}">
																<button class="btn btn-xs btn-primary" type="button"><i class="fa fa-edit"></i></button>
														</a>
															
														<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete-modal" onclick="deleteQuoteModal('{{ $quote['id'] }}')">
																	<i class="fa fa-fw fa-trash" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Delete Quote Board"></i>
																</button>
													</td>
													@else
													<td>											
														<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete-modal" onclick="deleteQuoteModal('{{ $quote['id'] }}')">
																	<i class="fa fa-fw fa-trash" aria-hidden="true" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Delete Quote Board"></i>
																</button>
													</td>
													@endif
												</tr>
												@endforeach
											@else
												<tr>
													<td></td>
													<td colspan="9">No entries in table</td>
												</tr>
											@endif
										</tbody>
									</table>	
		                    	</div>
		                  	</div>
		                </div>
		                <!-- confirm Quote Information board end-->
		                <!-- customer dynamic images start-->
		                <div class="panel box box-primary">
		                	<div class="box-header with-border">
		                    	<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
		                    		<h4 class="box-title">Customer Content Images</h4>
		                      	</a>
		                  	</div>
		                  	<div id="collapseThree" class="panel-collapse collapse">
		                    	<div class="box-body">
		                      		<div class="box-body">
										<div class="row">
 											<div class="col-md-6 form-group">
												<!-- dashboard image start -->
 												<div class="dr-box">
 													<form method="POST" id="dashboardImg" name="dashboard_img" action="{{ route ('cust_dynamic_img') }} " enctype="multipart/form-data">  
													@csrf
														<label for="dash_img_slider" class="control-label">DASHBOARD IMAGES:</label>
	 													<div class="v-lic">
	 														<input type="file" name="dash_img_slider" id="dash_img_slider" class="form-control ggt-img dash_img_slider" accept="image/jpg,image/png,image/jpeg,image">
	 														<input type="hidden" name="image_type" id="image_type" value="DASHBOARD">
	 														<i class="fa fa-fw fa-image" onclick="preview_dashboard_image()" data-toggle="modal" data-target="#dashboard_imags" ></i>
 														<input type="submit" class="btn btn-xs btn-success pull-right" value="Submit">
 														</div> 
 														<label id="dash_img_slider-error" class="error" for="dash_img_slider"></label>
 													</form>
													<table class="table table-bordered table-striped list">
														<thead>
															<tr>
																<th class="sr-col">Sr.No</th>
																<th class="d-col-1">Image Name</th>
																<th class="d-col-2">Actions</th>
															</tr>
														</thead>
														<tbody>
															<?php $count=1; ?>
															@if(count($dashboardImages) > 0)
																@foreach ($dashboardImages as $img)
																<tr>
																	<td class="sr-col">{{ $count++ }}</td>
																	<td class="d-col-1">{{ $img['image_name'] }}</td>
																	<td class="d-col-2">
																	<a href="{{ route('cust_dynamic_img/edit/',[$img['id']]) }}">
																		<button class="btn btn-xs btn-primary" type="button"><i class="fa fa-edit"></i></button>
																	</a>
																	<button class="btn btn-xs btn-danger" type="button" onclick="delete_image('{{ $img['id'] }}','banner')"><i class="fa fa-trash"></i></button>
																	</td>
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
												<!-- dashboard image End -->
												<!-- my lucky offer image start -->
 												<div class="dr-box">
 													<form method="POST" id="myluckysingleImg" name="my_lucky_offer_single_img" action="{{ route ('cust_dynamic_img') }} " enctype="multipart/form-data">
 													@csrf
                                            			<label for="my_lucky_offer_img" class="control-label"> MY LUCKY OFFER IMAGE SINGLE:</label>
	 													<div class="v-lic">
	 														<div class="mt_image">
	 															<input id="my_lucky_offer_img" type="file" class="form-control ggt-img maintanace_tip" name="my_lucky_offer" value=""  autofocus onchange="preview_mylucky_pic();">
	 															<input type="hidden" name="image_type" id="image_type" value="MYLUCKYSINGLE">
	                                                    		<p class="help-block-message"></p>
	                                                			@if($errors->has('my_lucky_offer_img'))
			                                                        <p class="help-block-message">
			                                                            {{ $errors->first('my_lucky_offer_img') }}
			                                                        </p>
	                                                			@endif		
	 														</div>
				                                            <div class="view_mittr_image" id="mylucky_image_div" style="display: none">
				                                            	<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#mylucky_offer_mdl" ></i>
				                                            </div>
				                                        	<input type="submit" class="btn btn-xs btn-success pull-right" value="Submit">
	                                            		</div>
                                            		</form>
                                            		<table class="table table-bordered table-striped list">
														<thead>
															<tr>
																<th class="sr-col">Sr.No</th>
																<th class="d-col-1">Image Name</th>
																<th class="d-col-2">Actions</th>
															</tr>
														</thead>
														<tbody>
															<?php $count=1; ?>
															@if(count($myluckySingleImages) > 0)
																@foreach ($myluckySingleImages as $img)
																<tr>
																	<td class="sr-col">{{ $count++ }}</td>
																	<td class="d-col-1">{{ $img['image_name'] }}</td>
																	<td class="d-col-2">
																	<a href="{{ route('cust_dynamic_img/edit/',[$img['id']]) }}">
																		<button class="btn btn-xs btn-primary" type="button"><i class="fa fa-edit"></i></button>
																	</a>
																	<button class="btn btn-xs btn-danger" type="button" onclick="delete_image('{{ $img['id'] }}','banner')"><i class="fa fa-trash"></i></button>
																	</td>
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
												<!-- my lucky offer End -->
												<!-- accural image start -->
 												<div class="dr-box">
 													<form method="POST" id="accuralImg" name="accural_img" action="{{ route ('cust_dynamic_img') }} " enctype="multipart/form-data">
 													@csrf
                                            			<label for="accural_img" class="control-label"> ACCURAL IMAGE:</label>
 														<div class="v-lic">
 															<div class="mt_image">
 																<input id="accural_img" type="file" class="form-control ggt-img maintanace_tip" name="accural_img" value=""  autofocus onchange="preview_accural_pic();">
 																<input type="hidden" name="image_type" id="image_type" value="ACCURAL">
                                                    			<p class="help-block-message"></p>
                                                				@if($errors->has('accural_img'))
		                                                        	<p class="help-block-message">
		                                                            	{{ $errors->first('accural_img') }}
		                                                        	</p>
                                                				@endif		
 															</div>
			                                            	<div class="view_mittr_image" id="accural_image_div" style="display: none">
			                                            		<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#accural_mdl" ></i>
			                                            	</div>
			                                            	<input type="submit" class="btn btn-xs btn-success pull-right" value="Submit">
                                            			</div>
                                            		</form>
                                            		<table class="table table-bordered table-striped list">
														<thead>
															<tr>
																<th class="sr-col">Sr.No</th>
																<th class="d-col-1">Image Name</th>
																<th class="d-col-2">Actions</th>
															</tr>
														</thead>
														<tbody>
															<?php $count=1; ?>
															@if(count($accuralImages) > 0)
																@foreach ($accuralImages as $img)
																<tr>
																	<td class="sr-col">{{ $count++ }}</td>
																	<td class="d-col-1">{{ $img['image_name'] }}</td>
																	<td class="d-col-2">
																	<a href="{{ route('cust_dynamic_img/edit/',[$img['id']]) }}">
																		<button class="btn btn-xs btn-primary" type="button"><i class="fa fa-edit"></i></button>
																	</a>
																	<button class="btn btn-xs btn-danger" type="button" onclick="delete_image('{{ $img['id'] }}','banner')"><i class="fa fa-trash"></i></button>
																	</td>
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
												<!-- accural image End -->
												<!-- my lucky offer multiple image start -->
 												<div class="dr-box">
 													<form method="POST" id="myLuckyOfferMultipleImg" name="mylucky_offer_multiple_img" action="{{ route ('cust_dynamic_img') }} " enctype="multipart/form-data">  
													@csrf
													<label for="mylucky_img_multi_slider" class="control-label">MY LUCKY OFFER MULTIPLE IMAGES:</label>
 													<div class="v-lic">
 														<input type="file" name="mylucky_img_multi_slider" id="mylucky_img_multi_slider" class="form-control ggt-img mylucky_img_multi_slider" accept="image/jpg,image/png,image/jpeg,image">
 														<input type="hidden" name="image_type" id="image_type" value="MYLUCKYMULTIPLE">	
														<i class="fa fa-fw fa-image" onclick="preview_mylucky_multiple_image()" data-toggle="modal" data-target="#mylucky_multi_imags" ></i>
 													<input type="submit" class="btn btn-xs btn-success pull-right" value="Submit">
 													</div> 
 													<label id="mylucky_img_multi_slider-error" class="error" for="mylucky_img_multi_slider"></label>
 													<table class="table table-bordered table-striped list">
														<thead>
															<tr>
																<th class="sr-col">Sr.No</th>
																<th class="d-col-1">Image Name</th>
																<th class="d-col-2">Actions</th>
															</tr>
														</thead>
														<tbody>
															<?php $count=1; ?>
															@if(count($myluckyMultipleImages) > 0)
																@foreach ($myluckyMultipleImages as $img)
																<tr>
																	<td class="sr-col">{{ $count++ }}</td>
																	<td class="d-col-1">{{ $img['image_name'] }}</td>
																	<td class="d-col-2">
																	<a href="{{ route('cust_dynamic_img/edit/',[$img['id']]) }}">
																		<button class="btn btn-xs btn-primary" type="button"><i class="fa fa-edit"></i></button>
																	</a>
																	<button class="btn btn-xs btn-danger" type="button" onclick="delete_image('{{ $img['id'] }}','banner')"><i class="fa fa-trash"></i></button>
																	</td>
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
												<!-- my lucky offer multiple image End -->
											</div> 
										</div>	
		                    		</div>
		                    	</div>
		                  	</div>
		                </div>
		                <!-- customer dynamic images start-->
					</div>
				</div> 
			</div>
		</div>
	</div>
	<!-- dashboard image modal start-->
	<div class="modal fade" id="dashboard_imags">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title">Dashboard Images</h4>
               </div>
               <div class="modal-body" >
                   <div id="dashboard_image">
                   </div>
               </div>
           </div>
       </div>
	</div>
	<!-- dashboard image modal end-->
	<!-- mylucky offer image modal start -->
	<div class="modal fade" id="mylucky_offer_mdl">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">My Lucky Offer Single</h4>
				</div>
				<div class="modal-body" >
					<div id="view_mylucky_offer">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- mylucky offer image modal end -->
	<!-- accural image modal start -->
	<div class="modal fade" id="accural_mdl">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Accural Image</h4>
				</div>
				<div class="modal-body" >
					<div id="view_accural_img">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- accural image modal end -->
	<!-- mylucky offer image multiple modal start-->
	<div class="modal fade" id="mylucky_multi_imags">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title">My Lucky Offer Multiple Images</h4>
               </div>
               <div class="modal-body" >
                   <div id="mylucky_multi_image">
                   </div>
               </div>
           </div>
       </div>
	</div>
	<!-- mylucky offer image multiple modal end-->
@endsection

@section('javascript')
<script language="javascript" type="text/javascript">

	function preview_mylucky_pic() 
	{
		$('#view_mylucky_offer').html('');
		var output = document.getElementById("my_lucky_offer_img");
		var total_file = document.getElementById("my_lucky_offer_img").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#edit_mylucky_offer_img_div').hide()
			$('#mylucky_image_div').show()
			$('#view_mylucky_offer').append("<img src='"+output.src+"' class='veh-mt'>");
		}else{
			$('#edit_mylucky_offer_img_div').show()
			$('#mylucky_image_div').hide()
			$('#view_mylucky_offer').html('');
		}
	}

	function preview_accural_pic() 
	{
		$('#view_accural_img').html('');
		var output = document.getElementById("accural_img");
		var total_file = document.getElementById("accural_img").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#accural_img_div').hide()
			$('#accural_image_div').show()
			$('#view_accural_img').append("<img src='"+output.src+"' class='veh-mt'>");
		}else{
			$('#accural_img_div').show()
			$('#accural_image_div').hide()
			$('#view_accural_img').html('');
		}
	}

    function preview_dashboard_image() 
	{
		$('#dashboard_image').html('');
		var total_files=document.getElementById("dash_img_slider").files.length;
		var file1 = document.getElementById("dash_img_slider");
		if(total_files > 0){
			for(var i=0;i<total_files;i++)
			{
				$('#dashboard_image').append("<img src='"+URL.createObjectURL(file1.files[i])+"' class='veh-image'>");
			}
		}else{
			$('#dashboard_image').html('');
		}
	}

    function preview_mylucky_multiple_image() 
	{
		$('#mylucky_multi_image').html('');
		var total_files=document.getElementById("mylucky_img_multi_slider").files.length;
		var file1 = document.getElementById("mylucky_img_multi_slider");
		if(total_files > 0){
			for(var i=0;i<total_files;i++)
			{
				$('#mylucky_multi_image').append("<img src='"+URL.createObjectURL(file1.files[i])+"' class='veh-image'>");
			}
		}else{
			$('#mylucky_multi_image').html('');
		}
	}

	function deleteModal(id){
		swal({
			title: 'Are you sure?',
			text: "Delete a Informtion Board!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('info-board-delete') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Deleted!", text: "Deleted Information Board", type: result.status}).then(function(){ 
							location.reload();
						});
					}
					else{
						swal({title: "Oops!", text: "Failed to delete Information Board", type: result.status}).then(function(){
							location.reload();
						});
					}
				}
			});
		})
	}

	function deleteQuoteModal(id){
		swal({
			title: 'Are you sure?',
			text: "Delete a quote Board!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('deletequote') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Deleted!", text: "Deleted Quote Board", type: result.status}).then(function(){ 
							location.reload();
						});
					}
					else{
						swal({title: "Oops!", text: "Failed to delete Quote Board", type: result.status}).then(function(){
							location.reload();
						});
					}
				}
			});
		})
	}
	
</script>

@endsection

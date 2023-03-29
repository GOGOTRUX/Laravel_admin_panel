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
		Driver Home
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">driverhome</li>
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
		                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
		                <!-- Driver home banner images start-->
		                <div class="panel box box-primary">
							<div class="box-header with-border">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
									<h4 class="box-title">Driver Home Banner Images</h4>
								</a>
							</div>
		                  	<div id="collapseOne" class="panel-collapse collapse in">
		                    	<div class="box-body">
									<p>
										<a href="{{ route('driverhome.create') }}" class="btn btn-xs btn-success">Add New Image</a>
									</p>
									<table class="table table-bordered table-striped list {{ count($data) > 0 ? 'datatable' : '' }}" data-page-length="10">
										<thead>
											<tr>
												<th class="sr-col">Sr.No</th>
												<th class="d-col-1">Image Name</th>
												<th class="d-col-2">Actions</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php $count=1; ?>
											@if(count($data) > 0)
												@foreach ($data as $names)
												<tr>
													<td class="sr-col">{{ $count++ }}</td>
													<td class="d-col-1">{{ $names['banner_image'] }}</td>
													<td class="d-col-2">
													<a href="{{ route('driverhome.edit',[$names['id']]) }}">
														<button class="btn btn-xs btn-primary" type="button"><i class="fa fa-edit"></i></button>
													</a>
														
													<button class="btn btn-xs btn-danger" type="button" onclick="delete_image('{{ $names['id'] }}','banner')"><i class="fa fa-trash"></i></button>
													</td>
													<td></td>
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
		                <!-- Driver home banner images end-->
		                <!-- Information board start-->
		                <div class="panel box box-primary">
		                	<div class="box-header with-border">
		                    	<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
		                    		<h4 class="box-title">Information Board</h4>
		                      	</a>
		                  	</div>
		                  	<div id="collapseTwo" class="panel-collapse collapse">
		                    	<div class="box-body">
		                      		<p>
										<a href="{{ route('informationboard') }}" class="btn btn-xs btn-success">Add new</a>
									</p>
									<table class="table table-bordered table-striped list {{ count($info_board) > 0 ? 'datatable' : '' }}" data-page-length="10">
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
											@if(count($info_board) > 0)
												@foreach ($info_board as $info)
												<tr>
													<td class="sr-col">{{ $count++ }}</td>
													<td class="d-col-1">
														{{ $info['info_board_text'] }}
													</td>
													<td>
														<a href="{{ route('informationboard/edit/',[$info['id']]) }}">
															<button class="btn btn-xs btn-primary" type="button"><i class="fa fa-edit"></i></button>
														</a>
															
														<button class="btn btn-xs btn-danger" type="button" onclick="delete_image('{{ $info['id'] }}','infoboard')"><i class="fa fa-trash"></i></button>
													</td>
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
		                <!-- Information board end-->
		                <!-- Driver katta start-->
		                <div class="panel box box-primary dr-katta">
		                	<div class="box-header with-border">
		                      	<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
		                    		<h4 class="box-title">Driver Katta</h4>
		                      	</a>
		                  	</div>
		                  	<div id="collapseFour" class="panel-collapse collapse">
		                    	<div class="box-body">
									<form method="POST" id="driverKatta" name="driver_katta" action="{{ route ('driver_katta') }} " enctype="multipart/form-data" data-page-length="10">  
									@csrf
										<div class="row">
 											<div class="col-md-6 form-group">
												<input type="hidden" name="edit_id" value="{{$edit_id}}">
												<!-- up slider start -->
 												<div class="dr-box">
													<label for="katta_up_slider" class="control-label">UPSLIDER IMAGES:(Upload maximum 3 images)</label>
 													<div class="v-lic">
 														<input type="file" name="katta_up_slider[]" id="katta_up_slider" class="form-control ggt-img katta_up_slider" accept="image/jpg,image/png,image/jpeg,image"  multiple="">		
														@if(!empty($imagedriverkatta['upslider_image']['driverkatta_img_data']))
															<i class="fa fa-fw fa-image" onclick="showSliderimage('{{json_encode(isset($imagedriverkatta['upslider_image']['driverkatta_img_data']) ? $imagedriverkatta['upslider_image']['driverkatta_img_data']:null) }} ')" data-toggle="modal" data-target="#upslider_imags" ></i>
														@else
															<i class="fa fa-fw fa-image" onclick="preview_image()" data-toggle="modal" data-target="#upslider_imags" ></i>
														@endif
 													</div> 
 													<label id="katta_up_slider-error" class="error" for="katta_up_slider"></label>
 												</div>
												<!-- up slider End -->
												<!-- laughter corner start-->
 												<div class="dr-box">
													<label for="katta_bottom_slider" class="control-label">LAUGHTER CORNERS:(Upload maximum 3 images)</label>
 													<div class="v-lic">
														<input type="file" name="katta_bottom_slider[]" id="katta_bottom_slider" class="form-control ggt-img katta_bottom_slider" multiple="true">
														@if(!empty($imagedriverkatta['upslider_image']['driverkatta_img_data']))
															<i class="fa fa-fw fa-image" onclick="laughterCornerimage( '{{json_encode(isset($imagedriverkatta['laughter_image']['driverkatta_img_data']) ? $imagedriverkatta['laughter_image']['driverkatta_img_data']:null) }} ') " data-toggle="modal" data-target="#laughter_imags" ></i>
														@else
															<i class="fa fa-fw fa-image" onclick="preview_image_laughter()" data-toggle="modal" data-target="#laughter_imags" ></i>
														@endif
													</div>
													<label id="katta_bottom_slider-error" class="error" for="katta_bottom_slider"></label>
												</div>
												<!-- laughter corner End-->
												<!-- Maintanance tips start--> 
												<div class="dr-box">
                                            		<label for="katta_mt_tips_image" class="control-label"> MAINTENANCE TIPS IMAGE:</label>
 													<div class="v-lic">
 														<div class="mt_image">
 															<input id="katta_mt_tips_image" type="file" class="form-control ggt-img maintanace_tip" name="katta_mt_tips[]" value="{{$imagedriverkatta['url_katta_mt_tips_image']}}"  autofocus onchange="preview_mt_pic();">
                                                    		<p class="help-block-message"></p>
                                                			@if($errors->has('katta_mt_tips_image'))
		                                                        <p class="help-block-message">
		                                                            {{ $errors->first('katta_mt_tips_image') }}
		                                                        </p>
                                                			@endif		
 														</div>
 														<div class="show_mt_image" id="edit_mt_images_div">
			                                                @if(!empty($imagedriverkatta['url_katta_mt_tips_image']))
			                                                    <img src = '{{ $imagedriverkatta['url_katta_mt_tips_image'] }}'>
			                                                @endif
		                                            	</div>
			                                            <div class="view_mittr_image" id="mt_images_div" style="display: none">
			                                            	<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_mt" ></i>
			                                            </div>
                                            		</div>
												</div>
												<!-- Maintanance tips End-->
												<!-- Loading/Uploading tips start-->
												<div class="dr-box">
													<label for="url_katta_load_unload_image" class="control-label">LOADING/UNLOADING TIPS IMAGE:</label>
													<div class="v-lic">
														<div class="mt_image">
															<input id="url_katta_load_unload_image" type="file" class="form-control p-0 ggt-img loading_tip" name="katta_load_unload_image[]" value="{{$imagedriverkatta['url_katta_load_unload_image']}}" autofocus onchange="preview_load_unload_pic();">

		                                                    <p class="help-block-message"></p>
		                                                    @if($errors->has('url_katta_load_unload_image'))
	                                                            <p class="help-block-message">
	                                                                {{ $errors->first('url_katta_load_unload_image') }}
	                                                            </p>
		                                                    @endif
														</div>
														<div class="show_mt_image" id="edit_load_unload_images_div">
                                                    		@if(!empty($imagedriverkatta['url_katta_load_unload_image']))
                                                            	<img src = '{{ $imagedriverkatta['url_katta_load_unload_image'] }}'>
                                                            @endif
                                            			</div>
                                            			<div class="view_mt_image" id="load_unload_images_div" style="display: none">
                                                    		<i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_load_unload" ></i>
                                            			</div>
													</div>
												</div>
												<!-- Loading/Uploading tips End-->
												<!-- Essential Tools and first Aid start -->
												<div class="dr-box">
									            	<label for="katta_essential_tool_image" class="control-label">ESSENTIAL TOOLS & FIRST AID IMAGES:</label>
									            	<div class="v-lic">
														<div class="mt_image">
												            <input id="katta_essential_tool_image" type="file" class="form-control ggt-img essential_tool" name="katta_essential_tool_image[]" value="{{  isset($imagedriverkatta['katta_essential_tool_image']) ? $imagedriverkatta['katta_essential_tool_image'] : null }}" autofocus onchange="preview_tools_aid_pic();">
												         	<p class="help-block-message"></p>
												        	 	@if($errors->has('katta_essential_tool_image'))
				                                                    <p class="help-block-message">
				                                                        {{ $errors->first('katta_essential_tool_image') }}
				                                                    </p>
				                                            	@endif
														</div>
				                                        <div class="show_mt_image" id="edit_tool_aid_images_div">
				                                            @if(!empty($imagedriverkatta['katta_essential_tool_image']))                                      
			                                                <img src = '{{ $imagedriverkatta['katta_essential_tool_image'] }}' width="80px" height="80px">
				                                            @endif
				                                        </div>
				                                        <div class="view_mt_image" id="tool_aid_images_div" style="display: none">
				                                            <i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_tool_aid" ></i>
				                                        </div>
													</div>
												</div>
												<!-- Essential Tools and first Aid End -->
											</div> 
											<div class="col-md-6 form-group">
												<!-- up usefull links start -->
												<div class="dr-box" id="append_useful_links">
 													<div class="v-lic">
 														<label for="katta_useful_links_name" class="control-label links">USEFUL LINKS:</label>
														<button type="button" name="add" id="add_useful_links" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></button>
													</div>
													@if(!empty($driver_katta[0]['katta_useful_up_link']))
														<?php $uplink = json_decode($driver_katta[0]['katta_useful_up_link'],true);?>
														@foreach($uplink as $key => $useful_link)
															<div class = "branch_col add_link" branch-key = "{{$key}}"  id="delete_up_row_{{$key}}">
																<div class="row">
																	<div class="col-md-5">
																		<input type="text" class="form-control usefullink_name" name="useful_link[{{$key}}][name]" id="katta_useful_link_text[{{$key}}]" placeholder="Name" value="{{$useful_link['name']}}" required>
																	</div>
																	<div class="col-md-6">
																		<input type="text" class="form-control usefullink_value" name="useful_link[{{$key}}][value]" id="katta_useful_link_name[{{$key}}]" placeholder="Link" value="{{$useful_link['value']}}" required>
																	</div>
																	<div class="col-md-1">
																		<button type="button" name="remove" id="row_{{$key}}" class="btn btn-xs btn-danger btn_remove_uplink"><i class="fa fa-times"></i></button>
																	</div>								
																</div> 
															</div>
														@endforeach
													@endif
												</div>
												<!-- up usefull links End -->
												<!-- External links start -->
												<div class="dr-box" id="append_down_useful_links">
 													<div class="v-lic">
														<label for="katta_down_useful_links_name" class="control-label links">EXTERNAL LINKS (NEWS/SPORTS/EXTERNAL SITE):</label>
														<button type="button" name="add" id="add_down_useful_links" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></button>
													</div>
													@if(!empty($driver_katta[0]['katta_useful_down_link']))
														<?php $downlink = json_decode($driver_katta[0]['katta_useful_down_link'],true);?>
														@foreach($downlink as $key => $useful_down_link)
															<div class = "branch_col_downlink add_link" branch-key = "{{$key}}"  id="delete_down_row_{{$key}}">					
																<div class="row">
																	<div class="col-md-5">
																		<input type="text" class="form-control downlink_name" name="useful_down_link[{{$key}}][name]" id="katta_useful_link_text[{{$key}}]" placeholder="Name" value="{{$useful_down_link['name']}}">
																	</div>
																	<div class="col-md-6">
																		<input type="text" class="form-control downlink_value" name="useful_down_link[{{$key}}][value]" id="katta_useful_link_name[{{$key}}]" placeholder="Link" value="{{$useful_down_link['value']}}">
																	</div>
																	<div class="col-md-1">
																		<button type="button" name="remove" id="row_{{$key}}" class="btn btn-danger btn-xs btn_remove_downlink"><i class="fa fa-times"></i></button>
																	</div>
																</div> 
															</div>
														@endforeach
													@endif
												</div>
												<!-- External links End -->
												<!-- Breakdown Assistance Start -->
												<div class="dr-box" id="append_bd_assistance">
 													<div class="v-lic">
														<label for="katta_bd_assistance" class="control-label links">BREAKDOWN ASSISTANCE:</label>
														<button type="button" name="add" id="add_bd_assistance" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></button>
													</div>
													@if(!empty($driver_katta[0]['katta_bd_assistance']))
														<?php $breakdown = json_decode($driver_katta[0]['katta_bd_assistance'],true);?>
														@foreach($breakdown as $key => $breakdown_katta)
															<div class = "branch_col_breakdown add_link" branch-key = "{{$key}}" id="delete_row_{{$key}}">
																<div class="row">
																	<div class="col-md-5">
																		<input type="text" class="form-control breakdown_name" name="bd_assistance[{{$key}}][name]" id="katta_bd_assistance_model[{{$key}}]" placeholder="Make Model" value="{{$breakdown_katta['name']}}">
																	</div>
																	<div class="col-md-6">
																		<input type="text" class="form-control breakdown_value" name="bd_assistance[{{$key}}][value]" id="katta_bd_assistance_number[{{$key}}]" placeholder="Number" value="{{$breakdown_katta['value']}}">
																	</div>
																	<div class="col-md-1">
																		<button type="button" name="remove" id="row_{{$key}}" class="btn btn-danger btn-xs btn_remove_breakdown"><i class="fa fa-times"></i></button>
																	</div>
																</div>
															</div>											
														@endforeach
													@endif
												</div>
												<!-- Breakdown Assistance End -->
											</div>
										</div>	
										<div class="row">
											<div class="col-md-12">
												<input type="submit" class="btn btn-xs btn-success pull-right" value="Update">
											</div>
                                		</div>  
									</form>
		                    	</div>
		                  	</div>
		                  	<div id="collapseTwo" class="panel-collapse collapse">
		                    <div class="box-body">
		                    	
		                    </div>
		                  </div>
		                </div>
		                <!-- Driver katta end-->
		                <!-- Driver mitr start-->
		                <div class="panel box box-primary">
		                	<div class="box-header with-border">
		                    	<a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
		                    		<h4 class="box-title">GoGoTrux Mitr</h4>
		                      	</a>
		                  	</div>
		                  	<div id="collapseFive" class="panel-collapse collapse">
		                    	<div class="box-body">
		                 			<form method="POST" id="gogotruxMitrForm" name="gogotrux_mitr" action="{{ route ('gogotrux_mitr') }} " enctype="multipart/form-data" data-page-length="10">  
									@csrf

										<div class="row">
											<div class="col-md-6 form-group">
												<input type="hidden" name="mittr_id" value="{{$mittr_id}}">
												<label for="mitr_text" class="control-label">GoGoTrux Mitr Text:</label>
												@if(!empty($drivermittr[0]['ggt_mitr_text']))
													<textarea type="text" class="form-control" name="mitr_text" id="mitr_text">{{ $drivermittr[0]['ggt_mitr_text'] }}</textarea>
												@else
													<textarea type="text" class="form-control" name="mitr_text" id="mitr_text"></textarea>
												@endif
											</div>
											<!-- <div class="col-md-6 form-group">
												<label for="mitr_image" class="control-label">GoGoTrux Mitr Image:</label>
												<input type="file" name="mitr_image" id="mitr_image" class="form-control ggt-img p-0">
											</div> -->
											<!-- image upload -->
								
											<!-- <div class="dr-box"> -->
											<div class="col-md-6 form-group">
									            <label for="mittr_image" class="control-label">GoGoTrux Mitr Image:</label>
								            	<div class="ggt_mitr">
													<div class="mt_image">
											            <input id="mittr_image" type="file" class="form-control ggt-img mittr" name="mittr_image" value="{{  isset($drivermittr[0]['ggt_mitr_image']) ? $drivermittr[0]['ggt_mitr_image'] : null }}" autofocus onchange="preview_mittr_image();">
											         	<p class="help-block-message"></p>
											        	 	@if($errors->has('ggt_mitr_image'))
			                                                    <p class="help-block-message">
			                                                        {{ $errors->first('ggt_mitr_image') }}
			                                                    </p>
			                                            	@endif
													</div>
			                                        <div class="show_mittr_image" id="edit_mittr_images_div">
												        @if(!empty($drivermittr[0]['ggt_mitr_image']))                                      
												        	<img src = '{{ $drivermittr[0]['ggt_mitr_image'] }}' width="80px" height="80px">
												        @endif
											    	</div>
												    <div class="view_mittr_image" id="mittr_images_div" style="display: none">
												        <i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_mittr" ></i>
												    </div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<input type="submit" class="btn btn-xs btn-success pull-right" value="Update">
											</div>
										</div> 
									</form>
		                    	</div>
		                  	</div>
		                </div>
		                <!-- Driver mitr end-->
		                <!-- Driver of the month start-->
		                <div class="panel box box-primary">
		                 	<div class="box-header with-border">
		                    	<a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
		                    		<h4 class="box-title">Driver of the month</h4>
		                      	</a>
		                  	</div>
		                  	<div id="collapseSix" class="panel-collapse collapse">
		                    	<div class="box-body">
		                 			<p>
										<a href="{{ route('driver_of_month.create') }}" class="btn btn-xs btn-success">Add new</a>
									</p>
									<table class="table table-bordered list table-striped {{ count($drivers_of_month) > 0 ? 'datatable' : '' }}" data-page-length="10">
										<thead>
											<tr>
												<th class="sr-col">Sr.No</th>
												<th class="d-col-3">Driver Name</th>
												<th class="d-col-2">Driver Mobile</th>
												<th class="d-col-2">Actions</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php $count=1; ?>
											@if(count($drivers_of_month) > 0)
												@foreach ($drivers_of_month as $drivers)
												<tr>
													<td class="sr-col">{{ $count++ }}</td>
													<td class="d-col-3">{{ $drivers['driver_first_name']." ".$drivers['driver_last_name'] }}</td>
													<td class="d-col-2">
														{{ $drivers['op_mobile_no'] }}
													</td>
													<td class="d-col-2">
														<a href="{{ route('driver_of_month.edit',[$drivers['id']]) }}">
															<button class="btn btn-xs btn-primary" type="button"><i class="fa fa-edit"></i></button>
														</a>
															
														<button class="btn btn-xs btn-danger" type="button" onclick="delete_driverofmonth('{{ $drivers['id'] }}')"><i class="fa fa-trash"></i></button>
													</td>
													<td></td>
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
		                <!-- Driver of the month end-->
		            </div>
				</div>
			</div>
		</div>
	</div>
<div class="modal fade" id="view_mt">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Maintenance Picture</h4>
			</div>
			<div class="modal-body" >
				<div id="view_mt_img">
					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="view_load_unload">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Loading/Unloading Tips Image Picture</h4>
			</div>
			<div class="modal-body" >
				<div id="view_load_unload_img">
					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="view_tool_aid">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Essential Tools & First Aid Image Picture</h4>
			</div>
			<div class="modal-body" >
				<div id="view_tool_aid_img">
					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="view_mittr">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">GoGoTrux Mitr Image</h4>
			</div>
			<div class="modal-body" >
				<div id="view_mittr_img">
					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="upslider_imags">
       <div class="modal-dialog">
               <div class="modal-content">
                       <div class="modal-header">
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span></button>
                               <h4 class="modal-title">Upslider Images</h4>
                       </div>
                       <div class="modal-body" >
                               <div id="upslider_image">
                               </div>
                       </div>
               </div>
       </div>
</div>
<div class="modal fade" id="laughter_imags">
       <div class="modal-dialog">
               <div class="modal-content">
                       <div class="modal-header">
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span></button>
                               <h4 class="modal-title">Laughter corner Images</h4>
                       </div>
                       <div class="modal-body" >
                               <div id="laughter_image">
                               </div>
                       </div>
               </div>
       </div>
</div>

<!-- excel read end -->
@endsection

@section('javascript')
<script language="javascript" type="text/javascript">

	function showSliderimage(images)
	    {
            $("#upslider_image").html(" ");
            var html2 = '';

            
            if(images !== null)
            {
                    var data = JSON.parse(images);
                    $.each(data, function (key, value)
                    {
                            html2 += '<img class="image" src = "'+value.img_preview+'">';

                    });
                    
                    $("#upslider_image").append(html2);
            }
            else
            {
            	var total_file=document.getElementById("katta_up_slider").files.length;
            	
            	if(total_file > 0){
            		for(var i=0;i<total_file;i++)
					{    
						$('#upslider_image').append("<img src='"+URL.createObjectURL(event.target.files[i])+"' class='veh-image'>");
					}
            	}
            }
	    }
	    function preview_image() 
		{
			$('#upslider_image').html('');
			var total_files=document.getElementById("katta_up_slider").files.length;
			var file1 = document.getElementById("katta_up_slider");
			if(total_files > 0){
				// $('#edit_veh_images_div').hide();
				// $('#veh_images_div').show();
				for(var i=0;i<total_files;i++)
				{
					$('#upslider_image').append("<img src='"+URL.createObjectURL(file1.files[i])+"' class='veh-image'>");
				}
			}else{
				// $('#edit_veh_images_div').show();
				// $('#veh_images_div').hide();
				$('#upslider_image').html('');
			}
		}


	    function laughterCornerimage(images)
	    {
	            $("#laughter_image").html(" ");
	            var html2 = '';
	            
	            if(images !== null)
	            {
	                    var data = JSON.parse(images);
	                    $.each(data, function (key, value)
	                    {
	                           html2 += '<img class="image" src = "'+value.img_preview+'">';

	                    });
	                    
	                    $("#laughter_image").append(html2);
	            }
	    }
	    function preview_image_laughter() 
		{
			$('#laughter_image').html('');
			var total_file=document.getElementById("katta_bottom_slider").files.length;
			var file = document.getElementById("katta_bottom_slider");
			if(total_file > 0){
				// $('#edit_veh_images_div').hide();
				// $('#veh_images_div').show();
				for(var i=0;i<total_file;i++)
				{
					$('#laughter_image').append("<img src='"+URL.createObjectURL(file.files[i])+"' class='bottom-image'>");
				}
			}else{
				// $('#edit_veh_images_div').show();
				// $('#veh_images_div').hide();
				$('#laughter_image').html('');
			}
		}

	function preview_mt_pic() 
	{
		$('#view_mt_img').html('');
		var output = document.getElementById("katta_mt_tips_image");
		var total_file = document.getElementById("katta_mt_tips_image").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#edit_mt_images_div').hide()
			$('#mt_images_div').show()
			$('#view_mt_img').append("<img src='"+output.src+"' class='veh-mt'>");
		}else{
			$('#edit_mt_images_div').show()
			$('#mt_images_div').hide()
			$('#view_mt_img').html('');
		}
	}
	function preview_load_unload_pic() 
	{
		$('#view_load_unload_img').html('');
		var output = document.getElementById("url_katta_load_unload_image");
		var total_file = document.getElementById("url_katta_load_unload_image").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#edit_load_unload_images_div').hide()
			$('#load_unload_images_div').show()
			$('#view_load_unload_img').append("<img src='"+output.src+"' class='veh-load'>");
		}else{
			$('#edit_load_unload_images_div').show()
			$('#load_unload_images_div').hide()
			$('#view_load_unload_img').html('');
		}
	}
	function preview_tools_aid_pic() 
	{
		$('#view_tool_aid_img').html('');
		var output = document.getElementById("katta_essential_tool_image");
		var total_file = document.getElementById("katta_essential_tool_image").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#edit_tool_aid_images_div').hide()
			$('#tool_aid_images_div').show()
			$('#view_tool_aid_img').append("<img src='"+output.src+"' class='veh-tool veh-mt'>");
		}else{
			$('#edit_tool_aid_images_div').show()
			$('#tool_aid_images_div').hide()
			$('#view_tool_aid_img').html('');
		}
	}
	function preview_mittr_image() 
	{
		$('#view_mittr_img').html('');
		var output = document.getElementById("mittr_image");
		var total_file = document.getElementById("mittr_image").files.length;
		output.src = URL.createObjectURL(event.target.files[0]);
		if(total_file > 0){
			$('#edit_mittr_images_div').hide()
			$('#mittr_images_div').show()
			$('#view_mittr_img').append("<img src='"+output.src+"' class='mittr-image'>");
		}else{
			$('#edit_mittr_images_div').show()
			$('#mittr_images_div').hide()
			$('#view_mittr_img').html('');
		}
	}
	jQuery.validator.addMethod("breakdown_number", function(value, element) {
		return value.match(/^[0-9]{9,10}$/)
	}, "Please enter valid number");

	jQuery.validator.addMethod("cus_url", function (value, element) {
    if (value.substr(0, 7) != 'http://' && value.substr(0, 8) != 'https://') {
        value = 'http://' + value;         
    }
    
    if (value.substr(value.length - 1, 1) != '/') {
        value = value + '/';
    }
    return this.optional(element) || /^(https|http):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(value);
    }, "Not Valid URL");
	

	$('form#driverKatta').on('submit', function(event) {
        //Add validation rule for dynamically generated name fields
    	var i = $('.branch_col').length;
		if(i%2 == 0){
			// alert(i);
			 // swal("please input even row");
			}
		else{
			 swal("You Entered Odd Rows In Useful Link, Please Enter Even Entry");
			 return false;
			//event.preventDefault();	
		}
		var k = $('.branch_col_downlink').length;
		if(k%2 == 0){
			// alert(k);
			 // swal("please input even row");
			}
		else{
			 swal("You Entered Odd Rows In External link, please enter even entry");
			 return false;
			//event.preventDefault();	
		}
		var j = $('.branch_col_breakdown').length;
		if(j%2 == 0){
			// alert(j);
			 // swal("please input even row");
			}
		else{
			 swal("You Entered Odd Rows In Breakdown, Please Enter Even Entry");
			return false;
			//event.preventDefault();	
		}

	   	
	  // validation of upslider and laughter corner 
	  $('.katta_up_slider').each(function() {
	        $(this).rules("add", 
            {
                required:{
					depends:function(){
					var upslider = '{{ json_encode(isset($imagedriverkatta['upslider_image']['driverkatta_img_data']) ? $imagedriverkatta['upslider_image']['driverkatta_img_data']:null) }}';
					var filesArray = document.getElementById("katta_up_slider").files;
			            if( (upslider != '' && upslider != 'null' ) || filesArray.length != 0){
			                    return false;
			            }
			            if((upslider == '' || upslider == 'null' ) && filesArray.length == 0){
			                    return true;
			            }
					}
				},
                messages: {
                    required: "Please upload upslider images with max 3 files",
                }
            });
	    });
	   $('.katta_bottom_slider').each(function() {
	        $(this).rules("add", 
            {
                required:{
	                depends:function(){
	                var bottomslider = '{{ json_encode(isset($imagedriverkatta['laughter_image']['driverkatta_img_data']) ? $imagedriverkatta['laughter_image']['driverkatta_img_data']:null) }}';
	                var filesArray = document.getElementById("katta_up_slider").files;
	                    if( (bottomslider != '' && bottomslider != 'null' ) || filesArray.length != 0){
	                            return false;
	                    }
	                    if((bottomslider == '' || bottomslider == 'null' ) && filesArray.length == 0){
	                            return true;
	                    }

	                }
                },
                messages: {
                    required: "Please upload laughter corner images with max 3 files",
                }
            });
	    });
	    $('.maintanace_tip').each(function() {
	        $(this).rules("add", 
            {
                required: {
					depends:function(){
					var mt_tips = '{{$imagedriverkatta['url_katta_mt_tips_image'] }}';
					var filesArray = document.getElementById("katta_mt_tips_image").files;
		       			if( (mt_tips!='' && mt_tips!=null ) || filesArray.length!=0){
		          			return false;
		        		}
		        		if((mt_tips=='' || mt_tips==null ) && filesArray.length==0){
		           			console.log('mt img is empty');
		           			return true;
		       	 		}
		        	}	
				},
                messages: {
                    required: "Please upload Maintenance Tips image",
                }
            });
	    });
	    $('.loading_tip').each(function() {
	        $(this).rules("add", 
            {
                required: {
		 			depends:function(){
                    var loading_tips = '{{$imagedriverkatta['url_katta_load_unload_image']}}';
                    var filesArray = document.getElementById("url_katta_load_unload_image").files;
                        if( (loading_tips!='' && loading_tips!=null ) || filesArray.length!=0){
                                return false;
                        }
                        if((loading_tips=='' || loading_tips==null ) && filesArray.length==0){
                                console.log('mt img is empty');
                                return true;
                        }
                    }       
                },
                messages: {
                    required: "Please upload Loading/Unloading Tips image",
                }
            });
	    });
	    $('.essential_tool').each(function() {
	        $(this).rules("add", 
            {
                required: {
                	depends:function(){
                    var essential_tips = '{{$imagedriverkatta['katta_essential_tool_image']}}';
                    var filesArray = document.getElementById("katta_essential_tool_image").files;
                        if( (essential_tips!='' && essential_tips!=null ) || filesArray.length!=0){
                                return false;
                        }
                        if((essential_tips=='' || essential_tips==null ) && filesArray.length==0){
                                console.log('essential tool img is empty');
                                return true;
                        }
                    }       
                },
                messages: {
                    required: "Please upload Essential Tools And First Aid image",
                }
            });
	    });
	    
	    $('.usefullink_name').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Please enter Useful Link Name",
                }
            });
	    });
	   $('.usefullink_value').each(function() {
	        $(this).rules("add", 
            {
                cus_url: true,
                required:true,
                messages: {
                    required: "Please enter Useful Link",
				}
            });
	    });
	    $('.downlink_name').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Please enter External Link Name",
                }
            });
	    });
	   $('.downlink_value').each(function() {
	        $(this).rules("add", 
            {
            	cus_url: true,
                required: true,
                messages: {
                    required: "Please enter External Link",
				}
            });
	    });
	   $('.breakdown_name').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Please enter Breakdown Model Name",
                }
            });
	    });
	   $('.breakdown_value').each(function() {
	        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Please enter Contact Number",
				}
            });
	    });

	   
	});
	$("#driverKatta").validate();
	$("#katta_up_slider").on("change", function() {
	    if ($("#katta_up_slider")[0].files.length > 3) {
	    	$(this).val('');
	        //alert("You can select only 3 images");
            swal("You can select only 3 images");

	    } 
	    else{
	        //$("#imageUploadForm").submit();
	    }
	});
	$("#katta_bottom_slider").on("change", function() {
	    if ($("#katta_bottom_slider")[0].files.length > 3) {
	    	$(this).val('');
	        //alert("You can select only 3 images");
            swal("You can select only 3 images");

	    } 
	    else{
	        //$("#imageUploadForm").submit();
	    }
	});

	/*$("#driverKatta").validate({
		rules: {
	       "katta_up_slider[]": {
             required: true,
             extension: "jpg|jpeg|png",
             maxNumberOfFiles: 3, 
          }
	    }	
	});*/
	
	function delete_image(id,type)
	{
		swal({
			title: 'Are you sure?',
			text: "Delete this image!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete'
		}).then(function() 
		{
			$.ajax({
				url :"{{ route('delete-bannerimage') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"id": id,
					"delete_type": type,
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Deleted!", text: "Deleted successfully.", type: result.status}).then(function()
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

	$(document).ready(function(){
		setTimeout(function() {
            $("#success-message").addClass('hide');
        }, 1000);
        setTimeout(function() {
            $("#fail-message").addClass('hide');
        }, 1000);	
	});
	$('.btn_remove_uplink').click(function(){
        $(document).on('click', '.btn_remove_uplink', function(){
            var button_id = $(this).attr("id");
            $('#delete_up_'+button_id).remove();
    	});
    });    
	
	$('.btn_remove_downlink').click(function(){
	 $(document).on('click', '.btn_remove_downlink', function(){		
        var button_id = $(this).attr("id");
        $('#delete_down_'+button_id).remove();
		});
	});
	$('.btn_remove_breakdown').click(function(){
		$(document).on('click', '.btn_remove_breakdown', function(){		
        var button_id = $(this).attr("id");
        $('#delete_'+button_id).remove();
		});
	});	
	//repeat form element driver katta useful link
    $('#add_useful_links').click(function(){
    	 var i = $('.branch_col').length;
        $('#append_useful_links').append('<div class = "branch_col add_link" branch-key = "'+i+'"><div id="row_up'+i+'" class="row"><div class="col-md-5"><input type="text" class="form-control usefullink_name" name="useful_link['+i+'][name]" id="katta_useful_link_text['+i+']" placeholder="Name"></div><div class="col-md-6"><input type="text" class="form-control usefullink_value" name="useful_link['+i+'][value]" id="katta_useful_link_name['+i+']" placeholder="Link"></div><div class="col-md-1"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-xs btn_remove"><i class="fa fa-times"></i></button></div></div></div>');
        // i++;
        
        $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id");
            $('#row_up'+button_id).parent().remove();
        });

    });
    // var k = 0;
    $('#add_down_useful_links').click(function(){
    	 var k = $('.branch_col_downlink').length;
        $('#append_down_useful_links').append('<div class = "branch_col_downlink add_link" branch-key = "'+k+'"><div id="row_down'+k+'" class="row"><div class="col-md-5"><input type="text" class="form-control downlink_name" name="useful_down_link['+k+'][name]" id="katta_down_useful_link_text['+k+']" placeholder="Name"></div><div class="col-md-6"><input type="text" class="form-control downlink_value" name="useful_down_link['+k+'][value]" id="katta_down_useful_link_name['+k+']" placeholder="Link"></div><div class="col-md-1"><button type="button" name="remove" id="'+k+'" class="btn btn-danger btn-xs btn_remove1"><i class="fa fa-times"></i></button></div></div></div>');
        k++;
        
        $(document).on('click', '.btn_remove1', function(){
            var button_id = $(this).attr("id");
            $('#row_down'+button_id).parent().remove();
        }); 

    });

    //repeat form element driver katta breakdown assistance
	// var j = 0;
    $('#add_bd_assistance').click(function(){
    	var j = $('.branch_col_breakdown').length;
        $('#append_bd_assistance').append('<div class = "branch_col_breakdown add_link" branch-key = "'+j+'"><div id="row_bd'+j+'" class="row"><div class="col-md-5"><input type="text" class="form-control breakdown_name" name="bd_assistance['+j+'][name]" id="katta_bd_assistance_model['+j+']" placeholder="Make Model"></div><div class="col-md-6"><input type="text" class="form-control breakdown_value" name="bd_assistance['+j+'][value]" id="katta_bd_assistance_number['+j+']" placeholder="Number"></div><div class="col-md-1"><button type="button" name="remove" id="'+j+'" class="btn btn-danger btn-xs bd_btn_remove"><i class="fa fa-times"></i></button></div></div></div>');
        j++;
        
        $(document).on('click', '.bd_btn_remove', function(){
            var button_id = $(this).attr("id");
            $('#row_bd'+button_id).parent().remove();
        });

    });

    //delete function for the driver of the month 
    function delete_driverofmonth(id)
	{ 
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
			$.ajax({
				url :"{{ route('deleteDriverOfMonth') }}",
				method:"POST",
				data: {
					"_token": "{{ csrf_token() }}",
					"selectid": id
				},
				success : function(data)
				{
					var result = JSON.parse(data);
					if(result.status == 'success'){
						swal({title: "Deleted!", text: "Your Driver of month has been deleted.", type: result.status}).
						then(function()
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

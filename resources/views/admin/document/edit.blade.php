
@extends('layouts.app')

@section('content-header')
	<h1>Operator Document</h1>
	<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
	<li>Roles</li>
	<li class="active">Edit</li>
	</ol>
@endsection

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
	  			<div class="row"> 
					<div class="col-sm-12 form-group section-title">Edit Document Information</div>
					<div class="section">
						<?php $operator_id = Request::get('_op');?>
							<form method="POST" action="{{ route('update/document') }}" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="doc_id" id="doc_id" value="{{ $doc->doc_id }}">
							<input id="operator_id" type="hidden" name="operator_id" value="{{ $operator_id }}" required>
								<div class="row">						
									<div class="col-xs-4 form-group">
										<label for="doc_type_id" class="control-label">{{ __('Document type*') }}</label>
										<select  id="doc_type_id" type="text" class="form-control" name="doc_type_id"  required autofocus onclick="checkvalidity()">
											@foreach ($doc_list as $doc_type) 
												<option value="{{  $doc_type['doc_type_id']  }}" {{ $doc_type['doc_type_id'] == $doc->doc_type_id ? 'selected' : ''}}>
													{{  $doc_type['doc_label']    }}
												</option>									
											@endforeach
										</select>
								   
										<p class="help-block"></p>
										@if($errors->has('doc_type_id'))
											<p class="help-block">
												{{ $errors->first('doc_type_id') }}
											</p>
										@endif
									</div>						
									<div class="col-xs-4 form-group">
										<label for="doc_expiry" class="control-label" id="doc_label">{{ __('Document validity') }}</label>
										<div class="input-group">
											<input id="doc_expiry" type="text" class="form-control pull-right date-picker" name="doc_expiry" value="{{ $doc->doc_expiry }}" autofocus>
											<div class="input-group-addon calender">
												<i class="fa fa-calendar"></i>
											</div>
										</div>
										<p class="help-block"></p>
										@if($errors->has('doc_expiry'))
											<p class="help-block">
												{{ $errors->first('doc_expiry') }}
											</p>
										@endif
									</div>
									<div class="col-xs-4 form-group">
										<label for="doc_number" class="control-label">{{ __('Document Number*') }}</label>
										<input id="doc_number" type="text" class="form-control" name="doc_number" value="{{ $doc->doc_number }}" required autofocus>
										<p class="help-block"></p>
										@if($errors->has('doc_number'))
											<p class="help-block">
												{{ $errors->first('doc_number') }}
											</p>
										@endif
									</div>
								</div>
								<div class="row">
									<div class="col-xs-4 form-group">
										<label for="doc_images" class="control-label">{{ __('Document Photo') }}</label>
										<input id="doc_images" type="file" class="form-control p-0" name="doc_images" value="" >
										<p class="help-block"></p>
										@if($errors->has('doc_images'))
											<p class="help-block">
												{{ $errors->first('doc_images') }}
											</p>
										@endif	
									</div>
									<div class="col-xs-4">				
										@if(isset($doc->doc_images))
										<div>
											<img src = 'data:image/png;base64,{{ $doc->doc_images }}' width="80px" height="80px">
										</div>
										@endif
									</div>
								</div>			   
								<div class="col-xs-12 form-group">
									<div class="checkbox">
										<label>
											<input type="checkbox" id="is_verified" name="is_verified" 
											{{ $doc->is_verified==1 ? ' value=1 checked' : 'value=0' }}><b> Verified</b>
										</label>
									</div>
								</div>
								<div class="row">				
									<div class="col-xs-12 form-group">
										<div class="btn-b-u">
											<a href="{{ URL::previous()}}" class="btn btn-warning">Back</a>
												<button type="submit" class="btn btn-success">
											{{ __('Update') }}
											</button>
										</div>
									</div>
								</div>
		 					</form>
	 				</div>
	 			</div>
	 		</div>
	 	</div>
	@endif
@endsection

@section('javascript')
<script type="text/javascript">

	function checkvalidity()
	{
		if($("#doc_type_id").val()==2)
		{
			
			$("#doc_expiry").attr('required',true);
			$("#doc_label").html("Document validity *");
		}
		else
		{
			$("#doc_label").html("Document validity ");
			$("#doc_expiry").attr('value'," ")
			$("#doc_expiry").attr('required',false);  
		}
	}
</script>
@endsection






@extends('layouts.app')
<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Driver Home
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Information Borad</li>
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
					<h4>
						Information Board Edit
					</h4>
					<form method="POST" id="infoBoardEditForm" name="info_board_edit" action="{{ route ('informationboard/update/', [$info_board->id]) }} ">  
					@csrf
						<div class="row">
							<div class="col-md-6">
								<label for="info_board_text_edit" class="control-label">Information Board Text:</label>
								<textarea type="text" class="form-control" name="info_board_text_edit" id="info_board_text_edit">{{ $info_board['info_board_text'] }}</textarea>
							</div>
							<div class="col-md-2">
								<input type="submit" class="btn btn-success s-top" value="Update">
							</div>
						</div> 
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
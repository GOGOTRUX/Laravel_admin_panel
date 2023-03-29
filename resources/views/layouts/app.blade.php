<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('partials.head')
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<!-- Main Header -->
			@include('partials.header')

			<!-- Left side column. contains the logo and sidebar -->
			@include('partials.sidebar')
			<!-- Content Wrapper. Contains page content -->

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					@if (Session::has('message'))
						<div class="note note-info">
							<p class="text-green">{{ Session::get('message') }}</p>
						</div>
					@endif
					<!-- @if ($errors->count() > 0)
						<div class="note note-danger">
							<ul class="list-unstyled">
								@foreach($errors->all() as $error)
									<li class="text-red">{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif -->
					@yield('content-header')
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					@yield('content')
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->

			<!-- Main Footer -->
			@include('partials.footer')
			<!-- Add the sidebar's background. This div must be placed
			immediately after the control sidebar -->
			<div class="control-sidebar-bg"></div>
		</div>

		@include('partials.javascripts')
	</body>
</html>

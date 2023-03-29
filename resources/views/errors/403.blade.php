<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>GOGOTRUX-ADMIN</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- Bootstrap 3.3.6 -->
		<!-- import your app css -->
		<!-- Font Awesome -->
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
		<style>

		.layer {
			background-color: #ddddd;
		}
		.register-box{
			width: 80%;
			@if(config('app.debug') == true)
				padding-top: 10vh;
			@else
				padding-top: 40vh;
			@endif
			margin: 0px;
			margin-left:10%;
		}
		.message{
			font-size: 20px;
			color:#000;
		}
		</style>
	</head>
	<body class="hold-transition register-page layer" >

		<div class="register-box">
			<div class="register-logo animated slideInDown">
				<a href="#" style="color: #a94442;"><b style="color: #444444;">GOGOTRUX</b>ADMIN</a>  &rarr;
				<span class="text-danger" style="font-weight: bold;">{{ $exception->getStatusCode() }}</span><br>
				<span class="message">Access Forbidden_</span>
			</div>
			<!-- @if(config('app.debug') == true)
				<pre>{{ $exception }}</pre>
			@endif -->
		</div>
		</body>
	</html>

<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'GoGoTrux') }}</title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('/bower_components/Ionicons/css/ionicons.min.css') }}" />
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}" />
  <!-- mailbox -->
  <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/iCheck/flat/blue.css') }}" />
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/select.dataTables.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/buttons.dataTables.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/limonte-sweetalert2-5.0.7-sweetalert2.min.css') }}" />
  <!-- bootstrap toggle -->
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-toggle/css/bootstrap-toggle.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('/css/themes-base-jquery-ui.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap-timepicker.min.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/jquery-clockpicker.min.css') }}" />

  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css"/> -->
  <!-- <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css"/> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/AdminLTE.min.css') }}" />
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/skins/skin-blue.min.css') }}" />
  <link href="{{ asset('css/style.css')}}" rel="stylesheet" type="text/css">
  <link rel="manifest" href="manifest.json">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<script src="{{ asset('/js/jquery-3.3.1-jquery.min.js') }}"></script>
<script src="{{ asset('/js/ui-1.11.4-jquery-ui.js') }}"></script>







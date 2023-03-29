<!-- jQuery 3 -->
<!-- <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
 -->
 <!-- google map api key -->
<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyArZ5oTxpSuxQhaaNyJmKK94fPLKynjVPk&amp;libraries=places&amp;language=en"></script>--> <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBOXc9fpTDGLvxYBts4YEazCvFU4f4E-wU&amp;libraries=places&amp;language=en"></script>
<script src="https://www.gstatic.com/firebasejs/5.8.5/firebase.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.8.5/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.8.5/firebase-messaging.js"></script> 
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>

<!-- DataTables -->
<script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script> -->
<!-- SlimScroll -->
<script src="{{ asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- Bower jquery validation -->
<script src="{{ asset('bower_components/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!-- main js -->
<script src="{{ url('js/main.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('bower_components/chart.js/Chart.js')}}"></script>
<!-- mail box -->
<script src="{{ asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
<script src="{{ asset('bower_components/admin-lte/plugins/iCheck/icheck.min.js')}}"></script>

<script src="{{ asset('/js/datepicker.js') }}"></script>
<script src="{{ asset('/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('/js/limonte-sweetalert2-5.0.7-sweetalert2.min.js') }}"></script>
<script src="{{ asset('/js/jquery.autocomplete.js') }}"></script>
<script src="{{ asset('/js/firebase.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap-timepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap-clockpicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-clockpicker.min.js') }}"></script>
<!--  sweetalert 	-->
<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/5.0.7/sweetalert2.min.js'></script> -->

<!-- bootstrap-toggle -->
<script src="{{ asset('bower_components/bootstrap-toggle/js/bootstrap-toggle.min.js') }}"></script>

<!-- netcore scripts for sms -->
<script src='//cdnt.netcoresmartech.com/smartechclient.js'></script>
<script>
smartech('create', 'ADGMOT35CHFLVDHBJNIG50K968FHDHQLBCIQS12BS58OKMVNVQR0' );
smartech('register', '6a84004850a5d82e60c90a1af7fbea91');
smartech('identify', '');
</script>
 
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  -->
<!-- <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> -->


<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
<script>
    window._token = '{{ csrf_token() }}';
</script>
@yield('javascript')


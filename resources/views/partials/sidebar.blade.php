<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<div class="collapse navbar-collapse m-0 ad-user sidebar-form" id="navbar-collapse">
			<ul class="nav navbar-nav">
				<!-- User Account Menu -->
				<li class="dropdown user user-menu">
					<!-- Menu Toggle Button -->
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<!-- The user image in the navbar-->
						<!-- <img src="{{ asset('/bower_components/admin-lte/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image"> -->
						<div id="profile_view_image"></div>
						<!-- hidden-xs hides the username on small devices so only the image appears. -->
						<span class="hidden-xs">{{ Auth::user()->name }}</span>
					</a>
					<ul class="dropdown-menu" style="width: 160px; !important">
						<li><a href="{{ route('auth.my_profile') }}"><i class="fa fa-user"></i> <span>Profile</span></a></li>
						<li><a href="{{ route('auth.change_password') }}"><i class="fa fa-key"></i> <span>Change password</span></a></li>
						<!-- <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
						document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> <span>Sign out</span></a>
						</li>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
						</form> -->
					</ul>
				</li>
			</ul>
		</div>

		<!-- Sidebar Menu -->
		<ul class="sidebar-menu" data-widget="tree">       

			<!-- User Management Zone -->
			@if(Gate::check('users_manage') || Gate::check('roles_manage'))
			@can('users_manage')
			<li class="treeview">
				<a href="#"><i class="fa fa-user-plus"></i> <span>User Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					@can('users_view')
					<li class="{{ request()->segment(1) == 'users' ? 'active active-sub' : '' }}"><a href="{{ route('users.index') }}"><i class="fa fa-user"></i>Users</a></li>
					@endcan
					@can('roles_manage')
					<li class="{{ request()->segment(1) == 'roles' ? 'active active-sub' : '' }}"><a href="{{ route('roles.index') }}"><i class="fa fa-briefcase"></i>Roles</a></li>
					@endcan
				</ul>
			</li>
			@endcan
			@endif

			<!-- Order Management Zone -->
			<!-- trip_manage -->
 			@can('trip_manage')
			<li class="treeview">
				<a href="#"><i class="fa fa-truck"></i> <span>Trip Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					@can('trip_view')
						@can('trip_view')
							<li><a href="{{ route('Orders.index') }}"><i class="fa fa-truck"></i>Trips</a></li>

						@endcan
					@endcan
					@can('realtimeassistance_view')
						<li><a href="{{ url('realtime-assistance') }}"><i class="fa fa-truck"></i>Real Time Assistance</a></li>
					@endcan
				</ul>
			</li>
			@endcan

			<!-- Operator Management Zone -->
			@can('operator_manage')
			<li class="treeview {{ (request()->segment(1) == 'operators') ? 'active' : '' }}">
				<a href="#"><i class="fa fa-user-plus"></i> <span>Operator Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class="{{ (request()->segment(1) == 'operators' && request()->segment(2) == '') ? 'active active-sub' : '' }}"><a href="{{ url('/operators') }}"><i class="fa fa-user"></i>All Operators</a></li>
					<li class="{{ (request()->segment(1) == 'operators' && request()->segment(2) == 'individual') ? 'active active-sub' : '' }}"><a href="{{ url('/operators/individual/operators') }}"><i class="fa fa-user"></i>IND</a></li>
					<li class="{{ (request()->segment(1) == 'operators' && request()->segment(2) == 'business') ? 'active active-sub' : '' }}"><a href="{{ url('/operators/business/operators') }}"><i class="fa fa-user"></i>BN</a></li>
				</ul>
			</li>
			@endcan

			<!-- Customer Management Zone -->
			@can('customer_manage')
			<li class="treeview">
				<a href=""><i class="fa fa-users"></i> <span>Customer Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="{{ route('customer.index') }}"><i class="fa fa-user"></i>Customers</a></li>
					<!-- <li><a href="#"><i class="fa fa-briefcase"></i>Reports</a></li> -->
				</ul>
			</li>
			@endcan

			<!-- Subscription Management Zone -->
			@can('subscription_manage')
			<li class="treeview">
				<a href="#"><i class="fa fa-fw fa-hand-pointer-o"></i> <span>Subscription Management </span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<!-- <li><a href="{{ url('/subscriptiontypes') }}"><i class="fa fa-gear"></i>Subscription</a></li> -->
					<li><a href="{{ url('/subscriptions') }}"><i class="fa fa-tasks"></i>Manage Subscription</a></li>
					<li><a href="{{ url('/loyalty') }}"><i class="fa fa-trophy"></i>Loyalty</a></li>
				</ul>
			</li>
			@endcan

			<!-- Vehicle Management Zone -->
			@can('vehicle_facility_manage')
			<li class="treeview">
				<a href="#"><i class="fa fa-truck"></i> <span>Vehicle Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<!-- <li><a href="{{ url('/vehicle') }}"><i class="fa fa-truck"></i>Vehicle</a></li> -->
					<li><a href="{{ url('/vehiclesfacility') }}"><i class="fa fa-gear"></i>Vehicles Facility  </a></li>
				</ul>
			</li>
			@endcan

			<!-- Feedback Management Zone -->
			@can('feedback_manage')
			<li class="treeview">
				<a href="#"><i class="fa fa-minus-square-o"></i> <span>Feedback &amp; Complaint Zone</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="{{ url('/feedback') }}"><i class="fa fa-fw fa-thumbs-o-up"></i>FeedBack</a></li>
				</ul>
			</li>
			@endcan

			<!-- Payment Management Zone -->
			@can('payment_manage')
			<li class="treeview" class="treeview {{ (request()->segment(1) == 'payments') || (request()->segment(1) == 'payment-credit-debit-note') || (request()->segment(1) == 'ledger') || (request()->segment(1) == 'invoice') ? 'active' : '' }}">
				<a href="#"><i class="fa fa-money"></i> <span>Payment Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class="{{ (request()->segment(1) == 'payments' && request()->segment(2) == '') ? 'active active-sub' : '' }}"><a href="{{ url('payments/operator') }}"><i class="fa fa-money"></i>Payments</a></li>
					<li class="{{ (request()->segment(1) == 'payment-credit-debit-note' && request()->segment(2) == '') ? 'active active-sub' : '' }}"><a href="{{ url('/payment-credit-debit-note') }}"><i class="fa fa-credit-card"></i>Credit/Debit Note</a></li>
					<li class="{{ (request()->segment(1) == 'ledger' && request()->segment(2) == '') ? 'active active-sub' : '' }}"><a href="{{ url('/ledger') }}"><i class="fa fa-file-text"></i>Ledger</a></li>
					<li lass="{{ (request()->segment(1) == 'invoice' && request()->segment(2) == '') ? 'active active-sub' : '' }}"><a href="{{ url('/invoice') }}"><i class="fa fa-file-text"></i>Invoice</a></li>
				</ul>
			</li>
			@endcan

			@can('information_manage')
			<li class="treeview">
				<a href="#"><i class="fa fa-info-circle"></i> <span>Information Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
				@can('information_view')
					<li><a href="{{ url('/information') }}"><i class="fa fa-truck"></i>Message Service</a></li>
				@endcan
				@can('home_screen_manage')
					<li><a href="{{ url('/driverhome') }}"><i class="fa fa-home"></i>Home screen update</a></li>
				@endcan
				@can('information_board_screen_manage')
					<li><a href="{{ url('/customerinformationboard') }}"><i class="fa fa-user"></i>Customer Board screen</a></li>
				@endcan
				</ul>
			</li>
			@endcan

			@can('notification_manage')
			<li class="treeview">
				<a href="#"><i class="fa fa-bell-o"></i> <span>Notification Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href=""><i class="fa fa-bell-o"></i>Task</a></li>
				</ul>
			</li>
			@endcan

			@can('price_manage')
			<li class="treeview">
				<a href="#"><i class="fa fa-inr"></i> <span>Price Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="{{ url('/manage_price')}}"><i class="fa fa-inr"></i>Manage Price</a></li>
				</ul>
			</li>
			@endcan

			@can('report_manage')
			<li class="treeview">
				<a href="#"><i class="fa fa-bar-chart"></i> <span>Reports</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="{{ url('/generate_reports')}}"><i class="fa fa-line-chart"></i>Generate Reports</a></li>
				</ul>
			</li>
			@endcan
			@can('setting_manage')
			
			<li class="treeview">
				<a href="#"><i class="fa fa-gear"></i> <span>Settings</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li><a href="{{ url('/setting') }}"><i class="fa fa-book"></i>common settings</a></li>
				</ul>
			</li>
			@endcan
		</ul>
		<!-- /.sidebar-menu -->
	</section>
	<!-- /.sidebar -->
</aside>
<script type="text/javascript">
		var offset = 0;
		var html = '';

		$(window).on('load',function()
		{	getProfileImg();
		});

		function getProfileImg(){
			var token = "{{csrf_token()}}";
			$.ajax({
				headers: {'X-CSRF-TOKEN':token},
				type: "GET",
				url: "/get-admin-profile",
				dataType: 'json',
				async: false,
				success: function(response)
				{
					if(response.status=='success'){
						$('#profile_view_image').html('');
						if(response.profile_info!=null){
							var profile = response.profile_info;
							$('#profile_view_image').append("<img src='data:image/"+profile['img_type']+";base64,"+profile['img_path']+"' class='user-image'>");
						}else{
							$('#profile_view_image').append("<img src='{{ asset('/bower_components/admin-lte/dist/img/user2-160x160.jpg') }}' class='user-image' alt='User Image'>");
						}
					}else{
						// console.log('default user profile');
					}
				}
			});
		}
</script>
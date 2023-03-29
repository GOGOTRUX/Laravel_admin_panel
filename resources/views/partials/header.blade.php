<header class="main-header">
	<!-- Logo -->
	<a href="{{ url('home') }}" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b>GGT</b></span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><img src="{{ asset('images/gogotrux-logo.png')}}" alt="GoGoTRux"></span>
	</a>

	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- Sidebar toggle button-->
		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>
		<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">
			<div class="datetime">
				<span id="showDateTime"></span> 
			</div>
			<ul class="nav navbar-nav right">
				<!-- Notifications: style can be found in dropdown.less -->
				<li class="dropdown notifications-menu" id="notif_div">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="notif_div-dropDown">
						<i class="fa fa-bell-o"></i>
						<span class="label label-warning notif"></span>
						<audio style="display: none;" id="audio" src="{{ asset('sound/accomplished1.mp3') }}" ></audio>
					</a>
					<ul class="dropdown-menu">
						<li class="header" id="notifications_count"></li>
						<li>
							<ul class="menu more_notification accordion">
							</ul>
						</li>
						<li class="footer" id="notification_footer">
							<a name='lnkViews' href="{{ route('home.notificationbox') }}" class="view-btn">View All</a>
							<a name='seeMoreNotifications' href="#" class="view-btn right">See More</a>
						</li>
						
					</ul>
				</li>
				<!-- Notifications: style can be found in dropdown.less -->
				<li class="dropdown logout-menu">
					<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						<i class="fa fa-power-off"></i>
					</a>					
				</li>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
					@csrf
				</form>	
			</ul>
		</div>
	</nav>
	<script type="text/javascript">
		var offset = 0;
		var html = '';
		window.RepLogApp  = "";
		$(window).on('load',function()
		{
			loadData(offset);   
		});

		function loadData()
		{
			// console.log("getNotification");
			var html = '';
			var token = "{{csrf_token()}}"; 
			$.ajax({
				headers: {'X-CSRF-TOKEN':token},
				type: "POST",
				url: "/getNotification",
				data: {offset : offset},
				dataType: 'json',
				async: false,
				success: function(response) 
				{
					showNotification(response);
				}
			});
		}

		function showNotification(response){
			var notifications_count = response['notification_count'];
			RepLogApp = response['last_notification_time'];
			var response = response['data'];
			if(response.length==0)
			{
				$("#notifications_count").html('No new notifications');
				$(".notif").html(0);
				$("#notification_footer").html('');
				$(".footer").html();
				
			}
			else{
				if (notifications_count != 0) 
				{
					$("#notifications_count").html('You have '+notifications_count+' new notifications.');
					$(".notif").html(notifications_count);
				};
				
				// console.log('res length',response.length);
				// {{ url('/notification/view/'.encrypt('+ response[i].notification_id +')) }}
				for(var i = 0; i < response.length; i++)
				{
					html += '<li class="note-msg accordion-item"><a href="/notification/view/'+response[i].notification_id+'">';
						html += '<h5>'+response[i].title
						html += '</h5>';
						html += '<div class="accordion-item-content">';
							html += '<div class="accordion-inner-content">';
								html += '<a href="#" onclick="showInDetail('+"'"+response[i].message_type+"'"+','+response[i].message_view_id+','+response[i].notification_msg_id+')">';
									html += ''+response[i].message
									html += '<div class="note_btn">';
										html += '<button class="btn btn-xs reject">'+'R';
										html += '</button>';
										html += '<button class="btn btn-xs hold">'+'H';
										html += '</button>';
										html += '<button class="btn btn-xs approv">'+'A';
										html += '</button>';
									html += '</div>';
									html += '<div>';
										html +='<i class="fa fa-clock-o" aria-hidden="true"></i>'+response[i].duration
										if(response[i].is_read == 0)
										{	html += '<a onclick="markAsReadUserNotification('+ response[i].notification_id +');" id="mark-read-notification'+ response[i].notification_id+'" class="mark-read-notification fa fa-eye" title="Mark Read"></a>';
										}
										else
										{
											// html += '<a id="mark-read-notification'+ response[i].notification_id+'" class="visibility-hidden mark-read-notification fa fa-eye-slash" title="Mark Read"></a>';
										}
									html += '</div>';
								html += '</a>';
							html += '</div>';
						html += '</div>';
					html += '</a></li>';
				}
				$('.more_notification').append(html);
			}
		}
		
		function showInDetail(message_type,message_view_id,notification_msg_id){
			var token = "{{csrf_token()}}";
			$.ajax({
				headers: {'X-CSRF-TOKEN':token},
				type: "POST",
				url: "/viewNotification",
				data: {
					"message_type": message_type,
					"message_view_id": message_view_id,
					"notification_msg_id" :  notification_msg_id
				},
				dataType: 'json',
				async: false,
				success: function(response)
				{
					if(response['success']){
						window.location = response['redirect_url'];	
					}
				}
			});	
		}

		function markAsReadUserNotification(message_id)
		{
			var token = "{{csrf_token()}}";
			$.ajax({
				headers: {'X-CSRF-TOKEN':token},
				type: "POST",
				url: "/markAsReadUserNotification",
				data: {message_id : message_id},
				dataType: 'json',
				async: false,
				success: function(response)
				{
					if(response['count'].length != 0) 
					{
						var notifications_count = response['count'];
						$("#notifications_count").html('You have '+notifications_count+' new notifications.');
						$(".notif").html(notifications_count);
					};
					var response = response['mark_as_read'];
					if(response == 1)
					{
						$('#mark-read-notification'+message_id).css("color", "red");
						$('#mark-read-notification'+message_id).addClass("test");
						$('.border_bottom').css('background-color','none');
						$('#border_bottom'+message_id).removeClass("border_bottom");
					}
				}
			});
		}

		$("a[name=seeMoreNotifications]").on("click", function () 
		{
			offset = offset + 5;
			loadData(offset);
			$('#notif_div').addClass('open');
			$("#notif_div-dropDown").attr("aria-expanded","true");
		});

		$(document).ready(function(){

			setInterval(relativeTime, 1000);
			setInterval(getLatestNotifications, 5000);

			function relativeTime()
			{
				var m = new Date($.now());
				var dateString = m.toDateString();
				var timeString = m.toLocaleTimeString();
				var dateTime = dateString+' '+timeString;
				$('#showDateTime').html(dateTime);
			}

			function getLatestNotifications(){
				var html = '';
				var token = "{{csrf_token()}}"; 
				$.ajax({
					headers: {'X-CSRF-TOKEN':token},
					type: "POST",
					url: "/getLatestNotification",
					data: {offset : RepLogApp},
					dataType: 'json',
					async: false,
					success: function(response) 
					{
						if(response.status){
							var sound = document.getElementById("audio");
							sound.play();
							loadData(0);
						}
					}
				});
			}
		});
	</script>
</header>

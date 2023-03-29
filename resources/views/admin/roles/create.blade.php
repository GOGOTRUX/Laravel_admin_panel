@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
	<h1>
		Roles
		<!--<small>(Vendors)</small>-->
	</h1>
	<ol class="breadcrumb">
	<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
	<li class="active">Roles</li>
	</ol>
@endsection

<!-- Main Content -->
@section('content')
	<form method="POST" action="{{ route('roles.store') }}">
	@csrf
		<div class="panel-body p-0">
			<div class="view-op">
			<div class="row">
				<div class="col-sm-12 form-group section-title">Create</div>
				<div class="section">
					<div class="row">
						<div class="col-xs-6 form-group">
							<label for="name" class="control-label">{{ __('Name*') }}</label>
							<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
							<p class="help-block"></p>
							@if($errors->has('name'))
								<p class="help-block">
									{{ $errors->first('name') }}
								</p>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 form-group">
							<label for="permission" class="control-label">{{ __('Permissions*') }}</label><br>
							@if(!empty($permissions))
								<div id="manage_permissions"></div>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 form-group">
							<div class="btn-b-u">
								<a href="{{ URL::previous()}}" class="btn btn-warning">Back</a>
								<button type="submit" class="btn btn-success">
									{{ __('Save') }}
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<script type="text/javascript">

	var text = '{{$permissions}}';
	var permissions = JSON.parse(text.replace(/&quot;/g, '\"'));
	if(jQuery.isEmptyObject(permissions)){
		$('#manage_permissions').html("permission not available");
	}
	else{
		var html, manage_html = users_html = customer_html = subscription_html = veh_fac_html = feedback_html = op_html = order_html = payment_html = role_html = info_html = verify_html = home_screen_html = price_html = trip_html = dashboard_html ='';
		$.each( permissions, function( key, value ) {
			if(value.indexOf('manage') != -1){
				var count = (value.match(/_/g) || []).length;
				// if(count == 1){
					manage_html += '<input type="checkbox" name="permission[]" onclick="handleClick(this);" value="'+value+'"> <b>'+value+'</b><div id="'+value+'" class="hide p-l-15"></div><br>'
				// }
				$('#manage_permissions').html(manage_html);
			}
		});

		$.each( permissions, function( key, value ) {
			if(value.indexOf('users') != -1 && !(value.indexOf('manage') != -1)){
				users_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
					$('#users_manage').html(users_html);
			}
			else if(value.indexOf('customer') != -1 && !(value.indexOf('manage') != -1)){
				customer_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
				$('#customer_manage').html(customer_html);
			}
			else if(value.indexOf('subscription') != -1 && !(value.indexOf('manage') != -1)){
				subscription_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
				$('#subscription_manage').html(subscription_html);
			}
			else if(value.indexOf('vehicle_facility') != -1 && !(value.indexOf('manage') != -1)){
				veh_fac_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
				$('#vehicle_facility_manage').html(veh_fac_html);
			}
			else if(value.indexOf('feedback') != -1 && !(value.indexOf('manage') != -1)){
				feedback_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
				$('#feedback_manage').html(feedback_html);
			}
			else if(value.indexOf('operator') != -1 && !(value.indexOf('manage') != -1)){
				op_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
				$('#operator_manage').html(op_html);
			}
			else if(value.indexOf('order') != -1 && !(value.indexOf('manage') != -1)){
				order_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
				$('#order_manage').html(order_html);
			}
			else if(value.indexOf('payment') != -1 && !(value.indexOf('manage') != -1)){
				payment_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
				$('#payment_manage').html(payment_html);
			}
			else if(value.indexOf('role') != -1 && !(value.indexOf('manage') != -1)){
				role_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
				$('#roles_manage').html(role_html);
			}
			else if((value.indexOf('information') != -1 || value.indexOf('notification') != -1 || value.indexOf('mail') != -1) && !(value.indexOf('manage') != -1)){
				info_html += '<input type="checkbox" name="permission[]" value="'+value+'"> '+value+'<br>'
				$('#information_manage').html(info_html);
			}
			else if((value.indexOf('home_screen') != -1) && !(value.indexOf('manage') != -1)){
                home_screen_html += '<input type="checkbox" name="permission[]" id="_'+value+'" value="'+value+'"> '+value+'<br>'
                $('#home_screen_manage').html(home_screen_html);
            }
            else if((value.indexOf('price_manage') != -1 || value.indexOf('create_') != -1 ) && !(value.indexOf('manage') != -1)){
                price_html += '<input type="checkbox" name="permission[]" id="_'+value+'" value="'+value+'"> '+value+'<br>'
                $('#price_manage').html(price_html);
            }
            else if((value.indexOf('trip') != -1 || value.indexOf('realtimeassistance_view') != -1 ) && !(value.indexOf('manage') != -1)){
                trip_html += '<input type="checkbox" name="permission[]" id="_'+value+'" value="'+value+'"> '+value+'<br>'
                $('#trip_manage').html(trip_html);
            }
            else if((value.indexOf('dashboard') != -1) && !(value.indexOf('manage') != -1)){
                dashboard_html += '<input type="checkbox" name="permission[]" id="_'+value+'" value="'+value+'"> '+value+'<br>'
                $('#dashboard_manage').html(dashboard_html);
            }
			else if((value.indexOf('verify') != -1)){
                verify_html += '<input type="checkbox" name="permission[]" id="_'+value+'" value="'+value+'"> '+value+'<br>'
                $('#verification_manage').html(verify_html);
            }
		});
	}

	function handleClick(cb) {
		if(cb.checked){
			$('#'+cb.value).removeClass('hide');
		}
		else{
			$('#'+cb.value).addClass('hide');	
		}
		// console.log("Clicked, new value = " + cb.value);
	}
	

</script>
@endsection
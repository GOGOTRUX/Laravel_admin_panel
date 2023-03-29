@extends('layouts.app')

<!-- Content Header (Page header) -->
@section('content-header')
    <h1>My Profile<!--<small>(Vendors)</small>--></h1>
    <ol class="breadcrumb">
    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">My Profile</li>
    </ol>
@endsection

<!-- Main Content -->
@section('content')
    @if(session('success'))
        <!-- If password successfully show message -->
        <div class="row">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif
    <form method="POST" action="{{ route('auth.my_profile') }}" id="editProfileFrom" enctype="multipart/form-data">
    @csrf
        <div class="panel-body p-0">
            <div class="view-op">
                <div class="row">
                    <div class="col-sm-12 form-group section-title"><b>Edit Profile</b></div>
                    <div class="section">
                        <div class="row">
                            <div class="col-xs-6 form-group">
                                <label for="name" class="control-label">{{ __('Name*') }}</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>
                                <p class="help-block"></p>
                                @if($errors->has('name'))
                                    <p class="help-block">
                                        {{ $errors->first('name') }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-xs-6 form-group">
                                <label for="email" class="control-label">{{ __('Email*') }}</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required autofocus>
                                <p class="help-block"></p>
                                @if($errors->has('email'))
                                    <p class="help-block">
                                        {{ $errors->first('email') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="row">   
                            <div class="col-xs-6 form-group">
                                <label for="profileimage" class="control-label">{{ __('Profile Photo*') }}</label>
                                <input id="profileimage" type="file" class="form-control p-0" name="profileimage" autofocus onchange="preview_profile_pic();">
                                <p class="help-block"></p>
                                @if($errors->has('profileimage'))
                                    <p class="help-block">
                                        {{ $errors->first('profileimage') }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group" id="edit_profile_images_div">
                                @if($user->profile_image)
                                    <div>
                                        <img src = 'data:image/png;base64,{{ $user->profile_image }}' width="80px" height="80px">
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group" id="profile_images_div" style="display: none">
                                <label for="view_veh_images" class="control-label">{{ __('View Profile Images') }}</label><br>
                                <i class="fa fa-fw fa-image" data-toggle="modal" data-target="#view_profile" ></i>
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
        </div>
    </form>
    <!-- view profile images modal -->
    <div class="modal fade" id="view_profile">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Profile Picture</h4>
                </div>
                <div class="modal-body" >
                    <div id="view_profile_img">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- view profile images modal end -->
@endsection

<!-- JS scripts for this page only -->
@section('javascript')
<script type="text/javascript">
// form validation
    $(document).ready(function () {
        $('#editProfileFrom').on('submit', function(e){
            console.log('on form submit');
        });

        $("#editProfileFrom").validate({
            rules: {
                name: {
                    required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                },
                email: {
                    required: true,
                    email: true,
                },
                profileimage: {
                    required: {
                        depends: function (){ 
                            var edit_profile_images = '{{ $user->profile_image }}';
                            var filesArray = document.getElementById("profileimage").files;
                            if( (edit_profile_images!='' && edit_profile_images!=null )&& filesArray.length==0){
                                    return false;
                            }
                            if((edit_profile_images=='' || edit_profile_images==null ) && filesArray.length==0){
                                    return true;
                            }
                        }
                    },
                },
            },  
            messages: {
                name : {
                    required:"Please enter name",
                },
                email:{
                    required:"Please enter email",
                    email:"Please enter valid email address",
                }, 
                profileimage:{
                    required:"Please upload profile picture",
                }, 
            },
            invalidHandler: function(event, validator) {
                // console.log(event);
                // console.log(validator);
            },
        });
    }); 
//form validation-end

    function preview_profile_pic() 
    {
        $('#view_profile_img').html('');
        var output = document.getElementById("profileimage");
        var total_file = document.getElementById("profileimage").files.length;
        output.src = URL.createObjectURL(event.target.files[0]);
        if(total_file > 0){
            $('#edit_profile_images_div').hide()
            $('#profile_images_div').show()
            $('#view_profile_img').append("<img src='"+output.src+"' class='veh-image'>");
        }else{
            $('#edit_profile_images_div').show()
            $('#profile_images_div').hide()
            $('#view_profile_img').html('');
        }
    }
</script>
@endsection
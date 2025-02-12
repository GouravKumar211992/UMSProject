<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@section('title') DSMNRU @show</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/css/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link href="/assets/admin/css/font-awesome.css" rel="stylesheet">
    <link href="/assets/admin/css/style.css" rel="stylesheet">
    <link href="/assets/admin/css/responsive.css" rel="stylesheet">
    <link href="/assets/admin/css/bootstrap-select.css" rel="stylesheet">
    {{-- {!! RecaptchaV3::initJs() !!} --}}

    @yield('styles')
	<style>
.eyebtn {
            cursor: pointer;
			position: absolute;
			right: 13px;
			bottom: 8px;
        }
		label {

     margin-bottom:0;
}

</style>
</head>


<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">


        <aside class="main-sidebar sidebar-dark-primary">
            <div class="navbg">
                <img src="/assets/admin/img/navtopbg.svg" />
            </div>

            <div class="sidebar">

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item"><a href="{{url('faculty')}}" class="nav-link"><i class="iconly-boldCategory"></i>
                                <p>Dashboard</p>
                        </a></li>

                        <!--li class="nav-item"><a href="{{url('faculty')}}" class="nav-link"><i class="iconly-boldCategory"></i>
                                <p>Student Lecture Attendance</p>
                        </a></li-->
                        <!--<li class="nav-item"><a href="{{url('faculty')}}" class="nav-link"><i class="iconly-boldCategory"></i>
                                <p>Leave Applications</p>
                        </a></li-->
                        <li class="nav-item has-treeview"><a href="#" class="nav-link"><i class="iconly-boldPaper"></i>
                                <p>Internal Marks Filling<i class="fa fa-angle-left right"></i></p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{url('faculty/internal-marks-list')}}">
                                        <p>Internal Marks List</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item"><a href="{{url('faculty')}}" class="nav-link "><i class="iconly-boldCategory"></i>
                            <p>Practical Marks Filling<i class="fa fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{url('faculty/practical-marks-list')}}">
                                        <p>Practical Marks List</p>
                                    </a>
                                </li>

                            </ul>
                       </li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="iconly-boldPaper"></i>
                            <p>External Marks Filling<i class="fa fa-angle-left right"></i></p>
                        </a>
						<ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{url('faculty/external-marks-list')}}">
                                        <p>External Marks List</p>
                                    </a>
                                </li>

                            </ul>
						</li>
                        <!--li class="nav-item"><a href="{{url('faculty')}}" class="nav-link"><i class="iconly-boldCategory"></i>
                                <p>Lecture Plan</p>
                        </a></li-->
                        <li class="nav-item"><a href="{{url('faculty')}}" class="nav-link"><i class="iconly-boldCategory"></i>
                                <p>Lecture Schedule<i class="fa fa-angle-left right"></i></p>
                        </a>

                        <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{url('faculty/lecture-schedule')}}">
                                        <p>Lecture Schedule View</p>
                                    </a>
                                </li>



                        </ul>
                         <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{url('faculty/timetable/show')}}">
                                        <p>Time Table Schedule</p>
                                    </a>
                                </li>



                        </ul>
                        </li>
                        <li class="nav-item"><a href="{{url('faculty')}}" class="nav-link ">
                            <i class="iconly-boldCategory"></i>
                            <p>Attendance Filling<i class="fa fa-angle-left right"></i></p></a>
                           <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{url('faculty/attendence/show')}}">
                                        <p>Attendance</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('notification')}}" class="nav-link"><i class="fa fa-bell" aria-hidden="true"></i>
                                <p>Notification</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('faculty/holiday-calender')}}" class="nav-link"><i class="fa fa-calendar" aria-hidden="true"></i>
                                <p>Holiday calender</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="{{url('faculty')}}" class="nav-link"><i class="fa fa-calendar" aria-hidden="true"></i>
                                <p>Circular</p>
                            </a>
                        </li> -->

                        <!--li class="nav-item"><a href="{{url('faculty')}}" class="nav-link"><i class="iconly-boldCategory"></i>
                                <p>Test Marks Filling System</p>
                        </a></li-->

                    </ul>
                </nav>

            </div>

        </aside>
        <div class="content-wrapper">
            <div class="content-header pb-0">
                <div class="container-fluid mt-3">

                    <div class="row mb-2">
                        <div class="col-md-4 col-1">
                            <a class="d-block d-md-none" data-widget="pushmenu" href="#" role="button"><img src="/assets/admin/img/menu-left-alt.svg" /></a>
                            <a class="d-block d-sm-none d-md-block" href="#" role="button"><img src="/assets/admin/img/logo.png" /></a>
                        </div>
                        <div class="col-md-7 col-11 user-profile offset-md-1">
                            {{--  <div class="float-right notificationbar mt-4">
                                <a href="#" class="position-relative"><img src="/assets/admin/img/notification.svg" class="float-none" /><span class="top-notification">05</span></a>
                                <!-- <a href="#"><img src="/assets/admin/img/help.svg" class="m-0 float-none" /></a> -->
                                <!-- <a href="#" class="d-none d-sm-none d-md-inline-block"><img src="/assets/admin/img/updesco.png"
                                    class="m-0 float-none updesco" /></a> -->
                            </div>  --}}
                            {{-- <div class="float-right dropdown userData">
                                <a data-toggle="dropdown" href="#" aria-expanded="true">
                                    <p>{{ucfirst(Auth::guard('faculty')->user()->designation)}} Faculty<span>{{Auth::guard('faculty')->user()->name}}</span></p><img src="/assets/admin/img/user.jpeg" class="img-circle mr-1" width="36" /> <img src="/assets/admin/img/dot.svg" class="float-none" />
                                </a>
                                <div class="dropdown-menu dropdown-menu-md tableaction useraction-dropdown dropdown-menu-right" style="left: inherit; right: 0px;">
                                    <ul>
                                        <li><a href="{{route('faculty-profile')}}">My Profile</a></li>
                                        <li><!--a href="{{ route('faculty-password-change') }}">Change Password</a--><a href="#"  class="f-14" data-dismiss="modal" data-toggle="modal" data-target="#forgetpassword">Change Password</a></li>
                                        <li><a href="{{ route('faculty-logout') }}">Logout</a></li>
                                    </ul>
                                </div>
                            </div>

                        </div> --}}

                    </div>


                    <div class="modal fade rightModal" id="forgetpassword" tabindex="-1" role="dialog" aria-labelledby="loginpopupTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-slideout" role="document">

                            <div class="modal-content bg-light">
							{{-- <form method="POST" class="needs-validation" action="{{ route('faculty-password-change') }}" id="myform"> --}}
                                        @csrf

                                    <div class="modal-header pt-5 pl-5 pr-5 border-0">
                                        <div class="pt-3 col-md-12">
                                            <button type="button" class="close search-btn addaddressbtn" data-dismiss="modal" aria-label="Close">
                                                <img src="/assets/admin/img/close.svg"/>
                                            </button>
                                            <div class="">
                                                <h2>Password</h2>
                                                <h5>Update</h5>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-body pt-3 pr-5 pl-5">

                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-label-group">
												<label for="current_password">Current password</label>
												@if(Auth::guard('faculty')->user())<input type="hidden" name="email" class="validate" value="{{Auth::guard('faculty')->user()->email}}">
																		@endif
                                                    <input type="password" id="current_password" name="current_password" class="form-control" placeholder=" Enter Current Password" value="{{old('current_password')}}">
													<a onclick="show_password($(this));" class="eyebtn"><img src="{{asset('assets/admin/img/eye-inactive.svg')}}" /></a>
													<div class="text-danger">{{$errors->first('current_password')}}</div>

                                                </div>
                                            </div>
											<div class="col-md-12">
                                                <div class="form-label-group">
												<label for="password">New password</label>
                                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter New Password" value="{{old('password')}}"><a onclick="show_password($(this));" class="eyebtn"><img src="{{asset('assets/admin/img/eye-inactive.svg')}}" /></a>
													<div class="text-danger">{{$errors->first('password')}}</div>

                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-label-group">
												<label for="confirm_password">Re-type new password</label>
                                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder=" Confirm New Password" value="{{old('confirm_password')}}"><a onclick="show_password($(this));" class="eyebtn"><img src="{{asset('assets/admin/img/eye-inactive.svg')}}" /></a>
													<div class="text-danger ">{{$errors->first('confirm_password')}}</div>
													<span class="text-danger">Enter Min 8 Characters for Password</span>

                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center mt-4">
                                                <button type="submit" class="btn btn-secondary btn-radius">Update Password</button>
                                            </div>

                                        </div>

                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
            @yield('content')
            <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                {{-- {!! RecaptchaV3::field('facultyportal') !!} --}}
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
        </div>

        @yield('form-model')

        <!-- </div> -->


        <script src="/assets/admin/js/jquery.min.js"></script>
        <script src="/assets/admin/js/jquery-ui.min.js"></script>
        <script src="/assets/admin/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/admin/css/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <script src="/assets/admin/js/adminlte.js"></script>
        <script src="/assets/admin/js/bootstrap-select.js"></script>
        <script src="/assets/admin/js/custom-app.js"></script>
        <!-- Include jQuery from a CDN or locally -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        @yield('scripts')
<script>
function show_password($this) {
            if ('password' == $this.parent().find('.password').attr('type')) {
                $this.parent().find('.password').prop('type', 'text');
            } else {
                $this.parent().find('.password').prop('type', 'password');
            }
        }
		$("input").keypress(function() {
			$(this).parent().find('.text-danger').text('');
		});
		$("input").on('change', function() {
			$(this).parent().find('.text-danger').text('');
		});
</script>

</body>

</html>


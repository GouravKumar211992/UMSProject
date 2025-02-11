<style>
#application-alert-modal .modal-content,
#login-alert-modal .modal-content,
#forgot-alert-modal .modal-content,
#register-alert-modal .modal-content {
   background-color: #5cb85c;
}
#application-alert-modal-false .modal-content{
	background-color:#ff0000;
}
.eyebtn {
            cursor: pointer;
			position: absolute;
			right: 13px;
			bottom: 10px;
        }

</style>

<!-- MOBILE MENU -->
<section>
	<div class="ed-mob-menu">
		<div class="ed-mob-menu-con">
			<div class="ed-mm-left">
				<div class="wed-logo">
					<a href="{{url('')}}"><img src="/assets/frontend/images/logo.png" alt="" /> </a>
				</div>
			</div>
			<div class="ed-mm-right">
				<div class="ed-mm-menu">
					<a href="#!" class="ed-micon"><i class="fa fa-bars"></i></a>
					<!--<div class="ed-mm-inn mob-menudeta">
						<a href="#!" class="ed-mi-close"><i class="fa fa-times"></i></a>
						<h4 class="pb-2 border-bottom mb-2"><a href="#!" data-toggle="modal" id="login_btn" data-target="#login">LogIn</a></h4>
						<h4 class="pb-2 border-bottom mb-2"><a href="#!" data-toggle="modal" data-target="#register">Register</a></h4>
						<h4 class="pb-2 border-bottom mb-2"><a href="{{url('/')}}">Home</a></h4>
						<h4><a href="#">University</a></h4>
						<ul>
							<li><a href="{{url('About-Us')}}">About Us</a></li>
							<li><a href="{{url('About-Lucknow')}}">About Lucknow</a></li>
							<li><a href="{{url('Vision-Mission')}}">Vision & Mission</a></li>
							<li><a href="{{url('Statement-Object')}}">Statement of Object</a></li>
							<li><a href="{{url('University-Act')}}">University Act</a></li>
							<li><a href="{{url('University-Statute')}}" >University Statute</a></li>
							<li><a href="{{url('Distinctive-Features')}}">Distinctive Features</a></li>
						</ul> 
						<h4 class="pb-2 border-bottom mb-2"><a href="{{url('Administration')}}">Administration</a></h4>
						<h4 class="pb-2 border-bottom mb-2"><a href="{{url('Academics')}}">Academics</a></h4>
						<h4 class="pb-2 border-bottom mb-2"><a href="{{url('Students-Zone')}}">Students Zone</a></h4>
						@if(Auth::user())
						<h4 class="pb-2 border-bottom mb-2"><a href="{{url('application-form')}}">Admissions</a></h4>
						@else
						<h4 class="pb-2 border-bottom mb-2"><a ref="#!" data-toggle="modal" data-target="#login">Admissions</a></h4>
						@endif
						<h4 class="pb-2 border-bottom mb-2"><a href="{{url('all-categories')}}">All Courses</a></h4>
						{{-- <h4 class="pb-2 border-bottom mb-2"><a href="{{route('student-login')}}">Student Login</a></h4> --}}
					</div>-->
				</div>
			</div>
		</div>
	</div>
</section>


<!--HEADER SECTION-->
<section>
	<!-- TOP BAR -->
	<div class="ed-top">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="ed-com-t1-left top-leftlanaction">
						<ul>
							<li><a href="#">A-</a></li> 
							<li><a href="#">A</a></li> 
							<li><a href="#">A+</a></li> 
							<li><a href="#" class="darktheme">T</a></li> 
							<li><a href="#" class="maintheme">T</a></li> 
						</ul>
					</div>
					<div class="ed-com-t1-right">

						@if(Auth::user())
							<ul>
								<li><a href="/dashboard"><i class="fa fa-user-circle-o"></i> Hi, {{Auth::user()->name}}</a></li>
								<li><a style="cursor:pointer;" onclick="logout()"><i class="fa fa-lock"></i> Logout</a></li>
							</ul>
						@else
							<ul>
								<li><a href="#!" data-toggle="modal" id="login_btn" data-target="#login"><i class="fa fa-sign-in"></i> LogIn</a></li>
								<li><a href="#!" data-toggle="modal" data-target="#register"><i class="fa fa-sign-out"></i> Register</a></li>
							</ul>
						@endif
					</div>
					<div class="ed-com-t1-social">
						<ul>
							<li><a href="{{url('twitter')}}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
							<li><a href="{{url('linkedin')}}"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
							<li><a href="{{url('facebook')}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a href="{{url('wikipedia')}}"><i class="fa fa-wikipedia-w" aria-hidden="true"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- LOGO AND MENU SECTION -->
	<!--div class="top-logo" data-spy="affix" data-offset-top="250">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="wed-logo">
						<a href="{{url('')}}"><img src="/assets/frontend/images/logo.png" alt="" />
						</a>
					</div>
					<div class="main-menu">
						<ul>
							<li><a href="{{url('/')}}">Home</a></li>
							<li class="about-menu">
								<a href="#" class="mm-arr">University</a>
								<div class="mm-pos">
									<div class="about-mm m-menu">
										<div class="m-menu-inn">
											<div class="mm1-com mm1-s1">
												<div class="ed-course-in">
													<a class="course-overlay menu-about" href="admission.html">
														<img src="/assets/frontend/images/h-about.jpg" alt="">
														<span>University</span>
													</a>
												</div>
											</div>
											<div class="mm1-com mm1-s3">
												<ul>
													<li><a href="{{url('About-Us')}}">About Us</a></li>
													<li><a href="{{url('About-Lucknow')}}">About Lucknow</a></li>
													<li><a href="{{url('Vision-Mission')}}">Vision & Mission</a></li>
													<li><a href="{{url('Statement-Object')}}">Statement of Object</a></li>
													<li><a href="{{url('University-Act')}}">University Act</a></li>
													<li><a href="{{url('University-Statute')}}" >University Statute</a></li>
													<li><a href="{{url('Distinctive-Features')}}">Distinctive Features</a></li>
												</ul>
											</div>

										</div>
									</div>
								</div>
							</li>
							<li><a href="{{url('Administration')}}">Administration</a></li>
							<li><a href="{{url('Academics')}}">Academics</a></li>
							<li><a href="{{url('Students-Zone')}}">Students Zone</a></li>

							@if(Auth::user())
							<li><a href="{{url('application-form')}}">Admissions</a></li>
							@else
							<li><a ref="#!" data-toggle="modal" data-target="#login">Admissions</a></li>
							@endif
							<li><a href="{{url('all-categories')}}">All Courses</a></li>
							{{-- <li><a href="{{route('student-login')}}">Student Login</a></li> --}}
						</ul>
					</div>
				</div>
				<div class="all-drop-down-menu">

				</div>

			</div>
		</div>
	</div-->
</section>




    <!--SECTION LOGIN, REGISTER AND FORGOT PASSWORD-->
	@if(Auth::check()==false)
    <section>
        <!-- LOGIN SECTION -->
		@include('frontend.layouts.partials.login-page-modal')
		@include('frontend.layouts.partials.register-page-modal')
		
        <!-- FORGOT SECTION -->
        <div id="modal3" class="modal fade" role="dialog">
            <div class="log-in-pop"> 
                <div class="log-in-pop-right">
                    <a href="#" class="pop-close" data-dismiss="modal"><img src="images/cancel.png" alt="" />
                    </a>
                    <h4>Forgot password</h4>
                    <!--p>Don't have an account? Create your account. It's take less then a minutes</p-->
					<div class="invalid-feedback text-danger forgot_forgot"></div>
					<form class="row" method="POST" action="{{ route('forgot-password') }}" id="myform_forgot">
					@csrf
                        <div>
                            <div class="input-field s12">
                                <input type="text" data-ng-model="name3" class="validate" name="email" autocomplete="off">
                                <label>User name or email id</label>
								<div class="invalid-feedback text-danger email_forgot"></div>
                            </div>
                        </div>
                        <div>
                            <div class="input-field s4">
                                <input type="button" onclick="enquery_gorgot_password();" value="Submit" class="waves-effect waves-light log-in-btn"> </div>
                        </div>
                        <div>
                            <div class="input-field s12"> <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#login">Already have an account ? Login</a> | <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#register">Create a new account</a> </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endif
	<!-- Success Alert Modal -->
	<div id="register-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm modal-success">
			<div class="modal-content modal-filled bg-success">
				<div class="modal-body p-4">
					<div class="text-center">
						<i class="dripicons-checkmark h1 text-white"></i>
						<h4 class="mt-2 text-white" style="color:white;">Well Done!</h4>
						<p class="mt-3 text-white" style="color:white;">Registered Successfully.</p>
						<p class="mt-3 text-white" style="color:white;">Please click the activation link sent on your email and login.</p>
						<a href="{{url()->current()}}" class="btn btn-info my-2">Continue</a>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<!-- Success Alert Modal -->
	<div id="login-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content modal-filled bg-success">
				<div class="modal-body p-4">
					<div class="text-center">
						<i class="dripicons-checkmark h1 text-white"></i>
						<h4 class="mt-2 text-white" style="color:white;">Well Done!</h4>
						<p class="mt-3 text-white" style="color:white;">Logged in Successfully.</p>
						@if(Request::segment(1)==null || Request::segment(1)=='login' || Request::segment(1)=='admission-portal')
						<a href="{{url('dashboard')}}" class="btn btn-info my-2">Continue</a>
						@else
						<a href="{{url()->current()}}" class="btn btn-info my-2">Continue</a>
						@endif
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<!-- Success Alert Modal -->
	<div id="forgot-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content modal-filled bg-success">
				<div class="modal-body p-4">
					<div class="text-center">
						<i class="dripicons-checkmark h1 text-white"></i>
						<h4 class="mt-2 text-white" style="color:white;">Well Done!</h4>
						<p class="mt-3 text-white" style="color:white;">Email sent on your email.</p>
						<a href="{{url()->current()}}" class="btn btn-info my-2">Continue</a>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="loading-image" style="display:none;position:fixed;z-index:999999;background:rgb(255 255 255 / 80%);height:100%;width:100%;top:0px;text-align: center;">
		<img src="{{asset('images/Ajux_loader.gif')}}" style="width:300px;"/>
	</div>

<!--END HEADER SECTION-->


<script>
function logout(){
	$("#loading-image").show();
	setTimeout( function(){ 
		window.location.href = "{{url('logout')}}";
	}  , 3000 );
}
function show_password($this) {
            if ('password' == $this.parent().find('.password').attr('type')) {
                $this.parent().find('.password').prop('type', 'text');
            } else {
                $this.parent().find('.password').prop('type', 'password');
            }
        }
</script>

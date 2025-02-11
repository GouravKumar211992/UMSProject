        <div id="login" class="modal fade" role="dialog">
            <div class="log-in-pop"> 
                <div class="log-in-pop-right">
                    <a href="#" class="pop-close" data-dismiss="modal"><img src="{{asset('assets/frontend/images/cancel.png')}}" alt="" /></a>
					<img src="{{asset('assets/frontend/images/logo.png')}}" alt="" class="mb-5" />
                    <h4>Login</h4>
                    <!--p>Don't have an account? Create your account. It's take less then a minutes</p-->
					<div class="invalid-feedback text-danger login_login"></div>
					<form method="POST" action="{{ route('enquery-login') }}" id="myform">
					@csrf
                        <div>
                            <div class="input-field s12">
							<input type="text" name="email" class="validate" autocomplete="off">
                                <label>User name</label>
                            </div>
							<div class="invalid-feedback text-danger email_login"></div>
                        </div>
                        <div>
                            <div class="input-field s12">
							<input type="password" name="password" id="password" class="validate password" autocomplete="off"><a onclick="show_password($(this));" class="eyebtn"><img src="{{asset('assets/admin/img/eye-inactive.svg')}}" /></a>
                                <label>Password</label>
                            </div>
							<div class="invalid-feedback text-danger password_login"></div>
                        </div>
						<div class="clearfix"></div>

						<!--div class="div">
                            <div class="input-field s12">
								
                            </div>
							<div class="invalid-feedback text-danger g-recaptcha-response_login"></div>
                        </div-->
                        <div>
                            <div class="s12 log-ch-bx">
                                <p>
                                    <input type="checkbox" name="remember" id="test5" />
                                    <label for="test5">Remember me</label>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="input-field s4 mt-4 pt-3">
                                <input type="button" onclick="enquery_login($(this));" value="Login" class="waves-effect waves-light log-in-btn"> </div>
                        </div>
						<div class="clearfix"></div>
                        <div>
                            <div class="input-field s12"> <a href="#" class="f-14" data-dismiss="modal" data-toggle="modal" data-target="#modal3">Forgot password</a> | <a href="#"  class="f-14" data-dismiss="modal" data-toggle="modal" data-target="#register">Create a new account</a> </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		

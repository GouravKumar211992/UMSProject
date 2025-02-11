<style>
.register-in-btn{
	color: #fff !important;
	background: #1da1f2;
    padding: 2px 10px;
    font-weight: 600;
	border-radius: 0.2em;
    vertical-align: bottom;
    height: 32px;
	position: relative;
    cursor: pointer;
    display: inline-block;
    overflow: hidden;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    vertical-align: middle;
    z-index: 1;
    transition: .3s ease-out;
	border: 0;
    font-style: normal;
    font-size: inherit;
    text-transform: inherit;
    outline: none;
	padding: 8px;
    box-sizing: border-box;
    height: 40px;
}
input[type="checkbox"] {
  -webkit-appearance: checkbox;
     -moz-appearance: checkbox;
          appearance: checkbox;
  display: inline-block;
  width: auto;
}
</style>
<div class="log-in-pop-right">
                    <h4>Login</h4>
                    <!--p>Don't have an account? Create your account. It's take less then a minutes</p-->
					<form method="POST" action="{{ route('enquery-login') }}" id="myform">
					<div class="invalid-feedback text-danger login_login"></div>

                    @include('partials.notifications')

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

						
                        <div>
                            <div class="s12 log-ch-bx">
                                <p>
                                <input type="checkbox" value="lsRememberMe" id="rememberMe"> <label for="rememberMe">Remember me</label>
                                    <!-- <input type="checkbox" id="test5" />
                                    <label for="test5">Remember me</label> -->
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="input-field s4 mt-4 pt-3 text-left">
                                <input type="button" onclick="enquery_login($(this));" value="Login" class="waves-effect waves-light log-in-btn">
								<a href="#"  class="register-in-btn" data-dismiss="modal" data-toggle="modal" data-target="#register">Register</a>
							</div>
                        </div>
						<div class="clearfix"></div>
                        <div>
                            <div class="input-field s4 pt-3 text-left">
							<a href="{{asset('addmission_manual.pdf')}}" target="_blank" download style="color:#fff;" class="btn btn-warning;"><i class="fa fa-file"></i> Guideline to fill the online application form</a>
							</div>
                        </div>
						<div class="clearfix"></div>
                        <div>
                            <div class="input-field s12"> <a href="#" class="f-14" data-dismiss="modal" data-toggle="modal" data-target="#modal3">Forgot password</a> | <a href="#"  class="f-14" data-dismiss="modal" data-toggle="modal" data-target="#register">Create a new account</a> </div>
                        </div>
                    </form>
                </div>
                <script>
                    function lsRememberMe() {
                        if (rmCheck.checked && emailInput.value !== "") {
                            localStorage.username = emailInput.value;
                            localStorage.checkbox = rmCheck.value;
                        } else {
                            localStorage.username = "";
                            localStorage.checkbox = "";
                        }
                    }
                    </script>
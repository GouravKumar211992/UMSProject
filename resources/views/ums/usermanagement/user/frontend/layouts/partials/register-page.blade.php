                <div class="log-in-pop-right">
                    <h4>Register Yourself</h4>
                    <!--p>Don't have an account? Create your account. It's take less then a minutes</p-->
					<div class="invalid-feedback text-danger register_register"></div>
					<form class="row" method="POST" action="{{ route('enquery-register') }}" id="myform_register">
					@csrf
						<div class="col-md-4 removeError">
	                        <div class="input-field s12">
	                            <input type="text" name="first_name" class="validate" autocomplete="off">
	                            <label>First Name</label>
	                        </div>
	                        <div class="invalid-feedback text-danger first_name_register"></div>
	                    </div>
	                    <div class="col-md-4 removeError">
	                        <div class="input-field s12">
	                            <input type="text" name="middle_name" class="validate" autocomplete="off">
	                            <label>Middle Name</label>
	                        </div>
	                        <div class="invalid-feedback text-danger middle_name_register"></div>
	                    </div>
	                    <div class="col-md-4 removeError">
	                        <div class="input-field s12">
	                            <input type="text" name="last_name" class="validate" autocomplete="off">
	                            <label>Last Name</label>
	                        </div>
	                        <div class="invalid-feedback text-danger last_name_register"></div>
	                    </div>

	                    <div class="clearfix"></div>
                        <div class="col-md-6 removeError">
                            <div class="input-field s12">
                                <input type="email" name="email" class="validate" autocomplete="off">
                                <label>Email id</label>
                            </div>
							<div class="invalid-feedback text-danger email_register"></div>
                        </div>
						<div class="col-md-6 removeError">
                            <div class="input-field s12">
                                <input type="email" name="mobile" class="validate numbersOnly" maxlength="10" autocomplete="off">
                                <label>Mobile No</label>
                            </div>
							<div class="invalid-feedback text-danger mobile_register"></div>
                        </div>

						<div class="clearfix"></div>
						<div class="col-md-6 removeError">
                            <div class="input-field s12">
							<input type="password" name="password" class="validate password" autocomplete="off">
                                <a onclick="show_password($(this));" class="eyebtn"><img src="{{asset('assets/admin/img/eye-inactive.svg')}}" /></a><label>Password</label>
                            </div>
							<div class="invalid-feedback text-danger password_register"></div>
                        </div>
                        
                        <div class="col-md-6 removeError">
                            <div class="input-field s12">
							<input type="password" name="password_confirmation" class="validate password" autocomplete="off"><a onclick="show_password($(this));" class="eyebtn"><img src="{{asset('assets/admin/img/eye-inactive.svg')}}" /></a>
                                <label>Confirm password</label>
                            </div>
							<div class="invalid-feedback text-danger password_confirmation_register"></div>
                        </div>

						<div class="clearfix"></div>

						<div class="col-md-6 removeError">
                            <div class="input-field s12">
								{!! app('captcha')->display() !!}
                            </div>
							<div class="invalid-feedback text-danger g-recaptcha-response_register"></div>
                        </div>

						<div class="clearfix"></div>
                        <div class="col-md-12 mt-4 pt-3">
                            <div class="input-field s4">
                                <input type="button" onclick="enquery_register($(this));" value="Register" class="waves-effect waves-light log-in-btn"> </div>
                         
                            <div class="input-field s12"> <a href="{{url('admission-portal')}}" >Already have an account ? Login</a> </div>
                        </div>
                    </form>
                </div>

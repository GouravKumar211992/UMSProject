
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Result Portal</title>


    <link rel="shortcut icon" href="/assets/frontend/images/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700%7CJosefin+Sans:600,700" rel="stylesheet">
    <link rel="stylesheet" href="/assets/frontend/css/font-awesome.min.css">
    <link href="/assets/frontend/css/materialize.css" rel="stylesheet">
    <link href="/assets/frontend/css/bootstrap.css" rel="stylesheet" />
    <link href="/assets/frontend/css/style.css" rel="stylesheet" />
    <link href="/assets/frontend/css/style-mob.css" rel="stylesheet" />
	<link href="{{asset('assets/frontend/css/result.css')}}" rel="stylesheet">

    @yield('styles')
</head>
<body>
	<section class="bg-white py-3 border-bottomdashed">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="{{asset('assets/frontend/css/landing/mainpagelogo.png')}}" />
                    </div>
                </div>
            </div>
        </section>

<section class="bg-img" style="background: url({{asset('assets/frontend/css/landing/university-bgmain.jpg')}})">

        <!--Import jQuery before materialize.js-->
    <script src="/assets/frontend/js/main.min.js"></script>
    <script src="/assets/frontend/js/bootstrap.min.js"></script>
    <script src="/assets/frontend/js/materialize.min.js"></script>
    <script src="/assets/frontend/js/custom.js"></script>

    

    @yield('scripts')

    <form method="post" id="form1">
    	@csrf
    <div>

   <div class="super_container">

	<!-- Home -->



	<!-- Features -->

	<div class="features">
		<div class="container">

			<div class="row features_row">

				<!-- Features Item -->

    <div class="container" style="margin-top:40px;" id="example1">
       @include('admin.partials.notifications')
		<div class="row">
			<div class="col-sm-6 col-md-4 ">
				<div class="panel panel-default" style="background:transparent;border: transparent;">
					<div class="panel-heading">
						<strong><center>Result Portal</center></strong>
					</div>
					<div class="panel-body">
						<fieldset>
								<div class="row">
									<div class="center-block text-center">
										<img src="assets\frontend\images\icon.png" class="circular--square"  />
									</div>
								</div><br><br>
								<div class="row">
									<div class="col-sm-12 col-md-10  col-md-offset-1 ">

										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-earphone"></i><br>
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
												<input name="roll_no" type="text" id="roll_no" class="form-control" placeholder="Roll Number" style="border: 1px solid #c0c0c0;padding-left: 5px;" value="{{old('roll_no')}}" />
												<input name="dob" type="text" id="dob" class="form-control"style="border: 1px solid #c0c0c0;padding-left: 5px;" placeholder="Date Of Birth" onfocus="(this.type='date')" value="{{old('dob')}}" />	</div>
										</div><br>
										{{--  <div class="form-group">
											<div class="removeError">
											<div class="input-field s12">
												
											</div>
											<div class="invalid-feedback text-danger g-recaptcha-response_register"></div>
										</div>  --}}
										</div>
										<div class="form-group">

                                        <input type="submit" class="btn btn-lg btn-primary btn-block" />

										</div>
									</div>
								</div>
							</fieldset>
					</div>

                </div>
			</div>
		</div>
	</div>




			</div>
		</div>
	</div>



	<!-- Footer -->


</div>


    </div>
    </form>
</section>
<script src="{{asset('assets/frontend/js/result.js')}}"></script>
</body>
</html>



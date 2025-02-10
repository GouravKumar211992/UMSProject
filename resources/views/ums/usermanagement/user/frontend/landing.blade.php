<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dr. Shakuntala Misra National Rehabilitation University, Lucknow</title>

	<link href="{{asset('assets/frontend/css/landing/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/landing/style.css')}}">
	<link rel="shortcut icon" href="/assets/frontend/images/favicon.png" type="image/x-icon">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700%7CJosefin+Sans:600,700" rel="stylesheet">
	<link rel="stylesheet" href="/assets/frontend/css/font-awesome.min.css">
	<link href="/assets/frontend/css/materialize.css" rel="stylesheet">
	<link href="/assets/frontend/css/bootstrap.css" rel="stylesheet" />
	<link href="/assets/frontend/css/style.css" rel="stylesheet" />
	<link href="/assets/frontend/css/style-mob.css" rel="stylesheet" />
	<script src="https://www.google.com/recaptcha/api.js?render=6Lc_WLUfAAAAALifvZW6ZwDhGBRysm0n1pptXY4g"></script>
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

        <section class="bg-img" style="background: url({{asset('assets/frontend/css/landing/university-bgmain.jpg')}});background-repeat: no-repeat;
    background-size: 100% 100%;"> 
            <div class="container-fluid">
                 	<div class="row">
						<div class="col-md-12">
							<div class="containerBox">
								
								<div class="row"> 
									<div class="col-md-6">
										<div class="head-section">
											<h2><span>WELCOME,</span> To DSMNRU ERP</h2>
											<h5>Below are the important Links of ERP</h5>
											<p>Please click on the particular link to view the details</p>
										</div> 
									</div>
								</div>

								@include('partials.notifications')

								<div class="row">
									<div class="col-md-5">
										<div class="row dsmnruaction"> 
												<div class="col-md-3 col-4 text-center">
													<a href="{{url('admin/login')}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/university.png')}}" alt="" >
															</div>
															<p>University</p>
														</div>
													</a>
												</div>
												<div class="col-md-3 col-4 text-center">
													<a href="{{url('admission-portal')}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/admission.png')}}" alt="" >
															</div>
															<p>Admission</p>
														</div>
													</a>
												</div>
												<div class="col-md-3 col-4 text-center">
													<a href="{{url('student/login')}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/student.png')}}" alt="" >
															</div>
															<p>Student</p>
														</div>
													</a>
												</div>
												<div class="col-md-3 col-4 text-center">
													<a href="{{route('exam-login',['exam_portal'=>1])}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/examination.png')}}" alt="" >
															</div>
															<p>Examination</p>
														</div>
													</a>
												</div>
												<div class="col-md-3 col-4 text-center">
													<a href="{{url('faculty')}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/faculty.png')}}" alt="" >
															</div>
															<p>Faculty</p>
														</div>
													</a>
												</div>
												<div class="col-md-3 col-4 text-center">
													<a href="{{url('hod/login')}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/pngtree-office-manager-image_1232630.png')}}" alt="" >
															</div>
															<p>HOD</p>
														</div>
													</a>
												</div>
												<!-- <div class="col-md-3 col-4 text-center">
													<a href="#!" data-toggle="modal" id="login_btn" data-target="#login">
														<div class="curve-box">
															<div class="head-img"> 
															<img src="{{asset('assets/frontend/css/landing/student.png')}}" alt="" >
															</div>
															<p>One View</p>
														</div>
													</a>
												</div> -->
												<div class="col-md-3 col-4 text-center">
													<a href="{{url('result-portal')}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/result.png')}}" alt="" >
															</div>
															<p>Result</p>
														</div>
													</a>
												</div>
												<div class="col-md-3 col-4 text-center">
													<a href="{{url('certificate-portal')}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/certificate.png')}}" alt="" >
															</div>
															<p>Certificate</p>
														</div>
													</a>
												</div>
												<div class="col-md-3 col-4 text-center">
													<a href="{{route('affiliate-login')}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/affiliate.jpg')}}" alt="" >
															</div>
															<p>Affiliate College</p>
														</div>
													</a>
												</div>
												<div class="col-md-3 col-4 text-center">
													<a href="{{route('complaint-form')}}">
														<div class="curve-box">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/grievance1.png')}}" alt="" >
															</div>
															<p>Grievance</p>
														</div>
													</a>
												</div>
												<br>
												<div class="col-md-3 col-4 text-center">
													<a href="https://accounts.digilocker.gov.in/signin/smart_v2/30fffe67c0ffa19102a0c55983f27702--en" target="_blank">
														<div class="curve-box" style="height: 140px;">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/digilocker.png')}}" alt="" >
															</div>
															<p>Digilocker</p>
														</div>
													</a>
												</div>
												{{--<div class="col-md-3 col-4 text-center">
													<a href="{{url('digilockerupload')}}" target="_blank">
														<div class="curve-box" style="height: 140px;">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/digilocker.png')}}" alt="" >
															</div>
															<p>Digilocker Degree Uploading</p>
														</div>
													</a>
												</div>
												<div class="col-md-3 col-4 text-center">
													<a href="{{url('digilockeruploadingvedio')}}" target="_blank">
														<div class="curve-box" style="height: 140px;">
															<div class="head-img"> 
																<img src="{{asset('assets/frontend/css/landing/digilocker.png')}}" alt="" >
															</div>
															<p>Digilocker Uploading<br> Video</p>
														</div>
													</a>
												</div>--}}

										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 text-center">
										<code style="color:#000;">Note:
											If Any Query Please Call or Whatsapp on :<a href="tel:+919667277184">+919667277184</a> between 10:00AM to 5:00PM
											<br/>Or Mail To <a href="mailto:dsmnru.help@gmail.com">dsmnru.help@gmail.com</a>
										</code>
									</div>
								</div>


							</div>
							
						</div>
					</div> 
                </div>
        </section>
	
	
	
		<footer>
			<p>Copyright © 2021-22. All Rights Reserved - Dr Shakuntala Misra National Rehabilitation University, Lucknow</p>
			<p>Powered by <strong>Sheela Foam Ltd.</strong></p> 
		</footer>
    


<!-- Start Panel -->
<div id="login" class="modal fade" role="dialog">
	<div class="log-in-pop"> 
		<div class="log-in-pop-right">
			<a href="#" class="pop-close" data-dismiss="modal"><img src="{{asset('assets/frontend/images/cancel.png')}}" alt=""></a>
			<img src="{{asset('assets/frontend/images/logo.png')}}" alt="" class="mb-5">
			<h4>Enter Roll Number</h4>
			<form method="GET" action="{{route('oneViewResult',[1])}}" id="myform">
				<div class="input-field s12">
					<input type="text" name="roll_no" class="validate valid roll_no" autocomplete="off">
					<label class="active">Roll Number</label>
				</div>
				<div class="clearfix"></div>
				<div class="input-field s4 mt-4 pt-3">
					<i class="waves-effect waves-light log-in-btn waves-input-wrapper">
						<input type="button" value="Show One View" class="waves-button-input" onClick="oneViewResult()">
					</i> 
				</div>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
<!-- End Panel -->

 
<script src="/assets/frontend/js/main.min.js"></script>
	<script src="/assets/frontend/js/bootstrap.min.js"></script>
	<script src="/assets/frontend/js/materialize.min.js"></script>
	<script src="/assets/frontend/js/custom.js"></script>

	<script src="https://www.google.com/recaptcha/api.js?" async defer></script>
	<script>
		function oneViewResult(){
			var roll_no = $('.roll_no').val();
			if(roll_no==''){
				alert('Please Enter Any Valid Roll Number');
				return fales;
			}
			window.location.href = "{{url('one-view-result')}}/"+btoa(roll_no);
		}
	</script>
</body>
</html>
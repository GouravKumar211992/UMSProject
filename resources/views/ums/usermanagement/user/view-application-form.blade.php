
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Dashboard</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../assets/css/favicon.png">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600;700" rel="stylesheet"> --}}

    @include('ums.admin.header')

</head>
<!-- END: Head-->




{{-- @section('content') --}}

<style>
	.education_btn{
		cursor:pointer;
	}
	@media print {
		.print_hide,
		.wed-hom-footer,
		.copy-right,
		.icon-float,
		.ed-top,
		.top-logo{
			display:none;
		}
		body{
			margin: 0mm 0mm 0mm 0mm;
		}
		@page {size: portrait;}
	}
	@media print{
		.viewapplication-form p {
			background: #7c6f0c !important;
		}
	}
	
	</style>



<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
data-menu="vertical-menu-modern" data-col="">

@php $btech_course = array(114,133,134); @endphp

	<!--SECTION START-->
    <section class=" mt-2">
        <div class="container com-sp pad-bot-70 pg-inn  bg-white shadow p-5">
		<div class="col-md-12 col-xs-12">
			<h2 class="mt-0 mb-3 mobileadmintxt">Application Form Filled</h2>
			<h5 class="mt-0 mb-3 mobileadmintxt"><b>Application Number : {{$application->application_no}}</b></h5>
			<hr />
		</div>

					<div class="invalid-feedback text-danger error_application"></div>
							  
    
    
                 		<div class="row"> 
							<div class="col-md-6 col-xs-6 col-xs-6 mb-5">
                           <h5 class="front-form-head">Application For</h5>
						   <div class="col-md-12 col-xs-12 col-xs-12">
								<p class="newboxciradio">
                                    <input type="radio" class="filled-in" name="application_for" value="1" id="applicatio_for1" disabled @if($application->application_for== 1) checked @endif>
                                    <label for="applicatio_for1"  class="form-check-label"><strong>IN DSMNRU CAMPUS</strong></label>
									
									<input type="radio" class="filled-in" name="application_for" value="2" id="applicatio_for2" disabled @if($application->application_for!= 1)) checked @endif>
                                    <label for="applicatio_for2"  class="form-check-label ml-5"><strong>IN AFFILIATED COLLEGES</strong></label>

									<div class="invalid-feedback text-danger application_for_application"></div>
                                </p>
							</div>
                         </div> 
						 <div class="col-md-6 col-xs-6 col-xs-6 mb-5">
                           <h5 class="front-form-head">College Name</h5>
						   <p>{{$application->campus->name}}</p>
                         </div>
						</div>
						
						
                
                    <div class="row mb-5 viewapplication-form">
                        <div class="col-md-12 col-xs-12">
                           <h5 class="front-form-head">  Program Details</h5>
                         </div>
						<div class="col-md-12 col-xs-12 row">
							<div class="col-md-4 col-xs-4 row">
								<div class="form-group">
									<label for="">Academic Session</span></label> 
									<p>{{$application->academic_session}}</p>
									</select>
								</div>
							</div>
                        </div>
						<div class="col-md-4 col-xs-4">
                            <label for="">Programme/CourseType*</label>
                            <p>{{$program->name}}</p>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <label for="">Name of Programme/Course</label>
                            <p>{{$course->name}}</p>
						</div>
						@if($course->id ==126)
						<div class="col-md-4 col-xs-4 hidden">
                            <label for="">Lateral Entry</label>
                            <p>{{strtoupper($application->lateral_entry)}}</p>
						</div>
						@endif
						@php $course_single = $course; @endphp
						@include('ums.usermanagement.user.frontend.index.application-course-preference-view')
                    </div>
                 
    
                <div class="row mb-2 viewapplication-form">
                    <div class="col-md-12 col-xs-12">
						<h5 class="front-form-head">  Student Details</h5> 
                    </div>
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>First Name *</label>
							<p>{{$application->first_Name}}</p>
							</div>
					</div>
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Middle Name</label>
							<p>{{$application->middle_Name}}</p>
							</div> 
					</div>
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Last Name</label>
							<p>{{$application->last_Name}}</p>
						</div> 
					</div>
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label for="">Date of Birth *</label>
							<p>{{date("d/m/Y", strtotime($application->date_of_birth))}}
							</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label for="">E-mail ID *</label>
							<p>{{$application->email}}</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label for="">Mobile Number*</label>
							<p>{{$application->mobile}}</p>
						</div> 
					</div>
                </div>
                
    
                <div class="row mb-2 viewapplication-form">
                    <div class="col-md-6 col-xs-6">
						<h5 class="pl-0 font-weight-bold">  Father's Name</h5> 
						<p>{{$application->father_first_name}}</p>
                    </div>
                    <div class="col-md-6 col-xs-6">
						<h5 class="pl-0 font-weight-bold">Father's Mobile Number</h5> 
						<p>{{$application->father_mobile}}</p>
                    </div>
                </div> 
                <div class="row mb-2 viewapplication-form">
                    <div class="col-md-6 col-xs-6">
						<h5 class="pl-0 font-weight-bold">  Mother's Name</h5> 
						<p>{{$application->mother_first_name}}</p>
                    </div>
                    <div class="col-md-6 col-xs-6">
						<h5 class="pl-0 font-weight-bold">Mother's Mobile Number</h5> 
						<p>{{($application->mother_mobile)?$application->mother_mobile:'N/A'}}</p>
                    </div>
                </div> 
				
				<div class="row mb-5 viewapplication-form">
                    <div class="col-md-6 col-xs-6">
						<h5 class="pl-0 font-weight-bold"> Local Guardian Name</h5> 
						<p>{{$application->guardian_first_name}}</p>
                    </div>
                    <div class="col-md-6 col-xs-6">
						<h5 class="pl-0 font-weight-bold"> Local Guardian Mobile</h5> 
						<p>{{$application->guardian_mobile}}</p>
                    </div>
                </div>
				@if($application->nominee_name)
				<div class="row mb-5 viewapplication-form">
                    <div class="col-md-6 col-xs-6">
						<h5 class="pl-0 font-weight-bold"> Name of Nominee (For Insurance Purpose)</h5> 
						<p>{{$application->nominee_name}}</p>
                    </div>
                </div>
				@endif
				
				
				<div class="row mb-5 viewapplication-form">
                    <div class="col-md-12 col-xs-12">
						<h5 class="front-form-head"> Personal Information</h5> 
                    </div>
					 
					
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Gender*</label> 
							<p>{{strtoupper($application->gender)}}</p>
						</div> 
					</div> 

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Religion*</label>
							<p>{{$application->religion}}</p>
						</div> 
					</div>

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Marital Status*</label> 
							<p>{{strtoupper($application->marital_status)}}</p>
						</div> 
					</div>
					
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Blood Group*</label> 
							<p>{{$application->blood_group}}</p>
						</div> 
					</div>

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Domicile*</label>
							@if(Request()->edit=='true' && admission_open_couse_wise($application->course_id,2,$application->academic_session))
							<button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#editDomicile"><i class="fa fa-edit"></i> Edit Domicile</button>
							@include('frontend.index.edit-application-form.edit-domicile')
							@endif
							<p>{{$application->domicile}}</p>
						</div> 
					</div>

					@if($application->enrollment_number)
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Enrollment Number</label> 
							<p>{{$application->enrollment_number}}</p>
						</div> 
					</div>
					@endif

					

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Category*</label>
							@if(Request()->edit=='true' && admission_open_couse_wise($application->course_id,2,$application->academic_session))
							<button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#editCastCategory"><i class="fa fa-edit"></i> Edit Category</button>
							@include('frontend.index.edit-application-form.edit-cast-category')
							@endif
							<p>{{$application->category}}</p>							
						</div> 
					</div> 
					@if($application->certificate_number)
					<div class="col-md-3 col-xs-3 mb-2" id="certificate_no1">
						<div class="form-group">
							<label>Certificate Number*</label> 
							<p>{{$application->certificate_number}}</p>
						</div> 
					</div>
					@endif					
 
					
					
					

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Nationality*</label> 
							<p>{{$application->nationality}}</p>
						</div> 
					</div>
					
					
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>DSMNRU Employee*</label>
							<p>{{strtoupper($application->dsmnru_employee)}}</p>							
						</div> 
					</div> 
					
					@if($application->dsmnru_employee == 'yes')					
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>DSMNRU Relation*</label>
							<p>{{strtoupper($application->dsmnru_relation)}}</p>							
						</div> 
					</div>
					@endif
					
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>DSMNRU Employee Ward*</label> 
							<p>{{strtoupper($application->dsmnru_employee_ward)}}</p>
						</div> 
					</div>
					@if($application->dsmnru_employee_ward == 'yes')
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Ward Employee Name*</label> 
							<p>{{strtoupper($application->ward_emp_name)}}</p>
						</div> 
					</div> 
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Ward Employee Relation*</label> 
							<p>{{strtoupper($application->ward_emp_relation)}}</p>
						</div> 
					</div>
					@endif
					
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Disability*</label>
							<p>{{strtoupper($application->disability)}}</p>
						</div> 
					</div> 
					@if($application->disability == 'yes')
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Disability Category*</label>
							<p>{{strtoupper($application->disability_category)}}</p>
						</div> 
					</div> 
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Percentage of Disability</label>
							<p>{{$application->percentage_of_disability}}</p>
						</div> 
					</div> 
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Disability Certificate</label> 
							<a href="{{$application->upload_disability_certificate}}" target="_blank"><img src="{{$application->upload_disability_certificate}}" alt="" style="width:100px; height:100px;"></a>

						</div> 
					</div>
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>UDID Number*</label>
							<p>{{$application->udid_number}}</p>
						</div> 
					</div>
					@endif 

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Freedom Fighter Dependent*</label>
							<p>{{strtoupper($application->freedom_fighter_dependent)}}</p>
						</div> 
					</div>
					
					
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>NCC*</label> 
							<p>{{strtoupper($application->ncc)}}</p>
						</div> 
					</div> 
					
					
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>NSS*</label> 
							<p>{{strtoupper($application->nss)}}</p>
						</div> 
					</div> 

										
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Sports*</label> 
							<p>{{strtoupper($application->sports)}}</p>
						</div> 
					</div> 

					@if($application->sport_level)
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Sport Level</label> 
							<p>{{strtoupper($application->sport_level)}}</p>
						</div> 
					</div>
					@endif

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Hostel Facility Required*</label> 
							<p>{{strtoupper($application->hostel_facility_required)}}</p>
						</div> 
					</div> 

					@if($application->hostel_for_years)
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>How many years staying in DSMNRU Hostel</label> 
							<p>{{$application->hostel_for_years}}</p>
						</div> 
					</div>
					@endif

					@if($application->hostel_for_years)
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Distance from your residence to University campus</label> 
							<p>{{$application->distance_from_university}}</p>
						</div> 
					</div>
					@endif

				
					
					
					<!-- <div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Zero Fee </label> 
							<p>{{$application->zero_fee}}</p>
						</div> 
					</div>
					
					
					
					<div class="col-md-4 col-xs-4 mb-2">
						<div class="form-group">
							<label>Is Father's Income below Rs. 2,00,000 PA ?</label> 
							<p>{{$application->is_father_income}}</p>
						</div> 
					</div>
					 
					<div class="col-md-4 col-xs-4 mb-2">
						<div class="form-group">
							<label>Annual Income of Family (in Rs.)*</label> 
							<p>{{$application->annual_income}}</p>
						</div> 
					</div>
					
					
					
					<div class="col-md-4 col-xs-4 mb-2">
						<div class="form-group">
							<label>Income Certificate Number</label>  
							<p>{{$application->income_certificate_number}}</p>
						</div> 
					</div>
					 
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>ID Proof*</label>
							<p>{{$application->id_proof}}</p>
							</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>ID Number*</label>
							<p>{{$application->id_number}}</p>
							</div> 
					</div> -->
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Aadhar Card Number*</label>
							<p>{{$application->adhar_card_number}}</p>
							</div> 
					</div>

					<div class="col-md-4 col-xs-4 mb-2">
						<label>DSMNRU Student*</label>
						<p>{{$application->dsmnru_student}}</p>
					</div>
					@if($application->dsmnru_student=='Yes')
					<div class="col-md-4 col-xs-4 mb-2" id="enrollment11">
					<label>Enrollment Number*</label>
					<p>{{$application->enrollment_number}}</p>
					</div>
					@endif

					@if((in_array($application->course_id, $btech_course)) && $application->lateral_entry=='no')
					<div class="col-md-12 col-xs-12">
						<h5 class="front-form-head">In which national/state level entrance test you appeared.
						@if(Request()->edit=='true' && admission_open_couse_wise($application->course_id,2,$application->academic_session))
						<button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#entranceTest"><i class="fa fa-edit"></i> Edit Entrance Test</button>
						@include('frontend.index.edit-application-form.entrance-test')
						@endif
						</h5>
					</div>
					<div class="col-md-4 col-xs-4 mb-2">
						<div class="form-group">
							<label>Selected Process<span class="text-danger">*</span></label>
							<p>{{$application->admission_through}}</p>
						</div>
					</div>
					@if($application->admission_through_exam_name)
					<div class="col-md-4 col-xs-4 mb-2">
						<div class="form-group">
							<label>Name of Exam<span class="text-danger">*</span></label>
							<p>{{$application->admission_through_exam_name}}</p>
						</div>
					</div>
					@endif
					@if($application->appeared_or_passed)
					<div class="col-md-4 col-xs-4 mb-2">
						<div class="form-group">
							<label>Appeared or Passed<span class="text-danger">*</span></label>
							<p>{{$application->appeared_or_passed}}</p>
						</div>
					</div>
					@endif
					@if($application->date_of_examination)
					<div class="col-md-4 col-xs-4 mb-2 jee" >
						<div class="form-group">
							<label>Date of Examination<span class="text-danger">*</span></label>
							<p>{{$application->date_of_examination}}</p>
						</div>
					</div>
					@endif
					@if($application->roll_number)
					<div class="col-md-4 col-xs-4 mb-2 jee" >
						<div class="form-group">
							<label>Roll Number<span class="text-danger">*</span></label>
							<p>{{$application->roll_number}}</p>
						</div>
					</div>
					@endif
					@if($application->score)
					<div class="col-md-4 col-xs-4 mb-2 jee" >
						<div class="form-group">
							<label>Score<span class="text-danger">*</span></label>
							<p>{{$application->score}}</p>
						</div>
					</div>
					@endif
					@if($application->rank)
					<div class="col-md-4 col-xs-4 mb-2 jee" >
						<div class="form-group">
							<label>Rank<span class="text-danger">*</span></label>
							<p>{{$application->rank}}</p>
						</div>
					</div>
					@endif

					@endif


					@if($application->course_id==11 || $application->course_id==26 || $application->course_id==27)
					<div class="row" id="ded">
						<div class="col-md-12 col-xs-12">
							<h5 class="front-form-head">D.Ed. Spl.Ed. (HI/VI/ID) Fill These Fields</h5>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>AIOT Score<span class="text-danger">*</span></label>
							</div>
						</div>
						<div class="col-md-4 col-xs-4">
							<div class="form-group">
							<p>{{$application->aiot_score}}</p>
							</div>
						</div> 

						<div class="col-md-2">
							<div class="form-group">
								<label>AIOT Rank<span class="text-danger">*</span></label>
							</div>
						</div>
						<div class="col-md-4 col-xs-4">
							<div class="form-group">
							<p>{{$application->aiot_rank}}</p>
							</div>
						</div> 
						<div class="col-md-2">
							<div class="form-group">
								<label>AIOT Score Card<span class="text-danger">*</span></label>
							</div>
						</div>
						<div class="col-md-4 col-xs-4">
							<div class="form-group">
							<a href="{{$application->aiot_score_card}}" target="_blank" rel="noopener noreferrer">
								<img style="width: 100px;border:1px solid;" src="{{$application->aiot_score_card}}" alt="Download Here">
							</a>
							</div>
						</div> 

					</div>
					@endif


					@if($application->course_id==41 || $application->course_id==42 || $application->course_id==43 || $application->course_id==44 || $application->course_id==45)

					<div class="col-md-12 col-xs-12">
						<h5 class="front-form-head">Admission Applying Through</h5>
					</div>
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Process</label> 
							<p>{{$application->admission_through}}</p>
						</div> 
					</div>

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Date Of Examination</label> 
							<p>{{$application->date_of_examination}}</p>
						</div> 
					</div>


					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Roll Number</label> 
							<p>{{$application->roll_number}}</p>
						</div> 
					</div>
					
					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Score</label> 
							<p>{{$application->score}}</p>
						</div> 
					</div>

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Merit</label> 
							<p>{{$application->merit}}</p>
						</div> 
					</div>

					<div class="col-md-3 col-xs-3 mb-2">
						<div class="form-group">
							<label>Rank</label> 
							<p>{{$application->rank}}</p>
						</div> 
					</div>
					@endif
					@include('ums.usermanagement.user.frontend.index.application-form.view-address')

                </div>


				<div class="row">
                    <div class="col-md-12 col-xs-12">

					<div class="table-responsive">
					@include('ums.usermanagement.user.frontend.index.application-form.view-education-qualification')

					@if(Request()->edit=='true' && admission_open_couse_wise($application->course_id,2,$application->academic_session))
						@include('frontend.index.application-form.edit-education-qualification')
					@endif
					</div>
					@include('ums.usermanagement.user.frontend.index.application-form.cuet-details')

					<div class="table-responsive">
     				<table class="table table-bordered uploadtable">
						<tr>
							<td colspan="2"><b>Uploaded Photo, Signature & Documents</b>
						</td>
						</tr>
						<tr>
							<td>Upload Your Photo*</td>
							<td>
								<a href="{{$application->photo_url_user}}" target="_blank"><img src="{{$application->photo_url_user}}" width="50px"  ></a>
								<div class="invalid-feedback text-danger upload_photo_application"></div>
							</td>
						</tr>
					   <tr>
						   <td>Upload Your Signature*</td>
							<td>
								<a href="{{$application->signature_url_user}}" target="_blank"><img src="{{$application->signature_url_user}}" width="50px"  ></a>
							</td>
					   </tr>
						@if($application->caste_certificate_url)
						<tr>
							<td>Caste Certificate (Excluding General)</td>
							<td>
								<a href="{{$application->caste_certificate_url}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->upload_disability_certificate)
						<tr>
							<td>Disability Certificate</td>
							<td>
								<a href="{{$application->upload_disability_certificate}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->ncc_cirtificate)
						<tr>
							<td>NCC Certificate</td>
							<td>
								<a href="{{$application->ncc_cirtificate}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->nss_cirtificate)
						<tr>
							<td>NSS Certificate</td>
							<td>
								<a href="{{$application->nss_cirtificate}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->freedom_fighter_dependent_file)
						<tr>
							<td>Freedom Fighter Dependent Certificate</td>
							<td>
								<a href="{{$application->freedom_fighter_dependent_file}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->sportt_cirtificate)
						<tr>
							<td>Sports Certificate</td>
							<td>
								<a href="{{$application->sportt_cirtificate}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->domicile_cirtificate)
						<tr>
							<td>Domicile Certificate</td>
							<td>
								<a href="{{$application->domicile_cirtificate}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->caste_cirtificate)
						<tr>
							<td>Uploaded Caste Certificate</td>
							<td>
								<a href="{{$application->caste_cirtificate}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->aiot_score_card)
						<tr>
							<td>AIOT Score Card</td>
							<td>
								<a href="{{$application->aiot_score_card}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->income_certificate_url)
						<tr>
							<td>Income Certificate (EWS)</td>
							<td>
								<a href="{{$application->income_certificate_url}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						@if($application->any_other_url)
						<tr>
							<td>Any Other</td>
							<td>
								<a href="{{$application->any_other_url}}" class="btn-sm btn-success" target="_blank">Download</a>
							</td>
						</tr>
						@endif
						
					</table>
						
					</div>

					<hr/>
                        <h5><b>Payment Details :
							@if($application->payment)
								@if($application->payment_details())
								<span class="text-success"><b>PAID</b></span>
								<table class="table table-bordered bottomform">
									{{--<tr>
										<td width="20%">ORDER ID</td>
										<td>
												{{@$applicationPayment->order_id}}
											</td>
									</tr>--}}
									<tr>
										<td>TRANSACTION ID</td>
										<td>
												{{@$applicationPayment->transaction_id}}
											</td>
									</tr>
									<tr>
										<td>PAID AMOUNT</td>
										<td>
												{{@$applicationPayment->paid_amount}}
											</td>
									</tr>
									<tr>
										<td>TXN DATE</td>
										<td>
												{{date('d-m-Y',strtotime(@$applicationPayment->txn_date))}}
											</td>
									</tr>
									<tr>
										<td>TXN STATUS</td>
										<td>
												{{@$applicationPayment->txn_status}}
											</td>
									</tr>
								</table>
							@else
							@if(admission_open_couse_wise($application->course_id,1))
									<a target="_blank" href="{{route('pay-now',['id'=>$application->id])}}" class="btn-sm btn-primary" style="color: #fff;">Pay Now</a>
									@endif
								@endif
							@else
								@if(admission_open_couse_wise($application->course_id,1))
								{{$application->payment_status_text}} <a target="_blank" href="{{route('pay-now',['id'=>$application->id])}}" class="btn-sm btn-primary" style="color: #fff;">Pay Now</a>
								@endif
							@endif


						</b></h5> 
						<br/>
						@if($application->payment && $application->payment_details())
                        <h5><b>DECLARATION</b></h5> 

                        <p class="text-justify">I do hereby, solemn and affirm that details provided by me in this application form under various heads are true & correct to the best of my knowledge and information. I affirm that no part of information has been concealed, fabricated or manipulated and that I have read university’s regulations for eligibility & admission procedure. In the event that information provided by me is found incorrect, inappropriate, false, manipulated or fabricated, the university shall have right to withdraw admission provided to me through this application and to take such legal action against me as may be warranted by law.</p> 
                        <p class="text-justify">I also acknowledge hereby that I have read general instructions for application, procedure of admission, general code of conduct, hostel rules, examination rules, anti-ragging guidelines issued by UGC or Dr. Shakuntala Misra National Rehabilitation University and that I shall abide by them at all points of time. If my involvement in activities relating to discipline in university is found evident, university shall have all rights to take appropriate action against me. I also acknowledge that I am not suffering from any contagious disease that poses potential threat to health and safety of students of the university and shall always treat students with special needs (differently-abled), girls students and economically/ socially deprived with compassion and cooperation.</p>
						<div>
						<p class="mt-4">
							<input type="checkbox" class="filled-in" name="is_agree" id="agree1" value="1" checked >
							<label for="agree1"  class="form-check-label" ><strong>I Agree</strong></label>
							<div class="invalid-feedback text-danger is_agree_application"></div>
						</p>
						</div>
					
					
					
					<!-- <h5 class="front-form-head">  Fee Details & Payment Submission</h5>
					<p><strong>Note:</strong> In case you get disconnected while making online payment or your payment gets failed, please login again with your registration ID and Password sent your email and Mobile to continue with the Online Payment process.</p>
					
					
					<h5 class="f-16"><strong><i class="fa fa-rupee"></i> 500/-</strong> Payable Amount </h3>
					<h5 class="f-16 border-bottom pb-3 mb-4"><strong><i class="fa fa-rupee"></i> 50/-</strong> Bank Transaction Charges</h3>
					<h4 class="f-18 mb-5 text-warning"><strong><i class="fa fa-rupee"></i> 550/-</strong> Total Amount </h3>
					
					
					
						<h5><strong class="f-15 mb-3">Payment Mode:</strong></h5>

						   <div class="row mb-5">
							   <div class="col-md-6 col-xs-6">
								   <ol class="pl-3">
									   <li class="f-14">Online (Credit card/Debit card/Net Banking )</li>
									   <li class="f-14">Offline ( to Pay Offline the details given below)</li>
								   </ol>
							   </div>
							   <div class="col-md-6 col-xs-6" style="display:none;">
								   <button class="btn btn-dark mt-4 " style="margin:auto" type="button">make payment</button>
							   </div>
						   </div> 



						<h5 class="mb-3"><strong class="f-16">Bank Detail</strong></h5>
					
							<table class="table table-bordered">
							   <tr>
								   <td width="20%">Account Holder*</td>
								   <td><p class="f-16 text-black"><strong>DSMNRU</strong></p></td>
							   </tr>
							   <tr>
								   <td>Account Holder’s Name*</td>
								   <td><p class="f-16 text-black"><strong>University</strong></p></td>
							   </tr>
							   <tr>
								   <td>Name of the Bank</td>
								   <td><p class="f-16 text-black"><strong>Axis Bank</strong></p></td>
							   </tr>
							   <tr>
								   <td>Account No.</td>
								   <td><p class="f-16 text-black"><strong>098787655655</strong></p></td>
							   </tr>
							   <tr>
								   <td>IFSC Code</td>
								   <td><p class="f-16 text-black"><strong>UTIB0000654</strong></p></td>
							   </tr>
						   </table>
					
					<h5 class="mb-3"><strong class="f-16">After Payment: If Online than Auto else Candidate will fill offline Fee Details.</strong></h5>
					
					
					<table class="table table-bordered bottomform">
					   <tr>
						   <td width="20%">ORDER ID</td>
						   <td>
								{{@$applicationPayment->order_id}}
							</td>
					   </tr>
					   <tr>
						   <td>TRANSACTION ID</td>
						   <td>
								{{@$applicationPayment->transaction_id}}
							</td>
					   </tr>
					   <tr>
						   <td>PAID AMOUNT</td>
						   <td>
								{{@$applicationPayment->paid_amount}}
							</td>
					   </tr>
					   <tr>
						   <td>TXN DATE</td>
						   <td>
								{{date('d-m-Y',strtotime(@$applicationPayment->txn_date))}}
							</td>
					   </tr>
					   <tr>
						   <td>TXN STATUS</td>
						   <td>
								{{@$applicationPayment->txn_status}}
							</td>
					   </tr>
				   </table> -->
					
					<p class="mb-5"><a style="cursor:pointer;" onClick="window.print();"><i class="fa fa-print"></i> Download and print the application form</a></p>
					@endif

					
					
           			
					
					
					
                    </div>
                </div>
    
                           
            
   


           

            </div>
        </div>
    </section>
    <!--SECTION END-->

	
	




</body>
</html>

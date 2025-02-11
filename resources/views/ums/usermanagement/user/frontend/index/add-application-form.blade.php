
@extends('frontend.layouts.app')
@section('content')

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
}
</style>
	<!--SECTION START-->
    <section>
        <div class="container com-sp pad-bot-70 pg-inn pt-3">
            <div class="n-form-com admiss-form">
            
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mt-0 mb-3 mobileadmintxt">Add Application Form for Other Course</h2>
						<hr />
                    </div>
                </div>
				<form method="POST" action="{{ route('add-application-form') }}" id="myform_application" enctype="multipart/form-data" autocomplete="off">
				@csrf
			
					<div class="invalid-feedback text-danger error_application"></div>
							  
    
    
					<div class="row"> 
							<div class="col-md-6 mb-5">
                           <h5 class="front-form-head">Application For</h5>
						   <div class="col-md-12">
								<p class="newboxciradio">
                                    <input type="radio" class="filled-in" name="application_for" value="1" id="applicatio_for1" disabled @if($application->application_for== 1) checked @endif>
                                    <label for="applicatio_for1"  class="form-check-label"><strong>IN DSMNRU CAMPUS</strong></label>
									
									<input type="radio" class="filled-in" name="application_for" value="2" id="applicatio_for2" disabled @if($application->application_for!= 1)) checked @endif>
                                    <label for="applicatio_for2"  class="form-check-label ml-5"><strong>IN AFFILIATED COLLEGES</strong></label>

									<div class="invalid-feedback text-danger application_for_application"></div>
                                </p>
							</div>
                         </div> 
							<div class="col-md-6 mb-5">
                           <h5 class="front-form-head">College Name</h5>
                         </div>
						 <div class="col-md-6">
								<p>{{$application->campus->name}}</p>
							</div>
							</div>
    
                
                    <div class="row mb-5 viewapplication-form">
                        <div class="col-md-12">
                           <h5 class="front-form-head">  Program Details</h5>
                         </div>
						<div class="col-md-12 row">
							<div class="col-md-4 row">
								<div class="form-group">
									<input type="text" name="application_id" value="{{Request()->application_id}}" hidden/>
									<label for="">Academic Session</span></label> 
									<p>{{$application->academic_session}}</p>
									</select>
								</div>
							</div>
                        </div>
						<div class="col-md-4">
						<label for="">Programme/Course Type<span class="text-danger">*</span></label>
						<select class="form-control" name="course_type" id="course_type">
							<option value="">--Select Program--</option>
							@foreach($programm_types as $programm_type)
							<option value="{{$programm_type->id}}" >{{$programm_type->name}}</option>
							@endforeach
						</select>
						<div class="invalid-feedback text-danger course_type_application"></div>
					</div>
					<div class="col-md-4">
						<label for="">Name of Programme/Course<span class="text-danger">*</span></span></label>
						<select class="form-control" name="course_id" id="course_id" onChange="btech($(this).val())">

							<option value="">--Select Course--</option>
							@foreach($courses as $course)
							<option value="{{$course->id}}">{{$course->name}}</option>
							@endforeach
						</select>
						<div class="invalid-feedback text-danger course_id_application"></div>
					</div>
					<div class="col-md-4" id="hidelateral_entry">
						<label for="">Lateral Entry</label>
							<select class="form-control" name="lateral_entry" id="lateral_entry">
								<option value="">--Select Lateral Entry--</option>
								<option value="yes">Yes</option>
								<option value="no">No</option>
							</select>
						<div class="invalid-feedback text-danger course_id_application"></div>
					</div>
                   </div>
                 
    
					<div class="row mb-2 viewapplication-form">
                    <div class="col-md-12">
						<h5 class="front-form-head">  Student Details</h5> 
                    </div>
					<div class="col-md-4">
						<div class="form-group">
							<label>First Name *</label>
							<p>{{$application->first_Name}}</p>
							</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Middle Name</label>
							<p>{{$application->middle_Name}}</p>
							</div> 
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Last Name</label>
							<p>{{$application->last_Name}}</p>
						</div> 
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="">Date of Birth *</label>
							<p>{{date("d/m/Y", strtotime($application->date_of_birth))}}
							</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="">E-mail ID *</label>
							<p>{{$application->email}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="">Mobile Number*</label>
							<p>{{$application->mobile}}</p>
						</div> 
					</div>
                </div>
                
    
                <div class="row mb-2 viewapplication-form">
                    <div class="col-md-12">
						<h5 class="front-form-head no-bg pl-0 font-weight-bold">  Father Details</h5> 
                    </div>
					<div class="col-md-4">
						<div class="form-group">
							<label>First Name *</label>
							<p>{{$application->father_first_name}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Middle Name</label>
							<p>{{$application->father_middle_Name}}</p>
						</div> 
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Last Name</label>
							<p>{{$application->father_last_name}}</p>
						</div>
					</div> 
                </div> 
				
				
				<div class="row mb-2 viewapplication-form">
                    <div class="col-md-12">
						<h5 class="front-form-head no-bg pl-0 font-weight-bold">  Mother Details</h5> 
                    </div>
					<div class="col-md-4">
						<div class="form-group">
							<label>First Name *</label>
							<p>{{$application->mother_first_name}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Middle Name</label>
							<p>{{$application->mother_middle_Name}}</p>
						</div> 
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Last Name</label>
							<p>{{$application->mother_last_name}}</p>
						</div> 
					</div> 
                </div>
				
				<div class="row mb-5 viewapplication-form">
                    <div class="col-md-12">
						<h5 class="front-form-head no-bg pl-0 font-weight-bold">  Name of Nominee ( For Insurance Purpose)</h5> 
                    </div>
					<div class="col-md-4">
						<div class="form-group">
							<label>First Name *</label>
							<p>{{$application->nominee_first_name}}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Middle Name</label>
							<p>{{$application->nominee_middle_Name}}</p>
						</div> 
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Last Name</label>
							<p>{{$application->nominee_last_name}}</p>
						</div> 
					</div> 
                </div>
				<div class="row mb-5 viewapplication-form">
                    <div class="col-md-12">
						<h5 class="front-form-head no-bg pl-0 font-weight-bold"> Local Guardian Details</h5> 
                    </div>
					<div class="col-md-3">
						<div class="form-group">
							<label>First Name *</label>
							<p>{{$application->guardian_first_name}}</p>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Middle Name</label>
							<p>{{$application->guardian_middle_Name}}</p>
						</div> 
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Last Name</label>
							<p>{{$application->guardian_last_name}}</p>
						</div> 
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Mobile *</label>
							<p>{{$application->guardian_mobile}}</p>
						</div> 
					</div> 
                </div>
				
				
				<div class="row mb-5 viewapplication-form">
                    <div class="col-md-12">
						<h5 class="front-form-head">  Personal Information</h5> 
                    </div>
					 
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Domicile*</label> 
							<p>{{$application->domicile}}</p>
						</div> 
					</div>

					@if($application->domicile=='UTTAR PRADESH')
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Domicile Certificate</label> 
							<a href="{{$application->domicile_cirtificate}}" target="_blank"><img src="{{$application->domicile_cirtificate}}" alt="" style="width:100px; height:100px;"></a>

						</div> 
					</div>
					@endif

					@if($application->enrollment_number)
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Enrollment Number</label> 
							<p>{{$application->enrollment_number}}</p>
						</div> 
					</div>
					@endif
					
					
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Gender*</label> 
							.<p>{{$application->gender}}</p>
						</div> 
					</div> 
					
					
					
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Category*</label>
							<p>{{$application->category}}</p>							
						</div> 
					</div> 
					@if($application->certificate_number)
					<div class="col-md-3 mb-2" id="certificate_no1">
						<div class="form-group">
							<label>Certificate Number*</label> 
							<p>{{$application->certificate_number}}</p>
						</div> 
					</div>
					@endif					
 
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Religion*</label>
							<p>{{$application->religion}}</p>
						</div> 
					</div>
					
					
					
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Marital Status*</label> 
							<p>{{$application->marital_status}}</p>
						</div> 
					</div>
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Blood Group*</label> 
							<p>{{$application->blood_group}}</p>
						</div> 
					</div>

					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Nationality*</label> 
							<p>{{$application->nationality}}</p>
						</div> 
					</div>
					
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>DSMNRU Employee*</label>
							<p>{{$application->dsmnru_employee}}</p>							
						</div> 
					</div> 
					
					@if($application->dsmnru_employee == 'yes')					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>DSMNRU Relation*</label>
							<p>{{$application->dsmnru_relation}}</p>							
						</div> 
					</div>
					@endif
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>DSMNRU Employee Ward*</label> 
							<p>{{$application->dsmnru_employee_ward}}</p>
						</div> 
					</div>
					@if($application->dsmnru_employee_ward == 'yes')
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Ward Employee Name*</label> 
							<p>{{$application->ward_emp_name}}</p>
						</div> 
					</div> 
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Ward Employee Relation*</label> 
							<p>{{$application->ward_emp_relation}}</p>
						</div> 
					</div>
					@endif
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Disability*</label>
							<p>{{$application->disability}}</p>
						</div> 
					</div> 
					@if($application->disability == 'yes')
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Disability Category*</label>
							<p>{{$application->disability_category}}</p>
						</div> 
					</div> 
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Percentage of Disability</label>
							<p>{{$application->percentage_of_disability}}</p>
						</div> 
					</div> 
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>UDID Number*</label>
							<p>{{$application->udid_number}}</p>
						</div> 
					</div>
					@endif 

					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Freedom Fighter Dependent*</label>
							<p>{{$application->freedom_fighter_dependent}}</p>
						</div> 
					</div>
					@if($application->freedom_fighter_dependent=='yes')
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Freedom Fighter Certificate*</label>
							<a href="{{$application->freedom_fighter_dependent_file}}" target="_blank"><img src="{{$application->freedom_fighter_dependent_file}}" alt="" style="width:100px; height:100px;"></a>
						</div> 
					</div>
					@endif
					
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>NCC*</label> 
							<p>{{$application->ncc}}</p>
						</div> 
					</div> 
					
					@if($application->ncc=='yes')
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>NCC Certificate*</label>
							<a href="{{$application->ncc_cirtificate}}" target="_blank"><img src="{{$application->ncc_cirtificate}}" alt="" style="width:100px; height:100px;"></a>
						</div> 
					</div>@endif
					
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>NSS*</label> 
							<p>{{$application->nss}}</p>
						</div> 
					</div> 

					@if($application->nss=='yes')
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>NSS Certificate*</label>
							<a href="{{$application->nss_cirtificate}}" target="_blank"><img src="{{$application->nss_cirtificate}}" alt="" style="width:100px; height:100px;"></a>
						</div> 
					</div>@endif
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Sports*</label> 
							<p>{{$application->sports}}</p>
						</div> 
					</div> 

					@if($application->sport_level)
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Sport Level</label> 
							<p>{{$application->sport_level}}</p>
						</div> 
					</div>
					@endif

					@if($application->sports=='yes')
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Sport Certificate*</label>
							<a href="{{$application->sportt_cirtificate}}" target="_blank"><img src="{{$application->sportt_cirtificate}}" alt="" style="width:100px; height:100px;"></a>
						</div> 
					</div>@endif

					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Hostel Facility Required*</label> 
							<p>{{$application->hostel_facility_required}}</p>
						</div> 
					</div> 

					@if($application->hostel_for_years)
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>How many years staying in DSMNRU Hostel</label> 
							<p>{{$application->hostel_for_years}}</p>
						</div> 
					</div>
					@endif

					@if($application->hostel_for_years)
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Distance from your residence to University campus</label> 
							<p>{{$application->distance_from_university}}</p>
						</div> 
					</div>
					@endif



				
					
					
					<!-- <div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Zero Fee </label> 
							<p>{{$application->zero_fee}}</p>
						</div> 
					</div>
					
					
					
					<div class="col-md-4 mb-2">
						<div class="form-group">
							<label>Is Father's Income below Rs. 2,00,000 PA ?</label> 
							<p>{{$application->is_father_income}}</p>
						</div> 
					</div>
					 
					<div class="col-md-4 mb-2">
						<div class="form-group">
							<label>Annual Income of Family (in Rs.)*</label> 
							<p>{{$application->annual_income}}</p>
						</div> 
					</div>
					
					
					
					<div class="col-md-4 mb-2">
						<div class="form-group">
							<label>Income Certificate Number</label>  
							<p>{{$application->income_certificate_number}}</p>
						</div> 
					</div>
					 
					
					<div class="col-md-4">
						<div class="form-group">
							<label>ID Proof*</label>
							<p>{{$application->id_proof}}</p>
							</div> 
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label>ID Number*</label>
							<p>{{$application->id_number}}</p>
							</div> 
					</div> -->
					
					<div class="col-md-4">
						<div class="form-group">
							<label>Aadhar Card Number*</label>
							<p>{{$application->adhar_card_number}}</p>
							</div> 
					</div>

					@if($application->course_id==11 || $application->course_id==26 || $application->course_id==27)
					<div class="row" id="ded">
						<div class="col-md-12">
							<h5 class="front-form-head">D.Ed. Spl.Ed. (HI/VI/ID) Fill These Fields</h5>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>AIOT Score<span class="text-danger">*</span></label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<p>{{$application->aiot_score}}</p>
							</div>
						</div> 

						<div class="col-md-2">
							<div class="form-group">
								<label>AIOT Rank<span class="text-danger">*</span></label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<p>{{$application->aiot_rank}}</p>
							</div>
						</div> 
						<div class="col-md-2">
							<div class="form-group">
								<label>AIOT Score Card<span class="text-danger">*</span></label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<a href="{{$application->aiot_score_card}}" target="_blank" rel="noopener noreferrer"><img style="width: 100px;border:1px solid;" src="{{$application->aiot_score_card}}" alt="Download Here"></a>
							</div>
						</div> 

					</div>
					@endif


					@if($application->course_id==41 || $application->course_id==42 || $application->course_id==43 || $application->course_id==44 || $application->course_id==45)

					<div class="col-md-12">
						<h5 class="front-form-head">Admission Applying Through</h5>
					</div>
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Process</label> 
							<p>{{$application->admission_through}}</p>
						</div> 
					</div>

					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Date Of Examination</label> 
							<p>{{$application->date_of_examination}}</p>
						</div> 
					</div>


					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Roll Number</label> 
							<p>{{$application->roll_number}}</p>
						</div> 
					</div>
					
					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Score</label> 
							<p>{{$application->score}}</p>
						</div> 
					</div>

					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Merit</label> 
							<p>{{$application->merit}}</p>
						</div> 
					</div>

					<div class="col-md-3 mb-2">
						<div class="form-group">
							<label>Rank</label> 
							<p>{{$application->rank}}</p>
						</div> 
					</div>
					@endif
					@include('frontend.index.application-form.view-address')

                </div>

				<div class="row">
                    <div class="col-md-12">

					<div class="table-responsive">
					@include('frontend.index.application-form.view-education-qualification')
					</div>

					<div class="table-responsive">
     				<table class="table table-bordered uploadtable">
						<tr>
							<td colspan="2"><b>Upload Photo, Sign & Documents</b>
							<br/>
							<span class="text-danger f-12 normal-line-height">(Uploaded doc should not be more than 500KB and only JPG, PNG format accepted)</span>
						</td>
						</tr>
						<tr>
							<td>Upload Your Photo*</td>
							<td>
								<a href="{{$application->photo_url}}" target="_blank"><img src="{{$application->photo_url}}" width="50px"  ></a>
								<div class="invalid-feedback text-danger upload_photo_application"></div>
							</td>
						</tr>
					   <tr>
						   <td>Upload Your Signature*</td>
							<td>
								<a href="{{$application->signature_url}}" target="_blank"><img src="{{$application->signature_url}}" width="50px"  ></a>
							</td>
					   </tr>
					   <!-- <tr>
							<td>Upload Your Adhar*</td>
							<td>
								@if($application->aadharcards_url)
								<a href="{{$application->aadharcards_url}}" class="btn-sm btn-success" target="_blank">Download</a>
								@endif
							</td>
						</tr> -->
						<tr>
							<td>Caste Certificate (Excluding General)</td>
							<td>
								@if($application->caste_certificate_url)
								<a href="{{$application->caste_certificate_url}}" class="btn-sm btn-success" target="_blank">Download</a>
								@endif
							</td>
						</tr>
						<tr>
							<td>Disability Certificate</td>
							<td>
								@if($application->disability_certificate_url)
								<a href="{{$application->disability_certificate_url}}" class="btn-sm btn-success" target="_blank">Download</a>
								@endif
							</td>
						</tr>
						<tr>
							<td>Income Certificate (EWS)</td>
							<td>
								@if($application->income_certificate_url)
								<a href="{{$application->income_certificate_url}}" class="btn-sm btn-success" target="_blank">Download</a>
								@endif
							</td>
						</tr>
						<tr>
							<td>Any Other</td>
							<td>
								@if($application->any_other_url)
								<a href="{{$application->any_other_url}}" class="btn-sm btn-success" target="_blank">Download</a>
								@endif
							</td>
						</tr>
						
					</table>
						
					</div>

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
							   <div class="col-md-6">
								   <ol class="pl-3">
									   <li class="f-14">Online (Credit card/Debit card/Net Banking )</li>
									   <li class="f-14">Offline ( to Pay Offline the details given below)</li>
								   </ol>
							   </div>
							   <div class="col-md-6" style="display:none;">
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
					
					
				   <div class="print_hide">
										


										<div class="row hidden">
											<div class="col-md-6">
												<div class="input-field s12">
													{!! app('captcha')->display() !!}
												</div>
												<div class="invalid-feedback text-danger g-recaptcha-response_application"></div>
											</div>
										</div>
										<br />
										<button type="button" class="btn btn-default mr-3"><i class="fa fa-undo" aria-hidden="true"></i> Reset</button>

										<button type="submit" class="btn btn-warning">
											<i class="fa fa-send" aria-hidden="true"></i> Submit
										</button>
										<a class="btn btn-info" onClick="window.print();"><i class="fa fa-print"></i> Download and print the application form</a>
									</div>
					
					
           			
					
					
					
                    </div>
                </div>
					
                </div>
    
                           
            
   


           
			</form>
            </div>
        </div>
    </section>
    <!--SECTION END-->
	<div id="application-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-l modal-success">
		<div class="modal-content modal-filled bg-success">
			<div class="modal-body p-4">
				<div class="text-center">
					<i class="dripicons-checkmark h1 text-white"></i>
					<h4 class="mt-2 text-white" style="color:white;">Well Done!</h4>
					<p class="mt-3 text-white" style="color:white;">Application Submitted Successfully.</p>
					<a id="more_courses" class="btn btn-info my-2">Click For The Apply More Courses</a>
					<a id="payment_url" class="btn btn-info my-2">Click For The Payment</a>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<div id="application-alert-modal-false" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-l modal-fail">
		<div class="modal-content modal-filled bg-fail">
			<div class="modal-body p-4">
				<div class="text-center">
					<i class="dripicons-checkmark h1 text-white"></i>
					<h4 class="mt-2 text-white" style="color:white;">Your Application Already Submitted For Selected Course </h4>
					<a id="dashboard" class="btn btn-info my-2">Click Here To Go To Dashboard</a>

				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
	
	


@section('scripts')
<script>
$(document).ready(function() {
	$("#hidelateral_entry").hide();
	$('#myform_application').submit(function(e) {
		e.preventDefault();
		$('.error_application').text("").css({
			'display': 'none'
		});
		var formData = new FormData(this);
		$('.invalid-feedback').text('');
		$.ajax({
			headers: {
				'X-CSRF-Token': $('meta[name=_token]').attr('content')
			},
			type: 'POST',
			url: "{{ route('add-application-form') }}",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				grecaptcha.reset();
				console.log(data);
				if (data.status == true) {
					$('#application-alert-modal').addClass('show');
					$('#application-alert-modal').addClass('in');
					$('#more_courses').attr('href',"{{route('add-application-form')}}?application_id="+data.application_id);
					$('#payment_url').attr('href',"{{route('pay-now')}}?id="+data.application_id);
				}else if(data.status == false){
					$('#application-alert-modal-false').addClass('show');
					$('#application-alert-modal-false').addClass('in');
					$('#dashboard').attr('href',"{{route('user-dashboard')}}");

						
				} 
				else {
					var first_error = '';
					var counter = 0;
					var errors = "";
					$.each(data, function(index, val) {

						if ($('.' + index + '_application').parent().length > 0) {

							++counter;
							if (counter == 1) {
								first_error = index + '_application';
							}
							$('.' + index + '_application').text(val).css({
								'display': 'block'
							});
						} else {
							errors += val + " <br/>";
						}
					});
					if (errors) {
						$('.error_application').html(errors).css({
							'display': 'block'
						});
					}

					if (first_error == '' & errors != "") {
						first_error = 'error_application';
					}

					if ($("." + first_error).parent().length > 0) {

						$('html, body').animate({
							scrollTop: $("." + first_error).parent().offset().top - 100
						}, 2000);
					}
				}
			},
			error: function(request, status, error) {
				$('.error_application').text(error).css({
					'display': 'block'
				});
			}
		});
	});
$('#course_type').change(function() {
			var affiliated = $('#affiliated_collage').val();
			var course_type = $('#course_type').val();
			$("#course_id").find('option').remove().end();
			var formData = {
				affiliated:affiliated,
				campus_id:{{$application->campus->id}},
				course_type: course_type,
				"_token": "{{ csrf_token() }}"
			}; //Array 
			$.ajax({
				url: "{{url('get-programm-type')}}",
				type: "POST",
				data: formData,
				success: function(data, textStatus, jqXHR) {
					$('#course_id').html(data);
				},
			});
		});
});

function btech(value){
			if(value==41){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();

			}
			else if(value==42){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();

			}
			else if(value==43){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();

			}else if(value==44){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();
			}
			else if(value==45){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();
			}
			else if(value==11){
				$("#ded").show();
			}
			else if(value==26){
				$("#ded").show();
			}
			else if(value==27){
				$("#ded").show();
			}
			else{
				$("#admission_applying_through").hide();
				$("#hidelateral_entry").hide();
				$("#ded").hide();

			}
		}
</script>
@endsection



@endsection

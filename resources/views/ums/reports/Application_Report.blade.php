@extends('ums.admin.admin-meta')
@section("content")
@php
$preferance_courses = [114,113,123,118,119,117,120];
@endphp

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Application report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Incident List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                      <button class="btn btn-primary btn-sm mb-50 mb-sm-0 " data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
                      <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" onClick="window.location.reload()"><i data-feather="refresh-cw"></i>reset</button>
                      <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50"></i>remove pagination</button>
                      <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50">show sitting plan</button>
                      {{-- <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50">excel export</button> --}}
							<!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                            <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="{{Request::fullUrl()}}?multiple_application=@if(Request()->multiple_application=='true') false @else true @endif"><i data-feather="file-text"></i>Show Multiple Courses applied data</a>

                    </div>
                    
                  </div>
                  <form method="get" action="{{url('Application_Report')}}" enctype="multipart/form-data">

                <div class="customernewsection-form poreportlistview p-1">
                  <div class="row"> 
                      <div class="col-md-2">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Campus:</label>
                              <select name="type" id="type" style="border-color: #c0c0c0;" class="form-control">
                                <option value="">--Choose Campus--</option>
                                @foreach($campuses as $campus)
                                    <option value="{{$campus->id}}" @if(Request()->type==$campus->id) selected @endif >{{$campus->name}}</option>
                                    @endforeach
                                </select>
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Program:</label>
                              <select class="form-control" style="border-color: #c0c0c0;" id="program" name="program">
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                <option value="{{$program->id}}" @if(Request()->program==$program->id) selected @endif>{{$program->name}}</option>
                                @endforeach
                            </select>
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">COURSES:</label>
                              <select name="course" id="course" style="border-color: #c0c0c0;" class="form-control">
                                <option value="">--Choose Course--</option>
                                    @foreach($courses as $course)
                                    <option value="{{$course->id}}" @if(Request()->course==$course->id) selected @endif >{{$course->name}}</option>
                                    @endforeach
                            </select>                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Academic Session:</label>
                              <select name="academic_session" style="border-color: #c0c0c0;" class="form-control">
                                <option value="2024-2025" @if($academic_session=='2024-2025') selected @endif>2024-2025</option>
                                <option value="2023-2024" @if($academic_session=='2023-2024') selected @endif>2023-2024</option>
                                <option value="2022-2023" @if($academic_session=='2022-2023') selected @endif>2022-2023</option>
                            </select>
                          </div>
                      </div> 
                      <div class="col-md-3">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">From Date:</label>
                              <input type="date" name="form_date" id="form_date" style="border-color: #c0c0c0;" class="form-control" value="@if(Request()->form_date){{date('d-m-Y',strtotime(Request()->form_date))}} @endif">
                            </div>
                      </div>
                      <div class="col-md-2">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">To Date:</label>
                              <input type="date" name="to_date" id="to_date" style="border-color: #c0c0c0;" class="form-control" value="@if(Request()->to_date){{date('d-m-Y',strtotime(Request()->to_date))}} @endif">
                            </div>
                      </div>
                      <div class="col-md-3">
                          <!-- Add buttons aligned in the same row -->
                          <div class="d-flex gap-1 mt-2">
                              <button class="btn btn-primary btn-sm" type="submit" name="submit_form">
                                  Get Report
                              </button>
                              <button class="btn btn-warning btn-sm" href="#">
                                  Remove Image
                              </button>
                          </div>
                      </div>
                  </div>
                </form>
              </div>
            </div>
            
            <div class="content-body">
                 
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">

                                        @php $loop_max = 6; @endphp
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            <thead style="border: 1px solid #000;">
                                                <tr style="border: 1px solid #000;">
                                                    @if(Request()->sitting=='true')
                                                    <th style="border: 1px solid #000;" class="text-left">Sr.No</th>
                                                    {{--<th style="border: 1px solid #000;" class="text-left">Application No</th> --}}
                                                    <th style="border: 1px solid #000;" class="text-left">Campuse</th>
                                                    <th style="border: 1px solid #000;" class="text-left">Entrance Roll Number</th>
                                                    <th style="border: 1px solid #000;" class="text-left">Course</th>
                                                    <th style="border: 1px solid #000;" class="text-left">Course Code</th>
                                                    <th style="border: 1px solid #000;" class="text-left">Student Name</th>
                                                    <th style="border: 1px solid #000;" class="text-left">Student 's Mobile Number</th>
                                                    <th style="border: 1px solid #000;" class="text-left">Father Name</th>
                                                    <!-- <th style="border: 1px solid #000;" class="text-left">Father's Mobile Number</th>
                                                    <th style="border: 1px solid #000;" class="text-left">Mother Name</th>
                                                    <th style="border: 1px solid #000;" class="text-left">Disability</th>  -->
                                                    <th style="border: 1px solid #000;" class="text-left">Disability Category</th>
                                                    <th style="border: 1px solid #000;" class="text-left photoClass">Photo</th>
                                                    @else
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Sr.No</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Application No</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Entrance Roll Number</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Application Date</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Academic Session</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Campuse</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Course</th>
                                                    @if(Request()->course && Request()->course==94)
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Subject</th>
                                                    @elseif(Request()->course && in_array(Request()->course,$preferance_courses))
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Lateral Entry</th>
                                                    <th style="border: 1px solid #000;" colspan="7" class="text-center">Only For B.Tech Course (if Lateral Entry is NO)</th> 
                                                    @endif
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Name</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Adhar No</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">DOB</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Email</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Contact</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Gender</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Category</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Cast Certificate Number</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">DSMNRU Student?</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Enrollment Number<br/>(if DSMNRU student)</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Father Name</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Father's Mobile Number</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Mother Name</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Mother's Mobile Number</th>
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Religion</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Nationality</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Domicile</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Marital Status</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Disability</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Disability Category</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Percentage of Disability</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Disability UDID Number</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Blood Group</th> 
            
                                                    @for($i=1;$i<=$loop_max;$i++)
                                                    <th style="border: 1px solid #000;" colspan="11" class="text-center">Educational Qualification(s) {{$i}}</th>
                                                    @endfor
            
                                                    @if(Request()->course && in_array(Request()->course,$preferance_courses))
                                                    <th style="border: 1px solid #000;" colspan="{{$loop_max}}" class="text-center">Course Preferences</th> 
                                                    @endif
            
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Permanent Address</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Correspondence Address</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Dsmnru Employee</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">DSMNRU Designation</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Dsmnru Employee Ward</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">DSMNRU Employee Name</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">DSMNRU Employee Relation</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Freedom Fighter</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">NCC (C-Certificate)</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">NSS (240 hrs and 1 camp)</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Sports</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Sport Level</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Hostel Facility</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">How many years staying in DSMNRU Hostel</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Distance from your residence to University campus</th> 
                                                    <th style="border: 1px solid #000;min-width:120px;" rowspan="2" class="text-left">Payment Date</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Payment Amount</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Payment Transaction Number</th> 
                                                    <th style="border: 1px solid #000;" rowspan="2" class="text-left">Action</th> 
                                                </tr>
                                                <tr>
                                                    @if(Request()->course && in_array(Request()->course,$preferance_courses))
                                                    <th style="border: 1px solid #000;" class="text-left">Selected Process</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Name of Exam</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Appeared or Passed</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Date of Examination</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Roll Number</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Score</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Rank</th> 
                                                    @endif
            
            
                                                    @for($i=1;$i<=$loop_max;$i++)
                                                    <th style="border: 1px solid #000;" class="text-left">Name of Exam</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Degree Name</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Board</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Passing Status</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Passing Year</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Mark Type</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Total Marks / CGPA</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Marks/CGPA Obtained</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Equivalent Percentage</th>
                                                    <th style="border: 1px solid #000;" class="text-left">Subject</th> 
                                                    <th style="border: 1px solid #000;" class="text-left">Roll Number</th> 
                                                    @endfor
            
                                                    @if(Request()->course && in_array(Request()->course,$preferance_courses))
                                                        @for($i=1;$i<=$loop_max;$i++)
                                                        <th style="border: 1px solid #000;" class="text-center">Preference {{$i}}</th> 
                                                        @endfor
                                                    @endif
            
                                                </tr>
                                                @endif
                                            </thead>
                                            @if(count($Application_sort) > 0)
                                            @php $serial_no = ((($current_page - 1) * $per_page) + 1); @endphp
                                            @foreach( $Application_sort as $index => $app)
            
                                            <tbody style="border: 1px solid #000;">
                                                <tr style="border: 1px solid #000;">  
                                                    <td style="border: 1px solid #000;">{{$serial_no++}}</td>
                                                    @if(Request()->sitting=='true')
                                                    {{--<td style="border: 1px solid #000;">{{$app->application_no}}
            
                                                        @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->role == '1' || Auth::guard('admin')->user()->role == '3'))
                                                        <a href="{{url('user-secret-login',$app->user_id)}}?application_id={{$app->id}}" target="_blank" title="Admin Login" class="btn-sm btn-dark"> <i class="fa fa-home"></i> </a>
                                                        @endif
                                                    </td>--}}
                                                    <td style="border: 1px solid #000;">{{$app->entrance_roll_number}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->campus->name}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->course->name}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->course->roll_number}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->full_name}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->mobile}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->father_first_name}}</td>
                                                    {{--<td style="border: 1px solid #000;">{{$app->father_mobile}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->mother_first_name}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->disability)}}</td>--}}
                                                    <td style="border: 1px solid #000;">{{$app->disability_category ? $app->disability_category : 'NA'}}</td>
                                                    <td style="border: 1px solid #000;" class="photoClass"><img src="{{asset($app->photo_url_user)}}" style="height: 180px;width: 150px;max-width: 150px;" /></td>
                                                    @else
                                                    <td style="border: 1px solid #000;">{{$app->application_no}}
            
                                                        {{-- @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->role == '1' || Auth::guard('admin')->user()->role == '3')) --}}
                                                        <a href="{{url('user-secret-login',$app->user_id)}}?application_id={{$app->id}}" target="_blank" title="Admin Login" class="btn-sm btn-dark"> <i data-feather="home" ></i> </a>
                                                        {{-- @endif --}}
                                                    </td>
                                                    <td style="border: 1px solid #000;">{{$app->entrance_roll_number}}</td>
                                                    <td style="border: 1px solid #000;">{{date('d-m-Y',strtotime($app->created_at))}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->academic_session}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->campus->name}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->course->name}}</td>
                                                    @if(Request()->course && Request()->course==94)
                                                    <td style="border: 1px solid #000;">{{($app->phdSubject)?$app->phdSubject->name:'-'}}</td>
                                                    @elseif(Request()->course && in_array(Request()->course,$preferance_courses))
                                                    <td style="border: 1px solid #000;">
                                                    {{($app->lateral_entry)?strtoupper($app->lateral_entry):'N/A'}}
                                                    </td>
                                                    <td style="border: 1px solid #000;">{{($app->admission_through)?$app->admission_through:'-'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->admission_through_exam_name)?$app->admission_through_exam_name:'-'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->appeared_or_passed)?$app->appeared_or_passed:'-'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->date_of_examination)?date('d-m-Y',strtotime($app->date_of_examination)):'-'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->roll_number)?strtoupper($app->roll_number):'-'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->score)?$app->score:'-'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->rank)?$app->rank:'-'}}</td>
                                                    @endif
            
                                                    <td style="border: 1px solid #000;">{{$app->first_Name}} {{$app->middle_Name}} {{$app->last_Name}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->adhar_card_number}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->date_of_birth}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->email}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->mobile}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->gender)}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->category}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->certificate_number}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->dsmnru_student}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->enrollment_number)?$app->enrollment_number:'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->father_first_name}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->father_mobile}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->mother_first_name}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->mother_mobile}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->religion}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->nationality}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->domicile}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->marital_status)}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->disability)}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->disability_category)?$app->disability_category:'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->percentage_of_disability)?$app->percentage_of_disability:'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->udid_number)?strtoupper($app->udid_number):'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{$app->blood_group}}</td>
            
                                                    @foreach($app->getAllEducations as $education)
                                                    <td style="border: 1px solid #000;">{{strtoupper($education->name_of_exam)}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($education->degree_name)}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($education->board)}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($education->passing_status_text)}}</td>
                                                    <td style="border: 1px solid #000;">{{$education->passing_year}}</td>
                                                    <td style="border: 1px solid #000;">{{($education->cgpa_or_marks==1)?'MARKS':'CGPA'}}</td>
                                                    <td style="border: 1px solid #000;">{{$education->total_marks_cgpa}}</td>
                                                    <td style="border: 1px solid #000;">{{$education->cgpa_optain_marks}}</td>
                                                    <td style="border: 1px solid #000;">{{$education->equivalent_percentage}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($education->subject)}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($education->certificate_number)}}</td>
                                                    @endforeach
                                                    @php $loop_count = ($loop_max-$app->getAllEducations->count()); @endphp
            
                                                    @for($i=1;$i<=$loop_count;$i++)
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    <td style="border: 1px solid #000;">-</td>
                                                    @endfor
            
                                                    <!-- Course Preference -->
                                                    @if(Request()->course && in_array(Request()->course,$preferance_courses))
                                                        @for($i=1;$i<=$loop_max;$i++)
                                                        @php $index_course = ($i-1); @endphp
                                                        <td style="border: 1px solid #000;">
                                                            @if($app->ifCoursePreferenceRequired() && count($app->course_preference_list()) > 0 && isset($app->course_preference_list()[$index_course]))
                                                            {{$app->course_preference_list()[$index_course]->name}}
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                        @endfor
                                                    @endif
            
            
                                                    <td style="border: 1px solid #000;">
                                                    @if($app->addressByApplicationId)
                                                        {{strtoupper($app->getFullAddress(1))}}
                                                    @endif
                                                    </td>
                                                    <td style="border: 1px solid #000;">
                                                    @if($app->addressByApplicationId)
                                                        {{strtoupper($app->getFullAddress(2))}}
                                                    @endif
                                                    </td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->dsmnru_employee)}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->dsmnru_relation)?strtoupper($app->dsmnru_relation):'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->dsmnru_employee_ward)}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->ward_emp_name)?strtoupper($app->ward_emp_name):'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->ward_emp_relation)?strtoupper($app->ward_emp_relation):'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->freedom_fighter_dependent)}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->ncc)}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->nss)}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->sports)}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->sport_level)?strtoupper($app->sport_level):'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{strtoupper($app->hostel_facility_required)}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->hostel_for_years)?$app->hostel_for_years:'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->distance_from_university)?$app->distance_from_university:'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->payment_details() && $app->payment_details()->txn_date)?date('d-m-Y',strtotime($app->payment_details()->txn_date)):'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->payment_details())?$app->payment_details()->paid_amount:'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">{{($app->payment_details())?$app->payment_details()->transaction_id:'N/A'}}</td>
                                                    <td style="border: 1px solid #000;">
                                                        {{-- @if(Auth::guard('admin')->check() && (Auth::guard('admin')->user()->role == '1' || Auth::guard('admin')->user()->role == '3')) --}}
                                                        <a href="{{url('user-secret-login',$app->user_id)}}?application_id={{$app->id}}" target="_blank" title="Admin Login" class="btn-sm btn-dark"> <i class="fa fa-home"></i> </a>
                                                        {{-- @endif --}}
                                                    </td>
            
                                                    @endif
                                                </tr>
                                            </tbody>
                                            @endforeach
                                            {{-- @else
                                                <tr>
                                                    <td style="border: 1px solid #000;" colspan="8" class="text-center">NO DATA FOUND</td>
                                                </tr> --}}
                                            @endif
                                        
                                        </table>
                                        
                                   
                                      
                                    </div>
								
                            </div>
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                    <div class="modal modal-slide-in fade" id="modals-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="add-new-record modal-content pt-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                        <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-post">Post</label>
                                        <input type="text" id="basic-icon-default-post" class="form-control dt-post" placeholder="Web Developer" aria-label="Web Developer" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-email">Email</label>
                                        <input type="text" id="basic-icon-default-email" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
                                        <small class="form-text"> You can use letters, numbers & periods </small>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                                        <input type="text" class="form-control dt-date" id="basic-icon-default-date" placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="basic-icon-default-salary">Salary</label>
                                        <input type="text" id="basic-icon-default-salary" class="form-control dt-salary" placeholder="$12000" aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                 

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a class="ml-25" href="#" target="_blank">Presence 360</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
        
        <div class="footerplogo"><img src="../../../assets/css/p-logo.png" /></div>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->
	
     
    
    <div class="modal fade" id="reallocate" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-4 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Re-Allocate Incident</h1>
					<p class="text-center">Enter the details below.</p>

					<div class="row mt-2"> 
						
						<div class="col-md-12 mb-1">
							<label class="form-label">Re-Allocate To <span class="text-danger">*</span></label>
							<select class="form-select select2">
                                <option>Select</option>
                            </select>
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">Re-Allocate Dept. <span class="text-danger">*</span></label>
							<select class="form-select select2">
                                <option>Select</option>
                            </select>
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">PDC Date <span class="text-danger">*</span></label>
							<input type="date" class="form-control" placeholder="Enter Name" />
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">Remarks <span class="text-danger">*</span></label>
							<textarea class="form-control"></textarea>
						</div>
                          
                         
				    </div>
                </div>
				
				<div class="modal-footer justify-content-center">  
						<button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
					<button type="reset" class="btn btn-primary">Re-Allocate</button>
				</div>
			</div>
		</div>
	</div>
    
      
    
	 
    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
		<div class="modal-dialog sidebar-sm">
			<form class="add-new-record modal-content pt-0"> 
				<div class="modal-header mb-1">
					<h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Select Date Range</label>
						  <input type="text" id="fp-range" class="form-control flatpickr-range" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
					</div>
					
					<div class="mb-1">
						<label class="form-label">Select Incident No.</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Select Customer</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Assigned To</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Status</label>
						<select class="form-select">
							<option>Select</option> 
							<option>Open</option>
							<option>Close</option>
							<option>Re-Allocatted</option>
						</select>
					</div> 
					 
				</div>
				<div class="modal-footer justify-content-start">
					<button type="button" class="btn btn-primary data-submit mr-1">Apply</button>
					<button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
<!-- Include jQuery via CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function exportdata() {
             var fullUrl_count = "{{count(explode('?',urldecode(url()->full())))}}";
             var fullUrl = "{{url()->full()}}";
             if(fullUrl_count>1){
                 fullUrl = fullUrl.split('?')[1];
                 fullUrl = fullUrl.replace(/&amp;/g, '&');
                 fullUrl = '?'+fullUrl;
            }else{
                fullUrl = '';
            }
            var url = "{{url('admin/master/campus/campus-export')}}"+fullUrl;
            window.location.href = url;
        }
        function editCourse(slug) {
            var url = "{{url('admin/master/campus/edit-campus')}}"+"/"+slug;
            window.location.href = url;
        }
        function deleteCourse(slug) {
            var url = "{{url('admin/master/campus/delete-model-trim')}}"+"/"+slug;
            window.location.href = url;
        }
        $(document).ready(function() {
        $('.alphaOnly').keyup(function() {
                this.value = this.value.replace(/[^a-z|^A-Z\.]/g, '');
            });
        
            $('#dd').on('click', function(e){
    
                var html = "<html><head><meta charset='utf-8' /></head><body>" + document.getElementsByTagName("table")[0].outerHTML + "</body></html>";
                var blob = new Blob([html], { type: "application/vnd.ms-excel" });
                var a = document.getElementById("dd");
                // Use URL.createObjectURL() method to generate the Blob URL for the a tag.
                a.href = URL.createObjectURL(blob);
    
                var selmonth = $("#month option:selected").text();
                var selyear = $("#year option:selected").text();
                var show_agreement_type = $("#agreement_type option:selected").text();
                $('.show_agreement_type').text(show_agreement_type);
                
                // Set the download file name.
                a.download = "Application_Report.xls";
            });
        });
    
        $(document).ready(function(){
            $('#program').change(function() {
                    var course_type = $('#program').val();
                    var campuse_id = $('#type').val();
                // console.log('campuse id>>>>>>>>>>>',course_type);
                //  $("#course").find('option').remove().end();
                    var formData = {
                        program: course_type,
                        campuse_id: campuse_id,
                        "_token": "{{ csrf_token() }}"
                    }; //Array 
                    $.ajax({
<<<<<<< HEAD
                        url: "{{route('course_list')}}",
=======
                        url: "{{route('get-courses')}}",
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
                        type: "POST",
                        data: formData,
                        success: function(data, textStatus, jqXHR) {
                            $('#course').html(data);
                            console.log(data);
                        },
                    });
            });
    
            $('#filter_program').change(function() {
                    var course_type = $('#filter_program').val();
                    var campuse_id = $('#campus').val();
                // console.log('campuse id>>>>>>>>>>>',course_type);
                //  $("#course").find('option').remove().end();
                    var formData = {
                        program: course_type,
                        campuse_id: campuse_id,
                        "_token": "{{ csrf_token() }}"
                    }; //Array 
                    $.ajax({
<<<<<<< HEAD
                        url: "{{route('course_list')}}",
=======
                        url: "{{route('get-courses')}}",
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
                        type: "POST",
                        data: formData,
                        success: function(data, textStatus, jqXHR) {
                            $('#filter_course').html(data);
                            console.log(data);
                        },
                    });
            });
            
    
    
    });
    </script>
  @endsection
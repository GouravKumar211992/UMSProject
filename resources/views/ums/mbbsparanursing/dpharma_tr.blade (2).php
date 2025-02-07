@extends("ums.admin.admin-meta")
@section('content')
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
    
    <!-- BEGIN: Content-->

    <div class="app-content content ">
        <h4>Tabular Record (TR)</h4>

    <form method="get">
    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-sm-2" style="display: none;">

                <span style="color: black;">COURSES:</span>
                <select name="course" id="course"  class="form-control" onchange="showSemesters($(this));">
                   <!-- <option value="">--Choose Course--</option> -->
                        @foreach($courses as $course)
                         
                          <option value="{{$course->id}}" @if($course_id==$course->id) selected @endif >{{$course->name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger">{{ $errors->first('course') }}</span>
                </div>
           
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Semester <span class="text-danger">*</span></label>
                <select name="semester" id="semester"  class="form-control">
                    <option value="">--Select Semester--</option>
                    @foreach($semesters as $semester)
                    <option value="{{$semester->id}}" @if($semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                    @endforeach       
                </select>
                <span class="text-danger">{{ $errors->first('semester') }}</span>
                </div>
                
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-5">Academic Session <span class="text-danger">*</span></label>
                <select name="session" id="session" class="form-control">
                    @foreach($sessions as $session)
                    <option value="{{$session->academic_session}}" @if(Request()->session==$session->academic_session) selected @endif >{{$session->academic_session}}</option>
                    @endforeach
                 </select>
                 <span class="text-danger">{{ $errors->first('session') }}</span>   
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Form Type <span class="text-danger ">*</span></label>
                    <select name="form_type" id="form_type" class="form-control">
                        <option value="REGULAR" @if(Request()->form_type=='REGULAR') selected @endif >REGULAR</option>
                        <option value="BACK" @if(Request()->form_type=='BACK') selected @endif >BACK</option>
                     </select>
                     <span class="text-danger">{{ $errors->first('semester') }}</span>
                </div>

            </form>
                <div class="col-md-2 submit text-end">
        <button onclick="javascript: history.go(-1)" class=" btn btn-primary btn-sm mb-50 mb-sm-0r waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Submit</button>
        
        <button onclick="window.location.reload()" class="btn btn-warning btn-sm mb-50 mb-sm-0r " type="reset">
            <i data-feather="refresh-cw"></i> Reset
        </button>
    </div>

        </div>
    </div>

<!-- options section end-->

        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                {{-- <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Admin <br> <small>List of Admins</small></h2>
                            {{-- <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>  
                                    <li class="breadcrumb-item active">Incident List</li>
                                </ol>
                            </div> --}}
                        {{-- </div>
                    </div>
                </div>  --}}
                {{-- <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal">
                            <i data-feather="filter"></i> Filter
                        </button> 
                        <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                        <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="incident-add.html">
                            <i data-feather="user-plus"></i> Add Admin
                        </a>  
                        <!-- Reset Button -->
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" type="reset">
                            <i data-feather="refresh-cw"></i> Reset
                        </button>
                    </div>
                </div> --}}
                
            </div>
            <div class="content-body">
                 
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
                                    <table class="table" style="width: 100% !important;" id="example" cellpadding="0" cellspacing="0" border="1">

                                        <thead>
                                            <tr>
                                                <td style="text-align: center;padding:5px;border: none;" colspan="{{($subjects->count()*3)+5}}">
                                                    <h5><b>DR. SHAKUNTALA MISRA NATIONAL REHABILITATION UNIVERSITY, LUCKNOW<br>
                                                    FIRST YEAR  EXAMINATION RESULT, JAN-2024<br>
                                                    STATEMENT OF MARKS</b></h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;padding:5px;border-top: 0px;" colspan="{{($subjects->count()*3)+5}}">
                                                    <b>
                                                        @if($semester_details)
                                                        Name of the institution : {{$semester_details->course->campuse->name}}<br>
                                                        COURSE : {{$semester_details->course->name}}<br>
                                                        YEAR : {{$semester_details->semester_number}}
                                                        @endif
                                                    </b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td rowspan="4" style="border-top: 1px;">SN#</td>
                                                <td rowspan="4" style="border-top: 1px;">ROLL NO.</td>
                                                <td rowspan="4" style="border-top: 1px;">NAME</td>
                                                @foreach($subjects as $subject)
                                                <td colspan="3" style="border-top: 1px;text-align: center;">{{$subject->name}}</td>
                                                @endforeach
                                                <td rowspan="3" style="border-top: 1px;">Grand Total</td>
                                                <td rowspan="4" style="border-top: 1px;">RESULT</td>
                                            </tr>
                                
                                            <tr>
                                                @foreach($subjects as $subject)
                                                <td colspan="3" style="border-top: 1px;text-align: center;">{{$subject->sub_code}}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach($subjects as $subject)
                                                <td style="border-top: 1px;">INT @if(Request()->form_type=='BACK') (OLD) NEW @endif</td>
                                                <td style="border-top: 1px;">EXT @if(Request()->form_type=='BACK') (OLD) NEW @endif</td>
                                                <td style="border-top: 1px;">TOTAL</td>
                                                @endforeach
                                            </tr>
                                
                                            <tr>
                                                @foreach($subjects as $subject)
                                                <td style="border-top: 1px;">{{$subject->internal_maximum_mark}}</td>
                                                <td style="border-top: 1px;">{{$subject->maximum_mark}}</td>
                                                <td style="border-top: 1px;">{{$subject->internal_maximum_mark + $subject->maximum_mark}}</td>
                                                @endforeach
                                                <td style="border-top: 1px;">{{$subjects->sum('internal_maximum_mark') + $subjects->sum('maximum_mark')}}</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                
                                            @foreach($results as $index=>$result_single)
                                            @php
                                            $student = $result_single->student;
                                            $result_array = \App\Models\Result::select('*')
                                            ->where('roll_no',$result_single->roll_no)
                                            ->where('course_id',$result_single->course_id)
                                            ->where('semester',$result_single->semester)
                                            ->where('exam_session',$result_single->exam_session)
                                            ->where('back_status_text',$result_single->back_status_text)
                                            ->orderBy('subject_position','asc')
                                            ->get();
                                            $grand_total = 0;
                                            $result_status = 'PASSED';
                                            @endphp
                                            <tr>
                                                <td style="border-top: 1px;">{{++$index}}</td>
                                                <td style="border-top: 1px;">{{$result_single->roll_no}}</td>
                                                <td style="border-top: 1px;">{{$student->first_Name}}</td>
                                                @foreach($result_array as $result)
                                                @php
                                                $backResult = $result->backResult();
                                                $special_back_table_details = null;
                                                    if(Request()->form_type=='BACK'){
                                                        $result->form_type = 'final_back_paper';
                                                        $special_back_table_details = $result->special_back_table_details();
                                                    }
                                                @endphp
                                                <td style="border-top: 1px;">
                                                    @php $internal_marks_old = ($backResult)?$backResult->internal_marks:'-'; @endphp
                                                    @if($special_back_table_details && ($special_back_table_details->mid==1 || $special_back_table_details->ass==1 || $special_back_table_details->viva==1))
                                                    ({{$internal_marks_old}})
                                                    @endif
                                                     {{(int)$result->internal_marks}}
                                                </td>
                                                <td style="border-top: 1px;">
                                                    @php $external_marks_old = ($backResult)?$backResult->external_marks:'-'; @endphp
                                                    @if($special_back_table_details && ($special_back_table_details->external=='1' || $special_back_table_details->p_internal=='1' || $special_back_table_details->p_external=='1'))
                                                    ({{$external_marks_old}})
                                                    @endif
                                                    {{(int)$result->external_marks}}
                                                </td>
                                                @php 
                                                $papter_total = (int)$result->internal_marks + (int)$result->external_marks;
                                                $grand_total = $grand_total + $papter_total;
                                                if($result->grade_letter=='F'){
                                                    $result_status = 'FAILED';
                                                }
                                                @endphp 
                                                <td style="border-top: 1px; @if($result->grade_letter=='F') color:red; @endif ">{{$papter_total}}@if($result->grade_letter=='F') * @endif</td>
                                                @endforeach
                                                <td style="border-top: 1px;">{{$grand_total}}</td>
                                                <td style="border-top: 1px; @if($result->result=='PCP' || $result->result=='F') color:red; @endif ">{{$result->result_full}}</td>
                                            </tr>
                                            @endforeach
                                
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td style="border: none;text-align: left;" colspan="{{($subjects->count()*3)}}">
                                                    &nbsp;
                                                    <br> &nbsp;
                                                    <br> &nbsp;
                                                    <br>
                                                    <b>P=PASSED, PCP=PROMOTED WITH CARRYOVER PAPERS, A=ABSENT, LG=LETTER GRADE, QP=QUALITY POINT, SGPA=SEMESTER GRADE POINT AVERAGE, WH=WITHHELD</b>
                                                </td>
                                                <td style="border: none;text-align: center;padding:5px;" colspan="5">
                                                    &nbsp;
                                                    <br> &nbsp;
                                                    <br> &nbsp;
                                                    <br>
                                                    <b>CONTROLLER OF EXAMINATION</b>
                                                </td>
                                            </tr>
                                        </tfoot>
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

@endsection
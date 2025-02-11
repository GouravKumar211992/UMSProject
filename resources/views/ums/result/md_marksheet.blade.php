@extends('ums.admin.admin-meta')
@section('content')
    @php

        $course_name = '';
        $semester_name = '';
    @endphp

    <div class="app-content content">
            <div class="big-box d-flex justify-content-between mb-1 align-items-center">
                <div class="head">
                    <div class="content-header-left col-md-12 mb-2">
                        <div class="row d-flex align-items-center justify-content-between">
                            <div class="col-auto">
                                <h2 class="content-header-title mb-0">Md Marksheet</h2>
                            </div>
                            <div class="col-auto">
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                        <li class="breadcrumb-item">Marksheet</li>  
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form method="GET" id="form_data" action="">
                <div class="submitss text-start me-3 align-item-center">
                    <button onclick="window.location.reload()" onclick="javascript: history.go(-1)"
                        class=" mt-1 btn btn-primary btn-sm mb-50 mb-sm-0r waves-effect waves-float waves-light ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-check-circle">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg> Get Report</button>
                    <button class="btn btn-warning btn-sm mb-50 mb-sm-0r mt-1" onclick="window.location.reload();"
                        type="reset">
                        <i data-feather="refresh-cw"></i> Reset
                    </button>
                </div>
            </div>


            <div class="col-md-12 mt-2">
                <div class="row align-items-center mb-1">
                    <div class="col-md-3 d-flex align-items-center">
                        <label class="form-label mb-0 me-2 col-3">COURSES: <span class="text-danger"></span></label>
                        <select data-live-search="true" name="course_id" id="course_id" style="border-color: #c0c0c0;"
                            class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                            <option value="">--Choose Course--</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @if (Request()->course_id == $course->id) selected @endif>
                                    {{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-center">
                        <label class="form-label mb-0 me-2 col-3">SEMESTER: <span class="text-danger"></span></label>
                        <select name="semester_id" id="semester_id" style="border-color: #c0c0c0;"
                            class="form-control js-example-basic-single ">
                            <option value="">--Choose Semester--</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}" @if (Request()->semester_id == $semester->id) selected @endif>
                                    {{ $semester->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-center">
                        <label class="form-label mb-0 me-2 col-3">Batch: <span class="text-danger"></span></label>
                        <select name="batch" id="batch" style="border-color: #c0c0c0;" class="form-control">
                            <option value="">--Batch--</option>
                            @foreach(batchArray() as $batch_row)
                            <option value="{{$batch_row}}" @if(Request()->batch==$batch_row) selected @endif >{{$batch_row}}</option>
                            @endforeach
                          </select>
                        
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                        <label class="form-label mb-0 me-2 col-3">Result Type: <span class="text-danger"></span></label>
                        <select name="exam_type" id="exam_type" style="border-color: #c0c0c0;" class="form-control">
                            <option value="0" @if (Request()->exam_type == 0) selected @endif>Regular</option>
                            <option value="1" @if (Request()->exam_type == 1) selected @endif>Scrutiny</option>
                            <option value="2" @if (Request()->exam_type == 2) selected @endif>Challenge</option>
                            <option value="3" @if (Request()->exam_type == 3) selected @endif>Supplementary</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
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
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
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
        {{-- <div class="content-body">
                 
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                 <div class="table-responsive">
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            @php $count = 1;
                                            $page = 1;
                                            if(Request()->semester==32){
                                                $page = 64;
                                            }
                                            if(Request()->semester==31){
                                                $page = 65;
                                            }
                                             if(Request()->page == 2){
                                                 $count = $page+1;
                                             }
                                            @endphp
                                            @foreach ($full_retult as $index_retult => $full_retult_row)
                                            @if (Request()->roll_no)
                                            @php $examStudents = $full_retult_row->studentByGroup()->where('roll_no',Request()->roll_no); @endphp
                                            @else
                                            @php $examStudents = $full_retult_row->studentByGroup(); @endphp
                                            @endif
                                            @php $sub_count = count(explode(' ',$full_retult_row->subject)); @endphp
                                            @if ($full_retult_row && $full_retult_row->get_last_semester()->id == Request()->semester)
                                                @php $column_count = ($sub_count*4) + 8; @endphp
                                            @else
                                                @php $column_count = ($sub_count*4) + 6; @endphp
                                            @endif
                                            @php $onepagegroup = (Request()->onepagegroup)?Request()->onepagegroup:1; @endphp
                                            @php $headerVisible = ($index_retult !=0 && $index_retult%$onepagegroup!=0)?'hidden':''; @endphp
                                            @if ($index_retult > 0)
                                            <table class="table table-responsive {{$headerVisible}} @if ($headerVisible == '') page-break @endif" style="width: 100%;border-collapse: unset;">
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="{{$column_count}}" style="border: none !important;">
                                                            <br/>
                                                            <br/>
                                                            <br/>
                                                            <br/>
                                                            <br/>
                                                            <br/>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="{{$column_count/2}}" style="border: none !important;text-align: left;font-size: 15px;"><strong>P=PASSED, PCP=PROMOTED WITH CARRYOVER PAPERS, A=ABSENT, LG=LETTER GRADE, QP=QUALITY POINT, SGPA=SEMESTER GRADE POINT AVERAGE, WH=WITHHELD</strong></th>
                                                        <th colspan="{{$column_count/2}}" style="border: none !important;text-align: right;font-size: 15px;"><strong>CONTROLLER OF EXAMINATION</strong></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            @endif
                                            <br/>
                                            <table class="table table-responsive {{$headerVisible}}" style="width: 100%;border-collapse: unset;">
                                                @php
                                                $sem_text = 'SEMESTER END';
                                                @endphp
                                                <tr>
                                                    <th colspan="{{$column_count}}" style="border: none !important;font-size: 25px;font-weight: bold;">
                                                        DR. SHAKUNTALA MISRA NATIONAL REHABILITATION UNIVERSITY, LUCKNOW
                                                        <br>
                                                        {{strtoupper($sem_text)}} EXAMINATION RESULT, 
                                    
                                                        @foreach ($full_retult_row->subject_sequence as $selected_subjects)
                                                        @php
                                                        if(isset($examStudents[0])){
                                                            $examStudents_value = $examStudents[0];
                                                        }
                                                        else if(isset($examStudents[1])){
                                                            $examStudents_value = $examStudents[1];
                                                        }
                                                        else if(isset($examStudents[2])){
                                                            $examStudents_value = $examStudents[2];
                                                        }
                                                        @endphp
                                                        @if (isset($examStudents_value))
                                                            @php $result_single = $selected_subjects->getResult($examStudents_value) @endphp
                                                        @endif
                                                        @endforeach
                                    
                                                        @if ($course_single && $course_single->semester_type == 'year')
                                                            MAY-{{date('Y')}}
                                                        @elseif(isset($result_single) && $result_single)
                                                            @if ($result_single->session_name == 'DECEMBER-2023')
                                                            DEC-2023
                                                            @else
                                                            {{$result_single->session_name}}
                                                            @endif
                                                        @endif
                                    
                                    
                                                        <br>
                                                        STATEMENT OF MARKS AND GRADES
                                                        @if (Request()->form_type == 'UFM')
                                                        <br>
                                                        UFM RESULT
                                                        @endif
                                                    </th>
                                                </tr>
                                            </table>
                                            <br/>
                                            <br/>
                                            <table class="table" style="width: 100% !important;" id="example" border="1" cellpadding="0" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: left !important;" colspan="{{$column_count}}">
                                                        <!-- GRADE TYPE : @if ($grade_type) OLD @else NEW @endif -->
                                                        <!-- <br> -->
                                                        COURSE : {{$semester_details->course->name}}
                                                        <br>
                                                        SEMESTER : {{$semester_details->name}}
                                                        <br>
                                                        SUBJECT CODES : {{$full_retult_row->subject}}
                                                        @if (Request()->campus_id != 1)
                                                        <br>
                                                        @if ($campus_details)
                                                        CAMPUS : {{$campus_details->name}}
                                                        @endif
                                                        @endif
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th rowspan="3">SN#</th>
                                                    <th rowspan="3">ROLL NO.</th>
                                                    <th rowspan="3" style="min-width: 200px;text-align: left;">NAME</th>
                                                    @foreach ($full_retult_row->subject_sequence as $selected_subjects_header)
                                                    <th colspan="4" style="max-width: 300px;">{{strtoupper($selected_subjects_header->name)}}</th>
                                                    @endforeach
                                                    <th rowspan="3">QP</th>
                                                    <th rowspan="3">SGPA</th>
                                                    <th rowspan="3">RESULT</th>
                                                    @if ($full_retult_row && $full_retult_row->get_last_semester()->id == Request()->semester)
                                                    <th rowspan="3">CGPA</th>
                                                    <th rowspan="3">OVERALL RESULT</th>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    @foreach ($full_retult_row->subject_sequence as $selected_subjects_header)
                                                    <th colspan="4">{{$selected_subjects_header->sub_code}}</th>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    @foreach ($full_retult_row->subject_sequence as $selected_subjects_header)
                                    
                                                    @if ($selected_subjects_header->internal_maximum_mark > 0)
                                                    <th>INT<br>{{$selected_subjects_header->internal_maximum_mark}}</th>
                                                    @else
                                                    <th>INT<br>-</th>
                                                    @endif
                                    
                                                    @if ($selected_subjects_header->maximum_mark > 0)
                                                    <th>EXT<br>{{$selected_subjects_header->maximum_mark}}</th>
                                                    @else
                                                    <th>EXT<br>-</th>
                                                    @endif
                                    
                                                    @if ($selected_subjects_header->internal_maximum_mark == 0 && $selected_subjects_header->maximum_mark == 0)
                                                    <th>TOTAL<br>-</th>
                                                    @else
                                                    <th>TOTAL<br>{{$selected_subjects_header->total_marks}}</th>
                                                    @endif
                                    
                                                    <th>LG</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    @foreach ($examStudents as $examStudent)
                                                        @php
                                                            $qp = '';
                                                            $sgpa = '';
                                                            $result_text = '';
                                                            $serial_no = (Request()->page_index++);
                                                            $from_serial_no = (Request()->from_serial_no)?Request()->from_serial_no:1;
                                                            $to_serial_no = (Request()->to_serial_no)?Request()->to_serial_no:1000;
                                                        @endphp
                                                        @if ($serial_no >= $from_serial_no && $serial_no <= $to_serial_no)
                                                        <tr>
                                                            <td>{{$serial_no}}</td>
                                                            <td>{{$examStudent->roll_no}}</td>
                                                            <td class="text-left" style="text-align: left;">{{@$examStudent->students->full_name}}</td>
                                                            @foreach ($full_retult_row->subject_sequence as $selected_subjects)
                                                            @php $result = $selected_subjects->getResult($examStudent) @endphp
                                                            @if ($result)
                                                                @php $qp = $result->qp; @endphp
                                                                @php $sgpa = $result->sgpa; @endphp
                                                                @php $result_text = $result->result @endphp
                                    
                                                                @if ($result->subject->internal_maximum_mark > 0)
                                                                <td>{{$result->internal_marks}}</td>
                                                                @else
                                                                <td>-</td>
                                                                @endif
                                    
                                                                @if ($result->subject->maximum_mark > 0)
                                                                <td>{{$result->external_marks}}</td>
                                                                @else
                                                                <td>-</td>
                                                                @endif
                                    
                                                                @if ($result->max_total_marks == 0)
                                                                <td>-</td>
                                                                <td>-</td>
                                                                @else
                                                                <td>{{$result->total_marks}}</td>
                                                                <td>{{$result->grade_letter}}</td>
                                                                @endif
                                    
                                                            @else
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                            @endif
                                                            @endforeach
                                                            <td>{{$qp}}</td>
                                                            <td>{{number_format((float)$sgpa, 2, '.', '')}}</td>
                                                            <td>{{$result_text}}</td>
                                                            @if (isset($result) && $result && $result->get_last_semester()->id == Request()->semester)
                                                                <td>{{number_format((float)$result->cgpa, 2, '.', '')}}</td>
                                                                @if ($result->result_overall)
                                                                    <td style="text-align: center;">{{$result->result_overall}} {{($result->semester_by_number)?'IN '.$result->semester_by_number->name:''}}</td>
                                                                @else
                                                                    <td>PASS</td>
                                                                @endif
                                                            @endif
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if (Request()->semester == 71)
                                            <strong>* MC-106 (RESEARCH PROJECT) to be evaluvated in 2nd semester</strong>
                                            @endif
                                            @if (count($full_retult) == $index_retult + 1)
                                            <br/>
                                            <br/>
                                            <br/>
                                            <table class="table table-responsive" style="width: 100%;border-collapse: unset;">
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="{{$column_count}}" style="border: none !important;">
                                                            <br/>
                                                            <br/>
                                                            <br/>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="{{$column_count/2}}" style="border: none !important;text-align: left;font-size: 15px;"><strong>P=PASSED, PCP=PROMOTED WITH CARRYOVER PAPERS, A=ABSENT, LG=LETTER GRADE, QP=QUALITY POINT, SGPA=SEMESTER GRADE POINT AVERAGE, WH=WITHHELD</strong></th>
                                                        <th colspan="{{$column_count/2}}" style="border: none !important;text-align: right;font-size: 15px;"><strong>CONTROLLER OF EXAMINATION</strong></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            @endif
                                        @endforeach
                                    </section>
                                    
                                             
                                    </div>
                                    @if ($download != 'pdf')
                                        @if (count($full_retult) > 0)
                                            {{$full_retult->appends(Request()->all())->links('partials.pagination')}}
                                        @endif
                                    @endif
                                          
                                        </table>
                                        
                                    </div>
								
                            </div> 

                            
                        </div>
                    </div> --}}
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
                            <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname"
                                placeholder="John Doe" aria-label="John Doe" />
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="basic-icon-default-post">Post</label>
                            <input type="text" id="basic-icon-default-post" class="form-control dt-post"
                                placeholder="Web Developer" aria-label="Web Developer" />
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="basic-icon-default-email">Email</label>
                            <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
                            <small class="form-text"> You can use letters, numbers & periods </small>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                            <input type="text" class="form-control dt-date" id="basic-icon-default-date"
                                placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="basic-icon-default-salary">Salary</label>
                            <input type="text" id="basic-icon-default-salary" class="form-control dt-salary"
                                placeholder="$12000" aria-label="$12000" />
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
    {{-- <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a class="ml-25" href="#" target="_blank">Presence 360</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
        
        <div class="footerplogo"><img src="../../../assets/css/p-logo.png" /></div>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button> --}}
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
                        <input type="text" id="fp-range" class="form-control flatpickr-range"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" />
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

    {{-- @include('footer'); --}}

    <!-- BEGIN: Vendor JS-->
    <!-- BEGIN: Vendor JS-->
    {{-- <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="../../../app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
	<script src="../../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS--> 
    <script src="../../../app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
	<script src="../../../app-assets/js/scripts/forms/form-select2.js"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
		$(function () { 

  var dt_basic_table = $('.datatables-basic'),
    dt_date_table = $('.dt-date'),
    dt_complex_header_table = $('.dt-complex-header'),
    dt_row_grouping_table = $('.dt-row-grouping'),
    dt_multilingual_table = $('.dt-multilingual'),
    assetPath = '../../../app-assets/';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  // DataTable with buttons
  // --------------------------------------------------------------------

  if (dt_basic_table.length) {
    var dt_basic = dt_basic_table.DataTable({
      
      order: [[0, 'asc']],
      dom: 
        '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle',
          text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
          buttons: [
            {
              extend: 'print',
              text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'csv',
              text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'excel',
              text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'pdf',
              text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'copy',
              text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            }
          ],
          init: function (api, node, config) {
            $(node).removeClass('btn-secondary');
            $(node).parent().removeClass('btn-group');
            setTimeout(function () {
              $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
            }, 50);
          }
        },
         
      ],
      
      language: {
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      }
    });
    $('div.head-label').html('<h6 class="mb-0">Event List</h6>');
  }

  // Flat Date picker
  if (dt_date_table.length) {
    dt_date_table.flatpickr({
      monthSelectorType: 'static',
      dateFormat: 'm/d/Y'
    });
  }

  // Add New record
  // ? Remove/Update this code as per your requirements ?
  var count = 101;
  $('.data-submit').on('click', function () {
    var $new_name = $('.add-new-record .dt-full-name').val(),
      $new_post = $('.add-new-record .dt-post').val(),
      $new_email = $('.add-new-record .dt-email').val(),
      $new_date = $('.add-new-record .dt-date').val(),
      $new_salary = $('.add-new-record .dt-salary').val();

    if ($new_name != '') {
      dt_basic.row
        .add({
          responsive_id: null,
          id: count,
          full_name: $new_name,
          post: $new_post,
          email: $new_email,
          start_date: $new_date,
          salary: '$' + $new_salary,
          status: 5
        })
        .draw();
      count++;
      $('.modal').modal('hide');
    }
  });

  // Delete Record
  $('.datatables-basic tbody').on('click', '.delete-record', function () {
    dt_basic.row($(this).parents('tr')).remove().draw();
  });
	
	 
 
});
		
		 
		
		
    </script>
</body>
<!-- END: Body-->

</html> --}}
@endsection

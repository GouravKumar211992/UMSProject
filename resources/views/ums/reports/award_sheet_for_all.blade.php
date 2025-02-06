@extends('ums.admin.admin-meta')
@section("content")

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

    <!-- BEGIN: Header-->
      
     
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="app-content content ">
        <form method="get" id="form_data">

    <div class="submitss text-end me-3">
        <button onclick="javascript: history.go(-1)" class=" btn btn-primary btn-sm mb-50 mb-sm-0r waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Create</button>
        <button class="btn btn-warning btn-sm mb-50 mb-sm-0r " type="reset">
            <i data-feather="refresh-cw"></i> Reset
        </button>
    </div>


    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger ">*</span></label>
                <select name="campus_id" id="campus_id" style="border-color: #c0c0c0;" class="form-control" onChange="return $('#form_data').submit();">
                    <option value="">--Choose Campus--</option>
                    @foreach($campuses as $campus)
                    <option value="{{$campus->id}}" @if(Request()->campus_id==$campus->id) selected @endif >{{$campus->name}}</option>
                    @endforeach
                    </select>
                       </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Coures <span class="text-danger">*</span></label>
                <select name="course_id" id="course_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                    <option value="">--Choose Course--</option>
                        @foreach($courses as $course)
                        <option value="{{$course->id}}" @if(Request()->course_id==$course->id) selected @endif >{{$course->name}}</option>
                        @endforeach
                    </select>
                </div>
                
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Semester <span class="text-danger">*</span></label>
                <select name="semester_id" id="semester_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single" onChange="return $('#form_data').submit();">
                    <option value="">--Select Semester--</option>
                    @foreach($semesters as $semester)
                        <option value="{{$semester->id}}" @if(Request()->semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                    @endforeach
                </select>
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Sub Code <span class="text-danger">*</span></label>
				<select name="sub_code" id="sub_code" style="border-color: #c0c0c0;" class="form-control js-example-basic-single">
                        <option value="">--Select Sub Code--</option>
                        @foreach($subjects as $subject)
                            <option value="{{$subject->sub_code}}" @if(Request()->sub_code==$subject->sub_code) selected @endif >{{$subject->sub_code}}</option>
                        @endforeach
                    </select>   
                </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Academic Session <span class="text-danger ">*</span></label>
                <select name="academic_session" id="academic_session" style="border-color: #c0c0c0;" class="form-control js-example-basic-single">
                    <option value="">--Select Academic Session--</option>
                    @foreach($academic_sessions as $academic_session)
                        <option value="{{$academic_session->academic_session}}" @if(Request()->academic_session==$academic_session->academic_session) selected @endif >{{$academic_session->academic_session}}</option>
                    @endforeach
                </select>  
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Exam Type <span class="text-danger">*</span></label>
                <select name="exam_type" id="exam_type" style="border-color: #c0c0c0;" class="form-control js-example-basic-single">
                    <option value="">--Select Exam Type--</option>
                    @foreach($exam_types as $exam_type)
                        <option value="{{$exam_type->exam_type}}" @if(Request()->exam_type==$exam_type->exam_type) selected @endif >{{$exam_type->exam_type}}</option>
                    @endforeach
                </select> 
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Batch<span class="text-danger">*</span></label>
                <select name="exam_type" id="exam_type" style="border-color: #c0c0c0;" class="form-control js-example-basic-single">
                    <option value="">--Select Batch Type--</option>
                    @foreach($batch as $batchValue)
                    <option value="{{ $batchValue }}">{{ $batchValue }}</option>
                @endforeach
            
                </select> 
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <button type="submit" class="btn btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter" name="submit_form" data-bs-toggle="modal"> Get Report</button> 

            </div>
        </div>
    </div>
</form>


<!-- options section end-->

<div class="content-overlay"></div>
<div class="header-navbar-shadow"></div>
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
    </div>
    <div class="content-body">
        <section id="">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive bg-white">
                            <table class="table table-bordered admintable dataTable no-footer" id="myTable" aria-describedby="myTable_info" style="width: 1040px;">
                                <thead>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Campus Name
                                        </th><th colspan="7" rowspan="1">
                                            {{@$selected_campus->name}}
                                        </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Course
                                        </th><th colspan="7" rowspan="1">
                                            {{@$selected_course->name}}
                                        </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Semester
                                        </th><th colspan="7" rowspan="1">
                                            {{@$selected_semester->name}}
                                        </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Paper Code (Name)
                                        </th><th colspan="7" rowspan="1">
                                            @php $subject_name = ''; @endphp
                                            @foreach($subjects as $subject)
                                            @if(Request()->sub_code==$subject->sub_code) 
                                            @php $subject_name = $subject->sub_code.'('.$subject->name.')'; @endphp
                                            @endif 
                                            @endforeach
                                            {{$subject_name}}                                                                  
                                        </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Maximum Marks
                                        </th><th colspan="7" rowspan="1"></th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Exam Type
                                        </th><th colspan="7" rowspan="1">
                                            @foreach($exam_types as $exam_type)
                                            @if(Request()->exam_type==$exam_type->exam_type) {{$exam_type->result_exam_type}} @endif 
                                            @endforeach
                                             </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Date
                                        </th><th colspan="7" rowspan="1">
                                            {{date('d-m-Y h:i:sa')}}
                                        </th>
                                    </tr>
									<tr class="">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 65px;" aria-sort="ascending" aria-label="SN#: activate to sort column descending">SN#</th>
                                        <th class="sorting" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 236px;" aria-label="Enrollment number: activate to sort column ascending">Enrollment number</th>
                                        <th class="sorting" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 153px;" aria-label="Roll number: activate to sort column ascending">Roll number</th>
                                        <th class="sorting" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 187px;" aria-label="External marks: activate to sort column ascending">External marks</th>
                                        <th class="sorting" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 289px;" aria-label="External marks In words: activate to sort column ascending">External marks In words</th>
                                    </tr>
								</thead>
                                <tbody style="">
                                    @foreach($exams as $index=>$exam)
                                    <tr>
                                        <td>{{++$index}}</td>
                                        <td><strong>{{$exam->enrollment_number}}</strong></td>
                                        <td><strong>{{$exam->roll_number}}</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                 <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            </div>
    <!-- END: Content-->

     @endsection
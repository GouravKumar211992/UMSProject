@extends("ums.admin.admin-meta")
@section("content")
 @section("content")
@if($download!='pdf')

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Tabular Record (TR)</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active"></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <form method="get" id="form_data">
                    <div class="row">
                        <div class="col-md-3 mt-2 mb-2">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-4">
                                    <label class="form-label">COURSES:<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <select data-live-search="true" name="course" id="course" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                                        <option value="">--Choose Course--</option>
                                                @foreach($courses as $course)
                                                    @if(Request()->course==$course->id)
                                                        @php
                                                            $course_name = $course->name;
                                                        @endphp
                                                    @endif
                                                    <option value="{{$course->id}}" @if(Request()->course==$course->id) selected @endif >{{$course->name}}</option>
                                                    @endforeach
                                                </select>
                                            <span class="text-danger">{{ $errors->first('course') }}</span>
                                </div>
                            </div>
                        </div>
                
                        <!-- New Select Dropdown added here -->
                        <div class="col-md-3 mt-2 mb-2">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-4">
                                    <label class="form-label">Semester:<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <select data-live-search="true" name="semester" id="semester" style="border-color: #c0c0c0;" class="form-control js-example-basic-single ">
                                        <option value="">--Select Semester--</option>
                                        @foreach($semesters as $semester)
                                            @if(Request()->semester==$semester->id)
                                                @php
                                                    $semester_name = $semester->name;
                                                @endphp
                                            @endif
                                        <option value="{{$semester->id}}" @if(Request()->semester==$semester->id) selected @endif >{{$semester->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('semester') }}</span>
                                </div>
                            </div>
                        </div>
                
                        <div class="col-md-3 mt-2 mb-2">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-4">
                                    <label class="form-label">Batch:<span class="text-danger m-0">*</span></label>
                                </div>

                                <div class="col-md-8">
                                    <select name="batch" id="batch" class="form-control" style="border-color: #c0c0c0;" onChange="return $('#form_data').submit();">
                                        @foreach($batchs as $batch)
                                        <option value="{{$batch}}" @if(Request()->batch == $batch) selected @endif >{{$batch}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('batch') }}</span>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3 mt-2 mb-2">
                            <div class="row align-items-center mb-1">
                    
                                <div class="col-md-5" >
                                    <button  type="submit" name="showdata" value="Get Data" class="btn btn-primary btn-sm mb-50 mb-sm-0 w-100" >
                                        <span  class="d-inline" ><i data-feather="check-circle" ></i>Get Data</span>
                                    </button>
                                    </div>
                                <div class="col-md-5" >
                                    <a href="" id='dd' class="btn btn-info btn-sm mb-50 mb-sm-0 w-100 " onclick="downloadExcel()">Excel Export</a>

                                    </div>
                            </div>
                        </div>
                    </div>
                </form>
                @endif
{{-- 
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table
                                        class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome">
                                        <thead>
                                            @if($semester_details)
                                            <tr>
                                                <th colspan="{{$subjects->count() + 6}}" style="border: none !important;font-size: 20px;font-weight: bold;text-transform: uppercase;">
                                                    <br>
                                                    {{$unuversity_name->name}}, Lucknow
                                                    <br>
                                                    FINAL EXAMINATION RESULT - 2022-23 (Held in March - 2024)
                                                    <br>
                                                    <br>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="{{$subjects->count() + 6}}" style="border: none !important;text-align: left !important;text-transform: uppercase;">
                                                    {{$campus_details->name}}
                                                    <br>
                                                    COURSE : {{$semester_details->course->name}}
                                                    <br>
                                                    Batch - 2020-23
                                                </th>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th rowspan="3">S.NO.</th>
                                                <th rowspan="3">ROLL NO.</th>
                                                <th rowspan="3" style="min-width: 200px;text-align: left;">NAME</th>
                                                <th colspan="{{$subject_theory_count+1}}">THEORY</th>
                                                <th>PRACTICAL</th>
                                                <th rowspan="2">GRAND TOTAL</th>
                                                <th rowspan="3">RESULT</th>
                                            </tr>
                                            <tr>
                                                @foreach($subjects as $index_sub_code=>$subject)
                                                @php ++$index_sub_code; @endphp
                                                <th style="max-width: 400px;">
                                                    {{strtoupper($subject->name)}}<br>
                                                    {{$subject->sub_code}}
                                                </th>
                                                @if($index_sub_code==$subject_theory_count)
                                                <th style="max-width: 400px;">TOTAL</th>
                                                @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @php $sub_total_max_marks = 0; @endphp
                                                @php $theory_total = 0; @endphp
                                                @foreach($subjects as $index_marks=>$subject)
                                                    @php ++$index_marks; @endphp
                                                    @php $sub_total_max_marks = $sub_total_max_marks + (int)$subject->maximum_mark; @endphp
                                                    @php $theory_total = $theory_total + (int)$subject->maximum_mark; @endphp
                                                    <th>
                                                        {{$subject->maximum_mark}}
                                                    </th>
                                                    @if($index_marks==$subject_theory_count)
                                                    <th style="max-width: 400px;">{{$theory_total}}</th>
                                                    @endif
                                                @endforeach
                                                <th>{{$sub_total_max_marks}}</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            @foreach($results as $index=>$result)
                                            @php 
                                                $student = $result->student;
                                                $result_subjects = $result->get_semester_result(1);
                                                $total_obtained_marks = 0;
                                                $total_required_marks = 0;
                                                $overall_status = 'PASSED';
                                            @endphp
                                            <tr>
                                                <td>{{++$index}}</td>
                                                <td>{{$result->roll_no}}</td>
                                                <td style="min-width: 200px;text-align: left;">{{$student->full_name}}</td>
                                                @php $total_theory_obtained_marks = 0; @endphp
                                                @foreach($result_subjects as $index_obtained_marks=>$mark)
                                                    @php
                                                        $status = 'PASSED';
                                                        $percentage = ((int)$mark->external_marks*100)/$mark->max_total_marks;
                                                        if($percentage < 40){
                                                            $status = 'FAILED';
                                                        }
                                                        if($status == 'FAILED'){
                                                            $overall_status = 'FAILED';
                                                        }
                                                        $total_obtained_marks = ($total_obtained_marks + (int)$mark->external_marks);
                                                        $total_required_marks = ($total_required_marks + (int)$mark->max_total_marks);
                                                    @endphp
                                                    <td style="max-width: 400px;">{{$mark->external_marks}} @if($status=='FAILED')*@endif</td>
                                                    @php $total_theory_obtained_marks = $total_theory_obtained_marks + (int)$mark->external_marks; @endphp
                                                    @php ++$index_obtained_marks; @endphp
                                                    @if($index_obtained_marks==$subject_theory_count)
                                                    <td style="max-width: 400px;">{{$total_theory_obtained_marks}}</td>
                                                    @endif
                                                @endforeach
                                                <td>{{$total_obtained_marks}}</td>
                                                <td>
                                                @php
                                                    $total_percentage = ((int)$total_obtained_marks*100)/$total_required_marks;
                                                    if($overall_status == 'FAILED'){
                                                        $overall_status = 'FAILED';
                                                    }
                                                    else if($total_percentage < 50){
                                                        $overall_status = 'FAILED';
                                                    }else{
                                                        $overall_status = 'PASSED';
                                                    }
                                                @endphp
                                                {{$overall_status}}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="{{$subjects->count() + 6}}" style="border: none !important;text-align: right;font-size: 15px;padding-top: 70px !important;">
                                                    <div class="row">
                                                        <div class="col-md-4" style="text-align: left;"><strong>CHECKED BY</strong></div>
                                                        <div class="col-md-4" style="text-align: center;"><strong>VERIFIED BY</strong></div>
                                                        <div class="col-md-4" style="text-align: right;"><strong>CONTROLLER OF EXAMINATION</strong></div>
                                                    </div>
                                                    
                                                </th>
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                        <input type="text" class="form-control dt-full-name"
                                            id="basic-icon-default-fullname" placeholder="John Doe"
                                            aria-label="John Doe" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-post">Post</label>
                                        <input type="text" id="basic-icon-default-post"
                                            class="form-control dt-post" placeholder="Web Developer"
                                            aria-label="Web Developer" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-email">Email</label>
                                        <input type="text" id="basic-icon-default-email"
                                            class="form-control dt-email" placeholder="john.doe@example.com"
                                            aria-label="john.doe@example.com" />
                                        <small class="form-text"> You can use letters, numbers & periods </small>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                                        <input type="text" class="form-control dt-date"
                                            id="basic-icon-default-date" placeholder="MM/DD/YYYY"
                                            aria-label="MM/DD/YYYY" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="basic-icon-default-salary">Salary</label>
                                        <input type="text" id="basic-icon-default-salary"
                                            class="form-control dt-salary" placeholder="$12000"
                                            aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section> --}}

                <div class="container">
                    <!-- or use any other number .col-md- -->
                    <section class="table table-responsive" id="downloadable_div">
                
                    <table class="table" style="width: 100% !important;" id="example" cellpadding="0" cellspacing="0">
                        <thead>
                            @if($semester_details)
                            <tr>
                                <th colspan="{{$subjects->count() + 6}}" style="border: none !important;font-size: 20px;font-weight: bold;text-transform: uppercase;">
                                    <br>
                                    {{$unuversity_name->name}}, Lucknow
                                    <br>
                                    FINAL EXAMINATION RESULT - 2022-23 (Held in March - 2024)
                                    <br>
                                    <br>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="{{$subjects->count() + 6}}" style="border: none !important;text-align: left !important;text-transform: uppercase;">
                                    {{$campus_details->name}}
                                    <br>
                                    COURSE : {{$semester_details->course->name}}
                                    <br>
                                    Batch - 2020-23
                                </th>
                            </tr>
                            @endif
                            <tr>
                                <th rowspan="3">S.NO.</th>
                                <th rowspan="3">ROLL NO.</th>
                                <th rowspan="3" style="min-width: 200px;text-align: left;">NAME</th>
                                <th colspan="{{$subject_theory_count+1}}">THEORY</th>
                                <th>PRACTICAL</th>
                                <th rowspan="2">GRAND TOTAL</th>
                                <th rowspan="3">RESULT</th>
                            </tr>
                            <tr>
                                @foreach($subjects as $index_sub_code=>$subject)
                                @php ++$index_sub_code; @endphp
                                <th style="max-width: 400px;">
                                    {{strtoupper($subject->name)}}<br>
                                    {{$subject->sub_code}}
                                </th>
                                @if($index_sub_code==$subject_theory_count)
                                <th style="max-width: 400px;">TOTAL</th>
                                @endif
                                @endforeach
                            </tr>
                            <tr>
                                @php $sub_total_max_marks = 0; @endphp
                                @php $theory_total = 0; @endphp
                                @foreach($subjects as $index_marks=>$subject)
                                    @php ++$index_marks; @endphp
                                    @php $sub_total_max_marks = $sub_total_max_marks + (int)$subject->maximum_mark; @endphp
                                    @php $theory_total = $theory_total + (int)$subject->maximum_mark; @endphp
                                    <th>
                                        {{$subject->maximum_mark}}
                                    </th>
                                    @if($index_marks==$subject_theory_count)
                                    <th style="max-width: 400px;">{{$theory_total}}</th>
                                    @endif
                                @endforeach
                                <th>{{$sub_total_max_marks}}</th>
                            </tr>
                            
                        </thead>
                        <tbody>
                            @foreach($results as $index=>$result)
                            @php 
                                $student = $result->student;
                                $result_subjects = $result->get_semester_result(1);
                                $total_obtained_marks = 0;
                                $total_required_marks = 0;
                                $overall_status = 'PASSED';
                            @endphp
                            <tr>
                                <td>{{++$index}}</td>
                                <td>{{$result->roll_no}}</td>
                                <td style="min-width: 200px;text-align: left;">{{$student->full_name}}</td>
                                @php $total_theory_obtained_marks = 0; @endphp
                                @foreach($result_subjects as $index_obtained_marks=>$mark)
                                    @php
                                        $status = 'PASSED';
                                        $percentage = ((int)$mark->external_marks*100)/$mark->max_total_marks;
                                        if($percentage < 40){
                                            $status = 'FAILED';
                                        }
                                        if($status == 'FAILED'){
                                            $overall_status = 'FAILED';
                                        }
                                        $total_obtained_marks = ($total_obtained_marks + (int)$mark->external_marks);
                                        $total_required_marks = ($total_required_marks + (int)$mark->max_total_marks);
                                    @endphp
                                    <td style="max-width: 400px;">{{$mark->external_marks}} @if($status=='FAILED')*@endif</td>
                                    @php $total_theory_obtained_marks = $total_theory_obtained_marks + (int)$mark->external_marks; @endphp
                                    @php ++$index_obtained_marks; @endphp
                                    @if($index_obtained_marks==$subject_theory_count)
                                    <td style="max-width: 400px;">{{$total_theory_obtained_marks}}</td>
                                    @endif
                                @endforeach
                                <td>{{$total_obtained_marks}}</td>
                                <td>
                                @php
                                    $total_percentage = ((int)$total_obtained_marks*100)/$total_required_marks;
                                    if($overall_status == 'FAILED'){
                                        $overall_status = 'FAILED';
                                    }
                                    else if($total_percentage < 50){
                                        $overall_status = 'FAILED';
                                    }else{
                                        $overall_status = 'PASSED';
                                    }
                                @endphp
                                {{$overall_status}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="{{$subjects->count() + 6}}" style="border: none !important;text-align: right;font-size: 15px;padding-top: 70px !important;">
                                    <div class="row">
                                        <div class="col-md-4" style="text-align: left;"><strong>CHECKED BY</strong></div>
                                        <div class="col-md-4" style="text-align: center;"><strong>VERIFIED BY</strong></div>
                                        <div class="col-md-4" style="text-align: right;"><strong>CONTROLLER OF EXAMINATION</strong></div>
                                    </div>
                                    
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </section>             
                </div>

                


            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a
                    class="ml-25" href="#" target="_blank">Presence 360</a><span
                    class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>

        <div class="footerplogo"><img src="../../../assets/css/p-logo.png" /></div>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
        <div class="modal-dialog sidebar-sm">
            <form class="add-new-record modal-content pt-0">
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="mb-1">
                        <label class="form-label" for="fp-range">Select Date</label>
                        <!--                        <input type="text" id="fp-default" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />-->
                        <input type="text" id="fp-range" class="form-control flatpickr-range bg-white"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                    </div>

                    <div class="mb-1">
                        <label class="form-label">PO No.</label>
                        <select class="form-select">
                            <option>Select</option>
                        </select>
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Vendor Name</label>
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
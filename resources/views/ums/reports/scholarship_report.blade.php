@extends('ums.admin.admin-meta')
@section('content')
    
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Pass Out Students</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                        <form method="GET" id="form_data">

                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0"  type="submit" name="submit_form"><i data-feather="check-circle" ></i>Get Report</button>

                        {{-- <button class="btn btn-warning box-shadow-2 btn-sm  mb-sm-0 mb-50"><i data-feather="refresh-cw"></i>Reset</button> --}}
                           
                            <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                             
                    </div>
                </div>
               
                </div>
            </div>
            <div class="content-body">
                <div class="row ">
                    <div class="col-md-4 ">
                        <div class="row align-items-center ">
                            <div class="col-md-4">
                                <label class="form-label">Campus:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select data-live-search="true" name="campus_id" id="campus_id" style="border-color: #c0c0c0;" class="form-control" onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Campus--</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{$campus->id}}" @if(Request()->campus_id==$campus->id) selected @endif >{{$campus->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="row align-items-center ">
                            <div class="col-md-4">
                                <label class="form-label">Courses:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select data-live-search="true" name="course_id" id="course_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Course--</option>
                                    @foreach($courses as $course)
                                        <option value="{{$course->id}}" @if(Request()->course_id==$course->id) selected @endif >{{$course->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="row align-items-center ">
                            <div class="col-md-4">
                                <label class="form-label">Academic Session:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select name="academic_session" id="academic_session" style="border-color: #c0c0c0;" class="form-control">
                                    <option value="">--Academic Session--</option>
                                    @foreach($sessions as $session)
                                    <option value="{{$session->academic_session}}" @if(Request()->academic_session==$session->academic_session) selected @endif >{{$session->academic_session}}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>
                    </div>
              
                </div>
            </form>
                
<br>

                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            <thead>
                                                <tr>
                                                    <th>SN#</th>
                                                    <th>Collage Code</th>
                                                    <th>Course Code</th>
                                                    <th>Enrollment</th>
                                                    <th>Name</th>
                                                    <th>Father Name</th>
                                                    <th>Mother Name</th>
                                                    <th>Date Of Birth</th>
                                                    <th>Course Name</th>
                                                    <th>Branch Name</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                    <th>Promoted
                                                    Y/N</th>
                                                    <th>Student Roll No
                                                    University Alloted</th>
                                                    <th>Passing Year</th>
                                                    <th>Full Exam-System
                                                    (S for Semester Or Y for Year)</th>
                                                    <th>Second Last Semester Total Marks</th>
                                                    <th>Second Last Semester Obtained Marks</th>
                                                    <th>Last Semester Total Marks</th>
                                                    <th>Last Semester Obtained Marks</th>
                                                    <th>Total Marks Marks</th>
                                                    <th>Total Obtained Marks</th>
                                                    <th>Percentage</th>
    
                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $counter = 0; @endphp
                                                @foreach($results as $index=>$result)
                                                  @php
                                                    $get_second_last_semesters = $result->get_second_last_semesters(Request()->academic_session);
                                                    $get_last_semesters = $result->get_last_semesters(Request()->academic_session);
                                                  @endphp
                                                  @if($get_last_semesters->count()>0)
                                                  @php
                                                    $result_data = $get_last_semesters->first();
                                                    $secont_last_max_marks = ($get_second_last_semesters->sum('max_internal_marks')+$get_second_last_semesters->sum('max_external_marks'));
                                                    $secont_last_obtained_marks = $get_second_last_semesters->sum('total_marks');
                                                    $last_max_marks = ($get_last_semesters->sum('max_internal_marks')+$get_last_semesters->sum('max_external_marks'));;
                                                    $last_obtained_marks = $get_last_semesters->sum('total_marks');
                                                    $total_max_marks = ($secont_last_max_marks + $last_max_marks);
                                                    $total_abtained_marks = ($secont_last_obtained_marks + $last_obtained_marks);
                                                    $percentage = (($total_abtained_marks*100)/$total_max_marks);
                                                  @endphp
                                                  <tr>
                                                    <td>{{++$counter}}</td>
                                                    <td>{{$result_data->course->campuse->campus_code}}</td>
                                                    <td>{{$result_data->course_name()}}</td>
                                                    <td>{{$result->enrollment_no}}</td>
                                                    <td>{{$result->student ? $result->student->full_name : "N/A"}}</td>
                                                    <td>{{$result->student ? $result->student->father_name : "N/A"}}</td>
                                                    <td>{{$result->student ? $result->student->mother_name : "N/A"}}</td>
                                                    <td>{{($result->student && $result->student->date_of_birth) ? date('d-m-Y',strtotime($result->student->date_of_birth)) : "N/A"}}</td>
                                                    <td>{{$result_data->course_description()}}</td>
                                                    <td>{{$result_data->course->stream->name}}</td>
                                                    <td>{{$result_data->result_full_text}}</td>
                                                    <td>N/A</td>
                                                    <td>
                                                      @if($result_data->result_full == 'FAILED' || $result_data->result_full == 'ABSENT')
                                                      No
                                                      @else
                                                      Yes
                                                      @endif
                                                    </td>
                                                    <td>{{$result->roll_no}}</td>
                                                    <td>{{Request()->academic_session}}</td>
                                                    <td>{{( strpos( $result_data->semester_details->name, 'YEAR' ) == true)?'Y':'S'}}</td>
                                                    <td>{{$secont_last_max_marks}}</td>
                                                    <td>{{$secont_last_obtained_marks}}</td>
                                                    <td>{{$last_max_marks}}</td>
                                                    <td>{{$last_obtained_marks}}</td>
                                                    <td>{{$total_max_marks}}</td>
                                                    <td>{{$total_abtained_marks}}</td>
                                                    <td>{{round($percentage,2)}}</td>
                                                </tr>
                                                @endif
                                                @endforeach
                                               
                                            </tbody>
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
                </section>


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
{{-- </body> --}}
   
@endsection
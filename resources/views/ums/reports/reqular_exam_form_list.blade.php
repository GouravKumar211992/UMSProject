@extends("ums.admin.admin-meta")
@section("content")


<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Regular Exam Form Report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Report List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
               
            <form method="get" id="form_data">
                    <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" type="submit"  name="submit_form" value="true"><i data-feather="clipboard"></i> 
                            GET REPORT</button>
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();"><i
                                data-feather="refresh-cw"></i>
                            Reset</button>


                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row  ">


                    <div class="col-md mt-4 mb-3">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Campus:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="campus" id="campus" class="form-control" class="form-control js-example-basic-single" onChange="return $('#form_data').submit();">
                            <option value="">--Select Session--</option>
                            @foreach($campuses as $campus)
                                <option value="{{$campus->id}}" @if(Request()->campus==$campus->id) selected @endif >{{$campus->name}}</option>
                            @endforeach
                        </select>
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course:<span class="text-danger ">*</span></label>
                            </div>

                            <div class="col-md-9">
                                  <select name="course[]" style="border-color: #c0c0c0;" class="form-control" multiple>
                            <option value="">--Select--</option>
                            @foreach($courses as $course)
                                <option value="{{$course->id}}" @if(Request()->course && in_array($course->id,Request()->course)) selected @endif >{{$course->name}}</option>
                                @endforeach
                        </select>
                            </div>
                        </div>


                    </div>
                    <div class="col-md mt-4 mb-3">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Academic Session:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                 <select name="academic_session" id="academic_session" class="form-control" required>
                            <option value="">--Select Session--</option>
                            @foreach($sessions as $session)
                                <option value="{{$session->academic_session}}" @if(Request()->academic_session==$session->academic_session) selected @endif >{{$session->academic_session}}</option>
                            @endforeach
                        </select>
                            </div>
                            </div>
                       

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Semester Type:<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="semester_type" id="semester_type" class="form-control" required>
                            <option value="">--Select Semester Type--</option>
                            <option value="odd" @if(Request()->semester_type=='odd') selected @endif >Odd</option>
                            <option value="even" @if(Request()->semester_type=='even') selected @endif >Even</option>
                        </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form> 

                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table
                                        class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome">
                                        <thead>
                                            <th >
                                                Student Result Count:
                                                {{($results)?$results->count():0}}
                           
                                            </th> 
                                            <tr>

                                                <th>S.NO</th>
                                                <th>Campus Name</th>
                                                <th>COURSE</th>
                                                <th>SEMESTER</th>
                                                <th>ROLL NUMBER</th>
                                                <th>ENROLLMENT NUMBER</th>
                                                <th>NAME</th>
                                                <th>Mobile</th>
                                                <th>Exam form Filled</th>
                                                

                                            </tr>

                                        </thead>
                                        <tbody>
                                            @if($results)
                                            @php
                                                $filledCount = 0;
                                                $notFilledCount = 0;
                                                @endphp
                                            @foreach($results as $index_summary=>$result)
                                            @php $student = $result->student; @endphp
                                            @php $course = $result->course; @endphp
                                            @php $elegible_for_exam = $result->elegible_for_exam(Request()->semester_type); @endphp
                                            @php $exam_filled_Status = $result->exam_filled_Status(Request()->academic_session,Request()->semester_type); @endphp
                                            <tr>
                                                <td>{{++$index_summary}}</td>
                                                <td>{{$course->campuse->name}}</td>
                                                <td>{{$course->name}}</td>
                                                <td>
                                                @if($exam_filled_Status)
                                                    {{$exam_filled_Status->semester_details->name}}
                                                @else
                                                    @if($elegible_for_exam)
                                                    {{$elegible_for_exam->name}}
                                                    @endif
                                                @endif
                                                </td>
                                                <td>{{$result->roll_no}}</td>
                                                <td>{{$result->enrollment_no}}</td>
                                                <td>{{($student)?$student->full_name:''}}</td>
                                                <td>{{($student)?$student->mobile:''}}</td>
                                                <td>{{($exam_filled_Status)?'Filled':'Not Filled'}}</td>
                                                @if($exam_filled_Status)
                                                    @php $filledCount++ @endphp
                                                @else
                                                    @php $notFilledCount++ @endphp
                                                @endif
                                            </tr>
                                            @endforeach
                                              {{-- Display filled and not filled student count --}}
                                              <p>Filled Student Count: {{$filledCount}}</p>
                                              <p>Not Filled Student Count: {{$notFilledCount}}</p>
                                            @endif 
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
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

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


    <script src="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

@endsection
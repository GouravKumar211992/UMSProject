@extends('ums.admin.admin-meta')
@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Exam </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item"><a>Exam Schedule</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right flex-nowrap">
                        <form method="get" id="form_data">
                      
                        <button class="btn btn-secondary btn-sm mb-50 mb-sm-0"><i data-feather="arrow-left-circle"></i>Go
                            Back</button>
                            <button class="btn btn-primary btn-sm mb-50 mb-sm-0" id="submitBtn" type="submit"><i
                                    data-feather="check-circle"></i> Submit</button>
                            <button class="btn btn-danger  btn-sm mb-50 mb-sm-0" data-bs-toggle="modal"
                                data-bs-target="#bulkUploadModal"><i data-feather="upload"></i>Schedule Bulk Upload</button>
                            <a class="btn btn-info btn-sm mb-50 mb-sm-0" href="{{url('view-time-tables')}}"> <i
                                    data-feather="eye"></i>View Time Tables</a>

                    </div>
                </div>
            </div>
            <div class="content-body">
                @include('ums.admin.notifications')
                <div class="row">

                    <!-- Campus and Course Selection -->
                    <div class="col-md mt-4 mb-3">
                        <!-- Campus Selection -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Campus:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="campus_id" id="campus_id"
                                    style="border-color: #c0c0c0;" class="form-control js-example-basic-single "
                                    onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Campus--</option>
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}"
                                            @if (Request()->campus_id == $campus->id) selected @endif>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Course Selection -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course:<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="course" id="course" style="border-color: #c0c0c0;"
                                    class="form-control js-example-basic-single "
                                    onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Course--</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}"
                                            @if (Request()->course == $course->id) selected @endif>{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Schedule Count<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select data-live-search="true" name="schedule_count" id="schedule_count"
                                    style="border-color: #c0c0c0;" class="form-control js-example-basic-single ">
                                    <option value="">Select Schedule Count</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}"
                                            @if (Request()->schedule_count == $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Semester, Session, and other options -->
                    <div class="col-md mt-4 mb-3">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Semester:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="semester" id="semester"
                                    style="border-color: #c0c0c0;" class="form-control js-example-basic-single ">
                                    <option value="">Select Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}"
                                            @if (Request()->semester == $semester->id) selected @endif>{{ $semester->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Session:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="session" id="session" style="border-color: #c0c0c0;"
                                    class="form-control js-example-basic-single ">
                                    <option value="">Select Session</option>
                                    @foreach ($sessions as $session)
                                        <option value="{{ $session->academic_session }}"
                                            @if (Request()->session == $session->academic_session) selected @endif>
                                            {{ $session->academic_session }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    </form>
                    <!-- Submit Button -->

                </div>



                @if (Request()->course)
                    <div class="container" style="background-color: #FFFFFF;">

                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">

                                </div><br><br>

                                <div id="" class=" " >
                                    <div class="panel-body">
                                        <div class="container">

                                          

                                                <form method="post" action="" id="main_form">
                                                    @csrf


                                                    <table border="1" id="tblTheory21" class="table table-hover">
                                                        <tr>
                                                            <td colspan="5">
                                                                <center>
                                                                    <h4>Exam Time Table</h4>
                                                                </center>
                                                        </tr>
                                                        <tr>
                                                            <th class="thcenter">COURSE/SEMESTER</th>
                                                            <th class="thcenter">DATE</th>
                                                            <th class="thcenter">SHIFT</th>
                                                            <th class="thcenter">PAPER CODE</th>
                                                            <th class="thcenter">PAPER NAME</th>

                                                        </tr>

                                                        @foreach ($subjects as $subject)
                                                            <input type="text" style=" max-width: 60px;"
                                                                class="form-control" readonly="true" name="course_id"
                                                                value="{{ $subject->course_id }}" hidden />
                                                            <input type="text" style=" max-width: 60px;"
                                                                class="form-control" readonly="true" name="courses_name"
                                                                value="{{ $subject->course->name }}" hidden />
                                                            <input type="text" style=" max-width: 60px;"
                                                                class="form-control" readonly="true" name="program_id"
                                                                value="{{ $subject->program_id }}" hidden />
                                                            <input type="text" style=" max-width: 60px;"
                                                                class="form-control" readonly="true" name="semester_id"
                                                                value="{{ $subject->semester_id }}" hidden />
                                                            <input type="text" style=" max-width: 60px;"
                                                                class="form-control" readonly="true" name="year"
                                                                value="{{ Request()->session }}" hidden />
                                                            <input type="text" style=" max-width: 60px;"
                                                                class="form-control" readonly="true"
                                                                name="schedule_count"
                                                                value="{{ Request()->schedule_count }}" hidden />
                                                            <input type="text" style=" max-width: 60px;"
                                                                class="form-control" readonly="true" name="semester_name"
                                                                value="{{ $subject->semester->name }}" hidden />

                                                            <tr class="auto-style18 thcenter">
                                                                <td>
                                                                    <span id="lblPap1ID">{{ $subject->semester->name }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span id="lblPap1Name">
                                                                        <input style="border-color: #c0c0c0;"
                                                                            name="date[]" type="date"
                                                                            class="form-control date" />
                                                                        <span
                                                                            class="text-danger">{{ $errors->first('date[]') }}</span>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <select name="shift[]"
                                                                        style="border-color: #c0c0c0; width:fit-content;"
                                                                        class="form-control shift">
                                                                        <option value="">--Choose Shift--
                                                                        </option>
                                                                        <option value="morning">Morning</option>
                                                                        <option value="evening">Evening</option>
                                                                    </select>
                                                                    <span
                                                                        class="text-danger">{{ $errors->first('shift[]') }}</span>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        style=" max-width: 100px;" readonly="true"
                                                                        name="paper_code[]"


                                                                        value="{{ $subject->sub_code }}" hidden />
                                                                    <label id="lblPap1TS">{{ $subject->sub_code }}</label>
                                                                </td>
                                                                <td><input type="text" class="form-control"
                                                                        style=" max-width: 100px;" readonly="true"
                                                                        name="paper_name[]" value="{{ $subject->name }}"
                                                                        hidden><label
                                                                        id="lblPap1ID">{{ $subject->name }}</label>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-sm-5"></div>
                                                        <div class="col-sm-2"><input type="submit"
                                                                class="btn btn-success form-control save_pge"
                                                                value="Save"></div>
                                                        <div class="col-sm-5"></div>
                                                    </div>
                                                </form>


                                            </div>

                                        </div>

                                    </div>
                                </div>
                                {{-- <div class="pagination_rounded pr-4">
                            @if ($subjects)
                                {{ $subjects->appends(Request()->all())->links('partials.pagination') }}
                            @endif
                        </div> --}}

                            </div>

                        </div>

                    </div>
                @endif
                {{-- <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">


                                <div class="table-responsive">
                                    <table
                                        class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome">
                                        <thead>
                                            <tr>

                                                <th>S.NO</th>
                                                <th>CAMPUS</th>
                                                <th>COURSE</th>
                                                <th>SEMESTER</th>
                                                <th>ROLL NUMBER</th>
                                                <th>NAME</th>
                                                <th>DISABILITY CATEGORY</th>
                                                <th>PAPER CODE</th>
                                                <th>PAPER NAME</th>
                                                <th>PAPER TYPE</th>
                                                <th>PAYMENT</th>
                                                <th>FACULTY NAME</th>
                                                <th>Internal Marks Filled</th>
                                            </tr>

                                        </thead>
                                        <tbody>


                                            <tr>
                                                <td>1</td>
                                                <td>Main Campus</td>
                                                <td>Bachelor of Science (BSc)</td>
                                                <td>2nd Semester</td>
                                                <td>123456</td>
                                                <td>John Doe</td>
                                                <td>OBC</td>
                                                <td>CS101</td>
                                                <td>Computer Science - Basics</td>
                                                <td>Theory</td>
                                                <td>Paid</td>
                                                <td>Dr. Smith</td>
                                                <td>Yes</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>North Campus</td>
                                                <td>Master of Arts (MA)</td>
                                                <td>1st Semester</td>
                                                <td>654321</td>
                                                <td>Jane Smith</td>
                                                <td>General</td>
                                                <td>EN101</td>
                                                <td>English Literature</td>
                                                <td>Practical</td>
                                                <td>Pending</td>
                                                <td>Prof. Johnson</td>
                                                <td>No</td>
                                                {{-- <td class="tableactionnew">
                                                    <div class="dropdown">
                                                        <button type="button"
                                                            class="btn btn-sm dropdown-toggle hide-arrow py-0"
                                                            data-bs-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="#">
                                                                <i data-feather="edit" class="me-50"></i>
                                                                <span>View Detail</span>
                                                            </a>
                                                            <a class="dropdown-item" href="#">
                                                                <i data-feather="edit-3" class="me-50"></i>
                                                                <span>Edit</span>
                                                            </a>

                                                            <a class="dropdown-item" href="#">
                                                                <i data-feather="trash-2" class="me-50"></i>
                                                                <span>Delete</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                           

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
                </section> --}}


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

    <div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary ">
                    <h5 class="modal-title text-white" id="bulkUploadModalLabel">Bulk File Upload</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/your-upload-endpoint" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="fileInput" class="form-label">Choose a file to upload</label>
                            <input class="form-control" type="file" id="fileInput" name="file[]"
                                accept=".csv, .xlsx, .xls" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Click Here To Download Format Of Excel
                                File</label>
                            <a href="#" class="btn btn-primary">Download</a>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('#course').change(function() {
                var course = $('#course').val();
                var formData = {
                    course: course,
                    "_token": "{{ csrf_token() }}"
                }; //Array 
                $.ajax({
                    url: "{{route('semester')}}",
                    type: "POST",
                    data: formData,
                    success: function(data, textStatus, jqXHR) {
                        $('#semester').html(data);
                    },
                });
            });
        });

        $('.save_pge').click(function() {
            $('.date').each(function(index, value) {
                var tr_value = $(this).closest('tr');

                if ($(this).closest('tr').find('.shift').is(":selected") == false && $(this).closest('tr')
                    .find('.date').val() == '') {

                    tr_value.remove();
                }
            });
            $('#main_form').submit();
        });
    </script>
@endsection

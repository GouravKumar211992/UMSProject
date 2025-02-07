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
                            <h2 class="content-header-title float-start mb-0">Back Paper Report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <!-- <li class="breadcrumb-item active">Report List</li> -->
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button onclick="window.location.reload()" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather="clipboard"></i> GET
                            REPORT</button>
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
                                <select name="campus_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single" onchange="getCourses($(this))">
                                    <option value="">---Choose Campus---</option>
                                    @foreach($campuses as $campus)
                                    <option value="{{$campus->id}}" @if($campus->id==Request()->campus_id) selected @endif >{{$campus->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course:<span class="text-danger">*</span></label>
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
                                <label class="form-label">Back Type:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="form_type" style="border-color: #c0c0c0;" class="form-control">
                                    <option value="">--Select Back Type--</option>
                                    @foreach($examType as $form_typeRow)
                                    @if($form_typeRow->exam_type!='regular')
                                    <option value="{{$form_typeRow->exam_type}}" @if($form_typeRow->exam_type==Request()->form_type) selected @endif >{{$form_typeRow->exam_type}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Acadmic Year:<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="academic_session" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " >    
                                    <option value="">---Choose Acedmic---</option>
                                    @foreach($academic_session as $academic_session)
                                    <option value="{{$academic_session->academic_session}}" @if($academic_session->academic_session==Request()->academic_session) selected @endif >{{$academic_session->academic_session}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>


                </div>


                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">


                                <div class="table-responsive">
                                    <table
                                        class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome">
                                        <thead>
                                            <tr>
                                                <th colspan="20"><span>Form Type.:-  {{Request()->form_type}} </span><br>
                                                <span>Academic Session:-  {{Request()->academic_session}}</span></th>
                                           </tr>
                                            <tr>

                                                <th>S.NO</th>
                                                <th>CAMPUS</th>
                                                <th>COURSE</th>
                                                <th>SEMESTER</th>
                                                <th>ROLL NUMBER</th>
                                                <th>ENROLLMENT NUMBER</th>
                                                <th>NAME</th>
                                                <th>CONTACT NO.</th>
                                                <th>WRITER/SCRIBE</th>
                                                <th>DISABILTY CATEGORY</th>
                                                <th>SUNBJECTS</th>
                                                <th>SUBJECT CODE</th>
                                                <th>PAYMENT</th>
                                                <th>EXT.</th>
                                                <th>VIVA</th>
                                                <th>P INT./P EXT.</th>
                                                <th>Internal Marks Filled</th>
                                                <th>External Marks Filled</th>
                                                <th>Practical Marks Filled</th>
                                                <th>Exam Form Filling Date</th>
                                                
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @php $count=0; @endphp
                                        @foreach($form_form_data as $index=>$examFee)
                                        {{  dd($examFee);}}
                                            @foreach($examFee->backPaperDetails as $backPaper)
                                            @php $backPapersubjectDetails = $backPaper->subjectDetails; @endphp
                                            @php $students = $examFee->students; @endphp
                                                <tr>
                                                    <td>{{++$count}}</td>
                                                    <td>{{$backPaper->course->campuse->name}}</td>
                                                    <td>{{$backPaper->course->name}}</td>
                                                    <td>{{($backPapersubjectDetails)?$backPapersubjectDetails->semester->name:''}}</td>
                                                    <td>{{$examFee->roll_no}}</td>
                                                    <td>{{$examFee->enrollment_no}}</td>
                                                    <td>{{$students->name}}</td>
                                                    <td>{{$students->mobile}}</td>
                                                    <td>{{strtoupper($examFee->scribe)}}</td>
                                                    <td>{{($students->disabilty_category)?$students->disabilty_category:'Not Applicable'}}</td>
                                                    <td>{{($backPapersubjectDetails)?$backPapersubjectDetails->name:''}}</td>
                                                    <td>{{($backPapersubjectDetails)?$backPapersubjectDetails->sub_code:''}}</td>
                                                    <td>SUCCESS</td>
                                                    @if($backPapersubjectDetails)
                                                    <td>{{($backPaper->mid)?'Yes':'No'}}</td>
                                                    <td>{{($backPaper->external)?'Yes':'No'}}</td>
                                                    <td>{{($backPaper->viva)?'Yes':'No'}}</td>
                                                    <td>{{($backPaper->p_internal)?'Yes':'No'}}</td>
                                                    @else
                                                    <td>No</td>
                                                    <td>No</td>
                                                    <td>No</td>
                                                    <td>No</td>
                                                    @endif
                                                    <td>{{($backPaper->internal_marks_filled)?'Filled':''}}</td>
                                                    <td>{{($backPaper->external_marks_filled)?'Filled':''}}</td>
                                                    <td>{{($backPaper->practical_marks_filled)?'Filled':''}}</td>
                                                    <td>{{date('d-m-Y',strtotime($examFee->created_at))}}</td>
                                                </tr>
                                            @endforeach
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

<script>
    function getCourses($this){
    var campus_id = $this.val();
    if(campus_id!=''){
        window.location.href = "{{url('back-paper-report')}}?campus_id="+campus_id;
    }
}

</script>

@endsection
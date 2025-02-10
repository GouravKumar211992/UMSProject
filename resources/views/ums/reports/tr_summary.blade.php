@extends('ums.admin.admin-meta')
@section("content")

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">TR Summary</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                    
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <form action="" id="getData">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" type="submit" name="showTr" ><i data-feather="clipboard"></i> Show TR Status
                            </button>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row  ">


                    <div class="col-md mt-4 mb-3">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label" for="">Exam Session:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select class="form-control" name="exam_session" required>
                                    <option value="">--select--</option>
                                    <option value="2023-2024AUG" @if(Request()->exam_session=='2023-2024AUG') selected @endif>2023-2024AUG</option>
                                    <option value="2023-2024JUL" @if(Request()->exam_session=='2023-2024JUL') selected @endif>2023-2024JUL</option>
                                    <option value="2023-2024FEB" @if(Request()->exam_session=='2023-2024FEB') selected @endif>2023-2024FEB</option>
                                    <option value="2023-2024" @if(Request()->exam_session=='2023-2024') selected @endif>2023-2024</option>
                                    <option value="2022-2023" @if(Request()->exam_session=='2022-2023') selected @endif>2022-2023</option>
                                    <option value="2021-2022" @if(Request()->exam_session=='2021-2022') selected @endif>2021-2022</option>
                                </select>
                            </div>
                        </div>

                        


                    </div>
                    <div class="col-md mt-4 mb-3">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label" for="">Exam Type:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select class="form-control" name="form_type" required>
                                    <option value="">--select--</option>
                                    <option value="regular" @if(Request()->form_type=='regular') selected @endif>REGULAR</option>
                                    <option value="back_paper" @if(Request()->form_type=='back_paper') selected @endif>BACK</option>
                                    <option value="special_back" @if(Request()->form_type=='special_back') selected @endif>SPL BACK</option>
                                    <option value="final_back_paper" @if(Request()->form_type=='final_back_paper') selected @endif>FINAL BACK</option>
                                    <option value="compartment" @if(Request()->form_type=='compartment') selected @endif>SUPPLEMENTARY</option>
                                </select>
                            </div>
                            {{-- <div class="col-md-4">
                                <label style="display:block">&nbsp;</label>
                                <input type="submit" class="btn btn-success" name="showTr" value="Show TR Status">
                            </div> --}}
                            </div>
                        </form>
                        </div>

                    </div>
                </div>


                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">


                                <div class="table-responsive">
                                    <table
                                        class="datatables-basic table ">
                                        <thead>
                                            <tr>

                                                <th>S.NO</th>
                                                <th>Campus </th>
                                                <th>COURSE</th>
                                                <th>SEMESTER</th>
                                                <th>Finalized status marks(to be filled/filled)</th>
                                               <th>Action</th>

                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tbody>
                                                {{-- @dd($results) --}}
                                                @foreach($results as $index=>$result)
                                                <?php
                                                $campus_id = $result->course->campus_id;
                                                $course_id = $result->course_id;
                                                $semester_id = $result->semester;
                                                $academic_session = Request()->exam_session;
                                                $form_type = Request()->form_type;
                                                $unfreez_url = "?course_id=$course_id&semester=$semester_id&academic_session=$academic_session&form_type=$form_type";
                                                $show_students_url = "?course_id=$course_id&semester=$semester_id&academic_session=$academic_session&form_type=$form_type&status=$result->status";
                                                $regular_tr_show_url = "?campus_id=$campus_id&course=$course_id&semester=$semester_id&academic_session=$academic_session&form_type=$form_type&page_index=1&showdata=Get+Data";
                                                $regular_generate_tr_url = "?campus_id=$campus_id&course=$course_id&semester=$semester_id&academic_session=$academic_session&form_type=$form_type";
                                                ?>
                                                <tr class="{{($result->status==2)?'success':'danger'}}">
                                                    <td>{{++$index}}</td>
                                                    <td>{{$result->course->campuse->name}}</td>
                                                    <td>{{$result->course_name()}}</td>
                                                    <td>{{$result->semester_details->name}}</td>
                                                    <td>{{($result->status==2)?'Freezed':'Not Freezed'}} ({{$result->getTrAllStudent($form_type)}}/{{$result->getStatusWiseStudent($form_type)}})</td>
                                                    <td class="tableactionnew">
                                                        <div class="dropdown z-index-25">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end ">
                                                                @if($result->status == 2)
                                                                    <a class="dropdown-item" href="{{url('unfreez-regular-tr')}}{{$unfreez_url}}" onClick="return confirm('Are you sure?\nYou want to Re-Fresh TR')">
                                                                        <i data-feather="refresh-cw" class="me-50"></i>
                                                                        <span>Un-Freez the TR</span>
                                                                    </a>
                                                                @else
                                                                    @if($form_type == 'regular')
                                                                        <a class="dropdown-item" target="_blank" href="{{url('university-tr')}}{{$regular_generate_tr_url}}" onClick="return confirm('Are you sure?\nYou want to Generate TR')">
                                                                            <i data-feather="file-text" class="me-50"></i>
                                                                            <span>Generate TR</span>
                                                                        </a>
                                                                    @elseif($form_type == 'back_paper')
                                                                        <a class="dropdown-item" target="_blank" href="{{url('back-university-tr')}}{{$regular_generate_tr_url}}" onClick="return confirm('Are you sure?\nYou want to Generate TR')">
                                                                            <i data-feather="file-text" class="me-50"></i>
                                                                            <span>Generate TR</span>
                                                                        </a>
                                                                    @elseif($form_type == 'special_back' || $form_type == 'final_back_paper')
                                                                        <a class="dropdown-item" target="_blank" href="{{url('final-back-tr-generate')}}{{$regular_generate_tr_url}}" onClick="return confirm('Are you sure?\nYou want to Generate TR')">
                                                                            <i data-feather="file-text" class="me-50"></i>
                                                                            <span>Generate TR</span>
                                                                        </a>
                                                                    @endif
                                                                    <a class="dropdown-item" data-toggle="modal" data-target="#myModal{{$index}}">
                                                                        <i data-feather="lock" class="me-50"></i>
                                                                        <span>Freez the TR</span>
                                                                    </a>
                                                                    <div id="myModal{{$index}}" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body">
                                                                                    <form action="{{url('freez-regular-tr')}}">
                                                                                        <div class="com-md-6 form-group">
                                                                                            <label for="">Enter Tr Approval Date</label>
                                                                                            <input type="date" name="approval_date" class="form-control" required style="border:1px solid #c0c0c0;">
                                                                                            <input type="hidden" value="{{$campus_id}}" name="campus_id" class="form-control" required>
                                                                                            <input type="hidden" value="{{$course_id}}" name="course_id" class="form-control" required>
                                                                                            <input type="hidden" value="{{$semester_id}}" name="semester" class="form-control" required>
                                                                                            <input type="hidden" value="{{$academic_session}}" name="academic_session" class="form-control" required>
                                                                                            <input type="hidden" value="{{Request()->form_type}}" name="form_type" class="form-control" required>
                                                                                        </div>
                                                                                        <div class="com-md-6 form-group">
                                                                                            <input type="submit" class="btn btn-success" value="Click here to approve the TR">
                                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                    
                                                                @if($form_type == 'regular')
                                                                    <a class="dropdown-item" target="_blank" href="{{url('regular_tr_view')}}{{$regular_tr_show_url}}" onClick="return confirm('Are you sure?\nYou want to Show TR')">
                                                                        <i data-feather="eye" class="me-50"></i>
                                                                        <span>Show TR</span>
                                                                    </a>
                                                                @elseif($form_type == 'back_paper')
                                                                    <a class="dropdown-item" target="_blank" href="{{url('back-university-tr-view')}}{{$regular_tr_show_url}}" onClick="return confirm('Are you sure?\nYou want to Show TR')">
                                                                        <i data-feather="eye" class="me-50"></i>
                                                                        <span>Show TR</span>
                                                                    </a>
                                                                @elseif($form_type == 'special_back' || $form_type == 'final_back_paper')
                                                                    <a class="dropdown-item" target="_blank" href="{{url('final-back-tr-view')}}{{$regular_tr_show_url}}" onClick="return confirm('Are you sure?\nYou want to Show TR')">
                                                                        <i data-feather="eye" class="me-50"></i>
                                                                        <span>Show TR</span>
                                                                    </a>
                                                                @endif
                                                                <a class="dropdown-item" target="_blank" href="{{url('tr_summary_show_students')}}{{$show_students_url}}">
                                                                    <i data-feather="users" class="me-50"></i>
                                                                    <span>Show Students</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                             
                                                </tr>
                                                @endforeach
                                            </tbody>

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

<!-- Bootstrap CSS (Ensure this is included) -->
{{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery (Ensure this is included first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
 --}}

    <!-- BEGIN: Vendor JS-->

    <!-- BEGIN: Vendor JS-->
    @endsection
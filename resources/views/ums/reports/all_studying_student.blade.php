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
                            <h2 class="content-header-title float-start mb-0">All Studying Student</h2>
                            <div class="breadcrumb-wrapper">
                                 
                            </div>
                        </div>
                    </div>
                </div>
                <form method="get" id="form_data">

                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather="clipboard" type="submit" name="submit_form" value="true"></i>Get Report
                            </button>
                            {{-- <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50"><i data-feather="refresh-cw"></i>Reset</button> --}}
                        


                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
    <!-- Campus Name -->
    <div class="col-md-4 mt-4 mb-3">
        <div class="row align-items-center mb-1">
            <div class="col-md-4">
                <label class="form-label">Campus Name:<span class="text-danger m-0">*</span></label>
            </div>
            <div class="col-md-8">
                <select name="campus" id="campus" class="form-control" required>
                    <option value="">--Select Session--</option>
                    @foreach($campuses as $campus)
                    <option value="{{$campus->id}}" @if(Request()->campus==$campus->id) selected @endif
                        >{{$campus->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Academic Session -->
    <div class="col-md-4 mt-4 mb-3">
        <div class="row align-items-center mb-1">
            <div class="col-md-4">
                <label class="form-label">Academic Session:<span class="text-danger m-0">*</span></label>
            </div>
            <div class="col-md-8">
                <select name="academic_session" id="academic_session" class="form-control" required>
                    <option value="">--Select Session--</option>
                    @foreach($academic_session as $session)
                    <option value="{{$session->academic_session}}" @if(Request() ->
                        academic_session==$session->academic_session) selected @endif
                        >{{$session->academic_session}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Additional Column (optional, empty space or other content) -->
    <div class="col-md-4 mt-4 mb-3">
        <!-- Add any content here, or leave it empty if not needed -->
        <div class="row align-items-center mb-1">
            <div class="col-md-4">
                <label class="form-label">Student Type:<span class="text-danger m-0">*</span></label>
            </div>
            <div class="col-md-8">
                <select name="type" id="type" class="form-control" required>
                    <option value="new" @if(Request()->type=='new') selected @endif>New Students</option>
                    <option value="old" @if(Request()->type=='old') selected @endif>Old Studing Students</option>
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
                                        class="datatables-basic table myrequesttablecbox  newerptabledesignlisthome">
                                        <thead>
                                            <tr>
                                                <th rowspan="3">SN#</th>
                                                <th rowspan="3">Course</th>
                                                <th rowspan="3">Total No Seats</th>
                                                <th colspan="9">Non-PwD Student</th>
                                                <th colspan="14">PwD Student</th>
                                                <th rowspan="3"> Total Male</th>
                                                <th rowspan="3"> Total Female</th>
                                                <th rowspan="3">Grand Total</th>
                                            </tr>
                                            <tr>
                                                <th rowspan="2">Total</th>
                                                <th colspan="2">Gender</th>
                                                <th colspan="5">Category</th>
                                                <th rowspan="2">Total</th>
                                                <th colspan="2">Gender</th>
                                                <th rowspan="2">Total</th>
                                                <th colspan="4">Disability</th>
                                                <th rowspan="2">Total</th>
                                                <th colspan="5">Category</th>
                                                <th rowspan="2">Total</th>
                                            </tr>
                                            <tr>
                                                <th>Male</th>
                                                <th>Female</th>
                                                <th>EWS</th>
                                                <th>General</th>
                                                <th>OBC</th>
                                                <th>SC</th>
                                                <th>ST</th>
                                                <th>Male</th>
                                                <th>Female</th>
                                                <th>VI</th>
                                                <th>HI</th>
                                                <th>LD/OH/PH</th>
                                                <th>Others</th>
                                                <th>EWS</th>
                                                <th>General</th>
                                                <th>OBC</th>
                                                <th>SC</th>
                                                <th>ST</th>
                                            </tr>
                
                
                                        </thead>
                                        <tbody>
                                            @if($courses)
                                            @php
                                            $index = 0;
                                            $total_male_nonPwd = 0;
                                            $total_female_nonPwd = 0;
                                            $total_gender_nonPwd = 0;
                                            $total_ews_students_nonPwd = 0;
                                            $total_gen_students_nonPwd = 0;
                                            $total_obc_students_nonPwd = 0;
                                            $total_sc_students_nonPwd = 0;
                                            $total_st_students_nonPwd = 0;
                                            $total_category_nonPwd = 0;
                    
                                            $total_male_students_Pwd = 0;
                                            $total_female_students_Pwd = 0;
                                            $total_gender_Pwd = 0 ;
                                            $total_vi_students_Pwd = 0;
                                            $total_hi_students_Pwd = 0;
                                            $total_ld_oh_ph_students_Pwd = 0;
                                            $total_others_students_Pwd = 0;
                                            $total_diability = 0;
                                            $total_category_Pwd = 0;
                    
                                            $total_ews_students_Pwd = 0;
                                            $total_gen_students_Pwd = 0;
                                            $total_obc_students_Pwd = 0;
                                            $total_sc_students_Pwd = 0;
                                            $total_st_students_Pwd = 0;
                    
                                            $grand_total_male = 0 ;
                                            $grand_total_female = 0;
                                            $grand_total_students = 0;
                    
                                            @endphp
                                            @foreach($courses as $index => $course)
                                            @php $course_wise_students = $course->all_course_wise_students(Request()->academic_session,Request()->type); 
                    
                                            $total_male_nonPwd +=  $course_wise_students['male_students_nonPwd'];
                    
                                             $total_female_nonPwd += $course_wise_students['female_students_nonPwd'];
                    
                                            $gender_total_nonPwd = ($course_wise_students['male_students_nonPwd'] + $course_wise_students['female_students_nonPwd']);
                    
                                            $total_gender_nonPwd += $gender_total_nonPwd;
                    
                                            $total_ews_students_nonPwd += $course_wise_students['ews_students_nonPwd'];
                    
                                            $total_gen_students_nonPwd += $course_wise_students['gen_students_nonPwd'];
                    
                                            $total_obc_students_nonPwd += $course_wise_students['obc_students_nonPwd'];
                    
                                            $total_sc_students_nonPwd += $course_wise_students['sc_students_nonPwd'];
                    
                                            $total_st_students_nonPwd += $course_wise_students['st_students_nonPwd'];
                    
                                            $total_category_nonPwd = $total_gen_students_nonPwd + $total_ews_students_nonPwd + $total_obc_students_nonPwd + $total_sc_students_nonPwd + $total_st_students_nonPwd ;
                    
                                            $category_total_nonPwd = ($course_wise_students['ews_students_nonPwd'] + $course_wise_students['gen_students_nonPwd'] + $course_wise_students['obc_students_nonPwd'] + $course_wise_students['sc_students_nonPwd'] + $course_wise_students['st_students_nonPwd']);
                    
                    
                                            $total_male_students_Pwd += $course_wise_students['male_students_Pwd'];
                    
                                            $total_female_students_Pwd += $course_wise_students['female_students_Pwd'];
                    
                                            $total_gender_Pwd = $total_male_students_Pwd + $total_female_students_Pwd;
                    
                                            $total_vi_students_Pwd +=  $course_wise_students['vi_students'];
                                            $total_hi_students_Pwd +=  $course_wise_students['hi_students'];
                                            $total_ld_oh_ph_students_Pwd +=   $course_wise_students['ld_oh_ph_students'];
                                            $total_others_students_Pwd += $course_wise_students['others_students'];
                                            
                                            $total_diability =  $total_vi_students_Pwd + $total_hi_students_Pwd + $total_ld_oh_ph_students_Pwd + $total_others_students_Pwd;
                    
                                             $total_ews_students_Pwd += $course_wise_students['ews_students_Pwd'];
                                             $total_gen_students_Pwd += $course_wise_students['gen_students_Pwd'];
                                             $total_obc_students_Pwd += $course_wise_students['obc_students_Pwd'];
                    
                                             $total_sc_students_Pwd += $course_wise_students['sc_students_Pwd'];
                                             $total_st_students_Pwd += $course_wise_students['st_students_Pwd'];
                    
                    
                                             $total_category_Pwd = $total_ews_students_Pwd + $total_gen_students_Pwd + $total_obc_students_Pwd + $total_sc_students_Pwd + $total_st_students_Pwd;
                    
                                            
                    
                                            $gender_total_Pwd = ($course_wise_students['male_students_Pwd'] + $course_wise_students['female_students_Pwd']);
                    
                    
                                            $category_total_Pwd = ($course_wise_students['ews_students_Pwd'] + $course_wise_students['gen_students_Pwd'] + $course_wise_students['obc_students_Pwd'] + $course_wise_students['sc_students_Pwd'] + $course_wise_students['st_students_Pwd']);
                    
                                            $total_male = ($course_wise_students['male_students_nonPwd'] + $course_wise_students['male_students_Pwd']);
                    
                                            $grand_total_male += $total_male;
                                            
                                            $total_female = ($course_wise_students['female_students_nonPwd'] + $course_wise_students['female_students_Pwd']);
                    
                                            $grand_total_female += $total_female;
                    
                                            $grand_total = $gender_total_nonPwd + $gender_total_Pwd ;
                    
                                            $grand_total_students +=  $grand_total;
                                          
                    
                                            @endphp
                                            
                                            <tr>
                                                
                                                <td>{{++$index}}</td>
                                                <td>{{ $course->name}}</td>
                                                <td>{{$course->total_no_of_seats}}</td>
                                                <td>{{ $course_wise_students['male_students_nonPwd']}}</td>
                                                <td>{{ $course_wise_students['female_students_nonPwd']}}</td>
                                                <td>{{ $gender_total_nonPwd}}</td>
                                                 <td>{{ $course_wise_students['ews_students_nonPwd']}}</td>
                                                <td>{{ $course_wise_students['gen_students_nonPwd']}}</td>
                                                <td>{{ $course_wise_students['obc_students_nonPwd']}}</td>
                                                <td>{{ $course_wise_students['sc_students_nonPwd']}}</td>
                                                <td>{{ $course_wise_students['st_students_nonPwd']}}</td>
                                                <td>{{ $category_total_nonPwd}}</td>
                                                <td>{{ $course_wise_students['male_students_Pwd']}}</td>
                                                <td>{{ $course_wise_students['female_students_Pwd']}}</td>
                                                <td>{{ $gender_total_Pwd}}</td>
                                                <td>{{ $course_wise_students['vi_students']}}</td>
                                                <td>{{ $course_wise_students['hi_students']}}</td>
                                                <td>{{ $course_wise_students['ld_oh_ph_students']}}</td>
                                                <td>{{ $course_wise_students['others_students']}}</td>
                                                <td>{{ $course_wise_students['total_disable_students']}}</td>
                                                <td>{{ $course_wise_students['ews_students_Pwd']}}</td>
                                                <td>{{ $course_wise_students['gen_students_Pwd']}}</td>
                                                <td>{{ $course_wise_students['obc_students_Pwd']}}</td>
                                                <td>{{ $course_wise_students['sc_students_Pwd']}}</td>
                                                <td>{{ $course_wise_students['st_students_Pwd']}}</td>
                                                <td>{{ $category_total_Pwd}}</td>
                                                <td>{{ $total_male }}</td>
                                                <td>{{ $total_female }}</td>
                                                <td>{{ $grand_total  }}
                                                </td>                             
                                            </tr>
                                          
                                            @endforeach
                                            @endif 
                                            <tr>
                                            <td colspan="3">
                                                <h6>Grand Total</h6>
                                            </td>
                                            <td>
                                                {{ $total_male_nonPwd }}
                                            </td>
                                            <td>
                                               {{ $total_female_nonPwd }}
                                            </td>
                                            <td>
                                                {{ $total_gender_nonPwd }} 
                                            </td>
                                            <td>{{$total_ews_students_nonPwd}}</td>
                                            <td>{{$total_gen_students_nonPwd}}</td>
                                            <td>{{ $total_obc_students_nonPwd }}</td>
                                            <td>{{ $total_sc_students_nonPwd }}</td>
                                            <td>{{$total_st_students_nonPwd}}</td>
                                            <td>{{ $total_category_nonPwd }}</td>
                                            <td>{{ $total_male_students_Pwd }}</td>
                                            <td>{{ $total_female_students_Pwd }}</td>
                                            <td>{{ $total_gender_Pwd }}</td>
                                            <td>{{ $total_vi_students_Pwd }}</td>
                                            <td>{{ $total_hi_students_Pwd }}</td>
                                            <td>{{ $total_ld_oh_ph_students_Pwd }}</td>
                                            <td>{{ $total_others_students_Pwd }}</td>
                                            <td>{{ $total_diability }}</td>
                                            <td>{{ $total_ews_students_Pwd }}</td>
                                            <td>{{ $total_gen_students_Pwd }}</td>
                                            <td>{{ $total_obc_students_Pwd }}</td>
                                            <td>{{ $total_sc_students_Pwd }}</td>
                                            <td>{{ $total_st_students_Pwd }}</td>
                                            <td>{{ $total_category_Pwd}}</td>
                                            <td>{{ $grand_total_male }}</td>
                                             <td>{{$grand_total_female}}</td>
                                             <td>{{$grand_total_students}}</td>
                                            
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
{{-- </body> --}}

@endsection
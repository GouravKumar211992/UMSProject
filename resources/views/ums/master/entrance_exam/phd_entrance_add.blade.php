 @extends('ums.admin.admin-meta')
<!-- END: Head-->

<!-- BEGIN: Body-->
 @section('content')
     


{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col=""> --}}



    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0 ">Add-Entrance-Exam
                            </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Add-Entrance-Exam</li>
                                </ol>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{route('phd-entrance-exam_add')}}">
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0" onclick="location.href='{{url('/phd_entrance_exam')}}'"> <i data-feather="arrow-left-circle"></i> GO BACK
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm mb-50 mb-sm-0"> <i data-feather="check-circle"
                                style="font-size: 40px;"></i>
                            Submit</button>


                    </div>
                </div>
            </div>
            <div class="content-body bg-white   py-4 mb-4 shadow">
                <div class="row  mt-3 mb-3 text-center px-md-5 ">


                    <div class="col-md-12 px-md-3 px-3">


                        <div class="row mb-1 ms-md-3">
                            <div class="col-md-2 text-start">
                                <label class="form-label">Program Name:<span class="text-danger m-0">*</span></label>
                            </div>
                    
                            <div class="col-md-9">
                                <input type="text" name="program_name"  class="form-control" placeholder="Enter notification description">
                            </div>
                        </div>
                    
                        <div class="row mb-1 ms-md-3">
                            <div class="col-md-2 text-start">
                                <label class="form-label">Program Code:<span class="text-danger m-0">*</span></label>
                            </div>
                    
                            <div class="col-md-9">
                                <input type="text"  name="program_code" class="form-control">
                            </div>
                        </div>
                    
                        <div class="row mb-1 ms-md-3">
                            <div class="col-md-2 text-start ">
                                <label class="form-label">Exam Date:<span class="text-danger m-0">*</span></label>
                            </div>
                    
                            <div class="col-md-9">
                                <input type="date" name="exam_date" class="form-control">
                            </div>
                        </div>
                    
                        <div class="row mb-1 ms-md-3">
                            <div class="col-md-2 text-start">
                                <label class="form-label">Exam Timing:<span class="text-danger m-0">*</span></label>
                            </div>
                    
                            <div class="col-md-9">
                                <input type="time" name="exam_time" class="form-control">
                            </div>
                        </div>
                    
                        <div class="row mb-1 ms-md-3">
                            <div class="col-md-2 text-start">
                                <label class="form-label">Exam Ending Timing:<span class="text-danger m-0">*</span></label>
                            </div>
                    
                            <div class="col-md-9">
                                <input type="time" name="exam_ending_time" class="form-control">
                            </div>
                        </div>
                    
                    </div>
                    

                </div>
            </div>
       
        </form>






            @endsection
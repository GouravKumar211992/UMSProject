
<<<<<<< HEAD
@extends('ums.admin.admin-meta')
=======
@extends('admin.admin-meta')
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
@section('content');
    
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

    <!-- BEGIN: Content-->

    <div class="app-content content ">


      <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        {{-- <h2 class="content-header-title float-start mb-0">Setting</h2> --}}
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                {{-- <li class="breadcrumb-item"><a href="dashboard">Home</a></li>  
                                <li class="breadcrumb-item active">Open Admission Form </li> --}}
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right">
                    <button class="btn btn-primary btn-sm mb-50 mb-sm-0" onclick="history.go(-1)" ><i data-feather="arrow-left"></i>Go back</button>
                    <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>
                        Reset</button> 
                </div>
            </div>
        </div>


        <section class="col-md-12 connectedSortable">
            <div class="row">
                <!-- Student ID Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <label for="student_id" class="form-label main-page">Student ID</label>
                        <input id="student_id" name="student_id" type="text" value="DSMNRU/" class="form-control" placeholder="Enter Student ID here">
                        <span class="text-danger"></span>
                    </div>
                </div>
                <!-- Course Code Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <label for="course_code" class="form-label main-page">Course Code</label>
                        <input id="course_code" name="course_code" type="text" value="" class="form-control" placeholder="Enter Course Code here">
                        <span class="text-danger"></span>
                    </div>
                </div>
                <!-- Semester Dropdown -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <label for="semester" class="form-label main-page">Semester</label>
                        <select id="semester" name="semester" class="form-control">
                            <option value="">-- Select Semester --</option>
                            <!-- Add semester options dynamically -->
                        </select>
                        <span class="text-danger"></span>
                    </div>
                </div>
            </div>
        </section>

        <section class="col-md-12 connectedSortable mt-3">
            <div class="row">
                <!-- Semester Fee Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <label for="semester_fee" class="form-label main-page">Semester Fee</label>
                        <input id="semester_fee" name="semester_fee" type="text" value="" class="form-control" placeholder="Enter Semester Fee here">
                        <span class="text-danger"></span>
                    </div>
                </div>
                <!-- Receipt Date Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <label for="receipt_date" class="form-label main-page">Receipt Date</label>
                        <input id="receipt_date" name="receipt_date" type="date" value="" class="form-control">
                        <span class="text-danger"></span>
                    </div>
                </div>
                <!-- Receipt Number Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <label for="receipt_number" class="form-label main-page">Receipt Number</label>
                        <input id="receipt_number" name="receipt_number" type="text" value="" class="form-control" placeholder="Enter Receipt Number here">
                        <span class="text-danger"></span>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    

<!-- options section end-->

    <!-- END: Content-->

  
   


{{-- </body> --}}
@endsection


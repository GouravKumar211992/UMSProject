@extends('admin.admin-meta')
@section("content")

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

    <!-- BEGIN: Header-->
      
     
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="app-content content ">

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
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">--Select--</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Coures <span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">All</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
                </div>
                
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Semester <span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">All</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Sub Code <span class="text-danger">*</span></label>
                   
                    <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                        <option value="7">--Select--</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="100">100</option>
                    </select>    
                </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Academic Session <span class="text-danger ">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">--Select--</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>    
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Exam Type <span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">--Select--</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>    
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Batch<span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">--Select--</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>   
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <button class="btn btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"> Get Report</button> 

            </div>
        </div>
    </div>

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
                                            
                                        </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Course
                                        </th><th colspan="7" rowspan="1">
                                            
                                        </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Semester
                                        </th><th colspan="7" rowspan="1">
                                            
                                        </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Paper Code (Name)
                                        </th><th colspan="7" rowspan="1">
                                                                                                                                    
                                        </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Maximum Marks
                                        </th><th colspan="7" rowspan="1"></th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Exam Type
                                        </th><th colspan="7" rowspan="1">
                                             </th></tr>
                                    <tr class=""><th colspan="2" rowspan="1">
                                            Date
                                        </th><th colspan="7" rowspan="1">
                                            09-01-2025 03:29:42pm
                                        </th></tr>
									<tr class=""><th class="sorting sorting_asc" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 65px;" aria-sort="ascending" aria-label="SN#: activate to sort column descending">SN#</th><th class="sorting" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 236px;" aria-label="Enrollment number: activate to sort column ascending">Enrollment number</th><th class="sorting" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 153px;" aria-label="Roll number: activate to sort column ascending">Roll number</th><th class="sorting" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 187px;" aria-label="External marks: activate to sort column ascending">External marks</th><th class="sorting" tabindex="0" aria-controls="myTable" rowspan="1" colspan="1" style="width: 289px;" aria-label="External marks In words: activate to sort column ascending">External marks In words</th></tr>
								</thead>
                                <tbody style="">
                                 <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No data available in table</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            </div>
    <!-- END: Content-->

     @endsection
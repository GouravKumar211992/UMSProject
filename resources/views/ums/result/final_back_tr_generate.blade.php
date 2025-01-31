@extends('ums.admin.admin-meta')
@section("content")
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
    {{-- @include('header');  --}}
    {{-- @include('sidebar'); --}}
    <!-- BEGIN: Content-->

    <div class="app-content content ">


        <!-- options section -->
    {{-- <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-4 text-center">
                <label class="form-label ">Base Rate % <span class="text-danger">*</span></label>
                <input type="number" value="5" class="form-control ">
            </div>
            <div class="col-md-4 text-center">
                <label class="form-label">Effective from <span class="text-danger">*</span></label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-4 text-center">
                <label class="form-label">Additional Input <span class="text-danger">*</span></label>
                <input type="text" class="form-control">
            </div>
        </div>
    </div> --}}

    <div class="big  d-flex justify-content-between align-items-center">

        <div class="content-header-left col-md-5 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Tabular Record (TR)</h2>
                   
                </div>
            </div>
        </div>
    <div class="submitss text-end me-3">

        <button onclick="javascript: history.go(-1)" class=" btn btn-primary btn-sm mb-50 mb-sm-0r waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Create</button>
        <button class="btn btn-warning btn-sm mb-50 mb-sm-0r " type="reset">
            <i data-feather="refresh-cw"></i> Reset
        </button>
    </div>
  </div>


    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">--Choose Campus--</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>
    
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Courses <span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">--Choose Course--</option>
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
                    <option value="7">--Select Semester--</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Form Type <span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">Final Year Back</option>
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
                <label class="form-label mb-0 me-2 col-3">Academic Session <span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">2024-2025</option>
                    <option value="10">2023-2024AUG</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>            
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Exam Month & Year<span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">Final Year Back</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Subject Group Name<span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">Please Select</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>
    

        </div>
    </div>
    

<!-- options section end-->

        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                {{-- <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Admin <br> <small>List of Admins</small></h2>
                            {{-- <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Incident List</li>
                                </ol>
                            </div> --}}
                        {{-- </div>
                    </div>
                </div>  --}}
                {{-- <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal">
                            <i data-feather="filter"></i> Filter
                        </button> 
                        <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                        <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="incident-add.html">
                            <i data-feather="user-plus"></i> Add Admin
                        </a>  
                        <!-- Reset Button -->
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" type="reset">
                            <i data-feather="refresh-cw"></i> Reset
                        </button>
                    </div>
                </div> --}}
                
            </div>
            <div class="content-body">
                 
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
{{-- 								   
                                <div class="table-responsive">
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>ROLL NO</th>
                                                    <th>NAME</th>
                                                    <th>GRAND TOTAL</th>
                                                    <th>RESULT</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td class="fw-bolder text-dark">Description will come here</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td>
                                                    <td><span class="badge rounded-pill badge-light-danger badgeborder-radius">Description will come here</span></td> 
                                                    <td>pass</td> 
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item">
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td class="fw-bolder text-dark">05-Sep-2024</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td>
                                                    <td><span class="badge rounded-pill badge-light-warning badgeborder-radius">Open</span></td> 
                                                    <td>pass</td> 
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" >
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                </tr>
                                                  <tr>
                                                    <td>3</td>
                                                    <td class="fw-bolder text-dark">05-Sep-2024</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td>
                                                    <td><span class="badge rounded-pill badge-light-success badgeborder-radius">Close</span></td> 
                                                    <td>pass</td> 
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item">
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>4</td>
                                                    <td class="fw-bolder text-dark">05-Sep-2024</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td>
                                                    <td><span class="badge rounded-pill badge-light-danger badgeborder-radius">Re-Allocatted</span></td> 
                                                    <td>pass</td> 
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" >
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td class="fw-bolder text-dark">05-Sep-2024</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td> 
                                                    <td><span class="badge rounded-pill badge-light-warning badgeborder-radius">Open</span></td>
                                                    <td>pass</td>   
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item">
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
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
								
                            </div> --}}

                            
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                    <div class="modal modal-slide-in fade" id="modals-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="add-new-record modal-content pt-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                        <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-post">Post</label>
                                        <input type="text" id="basic-icon-default-post" class="form-control dt-post" placeholder="Web Developer" aria-label="Web Developer" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-email">Email</label>
                                        <input type="text" id="basic-icon-default-email" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
                                        <small class="form-text"> You can use letters, numbers & periods </small>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                                        <input type="text" class="form-control dt-date" id="basic-icon-default-date" placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="basic-icon-default-salary">Salary</label>
                                        <input type="text" id="basic-icon-default-salary" class="form-control dt-salary" placeholder="$12000" aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                 

            </div>
        </div>
    </div>
    <!-- END: Content-->
 @endsection
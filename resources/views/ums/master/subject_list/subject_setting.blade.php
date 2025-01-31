@extends('ums.admin.admin-meta')

@section('content')
    

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col=""> --}}
  
   
    <div class="app-content content ">
        <h4>Subject Bulk Data</h4>

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

        <div class="submitss text-end me-3">


            <button class="btn btn-primary btn-sm mb-50 mb-sm-0r " type="reset">
                <i data-feather="check-circle"></i> Submit
            </button>
        </div>

<div class="content-body bg-white p-4 shadow">
        {{-- <div class="col-md-12  ">
            <div class="row align-items-center mb-1">
                <div class="col-md-4 d-flex align-items-center">
                    <div class="row">
                    <label class="form-label mb-0 me-2 col-3">Compus <span class="text-danger ">*</span></label>
                    <select name="DataTables_Table_0_length col-9" aria-controls="DataTables_Table_0" class="form-select">
                        <option value="7">--Select--</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="100">100</option>
                    </select>
                    </div>
                </div>

                <div class="col-md-4 align-items-center">
                    <div class="row">
                    <label class="form-label mb-0 me-2 col-3">Course <span class="text-danger">*</span></label>
                    <select name="DataTables_Table_0_length col-9 " aria-controls="DataTables_Table_0" class="form-select">
                        <option value="7">All</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="100">100</option>
                    </select>
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-center">
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
            </div>
        </div> --}}
        <div class="col-md-12">
            <div class="row align-items-center mb-1">
                <!-- Campus Field -->
                <div class="col-md-4">
                    <label class="form-label mb-0 me-2">Campus <span class="text-danger">*</span></label>
                    <select name="campus" aria-controls="DataTables_Table_0" class="form-select">
                        <option value="">--Select--</option>
                        <option value="7">Campus 1</option>
                        <option value="10">Campus 2</option>
                        <option value="25">Campus 3</option>
                        <option value="50">Campus 4</option>
                        <option value="75">Campus 5</option>
                        <option value="100">Campus 6</option>
                    </select>
                </div>
        
                <!-- Course Field -->
                <div class="col-md-4">
                    <label class="form-label mb-0 me-2">Course <span class="text-danger">*</span></label>
                    <select name="course" aria-controls="DataTables_Table_0" class="form-select">
                        <option value="">--Select--</option>
                        <option value="7">Course 1</option>
                        <option value="10">Course 2</option>
                        <option value="25">Course 3</option>
                        <option value="50">Course 4</option>
                        <option value="75">Course 5</option>
                        <option value="100">Course 6</option>
                    </select>
                </div>
        
                <!-- Semester Field -->
                <div class="col-md-4">
                    <label class="form-label mb-0 me-2">Semester <span class="text-danger">*</span></label>
                    <select name="semester" aria-controls="DataTables_Table_0" class="form-select">
                        <option value="">--Select--</option>
                        <option value="7">Semester 1</option>
                        <option value="10">Semester 2</option>
                        <option value="25">Semester 3</option>
                        <option value="50">Semester 4</option>
                        <option value="75">Semester 5</option>
                        <option value="100">Semester 6</option>
                    </select>
                </div>
            </div>
        </div>
        
    







        <!-- options section end-->

          
                
                    <div class="row mt-4">
                        <!-- Card 1 -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    Compulsory Papers
                                </div>
                                <div class="card-body py-2">
                                    <h5 class="card-title">Item List 1</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item">Item 1A</li>
                                        <li class="list-group-item">Item 1B</li>
                                        <li class="list-group-item">Item 1C</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    Optional Papers
                                </div>
                                <div class="card-body py-2">
                                    <h5 class="card-title">Item List 2</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item">Item 2A</li>
                                        <li class="list-group-item">Item 2B</li>
                                        <li class="list-group-item">Item 2C</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-danger text-white">

                                    Optional Papers
                                </div>
                                <div class="card-body py-2">
                                    <h5 class="card-title">Item List 3</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item">Item 3A</li>
                                        <li class="list-group-item">Item 3B</li>
                                        <li class="list-group-item">Item 3C</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
        <!-- END: Content-->

        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>

        <!-- BEGIN: Footer-->
        <footer class="footer footer-static footer-light">
            <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024
                    <a class="ml-25" href="#" target="_blank">Presence 360</a><span
                        class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>

            <div class="footerplogo"><img src="../../../assets/css/p-logo.png" /></div>
        </footer>
        <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
        <!-- END: Footer-->



        <div class="modal fade" id="reallocate" tabindex="-1" aria-labelledby="shareProjectTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header p-0 bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-4 mx-50 pb-2">
                        <h1 class="text-center mb-1" id="shareProjectTitle">Re-Allocate Incident</h1>
                        <p class="text-center">Enter the details below.</p>

                        <div class="row mt-2">

                            <div class="col-md-12 mb-1">
                                <label class="form-label">Re-Allocate To <span class="text-danger">*</span></label>
                                <select class="form-select select2">
                                    <option>Select</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-1">
                                <label class="form-label">Re-Allocate Dept. <span class="text-danger">*</span></label>
                                <select class="form-select select2">
                                    <option>Select</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-1">
                                <label class="form-label">PDC Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" placeholder="Enter Name" />
                            </div>

                            <div class="col-md-12 mb-1">
                                <label class="form-label">Remarks <span class="text-danger">*</span></label>
                                <textarea class="form-control"></textarea>
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
                        <button type="reset" class="btn btn-primary">Re-Allocate</button>
                    </div>
                </div>
            </div>
        </div>




        <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
            <div class="modal-dialog sidebar-sm">
                <form class="add-new-record modal-content pt-0">
                    <div class="modal-header mb-1">
                        <h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close">×</button>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <div class="mb-1">
                            <label class="form-label" for="fp-range">Select Date Range</label>
                            <input type="text" id="fp-range" class="form-control flatpickr-range"
                                placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Select Incident No.</label>
                            <select class="form-select select2">
                                <option>Select</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Select Customer</label>
                            <select class="form-select select2">
                                <option>Select</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Assigned To</label>
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
                                <option>Re-Allocatted</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="btn btn-primary data-submit mr-1">Apply</button>
                        <button type="reset" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        @endsection
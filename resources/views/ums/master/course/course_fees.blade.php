@extends('ums.admin.admin-meta')

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
                            <h2 class="content-header-title float-start mb-0">Course Fees</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item">Course Fees for DHAEMT (DHAEMT)</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0" data-bs-toggle="modal" data-bs-target="#addCourseFeesModal">
                            <i data-feather="plus"></i> Add Course Fees
                        </button>
                    </div>
                </div>
            </div>
    
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="table-responsive">
                                <table class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome">
                                    <thead>
                                        <tr>
                                            <th>ID#</th>
                                            <th>Fees Details</th>
                                            <th>Fees for Gen/OBC/SC/ST (Rs.)</th>
                                            <th>Fees for Disabled (Rs.)</th>
                                            <th>Semester</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>12345</td>
                                            <td>Theory</td>
                                            <td>BSc Computer Science</td>
                                            <td>2nd Semester</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Modal to add new record -->
                     <!-- Modal to add new record -->
<div class="modal fade" id="addCourseFeesModal" tabindex="-1" aria-labelledby="addCourseFeesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseFeesModalLabel">Add Course Fees</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCourseFeesForm">
                    <!-- Fees Type Dropdown -->
                    <div class="mb-2">
                        <label for="feesType" class="form-label">Fees Type</label>
                        <select class="form-select form-select-sm" id="feesType">
                            <option value="" disabled selected>Select Fees Type</option>
                            <option value="Tuition">Tuition</option>
                            <option value="Examination">Examination</option>
                            <option value="Lab">Lab</option>
                            <option value="Library">Library</option>
                        </select>
                    </div>
                    
                    <!-- Fees Details Dropdown -->
                    <div class="mb-2">
                        <label for="feesDetails" class="form-label">Fees Details</label>
                        <select class="form-select form-select-sm" id="feesDetails">
                            <option value="" disabled selected>Select Details</option>
                            <option value="Semester Fee">Semester Fee</option>
                            <option value="Yearly Fee">Yearly Fee</option>
                            <option value="Supplementary Fee">Supplementary Fee</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <!-- Fees Inputs -->
                    <div class="mb-2">
                        <label for="genFees" class="form-label">Fees for Gen/OBC/SC/ST (Rs.)</label>
                        <input type="number" class="form-control form-control-sm" id="genFees" placeholder="Enter fees">
                    </div>
                    <div class="mb-2">
                        <label for="disabledFees" class="form-label">Fees for Disabled (Rs.)</label>
                        <input type="number" class="form-control form-control-sm" id="disabledFees" placeholder="Enter fees">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" form="addCourseFeesForm">Save</button>
            </div>
        </div>
    </div>
</div>

            </section>
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


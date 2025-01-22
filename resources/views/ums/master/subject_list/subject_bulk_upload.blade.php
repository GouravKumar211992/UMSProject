@extends('admin.admin-meta')
@section('content')
    

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col=""> --}}
  

    <div class="app-content content ">
        <h4>Subject Bulk Data</h4>



        <div class="submitss text-end me-3">
           
            <button class="btn btn-primary btn-sm mb-50 mb-sm-0r " type="submit">
                <i data-feather="check-circle"></i> Submit
            </button>
            <button class="btn btn-warning btn-sm mb-50 mb-sm-0r " data-bs-toggle="modal" data-bs-target="#bulkUploadModal" >
                <i data-feather="refresh-cw"></i> Bulk Uplaod
            </button>
        </div>


        <div class="col-md-12">
            <div class="row align-items-center mb-1">
                <div class="col-md-4 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Compus <span class="text-danger ">*</span></label>
                    <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                        <option value="7">--Select--</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Course <span class="text-danger">*</span></label>
                    <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                        <option value="7">All</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="100">100</option>
                    </select>
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
        </div>







        <!-- options section end-->

        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">

            </div>
            <div class="content-body">

                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">


                                <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                        <thead>
                                            <tr>
                                                <th>SN#</th>
                                                <th>Campus</th>
                                                <th>Course</th>
                                                <th>Program</th>
                                                <th>Semester</th>
                                                <th>Stream</th>
                                                <th>Paper Code</th>
                                                <th>Paper Name</th>
                                                <th>Back Fees</th>
                                                <th>Scrutiny Fee</th>
                                                <th>Challenge Fee</th>
                                                <th>Status</th>
                                                <th>Subject Type</th>
                                                <th>Type</th>
                                                <th>Internal Maximum Mark</th>
                                                <th>Maximum Mark</th>
                                                <th>Oral</th>
                                                <th>Minimum Mark</th>
                                                <th>Credit</th>
                                                <th>Internal Marking Type</th>
                                                <th>Combined Subject Name</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                            <tr>
                                                <td>1</td>
                                                <td>Campus 1</td>
                                                <td>Course A</td>
                                                <td>Program 1</td>
                                                <td>Semester 1</td>
                                                <td>Stream X</td>
                                                <td>P123</td>
                                                <td>Mathematics</td>
                                                <td>$100</td>
                                                <td>$50</td>
                                                <td>$20</td>
                                                <td>Active</td>
                                                <td>Theory</td>
                                                <td>Full-time</td>
                                                <td>50</td>
                                                <td>100</td>
                                                <td>10</td>
                                                <td>40</td>
                                                <td>3</td>
                                                <td>Standard</td>
                                                <td>Subject A</td>
                                                <td>2025-01-15</td>
                                                <td class="tableactionnew">  
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{url('subject_list_edit')}}" >
                                                                <i data-feather="edit" class="me-50"></i>
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
                  
{{-- model  --}}
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
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
                        <input class="form-control" type="file" id="fileInput" name="file[]" accept=".csv, .xlsx, .xls" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Click Here To Download Format Of Excel File</label>
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

                </section>


            </div>
        </div>
    </div>
    <!-- END: Content-->
    @endsection
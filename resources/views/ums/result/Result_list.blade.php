@extends('ums.admin.admin-meta')
@section("content")
<!-- BEGIN: Body-->

{{-- <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
  
<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
             <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Result List</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Showing 1 to 10 of 0 category</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="content-header-right text-sm-end col-md-12 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right d-flex flex-wrap justify-content-start">
                        <button class="btn btn-primary btn-md mt-1 mb-1 me-2" data-bs-target="#filter" data-bs-toggle="modal">
                            <i data-feather="filter"></i> Filter
                        </button>  
                        <button class="btn btn-warning box-shadow-2 btn-md mt-1 mb-1 me-2" onclick="window.location.reload();">
                            <i data-feather="refresh-cw"></i> Reset
                        </button>
                        <a class="btn btn-dark btn-md mt-1 mb-1 me-2" href="#">
                            <i data-feather="file-text"></i> Bulk Approve
                        </a>
                        <button class="btn btn-warning btn-md mt-1 mb-1 me-1" data-bs-target="#" data-bs-toggle="modal">
                            Reset Bulk Download
                        </button> 
                        <button class="btn btn-primary btn-md mt-1 mb-1 me-2" data-bs-target="#filter" data-bs-toggle="modal">
                            Nursing Bulk Download
                        </button>  
                        <button class="btn btn-warning btn-md mt-1 mb-1 me-2" data-bs-target="#" data-bs-toggle="modal">
                            MBBS Results
                        </button> 
                        <a class="btn btn-dark btn-md mt-1 mb-1 me-2" href="#">
                            MD Results
                        </a>
                        <button class="btn btn-primary btn-md mt-1 mb-1 me-2" data-bs-target="#filter" data-bs-toggle="modal">
                            Universty TR Report
                        </button> 
                        <button class="btn btn-warning btn-md mt-1 mb-1 me-2" data-bs-target="#" data-bs-toggle="modal">
                            Internal/External/Practice Mark Submit
                        </button> 
                        <button class="btn btn-primary btn-md mt-1 mb-1 me-2" data-bs-target="#filter" data-bs-toggle="modal">
                            Result Analysis
                        </button>  
                        <a class="btn btn-dark btn-md mt-1 mb-1 me-2" href="#">
                            Tabulation Chart Report
                        </a>
                    </div>
                </div>
                
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
                                                          <th>id</th>
                                                          <th>Enrollment No.</th>
                                                          <th>Roll no</th>
                                                          <th>Course</th>
                                                          <th>Semester</th>
                                                          <th>Academic session</th>
                                                          <th>Student Name</th>
                                                          <th>Mobile Number</th>
                                                          <th>Result Status</th>
                                                          <th>Result Type</th>
                                                          <th>Status</th>
                                                          <th>Action</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>                                                                                                                                                                  
                                                      <tr>
                                                          <td>1</td>
                                                          <td class="fw-bolder text-dark">123456</td>
                                                          <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">123456</span></td>
                                                          <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">coding</span></td>
                                                          <td>First</td>
                                                          <td>2021-2022</td> 
                                                          <td>John</td> 
                                                          <td>7878787878</td> 
                                                          <td>nothing</td> 
                                                          <td>dfgh</td> 
                                                          <td><span class="badge rounded-pill badge-light-warning badgeborder-radius">Open</span></td>  
                                                          <td class="tableactionnew">  
                                                              <div class="dropdown">
                                                                  <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                      <i data-feather="more-vertical"></i>
                                                                  </button>
                                                                  <div class="dropdown-menu dropdown-menu-end">
                                                                      <a class="dropdown-item" href="edit">
                                                                          <i data-feather="edit" class="me-50"></i>
                                                                          <span>Edit</span>
                                                                      </a> 
                                                                      <a class="dropdown-item" href="incident-view.html">
                                                                          {{-- <i data-feather="eye" class="me-50"></i> --}}
                                                                          {{-- <span>View Detail</span> --}}
                                                                      </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                          {{-- <i data-feather="copy" class="me-50"></i>
                                                                          <span>Re-Allocate</span>
                                                                      </a> <a class="dropdown-item" href="#"> --}}
                                                                          <i data-feather="trash-2" class="me-50"></i>
                                                                          <span>Delete</span>
                                                                      </a>
                                                                  </div>
                                                              </div> 
                                                          </td>
                                                      </tr>
                                                      <tr>
                                                          <td>2</td>
                                                          <td class="fw-bolder text-dark">123456</td>
                                                          <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">123456</span></td>
                                                          <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">coding</span></td>
                                                          <td>First</td>
                                                          <td>2021-2022</td> 
                                                          <td>John</td> 
                                                          <td>7878787878</td> 
                                                          <td>nothing</td> 
                                                          <td>dfgh</td> 
                                                          <td><span class="badge rounded-pill badge-light-warning badgeborder-radius">Open</span></td>  
                                                          <td class="tableactionnew">  
                                                              <div class="dropdown">
                                                                  <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                      <i data-feather="more-vertical"></i>
                                                                  </button>
                                                                  <div class="dropdown-menu dropdown-menu-end">
                                                                      <a class="dropdown-item" href="edit">
                                                                          <i data-feather="edit" class="me-50"></i>
                                                                          <span>Edit</span>
                                                                      </a> 
                                                                      <a class="dropdown-item" href="#" onclick="window.confirm('Are you sure ? delete this data')">
                                                                          {{-- <i data-feather="eye" class="me-50"></i> --}}
                                                                          {{-- <span>View Detail</span> --}}
                                                                      {{-- </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate"> --}}
                                                                          {{-- <i data-feather="copy" class="me-50"></i>
                                                                          <span>Re-Allocate</span>
                                                                      </a> <a class="dropdown-item" href="#"> --}}
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
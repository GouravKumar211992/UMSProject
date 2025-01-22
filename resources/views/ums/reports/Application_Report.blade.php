@extends('admin.admin-meta')
@section("content")
<!-- END: Head-->

<!-- BEGIN: Body-->

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

     <!-- BEGIN: Header-->
   
     
     
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
     
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Application report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Incident List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                      <button class="btn btn-primary btn-sm mb-50 mb-sm-0 " data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
                      <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" onClick="window.location.reload()"><i data-feather="refresh-cw"></i>reset</button>
                      <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50"></i>remove pagination</button>
                      <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50">show sitting plan</button>
                      {{-- <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50">excel export</button> --}}
							<!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                            <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="incident-add.html"><i data-feather="file-text"></i>show multiple course </a>  
                    </div>
                    
                  </div>
                <div class="customernewsection-form poreportlistview p-1">
                  <div class="row"> 
                      <div class="col-md-2">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Campus:</label>
                              <select class="form-select select2">
                                  <option>Select</option> 
                              </select> 
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Program:</label>
                              <select class="form-select">
                                  <option>Select</option>
                                  <option>Raw Material</option>
                                  <option>Semi Finished</option>
                                  <option>Finished Goods</option>
                                  <option>Traded Item</option>
                                  <option>Asset</option>
                                  <option>Expense</option>
                              </select> 
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">COURSES:</label>
                              <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct mb-25" />
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Academic Session:</label>
                              <select class="form-select">
                                  <option>Select</option>
                                  <option>2021-2022</option>
                                  <option>2022-2023</option>
                                  <option>2023-2024</option>
                                  <option>2024-2025</option>
                              </select> 
                          </div>
                      </div> 
                      <div class="col-md-3">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">From Date:</label>
                              <input type="date" class="form-control" />
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">To Date:</label>
                              <input type="date" class="form-control" />
                          </div>
                      </div>
                      <div class="col-md-3">
                          <!-- Add buttons aligned in the same row -->
                          <div class="d-flex gap-1 mt-2">
                              <button class="btn btn-primary btn-sm" href="#">
                                  Get Report
                              </button>
                              <button class="btn btn-warning btn-sm" href="#">
                                  Remove Image
                              </button>
                          </div>
                      </div>
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
                                            <th class="text-left border border-light" rowspan="2">Sr.No</th>
                                            <th class="text-left border border-light" rowspan="2">Application No</th>
                                            <th class="text-left border border-light" rowspan="2">Entrance Roll Number</th>
                                            <th class="text-left border border-light" rowspan="2">Application Date</th>
                                            <th class="text-left border border-light" rowspan="2">Academic Session</th>
                                            <th class="text-left border border-light" rowspan="2">Campuse</th>
                                            <th class="text-left border border-light" rowspan="2">Course</th>
                                            <th class="text-left border border-light" rowspan="2">Name</th>
                                            <th class="text-left border border-light" rowspan="2">Adhar No</th>
                                            <th class="text-left border border-light" rowspan="2">DOB</th>
                                            <th class="text-left border border-light" rowspan="2">Email</th>
                                            <th class="text-left border border-light" rowspan="2">Contact</th>
                                            <th class="text-left border border-light" rowspan="2">Gender</th>
                                            <th class="text-left border border-light" rowspan="2">Category</th>
                                            <th class="text-left border border-light" rowspan="2">Cast Certificate Number</th>
                                            <th class="text-left border border-light" rowspan="2">DSMNRU Student?</th>
                                            <th class="text-left border border-light" rowspan="2">Enrollment Number<br>(if DSMNRU student)</th>
                                            <th class="text-left border border-light" rowspan="2">Father Name</th>
                                            <th class="text-left border border-light" rowspan="2">Father's Mobile Number</th>
                                            <th class="text-left border border-light" rowspan="2">Mother Name</th>
                                            <th class="text-left border border-light" rowspan="2">Mother's Mobile Number</th>
                                            <th class="text-left border border-light" rowspan="2">Religion</th>
                                            <th class="text-left border border-light" rowspan="2">Nationality</th>
                                            <th class="text-left border border-light" rowspan="2">Domicile</th>
                                            <th class="text-left border border-light" rowspan="2">Marital Status</th>
                                            <th class="text-left border border-light" rowspan="2">Disability</th>
                                            <th class="text-left border border-light" rowspan="2">Disability Category</th>
                                            <th class="text-left border border-light" rowspan="2">Percentage of Disability</th>
                                            <th class="text-left border border-light" rowspan="2">Disability UDID Number</th>
                                            <th class="text-left border border-light" rowspan="2">Blood Group</th>
                                      
                                            <!-- Educational Qualifications Header -->
                                            <th class="text-center border border-light" colspan="11">Educational Qualification(s) 1</th>
                                            <th class="text-center border border-light" colspan="11">Educational Qualification(s) 2</th>
                                            <th class="text-center border border-light" colspan="11">Educational Qualification(s) 3</th>
                                            <th class="text-center border border-light" colspan="11">Educational Qualification(s) 4</th>
                                            <th class="text-center border border-light" colspan="11">Educational Qualification(s) 5</th>
                                            <th class="text-center border border-light" colspan="11">Educational Qualification(s) 6</th>
                                      
                                            <th class="text-left border border-light" rowspan="2">Permanent Address</th>
                                            <th class="text-left border border-light" rowspan="2">Correspondence Address</th>
                                            <th class="text-left border border-light" rowspan="2">Dsmnru Employee</th>
                                            <th class="text-left border border-light" rowspan="2">DSMNRU Designation</th>
                                            <th class="text-left border border-light" rowspan="2">Dsmnru Employee Ward</th>
                                            <th class="text-left border border-light" rowspan="2">DSMNRU Employee Name</th>
                                            <th class="text-left border border-light" rowspan="2">DSMNRU Employee Relation</th>
                                            <th class="text-left border border-light" rowspan="2">Freedom Fighter</th>
                                            <th class="text-left border border-light" rowspan="2">NCC (C-Certificate)</th>
                                            <th class="text-left border border-light" rowspan="2">NSS (240 hrs and 1 camp)</th>
                                            <th class="text-left border border-light" rowspan="2">Sports</th>
                                            <th class="text-left border border-light" rowspan="2">Sport Level</th>
                                            <th class="text-left border border-light" rowspan="2">Hostel Facility</th>
                                            <th class="text-left border border-light" rowspan="2">How many years staying in DSMNRU Hostel</th>
                                            <th class="text-left border border-light" rowspan="2">Distance from your residence to University campus</th>
                                            <th class="text-left border border-light" rowspan="2">Payment Date</th>
                                            <th class="text-left border border-light" rowspan="2">Payment Amount</th>
                                            <th class="text-left border border-light" rowspan="2">Payment Transaction Number</th>
                                            <th class="text-left border border-light" rowspan="2">Action</th>
                                          </tr>
                                      
                                          <tr>
                                            <!-- Educational Qualification Details for 1 to 6 -->
                                            <th class="text-left border border-light">Name of Exam</th>
                                            <th class="text-left border border-light">Degree Name</th>
                                            <th class="text-left border border-light">Board</th>
                                            <th class="text-left border border-light">Passing Status</th>
                                            <th class="text-left border border-light">Passing Year</th>
                                            <th class="text-left border border-light">Mark Type</th>
                                            <th class="text-left border border-light">Total Marks / CGPA</th>
                                            <th class="text-left border border-light">Marks/CGPA Obtained</th>
                                            <th class="text-left border border-light">Equivalent Percentage</th>
                                            <th class="text-left border border-light">Subject</th>
                                            <th class="text-left border border-light">Roll Number</th>
                                          </tr>
                                      
                                          <tr>
                                            <!-- Educational Qualification Details for 2 to 6 -->
                                            <th class="text-left border border-light">Name of Exam</th>
                                            <th class="text-left border border-light">Degree Name</th>
                                            <th class="text-left border border-light">Board</th>
                                            <th class="text-left border border-light">Passing Status</th>
                                            <th class="text-left border border-light">Passing Year</th>
                                            <th class="text-left border border-light">Mark Type</th>
                                            <th class="text-left border border-light">Total Marks / CGPA</th>
                                            <th class="text-left border border-light">Marks/CGPA Obtained</th>
                                            <th class="text-left border border-light">Equivalent Percentage</th>
                                            <th class="text-left border border-light">Subject</th>
                                            <th class="text-left border border-light">Roll Number</th>
                                          </tr>
                                        </thead>
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

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a class="ml-25" href="#" target="_blank">Presence 360</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
        
        <div class="footerplogo"><img src="../../../assets/css/p-logo.png" /></div>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->
	
     
    
    <div class="modal fade" id="reallocate" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Select Date Range</label>
						  <input type="text" id="fp-range" class="form-control flatpickr-range" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
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
					<button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

    <!-- BEGIN: Vendor JS-->
    <!-- BEGIN: Vendor JS-->
  @endsection
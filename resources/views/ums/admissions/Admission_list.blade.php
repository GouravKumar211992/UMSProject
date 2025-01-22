@extends('ums.admin.admin-meta')
  
@section('content')
    

<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
             <div class="content-header row">
                <div class="content-header-left col-md-4 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Application Report</h2>
                            <div class="breadcrumb-wrapper">
                                
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="content-header-right text-sm-end col-md-8 mb-50 mb-sm-0">
                    <div class="d-flex justify-content-end gap-1"> 
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal">
                            <i data-feather="filter"></i> Filter
                        </button>
                        <button class="btn btn-danger btn-sm mb-50 mb-sm-0" href="#">
                            Remove Pagination
                        </button>
                        <button class="btn btn-success btn-sm mb-50 mb-sm-0" href="#">
                            Show Shitting Plan
                        </button>
                        <button class="btn btn-info btn-sm mb-50 mb-sm-0" href="#">
                            Show Multiple Courses Applied Data
                        </button>
                        <button class="btn btn-warning box-shadow-2 btn-sm mb-sm-0 mb-50" onclick="window.location.reload();">
                            <i data-feather="refresh-cw"></i> Reset
                        </button>
                    </div>
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
            

            <div class="content-body dasboardnewbody">
                 
                <!-- ChartJS section start -->
                <section id="chartjs-chart">
                    <div class="row">
						
						  
						
						<div class="col-md-12 col-12">
                            <div class="card  new-cardbox"> 
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
                                              <!-- Educational Qualification Details -->
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
                                            <!-- Sample Data Rows -->
                                            
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td class="text-left border border-light">1</td>
                                              <td class="text-left border border-light">APP11223</td>
                                              <td class="text-left border border-light">ER112233</td>
                                              <td class="text-left border border-light">12/03/2025</td>
                                              <td class="text-left border border-light">2024-2025</td>
                                              <td class="text-left border border-light">Main</td>
                                              <td class="text-left border border-light">M.A History</td>
                                              <td class="text-left border border-light">Alex Johnson</td>
                                              <td class="text-left border border-light">345678901234</td>
                                              <td class="text-left border border-light">22/07/1998</td>
                                              <td class="text-left border border-light">alex.johnson@example.com</td>
                                              <td class="text-left border border-light">3456789012</td>
                                              <td class="text-left border border-light">Male</td>
                                              <td class="text-left border border-light">General</td>
                                              <td class="text-left border border-light">1234</td>
                                              <td class="text-left border border-light">No</td>
                                              <td class="text-left border border-light">N/A</td>
                                              <td class="text-left border border-light">Michael Johnson</td>
                                              <td class="text-left border border-light">9876543210</td>
                                              <td class="text-left border border-light">Laura Johnson</td>
                                              <td class="text-left border border-light">9876543211</td>
                                              <td class="text-left border border-light">Christian</td>
                                              <td class="text-left border border-light">American</td>
                                              <td class="text-left border border-light">California</td>
                                              <td class="text-left border border-light">Single</td>
                                              <td class="text-left border border-light">No</td>
                                              <td class="text-left border border-light">N/A</td>
                                              <td class="text-left border border-light">No</td>
                                              <td class="text-left border border-light">A+</td>
                                          
                                              <!-- Educational Qualifications Details -->
                                              <td class="text-left border border-light">M.A</td>
                                              <td class="text-left border border-light">History</td>
                                              <td class="text-left border border-light">XYZ University</td>
                                              <td class="text-left border border-light">Passed</td>
                                              <td class="text-left border border-light">2023</td>
                                              <td class="text-left border border-light">CGPA</td>
                                              <td class="text-left border border-light">8.0</td>
                                              <td class="text-left border border-light">8.0</td>
                                              <td class="text-left border border-light">80%</td>
                                              <td class="text-left border border-light">History</td>
                                              <td class="text-left border border-light">112233445</td>
                                          
                                              <td class="text-left border border-light">B.A</td>
                                              <td class="text-left border border-light">Political Science</td>
                                              <td class="text-left border border-light">ABC University</td>
                                              <td class="text-left border border-light">Passed</td>
                                              <td class="text-left border border-light">2022</td>
                                              <td class="text-left border border-light">CGPA</td>
                                              <td class="text-left border border-light">7.5</td>
                                              <td class="text-left border border-light">7.5</td>
                                              <td class="text-left border border-light">75%</td>
                                              <td class="text-left border border-light">Political Science</td>
                                              <td class="text-left border border-light">223344556</td>
                                          
                                              <td class="text-left border border-light">Diploma</td>
                                              <td class="text-left border border-light">History & Culture</td>
                                              <td class="text-left border border-light">DEF Institute</td>
                                              <td class="text-left border border-light">Passed</td>
                                              <td class="text-left border border-light">2021</td>
                                              <td class="text-left border border-light">CGPA</td>
                                              <td class="text-left border border-light">6.5</td>
                                              <td class="text-left border border-light">6.5</td>
                                              <td class="text-left border border-light">65%</td>
                                              <td class="text-left border border-light">Cultural Studies</td>
                                              <td class="text-left border border-light">334455667</td>
                                          
                                              <!-- Additional Personal Information -->
                                              <td class="text-left border border-light">1234 Elm Street, City</td>
                                              <td class="text-left border border-light">5678 Oak Road, City</td>
                                              <td class="text-left border border-light">No</td>
                                              <td class="text-left border border-light">Assistant Professor</td>
                                              <td class="text-left border border-light">Ward 3</td>
                                              <td class="text-left border border-light">John Johnson</td>
                                              <td class="text-left border border-light">Father</td>
                                              <td class="text-left border border-light">No</td>
                                              <td class="text-left border border-light">No</td>
                                              <td class="text-left border border-light">Yes</td>
                                              <td class="text-left border border-light">District Level</td>
                                              <td class="text-left border border-light">No</td>
                                              <td class="text-left border border-light">3</td>
                                              <td class="text-left border border-light">20 km</td>
                                              <td class="text-left border border-light">17/03/2025</td>
                                              <td class="text-left border border-light">2000</td>
                                              <td class="text-left border border-light">TXN334455</td>
                                              <td class="text-left border border-light">Approve</td>
                                            </tr>
                                          </tbody>
                                          
                                          
                                    </table>
                                </div>
								
								  
                            
                                
								 
								 
                                
                          </div>
                        </div>
						
						
						 
                         
                         
                    </div>
					
					 
                     
                </section>
                <!-- ChartJS section end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    @endsection
 
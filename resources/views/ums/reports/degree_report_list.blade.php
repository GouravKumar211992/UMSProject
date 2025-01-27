
@extends('ums.admin.admin-meta')

@section('content')
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}


    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
              
                <div class="content-header-left col-md-5 mb-2">
                  
                    <div class="row breadcrumbs-top">
                      
                        <div class="col-12 d-flex justify-content-between mb-1 align-items-center">
                          
                          <div class="breadcrumb-wrapper">
                              <h2 class="content-header-title float-start mb-0">Degree Report</h2>
                              
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Report List</li>
                                </ol>
                            </div>

                        </div>
                        
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                      <div class="form-group breadcrumb-right mt-2"> 
                        <button  class="btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><i data-feather="check-circle" ></i>Get Report</button>
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light">Remove Image</button>
                                <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50 waves-effect waves-float waves-light" onClick="window.location.reload()"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>  Reset</button>                    
                              </div>
							<!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                    </div>
                </div>
            </div>
            <div class="content-body">
            <div class="row mb-2">
            <div class="col-md-3">
                    <span style="color: black;">Campus Name:</span>
                    <select name="campus" id="campus" class="form-control" required="">
                        <option value="">--Select Session--</option>
                                                    <option value="1">Dr. Shakuntala Misra National Rehabilitation University</option>
                                                    <option value="2">Kalyanam Karoti, Mathura</option>
                                                    <option value="3">Nai Subah,Village-Khanav, Post-Bachhav, Varanasi</option>
                                                    <option value="4">T.S. Misra Medical College and Hospital, Lucknow</option>
                                                    <option value="5">Hind Mahavidyalaya, Barabanki</option>
                                                    <option value="6">T.S. Misra College of Nursing, Lucknow</option>
                                                    <option value="8">K.S. Memorial College for Research &amp; Rehabilitation, Raebareli</option>
                                                    <option value="9">GRAMODYOG SEWA SANSTHAN</option>
                                                    <option value="13">PRAMILA KATIYAR SPECIAL EDUCATION INSTITUTE</option>
                                                    <option value="17">MAA BALIRAJI SEWA SANSTHAN(MIRZAPUR)</option>
                                                    <option value="18">MAA BALIRAJI SEWA SANSTHAN(ALLAHABAD)</option>
                                                    <option value="20">Handicapped Development Council, Sikandara, Agra</option>
                                                    <option value="21">JAY NAND SPECIAL TEACHERS’ TRAINING INSTITUTE, AYODHYA </option>
                                                    <option value="23">T. S. Misra College of Paramedical Sciences, Lucknow</option>
                                                    <option value="24">Sarveshwari Shikshan Sansthan, Kausambi</option>
                                                    <option value="25">SHRI CHANDRABHAN SINGH DEGREE COLLEGE</option>
                                                    <option value="26">MAHARISHI DAYANAND REHABILITATION INSTITUTE</option>
                                                    <option value="27">PRAKASH KIRAN EDUCATIONAL INSTITUTION</option>
                                                    <option value="28">Dr. RPS INSTITUTE OF EDUCATION</option>
                                                    <option value="29">SOCIETY FOR INSTITUTE OF PSYCHOLOGICAL RESEARCH &amp; HEALTH</option>
                                            </select>
                </div>
                <div class="col-sm-3">
            <span style="color: black;">Courses:</span>
            <select data-live-search="true" name="course_id" id="course_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onchange="return $('#form_data').submit();">
                <option value="">--Choose Course--</option>
                            </select>
        </div>
        <div class="col-sm-2">
                    <span style="color: black;">Session:</span>
                    <select data-live-search="true" name="session" id="session" class="form-control">
                        <option value="">--Select Session--</option>
                                                    <option value="2023-2024AUG">2023-2024AUG</option>
                                                    <option value="2023-2024JUL">2023-2024JUL</option>
                                                    <option value="2023-2024">2023-2024</option>
                                                    <option value="2022-2023">2022-2023</option>
                                                    <option value="2021-2022">2021-2022</option>
                                                    <option value="2020-2021">2020-2021</option>
                                                    <option value="2019-2020">2019-2020</option>
                                                    <option value="2018-2019">2018-2019</option>
                                                    <option value="2017-2018">2017-2018</option>
                                                    <option value="2016-2017">2016-2017</option>
                                                    <option value="2015-2016">2015-2016</option>
                                                    <option value="2014-2015">2014-2015</option>
                                            </select>
                </div>
                <div class="col-sm-2">
                    <span style="color: black;">Result Type:</span>
                    <select name="back_status_text" id="back_status_text" class="form-control">
                        <option value="REGULAR">REGULAR</option>
                        <option value="BACK">BACK</option>
                        <option value="SPLBACK">SPLBACK</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <span style="color: black;">Status:</span>
                    <select name="result_type" id="result_type" class="form-control">
                        <option value="">--Select Type--</option>
                        <option value="new">New</option>
                        <option value="old">Old</option>
                    </select>
                </div>
               
</div>

				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            <thead>
                                                <tr>
                                                    <th>SERIAL</th>
                                                    <th>ROLLNO</th>
                                                    <th>UNIVERSITY / COLLEGE NAME</th>
                                                    <th>COURSE NAME</th>
                                                    <th>ENROLLMENT</th>
                                                    <th>NAME</th>
                                                    <th>HINDI NAME</th>
                                                    <th>FATHERS NAME</th>
                                                    <th>MOTHERS NAME</th>
                                                    <th>SESSION</th>
                                                    <th>PHOTO PATH</th>
                                                    <th>CATEGORY</th>
                                                    <th>DISABLED</th>
                                                    <th>GENDER</th>
                                                    <th>ADDRESS	</th>
                                                    <th>SEMESTER</th>
                                                    <th>CONTACT NO.</th>
                                                    <th>ALTERNATE CONTACT NO.</th>
                                                    <th>LATERAL</th>
                                                    <th>BACK IN SEM / YEAR</th>
                                                    <th>TOTAL OBT. MARKS</th>
                                                    <th>TOTAL MAX MARKS</th>
                                                    <th>TOTAL QP</th>
                                                    <th>TOTAL CREDIT</th>
                                                    <th>CGPA</th>
                                                    <th>ELIGIBLE</th>
                                                    <th>PERCENTAGE</th>
                                                    <th>UG / PG</th>
                                                    <th>Image</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
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

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
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

   
{{-- </body> --}}
@endsection

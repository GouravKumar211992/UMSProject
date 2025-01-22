@extends("ums.admin.admin-meta")
@section("content")


<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
             <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Admit Card List</h2>
                            <div class="breadcrumb-wrapper">
                              
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">List of Admins</li>
                                </ol>
                            
                            </div>
                        </div>
                    </div>
                </div> 
				 <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                      <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="/Bulk_Admit_Card_Approval"><i data-feather="file-text"></i> Bulk Approve</a>  
                      <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
                    <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" onclick="window.location.reload();"><i data-feather="refresh-cw"></i> Reset</button>   
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
                                                <th>#ID</th>
                                                <th>Enrollment No.</th>
                                                <th>Roll No.</th>
                                                <th>Course</th>
                                                <th>Semester</th>
                                                <th>Student Name</th>
                                                <th>Mobile No.</th>
                                                <th>Mailing Address</th>
                                                <th>status</th>
                                                <th>Form Type</th>
                                                <th>Created At </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td class="fw-bolder text-dark">HL/2024/001</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Nishu Garg</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Home</td> 
                                                <td><span class="badge rounded-pill badge-light-danger badgeborder-radius">Pending</span></td> 
                                                <td>regular</td>
                                                <td>20-07-2024</td>
                                                <td class="tableactionnew">  
                                                  <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">                                                          <i data-feather="more-vertical"></i>
                                                      </button>
                                                      <div class="dropdown-menu dropdown-menu-end">
                                                          <a class="dropdown-item" href="admit_card_edit">
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
                                            <tr>
                                                <td>2</td>
                                                <td class="fw-bolder text-dark">HL/2024/001</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Kundan Kumar</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Term</td> 
                                                <td><span class="badge rounded-pill badge-light-danger badgeborder-radius">Pending</span></td> 
                                                <td>regular</td>
                                                <td>20-07-2024</td>
                                                <td class="tableactionnew">  
                                                  <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">                                                          <i data-feather="more-vertical"></i>
                                                      </button>
                                                      <div class="dropdown-menu dropdown-menu-end">
                                                          <a class="dropdown-item">
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
                                            <tr>
                                                <td>3</td>
                                                <td class="fw-bolder text-dark">HL/2024/001</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Rahul Upadhyay</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Vehicle</td> 
                                                <td><span class="badge rounded-pill badge-light-danger badgeborder-radius">Pending</span></td> 
                                                <td>regular</td>
                                                <td>20-07-2024</td>
                                                <td class="tableactionnew">  
                                                  <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">                                                          <i data-feather="more-vertical"></i>
                                                      </button>
                                                      <div class="dropdown-menu dropdown-menu-end">
                                                          <a class="dropdown-item">
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
                                            <tr>
                                                <td>4</td>
                                                <td class="fw-bolder text-dark">HL/2024/001</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Ashish Kumar</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Home</td> 
                                                <td><span class="badge rounded-pill badge-light-danger badgeborder-radius">Pending</span></td> 
                                                <td>regular</td>
                                                <td>20-07-2024</td>
                                                <td class="tableactionnew">  
                                                  <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">                                                          <i data-feather="more-vertical"></i>
                                                      </button>
                                                      <div class="dropdown-menu dropdown-menu-end">
                                                          <a class="dropdown-item">
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
                                            <tr>
                                                <td>5</td>
                                                <td class="fw-bolder text-dark">HL/2024/001</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Inder Singh</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Term</td> 
                                                <td><span class="badge rounded-pill badge-light-danger badgeborder-radius">Pending</span></td> 
                                                <td>regular</td>
                                                <td>20-07-2024</td>
                                                <td class="tableactionnew">  
                                                  <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">                                                          <i data-feather="more-vertical"></i>
                                                      </button>
                                                      <div class="dropdown-menu dropdown-menu-end">
                                                          <a class="dropdown-item">
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
					
					 
                     
                </section>
                <!-- ChartJS section end -->

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

@endsection
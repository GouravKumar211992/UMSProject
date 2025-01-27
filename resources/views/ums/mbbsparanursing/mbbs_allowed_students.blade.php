@extends("ums.admin.admin-meta")
@section('content')
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
  
    <div class="app-content content ">
        <h4>Mbbs Allowed Students</h4>

        <form method="get">
        <div class="row mb-3">
        <div class="col-md-3">
            <span style="color: black;">Select Course :</span>
            <select data-live-search="true" name="course" id="course" style="border-color: #c0c0c0;" class="form-control js-example-basic-single ">
               <option value="">--Choose Course--</option>
                                         
                      <option value="49">MBBS</option>
                                             
                      <option value="64">B.Sc. (Nursing)</option>
                                             
                      <option value="95">BPT</option>
                                             
                      <option value="96">BMLT</option>
                                             
                      <option value="131">M.D.-PATHOLOGY</option>
                                             
                      <option value="132">M.D.-COMMUNITY MEDICINE</option>
                                            </select>
                    <span class="text-danger"></span>
            </div>
           
            <div class="col-md-2 ms-0 submit text-end">
            <span></span><br>
        <button  class=" btn btn-primary btn-sm mb-50 mb-sm-0r waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Submit</button>
</div>
            
		 
        </div>
    </form>

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
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>  
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
								   
                                <div class="table-responsive mb-3">
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            <thead>
                                                <tr>
                                                    <th>Sr.No.</th>
                                                    <th>Roll No.</th>
                                                    <th>Mobile No.</th>
                                                    <th>DOB</th>
                                                    <th>Regular </th>
                                                    <th>Supplementary </th>
                                                    <th>Challenge</th>
                                                    <th>Scrutiny</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td class="fw-bolder text-dark">Description will come here</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Shivangi</span></td>
                                                    <td>05-Sep-2024</td>
                                                    <td>05-Sep-2024</td> 
                                                    <td>05-Sep-2024</td> 
                                                    <td>Description will come here</td> 
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
                                                                <a class="dropdown-item" href="#" onclick="window.confirm('Are you sure ? you want to delete this data')">
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
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Shivangi</span></td>
                                                    <td>VMM Pvt Ltd</td>
                                                    <td>Description will come here</td> 
                                                    <td>Description will come here</td> 
                                                    <td>Open</td> 
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
                                                                <a class="dropdown-item" href="#" onclick="window.confirm('Are you sure ? you want to delete this data')">
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


@endsection
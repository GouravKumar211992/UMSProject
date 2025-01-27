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
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Customizing Course</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Home</a></li>  
                                    <li class="breadcrumb-item active">Course</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                        
                        <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> --> 
                        <a class="btn  btn-secondary  btn-sm mb-50 mb-sm-0" href="#">
                            <i data-feather="arrow-left"></i>Go Back
                        </a>  
                        <a class="btn  btn-primary  btn-sm mb-50 mb-sm-0" href="#">
                            <i data-feather="user-plus"></i>Update
                        </a>  
                    </div>
                </div>
                
            </div>
            <div class="content-body ">
                <div class="col-md-12 bg-white p-2 rounded shadow-sm">
                    <form>
                        <div class="row">
                            <!-- Course Name -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Course Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="B.Ed. S.E. (VI)">
                                </div>
                            </div>
        
                            <!-- Course Code -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Course Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="24SS">
                                </div>
                            </div>
        
                            <!-- Category -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Category <span class="text-danger">*</span></label>
                                    <select class="form-select">
                                        <option value="">Professional</option>
                                    </select>
                                </div>
                            </div>
        
                            <!-- Campus -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger">*</span></label>
                                    <select class="form-select">
                                        <option value="">SOCIETY FOR INSTITUTE OF PSYCHOLOGICAL RESEARCH & HEALTH</option>
                                    </select>
                                </div>
                            </div>
        
                            <!-- Course Description -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Course Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="3" placeholder="B.Ed. Special Education (Visual Impairment)"></textarea>
                                </div>
                            </div>
        
                            <!-- Total Number of Semesters -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Total Number of Semesters <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" placeholder="4">
                                </div>
                            </div>
        
                            <!-- Qualification Required -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Qualification Required <span class="text-danger">*</span></label>
                                    <select class="form-select">
                                        <option value="">10th Or Equivalent, 10+2th Or Equivalent, Graduation</option>
                                    </select>
                                </div>
                            </div>
        
                            <!-- Course Group for Admission -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Course Group for Admission <span class="text-danger">*</span></label>
                                    <select class="form-select">
                                        <option value="">Nothing Selected</option>
                                    </select>
                                </div>
                            </div>
        
                            <!-- Entrance Exam Roll Number -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Entrance Exam Roll Number Prefix <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
        
                            <!-- CUET Required -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">CUET Required? <span class="text-danger">*</span></label>
                                    <select class="form-select">
                                        <option value="">No</option>
                                    </select>
                                </div>
                            </div>
        
                            <!-- Visible in Application -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Visible in Application <span class="text-danger">*</span></label>
                                    <select class="form-select">
                                        <option value="">Visible</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>   
 </div>
</div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    
  <!-- BEGIN: Footer-->
  <footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">COPYRIGHT © 2022<a class="ms-25" href="#" target="_blank">Staqo Presence</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
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

    
{{-- </body> --}}
@endsection

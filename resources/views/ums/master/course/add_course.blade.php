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
                            <h2 class="content-header-title float-start mb-0">Add New Course</h2>
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
                        <a class="btn  btn-primary  btn-sm mb-50 mb-sm-0" href="#">
                            <i data-feather="user-plus"></i>Submit
                        </a>  
                    </div>
                </div>
                
            </div>
            <div class="content-body mt-3">
                <div class="col-md-12 bg-white p-4 rounded shadow-sm">
                    <div class="row align-items-center mb-3">
                        
                        <!-- Course Name -->
                        <div class="col-md-4 d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2 col-3">Course Name: <span class="text-danger">*</span></label>
                            <input type="text" name="course_name" class="form-control" placeholder="Enter course name">
                        </div>
            
                        <!-- Course Code -->
                        <div class="col-md-4 d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2 col-3">Course Code: <span class="text-danger">*</span></label>
                            <input type="text" name="course_code" class="form-control" placeholder="Enter course code">
                        </div>
            
                        <!-- Category -->
                        <div class="col-md-4 d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2 col-3">Category: <span class="text-danger">*</span></label>
                            <select name="category" class="form-select">
                                <option value="" disabled selected>-----Select-----</option>
                                <option value="10">Category 1</option>
                                <option value="25">Category 2</option>
                                <option value="50">Category 3</option>
                                <option value="75">Category 4</option>
                                <option value="100">Category 5</option>
                            </select>
                        </div>
            
                        <!-- Campus -->
                        <div class="col-md-4 d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2 col-3">Campus: <span class="text-danger">*</span></label>
                            <select name="campus" class="form-select">
                                <option value="" disabled selected>-----Select-----</option>
                                <option value="10">Campus 1</option>
                                <option value="25">Campus 2</option>
                                <option value="50">Campus 3</option>
                                <option value="75">Campus 4</option>
                                <option value="100">Campus 5</option>
                            </select>
                        </div>
            
                        <!-- Semester Type -->
                        <div class="col-md-4 d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2 col-3">Semester Type: <span class="text-danger">*</span></label>
                            <select name="semester_type" class="form-select">
                                <option value="" disabled selected>-----Select-----</option>
                                <option value="10">2023-2024AUG</option>
                                <option value="25">2023-2024SEP</option>
                                <option value="50">2023-2024OCT</option>
                            </select>
                        </div>
            
                        <!-- Description -->
                        <div class="col-md-4 d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2 col-3">Description: <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Enter course description"></textarea>
                        </div>
            
                        <!-- Total Number of Semesters -->
                        <div class="col-md-4 d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2 col-3">Total Number of Semesters: <span class="text-danger">*</span></label>
                            <input type="number" name="total_semesters" class="form-control" min="1" step="1" placeholder="Enter number of semesters">
                        </div>
            
                        <!-- Required Qualification -->
                        <div class="col-md-4 d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2 col-3">Required Qualification: <span class="text-danger">*</span></label>
                            <select name="qualification" class="form-select">
                                <option value="" disabled selected>-----Select-----</option>
                                <option value="10">Qualification 1</option>
                                <option value="25">Qualification 2</option>
                                <option value="50">Qualification 3</option>
                            </select>
                        </div>
            
                        <!-- CUET Required -->
                        <div class="col-md-4 d-flex align-items-center mb-2">
                            <label class="form-label mb-0 me-2 col-3">CUET Required: <span class="text-danger">*</span></label>
                            <select name="cuet_required" class="form-select">
                                <option value="" disabled selected>-----Select-----</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
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


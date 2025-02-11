@extends("ums.admin.admin-meta")
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
                            <h2 class="content-header-title float-start mb-0">Result List</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Showing 1 to 100 of 2544 category</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
							<button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
							<!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                            <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" onclick="window.location.reload();"><i data-feather="refresh-cw"></i>Reset</button>
                            <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="bulk_result"><i data-feather="file-text"></i>Bulk Result</a>  
                    </div>
                </div>
            </div>
            <div class="content-body">
    <section id="basic-datatable" class="mb-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
    <thead>
        <tr>
            <th>ID#</th>
            <th>Enrollment No.</th>
            <th>Roll No.</th>
            <th>Semester</th>
            <th>Academic Year</th>
            <th>Student Name</th>
            <th>Mobile Number</th>
            <th>Result Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($results) && count($results) > 0)
            @foreach($results as $index => $result)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $result->enrollment_no }}</td>
                    <td>{{ $result->roll_no }}</td>
                    <td>{{ optional($result->semester_details)->name ?? '-' }}</td>
                    <td>{{ $result->exam_session }}</td>
                    <td>{{ optional($result->student)->full_name ?? '-' }}</td>
                    <td>{{ optional($result->student)->mobile ?? '-' }}</td>
                    <td>{{ $result->status_text }}</td>
                    <td class="tableactionnew">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown">
                                <i data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- View Button -->
                                <a class="dropdown-item" href="{{ url('/all_mbbs_result/view/' . $result->id) }}">
                                    <i data-feather="eye" class="me-50"></i>
                                    <span>View</span>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9" class="text-center">No data found</td>
            </tr>
        @endif
    </tbody>
</table>
                        <br>
                     
                    </div>
                </div>
            </div>
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
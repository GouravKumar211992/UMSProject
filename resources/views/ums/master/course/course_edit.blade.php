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
                        <button type="submit" form="edit_course_form" class="btn  btn-primary  btn-sm mb-50 mb-sm-0" href="#">
                            <i data-feather="user-plus"></i>Update
                        </button>  
                    </div>
                </div>
                
            </div>
            <form id="edit_course_form" method="POST" action="{{route('course_list_update')}}">
            	@csrf
                @method('PUT')
               

		<input type="hidden" name="course_id" value="{{$selected_course->id}}">
		<input type="hidden" name="course_status" id="course_status" value="">
            <div class="content-body ">
                <div class="col-md-12 bg-white p-2 rounded shadow-sm">
                    <form>
                        <div class="row">
                            <!-- Course Name -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Course Name <span class="text-danger">*</span></label>
                                    <input type="text" name="course_name" class="form-control" value="{{$selected_course->name}}" placeholder="Enter here">
                                </div>
                            </div>
        
                            <!-- Course Code -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Course Code <span class="text-danger">*</span></label>
                                    <input type="text" value="{{$selected_course->color_code}}" name="color_code" class="form-control" placeholder="24SS">
                                </div>
                            </div>
        
                            <!-- Category -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" name="category_id">
                                        @foreach ($categorylist as $category)
							<option value="{{$category->id}}" @if($selected_course->category_id == $category->id) selected @endif>{{ ucfirst($category->name) }}</option>
							@endforeach
                                    </select>
                                </div>
                            </div>
        
                            <!-- Campus -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger">*</span></label>
                                    <select class="form-select" name="campus_id">
                                        <option value="">Please select</option>
                                        @foreach ($campuslist as $campus)
                                        <option value="{{$campus->id}}" {{ ($selected_course->id && ($selected_course->campus_id == $campus->id)) ? 'selected':'' }}>{{ ucfirst($campus->name) }}</option>
                                        <option value="{{$campus->id}}">{{ ucfirst($campus->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
        
                            <!-- Course Description -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Course Description <span class="text-danger">*</span></label>
                                    <textarea name="course_description" class="form-control" rows="3" {{$selected_course->course_description}} placeholder="B.Ed. Special Education (Visual Impairment)"></textarea>
                                </div>
                            </div>
        
                            <!-- Total Number of Semesters -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Total Number of Semesters <span class="text-danger">*</span></label>
                                    <input type="number"  name="total_semester_number" value="{{$selected_course->total_semester_number}}" class="form-control" placeholder="4">
                                </div>
                            </div>
        
                            <!-- Qualification Required -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Qualification Required <span class="text-danger">*</span></label>
                                    @php
						$required_qualification_array = explode(',',$selected_course->required_qualification);
						@endphp
                                    <select class="form-select" name="required_qualification_data[]">
                                        <option value="">Please select</option>
                                        @foreach ($required_qualification as $rq)
							<option value="{{$rq->id}}" @if(in_array($rq->id, $required_qualification_array)) selected @endif>{{ ucfirst($rq->name) }}</option>
							@endforeach
                                    </select>
                                </div>
                            </div>
        
                            <!-- Course Group for Admission -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Course Group for Admission <span class="text-danger">*</span></label>
                                    @php
						$course_group_array = explode(',',$selected_course->course_group);
						@endphp
                                    <select class="form-select" name="course_group[]">
                                        <option value="">Please select</option>
                                        @foreach ($courses as $course)
                                        <option value="{{$course->id}}" @if(in_array($course->id, $course_group_array)) selected @endif>{{ ucfirst($course->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
        
                            <!-- Entrance Exam Roll Number -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label for="name" class="form-label mb-0 me-2 col-3">Entrance Exam Roll Number Prefix <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  name="roll_number" value="{{$selected_course->roll_number}}">
                                    <span class="text-danger">{{ $errors->first('roll_number') }}</span>
                                    
                                </div>
                            </div>
        
                            <!-- CUET Required -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">CUET Required? <span class="text-danger">*</span></label>
                                    <select class="form-select" name="cuet_status" id="cuet_status">
                                        <option value="">Please select</option>
							<option value="Yes" @if($selected_course->cuet_status=='Yes') selected @endif>Yes</option>
							<option value="No" @if($selected_course->cuet_status=='No') selected @endif>No</option>
                                    </select>
                                </div>
                            </div>
        
                            <!-- Visible in Application -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">Visible in Application <span class="text-danger">*</span></label>
                                    <select class="form-select" name="visible_in_application">
                                        <option value="1" @if($selected_course->visible_in_application == 1) selected @endif>Visible</option>
								<option value="0" @if($selected_course->visible_in_application == 0) selected @endif>Not Visible</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>  
            </form> 
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
<script>
	$(document).ready(function() {

		var selected_course = {
			{
				!!json_encode($selected_course) !!
			}
		};
	});
</script>

<script>
	function submitCat(form) {
		document.getElementById('edit_course_form').submit();
	}
	// $('.alphaOnly').keyup(function() {
	// 		this.value = this.value.replace(/[^a-z|A-Z\.]/g, '');
	// 	});
		$('.alphanumberOnly').keyup(function() {
			this.value = this.value.replace(/[^a-z|A-Z|0-9\.]/g, '');
		});
		function disableButton() {
        var btn = document.getElementById('btn');
        btn.disabled = true;
        btn.innerText = 'Submitting...'
    }
</script>
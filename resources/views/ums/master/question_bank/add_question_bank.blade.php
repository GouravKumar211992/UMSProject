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
                            <h2 class="content-header-title float-start mb-0">Question Bank</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <form id="cat_form" method="POST" action="{{route('question-bank-save')}}"  enctype="multipart/form-data">

                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active"> Add Question Bank</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button type="submit" form="cat_form" class=" btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Create </button>
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>
                            Reset</button> 

                    </div>
                </div>
            </div>

            <div class="content-body bg-white p-4 shadow">

                                 
        <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3"> Campus Name<span class="text-danger ">*</span></label>
                <select class="form-control selectpicker-back campus_id" id="campus_id" name="campus_id" required>
                    <option value="">--Select Campus--</option>
                    @foreach($campuses as $campuse)
                    <option value="{{$campuse->id}}" {{(old('campus_id')=="$campuse->id")? 'selected': ''}} >{{$campuse->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('campus_id'))
                    <span class="text-danger">{{ $errors->first('campus_id') }}</span>
                @endif
                    </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Program Name <span class="text-danger">*</span></label>
                <select class="form-control selectpicker" id="program_id" name="program_id" required>
                    <option value="">Select Program</option>
                    @foreach($programs as $program)
                     <option value="{{$program->id}}" {{(old('program')=="$program->id")? 'selected': ''}}>{{$program->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('program_id'))
                    <span class="text-danger">{{ $errors->first('program_id') }}</span>
                @endif
                </div>
                

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Course Name <span class="text-danger">*</span></label>
                <select class="form-control selectpicker-back" id="course_id" name="course_id" required>
                    <option value="">Select Course</option>
                </select>
                @if($errors->has('course_id'))
                    <span class="text-danger">{{ $errors->first('course_id') }}</span>
                @endif
              </div>

             
              <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Branch <span class="text-danger">*</span></label>
                <select class="form-control selectpicker-back" id="branch_id" name="branch_id" required>
                    <option value="">Select Branch</option>
                </select>
                @if($errors->has('branch_id'))
                    <span class="text-danger">{{ $errors->first('branch_id') }}</span>
                @endif
              </div>
        </div>
    </div>

        <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3"> Semester Name<span class="text-danger ">*</span></label>
                <select class="form-control selectpicker-back" id="semester_id" name="semester_id" required>
                    <option value="">Select Semester</option>
                </select>
                @if($errors->has('semester_id'))
                    <span class="text-danger">{{ $errors->first('semester_id') }}</span>
                @endif 
                    </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Subject Code <span class="text-danger">*</span></label>
                <select class="form-control selectpicker-back" id="sub_code" name="sub_code" required>
                    <option value="">Select Subject Code</option>
                </select>
                @if($errors->has('sub_code'))
                    <span class="text-danger">{{ $errors->first('sub_code') }}</span>
                @endif
                </div>
                

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Session <span class="text-danger">*</span></label>
                <select class="form-control selectpicker-back" id="session" name="session" required>
                    <option value="">Select Session</option>
                    @foreach($sessions as $session)
                    
                    <option value="{{$session->academic_session}}" {{(old('session')=="$session->id")? 'selected': ''}} >{{$session->academic_session}}</option>
                    @endforeach
                </select>
                @if($errors->has('session'))
                    <span class="text-danger">{{ $errors->first('session') }}</span>
                @endif
              </div>
              <div class="col-md-3 d-flex align-items-center" hidden>
                <label hidden class="form-label mb-0 me-2 col-3"> Title/Topic <span class="text-danger" hidden>*</span></label>
                <input  hidden type="text" name="phd_title" class="form-control">
                @if($errors->has('phd_title'))
                    <span class="text-danger">{{ $errors->first('phd_title') }}</span>
                @endif
              </div>
              <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3 mt-2"> File<span class="text-danger ">*</span></label>
                <input type="file" class="form-control selectpicker-back mt-2" name="question_bank_file" accept="application/pdf" required>
                @if($errors->has('session'))
                    <span class="text-danger">{{ $errors->first('session') }}</span>
                @endif
                 </div>
             
        </div>
       
    </div>

        <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center" hidden>
                <label hidden class="form-label mb-0 me-2 col-3"> Synopsis<span class="text-danger ">*</span></label>
                <input type="file" class="form-control selectpicker-back" name="synopsis_file" accept="application/pdf" hidden>
                @if($errors->has('synopsis_file'))
                    <span class="text-danger">{{ $errors->first('synopsis_file') }}</span>
                @endif
                 </div>

            <div class="col-md-3 d-flex align-items-center">
                <label hidden class="form-label mb-0 me-2 col-3">Thysis<span class="text-danger">*</span></label>
                <input type="file" class="form-control selectpicker-back" name="thysis_file" accept="application/pdf" hidden>
                @if($errors->has('thysis_file'))
                    <span class="text-danger">{{ $errors->first('thysis_file') }}</span>
                @endif
                </div>
                

            <div class="col-md-3 d-flex align-items-center">
                <label hidden class="form-label mb-0 me-2 col-3">Journal Paper <span class="text-danger">*</span></label>
                <input type="file" class="form-control selectpicker-back" name="journal_paper_file" accept="application/pdf" hidden>
										@if($errors->has('journal_paper_file'))
											<span class="text-danger">{{ $errors->first('journal_paper_file') }}</span>
										@endif
              </div>

              <div class="col-md-3 d-flex align-items-center">
                <label hidden class="form-label mb-0 me-2 col-3"> Seminar<span class="text-danger ">*</span></label>
                <input type="file" class="form-control selectpicker-back" name="seminar_file" accept="application/pdf" hidden>
                @if($errors->has('seminar_file'))
                    <span class="text-danger">{{ $errors->first('seminar_file') }}</span>
                @endif
                 </div>
        </div>

      
    </div>
    </div>
</form>

            </div>		
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
											   
									
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
	
	 
    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
		<div class="modal-dialog sidebar-sm">
			<form class="add-new-record modal-content pt-0"> 
				<div class="modal-header mb-1">
					<h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Select Date</label>
<!--                        <input type="text" id="fp-default" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />-->
						  <input type="text" id="fp-range" class="form-control flatpickr-range bg-white" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
					</div>
					
					<div class="mb-1">
						<label class="form-label">PO No.</label>
						<select class="form-select">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Vendor Name</label>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
        
    <script>
        function submitCourse(form) {
    
            document.getElementById('cat_form').submit();
        }
         function disableButton() {
            var btn = document.getElementById('btn');
            btn.disabled = true;
            btn.innerText = 'Posting...'
        }
        $(document).ready(function(){
         $("#phd_title_show").hide();
         $("#synopsis_show").hide();
         $("#thysis_show").hide();
         $("#journal_paper").hide();
         $("#seminar_show").hide();
    
            $('.faculty').select2();
    $('#program_id').change(function() {
            var course_type = $('#program_id').val();
            var campus_id = $('#campus_id').val();
        //	$("#course").find('option').remove().end();
            var formData = {
                program: course_type,
                campuse_id: campus_id,
                "_token": "{{ csrf_token() }}"
            }; //Array 
            $.ajax({
                url: "{{route('get-courses')}}",
                type: "POST",
                data: formData,
                success: function(data, textStatus, jqXHR) {
                    $('#course_id').html(data);
                    console.log(data);
                },
            });
        });
    $('#course_id').change(function() {
        var program_id=$('#program_id').val();
        var course_id=$('#course_id').val();
        if(course_id == '94'){
            $("#phd_title_show").show(); 
            $("#synopsis_show").show(); 
            $("#thysis_show").show(); 
            $("#journal_paper").show(); 
            $("#seminar_show").show(); 
        }else{
            $("#phd_title_show").hide(); 
            $("#synopsis_show").hide();
            $("#thysis_show").hide();
            $("#journal_paper").hide();
            $("#seminar_show").hide();
        }
        $("#semester_id").find('option').remove().end();
        $("#branch_id").find('option').remove().end();
        var formData = {program_id:program_id,course_id:course_id,"_token": "{{ csrf_token() }}"}; //Array 
        $.ajax({
            url : "{{route('get-questionbank-semester')}}",
            type: "POST",
            dataType: "json",
            data : formData,
            success: function(data, textStatus, jqXHR){
                $('#branch_id').append(data.branch);
                $('#semester_id').append(data.semester);
            },
        });
    });
    
    $('#semester_id').change(function() {
        var program_id=$('#program_id').val();
        var course_id=$('#course_id').val();
        var semester_id=$('#semester_id').val();
        $("#sub_code").find('option').remove().end();
        var formData = {program_id:program_id,course_id:course_id,semester_id:semester_id,"_token": "{{ csrf_token() }}"}; //Array 
        $.ajax({
            url : "{{route('get-questionbank-subject')}}",
            type: "POST",
            data : formData,
            success: function(data, textStatus, jqXHR){
                $('#sub_code').html(data);
                        console.log(data);
                
            },
        });
    });
    
    });
    
        var expanded = false;
    
        function showCheckboxes() {
          var checkboxes = document.getElementById("checkboxes");
          if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
          } else {
            checkboxes.style.display = "none";
            expanded = false;
          }
        }
    </script>

   {{-- </body> --}}
   @endsection

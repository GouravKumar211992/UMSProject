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
                            <h2 class="content-header-title float-start mb-0">Fee</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active"> Add Fee List </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button type="submit" form="fee_form" class=" btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Submit </button>
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>
                            Reset</button> 

                    </div>
                </div>
            </div>
            <form id="fee_form" method="POST" action="{{ url('/submit-fee-form') }}">
                @csrf
            <div class="content-body bg-white p-4 shadow">

                                 
        <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Course ID<span class="text-danger">*</span></label>
                <select class="form-control" id="course_id" name="course_id">
                    <option value="">--Select Course--</option>
                    @foreach($courses as $course)
                    <option value="{{$course->id}}" @if(old('course_id')==$course->id) selected @endif >{{$course->name}}</option>@endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('course_id') }}</span>
                </div>

            <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Academic Session <span class="text-danger">*</span></label>
                <select class="form-control" id="academic_session" name="academic_session">
                    <option value="">--Select Academic Session--</option>
                    @foreach($sessions as $session)
                    <option value="{{$session->academic_session}}" @if(old('academic_session')==$session->academic_session) selected @endif >{{$session->academic_session}}</option>@endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('academic_session') }}</span>
              </div>

              <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Seat <span class="text-danger">*</span></label>
                <input id="seat" name="seat" type="text" value="{{old('seat')}}" class="form-control " placeholder="Enter Seat here"> 
                <span class="text-danger">{{ $errors->first('seat') }}</span>
              </div>

        </div>
    </div>

        <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3"> Basic Eligibility<span class="text-danger ">*</span></label>
                <input id="basic_eligibility" name="basic_eligibility" type="text" value="{{old('basic_eligibility')}}" class="form-control " placeholder="Enter Basic Eligibility here"> 
                <span class="text-danger">{{ $errors->first('basic_eligibility') }}</span>
                </div>

            <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Mode Of Admission <span class="text-danger">*</span></label>
                <select class="form-control" id="mode_of_admission" name="mode_of_admission">
                    <option value="">--Select Mode Of Admission--</option> 
                    <option value="Online">Online</option> 
                    <option value="Offline">Offline</option> 
                    </select>
                    <span class="text-danger">{{ $errors->first('mode_of_admission') }}</span>
                </div>
                

            <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Course Duration <span class="text-danger">*</span></label>
                <input id="course_duration" name="course_duration" type="text" value="{{old('course_duration')}}" class="form-control" placeholder="Enter Course Duration here"> 
                <span class="text-danger">{{ $errors->first('course_duration') }}</span>
              </div>

        </div>
    </div>

        <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Tution Fee for Divyang Per Sem <span class="text-danger">*</span></label>
                <input id="tuition_fee_for_divyang_per_sem" name="tuition_fee_for_divyang_per_sem" type="text" value="{{old('tuition_fee_for_divyang_per_sem')}}" class="form-control numbersOnly" placeholder="Enter Tution Fee for Divyang Per Sem here"> 
                <span class="text-danger">{{ $errors->first('tuition_fee_for_divyang_per_sem') }}</span>
              </div>

            <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3"> Tution Fee for Other Per Sem<span class="text-danger ">*</span></label>
                <input id="tuition_fee_for_other_per_sem" name="tuition_fee_for_other_per_sem" type="text" value="{{old('tuition_fee_for_other_per_sem')}}" class="form-control numbersOnly" placeholder="Enter Tution Fee for Other Per Sem here"> 
                <span class="text-danger">{{ $errors->first('tuition_fee_for_other_per_sem') }}</span>
                 </div>

            <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Payable Fee for Divyang Per Sem<span class="text-danger">*</span></label>
                <input id="payable_fee_for_divyang_per_sem" name="payable_fee_for_divyang_per_sem" type="text" value="{{old('payable_fee_for_divyang_per_sem')}}" class="form-control numbersOnly" placeholder="Enter Tution Fee for Other Per Sem here"> 
                <span class="text-danger">{{ $errors->first('payable_fee_for_divyang_per_sem') }}</span>
                </div>

        </div>
    </div>
        <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-4 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Payable Fee for Other Per Sem <span class="text-danger">*</span></label>
                <input id="payable_fee_for_other_per_sem" name="payable_fee_for_other_per_sem" type="text" value="{{old('payable_fee_for_other_per_sem')}}" class="form-control numbersOnly" placeholder="Enter Payable Fee for Divyang Per Sem here"> 
                <span class="text-danger">{{ $errors->first('payable_fee_for_other_per_sem') }}</span>
              </div>
        </div>
    </div>
            </div> 
</form>
				
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">

									
                            </div>
                        </div>
                    </div>
                    {{-- <!-- Modal to add new record -->
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
                    </div> --}}
                </section>
                 

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>


    <script>
        function submitFee(form) {
            document.getElementById('fee_form').submit();
        }
        $('.numbersOnly').keyup(function () { 
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    $("input").keypress(function() {
        $(this).parent().find('.text-danger').text('');
    });
    
    $("select").keypress(function() {
        $(this).parent().find('.text-danger').text('');
    });
    
    
    $("input").on('change',function() {
        $(this).parent().find('.text-danger').text('');
    });
    
    $("select").on('change',function() {
        $(this).parent().find('.text-danger').text('');
    });
    
    </script>

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

{{-- </body> --}}
@endsection

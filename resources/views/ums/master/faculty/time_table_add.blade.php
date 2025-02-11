{{-- @extends("admin.admin-meta")
@section("content") --}}


@extends('ums.master.faculty.faculty-meta')
<!-- END: Head-->

<!-- BEGIN: Body-->
 @section('content')



    <!-- BEGIN: Content-->
    <form id="timetable_form" method="POST" action="{{ route('time-table-add') }}">
        <input type="hidden" name="timetable_status" id="timetable_status" value="">
        @csrf
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header pocreate-sticky">
				<div class="row">
					<div class="content-header-left col-md-6 mb-2">
						<div class="row breadcrumbs-top">
							<div class="col-12">
								<h2 class="content-header-title float-start mb-0">New TimeTable</h2>
								<div class="breadcrumb-wrapper">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="index.html">Home</a>
										</li>  
										<li class="breadcrumb-item active">Add New TimeTable</li>


									</ol>
								</div>
							</div>
						</div>
					</div>
					<div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
						<div class="form-group breadcrumb-right">
                            <button onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm mb-50 mb-sm-0"><i data-feather="arrow-left-circle"></i>Cancel</button>    
   
                            <button class="btn btn-secondary btn-sm mb-50 mb-sm-0" onclick="history.go(-1)">
                                <i data-feather="arrow-left-circle"></i> Go Back
                            </button>
                            <button  type="submit"  href="javascript:void(0)" onclick="submitCat()" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather="check-circle"></i>Publish</button> 
						</div>
					</div>
				</div>
			</div>
            <div class="content-body">
                 
                
				
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">  
							
                            <div class="card">
								 <div class="card-body customernewsection-form"> 
											 
											<div class="row">
												<div class="col-md-12">
                                                    <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between"> 
														<div>
                                                            <h4 class="card-title text-theme">Basic Information</h4>
														    <p class="card-text">Fill the details</p>
                                                        </div> 
													</div>
                                                    
                                                </div> 
                                                
                                                
                                                <div class="col-md-8"> 
                                                      
                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Period <span class="text-danger">*</span></label>  
                                                        </div>  

                                                        <div class="col-md-5">  
                                                            <select class="form-select"  id="period_id" name="period_id">
                                                                @foreach($periods  as $key => $period)
                                                                <option value="{{$period->id}}">{{$period->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger">{{ $errors->first('period_id') }}</span>
                                                        </div>
                                                     </div>
                                                    
          
                                                                           
                                                     <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Days <span class="text-danger">*</span></label>  
                                                        </div>  

                                                        <div class="col-md-5">  
                                                            <select class="form-select" name="day">
                                                                <option value="">--Select Option--</option>
                                                                <option value="Monday">Monday</option>
                                                                <option value="Tuesday">Tuesday</option>
                                                                <option value="Wednesday">Wednesday</option>
                                                                <option value="Thursday">Thursday</option>
                                                                <option value="Friday">Friday</option>
                                                                <option value="Saturday">Saturday</option>
                                                                <option value="Sunday">Sunday</option>
                                                            </select>
                                                        </div>
                                                     </div>
                                                                 
                                                     <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Course <span class="text-danger">*</span></label>  
                                                        </div>  

                                                        <div class="col-md-5">  
                                                            <select class="form-select"  id="course-id" name="course_id">
                                                                @foreach($courses as $course)
                                                                <option value="{{$course->id}}">{{$course->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger">{{ $errors->first('course_id') }}</span>
                                                        </div>
                                                     </div>
                                                    
                                                                          
                                                     <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label" >Semester  <span class="text-danger">*</span></label>  
                                                        </div>  

                                                        <div class="col-md-5">  
                                                            <select class="form-select" id="semester-id" name="semester_id">
                                                                <option>Select Semester</option> 
                                                                {{-- <option>Select</option> 
                                                                <option>Select</option> 
                                                                <option>Select</option>  --}}
                                                            </select>
                                                            <span class="text-danger">{{ $errors->first('semester_id') }}</span>
                                                        </div>
                                                     </div>
                                     
                                                     <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label" name="subject_id" >Select Subject <span class="text-danger">*</span></label>  
                                                        </div>  

                                                        <div class="col-md-5">  
                                                            <select id="subject-id" class="form-control" name="subject_id">
                                                                <span class="text-danger">{{ $errors->first('subject_id') }}</span>
                                                            </select>
                                                        </div>
                                                     </div>

                                                     <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label"  name="faculty_id">Faculty<span class="text-danger">*</span></label>  
                                                        </div>  

                                                        <div class="col-md-5">  
                                                            <select class="form-select" id="faculty-id" name="faculty_id" readonly>
                                                                @foreach($facultys as $faculty)
                                                                <option value="{{$faculty->id}}"
                                                                    @if(Auth::guard('faculty')->check() && Auth::guard('faculty')->user()->id == $faculty->id) selected @endif>
                                                                    {{$faculty->name}}
                                                                </option>
                                                            @endforeach
                                                            </select>
                                                            @if ($errors->has('faculty_id'))
                                                            <span class="text-danger">{{ $errors->first('faculty_id') }}</span>							
                                                            @endif
                                                            </select>
                                                        </div>
                                                     </div>
                                                     <div class="row align-items-center mb-1">
                                                        <div class="col-md-3">
                                                            <label for="room_no" class="form-label">Room No <span class="text-danger">*</span></label>
                                                            {{-- <input type="text" class="form-control" value="{{ old('room_no') }}" required> --}}
                                                            
                                                        </div>
                                                        
                                                        
                                                        <div class="col-md-5"> 
                                                            <input type="text" class="form-control"  id="room_no" name="room_no" >
                                                            @if ($errors->has('room_no'))
                                                                <span class="text-danger">{{ $errors->first('room_no') }}</span>
                                                            @endif
                                                        </div> 
                                                    
                                                     </div>
                                                     <div class="row align-items-center mb-2">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Status</label> 
                                                        </div>  

                                                        <div class="col-md-5"> 
                                                            <div class="demo-inline-spacing">
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input type="radio"  name="group1" type="radio" id="active" class="form-check-input" checked="">
                                                                    <label class="form-check-label fw-bolder" for="customColorRadio3">Active</label>
                                                                </div> 
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input type="radio" name="group1" type="radio" id="inactive" class="form-check-input">
                                                                    <label class="form-check-label fw-bolder" for="customColorRadio4">Inactive</label>
                                                                </div> 
                                                                @if ($errors->has('group1'))
                                                                <span class="text-danger">{{ $errors->first('group1') }}</span>
                                                                @endif
                                                            </div>  
                                                        </div>  
                                     
                                             {{-- <div class="mt-2">
                                                <div class="step-custhomapp bg-light mb-0">
                                                    <ul class="nav nav-tabs my-25 custapploannav" role="tablist"> 
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-bs-toggle="tab" href="#Invoice">Invoice & Order Detail</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#Assigned">Assigned Person Detail</a>
                                                        </li> 
                                                    </ul> 
                                                </div>

												 <div class="tab-content pt-2 pb-1 px-1 rounded-3 border">
                                                    <div class="tab-pane active" id="Invoice"> 
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Invoice No. <span class="text-danger">*</span></label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <input type="text" class="form-control"  />
                                                            </div> 
                                                         </div>

                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Invoice Date <span class="text-danger">*</span></label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <input type="date" value="2024-09-10" disabled class="form-control"  />
                                                            </div> 
                                                            <div class="col-md-3">
                                                                <a href="#" class="btn btn-sm btn-outline-primary waves-effect">
                                                                <i data-feather="file-text"></i> View Invoice</a>
                                                            </div>
                                                         </div>
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Sales Order Number <span class="text-danger">*</span></label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <input type="text" class="form-control"  />
                                                            </div> 
                                                         </div>
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Sales Order Date <span class="text-danger">*</span></label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <input type="date" value="2024-09-08" disabled class="form-control"  />
                                                            </div> 
                                                         </div>
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Customer Ref. No. <span class="text-danger">*</span></label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <input type="text" class="form-control"  />
                                                            </div> 
                                                         </div>
                                                    </div>
                                                     <div class="tab-pane" id="Assigned"> 
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Error Category <span class="text-danger">*</span></label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <select class="form-control select2">
                                                                    <option>Select</option>
                                                                </select>
                                                            </div> 
                                                         </div>

                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Sub Category 1</label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <select class="form-control select2">
                                                                    <option>Select</option>
                                                                </select>
                                                            </div> 
                                                         </div>
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Sub Category 2</label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <select class="form-control select2">
                                                                    <option>Select</option>
                                                                </select>
                                                            </div> 
                                                         </div>
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Responsible Dept. <span class="text-danger">*</span></label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <select class="form-control select2">
                                                                    <option>Select</option>
                                                                </select>
                                                            </div> 
                                                         </div>
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-2"> 
                                                                <label class="form-label">Assigned To <span class="text-danger">*</span></label>  
                                                            </div>  

                                                            <div class="col-md-3"> 
                                                                <select class="form-control select2">
                                                                    <option>Select</option>
                                                                </select>
                                                            </div> 
                                                         </div>
                                                    </div>
                                                 </div>
											  
 
											 
											
											
											 
 
								
								</div> --}}
                                     
                                     
                                         {{-- <div class="row mt-2"> 
                                            <div class="col-md-12">
                                                 <div class="col-md-4">
                                                    <div class="mb-1">
                                                        <label class="form-label">Upload Document</label>
                                                        <input type="file" class="form-control">
                                                    </div>
                                                </div> 
                                             </div> 
                                            <div class="col-md-12">
                                                <div class="mb-1">  
                                                    <label class="form-label">Incident Description</label> 
                                                    <textarea type="text" rows="4" class="form-control" placeholder="Enter Remarks here..."></textarea> 

                                                </div>
                                            </div> 
                                        </div>  --}}
                            </div>
                        </div>
                    </div>
                    
                    </div>
                </section>
                 

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

   
	
	
	  <div class="modal fade" id="attribute" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-2 mx-50 pb-2">
                    <h1 class="text-center mb-1" id="shareProjectTitle">Attribute Selling Pricing</h1>
                    <p class="text-center">Enter the details below.</p>

                    <div class="table-responsive-md customernewsection-form">
                                <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail"> 
                                    <thead>
                                         <tr>  
                                            <th>#</th>
                                            <th>Attribute Name</th>
                                            <th>Attribute Value</th>
                                            <th>Extra Selling Cost</th>
                                            <th>Actual Selling Price</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                             <tr> 
                                                <td>1</td>
                                                <td class="fw-bolder text-dark">Color</td>
                                                <td>Black</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>

                                            <tr>   
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>White</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>Red</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>Golden</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>Silver</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>2</td>
                                              <td class="fw-bolder text-dark">Size</td>
                                              <td>5.11 Inch</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td> 
                                              <td>6.0 Inch</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>6.25 Inch</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr> 
                                       </tbody>


                                </table>
                            </div>
                </div>

                <div class="modal-footer justify-content-center">  
                        <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button> 
                    <button type="reset" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>
	 
	
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

    <!-- BEGIN: Vendor JS-->
    <!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function () {
        
                    $('#course-id').on('change', function () {
                        //alert('hi')
                        var idCourse = this.value;
                        $("#semester-id").html('');
                        $.ajax({
                            url: "{{route('fetch-semester')}}",
                            type: "POST",
                            data: {
                                course_id: idCourse,
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function (result) {
                                $('#semester-id').html('<option value="">Select Semester</option>');
                                $.each(result.semester, function (key, value) {
                                    $("#semester-id").append('<option value="' + value
                                        .id + '">' + value.name + '</option>');
                                });
                                $('#subject-id').html('<option value="">Firstly Select Semester</option>');
                            }
                        });
                    });
                });
        </script>
        <script>
            $(document).ready(function () {
        
                    $('#semester-id').on('change', function () {
                        //alert('hi')
                        var idSemester = this.value;
                        $("#subject-id").html('');
                        $.ajax({
                            url: "{{url('fetch-subject')}}",
                            type: "POST",
                            data: {
                                semester_id: idSemester,
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function (result) {
                                $('#subject-id').html('<option value="">Select Subject</option>');
                                $.each(result.subject, function (key, value) {
                                    $("#subject-id").append('<option value="' + value
                                        .id + '">' + value.name + '</option>');
                                });
                                $('#child-id').html('<option value="">Firstly Select Subject</option>');
                            }
                        });
                    });
                });
            </script>
           <script>
            function submitCat() {
                // Check which radio button is selected and set its value to the hidden input
                if(document.getElementById('active').checked) {
                    document.getElementById('timetable_status').value = 'active';
                } else if(document.getElementById('inactive').checked) {
                    document.getElementById('timetable_status').value = 'inactive';
                }
        
                // Submit the form
                document.getElementById('timetable_form').submit();
            }
        </script>
    
   @endsection
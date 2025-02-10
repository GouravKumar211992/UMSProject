@extends('ums.admin.admin-meta')

@section('content')
    

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
  data-menu="vertical-menu-modern" data-col=""> --}}

  

  <!-- BEGIN: Content-->
  <div class="app-content content ">
  <form id="sub_form" method="POST" action="{{route('add-subject')}}">
  <input type="hidden" name="subject_status" id="subject_status" value="">
      @csrf
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper container-xxl p-0">
          <div class="content-header row">
              <div class="content-header-left col-md-5 mb-2">
                  <div class="row breadcrumbs-top">
                      <div class="col-12">
                          <h2 class="content-header-title float-start mb-0">Add Subject </h2>
                          <div class="breadcrumb-wrapper">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                  <li class="breadcrumb-item active">Add Subject</li>
                              </ol>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                  <div class="form-group breadcrumb-right">
                      <button class="btn btn-dark btn-sm mb-50 mb-sm-0" onclick="location.href='{{url('/subject_list')}}'"> <i data-feather="arrow-left-circle"></i> Go Back
                          </button>
                      <button type="submit" form="sub_form" class="btn btn-primary btn-sm mb-50 mb-sm-0" > <i data-feather="check-circle" style="font-size: 40px;"></i>
                          Publish</button>


                  </div>
              </div>
          </div>
          <div class="content-body bg-white p-4 shadow">
              <div class="row gy-0  mt-3 p-2 ">


                  <div class="col-md-6 ">

                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Campus<span class="text-danger m-0">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <select class="form-control" name="university" id="university" required="">
                         
                         <option value="">--Please select Campus--</option>
                         @foreach ($campuselist as $campus)
                             <option value="{{$campus->id}} ">{{ ucfirst($campus->name) }}</option>
                         @endforeach
                 </select>
                              
                          </div>
                          @error('university')
                  <label class="label">
                      <strong class="text-danger"> {{ $message }}</strong>
                  </label>
                  @enderror
                      </div>
                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Course<span class="text-danger m-0">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <select id ="course" name="course" class="form-control">
                              <option value="">--Select Course--</option>
                              </select>
                              <span class="text-danger">{{ $errors->first('course') }}</span>                          </div>

                      </div>
                      @error('university')
                  <label class="label">
                      <strong class="text-danger"> {{ $message }}</strong>
                  </label>
                  @enderror
                     


                  </div>
                  <div class="col-md-6 ">
                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Program<span class="text-danger m-0">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <select id="program" name="program" class="form-control">
                              <option value="">--Select Program--</option>
                              @foreach($programs as $program)
                              <option value="{{$program->id}}">{{$program->name}}
                              </option>
                              @endforeach
                              
                              </select>
                              <span class="text-danger">{{ $errors->first('program') }}</span>
                              </div>
                      </div>

                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Stream<span class="text-danger">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <select id ="stream_id" name="stream_id" class="form-control">
                              <option value="">--Select Stream--</option>
                              </select><span class="text-danger">{{ $errors->first('stream_id') }}</span>
                          </div>
                      </div>
                      

                     

                  </div>
                  <div class="col-md-6">

                   
                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Semester<span class="text-danger">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <select id ="semester" name="semester" class="form-control">
                              <option value="">--Select Semester--</option>
                              
                              </select><span class="text-danger">{{ $errors->first('semester') }}</span>
                          </div>
                      </div>
                      


                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Subject Code<span class="text-danger m-0">*</span></label>
                          </div>
  
                          <div class="col-md-9">
                          <input id="sub_code" name="sub_code" type="text" value="{{old('sub_code')}}" class="form-control" placeholder="Enter here"> 
                              
                              <span class="text-danger">{{ $errors->first('sub_code') }}</span>                            </div>
                      </div>
                  </div>
              

               
               
                  
               
               
                 


              
              
                  <div class="col-md-6">
                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Subject name<span class="text-danger">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <input id="name" name="name" type="text" value="{{old('name')}}" class="form-control" placeholder="Enter here"> 
                              
                              <span class="text-danger">{{ $errors->first('name') }}</span>
                          </div>
                      </div>

                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Back Fees<span class="text-danger m-0">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <input id="back_fees" name="back_fees" type="text" value="{{old('back_fees')}}" class="form-control" placeholder="Enter here"> 
                              
                              <span class="text-danger">{{ $errors->first('back_fees') }}</span>                            </div>
                      </div>

                  
                    
                  </div>
                  <div class="col-md-6 ">
                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Type<span class="text-danger m-0">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <select id="type" name="type" class="form-control">
                              <option value="">--Select Type--</option>
                              <option value="compulsory">Compulsory</option>
                              <option value="optional">Optional</option>
                              </select>
                              
                              
                              <span class="text-danger">{{ $errors->first('type') }}</span>                            </div>
                      </div>

                   
                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Subject Internal Maximum Mark<span class="text-danger">*</span></label>
                          </div>

                          <div class="col-md-9">
                            <input id="internal_maximum_mark" name="internal_maximum_mark" type="text" value="{{Request::get('old_internal_maximum_mark')}}" class="form-control" placeholder="Enter here">
                          </div>
                      </div>

                  </div>

           
               <div class="col-md-6">

                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Subject Type<span class="text-danger">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <select id="subject_type" name="subject_type" class="form-control">
                              <option value="">--Select Subject Type--</option>
                              <option value="Theory">Theory</option>
                              <option value="Practical">Practical</option>
                              </select>
                              
                              
                              <span class="text-danger">{{ $errors->first('subject_type') }}</span>
                          </div>
                  


                  </div>

                  <div class="row align-items-center mb-1">
                      <div class="col-md-3">
                          <label class="form-label">Subject External Maximum Mark<span class="text-danger">*</span></label>
                      </div>

                      <div class="col-md-9">
                      <input id="internal_maximum_mark" name="maximum_mark" type="text" value="{{Request::get('old_internal_maximum_mark')}}" class="form-control" placeholder="Enter here"> 
                              
                              
                              <span class="text-danger">{{ $errors->first('internal_maximum_mark') }}</span>
                      </div>
                  </div>
                    

                  </div>

                
             


                  <div class="col-md-6 ">

                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Subject Minimum Mark<span class="text-danger m-0">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <input id="minimum_mark" name="minimum_mark" type="text" value="{{Request::get('old_maximum_mark')}}" class="form-control" placeholder="Enter here"> 
                              
                              
                              <span class="text-danger">{{ $errors->first('maximum_mark') }}</span>
                          </div>
                      </div>

                    


                  </div>
                  <div class="col-md-6 ">

                     
                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Subject Credit<span class="text-danger">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <input id="credit" name="credit" type="text" value="{{Request::get('credit')}}" class="form-control" placeholder="Enter here"> 
                              
                              
                              <span class="text-danger">{{ $errors->first('credit') }}</span>
                          </div>
                      </div>


                  </div>
                  <div class="col-md-6 ">

                      <div class="row align-items-center mb-1">
                          <div class="col-md-3">
                              <label class="form-label">Batch<span class="text-danger m-0">*</span></label>
                          </div>

                          <div class="col-md-9">
                          <select name="batch" id="batch" class="form-control" required>
                                  <option value="">Select</option>
                                   @foreach($batchList as $batchArrayRow)
                                  <option value="{{$batchArrayRow}}" @if(old('batch')==$batchArrayRow) selected @endif>{{$batchArrayRow}}</option>
                                  @endforeach
                              </select>
                              <span class="text-danger">{{ $errors->first('batch') }}</span>                           </div>
                      </div>
                  </div>
                     
                      <div class="col-md-6">
                          <div class="row align-items-center mb-1">
                              <div class="col-md-3">
                                  <label class="form-label">Status<span class="text-danger m-0">*</span></label>
                              </div>
  
                              <div class="col-md-5"> 
                                <div class="demo-inline-spacing">
                                    <div class="form-check form-check-primary mt-25">
                                        <input type="radio" id="closeStatus" name="group1" value="active" class="form-check-input"checked>
                                        <label class="form-check-label fw-bolder" for="active">Active</label>
                                    </div> 
                                    <div class="form-check form-check-primary mt-25">
                                        <input type="radio" id="openStatus" name="group1" value="inactive" class="form-check-input" >
                                        <label class="form-check-label fw-bolder" for="inactive">Inactive</label>
                                    </div> 
                                    
                                </div>  
                            </div>  
                      </div>

                          @if ($errors->has('group1'))
                          <span class="text-danger">{{ $errors->first('group1') }}</span>
                          @endif                          </div>
                          </div> 
                      </div>

                     


             
               
                  
               
               
                 


              </div>
          </div>

     
          

          </div>
        
          {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          {{-- <script>
              $(document).ready(function() {
                  $('#program').change(function() {
                      var university = $('#university').val();  // Correct the campus selector
                      var id = $('#program').val();  // Correct the program ID selector
          
                      $("#course").find('option').remove().end();
                      $('#course').append('<option value="">Please select</option>');  // Add default placeholder
          
                      if (university && id) {
                          // Make an AJAX request to get the course list
                          $.ajax({
                              url: "/ums/master/stream/get-course-list",  // Laravel route
                              type: 'POST',
                              data: {
                                  "_token": "{{ csrf_token() }}",  // CSRF token for security
                                  "university": university,        // Pass university ID
                                  "id": id                         // Pass program ID
                              },
                              success: function(data) {
                                  // Check if data is returned
                                  if (data) {
                                      // Append the new course options to the dropdown
                                      $('#course').append(data);
                                  } else {
                                      // Handle case when no courses are found
                                      $('#course').append('<option value="">No courses available</option>');
                                  }
                              },
                              error: function(xhr, status, error) {
                                  // Handle error (optional)
                                  console.error("Error fetching course list:", error);
                              }
                          });
                      }
                  });
              });
          </script> --}}
          
          <script>
            // Submit the form with active/inactive status
            function submitCat(form) {
                if (document.getElementById('active').checked) {
                    document.getElementById('subject_status').value = 'active';
                } else {
                    document.getElementById('subject_status').value = 'inactive';
                }
        
                document.getElementById('sub_form').submit();
            }
        
            $(document).ready(function() {
                // When the program changes
                $('#program').change(function() {
                    var university = $('#university').val();  // Get selected university value
                    var id = $('#program').val();  // Get selected program value
        
                    // Clear existing course options
                    $("#course").find('option').remove().end();
        
                    // Request course data via AJAX
                    $.ajax({
                        url: "ums/master/stream/get-course-list",  // Route to get courses
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",  // CSRF token for security
                            university: university,  // Send university ID
                            id: id  // Send program ID
                        },
                        success: function(data) {
                            // Append the new course options to the course dropdown
                            $('#course').append(data);
                        }
                    });
                });
        
                // When the course changes
                $('#course').change(function() {
                    var program = $('#program').val();  // Get selected program value
                    var course = $('#course').val();  // Get selected course value
        
                    // Get streams for the selected course
                    get_stream(course);
        
                    // Clear existing semester options
                    $("#semester").find('option').remove().end();
        
                    // Request semester data via AJAX
                    var formData = {
                        program_id: program,
                        course_id: course,
                        "_token": "{{ csrf_token() }}"  // CSRF token for security
                    };
                    $.ajax({
                        url: "{{route('get-semesters')}}",  // Route to get semesters
                        type: "POST",
                        data: formData,
                        success: function(data) {
                            // Append the new semester options to the semester dropdown
                            $('#semester').append(data);
                        }
                    });
                });
            });
        
            // Function to get streams for the selected course
            function get_stream(course_id) {
                var formData = {
                    course_id: course_id,
                    "_token": "{{ csrf_token() }}"  // CSRF token for security
                };
                $.ajax({
                    url: "{{route('get-streams')}}",  // Route to get streams
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        // Append the new stream options to the stream dropdown
                        $('#stream_id').html(data);
                    }
                });
            }
        </script>
        
          @endsection
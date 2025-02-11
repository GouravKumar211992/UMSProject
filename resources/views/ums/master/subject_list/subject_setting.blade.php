@extends('ums.admin.admin-meta')

@section('content')
    

  
   
    <div class="app-content content ">
        <h4>Subject Bulk Data</h4>

<<<<<<< HEAD
        <div class="submitss text-end me-3">


            <button type="submit" form="form_data" class="btn btn-primary btn-sm mb-50 mb-sm-0r " type="reset">
=======
       

        <div class="submitss text-end me-3">


            <button form="form_data" class="btn btn-primary btn-sm mb-50 mb-sm-0r " type="submit">
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
                <i data-feather="check-circle"></i> Submit
            </button>
        </div>

<div class="content-body bg-white p-4 shadow">
<<<<<<< HEAD
       <form method="GET" id="form_data" action="">
=======
<form method="GET" id="form_data" action="{{url('subject_setting')}}">

>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
        <div class="col-md-12">
            <div class="row align-items-center mb-1">
                <!-- Campus Field -->
                <div class="col-md-4">
                    <label class="form-label mb-0 me-2">Campus <span class="text-danger">*</span></label>
<<<<<<< HEAD
                    <select name="campus_id" aria-controls="DataTables_Table_0" class="form-select" id="campus_id" required onchange="getCourseList()">
                        <option value="">--Select--</option>
                        @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" @if($campus->id == Request()->campus_id) selected @endif>{{ $campus->name }}</option>
                        @endforeach
                    </select>
=======
                    <select name="campus_id" style="border-color: #c0c0c0;" class="form-control campus_id" id="campus_id" onchange="this.form.submit()">
                <option value="">--Select--</option>
                @foreach($campuses as $campus)
                <option value="{{$campus->id}}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>
                    {{$campus -> name}}
                </option>
                @endforeach
               </select>
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
                </div>
                
                <!-- Course Field -->
                <div class="col-md-4">
                    <label class="form-label mb-0 me-2">Course <span class="text-danger">*</span></label>
<<<<<<< HEAD
                    <select name="course_id" aria-controls="DataTables_Table_0" class="form-select" id="course_id" onchange="getSemesterList()" required>
                        <option value="">--Select--</option>
                        <!-- Courses will be dynamically loaded here -->
                    </select>
=======
                    <select name="course_id" style="border-color: #c0c0c0;" class="form-control course_id" id="course_id" onchange="this.form.submit()">
                <option value="">--Select--</option>
                @foreach($courses as $course)
                <option value="{{$course->id}}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                    {{$course->name}}
                </option>
                @endforeach
               </select>
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
                </div>
                
        
                <!-- Semester Field -->
                <div class="col-md-4">
                    <label class="form-label mb-0 me-2">Semester <span class="text-danger">*</span></label>
<<<<<<< HEAD
                    <select name="semester_id" id="semester_id" aria-controls="DataTables_Table_0" class="form-select">
                        <option value="">--Select--</option>
                        @foreach($semesters as $semester)
                        <option value="{{$semester->id}}" @if($semester->id==Request()->semester_id) selected @endif>{{$semester->name}}</option>
                        @endforeach
                    </select>
=======
                    <select  name="semester_id" style="border-color: #c0c0c0;" class="form-control semester_id" id="semester_id" onchange="this.form.submit()">
                <option value="">--Select--</option>
                @foreach($semesters as $semester)
                <option value="{{$semester->id}}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                {{$semester->name}}
                </option>
                @endforeach
            </select>
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
                </div>
            </div>

        </div>
    </form>
    


</div>




        <!-- options section end-->

          
                
                    <div class="row mt-4">
                        <!-- Card 1 -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    Compulsory Papers
                                </div>
<<<<<<< HEAD
                                <div class="card-body py-2" id="container1">
                                    <h5 class="card-title">Item List 1</h5>
                                    <ul class="list-group">
                                        @foreach($subjects as $subject)
                                        @if($subject->type=='compulsory')
                                        <li style="text-wrap: wrap; text-align: left;" itemid="itm-{{$subject->id}}" subid="{{$subject->id}}" class="btn btn-default box-item">{{$subject->sub_code}} ({{substr($subject->name,0,30)}})</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
=======
                                <div id="container1" class="panel-body box-container">
                    @foreach($subjects as $subject)
                    @if($subject->type=='compulsory')
                    <div style="text-wrap: wrap; text-align: left;" class="btn btn-default box-item">
                        {{$subject->sub_code}} ({{substr($subject->name,0,30)}})
                    </div>
                    @endif
                    @endforeach
                </div>
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    Optional Papers
                                </div>
<<<<<<< HEAD
                                <div class="card-body py-2" id="container2"> 
                                    <h5 class="card-title">Item List 2</h5>
                                    <ul class="list-group">
                                        @foreach($subjects as $subject)
                                        @if($subject->type!='compulsory')
                                        <li style="text-wrap: wrap; text-align: left;" itemid="itm-{{$subject->id}}" subid="{{$subject->id}}" class="btn btn-default box-item">{{$subject->sub_code}} ({{$subject->name}})</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
=======
                                <div id="container2" class="panel-body box-container">
                    @foreach($subjects as $subject)
                    <div style="text-wrap: wrap; text-align: left;" class="btn btn-default box-item">
                        {{$subject->sub_code}}  ({{$subject->name}})</div>
                   
                    @endforeach
                </div>
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-danger text-white">

                                    Optional Papers
                                </div>
<<<<<<< HEAD
                                <div class="card-body py-2" id="container3">
                                    <h5 class="card-title">Item List 3</h5>
                                    <ul class="list-group">
                                        @foreach($subjects as $subject)
                                        @if($subject->type!='compulsory')
                                        <li style="text-wrap: wrap; text-align: left;" itemid="itm-{{$subject->id}}" subid="{{$subject->id}}" class="btn btn-default box-item">{{$subject->sub_code}} ({{$subject->name}})</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
=======
                                <div id="container3" class="panel-body box-container">
                    @foreach($subjects as $subject)
                    <div style="text-wrap: wrap; text-align: left;" class="btn btn-default box-item">
                        {{$subject->sub_code}} ({{$subject->name }})</div>
                    @endforeach
                </div>
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
                            </div>
                        </div>
                    </div>
                </div>


                </form>
            </div>
        </div>
        <!-- END: Content-->

<<<<<<< HEAD
=======
        
      
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88


        <div class="modal fade" id="reallocate" tabindex="-1" aria-labelledby="shareProjectTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header p-0 bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close">×</button>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <div class="mb-1">
                            <label class="form-label" for="fp-range">Select Date Range</label>
                            <input type="text" id="fp-range" class="form-control flatpickr-range"
                                placeholder="YYYY-MM-DD to YYYY-MM-DD" />
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
                        <button type="reset" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {

$('.box-item').draggable({
  cursor: 'move',
  helper: "clone"
});

$("#container1").droppable({
  drop: function(event, ui) {
    var itemid = $(event.originalEvent.toElement).attr("itemid");
    var subject_id = $(event.originalEvent.toElement).attr("subid");
    $('.box-item').each(function() {
      if ($(this).attr("itemid") === itemid) {
        $(this).appendTo("#container1");
        subject_type_update(subject_id,'compulsory');
      }
    });
  }
});

$("#container2").droppable({
  drop: function(event, ui) {
    var itemid = $(event.originalEvent.toElement).attr("itemid");
    var subject_id = $(event.originalEvent.toElement).attr("subid");
    $('.box-item').each(function() {
      if ($(this).attr("itemid") === itemid) {
        $(this).appendTo("#container2");
        subject_type_update(subject_id,'optional');
      }
    });
  }
});

$("#container3").droppable({
  drop: function(event, ui) {
    var itemid = $(event.originalEvent.toElement).attr("itemid");
    var subject_id = $(event.originalEvent.toElement).attr("subid");
    $('.box-item').each(function() {
      if ($(this).attr("itemid") === itemid) {
        $(this).appendTo("#container3");
        subject_type_update(subject_id,'optional');
      }
    });
  }
});

});

function subject_type_update(subject_id,type){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '<?php echo csrf_token() ?>'
        }
    });
    $.ajax({
        type:'POST',
        dataType: 'json',
        url:"{{route('subject-type-update')}}",
        data: {
            'subject_id' : subject_id,
            'type' : type
        },
        success:function(data) {
            $("#msg").html(data.msg);
        }
    });
}
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script>
    $(document).ready(function(){
        // Fetch courses based on selected campus
        $('#campus_id').change(function() {
            var university = $('#campus_id').val();  // Get campus value

            // Clear the course dropdown
            $("#course_id").find('option').remove().end();
            $('#course_id').append('<option value="">Please select</option>');  // Add default placeholder

            if (university) {
                // Make an AJAX request to get the course list based on campus
                $.ajax({
                    url: "{{ route('get-course-list') }}",  // Correct route URL for courses
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",       // CSRF token for security
                        "university": university             // Pass campus ID
                    },
                    success: function(data) {
                        // Check if data is returned
                        if (data) {
                            // Append the new course options to the dropdown
                            $('#course_id').append(data);
                        } else {
                            // Handle case when no courses are found
                            $('#course_id').append('<option value="">No courses available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error (optional)
                        console.error("Error fetching course list:", error);
                    }
                });
            }
        });

        // Fetch semesters based on selected course
        $('#course_id').change(function() {
            var course = $('#course_id').val();  // Get selected course value

            // Clear the semester dropdown
            $("#semester_id").find('option').remove().end();
            $('#semester_id').append('<option value="">Please select</option>');  // Add default placeholder

            if (course) {
                // Make an AJAX request to get the semester list based on selected course
                $.ajax({
                    url: "{{ route('get-semesters') }}",  // Correct route URL for semesters
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",       // CSRF token for security
                        "course_id": course                  // Pass course ID
                    },
                    success: function(data) {
                        // Check if data is returned
                        if (data) {
                            // Append the new semester options to the dropdown
                            $('#semester_id').append(data);
                        } else {
                            // Handle case when no semesters are found
                            $('#semester_id').append('<option value="">No semesters available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error (optional)
                        console.error("Error fetching semester list:", error);
                    }
                });
            }
        });
    });
</script>



        @endsection
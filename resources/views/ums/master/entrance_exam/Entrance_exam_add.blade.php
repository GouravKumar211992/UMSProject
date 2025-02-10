@extends('ums.admin.admin-meta')

@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Add Schedule </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                                <li class="breadcrumb-item active">Add Schedule</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right">
                    <button class="btn btn-dark btn-sm mb-50 mb-sm-0" onclick="location.href='{{url('/subject_list')}}'"> 
                        <i data-feather="arrow-left-circle"></i> Go Back
                    </button>
                    <button type="submit"  form="cat_form" class="btn btn-primary btn-sm mb-50 mb-sm-0" form="cat_form"> 
                        <i data-feather="check-circle" style="font-size: 40px;"></i> Publish
                    </button>
                </div>
            </div>
        </div>

        <!-- Start Form -->
        <form id="cat_form" method="POST" action="{{route('post-entrance-exam')}}">
            @csrf
            <div class="content-body bg-white p-3 shadow">
                <div class="row gy-0 mt-3 p-2">
                    <div class="col-md-4">

                        <!-- Campus Section -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Campus<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control" name="campuse_id" id="campuse_id" required="">
                                    <option value="">--Please Select Campus--</option>
                                    @foreach ($campuselist as $campus)
                                        <option value="{{$campus->id}}">{{ ucfirst($campus->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('campuse_id')
                                <label class="label">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </label>
                            @enderror
                        </div>

                        <!-- Examination Date Section -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4 text-nowrap">
                                <label class="form-label">Examination Date:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input id="entrance_exam_date" name="entrance_exam_date" type="date" class="form-control" value="{{old('entrance_exam_date')}}">
                                <span class="text-danger">{{ $errors->first('entrance_exam_date') }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <!-- Program Section -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Program<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control" name="program_id" id="program_id" required="">
                                    <option value="">Please select</option>
                                    @foreach ($categorylist as $category)
                                        <option value="{{$category->id}}" @if(old('program_id') == $category->id) selected @endif>{{ ucfirst($category->name) }}</option>
                                    @endforeach
                                </select>
                                @error('program_id')
                                    <label class="label">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </label>
                                @enderror
                            </div>
                        </div>

                        <!-- Reporting Timing Section -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4 text-nowrap">
                                <label class="form-label">Reporting Timing:<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input id="reporting_time" name="reporting_time" type="datetime-local" class="form-control" value="{{old('reporting_time')}}" placeholder="Enter Reporting Time">
                                <span class="text-danger">{{ $errors->first('reporting_time') }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <!-- Course Section -->
                       <!-- Course Section -->
<!-- Course Section -->
<div class="row align-items-center mb-1">
    <div class="col-md-4">
        <label class="form-label">Course<span class="text-danger m-0">*</span></label>
    </div>
    <div class="col-md-8">
        <select class="form-control" name="course_id" id="course_id" required="">
            <option value="">Please select</option>
        </select>
        @error('course_id')
            <label class="label">
                <strong class="text-danger">{{ $message }}</strong>
            </label>
        @enderror
    </div>
</div>



                        <!-- Examination Time Section -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4 text-nowrap">
                                <label class="form-label">Examination Time:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input id="examination_time" name="examination_time" type="datetime-local" class="form-control" value="{{old('examination_time')}}" placeholder="Enter Examination Time">
                                <span class="text-danger">{{ $errors->first('examination_time') }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <!-- Ending Time Section -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Ending Time:<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input id="end_time" name="end_time" type="datetime-local" class="form-control" value="{{old('end_time')}}" placeholder="Enter Ending Time">
                                <span class="text-danger">{{ $errors->first('end_time') }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <!-- Exam Center Section -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Exam Center<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control" name="center_id" id="center_id" required="">
                                    <option value="">Please select</option>
                                    @foreach ($examcenter as $center)
                                        <option value="{{$center->id}}" @if(old('center_id') == $center->id) selected @endif>{{ ucfirst($center->center_name) }}</option>
                                    @endforeach
                                </select>
                                @error('center_id')
                                    <label class="label">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </label>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <!-- Session Section -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Session<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control" name="session" id="session" required="">
                                    <option value="2024-2025">2024-2025</option>
                                </select>
                                @error('session')
                                    <label class="label">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </label>
                                @enderror
                            </div>
                        </div>

                    </div>

                </div>
            </div>

           
        </form>
        <!-- End Form -->

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('#program_id').change(function() {
            var university = $('#campuse_id').val();  // Get campus value
            var id = $('#program_id').val();          // Get program ID

            $("#course_id").find('option').remove().end();
            $('#course_id').append('<option value="">Please select</option>');  // Add default placeholder

            if (university && id) {
                // Make an AJAX request to get the course list
                $.ajax({
                    url: "ums/master/stream/get-course-list",  // Use Laravel's route helper
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",       // CSRF token for security
                        "university": university,             // Pass university ID
                        "id": id                              // Pass program ID
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
    });
</script>

@endsection

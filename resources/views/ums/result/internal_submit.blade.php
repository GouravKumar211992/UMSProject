@extends('ums.admin.admin-meta')
  
@section('content')


<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">


        <form method="get" action="{{route('internal_submit')}}" id="form_data">

        <div class="customernewsection-form poreportlistview p-1">
            <div class="row"> 
                <div class="col-md-2">
                    <div class="mb-1 mb-sm-0"> 
                        <label class="form-label">Session:</label>
                        <select name="session" id="session" style="border-color: #c0c0c0;" class="form-control" required>
                            <option value="">Select</option>
                            <option value="2023-2024AUG" @if(Request()->session=='2023-2024AUG') selected @endif>2023-2024AUG</option>
                            <option value="2023-2024JUL" @if(Request()->session=='2023-2024JUL') selected @endif>2023-2024JUL</option>
                            <option value="2023-2024FEB" @if(Request()->session=='2023-2024FEB') selected @endif>2023-2024FEB</option>
                            <option value="2023-2024" @if(Request()->session=='2023-2024') selected @endif>2023-2024</option>
                            <option value="2022-2023" @if(Request()->session=='2022-2023') selected @endif>2022-2023</option>
                            <option value="2021-2022" @if(Request()->session=='2021-2022') selected @endif>2021-2022</option>
                          </select>
                          <span class="text-danger">{{ $errors->first('session') }}</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-1 mb-sm-0"> 
                        <label class="form-label">Exam Type:</label>
                        <select name="exam_type" id="exam_type" style="border-color: #c0c0c0;" class="form-control" onchange="return $('#form_data').submit();">
                            @foreach($examTypes as $examType)
                            <option value="{{$examType->exam_type}}" @if(Request()->exam_type == $examType->exam_type) selected @endif >{{$examType->exam_type}}</option>
                            @endforeach
                          </select>
                          <span class="text-danger">{{ $errors->first('exam_type') }}</span> 
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-1 mb-sm-0"> 
                        <label class="form-label">Campuse:</label>
                        <select name="campus_id" id="campus_id" style="border-color: #c0c0c0;" class="form-control" onchange="return $('#form_data').submit();">
                            <option value="">--Choose Campus--</option>
                              @foreach($campuses as $campus)
                              <option value="{{$campus->id}}" @if(Request()->campus_id==$campus->id) selected @endif >{{$campus->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('campus_id') }}</span>                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-1 mb-sm-0"> 
                        <label class="form-label">Courses:</label>
                        <select name="course" id="course" style="border-color: #c0c0c0;" class="form-control" onchange="return $('#form_data').submit();">
                            <option value="">--Choose Course--</option>
                                 @foreach($courses as $course)
                                  
                                   <option value="{{$course->id}}" @if($course_id==$course->id) selected @endif >{{$course->name}}</option>
                                     @endforeach
                                 </select>
                                 <span class="text-danger">{{ $errors->first('course') }}</span>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="mb-1 mb-sm-0"> 
                        <label class="form-label">Semester:</label>
                        <select name="semester" id="semester" style="border-color: #c0c0c0;" class="form-control" onchange="return $('#form_data').submit();">
                            <option value="">--Select Semester--</option>
                            @foreach($semesters as $semester)
                            <option value="{{$semester->id}}" @if($semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                            @endforeach
                          </select>
                          <span class="text-danger">{{ $errors->first('semester') }}</span>                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-1 mb-sm-0"> 
                        <label class="form-label">Type:</label>
                        <select name="type" id="type" style="border-color: #c0c0c0;" class="form-control js-example-basic-single" onchange="return $('#form_data').submit();">
                            <option value="1" @if(Request()->type==1) selected @endif >Internal</option>
                            <option value="2" @if(Request()->type==2) selected @endif >External</option>
                            <option value="3" @if(Request()->type==3) selected @endif >Practical</option>
                        </select>
                        <span class="text-danger">{{ $errors->first('course') }}</span>                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-6 d-flex gap-2">
                    <input value="Save Data" name="saveData" type="submit" class="btn btn-primary">
                    <a href="{{ route('result_list') }}" class="btn btn-secondary">Go Back</a>
                </div>
            </div>
        </div> 
        </form>
    </div>
</div>
<!-- END: Content-->



@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        @endif
    });
</script>
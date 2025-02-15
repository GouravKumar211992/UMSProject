@extends('ums.student.student-meta')

@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h4 class="content-header-title float-start mb-0">Please Select Your Papers Type Then Press Submit Button</h4>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="student_dashboard">Home</a></li>  
                             
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right"> 
                    <form method="get" id="form_data" method="POST" action="">
                    <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                    <button class="btn btn-dark btn-sm mb-50 mb-sm-0" type="submit">
                        <i data-feather="user-plus"></i> Submit
                    </button>

                    <a class="btn btn-sm btn-success" href="{{url('admitcard-download-list')}}?id={{$student->roll_number}}&bypaas=true">Download Admit Card</a>

                    <!-- Reset Button -->
                    <button class="btn btn-warning btn-sm mb-50 mb-sm-0" type="reset" onClick="window.location.reload()">
                        <i data-feather="refresh-cw"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <section class="content-body p-5">
            <div class="row align-items-center mt-2">
                @if($student->enrollments->course_id==64 || $student->enrollments->course_id==49)
                <div class="col-md-12 text-center">
                    <br/><br/><br/><br/><br/><br/><br/><br/>
                    <a style="margin-top: 100px;" href="{{url('mbbs-exam-form-login')}}" class="btn-lg btn-info">MBBS / Nursing Examination Form Link</a>
                </div>
                @else
                {{-- <h4 class="text-start mb-3">Please Select Your Papers Type Then Press Submit Button </h4> --}}
                <div class="row">
                    <input type="hidden" name="course_id" value="{{$student->enrollments->course_id}}">
                    <div class="col-sm-4">
                        <span style="color: black;">Paper Types:</span>
                        <select name="back_papers" id="back_papers" class="form-control" onchange="$('#form_data').submit()" style="border-color: #c0c0c0;" required>
                            <option value="">--Select Papers--</option>
                            <option value="regular" @if(Request()->back_papers=='regular') selected @endif>Regular Exam</option>
                            <option value="final_back_paper" @if(Request()->back_papers=='final_back_paper') selected @endif>Back / Final Back</option>
                        </select>
                    </div>

                    <div class="col-sm-4" @if(Request()->back_papers == 'final_back_paper') hidden @endif>
                        <span style="color: black;">SEMESTER:</span>
                        <select data-live-search="true" name="semester_id" id="semester_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single" required>
                            @if(Request()->back_papers!='final_back_paper')
                            <option value="">---Select---</option>
                            @endif
                            @foreach($semesters as $semester)
                            <option value="{{$semester->id}}" @if(Request()->semester_id==$semester->id) selected @endif>{{$semester->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-4">
                        <span style="color: black;">Academic Session:</span>
                        <select name="accademic_session" id="academic_session" class="form-control" style="border-color: #c0c0c0;" required>
                            <option value="2024-2025">2024-2025</option>
                        </select>
                    </div>
                </div>
                </form>
                @endif
            </div>
        </section>

    </div>
</div>

<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script>
    // Custom JS code if needed
</script>

@endsection

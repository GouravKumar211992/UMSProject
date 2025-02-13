@extends('ums.admin.admin-meta')
@section('content')


    
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

<!-- BEGIN: Content-->
<div class="app-content content ">


    <div class="content-header row">
        <div class="content-header-left col-md-5 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <!-- Breadcrumbs can be added here -->
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
            <div class="form-group breadcrumb-right">
                <button class="btn btn-primary btn-sm mb-50 mb-sm-0" onclick="history.go(-1)" >
                    <i data-feather="arrow-left"></i>Go back
                </button>
                <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();" >
                    <i data-feather="refresh-cw"></i> Reset
                </button> 
            </div>
        </div>
    </div>

    <!-- Add Semester Fee Form -->
    <form method="POST" action="{{ route('student-semesterfee') }}">
        @csrf
        <section class="col-md-12 connectedSortable">
            <div class="row">
                <!-- Student ID Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <span class="form-label main-page">Student ID</span>
                        <input id="student_id" name="student_id" type="text" value="DSMNRU/" class="form-control" placeholder="Enter Student ID here"> 
                        
                        <span class="text-danger">{{ $errors->first('student_id') }}</span>
                        
                    </div>
                </div>

                <!-- Course Code Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <span class="form-label main-page">Course Code</span>
                        <input id="course_code" name="course_code" type="text" value="{{old('course_code')}}" class="form-control " placeholder="Enter Course Code here"> 
                        <span class="text-danger">{{ $errors->first('course_code') }}</span>
                        
                    </div>
                </div>

                <!-- Semester Dropdown -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <span class="form-label main-page">Semester</span>
                        <select id ="semester" name="semester" class="form-control ">
                        <option>--select semester--
                        </option>
                        </select>
                        <span class="text-danger">{{ $errors->first('semester') }}</span>
                        
                    </div>
                </div>
            </div>
        </section>

        <section class="col-md-12 connectedSortable mt-3">
            <div class="row">
                <!-- Semester Fee Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <span class="form-label main-page">Semester Fee</span>
                        <input id="semester_fee" name="semester_fee" type="text" value="{{old('semester_fee')}}" class="form-control" placeholder="Enter Semester Fee here"> 
                        <span class="text-danger">{{ $errors->first('semester_fee') }}</span>
                        
                    </div>
                </div>

                <!-- Receipt Date Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <span class="form-label main-page">Receipt Date</span>
                        <input id="receipt_date" name="receipt_date" type="date" value="{{old('receipt_date')}}" class="form-control" placeholder="Enter Semester Fee here"> 
                        <span class="text-danger">{{ $errors->first('receipt_date') }}</span>
                        
                    </div>
                </div>

                <!-- Receipt Number Input -->
                <div class="col-md-4">
                    <div class="form-group position-relative custom-form-group inner-formnew">
                        <span class="form-label main-page">Receipt Number</span>
                        <input id="receipt_number" name="receipt_number" type="text" value="{{old('receipt_number')}}" class="form-control" placeholder="Enter Semester Fee here"> 
                        <span class="text-danger">{{ $errors->first('receipt_number') }}</span>
                        
                    </div>
                </div>
            </div>
        </section>

        <!-- Submit Button -->
        <div class="col-md-12 text-center mt-3">
            <button type="submit" class="btn btn-success">Submit Fee</button>
        </div>
    </form>

</div>
<!-- END: Content-->

@endsection
<script>
	function submitFee(form) {
		document.getElementById('semesterfee_form').submit();
	}
	$(document).ready(function(){
		$('#student_id').on('change',function(){
			var student_id=$('#student_id').val();
	
	var formData = {student_id:student_id,"_token": "{{ csrf_token() }}"}; //Array 
	$.ajax({
		url : "{{route('get-student-data')}}",
		type: "POST",
		data : formData,
		success: function(data, textStatus, jqXHR){
			$('#course_code').val(data);
		},
	});
			
		});
		
		$('#course_code').keypress(function(){
			
			var course_id=$('#course_code').val();
			var student_id=$('#student_id').val();
	$("#semester").find('option').remove().end();
	var formData = {student_id:student_id,course_id:course_id,"_token": "{{ csrf_token() }}"}; //Array 
	$.ajax({
		url : "{{route('get-course-data')}}",
		type: "POST",
		data : formData,
		success: function(data, textStatus, jqXHR){
			$('#semester').append(data);
		},
	});
			
		});
	});
</script>
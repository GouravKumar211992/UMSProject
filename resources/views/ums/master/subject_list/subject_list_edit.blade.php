  @extends('ums.admin.admin-meta')
<!-- BEGIN: Body-->

@section('content')
    

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col=""> --}}

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Update Subject</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Update Subject</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0"onclick="location.href='{{url('/subject_list')}}'"> <i data-feather="arrow-left-circle"></i> Go Back
                            </button>
                        <button type="submit" form="edit_subject_form" class="btn btn-primary btn-sm mb-50 mb-sm-0" > <i data-feather="check-circle" style="font-size: 40px;"></i>
                            Update</button>


                    </div>
                </div>
            </div>
            <form id="edit_subject_form" method="POST" action="{{route('update-subject-form')}}">
                @csrf
                @method('PUT')
                <input type="hidden" name="subject_id" value="{{$selected_subject->id}}">

            <div class="content-body bg-white p-4 shadow">
                <div class="row gy-0  mt-3 p-2 ">


                    <div class="col-md-6 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Campus<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="university" id="university" class="form-control">
                                    <option value="">--Please select Campus--</option>
                                    @foreach ($campuselist as $campus)
                                        <option value="{{$campus->id}}" @if($selected_subject->course->campuse->id== $campus->id) selected @endif>{{ ucfirst($campus->name) }}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select id ="course" name="course" class="form-control">
                                    @foreach ($courseList as $course)
                                    <option value="{{$course->id}}" {{ ($selected_subject->id && ($selected_subject->course_id == $course->id)) ? 'selected':'' }}>{{ ucfirst($course->name) }}</option>
                                @endforeach
                                </select>                            </div>
                        </div>

                       


                    </div>
                    <div class="col-md-6 ">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Program<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select id ="program" name="program" class="form-control">
                                    @foreach($programs as $program)
                                    <option value="{{$program->id}}" @if($selected_subject->program_id== $program->id) selected @endif>{{$program->name}}
                                    </option>
                                    @endforeach
                                </select>                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Stream<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select id ="stream_id" name="stream_id" class="form-control">
                                    @foreach ($streams as $stream)
                                    <option value="{{$stream->id}}" {{ ($selected_subject->id && ($selected_subject->course_id == $stream->id)) ? 'selected':'' }}>{{ ucfirst($stream->name) }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        

                       

                    </div>
                    <div class="col-md-6">

                     
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Semester<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select id ="semester" name="semester"  class="form-control">
                                    @foreach ($semesterlist as $semester)
                                    <option value="{{$semester->id}}" {{ ($selected_subject->id && ($selected_subject->semester_id == $semester->id)) ? 'selected':'' }}>{{ ucfirst($semester->name) }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        


                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject Code<span class="text-danger m-0">*</span></label>
                            </div>
    
                            <div class="col-md-9">
                              <input id="sub_code" name="sub_code" type="text" value="{{$selected_subject->sub_code}}"  placeholder="Enter here" class="form-control">
                            </div>
                        </div>
                    </div>
                

                 
                 
                    
                 
                 
                   


                
                
                    <div class="col-md-6">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject name<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <input id="name" name="name" type="text" value="{{$selected_subject->name}}" class="form-control" placeholder="Enter here">
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Back Fees<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input id="back_fees" name="back_fees" type="text" value="{{$selected_subject->back_fees}}" placeholder="Enter here" class="form-control">
                            </div>
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
                                    <option value="compulsory" @if($selected_subject->type== 'compulsory') selected @endif >Compulsory</option>
                                    <option value="optional" @if($selected_subject->type== 'optional') selected @endif>Optional</option>
                                </select>                            </div>
                        </div>

                     
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject Internal Maximum Mark<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input id="internal_maximum_mark" name="internal_maximum_mark" type="text" value="{{$selected_subject->internal_maximum_mark}}" class="form-control" placeholder="Enter here">
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
                                    <option value="Theory"  @if($selected_subject->subject_type== 'Theory') selected @endif >Theory</option>
                                    <option value="Practical"  @if($selected_subject->subject_type== 'Practical') selected @endif >Practical</option>
                                </select>
                            </div>
                    


                    </div>

                    <div class="row align-items-center mb-1">
                        <div class="col-md-3">
                            <label class="form-label">Subject External Maximum Mark<span class="text-danger">*</span></label>
                        </div>

                        <div class="col-md-9">
                           <input id="maximum_mark" name="maximum_mark" type="text" value="{{$selected_subject->maximum_mark}}" class="form-control" placeholder="Enter here">
                        </div>
                    </div>
                      

                    </div>

                  
               


                    <div class="col-md-6 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject Minimum Mark<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input id="minimum_mark" name="minimum_mark" type="text" value="{{$selected_subject->minimum_mark}}" placeholder="Enter here" class="form-control">
                            </div>
                        </div>

                      


                    </div>
                    <div class="col-md-6 ">

                       
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject Credit<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input id="credit" name="credit" type="text" value="{{$selected_subject->credit}}" class="form-control" placeholder="Enter here">
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Batch<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="batch" id="batch" class="form-control">
                                    <option value="">Select</option>
									@foreach($batcharray  as $batchArrayRow)
									<option value="{{$batchArrayRow}}" @if($selected_subject->batch==$batchArrayRow) selected @endif>{{$batchArrayRow}}</option>
									@endforeach
                                </select>                            </div>
                        </div>
                    </div>
                    <div class="col-md-5"> 
                        <div class="demo-inline-spacing">
                            <div class="form-check form-check-primary mt-25">
                                <input type="radio" id="active" name="group1" value="active" class="form-check-input"checked>
                                <label class="form-check-label fw-bolder" for="active">Active</label>
                            </div> 
                            <div class="form-check form-check-primary mt-25">
                                <input type="radio" id="inactive" name="group1" value="inactive" class="form-check-input" >
                                <label class="form-check-label fw-bolder" for="inactive">Inactive</label>
                            </div> 
                            
                        </div>  
                    </div> 

                       


               
                 
                    
                 
                 
                   


                </div>
            </div>
        </form>

               

            </div>
          
            <script>
                $(document).ready(function(){
            
                    var selected_subject = {!! json_encode($selected_subject) !!};
            
                    if(selected_subject['status'] == 1) {
                        $('#active').prop('checked', true);
                        $('#inactive').prop('checked', false);
                    }
                    else {
                        $('#active').prop('checked', false);
                        $('#inactive').prop('checked', true);
                    }
                    $('#program').change(function() {
                var university=$('#university').val();
                var id = $('#program').val();
                $("#course").find('option').remove().end();
                $.ajax({
                    url: "ums/master/stream/get-course-list",
                    data: {"_token": "{{ csrf_token() }}",university:university ,id: id},
                    type: 'POST',
                    success: function(data,textStatus, jqXHR) {
                        $('#course').append(data);
                            
                        
                    }
                });
            });
                
            
            $('#course').change(function() {
                var program=$('#program').val();
                var course=$('#course').val();
                get_stream(course);
                $("#semester").find('option').remove().end();
                var formData = {program:program,course:course,"_token": "{{ csrf_token() }}"}; //Array 
                $.ajax({
                    url : "{{route('get-semesters')}}",
                    type: "POST",
                    data : formData,
                    success: function(data, textStatus, jqXHR){
                        $('#semester').append(data);
                    },
                });
            });
            });
            
            function get_stream(course_id){
                var formData = {course_id:course_id,"_token": "{{ csrf_token() }}"}; //Array 
                $.ajax({
                    url : "{{route('get-streams')}}",
                    type: "POST",
                    data : formData,
                    success: function(data, textStatus, jqXHR){
                        $('#stream_id').html(data);
                    },
                });
            }
            
            </script>
            
            <script>
            
                function submitCat(form) {
                    if(document.getElementById('active').checked) {
                        document.getElementById('subject_status').value = 'active';
                    }
                    else {
                        document.getElementById('subject_status').value = 'inactive';
                    }
            
                    document.getElementById('edit_subject_form').submit();
                }
            </script>    
    <!--@endsection
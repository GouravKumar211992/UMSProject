{{-- @extends("admin.admin-meta")
@section("content") --}}

@extends('ums.master.faculty.faculty-meta')
<!-- END: Head-->

<!-- BEGIN: Body-->
 @section('content')

    <!-- BEGIN: Content-->
    <form method="get" name="internal_form" onsubmit="return validateForm()">

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Practical Marks </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Practical Marks</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0" onclick="history.go(-1)"> <i data-feather="arrow-left-circle"></i> Go Back
                            </button>
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" > <i data-feather="check-circle" style="font-size: 40px;"></i>
                             Show Student list</button>


                    </div>
                </div>
            </div>
            <div class="content-body bg-white p-4 shadow">
                <div style="text-align: center;">
                    <h3>Dr. SHAKUNTALA MISRA NATIONAL REHABILITATION UNIVERSITY, LUCKNOW</h3>
                    <h3>AWARD SHEET OF INTERNAL MARKS</h3>
                    <h3>PRACTICAL MARKS</h3>
                </div>
                <div class="row gy-0  mt-3 p-2 ">

                    
                    <div class="col-md-6">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course Code<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="course" id="course" class="form-control" name="course" >
                                    <option value="">--Please select Course Code--</option>
                                    @foreach($mapped_Courses as $index=>$mapped_Course)
                                    <option value="{{$mapped_Course->id}}"@if(Request()->course==$mapped_Course->id) selected @endif>{{$mapped_Course->name}} ({{$mapped_Course->Course->campuse->campus_code}})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Semester Name<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="semester" id="" class="form-control">
                                    <option value="">--Select Course--</option>
                                    @if($mapped_Semesters)
                                    @foreach($mapped_Semesters as $index=>$mapped_Semester)
                                        <option value="{{$mapped_Semester->id}}"@if(Request()->semester==$mapped_Semester->id) selected @endif>{{$mapped_Semester->name}}</option>
                                        @endforeach
                                        @endif
                                </select>                     
                                   </div>
                        </div>

                       


                    </div>
                    <div class="col-md-6 ">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course Name<span class="text-danger m-0">*</span>
                                    <input type="text" value="{{($mapped_faculty)?$mapped_faculty->Course->name:''}}" hidden><p>{{($mapped_faculty)?$mapped_faculty->Course->name:''}}</p>
                                </label>
                            </div>

                            <div class="col-md-9">
                                {{-- <select name="" id="" class="form-control">
                                    <option value="">-- Select Program--</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>                            --}}
                             </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Exam Type<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="type" id="" class="form-control">
                                    
                                    <option value="1">--Select Stream--</option>
                                    @foreach($examTypes as $index=>$examType)
                                    <option value="{{$examType}}" @if(Request()->type==$examType) selected @endif>{{$examType}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        

                       

                    </div>
                    <div class="col-md-6">

                     
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Session<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="selcet" id="" class="form-control" name="session">
                                    
                                    <option value="1">--Select Semester--</option>
                                    @foreach($sessions as $session)
                                    <option value="{{$session->academic_session}}" @if(Request()->session==$session->academic_session) selected @endif>{{$session->academic_session}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        


                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Institution Code:<span class="text-danger m-0">*</span></label>
                            </div>
    
                            <div class="col-md-9">
                              {{-- <input type="text" placeholder="Enter here" class="form-control"> --}}
                            </div>
                        </div>
                    </div>
                

                 
                 
                    
                 
                 
                   


                
                
                    <div class="col-md-6">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Batch<span class="text-danger">*</span></label>
                                {{-- <select name="batch" id="batch" style="border-color: #c0c0c0;" class="form-control js-example-basic-single" required>
                                    <option value="">--Select--</option>
                                    @foreach(batchArray() as $batch)
                                    @php $batch_prefix = substr($batch,2,2); @endphp
                                    <option value="{{$batch_prefix}}" @if(Request()->batch == $batch_prefix) selected @endif >{{$batch}}</option>
                                    @endforeach
                                </select> --}}
                            </div>

                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter here">
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Institution Name:<span class="text-danger m-0">*</span>
                                    {{-- {{($selected_course)?$selected_course->campuse->name:''}}</label> --}}
                            </div>

                            <div class="col-md-9">
                              {{-- <input type="text" placeholder="Enter here" class="form-control"> --}}
                            </div>
                        </div>

                    
                      
                    </div>
                    <div class="col-md-6 ">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Paper Code<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="sub_code" class="form-control">
                                    <option value="">--Select Type--</option>
                                    @foreach($mapped_Subjects as $mapped_Subject)
                                        @php
                                            // Check if the subject code matches the selected sub_code
                                            $isSelected = (Request()->sub_code == $mapped_Subject->sub_code && Request()->course == $mapped_Subject->course_id);
                                            $sub_code_name = $isSelected ? $mapped_Subject->name : '';
                                        @endphp
                            
                                        @if ($mapped_Subject->internal_marking_type == 1 && $mapped_Subject->subject_type == 'Theory')
                                            @if ($loop->first)
                                                <option value="{{ $mapped_Subject->sub_code }}" @if($isSelected) selected @endif>
                                                    {{ $mapped_Subject->combined_subject_name }} ({{ $mapped_Subject->name }})
                                                </option>
                                            @endif
                                        @else
                                            <option value="{{ $mapped_Subject->sub_code }}" @if($isSelected) selected @endif>
                                                {{ $mapped_Subject->sub_code }} ({{ $mapped_Subject->name }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>

                     
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Date Of Practical Exam:<span class="text-danger">*</span>
                                    {{-- @if($details){{date('d-m-Y',strtotime($details->date_of_practical_exam))}}@endif --}}
                                </label>
                            </div>

                            <div class="col-md-9">
                              <input type="date" class="form-control" placeholder="Enter here">
                            </div>
                        </div>

                    </div>

             
                 <div class="col-md-6">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Paper Name<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="selcet" id="" class="form-control"  >
                                    
                                    <option value="1">--Select Subject Type--</option>
                                    <option value="{{$sub_code_name}}"></option>
                                    {{-- <option value="3">Option 3</option>
                                    <option value="4">Option 4</option> --}}
                                </select>
                            </div>
                    


                    </div>

                    <div class="row align-items-center mb-1">
                        <div class="col-md-3">
                            {{-- <label class="form-label">Subject External Maximum Mark<span class="text-danger">*</span></label> --}}
                        </div>

                        <div class="col-md-9">
                           {{-- <input type="text" class="form-control" placeholder="Enter here"> --}}
                        </div>
                    </div>
                      

                    </div>

                  
               


                    <div class="col-md-6 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Maximum Marks(Mid Term/ UT):<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input type="text" placeholder="Enter here" class="form-control">
                            </div>
                        </div>

                      


                    </div>
                    <div class="col-md-6 ">

                       
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Maximum Marks(Assignment/
                                    Presentation/Practical):<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input type="text" class="form-control" placeholder="Enter here">
                            </div>
                        </div>


                    </div>
                    {{-- <div class="col-md-6 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Batch<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="" id="" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>                            </div>
                        </div>
                    </div> --}}
                        {{-- <div class="col-md-6">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Status<span class="text-danger m-0">*</span></label>
                                </div>
    
                                <div class="col-md-9">
                                    <select name="" id="" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                    
                                    </select>                            </div>
                            </div> 
                        </div> --}}

                       


               
                 
                    
                 
                 
                   


                </div>
            </div>


               

            </div>
          
            
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

   
@endsection

   

   
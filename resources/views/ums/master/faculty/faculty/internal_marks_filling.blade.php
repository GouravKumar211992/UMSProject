{{-- @extends("admin.admin-meta")
@section("content") --}}


@extends('ums.master.faculty.faculty-meta')

  @section('content')

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col="">


    <!-- BEGIN: Content-->
    <form method="get" name="internal_form" onsubmit="return validateForm()">
		@csrf
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Internal Marks</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Internal Marks</li>
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
                    <h3>MID SEMESTER & ASSIGNMENT / PRESENTATION</h3>
                </div>
                <div class="row gy-0  mt-3 p-2 ">

                    
                    <div class="col-md-6">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course Code<span class="text-danger m-0">*</span>
                                  
                            </label>
                            </div>

                            <div class="col-md-9">
                                <select name="" id="" class="form-control">
                                    <option value="">--Please select Campus--</option>
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
                                <select id="semester" name="semester" class="form-control">
                                    <option value="">--Select Semester--</option>
                                    @if($mapped_Semesters)
                                    @foreach($mapped_Semesters as $mapped_Semester)
                                        <option value="{{ $mapped_Semester->id }}" @if(Request()->semester == $mapped_Semester->id) selected @endif>
                                            {{ $mapped_Semester->name }}
                                        </option>
                                    @endforeach
                                @endif
                                </select>
                                                     
                                   </div>
                        </div>

                       


                    </div>
                    <div class="col-md-6 ">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course Name<span class="text-danger m-0" > *<input type="text" value="{{($mapped_faculty)?$mapped_faculty->Course->name:''}}" hidden><p>{{($mapped_faculty)?$mapped_faculty->Course->name:''}}</p></span></label>
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
                                <select name="selcet" id="" class="form-control">
                                    
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
                                <select name="selcet" id="" class="form-control">
                                    
                                    <option value="1">--Select Session--</option>
                                    @foreach($sessions as $session)
                                    <option value="{{$session->academic_session}}" @if($session->academic_session==Request()->session) selected @endif>{{$session->academic_session}}</option>
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
                            </div>

                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter here">
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Institution Name:<span class="text-danger m-0">*</span></label>
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
                                <select name="sub_code" id="sub_code" class="form-control">
                                    <option value="">--Select Type--</option>
                                    @foreach($mapped_Subjects as $index=>$mapped_Subject)
                                @if(!Request()->sub_code==$mapped_Subject->sub_code)
                                    @php $sub_code_name =''; @endphp
                                @endif
                                @if((Request()->sub_code==$mapped_Subject->sub_code) && ((Request()->course==$mapped_Subject->course_id)))
                                    @php $sub_code_name = $mapped_Subject->name; @endphp
                                @endif
                                @if($mapped_Subject->internal_marking_type==1)
                                @if($index ==0)
                                <option value="{{$mapped_Subject->sub_code}}"@if(Request()->sub_code==$mapped_Subject->sub_code) selected @endif>{{$mapped_Subject->combined_subject_name}}  ({{$mapped_Subject->name}})</option>
                                    
                                @endif
                                    @else
                            
                                <option value="{{$mapped_Subject->sub_code}}"@if((Request()->sub_code==$mapped_Subject->sub_code) && ((Request()->course==$mapped_Subject->course_id))) selected @endif>{{$mapped_Subject->sub_code}}  ({{$mapped_Subject->name}})</option>
                                @endif
                                @endforeach
                                </select>                     
                                   </div>
                        </div>

                     
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Date Of Internal Exam:<span class="text-danger">*</span>
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
                            <select name="sub_code" id="sub_code" class="form-control">
                                <option value="">--Select Subject--</option>
                                @foreach($mapped_Subjects as $mapped_Subject)
                                    <option value="{{ $mapped_Subject->sub_code }}" @if(Request()->sub_code == $mapped_Subject->sub_code) selected @endif>
                                        @if($mapped_Subject->internal_marking_type == 1)
                                            {{ $mapped_Subject->combined_subject_name }} ({{ $mapped_Subject->name }})
                                        @else
                                            {{ $mapped_Subject->sub_code }} ({{ $mapped_Subject->name }})
                                        @endif
                                    </option>
                                @endforeach
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
          
            
 


   

         
  @endsection
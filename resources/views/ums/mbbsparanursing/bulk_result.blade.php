@extends("ums.admin.admin-meta")
@section('content')
{{-- <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
  
<!-- BEGIN: Content-->
@if(!Request()->result_query_string)
<form method="GET" id="form_data" action="">

<div class="app-content content ">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper container-xxl p-0">
      <div class="content-header row">
          <div class="content-header-left col-md-5 mb-2">
              <div class="row breadcrumbs-top">
                  <div class="col-12">
                      <h2 class="content-header-title float-start mb-0">Exam Schedule</h2>
                      <div class="breadcrumb-wrapper">
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>  
                          </ol>
                      </div>
                  </div>
              </div>
          </div>
          <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
              <div class="form-group breadcrumb-right"> 
                <button class="btn btn-primary btn-sm" type="submit" name="submit_form">
                  <i data-feather="check-circle" ></i>
                  Get Report
              </button>

              <button class="btn btn-outline-secondary btn-sm" id="exportButton" type="button" onClick="window.print()">
                <i data-feather="share" class="font-small-4 me-50"></i>Print
            </button>
           
             <button class="btn btn-warning box-shadow-2 btn-sm  mb-sm-0 mb-50" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>Reset</button>
             
                     
                       
              </div>
          </div>
      </div>
      <div class="customernewsection-form poreportlistview p-1">
          <div class="row">
              <!-- First Row -->
              <div class="col-md-3">
                  <div class="mb-1">
                      <label class="form-label">Courses:</label>
                      <select data-live-search="true" name="course_id" id="course_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                        <option value="">--Choose Course--</option>
                        @foreach($courses as $course)
                            <option value="{{$course->id}}" @if(Request()->course_id==$course->id) selected @endif >{{$course->name}}</option>
                        @endforeach
                    </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-1">
                      <label class="form-label">Semester:</label>
                      <select name="semester_id" id="semester_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single ">
                        <option value="">--Choose Semester--</option>
                        @foreach($semesters as $semester)
                            <option value="{{$semester->id}}" @if(Request()->semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                        @endforeach
                    </select>
                </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-1">
                      <label class="form-label">Batch:</label>
                      <select name="batch" id="batch" style="border-color: #c0c0c0;" class="form-control">
                        <option value="">--Batch--</option>
                        @foreach($batchPrefix as $semester)
                        <option value="{{ $semester }}" @if(Request()->semester_id == $semester) selected @endif>
                            {{ $semester }}
                        </option>
                    @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-1">
                      <label class="form-label">Result Type:</label>
                      <select name="exam_type" id="exam_type" style="border-color: #c0c0c0;" class="form-control">
                        <option value="0" @if(Request()->exam_type==0) selected @endif>Regular</option>
                        <option value="1" @if(Request()->exam_type==1) selected @endif>Scrutiny</option>
                        <option value="2" @if(Request()->exam_type==2) selected @endif>Challenge</option>
                        <option value="3" @if(Request()->exam_type==3) selected @endif>Supplementary</option>
                    </select>
                  </div>
              </div>
          </div>
         
      </div>
  </div>
</div>
</form>
@endif
@if(Request()->result_query_string)
<div class="col-md-12 text-center print_hide">
    <button type="button" onClick="window.print()" class="btn btn-info">Print</button>
</div>
@endif

    @foreach($students as $student)
    @php
        $exam_year_array = $student->exam_year_array;
        $result_single = $student->result_single;
        $results = $student->results;
        $student_details = $student->studentPhoto($result_single->roll_no);
        $course = $result_single->course;
    @endphp


    @if($results->count()>0)
    @php
    $results_for_total = $student->get_semester_result(1);
    @endphp
    <div class="result result_full_page_container pagebreak" style="margin: auto;">
        <table class="result-head" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:74%;" rowspan="3" class="qr_content"></td>
                <th style="width:70px;">Serial No.</th>
                <th style="width:20px;">:</th>
                <th class="result-sr" style="width:100px;text-align: left;">{{sprintf('%08d', $result_single->serial_no)}}</th>
            </tr>
            <tr class="result-roll">
                <th>Roll No.</th>
                <th style="width:20px;">:</th>
                <th style="text-align: left;" class="qr_roll_no">{{$result_single->roll_no}}</th>
            </tr>
            <tr>
                <th colspan="2">&nbsp;</th>
            </tr>
        </table>

		<div class="header">
            {{-- @if(Auth::guard('admin')->check()==false || Request()->student=='true')
            <img src="{{asset('images/marklogo.png')}}" >
            @endif --}}
        </div>
		<div class="header-1">
         <p style="font-size: 25px !important;">Statement of 
         @if($result_single->course_id==49)
         Marks
        @else
            Marks
        @endif
        </p>
		
         @if($result_single->course_id==49)
         <p class="">M.B.B.S. BATCH : {{batchFunctionReturn($result_single->roll_no)}}</p>
         <p style="text-transform: uppercase;">{{$result_single->semester_details->name}}</p>
         @else
         <p class="">{{$result_single->course_name()}}</p>
         @endif
         <p style="text-transform: uppercase;">
            @if($result_single->course_id!=49)
            {{$result_single->semester_details->name}}
            @endif

            {{$result_single->mbbs_check_suppementary($results)}} 
            EXAMINATION, {{strtoupper($result_single->session_name)}}
        </p>
        <br/>
        <br/>
		</div>
        <table class="head2" cellpadding="0" cellspacing="0">
             <tr style="font-weight: bold;">
                 <td style="width: 80%;">
                    <table class="student-details" cellpadding="0" cellspacing="0">
                    @if($course && $course->campuse && $course->campuse->id > 1)
                    <tr>
                        <td style="font-weight: bold;">Name of the Institution</td>
                        <th style="width:50px;"> :</th>
                        <td><b>{{strtoupper($course->campuse->name)}}</b></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;"></td>
                        <th style="width:50px;"> </th>
                        <td></td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-weight: bold;">Enrollment No.</td>
                        <th style="width:50px;"> :</th>
                        <td class="qr_enrollment_no"><b>{{$result_single->enrollment_no}}</b></td>
                    </tr>
                    <tr>
                       <td style="width:200px;">Name of the Student</td>
                        <th style="width:50px;"> :</th>
                        <td style="font-weight:normal" class="qr_name"> {{strtoupper($result_single->student->full_name)}}</td>
                   </tr>
                   <tr>
                       <td>Father’s Name</td>
                        <th style="width:50px;"> :</th>
                        <td style="font-weight:normal"> {{strtoupper($result_single->student->father_first_name)}}</td>
                   </tr>
                   <tr>
                       <td>Mother’s Name</td>
                        <th style="width:50px;"> :</th>
                        <td style="font-weight:normal"> {{strtoupper($result_single->student->mother_first_name)}}</td>
                   </tr> 
                </table>
            </td>
                 <td style="text-align: right;">
                    <img src="{{$result_single->student->photo}}" style="border: #afafaf thin solid;padding: 3px;margin-top: -40px;width: 130px;height: auto;" alt="">
                 </td>
             </tr>
        </table>

        @if($result_single->course_id==49)

        <!-- For MBBS Results -->
        @php $student_batch = batchFunctionMbbs($result_single->roll_no); @endphp
        @if($student_batch=='2018-2019' || $student_batch=='2019-2020' || $student_batch=='2020-2021' || $student_batch=='2021-2022')
            @if($result_single->semester_number==3 || $result_single->semester_number==4)
                {{--@include('admin.result.mbbs-tr.view3')--}}
                @if($student_batch=='2018-2019')
                    @include('admin.result.mbbs-tr.view2')
                @elseif(substr($result_single->approval_date,0,10)=='2024-05-09')
                    @include('admin.result.mbbs-tr.view_2019_2020And2020_2021')
                @else
                    @include('admin.result.mbbs-tr.view4')
                @endif
            @else
                @if($student_batch=='2018-2019' && ($result_single->semester_number==1 || $result_single->semester_number==2) )
                    @include('admin.result.mbbs-tr.view2')
                @else
                    @include('admin.result.mbbs-tr.view1')
                @endif
            @endif
        @endif
            @if($student_batch=='2016-2017' ||  $student_batch=='2017-2018')
                @include('admin.result.mbbs-tr.view2')
            @endif

        @elseif($result_single->course_id==64)
            @include('admin.result.nursing-marksheet')
        @endif

        <!-- For Nursing Results -->

        <br/>
         <table class="result-footer" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 20%;text-align:left;">
					Date : {{($result_single->approval_date)?date('d-m-Y',strtotime($result_single->approval_date)):''}}
                    <br/>
					Place : Lucknow
				</td>
                @if(Auth::guard('admin')->check()==true && Request()->student!='true')
                <td style="width: 20%;vertical-align: bottom;"><br>Prepared By</td>
                <td style="width: 28%;vertical-align: bottom;"><br>Verified By</td>
                <td style="width: 40%;vertical-align: bottom;text-align: right;font-size: 19px;">
                    <!-- <img src="{{asset('signatures/coe.png')}}" alt="" style="height:50px"/> -->
                    <br>
                    Controller of Examination
                </td>
                @elseif(Request()->student=='true')
                <td style="width: 25%;vertical-align: bottom;"></td>
                <td style="width: 25%;vertical-align: bottom;"></td>
                <td style="width: 25%;vertical-align: bottom;">
                    <img src="{{asset('signatures/coe.png')}}" alt="" style="height:50px"/>
                    <br>
                    Controller of Examination
                </td>
                {{-- @elseif(Auth::guard('student')->check()==true) --}}
                <td style="width: 25%;vertical-align: bottom;"></td>
                <td style="width: 25%;vertical-align: bottom;"></td>
                <td style="width: 25%;vertical-align: bottom;">
                    <img src="{{asset('signatures/coe.png')}}" alt="" style="height:50px"/>
                    <br>
                    Controller of Examination
                </td>
                @endif
            </tr> 
         </table>

    </div>
    @endif

<div class="print_hide"><br></div>

@endforeach

   
  @endsection
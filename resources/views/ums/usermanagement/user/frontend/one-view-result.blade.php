@extends('student.layouts.app1')
@section('content')

<link href="/assets/frontend/css/result.css" rel="stylesheet">
<link href="/assets/frontend/css/result-style.css" rel="stylesheet">

<style>
#example1 {
  border: 2px solid black;
  padding: 25px;
  background: url('assets/viewresult.jpg');
  background-repeat: repeat;
  background-size: auto;
  background-color: #ff7703;
  font-weight:300 !important;
}
td {
    font-size: 12px!important;
	
	}
.circular--square {
  border-radius: 50%;
        height: 100%;
        width: auto;
    }
    .auto-style2 {
        font-size: large;
    }
    .auto-style3 {
        width: 256px;
    }
    .auto-style4 {
        width: 207px;
    }
    .auto-style5 {
        width: 13px;
    }
    .auto-style6 {
        width: 268px;
    }
    .auto-style7 {
        width: 23px;
    }
    .auto-style10 {
        text-align: left;
    }
    .auto-style11 {
        font-size: x-large;
        font-weight: normal;
        color: #000000;
    }
    .auto-style12 {
        color: #000000;
    }
    .auto-style13 {
        width: 256px;
        font-weight: bold;
    }
    .auto-style14 {
        color: #000;
        font-weight: bold;
    }
    .auto-style16 {
        width: 178px;
    }
    .auto-style17 {
        width: 309px;
    }
    .auto-style18 {
        color: #FF3300;
    }
    .auto-style20 {
        color: #FF0000;
        font-weight: bold;
    }
    .auto-style21 {
        width: 211px;
    }
</style>
	
  
<body>


    <form  action="./result-login" id="form1">
    <div id="example1">

         <div class="container" style="margin-top:1px">
        <center>
        
         <table id="tblHeader" style="width:100%;">
            <tbody>
                <tr>
                     <td>
                         <img src="\assets\frontend\images\icon.png" class="circular--square" >
                     </td>
                    <td style="font-family:'Times New Roman'">
                        <center><h2 class="auto-style14">Dr Shakuntala Misra National Rehabilitation University</h2></center>
                    </td>
                      <td>
                    <img src="\assets\frontend\images\icon.png" class="circular--square" >
                      </td>
                 </tr>
                 
             </tbody>
         </table>
            <hr>
        <div class="col-md-12 text-center">
            <input type="button" value="PRINT" onclick="print()" class="btn btn-success">
            <br>
            <br>
        </div>
        </div>

    <div class="container" style="background-color: #FFFFFF;">
         <h3 class="auto-style12">STUDENT RESULT</h3>
        
         <table id="tbl1" class="table table-hover" style="color: #000;;">
            <tr>
                <th>Institute Code & Name</th>
                <th colspan="4">({{$result_single->course->campuse->campus_code}}) {{$result_single->course->campuse->name}}</th>
            </tr>
            <tr>
                <th>Course Code & Name</th>
                <td>({{$result_single->course_name()}}) {{$result_single->course_description()}}</td>
                <th>Branch Code & Name</th>
                <td>{{@$result_single->course->stream->name}}</td>
                <td rowspan="4">
                    @if($student_details && $student_details->photo)
                    <img src="{{$student_details->photo}}">
                    @elseif($examData && $examData->photo)
                    <img src="{{$examData->photo}}">
                    @endif
                </td>
            </tr>
            <tr>
                <th>Roll No</th>
                <td>{{$student->roll_number}}</td>
                <th>Enrollment No</th>
                <td>{{$student->enrollment_no}}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{$student->full_name}}</td>
                <th>Hindi Name</th>
                <td>{{$student->full_name}}</td>
            </tr>
            <tr>
                <th>Father's Name</th>
                <td>{{$student->father_first_name}}</td>
                <th>Gender</th>
                <td>{{$student->gender}}</td>
            </tr>
        </table>

@php
 $final_result = [];
 $final_cgpa = '';
 $result_overall = '';
@endphp
@foreach($results_session_wise as $index=>$result_row)
@php $get_semester_result = $result_row->get_semester_result(1); @endphp
<div class="panel-group" id="accordion{{$index}}">
    <div class="panel panel-default">

        <div class="panel-heading bg-red accordion-toggle text-bold" style="cursor: pointer;" data-toggle="collapse" data-parent="#accordion{{$index}}" href="#menuOne{{$index}}">
            <span class="glyphicon glyphicon-plus"></span>
                Session : {{$result_row->exam_session}} ({{$result_row->back_status_text}})
                , Semester : {{$result_row->semester_details->semester_number}}
                , Result : {{$result_row->result_full_text}}
                , Marks : {{$get_semester_result->sum('total_marks')}}/{{$get_semester_result->sum('max_total_marks')}}
                
            @foreach($semesters as $semester)
            @php
                $final_result[$result_row->semester_details->semester_number]['semester_name'] = $result_row->semester_details->name;
                $final_result[$result_row->semester_details->semester_number]['total_marks'] = $get_semester_result->sum('total_marks');
                $final_result[$result_row->semester_details->semester_number]['result_full_text'] = $result_row->result_full_text;
            @endphp
            @endforeach
            @php $final_cgpa = $result_row->cgpa; @endphp
            @php $result_overall = $result_row->result_overall; @endphp
        </div>
        <div id="menuOne{{$index}}"  class="panel-collapse collapse">
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <th>Date Of Declaration</th>
                        <td>{{date('d-m-Y',strtotime($result_row->approval_date))}}</td>
                        <th>Total Subjects</th>
                        <td>{{$get_semester_result->count()}}</td>
                    </tr>
                    <tr>
                        <th>SGPA</th>
                        <td>{{$result_row->sgpa}}</td>
                        <th>CGPA</th>
                        <td>{{$result_row->cgpa}}</td>
                    </tr>
                    <tr>
                        <th>Total Marks Obt</th>
                        <td>{{$get_semester_result->sum('total_marks')}}</td>
                        <th>Result Status</th>
                        <td>{{$result_row->result}}</td>
                    </tr>
                </table>
                <table class="table">
                    <tr class="bg-green">
                        <th>Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Internal</th>
                        <th>External</th>
                        <th>Back Paper</th>
                        <th>Grade</th>
                    </tr>
                    @php $session_wise_result = $result_row->get_session_wise_result(); @endphp
                    @foreach($session_wise_result as $semester_result_row)
                    @php $back_paper_marks = (object)$semester_result_row->back_paper_marks(); @endphp
                    <tr  class="@if($back_paper_marks->back_paper > 0 ) success  @endif @if( $semester_result_row->grade_letter=='F') danger  @endif ">
                        <td>{{$semester_result_row->subject_code}}</td>
                        <td>{{ucwords(@$semester_result_row->subject->name)}}</td>
                        <td>{{ucwords(@$semester_result_row->subject->subject_type)}}</td>
                        <td>{{$back_paper_marks->internal_marks}}</td>
                        <td>{{$back_paper_marks->external_marks}}</td>
                        <td>{{$back_paper_marks->back_paper}}</td>
                        <td>{{$semester_result_row->grade_letter}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

    </div>
</div>
@endforeach

<table class="table">
    <tr>
        <th class="bg-success" colspan="3">Result Summary</th>
    </tr>
    @foreach($final_result as $final_result_row)
    <tr>
        <th style="width: 200px;">{{$final_result_row['semester_name']}}</th>
        <th style="width: 50px;"> : </th>
        <th>{{$final_result_row['total_marks']}} ({{$final_result_row['result_full_text']}})</th>
    </tr>
    @endforeach
    <tr>
        <th style="width: 200px;">OVERALL RESULT</th>
        <th style="width: 50px;"> : </th>
        <th>{{$result_overall}}</th>
    </tr>
    <tr>
        <th style="width: 200px;">CGPS</th>
        <th style="width: 50px;"> : </th>
        <th>{{$result_single->cgpa}}/10</th>
    </tr>
</table>

    <BR>
    <BR>

    </div>

    <BR>
    <BR>
 
@endsection


{{-- Scripts --}}
@section('scripts')
@parent
<script src="/assets/frontend/js/result.js"></script>
<script type="text/javascript">
    $(function () {
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    })
</script>
@endsection
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
  color:white;
  background-color: #ff7703;
  font-weight:300 !important;
}
td {
    font-size: 12px!important;
	
	}
.circular--square {
  border-radius: 50%;
        height: 111px;
        width: 95px;
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
        color: #3333CC;
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

@if($all_data)
    <form  action="./result-login" id="form1">
    <div id="example1">

         <div class="container" style="margin-top:1px">
        <center>
        
         <table id="tblHeader" style="width:1000px;">
            <tbody>
                <tr>
                     <td>
                         <img src="\assets\frontend\images\icon.png" class="circular--square" width="80" height="80">
                     </td>
                    <td style="font-family:'Times New Roman'">
                        <center><h1 class="auto-style14">Dr Shakuntala Misra National Rehabilitation University</h1></center>
                    </td>
                      <td>
                    <img src="\assets\frontend\images\icon.png" class="circular--square" width="100" height="100">
                      </td>
                 </tr>
                 
             </tbody>
         </table>
            <hr>
             <br>
      </center>
             <br />
              <div class="row">
            <div class="col-sm-5"></div>
            <div class="col-sm-2">
              <input type="submit" value="PRINT" onclick="print()" class="btn btn-success">
            </div>
            <div class="col-sm-5"></div>
          </div>
         <br><br>
        </center>
        </div>

    <div class="container" style="width:1100px; height: 255px; background-color: #FFFFFF;">
         <h3 class="auto-style12">STUDENT RESULT</h3>
        
         <table id="tbl1" class="table table-hover" style="background-color:#CCCCCC; color:black; height: 190px;">
            <tr>
                <td class="auto-style16"><b>Institute Code & Name</b></td><td><b>:</b></td>
                <td colspan="4">(<b><span id="lblInstCode">898</span></b>)<b><span id="lblInst">{{$campuse['name']}}</span></b></td>
                

                <td rowspan="5"><img src="{{$application->photo_url}}" width="100" height="100" /> </td>
            </tr>
            <tr>
                <td class="auto-style16"><b>Course Code & Name</b></td><td><b>:</b></td>
                <td class="auto-style3">(<b><span id="lbCourseCode">{{$course['course_description']}}</span></b>)<b><span id="lblCourseName">{{$course['name']}}</span></b></td>
                <td class="auto-style21"><b>Branch Code & Name</b></td>
                <td class="auto-style5"><b>:</b></td>
                <td class="auto-style17">(<b><span id="lblBranchCode">40</span></b>)<b><span id="lblBranchName">{{$stream['name']}}</span></b></td>
            </tr>
            <tr>
                <td class="auto-style16"><b>Roll No</b></td><td><b>:</b></td><td class="auto-style13"><span id="lblRollNo">{{$data['roll_number']}} </span></td><td class="auto-style21"><b>EnrollmentNo</b></td><td class="auto-style5"><b>:</b></td><td class="auto-style17"><b><span id="lblEnroll">{{$data['enrollment_no']}}</span></b></td>
            </tr>
            <tr>
                <td class="auto-style16"><b>Name</b></td><td><b>:</b></td><td class="auto-style13"><span id="lblStName">{{$data['first_Name']}}&nbsp;{{$data['middle_Name']}}&nbsp;{{$data['last_Name']}}</span></td><td class="auto-style21"><b>Hindi Name</b></td><td class="auto-style5"><b>:</b></td><td class="auto-style17"><b><span id="lblHindi"></span></b></td>
            </tr>
            <tr>
                <td class="auto-style16"><b>Father's Name</b></td><td><b>:</b></td><td class="auto-style13"><span id="lblFName">{{$data['father_first_name']}}&nbsp;{{$data['father_middle_Name']}}&nbsp;{{$data['father_last_name']}} </span></td><td class="auto-style21"><b>Gender</b></td><td class="auto-style5"><b>:</b></td><td class="auto-style17"><b><span id="lblGender">{{$data['gender']}}</span></b></td>
            </tr>
        </table>
      

    </div>
    <div class="container" style="width:1100px; background-color: #FFFFFF;"><br><br>
       
<div class="panel-group" id="accordion" style="min-height: 600px;">
<div class="panel panel-default">@foreach($all_data as $key=>$all_data)
<div class="panel-heading">
<h4 class="panel-title">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#menuOne">
<span class="glyphicon glyphicon-minus"></span>
    <b>Session:</b><span id="lblYear2021">{{$all_data['results'][0]->exam_session}}</span>&nbsp;&nbsp; <b>Result:</b><span id="lblResult2021">{{$all_data['pass']}}</span> &nbsp;&nbsp;( Regular )&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; <b>Marks:</b><span id="lblMarksObtain2021">{{$all_data['obtain']}}   </span>/<span id="lblMarksTotal2021">{{$all_data['max_marks'] }}   </span>
</a>&nbsp;&nbsp;&nbsp; <strong>COP:<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#menuOne"><span id="lblCOP2021" style="color: #FF6600">
@if(isset($all_data['back']))
@foreach($all_data['back'] as $key=>$bk)
{{$bk['subject_code']}}
@endforeach
@endif
</span> 
</a></strong></h4>
</div>
   
<div id="menuOne"  class="panel-collapse collapse in" style="width:1000px;color:black">
<div class="panel-body">
   <div class="container" style="width:900px;">
         
       <div style="width:895px; height: 740px;">
           
           <table id="tbl2" class="table table-hover">
               <tr>
                   <td class="auto-style6">Year</td><td class="auto-style7">:</td><td>
                    <span id="lblsemester2021">1</span></td><td class="auto-style4">Date Of Declaration</td><td class="auto-style5">:</td><td><span id="lblDateOfDeclare2021">{{date($all_data['results'][0]->created_at)}}</span></td>
            </tr>
            <tr>
                <td class="auto-style6">Total Subjects</td><td class="auto-style7">:</td><td><span id="lblTotalSubject2021">@php echo count($all_data['results']) @endphp</span> </td><td class="auto-style4">Theory Subjects</td><td class="auto-style5">:</td><td><span id="lblTheory2021">{{$all_data['t_count']}}</span></td>
            </tr>
            <tr>
                <td class="auto-style6">Practical Subjects</td><td class="auto-style7">:</td><td><span id="lblPractical2021">{{$all_data['p_count']}}</span></td><td class="auto-style4">Total Marks Obt</td><td class="auto-style5">:</td><td><span id="lblTotalObtain2021">{{$all_data['obtain']}}</span></td>
            </tr>
            <tr>
                <td class="auto-style6">Result Status</td><td class="auto-style7">:</td><td><span id="lblResultStatus2021">{{$all_data['carryover']}}</span></td><td class="auto-style4">SGPA</td><td class="auto-style5">:</td><td><span id="lblSGPA2021">{{$all_data['cgpa']}}</span></td>
            </tr>
            
        </table>


        <center> <h2 class="auto-style2">Theory Result</h2></center>
           <table id="tblTheory21" class="table table-hover">
               <tr>
                   <th>Paper Code</th><th>Paper Name</th><th>Internal</th><th>External</th>
                  
               </tr>
               @foreach($all_data['results'] as $show)
			   @if($show->subject->subject_type=='Theory')
               <tr class="auto-style18">
                    <td><span id="lblPap1ID">{{$show['subject_code']}}</span></td>

                    <td><span id="lblPap1Name"> {{$show->subject->name}}</span></td><td><span id="lblPap1TE">{{$show['internal_marks']}}</span></td><td><span id="lblPap1TS">{{$show['external_marks']}} </span></td>
               </tr>
			   @endif
               @endforeach
               
               
           </table>

           <center> <h2 class="auto-style2">Practical Result</h2></center>
           <table id="tblPractical21" class="table table-hover">
               <tr>
                   <th>Practical Code</th><th>Practical Name</th><th>Internal</th><th>External</th>
                  
               </tr>
               @foreach($all_data['results'] as $show)
			   @if($show->subject->subject_type=='Practical')
               <tr class="auto-style18">
                    <td><span id="lblPap1ID">{{$show['subject_code']}}</span></td>

                    <td><span id="lblPap1Name">{{$show->subject->name}}</span></td><td><span id="lblPap1TE">{{$show['internal_marks']}}</span></td><td><span id="lblPap1TS">{{$show['external_marks']}} </span></td>
               </tr>
			   @endif
               @endforeach
              
         
               
           </table>
            </div>                    
                
    </div>
    <br />
     
    </div>
</div>

    


    
            

  @endforeach
</div>

        
    </div>
	
	<div class="container" style="background-color:white;color:black;width:1075px;"">
            <h3 class="auto-style10">RESULT SUMMARY</h3>
        <table id="tblResult" class="table table-bordered" style="background-color:#CCCCCC; color:black;">
            <tr>
                <td><b>First Year Marks</b></td><td>
                    <b>
                    <span id="lblFirstYearMarks">733    </span></b></td><td><b>First Year Status</b></td><td><b><span id="lblFirstYearStatus">Pass</span></b></td>
            </tr>
          
            <tr>
                <td class="auto-style20">Result Status</td><td class="auto-style20">Pass</td><td class="auto-style20">Division</td><td class="auto-style20">Second</td>
           </tr>
        </table>
      
        </div>
	
    </div>
          
    </form>

@else

<h4 class="text-center">Result Not Generated</h4>
@endif


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
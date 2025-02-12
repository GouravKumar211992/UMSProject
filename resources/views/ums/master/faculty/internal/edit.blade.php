@extends('faculty.layouts.app')

{{-- Web site Title --}} 
@section('title') Add Internal Marks :: @parent @stop 
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<style>
	.head1 td{
		border: #000000 1px solid; border-right: none; border-top: none;
		}
	.head2 td{
		border: #000000 solid; 
		border-left: none;
		border-top:none;
	}
	.head1 p{
		display:inline;
		/*font-size:15px;*/
		margin-left:5px;
		
	} .head1 input{
		width: auto;
		height: 30px;
		display:inline;
		margin-left:5px;
	}
	.head2 input{
		width: auto;
		height: 30px;
		display:inline;
		margin-left:5px;
	}.head1 select{
		width: auto;
		height: 45px;
		display:inline;
		margin-left:5px;
	}
	.head2 select{
		width: auto;
		height: 45px;
		display:inline;
		margin-left:5px;
	}
	</style>
	
<script>
function validateForm() {
  let sem_date = document.forms["internal_form"]["date_of_semester"].value;
  let assign_date = document.forms["internal_form"]["date_of_assign"].value;
  let max_internal = document.forms["internal_form"]["internal_maximum"].value;
  let maximum_assign = document.forms["internal_form"]["assign_maximum"].value;
  if (sem_date == "") {
    $("#error").dialog().text("Date Of Semester must be filled out");
    return false;
  }if (assign_date == "") {
    $("#error").dialog().text("Date Of Assignment/Presentation must be filled out");
    return false;
  }if (max_internal == "") {
    $("#error").dialog().text("Maximum Marks must be filled out");
    return false;
  }if (maximum_assign == "") {
    $("#error").dialog().text("Maximum Marks Assignment/Presentation must be filled out");
    return false;
  }
}
</script>
<section class="content mb-3">
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
    <div class="container-fluid">
      <div class="row mb-3 align-items-center">
        <div class="col-4">
          <a href="javascript:history.back()" class="btn btn-secondary btn-back">Go Back</a> 
        </div>
        <div class="col-md-12">
          <div class="border-bottom mt-3 mb-2 border-innerdashed"> </div>
        </div>
      </div>
    <form method="get" name="internal_form" onsubmit="return validateForm()">
		<table style="border:solid" width="100%">
        <thead class="head1">
		<tr  align="center" bordercolordark="#000000"><td colspan="8">Dr.SHAKUNTALA MISRA NATIONAL REHABILIATION UNIVERSITY, LUCKNOW</td>
		  </tr>
	  <tr  align="center" bordercolordark="#000000"><td colspan="8">AWARD SHEET OF INTERNAL MARKS</td>
		  </tr>
		  <tr  align="center"><td colspan="8">MID SEMESTER & ASSIGNMENT /PRESENTATION</td>
		  </tr>
		  <tr >
		  <td colspan="2">Course Code:</td><td colspan="2">  <p>{{$mapped_faculty->course_id}}</p></td>
		  <td class="head2" colspan="2">Course Name:</td><td colspan="2"> <input type="text" value="{{$mapped_faculty->Course->name}}" hidden><p>{{$mapped_faculty->Course->name}}</p></td>
		  </tr>
		  <tr >
		  <td colspan="2">Semester Name:</td><td colspan="2">  <input type="text" style="float: right" hidden><p>{{$mapped_faculty->Semester->name}}</p></td>
		  <td class="head2" colspan="2">Session:</td><td colspan="2"> <select id="session" name="session"  class="form-control">
		  <option value="">--Select Session--</option>
		  @foreach($sessions as $session)
		  <option value="{{$session->academic_session}}" @if($session)=={{$session['academic_session']}} selected @endif>{{$session->academic_session}}</option>
		  @endforeach
		  </select></td>
		  </tr>
		  <tr >
		  <td colspan="2">Institution Code: </td><td colspan="2"> <p>{{$mapped_faculty->Course->Campuse->id}}</p></td>
		  <td class="head2" colspan="2">Institution Name:</td><td colspan="2"> <input type="text" style="float: right" hidden><p>{{$mapped_faculty->Course->Campuse->name}}</p></td>
		  </tr>
		  <tr>
		  <td colspan="2">Paper Code:</td><td colspan="2">  
		  <select id="sub_code" name="sub_code"  class="form-control">
		  <option value="">--Select Subject--</option>
		  @foreach($mapped_Subjects as $mapped_Subject)
		  <option value="{{$mapped_Subject->sub_code}}" @if($sub_code)=={{$mapped_Subject['sub_code']}} selected @endif>{{$mapped_Subject->sub_code}}</option>
		  @endforeach
		  </select>
		  <span class="text-danger">{{ $errors->first('sub_code') }}</span></td>
		  <td colspan="2">Paper Name:</td><td colspan="2"> <input id="sub_name" name="sub_name"  class="form-control" @if($sub_code)value="{{$sub_name->name}}"@endif /></td>
		  </tr>
		  
		  <tr >
		  <td colspan="2">Date Of Semester Exam:</td><td colspan="2">  <input type="date" name="date_of_semester" class="form-control" value="{{$date_of_semester}}"></td>
		  <td class="head2" colspan="2">Date Of Assignment/Presentation:</td><td colspan="2"> <input type="date" name="date_of_assign" class="form-control" value="{{$date_of_assign}}"></td>
		  </tr>
		  <tr >
		  <td colspan="2">Maximum Marks: </td><td colspan="2"> <input type="text" id="internal_maximum" name="internal_maximum" class="form-control" value="{{$internal_maximum}}"></td>
		  <td class="head2" colspan="2">Maximum Marks(Assignment/Presentation):</td><td colspan="2"> <input type="text" class="form-control" id="assign_maximum"name="assign_maximum" value="{{$assign_maximum}}" ></td>
		  </tr>
		  <tr>
			  <td colspan="8">&nbsp; </td>
		  </tr>
		  </thead >
		     
		  
		  </table>
		  <input type="submit" class="btn btn-primary">
		  </form>
		  <form method="post" action="{{route('internal-marks')}}">
						@csrf
		  <table style="border:solid" class="table1"width="100%">.
		  <thead class="head1">
		  <tr><td rowspan="2">
			  Sr. No.</td>
			  <td rowspan="2">
			  Name Of Student</td>
			  <td rowspan="2">
			  Enrollment No.</td>
			  <td rowspan="2">
			  Roll No.</td>
			  <td rowspan="2">
			  Mid Semester Marks</td>
			  <td rowspan="2">
			  Assignment/Presentation Marks</td>
			  <td colspan="2">
			    Total Marks
		      </td>
			  </tr>
		  <tr>
		  		<td>In Figure</td>
				 <td>In Words</td>
		  </tr></thead>
	  
	  <tbody class="head1">
	  @foreach($subjects as $key=>$subject)
	  <tr>
			<td>{{$key+1}}</td>
					<input type="text" name="campus_id[]" value="{{$mapped_faculty->Course->Campuse->id}}"hidden>
					<input type="text" name="campus_name[]" value="{{$mapped_faculty->Course->Campuse->name}}"hidden>
					<input type="text" name="program_id[]" value="{{$mapped_faculty->program_id}}" hidden>
					<input type="text" name="program_name[]" value="{{$mapped_faculty->Category->name}}" hidden>
					<input type="text" name="course_id[]" value="{{$mapped_faculty->course_id}}" hidden>
					<input type="text" name="course_name[]" value="{{$mapped_faculty->Course->name}}" hidden>
					<input type="text" name="semester_id[]" value="{{$mapped_faculty->semester_id}}" hidden>
					<input type="text" name="semester_name[]" value="{{$mapped_faculty->Semester->name}}" hidden>
					<input type="text" name="session[]" value="{{$sessions[0]->academic_session}}" hidden>
					<input type="text" name="faculty_id[]" value="{{$mapped_faculty->faculty_id}}" hidden>
					<input type="text" class="form-control" readonly="true" name="subject_code[]" value="{{$sub_code}}" hidden>
					<input type="text" class="form-control" readonly="true" name="subject_name[]" value="{{$sub_name->name}}" hidden>
					<input type="text" name="semester_date[]" value="{{$date_of_semester}}" hidden>
					<input type="text" name="assign_date[]" value="{{$date_of_assign}}" hidden>
					<input type="text" name="maximum_internal[]" value="{{$internal_maximum}}" hidden>
					<input type="text" name="maximum_assign[]" value="{{$assign_maximum}}" hidden>
					<td><input type="text" class="form-control" readonly="true" name="student_name[]" value="{{$subject->student->first_Name}} {{$subject->student->middle_Name}} {{$subject->student->last_Name}}" hidden><span id="lblPap1ID">{{$subject->student->first_Name}} {{$subject->student->middle_Name}} {{$subject->student->last_Name}}</span></td>
			<td><input type="text" class="form-control" readonly="true" name="enrollment_number[]" value="{{$subject->enrollment_number}}" hidden><span id="lblPap1ID">{{$subject->enrollment_number}}</span></td>
			<td><input type="text" class="form-control" readonly="true" name="roll_number[]" value="{{$subject->roll_number}}" hidden><span id="lblPap1ID">{{$subject->roll_number}}</span></td>
			<td><input type="number" class="form-control obtain-internal-marks" name="mid_semester_marks[]" value="{{old('$mid_semester_marks[]')}}" ></td>
			<td><input type="number" class="form-control obtain-assign-marks"  name="assingnment_mark[]" value="{{old('$assingnment_mark[]')}}"></td>
			<td><input type="number" class="form-control" name="total_marks[]" value="{{old('$total_marks[]')}}" ></td>
			<td><input type="text" class="form-control"  name="total_marks_words[]" value="" ></td>
	    </tr>
		  @endforeach
	  </tbody>
	  
	</table>
	<div class="row">
				 <div class="col-sm-5"></div>
				 <div class="col-sm-2"><input type="submit" class="btn btn-success form-control"></div>
				 <div class="col-sm-5"></div>
			   </div>
	</form>

         



    
     
		
		<!--div class="row">
  <div class="container" style="width:1000px; background-color: #FFFFFF;">
       	<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div id=""  class="" style="width:1000px;color:black">
				<div class="panel-body">
					<form method="post" action="{{route('internal-marks')}}">
						@csrf
			<center> <head2 class="auto-style2">Internal Marks Filling</head2></center><br>
			   <table border="1" id="tblTheory21" class="table table-hover">
				   <tr>
					  <th class="thcenter">Roll Number</th>
					  <th class="thcenter">Student Name</th>
					  <th class="thcenter">Internal Maximum Marks</th>
					  <th class="thcenter">Internal Obtained Marks</th>
					</tr>
					<tr class="auto-style18 thcenter">
					  <td></td>
					  <td></td>
					  
					  
					  <td><input type="number"  class="form-control"  id="internal_maximum_marks"name="internal_maximum_marks" min="0" max="100" placeholder="Enter Maximum Internal Marks Here"></td>
					  <td></td>
					</tr>
					
					@foreach($subjects as $subject)
				   <tr class="auto-style18 thcenter">
					 <input type="text" class="form-control" readonly="true" name="sub_code[]" value="{{$sub_code}}" hidden>
					 <input type="text" class="form-control" readonly="true" name="enrollment_number[]" value="{{$subject->enrollment_number}}" hidden>
					 <input type="text" class="form-control" readonly="true" name="roll_number[]" value="{{$subject->roll_number}}" hidden>
					 <input type="text" class="form-control" readonly="true" name="session[]" value="{{$subject->session}}" hidden>
					 <input type="text" class="form-control" readonly="true" name="program[]" value="{{$subject->program_id}}" hidden >
					 <input type="text" class="form-control" readonly="true" name="course[]" value="{{$subject->course_id}}" hidden >
					 <input type="text" class="form-control" readonly="true" name="semester[]" value="{{$subject->semester_id}}" hidden >
					<td>
					  <span id="lblPap1ID">{{$subject->roll_number}}</span></td>
					  <td><span id="lblPap1ID">{{$subject->student->first_Name}} {{$subject->student->middle_Name}} {{$subject->student->last_Name}}</span></td>
					  <td><input type="text" class="form-control internal-maximum" readonly ></td>
					  <td><input type="number" min="0" max="100" type="text" class="form-control obtain-marks" placeholder="Enter Obtained Internal Marks Here"  name="internal_marks[]"> </td>
					  <td>
					  
				   </tr>
				  @endforeach
				  
				   

			   </table><br>
			   <div class="row">
				 <div class="col-sm-5"></div>
				 <div class="col-sm-2"><input type="submit" class="btn btn-success form-control"></div>
				 <div class="col-sm-5"></div>
			   </div>
		</form>

			   
				</div>                    
					
		</div>
		<br />
		 
		</div>
		</div>

</div>
  
</div-->
        
    </div>
</section>
<div id="error" title="Error">

</div>
@endsection

@section('styles')
    <style type="text/css"></style>
@endsection
@section('scripts')
<script>
$(document).ready(function(){
	
	
	
	$('.obtain-internal-marks').change(function(){
		
		var internal_maximum=parseInt($('#internal_maximum').val());
		var obtain_marks=$(this).val();
		if((obtain_marks < 0 || obtain_marks > internal_maximum))
		{	
			$("#error").dialog().text('Obtain Marks Must Be Less Than Internal Maximum Marks ');
			
			$(this).val('');
			$(this).css({'border':'1px solid red'});
		}else{
			$(this).css({'border':'0px solid red'});
		}
	});
	$('.obtain-assign-marks').change(function(){
		
		var assign_maximum=parseInt($('#assign_maximum').val());
		var obtain_marks=$(this).val();
		if((obtain_marks < 0 || obtain_marks > assign_maximum))
		{	
			$("#error").dialog().text('Obtain Marks Must Be Less Than Assignment/Presentation Maximum Marks ');
			
			$(this).val('');
			$(this).css({'border':'1px solid red'});
		}else{
			$(this).css({'border':'0px solid red'});
		}
		
		
	});
	
});
</script>
@endsection

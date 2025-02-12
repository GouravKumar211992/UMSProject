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
@if($msg)
    <div class="alert alert-success">
        {{ $msg}}
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
		  <td colspan="2">Course Code:</td><td colspan="2">  <p>{{($mapped_faculty)?$mapped_faculty->Course->name:''}}</p></td>
		  <td class="head2" colspan="2">Course Name:</td><td colspan="2"> <input type="text" value="{{($mapped_faculty)?$mapped_faculty->Course->name:''}}" hidden><p>{{($mapped_faculty)?$mapped_faculty->Course->name:''}}</p></td>
		  </tr>
		  <tr >
		  <td colspan="2">Semester Name:</td><td colspan="2">  <input type="text" style="float: right" hidden><p>{{($mapped_faculty)?$mapped_faculty->Semester->name:''}}</p></td>
		  <td class="head2" colspan="2">Session:</td><td colspan="2"> <select id="session" name="session"  class="form-control">
		  <option value="">--Select Session--</option>
		  @foreach($sessions as $session)
		  <option value="{{$session->academic_session}}" @if($session)=={{$session['academic_session']}} selected @endif>{{$session->academic_session}}</option>
		  @endforeach
		  </select></td>
		  </tr>
		  <tr >
		  <td colspan="2">Institution Code: </td><td colspan="2"> <p>{{($mapped_faculty)?$mapped_faculty->Course->Campuse->campus_code:''}}</p></td>
		  <td class="head2" colspan="2">Institution Name:</td><td colspan="2"> <input type="text" style="float: right" hidden><p>{{($mapped_faculty)?$mapped_faculty->Course->Campuse->name:''}}</p></td>
		  </tr>
		  <tr>
		  <td colspan="2">Paper Code:</td><td colspan="2">  
		  
		  <select id="sub_code" name="sub_code"  class="form-control" required>
		  <option value="">--Select Subject--</option>
		  @foreach($mapped_Subjects as $index=>$mapped_Subject)
		  @if(Request()->sub_code==$mapped_Subject->sub_code)
			  @php $sub_code_name = $mapped_Subject->name; @endphp
		  @endif
		  @if($mapped_Subject->internal_marking_type==1)
		  @if($index ==0)
		  <option value="{{$mapped_Subject->sub_code}}"@if(Request()->sub_code==$mapped_Subject->sub_code) selected @endif>{{$mapped_Subject->combined_subject_name}} ({{$mapped_Subject->name}})</option>
			
		  @endif
			@else
	  
		  <option value="{{$mapped_Subject->sub_code}}"@if(Request()->sub_code==$mapped_Subject->sub_code) selected @endif>{{$mapped_Subject->sub_code}}  ({{$mapped_Subject->name}})</option>
		  @endif
		  @endforeach
		  </select>
		  <span class="text-danger">{{ $errors->first('sub_code') }}</span></td>
		  <td colspan="2">Paper Name:</td><td colspan="2"> <input id="sub_name" name="sub_name"  class="form-control" value="{{$sub_code_name}}" readonly  required /></td>
		  </tr>
		  
		  <tr >
		  <td colspan="2">Date Of Internal Exam:</td><td colspan="2">  <input type="date" name="date_of_semester" class="form-control" value="{{$date_of_semester}}" required></td>
		  <td class="head2" colspan="2">Date Of Assignment/Presentation:</td><td colspan="2"> <input type="date" name="date_of_assign" class="form-control" value="{{$date_of_assign}}" required></td>
		  </tr>
		  <tr >
		  <td colspan="2">Maximum Marks: </td><td colspan="2"> <input type="text" id="internal_maximum" name="internal_maximum" class="form-control" value="{{$internal_maximum}}" required></td>
		  <td class="head2" colspan="2">Maximum Marks(Assignment/Presentation):</td><td colspan="2"> <input type="text" class="form-control" id="assign_maximum"name="assign_maximum" value="{{$assign_maximum}}" required ></td>
		  </tr>
		  <tr>
			  <td colspan="8">&nbsp; </td>
		  </tr>
		  </thead >
		     
		  
		  </table>
		  <input type="submit" class="btn btn-primary">
		  </form>
		  @if(count($students)>0)
		  <form method="post"  id="main_form">
						@csrf
		  <table style="border:solid" class="table1"width="100%">
		  <thead class="head1">
		  <tr>
			  <td rowspan="2"> Sr. No.</td>
			  <td rowspan="2"> Name Of Student</td>
			  <td rowspan="2"> Enrollment No.</td>
			  <td rowspan="2"> Roll No.</td>
			  <td rowspan="2"> Absent Status</td>
			  <td rowspan="2"> Mid Semester Marks</td>
			  <td rowspan="2"> Assignment/Presentation Marks</td>
			  <td colspan="2"> Total Marks </td>
		</tr>
		  <tr>
		  		<td>In Figure</td>
				 <td>In Words</td>
		  </tr></thead>
	  
	  <tbody class="head1">
	  @foreach($students as $key=>$student)
	  <tr>
			<td>{{$key+1}}
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
					<input type="text" name="max_internal[]" value="{{$student->Subject->internal_maximum_mark}}" hidden>
			</td>
			<td><input type="text" class="form-control" readonly="true" name="student_name[]" value="{{($student->student)?$student->student->first_name:$student->name}} " hidden><span id="lblPap1ID">{{($student->student)?$student->student->first_name:$student->name}}  </span></td>
			<td><input type="text" class="form-control" readonly="true" name="enrollment_number[]" value="{{$student->student->enrollment_no}}" hidden><span id="lblPap1ID">{{$student->student->enrollment_no}}</span></td>
			<td><input type="text" class="form-control" readonly="true" name="roll_number[]" value="{{$student->rollno}}" hidden><span id="lblPap1ID">{{$student->rollno}}</span></td>
			<td>
				<input type="checkbox" class="btn btn-info absent_status">
				<input type="hidden" value='0' class="absent_status_text" name="absent_status[]">
			</td>
			<td><input type="text" class="form-control numbersOnly fillable obtain-internal-marks" name="mid_semester_marks[]" value="{{old('$mid_semester_marks[]')}}" required></td>
			<td><input type="text" class="form-control numbersOnly fillable obtain-assign-marks"  name="assingnment_mark[]" value="{{old('$assingnment_mark[]')}}" required></td>
			<td><input type="text" class="form-control numbersOnly fillable total_marks" name="total_marks[]" value="{{old('$total_marks[]')}}" readonly style="width: 100px !important;"  ></td>
			<td><input type="text" class="form-control "  name="total_marks_words[]" value="" readonly  style="width: 250px !important;"  ></td>
	    </tr>
		  @endforeach
	  </tbody>
	  
	</table>
	<div class="row">
		<div class="col-sm-5"></div>
		<div class="col-sm-2"><input type="button" class="btn btn-success form-control save_pge" value="Save"></div>
		<div class="col-sm-5"></div>
	</div>
	</form>
	@elseif($students)
	<table style="border:solid" class="table1" width="100%">
		  <thead class="head2">
		  <tr>
			  <td rowspan="14">No Students Found</td>
		</tr>
		</thead>		
	</table>
	@endif


        
    </div>
	@if($students)
	{{$students->appends(Request()->all())->links('partials.pagination')}}
@endif
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
		var assign_marks = parseInt($(this).val());
		var internal_marks = parseInt($(this).closest('tr').find('.obtain-internal-marks').val());
		var total_marks_object = $(this).closest('tr').find('.total_marks');
		var total_marks_words = $(this).closest('tr').find('.total_marks_words');
		total_marks_object.val(assign_marks+internal_marks);
		$.ajax({
			type:'GET',
			url:"{{url('faculty/get_number_in_works')}}/"+total_marks_object.val(),
			data:'_token = <?php echo csrf_token() ?>',
			success:function(data) {
				total_marks_words.val(data);
			}
		});
		
	});
	
	
$('.save_pge').click(function(){
//	var check_value = false;
	var check_value = true;
	$('.total_marks').each(function(index,value){
		if($(this).val() == ''){
			check_value = false;
//			check_value = true;
		}
	});
	if(check_value==true){
		$('.total_marks').each(function(index,value){
			if($(this).val()==''){
				$(this).closest('tr').remove();
			}
		});
		$('#main_form').submit();
	}else{
		$("#error").dialog().text('Please Fill All Records');
		
	}
});

$('.numbersOnly').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});

$('.absent_status').click(function(){
	var absent_status = $(this).prop('checked');
	var current_tr = $(this).closest('tr');
	if(absent_status==true){
		current_tr.find('.absent_status_text').val('1');
		current_tr.find('.fillable').val('ABSENT');
	}else{
		current_tr.find('.absent_status_text').val('0');
		current_tr.find('.fillable').val('');
	}
});

	
});
</script>
@endsection

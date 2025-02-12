@extends('faculty.layouts.app')

{{-- Web site Title --}} 
@section('title') Add Internal Marks :: @parent @stop 
@section('content')
<link href="/assets/frontend/css/style.css" rel="stylesheet" />
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
@include('faculty.partials.notifications')
    <div class="container-fluid">
      <div class="row mb-3 align-items-center">
        <div class="col-4">
          <a href="javascript:history.back()" class="btn btn-secondary btn-back">Go Back</a> 
        </div>
        <div class="col-md-12">
          <div class="border-bottom mt-3 mb-2 border-innerdashed"> </div>
        </div>
      </div>
    
	 <form method="POST" action="{{route('internal-marks-preview')}}" >
	 @csrf
		<table style="border:solid" width="100%">
        <thead class="head1">
		<tr  align="center" bordercolordark="#000000"><td colspan="8">Dr.SHAKUNTALA MISRA NATIONAL REHABILIATION UNIVERSITY, LUCKNOW</td>
		  </tr>
	  <tr  align="center" bordercolordark="#000000"><td colspan="8">AWARD SHEET OF INTERNAL MARKS</td>
		  </tr>
		  <tr  align="center"><td colspan="8">MID SEMESTER & ASSIGNMENT /PRESENTATION</td>
		  </tr>
		  
		  <tr>
		  <td colspan="2">Course Code:</td><td colspan="2">  <p>{{$internal_data_first->Course->color_code}}</p></td>
		  <td class="head2" colspan="2">Course Name:</td><td colspan="2"><p>{{$internal_data_first->course_name}}</p></td>
		  </tr>

		  <tr >
		  <td colspan="2">Semester Name:</td><td colspan="2"><p>{{$internal_data_first->semester_name}}</p></td>
		  <td class="head2" colspan="2">Session:</td>
		  <td colspan="2"><input type="text" name="session" value="{{$internal_data[0]->session}}" hidden readonly /> <p>{{$internal_data_first->session}}</p></td>
			
		  </tr>

		  <tr >
		  <td colspan="2">Institution Code: </td><td colspan="2"> <p>{{$internal_data_first->campus_code}}</p></td>
		  <td class="head2" colspan="2">Institution Name:</td><td colspan="2"> <p>{{$internal_data_first->campus_name}}</p></td>
		  </tr>

		  <tr>
		  <td colspan="2">Paper Code:</td><td colspan="2"><input type="text" name="sub_code" value="{{$internal_data_first->sub_code}}" hidden readonly />{{$internal_data_first->sub_code}}</td>
		  <td colspan="2">Paper Name:</td><td colspan="2">{{$internal_data_first->sub_name}}</td>
		  </tr>

		  <tr >
		  <td colspan="2">Date Of Semester Exam:</td><td colspan="2">{{date('d-m-Y',strtotime($internal_data_first->date_of_semester_exam))}}</td>
		  <td class="head2" colspan="2">Date Of Assignment/Presentation:</td><td colspan="2">{{date('d-m-Y',strtotime($internal_data_first->date_of_assignment))}}</td>
		  </tr>
		  <tr >
		  <td colspan="2">Maximum Marks: </td><td colspan="2">{{$internal_data_first->maximum_mark}}</td>
		  <td class="head2" colspan="2">Maximum Marks(Assignment/Presentation):</td><td colspan="2">{{$internal_data_first->maximum_mark_assignment}}</td>
		  </tr>
		  <tr>
			  <td colspan="8">&nbsp; </td>
		  </tr>

		  
		  </thead >
		     
		  
		  </table>
		  
		  

		  <table style="border:solid" class="table1"width="100%">
		  <thead class="head1">
		  <tr><td rowspan="2"></td><td rowspan="2">
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
		  </tr>
		<td rowspan="2">
			  Initial of Examiner(s)</td></thead>
		  <tbody class="head1">{{--dd('')--}}
	  @foreach($internal_data as $key=>$mark)
	  <tr>
	  
	  	<td><!--@if($mark->final_status!=1)<a href="#!" data-toggle="modal" data-mid="{{$mark->id}}" id="login_btn" data-target="#exampleModal" class="fa fa-pencil login_btn" >
			  Edit</a>@endif--></td>
	  
	  	<td>{{$key+1}}</td>
		  	<td>{{$mark->student_name}}</td>
	  		<td>{{$mark->enrollment_number}}</td>
	  		<td>{{$mark->roll_number}}</td>
	  		<td><input type="text" class="mid_sem" value="{{$mark->mid_semester_marks}}" hidden>{{$mark->mid_semester_marks}}</td>
	  		<td><input type="text" class="assign" value="{{$mark->assignment_marks}}" hidden>{{$mark->assignment_marks}}</td>
	  		<td>{{$mark->total_marks}}</td>
			  <td>{{numberFormat($mark->total_marks)}}</td>
	  </tr>
		  @endforeach
	  </tbody>
	  <tfoot>
	  	<tr>
	  		<td>
	  			Name of Evaluator:
	  			Contact No.
	  		</td>
	  		<td>
	  			Signature of Evaluator With Date
	  		</td>
	  	</tr>
	  </tfoot>
	</table>
<input type="submit"  value="Final Submit" class="btn btn-info">

	  </form>
		  
    </div>
	<p><code>Note:</code>You do not Change Value After Final Submit</p>
</section>
<!-- Button trigger modal -->


<!-- Modal -->


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Internal Mark</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div><form method="POST" action="{{ route('internal-update') }}" id="myform">
		@csrf
      <div class="modal-body">
        <div class="row">
			<input type="text" name="id" class="modal_id" hidden>
            <label for="recipient-name" class="col-form-label">Mid Semester Marks</label>
			<input type="number" value="{{}}" name="mid_semester_marks" class="form-control col-md-3 mid_semester_marks" onchange="add_figure()" autocomplete="off">
        </div>
		<div class="row">
			<input type="text" name="id" class="modal_id" hidden>
            <label for="recipient-name" class="col-form-label">Assignment/Presentation Marks</label>
			<input type="number" name="assingnment_mark" class="form-control col-md-3 assingnment_mark" onchange="add_figure()" autocomplete="off">
        </div>
		<div class="row">
			<input type="text" name="id" class="modal_id" hidden>
            <label for="recipient-name" class="col-form-label">In Figure</label>
			<input type="number" name="total_marks" class="form-control col-md-3 total_marks" autocomplete="off">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit"  value="Save Changes" class="btn btn-primary"/>
      </div>
	  </form>
    </div>
  </div>
</div>
<div id="error" title="Error">

</div>
@endsection

@section('styles')
    <style type="text/css"></style>
@endsection
@section('scripts')
<script>
$(document).ready(function(){
	$('.login_btn').click(function(){
		var current_tr = $(this).closest('tr');
		$('.modal_id').val($(this).data('mid'));
		var mid_semester_marks = parseInt(current_tr.find('.mid_sem').val());
	var assingnment_mark = parseInt(current_tr.find('.assign').val());
	
	$('.mid_semester_marks').val(mid_semester_marks);
	$('.assingnment_mark').val(assingnment_mark);
	});
	
	
});
function add_figure(){
	var mid_semester_marks = parseInt($('.mid_semester_marks').val());
	var assingnment_mark = parseInt($('.assingnment_mark').val());
	$('.total_marks').val(mid_semester_marks+assingnment_mark);
}

</script>
@endsection

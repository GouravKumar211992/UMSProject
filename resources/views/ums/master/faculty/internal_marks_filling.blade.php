{{-- @extends('faculty.layouts.app') --}}

{{-- Web site Title --}} 
@section('title') Show Internal Marks :: @parent @stop 
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<style>
	.head1 td{
		border: #000000 1px solid; border-right: none; border-top: none;
		}
		.head1 td:last-child{
		 border-right: #000000 1px solid; border-top: none;
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
	
	@media print {
		.noPrint{
			display:none;
		}
	}

	table td{
		padding: 5px 10px;
	}
	.form-control{
		width:100% !important;
		border: 1px solid #000;
		margin-left: 0px !important;
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
{{-- @include('faculty.partials.notifications') --}}
    <div class="container-fluid">
      <div class="row mb-3 align-items-center">
        <div class="col-4">
          <a href="javascript:history.back()" class="btn btn-secondary btn-back noPrint">Go Back</a> 
        </div>
        
      </div>
    
	  <form method="get" name="internal_form" onsubmit="return validateForm()">
		@csrf
		<table style="border:solid" width="100%">
        <thead class="head1">
		<tr  align="center" bordercolordark="#000000"><td colspan="8">Dr.SHAKUNTALA MISRA NATIONAL REHABILIATION UNIVERSITY, LUCKNOW</td>
		  </tr>
	  <tr  align="center" bordercolordark="#000000"><td colspan="8">AWARD SHEET OF INTERNAL MARKS</td>
		  </tr>
		  <tr  align="center"><td colspan="8">MID SEMESTER & ASSIGNMENT /PRESENTATION</td>
		  </tr>
		  
		  <tr >
			<td colspan="2">Course Code:</td><td colspan="2">
				<select id="course" name="course"  class="form-control" required>
				<option value="">--Select Course--</option>
				@foreach($mapped_Courses as $index=>$mapped_Course)
				<option value="{{$mapped_Course->id}}"@if(Request()->course==$mapped_Course->id) selected @endif>{{$mapped_Course->name}} ({{$mapped_Course->Course->campuse->campus_code}})</option>
				@endforeach
				</select>
			</td>
			<td class="head2" colspan="2">Course Name:</td><td colspan="2"> <input type="text" value="{{($mapped_faculty)?$mapped_faculty->Course->name:''}}" hidden><p>{{($mapped_faculty)?$mapped_faculty->Course->name:''}}</p></td>
		  </tr>
		  
		  <tr>
		  <td colspan="2">Semester Name:</td><td colspan="2">
			<select id="semester" name="semester"  class="form-control" required>
			<option value="">--Select Semester--</option>
			@if($mapped_Semesters)
			@foreach($mapped_Semesters as $index=>$mapped_Semester)
				<option value="{{$mapped_Semester->id}}"@if(Request()->semester==$mapped_Semester->id) selected @endif>{{$mapped_Semester->name}}</option>
				@endforeach
				@endif
			</select>
		 	</td>
		  	<td class="head2" colspan="2">Exam Type:</td>
			<td colspan="2"> 
				<select id="type" name="type"  class="form-control" required>
				@foreach($examTypes as $index=>$examType)
				<option value="{{$examType}}" @if(Request()->type==$examType) selected @endif>{{$examType}}</option>
				@endforeach
				</select>
			</td>
		  </tr>

		  <tr >
		  	<td colspan="2">Session: </td>
		  	<td colspan="2"> 
		  		<select id="session" name="session"  class="form-control"  onChange="$('.show_data').trigger('click')" required>
		  			<option value="">--Select Session--</option>
		  			@foreach($sessions as $session)
		  			<option value="{{$session->academic_session}}" @if($session->academic_session==Request()->session) selected @endif>{{$session->academic_session}}</option>
		  			@endforeach
		  		</select>
			</td>
			<td colspan="2">Batch :</td>
			<td colspan="2">
				<select name="batch" id="batch" style="border-color: #c0c0c0;" class="form-control js-example-basic-single" required>
					<option value="">--Select--</option>
					{{-- @foreach(batchArray() as $batch)
					@php $batch_prefix = substr($batch,2,2); @endphp
					<option value="{{$batch_prefix}}" @if(Request()->batch == $batch_prefix) selected @endif >{{$batch}}</option>
					@endforeach --}}
				</select>
			</td>
		  </tr>

		  <tr >
		  <td colspan="2">Select Institution: </td>
		  	<td colspan="2">
			<p>{{($selected_course)?$selected_course->campuse->name:''}}</p>
			</td>
			<td class="head2" colspan="2">Exam Month & Year</td>
		  	<td colspan="2">
			  	<select name="month_year" id="month_year" class="form-control" style="border-color: #c0c0c0;width:90%;" required>
					@if(Request()->month_year)
					<option value="{{Request()->month_year}}">{{date('F-Y',strtotime(Request()->month_year))}}</option>
					@else
					<option value="">--Select--</option>
					@endif
				</select>
		  	</td>
		  </tr>

		  <tr>
		  <td colspan="2">Paper Code:</td><td colspan="2">  
		  <select id="sub_code" name="sub_code"  class="form-control" style="width: 250px;" required>
		  <option value="">--Select Subject--</option>
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
		  <span class="text-danger">{{ $errors->first('sub_code') }}</span></td>
		  <td colspan="2">Paper Name:</td><td colspan="2"> <input id="sub_name" name="sub_name"  class="form-control" value="{{$sub_code_name}}" disabled  required /></td>
		  </tr>

		  <tr >
		  <td colspan="2">Date Of Semester Exam:</td><td colspan="2">@if($details){{date('d-m-Y',strtotime($details->date_of_semester_exam))}}@endif</td>
		  <td class="head2" colspan="2">Date Of Assignment/Presentation:</td><td colspan="2">@if($details){{date('d-m-Y',strtotime($details->date_of_assignment))}}@endif</td>
		  </tr>
		  <tr >
		  <td colspan="2">Maximum Marks(Mid Term/ UT): </td><td colspan="2">{{($details)?$details->maximum_mark:''}}</td>
		  <td class="head2" colspan="2">Maximum Marks(Assignment/Presentation):</td><td colspan="2">{{($details)?$details->maximum_mark_assignment:''}}</td>
		  </tr>
		  <tr>
			  <td colspan="8">&nbsp; </td>
		  </tr>

		  
		  </thead >
		     
		  
		  </table>
		  <input type="submit" class="btn btn-primary show_data noPrint" value="Show Student List" style="display:nones;">
		  </form>
	   @if(count($marks)>0)
		<form method="POST" action="{{route('internal-marks-delete')}}" id="table_form" >
			@csrf
			<input type="hidden" name="course" value="{{Request()->course}}" required>
		<input type="hidden" name="semester" value="{{Request()->semester}}" required>
		<input type="hidden" name="sub_code" value="{{Request()->sub_code}}" required>
		<input type="hidden" name="session" value="{{Request()->session}}" required>
		<input type="hidden" name="type" value="{{Request()->type}}" required>
		<input type="hidden" name="batch" value="{{Request()->batch}}" required>
		<input type="hidden" name="month_year" value="{{Request()->month_year}}" required>
		  <table style="border:solid" class="table1"width="100%">
		  <thead class="head1">
		  <!-- <tr><td rowspan="2"> Action</td> -->
			  <td rowspan="2"> Sr. No.</td>
			  <td rowspan="2"> Name Of Student<input type="text" name="sub_code" value="{{Request()->sub_code}}" hidden readonly></td>
			  <td rowspan="2"> Enrollment No.<input type="text" name="session" value="{{Request()->session}}" hidden  readonly></td>
			  <td rowspan="2"> Roll No.</td>
			  <td rowspan="2"> Mid Semester Marks</td>
			  <td rowspan="2"> Assignment/Presentation Marks</td>
			  <td rowspan="2" class="comment-heading" style="width: 150px;">Comments</td>
			  <td colspan="2"> Total Marks </td>
				<!-- <td rowspan="2">Initial of Examiner(s)</td> -->
				<td>
					@if(Auth::guard('admin')->check()==true && Auth::guard('admin')->user()->role==1)
						Delete All
					@endif
					</td>
			</tr>
		  <tr>
		  		<td>In Figure</td>
				 <td>In Words</td>
				 <td class="text-center">
					@if(Auth::guard('admin')->check()==true && Auth::guard('admin')->user()->role==1)
					<input type="checkbox" class="delete_all" style="width:30px;">
					@endif
				</td>
		  </tr>

		</thead>
		  <tbody class="head1">
		  @php $final_submit = 'hidden'; @endphp
		  @php $print_show = ''; @endphp
	  @foreach($marks as $key=>$mark)
		@if($mark->final_status==0)
		  @php $final_submit = ''; @endphp
		  @php $print_show = 'hidden'; @endphp
		@endif
	  	<td>{{++$key}}</td>
			<td>{{$mark->student_name}}</td>
	  		<td>{{$mark->enrollment_number}}</td>
	  		<td>{{$mark->roll_number}}</td>
	  		<td>{{$mark->mid_semester_marks}}</td>
	  		<td>{{$mark->assignment_marks}}</td>
			  <td >
				
				<input value="{{$mark->comment}}" hidden>{{$mark->comment}}</td>
			</td>
	  		<td>{{$mark->total_marks}}</td>
			<td>{{numberFormat($mark->total_marks)}}</td>
			<td class="text-center">
				@if(Auth::guard('admin')->check()==true && Auth::guard('admin')->user()->role==1)
				<input type="checkbox" class="delete_single" value="{{$mark->roll_number}}" name="roll_number[]" style="width:30px;">
				@endif
			</td>
			<!-- <td></td> -->
	  </tr>
		  @endforeach
	  </tbody>
	  	  <tfoot>
			<tr>
				<td colspan="2">
					<br>
					<br>
					<br>
					Name of Evaluator:
					Contact No.
					<br>
					<br>
				</td>
				<td colspan="5" class="text-right">
					<br>
					<br>
					<br>
					Signature of Evaluator With Date
					<br>
					<br>
				</td>
				<td>&nbsp;</td>
			</tr>
	  </tfoot>
	</table>
	<br/>
	<!-- <h5 {{$final_submit}} ><code>Note:</code>You can take print of award sheet after final submit</h5> -->
	<h5 {{$print_show}} ><code>Note:</code>Final Submission is done so please take a print of the award sheet</h5>
	<br/>
	 <!-- <input type="submit"  value="Final Submit" class="btn btn-info" {{$final_submit}} onclick="return confirm('Are you sure?');"> -->
	 @if(Auth::guard('admin')->check()==true && Auth::guard('admin')->user()->role==1)
	 <input type="button"  value="Delete Selected Marks" class="btn btn-danger" onclick="delete_all_marsk();">
	 @endif
	 <button type="button" onclick="return window.print();" class="btn btn-info">Print</button> 

	  </form> 
	  @elseif($marks)
	<table style="border:solid" class="table1" width="100%">
		  <thead class="head2">
		  <tr>
			  <td rowspan="14">No Records Found</td>
		</tr>
		</thead>		
	</table>
	@endif
		
    </div>
</section>

<br/>
<br/>
<br/>
<br/>
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
      <div class="modal-body" style="background:#f0f0f0;">
        <div class="row form-group">
			<div class="col-md-6">
				<input type="text" name="id" class="modal_id" hidden>
				<label for="recipient-name" class="col-form-label">Mid Semester Marks</label>
			</div>
			<div class="col-md-6">
				<input type="text" value="" name="mid_semester_marks" class="form-control numbersOnly mid_semester_marks" onchange="add_figure()" required autocomplete="off">
			</div>
        </div>
        <div class="row form-group">
			<div class="col-md-6">
				<input type="text" name="id" class="modal_id" hidden>
				<label for="recipient-name" class="col-form-label">Assignment/Presentation Marks</label>
			</div>
			<div class="col-md-6">
				<input type="text" name="assingnment_mark" class="form-control numbersOnly assingnment_mark" onchange="add_figure()" required autocomplete="off">
			</div>
        </div>
        <div class="row form-group">
			<div class="col-md-6">
				<input type="text" name="id" class="modal_id" hidden>
				<label for="recipient-name" class="col-form-label">Total Marks</label>
			</div>
			<div class="col-md-6">
				<input type="text" name="total_marks" class="form-control numbersOnly total_marks" required readonly autocomplete="off">
			</div>
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
		var mid_semester_marks = current_tr.find('.mid_sem').val();
	var assingnment_mark = current_tr.find('.assign').val();
	
	$('.mid_semester_marks').val(mid_semester_marks);
	$('.assingnment_mark').val(assingnment_mark);
	});
	
	$('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
$('#course').change(function(){
	var course=$('#course').val();
	$("#semester").find('option').remove().end();
	var formData = {course:course,"_token": "{{ csrf_token() }}"}; 
	$.ajax({
		url : "{{url('faculty/getsemester')}}",
		type: "POST",
		data : formData,
		success: function(data, textStatus, jqXHR){
			$('#semester').append(data);
			
		},
	});
	// if(course == 37){
	// 	$('#strean_id').show();
	// }else{
	// 	$('#strean_id').hide();
	// }
});

$('#semester').change(function(){
	var course=$('#course').val();
	var semester=$('#semester').val();
	$("#sub_code").find('option').remove().end();
	var formData = {
			permissions:1,
			semester:semester,
			course:course,
			"_token": "{{ csrf_token() }}"
		}; 
	$.ajax({
		url : "{{url('faculty/getsubject')}}",
		type: "POST",
		data : formData,
		success: function(data, textStatus, jqXHR){
			$('#sub_code').append(data);
			
		},
	});
	});

// Code for getting Month Name & Year
$('#course, #semester, #session, #sub_code, #type').change(function(){
	var course=$('#course').val();
	var semester=$('#semester').val();
	var session=$('#session').val();
	var sub_code=$('#sub_code').val();
	var type=$('#type').val();
	var table_type= 'InternalMark';
	$("#month_year").html('<option value="">--Select--</option>');
	var formData = {
		course:course,
		semester:semester,
		session:session,
		sub_code:sub_code,
		type:type,
		table_type:table_type,
		"_token": "{{ csrf_token() }}"
	}; 
	$.ajax({
		url : "{{url('faculty/get_month_year')}}",
		type: "POST",
		data : formData,
		success: function(data, textStatus, jqXHR){
			$('#month_year').append(data);
			
		},
	});
});


});


function add_figure(){
	var mid_semester_marks = parseInt($('.mid_semester_marks').val());
	var assingnment_mark = parseInt($('.assingnment_mark').val());
	$('.total_marks').val(mid_semester_marks+assingnment_mark);
}
	
	
jQuery(document).bind("keyup keydown", function(e){
    if(e.ctrlKey && e.keyCode == 80){
        return false;
    }
});	
	// Delete Marks code start
// select all function 
<?php if(Auth::guard('admin')->check()==true){ ?>
$(".delete_all").click(function(){
    $('.delete_single').not(this).prop('checked', this.checked);
});
function delete_all_marsk(){
	var text = "Are you sure?";
	if (confirm(text) == false) {
		return false;
		exit;
	}
	if($('.delete_single:checked').length == 0){
		alert('Please select atleast one check box the delete the marks.');
	}else if($('.delete_single:checked').length > 0){
		$('.delete_single').each(function(){
			if($(this).is(":checked")==false){
				$(this).closest('tr').remove();
			}
			$('#table_form').submit();
		});
	}
}
<?php } ?>
	// Delete Marks code end
</script>
@endsection


<table class="table table-bordered table-striped educationtable" style="border-collapse: collapse !important;">
	<thead>
		<tr>
		<td colspan="9"><b>Educational Qualification(s) from 10th Std. Onwards</b></td>
		</tr>
			<tr>
				<th>Name of Exam<span class="text-danger">*</span></th>
				<th>Degree Name<span class="text-danger">*</span></th>
				<th>Board / University<span class="text-danger">*</span></th>
				<th>Status<span class="text-danger">*</span></th>
				<th>Passing Year<span class="text-danger">*</span></th>
				<th>Mark Type<span class="text-danger">*</span></th>
				<th>Total Marks / CGPA<span class="text-danger">*</span></th>
				<th >Marks/CGPA Obtained<span class="text-danger">*</span></th>
				<th>Equivalent Percentage<span class="text-danger">*</span></td>
				<th>Subjects</td>
				<th>Roll Number</th>
				<th>Upload Files
					<span class="text-danger">*</span><br/>
					<span class="text-danger">Only JPG or PDF files from 200KB to 500KB</span>
				</th>
				<th>Action</th>
			</tr>
	</thead>
	@if(Request()->course_id)
	<tbody>
		@php $required_qualifications = $course_single->required_qualifications(); @endphp
		@foreach($required_qualifications as $index=>$qualification)
		@php $index = ($index+1); @endphp
		<tr>
			<td>
				<!-- <input type="text" class="form-control name_of_exam" name="name_of_exam[]" value="{{$qualification->name}}" maxlength="50" required readonly autocomplete="off" style="width:auto;"> -->
				<textarea class="form-control name_of_exam" name="name_of_exam[]" maxlength="50" required readonly autocomplete="off" style="width:auto;">{{$qualification->name}}</textarea>
				<div class="invalid-feedback text-danger name_of_exam_application"></div>
			</td>
			<td>
				<input type="text" class="form-control degree_name" name="degree_name[]" value="" maxlength="50" required autocomplete="off">
				<div class="invalid-feedback text-danger degree_name_application"></div>
			</td>
			<td>
				<input type="text" class="form-control" name="board[]" maxlength="50" required autocomplete="off">
				<div class="invalid-feedback text-danger board_application"></div>
			</td>
			
			<td>
				<input type="radio" class="filled-in passing_status" name="passing_status{{$index}}" value="1" id="passing_status{{$index}}" onchange="set_passing_status($(this))" checked>
				<label for="passing_status{{$index}}" class="form-check-label"><strong>Passed</label>
				@if(Request()->course_id == 39)
					@if($index > 2)
					<input type="radio" class="filled-in passing_status" name="passing_status{{$index}}" value="2" id="passing_status{{$index}}_{{$index}}" onchange="set_passing_status($(this))">
					<label for="passing_status{{$index}}_{{$index}}" class="form-check-label"><strong>Appeared</label>
					@endif
				@else
					@if($required_qualifications->count()==$index)
					<input type="radio" class="filled-in passing_status" name="passing_status{{$index}}" value="2" id="passing_status{{$index}}_{{$index}}" onchange="set_passing_status($(this))">
					<label for="passing_status{{$index}}_{{$index}}" class="form-check-label"><strong>Appeared</label>
					@endif
				@endif
			</td>
			
			<td class="passing_status_true">
				<input type="text" class="form-control numbersOnly" name="passing_year[]" maxlength="4" required autocomplete="off">
				<div class="invalid-feedback text-danger passing_year_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="radio" class="filled-in cgpa_or_marks" name="cgpa_or_marks{{$index}}" value="1" id="cgpa_or_marks{{$index}}" onchange="calculate_marks($(this),true)" checked>
				<label for="cgpa_or_marks{{$index}}" class="form-check-label passing_status_hide"><strong>Marks</label>
				
				<input type="radio" class="filled-in cgpa_or_marks" name="cgpa_or_marks{{$index}}" value="2" id="cgpa_or_marks{{$index}}_{{$index}}" onchange="calculate_marks($(this),true)">
				<label for="cgpa_or_marks{{$index}}_{{$index}}" class="form-check-label passing_status_hide"><strong>CGPA</label>
			</td>
			<td class="passing_status_true">
				<input type="text" class="form-control numbersOnly total_marks_cgpa" name="total_marks_cgpa[]" maxlength="4" required autocomplete="off" onchange="calculate_marks($(this),false)">
				<div class="invalid-feedback text-danger total_marks_cgpa_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="text" class="form-control numbersOnly cgpa_optain_marks" name="cgpa_optain_marks[]" maxlength="4" required autocomplete="off" onchange="calculate_marks($(this),false)">
				<div class="invalid-feedback text-danger cgpa_optain_marks_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="text" class="form-control numbersOnly equivalent_percentage percentage_type" placeholder="%" readonly name="equivalent_percentage[]" maxlength="5" max="100" min="0" required  autocomplete="off" onchange="max_percentage($(this))">
				<div class="invalid-feedback text-danger equivalent_percentage_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="text" class="form-control" name="subject[]" maxlength="50" autocomplete="off">
				<div class="invalid-feedback text-danger subject_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="text" class="form-control" name="certificate_number[]" maxlength="15" autocomplete="off">
				<div class="invalid-feedback text-danger certificate_number_application"></div>
			</td>
			<td class="passing_status_true">
				<label class="passing_status_hide">Marksheet File</label>
				<input type="file" class="form-control" name="education_document[]" accept="image/jpeg,image/jpg,application/pdf" required>
				<div class="cgpa_document_container" style="display: none;">
					<label>CGPA Formula</label>
					<input type="file" class="form-control cgpa_document" name="cgpa_document[]" accept="image/jpeg,image/jpg,application/pdf" >
				</div>
				<div class="invalid-feedback text-danger education_document_application"></div>
			</td>
			<td class="text-primary passing_status_true">
				{{-- @if(($required_qualifications->count() - 1)==$index)
				<i class="education_btn fa fa-plus-square f-20 mt-2" onClick="addNewEducation($(this))" ></i>
				@endif --}}
			</td>
		</tr>
		@endforeach
		
	</tbody>
	@endif 

</table>




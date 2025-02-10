
<tr>
	<td>
		<input type="text" class="form-control name_of_exam" name="name_of_exam[]" maxlength="50" required autocomplete="off" placeholder="Other" value="Other" readonly>
		<div class="invalid-feedback text-danger name_of_exam_application"></div>
	</td>
	<td>
		<input type="text" class="form-control degree_name" name="degree_name[]" maxlength="50" required autocomplete="off">
		<div class="invalid-feedback text-danger degree_name_application"></div>
	</td>
	<td>
		<input type="text" class="form-control" name="board[]" maxlength="50" required autocomplete="off">
		<div class="invalid-feedback text-danger board_application"></div>
	</td>
	<td>
		<input type="radio" class="filled-in passing_status" name="passing_status{{$next_rows}}" value="1" id="passing_status{{$next_rows}}" onchange="set_passing_status($(this),true)" checked>
		<label for="passing_status{{$next_rows}}" class="form-check-label"><strong>Passed</label>
		
		<input type="radio" class="filled-in passing_status" name="passing_status{{$next_rows}}" value="2" id="passing_status{{$next_rows}}_{{$next_rows}}" onchange="set_passing_status($(this),true)">
		<label for="passing_status{{$next_rows}}_{{$next_rows}}" class="form-check-label"><strong>Appeared</label>
	</td>
	<td class="passing_status_true">
		<input type="text" class="form-control numbersOnly" name="passing_year[]" maxlength="4" required autocomplete="off">
		<div class="invalid-feedback text-danger passing_year_application"></div>
	</td>
	<td class="passing_status_true">
		<input type="radio" class="filled-in cgpa_or_marks" name="cgpa_or_marks{{$next_rows}}" value="1" id="cgpa_or_marks{{$next_rows}}" onchange="calculate_marks($(this),true)" checked>
		<label for="cgpa_or_marks{{$next_rows}}" class="form-check-label passing_status_hide"><strong>Marks</label>
		
		<input type="radio" class="filled-in cgpa_or_marks" name="cgpa_or_marks{{$next_rows}}" value="2" id="cgpa_or_marks{{$next_rows}}_{{$next_rows}}" onchange="calculate_marks($(this),true)">
		<label for="cgpa_or_marks{{$next_rows}}_{{$next_rows}}" class="form-check-label passing_status_hide"><strong>CGPA</label>
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
		<i class="education_btn fa fa-trash f-22 mt-2 text-danger" onClick="delete_education($(this))"></i>
	</td>
</tr>


<script>
$('.numbersOnly').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
</script>
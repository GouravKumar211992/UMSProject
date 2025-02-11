@if(Request()->edit=='true' && admission_open_couse_wise($application->course_id,2,$application->academic_session))

@php
$educationAppeared = $applicationEducation->where('passing_status',2)->first();
@endphp
@if($educationAppeared)
<style>
    input{
        background-color: #ffffff !important;
        border: 1px solid #c0c0c0 !important;
    }
</style>
<form action="" id="educationEditForm">
@csrf
<table class="table table-bordered table-striped educationtable" style="border-collapse: collapse !important;">
	<thead>
        <tr>
            <th>Name of Exam<span class="text-danger">*</span></th>
            <th>Degree Name<span class="text-danger">*</span></th>
            <th>Board / University<span class="text-danger">*</span></th>
            
            <th>Status<span class="text-danger">*</span></th>
            
            <th>Passing Year<span class="text-danger">*</span></th>
            <th>Mark Type<span class="text-danger">*</span></th>
            <th>Total Marks / CGPA<span class="text-danger">*</span></th>
            <th>Marks/CGPA Obtained<span class="text-danger">*</span></th>
            <th>Equivalent Percentage<span class="text-danger">*</span>
            </th><th>Subjects
            </th><th>Roll Number</th>
            <th>Upload Files
                <span class="text-danger">*</span><br>
                <span class="text-danger">Only JPG or PDF files from 200KB to 500KB</span>
            </th>
        </tr>
	</thead>
		<tbody>
            <tr>
			<td>
                <input type="hidden" class="form-control degree_id" name="degree_id" value="{{$educationAppeared->id}}">
				{{$educationAppeared->name_of_exam}}
			</td>
			<td>
                {{$educationAppeared->degree_name}}
			</td>
			<td>
            {{$educationAppeared->board}}
			</td>
			
			<td>
				<input type="radio" class="filled-in passing_status" checked="">
				<label class="form-check-label"><strong>Passed</strong></label>
            </td>
			
			<td class="passing_status_true">
				<input type="text" class="form-control numbersOnly" name="passing_year" maxlength="4" required="" autocomplete="off">
				<div class="invalid-feedback text-danger passing_year_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="radio" class="filled-in cgpa_or_marks" name="cgpa_or_marks" value="1" id="cgpa_or_marks0" onchange="calculate_marks($(this),true)" checked="">
				<label for="cgpa_or_marks0" class="form-check-label passing_status_hide"><strong>Marks</strong></label>

				<input type="radio" class="filled-in cgpa_or_marks" name="cgpa_or_marks" value="2" id="cgpa_or_marks0_0" onchange="calculate_marks($(this),true)">
				<label for="cgpa_or_marks0_0" class="form-check-label passing_status_hide"><strong>CGPA</strong></label></td>
			<td class="passing_status_true">
				<input type="text" class="form-control numbersOnly total_marks_cgpa" name="total_marks_cgpa" maxlength="4" required="" autocomplete="off" onchange="calculate_marks($(this),false)">
				<div class="invalid-feedback text-danger total_marks_cgpa_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="text" class="form-control numbersOnly cgpa_optain_marks" name="cgpa_optain_marks" maxlength="4" required="" autocomplete="off" onchange="calculate_marks($(this),false)">
				<div class="invalid-feedback text-danger cgpa_optain_marks_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="text" class="form-control numbersOnly equivalent_percentage percentage_type" placeholder="%" readonly="" name="equivalent_percentage" maxlength="5" max="100" min="0" required="" autocomplete="off" onchange="max_percentage($(this))">
				<div class="invalid-feedback text-danger equivalent_percentage_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="text" class="form-control" name="subject" maxlength="50" autocomplete="off">
				<div class="invalid-feedback text-danger subject_application"></div>
			</td>
			<td class="passing_status_true">
				<input type="text" class="form-control" name="certificate_number" maxlength="15" autocomplete="off">
				<div class="invalid-feedback text-danger certificate_number_application"></div>
			</td>
			<td class="passing_status_true">
				<label class="passing_status_hide">Marksheet File</label>
				<input type="file" class="form-control" name="education_document" accept="application/pdf" required="">
				<div class="cgpa_document_container" style="display: none;">
					<label>CGPA Formula</label>
					<input type="file" class="form-control cgpa_document" name="cgpa_document" accept="application/pdf">
				</div>
				<div class="invalid-feedback text-danger education_document_application"></div>
			</td>
		</tr>
				
	</tbody>
	<tfoot>
        <tr>
            <td colspan="12">
                <button type="submit" class="btn btn-primary educationEditFormSave">Save Educational Qualification</button>
            </td>
        </tr>
    </tfoot>
	 

</table>
</form>

<script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
<script>
/*=============Education Code =========*/
function calculate_marks($this,cgpa_check){
	var current_tr = $this.closest('tr');
	$this.closest('tr').find('.cgpa_document').prop('required',false);
		$this.closest('tr').find('.cgpa_document_container, .cgpa_document').hide();
	if(current_tr.find('.cgpa_or_marks:checked').val()==1){
		current_tr.find('.equivalent_percentage').prop('readonly',true);
		var total_marks_cgpa = parseFloat(current_tr.find('.total_marks_cgpa').val());
		var cgpa_optain_marks = parseFloat(current_tr.find('.cgpa_optain_marks').val());
		if(total_marks_cgpa < cgpa_optain_marks){
			current_tr.find('.cgpa_optain_marks').val('');
			return false;
		}
		if(Number.isInteger(total_marks_cgpa) && Number.isInteger(cgpa_optain_marks) ){
			var equivalent_percentage = ((cgpa_optain_marks/total_marks_cgpa)*100);
			current_tr.find('.equivalent_percentage').val(equivalent_percentage.toFixed(2));
		}else{
			current_tr.find('.equivalent_percentage').val('');
		}
	}else{
		$this.closest('tr').find('.cgpa_document').prop('required',true);
		$this.closest('tr').find('.cgpa_document_container, .cgpa_document').show();
		var total_marks_cgpa = parseFloat(current_tr.find('.total_marks_cgpa').val());
		var cgpa_optain_marks = parseFloat(current_tr.find('.cgpa_optain_marks').val());
		if(total_marks_cgpa < cgpa_optain_marks){
			current_tr.find('.cgpa_optain_marks').val('');
			return false;
		}
		

		current_tr.find('.equivalent_percentage').prop('readonly',false);
		if(cgpa_check==true){
			current_tr.find('.total_marks_cgpa').val('');
			current_tr.find('.cgpa_optain_marks').val('');
			current_tr.find('.equivalent_percentage').val('');
		}
	}
}

function set_passing_status($this){
	var passing_status_true = $this.closest('tr').find('.passing_status_true');
	if($this.val() == 2){
		passing_status_true.find('input').fadeOut();
		passing_status_true.find('.passing_status_hide').fadeOut();
		$('.education_btn').fadeOut();
		passing_status_true.find('input').prop('required',false);
	}else{
		passing_status_true.find('input').fadeIn();
		passing_status_true.find('.passing_status_hide').fadeIn();
		$('.education_btn').fadeIn();
		passing_status_true.find('input').prop('required',true);
	}
}

function max_percentage($this){
	if(parseFloat($this.val())>100){
		$this.val('');
	}
}

$('.percentage_type').change(function(){
	max_percentage($(this));
});

$('#educationEditForm').submit(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('edit-application-form',[$educationAppeared->id]) }}",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				console.log(data);
				if(data.status==true){
					Swal.fire({
						title: 'SUCCESS! ',
						text: data.message,
						icon: 'success',
						confirmButtonColor: '#449d44',
						cancelButtonColor: '#d33',
						showCancelButton: false,
						confirmButtonText: 'OK'
					}).then((result) => {
						console.log(result);
						if (result.value) {
							location.reload();
						}
					});
				}else{
					Swal.fire(
					'Error!',
					data.message,
					'error'
					)
				}
			},
			error: function(request, status, error) {
				Swal.fire(
					'Error!',
					error,
					'error'
					)
			}
		});
	});

</script>

@endif

@endif
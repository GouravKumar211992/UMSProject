@if(Request()->edit=='true' && admission_open_couse_wise($application->course_id,2,$application->academic_session))
<!-- Modal -->
<div class="modal fade" id="entranceTest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<form id="entranceTestEditForm">
@csrf
<input type="hidden" name="table" value="applications">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit - In which national/state level entrance test you appeared.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
<div class="row">

          <div class="col-md-12">
						<h5 class="front-form-head">In which national/state level entrance test you appeared.</h5>
					</div>
					<div class="col-md-2 mb-2" id="">
						<div class="form-group">
							<label>Select Process<span class="text-danger">*</span></label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<select class="form-control" id="admission_through" name="admission_through" onChange="admissionthrough()">
							<option value="">--Select Option--</option>
							<option value="JEE(MAIN)">JEE(MAIN)</option>
							<option value="CUET">CUET</option>
							<option value="OTHER STATE LEVEL EXAM">OTHER STATE LEVEL EXAM</option>
							</select>
						<div class="invalid-feedback text-danger admission_through_application"></div>
						</div> 
					</div>

					<div class="col-md-2 mb-2 admission_through_exam_name_class" style="display:none;">
						<div class="form-group">
							<label>Name of Exam<span class="text-danger">*</span></label>
						</div>
					</div>
					<div class="col-md-4 admission_through_exam_name_class" style="display:none;">
						<div class="form-group">
							<input type="text" class="form-control" name="admission_through_exam_name" id="admission_through_exam_name" autocomplete="__away"/>
							<div class="invalid-feedback text-danger admission_through_exam_name_application"></div>
						</div> 
					</div> 
					<div class="clearfix"></div>	
					<div class="col-md-2 mb-2 jee appeared_or_passed_class" >
						<div class="form-group">
							<label>Appeared or Passed<span class="text-danger">*</span></label>
						</div>
					</div>
					<div class="col-md-4 jee appeared_or_passed_class" >
						<div class="form-group">
							<select name="appeared_or_passed" id="appeared_or_passed" class="form-control"  onchange="admissionthrough()">
								<option value="">Select</option>
								<option value="Passed">Passed</option>
								<option value="Appeared">Appeared</option>
							</select>
							<div class="invalid-feedback text-danger appeared_or_passed_application"></div>
						</div> 
					</div>
					<div class="col-md-2 mb-2 jee" >
						<div class="form-group">
							<label>Date of Examination<span class="text-danger">*</span></label>
						</div>
					</div>
					<div class="col-md-4 jee" >
						<div class="form-group">
							<input type="date" class="form-control" name="date_of_examination" id="jeee_date_of_examination" autocomplete="__away" />
							<div class="invalid-feedback text-danger jeee_date_of_examination_application"></div>
						</div> 
					</div>
					<div class="clearfix"></div>
					<div class="col-md-2 mb-2 jee">
						<div class="form-group">
							<label>Roll Number<span class="text-danger">*</span></label>
						</div>
					</div>
					<div class="col-md-4 jee">
						<div class="form-group">
							<input type="text" class="form-control" name="roll_number" id="jeee_roll_number" autocomplete="__away" />
							<div class="invalid-feedback text-danger jeee_roll_number_application"></div>
						</div> 
					</div> 

					<div class="col-md-2 mb-2 jee">
						<div class="form-group">
							<label>Score<span class="text-danger">*</span></label>
						</div>
					</div>
					<div class="col-md-4 jee">
						<div class="form-group">
							<input type="text" class="form-control" name="score" id="jeee_score" autocomplete="__away" />
							<div class="invalid-feedback text-danger jeee_score_application"></div>
						</div> 
					</div>
					{{--<div class="col-md-2 mb-2 jee">
						<div class="form-group">
							<label>Merit<span class="text-danger">*</span></label>
						</div>
					</div>
					<div class="col-md-4 jee">
						<div class="form-group">
							<input type="text" class="form-control" name="merit" id="jeee_merit" autocomplete="__away" />
							<div class="invalid-feedback text-danger jeee_merit_application"></div>
						</div> 
					</div> --}}
					<div class="col-md-2 mb-2 jee">
						<div class="form-group">
							<label>Rank<span class="text-danger">*</span></label>
						</div>
					</div>
					<div class="col-md-4 jee">
						<div class="form-group">
							<input type="text" class="form-control" name="rank" id="jeee_rank" autocomplete="__away" />
							<div class="invalid-feedback text-danger jeee_rank_application"></div>
						</div> 
					</div>

</div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary pull-right">Save changes</button>
      </div>
    </div>
  </div>
  </form>
</div>


<script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
<script>
function btech(){
      var value = 114;
			var application_for = $('.application_for:checked').val();
			var academic_session = $('#academic_session').val();
			var course_type = $('#course_type').val();
			var course_id = $('#course_id').val();
			// reload current page for the selected course 
			var url_with_course = "{{url('application-form')}}?application_for="+application_for+"&academic_session="+academic_session+"&course_type="+course_type+"&course_id="+course_id;
			window.location.href = url_with_course;
			if(value==41){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();

			}
			else if(value==42){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();

			}
			else if(value==43){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();

			}else if(value==44){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();
			}
			else if(value==45){
				$("#admission_applying_through").show();
				$("#hidelateral_entry").show();
				$("#ded").hide();
			}
			else if(value==11){
				$("#ded").show();
			}
			else if(value==26){
				$("#ded").show();
			}
			else if(value==27){
				$("#ded").show();
			}
			else{
				$("#admission_applying_through").hide();
				$("#hidelateral_entry").hide();
				$("#ded").hide();

			}
		}
    $('.jee').hide();
    function admissionthrough(){
			$('.appeared_or_passed_class').hide();
			var admission_through = $('#admission_through').val();
			var appeared_or_passed = $('#appeared_or_passed').val();
			if(admission_through=='' || appeared_or_passed=='Appeared'){
				$('.jee').hide();
			}else{
				$('.jee').show();
				$('.admission_through_exam_name_class').hide();
				if(admission_through=='OTHER STATE LEVEL EXAM'){
					$('.admission_through_exam_name_class').show();
				}
			}
			$('.appeared_or_passed_class').show();
		}
$('#entranceTestEditForm').submit(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('edit-application-form',[Request()->application_id]) }}",
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
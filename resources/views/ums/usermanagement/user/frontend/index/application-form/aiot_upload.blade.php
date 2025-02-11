@extends('frontend.layouts.app')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<style>
	.education_btn {
		cursor: pointer;
	}

	@media print {

		.print_hide,
		.wed-hom-footer,
		.copy-right,
		.icon-float,
		.ed-top,
		.top-logo {
			display: none;
		}

		body {
			margin: 0mm 0mm 0mm 0mm;
		}
	}
	.uppercase{
		text-transform: uppercase;
	}
	input[type=text]{
		text-transform: uppercase;
	}
	.placeholder_right::-webkit-input-placeholder ,
	.percentage_type::-webkit-input-placeholder {
	  text-align: right;
	  font-weight:bold;
	  font-size:20px;
	}
	.educationtable input{
		min-width:100px;
	}
	.educationtable input[type="file"]{
		min-width:200px;
	}
</style>

<!--SECTION START-->
<section>
	<div class="container com-sp pad-bot-70 pg-inn pt-3">
		<div class="n-form-com admiss-form">
			
			<div class="row">
				<div class="col-md-12">
					<h2 class="mt-0 mb-3 mobileadmintxt">Update AIOT Score Card</h2>
					<hr />
				</div>
			</div>
			<form method="POST" action="{{url('aiot-upload-save')}}"  enctype="multipart/form-data" autocomplete="off">
				@csrf
				<div class="invalid-feedback text-danger error_application"></div>
				<div class="row">
					<div class="col-md-12">
					 	<input type="hidden" name="applicationId" value="{{$applicationId}}">
						 <div class="table-responsive">
					<p style="color: red;">You can upload your AIOT Score Card</p>
					
					<div class="col-md-4 mb-2">
						<div class="form-group">
							<label>AIOT Score<span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="aiot_score" id="p_police_station" autocomplete="__away" />
							<div class="invalid-feedback text-danger police_station_application"></div>
						</div>
				    </div>
				    <div class="col-md-4 mb-2">
						<div class="form-group">
							<label>Aiot Rank<span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="aiot_rank" id="p_police_station" autocomplete="__away" />
							<div class="invalid-feedback text-danger police_station_application"></div>
						</div>
				    </div>
				    <div class="col-md-6 mb-4">
						<div class="col-md-4 mb-2">
						<div class="form-group">
							<label>AIOT Score Card<span class="text-danger">*</span></label>
						</div>
						<div class="col-md-4 mb-2">
						<input type="file" class="filled-in" name="aiot_score_card" id="domicile2" accept="image/*,application/pdf" autocomplete="__away" hidden>
						<div class="invalid-feedback text-danger aiot_score_card"></div>
					</div>
					</div>
				    </div>
					<div class="clearfix"></div>
					</div>
					</div>
					<br /> 
					<!-- <button type="button" class="btn btn-default mr-3"><i class="fa fa-undo" aria-hidden="true"></i> Reset</button> -->

					<button type="submit" class="btn btn-warning" onclick="myFunction()">
						<i class="fa fa-send" aria-hidden="true"></i> Submit
					</button>
					<!-- <a class="btn btn-info" onClick="window.print();"><i class="fa fa-print"></i> Download and print the application form</a> -->
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<!--SECTION END-->

<div id="error" title="Error!!!">

</div>
<!-- Success Alert Modal -->
<div id="application-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-l modal-success">
		<div class="modal-content modal-filled bg-success">
			<div class="modal-body p-4">
				<div class="text-center">
					<i class="dripicons-checkmark h1 text-white"></i>
					<h4 class="mt-2 text-white" style="color:white;">Well Done!</h4>
					<p class="mt-3 text-white" style="color:white;">Application Submitted Successfully.</p>
					<a id="more_courses" class="btn btn-info my-2">Click For The Apply More Courses</a>
					<a id="payment_url" class="btn btn-info my-2">Click For The Payment</a>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="application-alert-modal-false" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-l modal-success">
		<div class="modal-content modal-filled bg-success">
			<div class="modal-body p-4">
				<div class="text-center">
					<i class="dripicons-checkmark h1 text-white"></i>
					<h4 class="mt-2 text-white" style="color:white;">Your Application Already Submitted For Selected Course </h4>
					<a id="dashboard" class="btn btn-info my-2">Click Here To Go To Dashboard</a>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

@section('scripts')

<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>

<script>
	/============ Ajax Data Save ============/

	$(document).ajaxStart(function() {
			$("#loading-image").show();
		})
		.ajaxStop(function() {
			$("#loading-image").hide();
		});

	$('#success-alert-modal').on('hidden.bs.modal', function() {
		window.location.href = "{{ route('admin-dashboard') }}";
	})

	$('#myform_application').submit(function(e) {
		e.preventDefault();
		$('.error_application').text("").css({
			'display': 'none'
		});
		var formData = new FormData(this);
		$('.invalid-feedback').text('');
		$.ajax({
			headers: {
				'X-CSRF-Token': $('meta[name=_token]').attr('content')
			},
			type: 'POST',
			url: "{{ route('application-form') }}",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				//grecaptcha.reset();
				console.log(data);
				if (data.status == true) {
					$('#application-alert-modal').addClass('show');
					$('#application-alert-modal').addClass('in');
					$('#more_courses').attr('href',"{{url('additional-education-save')}}?application_id="+data.application_id);
					$('#payment_url').attr('href',"{{route('pay-now')}}?id="+data.application_id);
				}else if(data.status == false){
					$('#application-alert-modal-false').addClass('show');
					$('#application-alert-modal-false').addClass('in');
					$('#dashboard').attr('href',"{{route('user-dashboard')}}");

						
				}
				 else {
					var first_error = '';
					var counter = 0;
					var errors = "";
					$.each(data, function(index, val) {

						if ($('.' + index + '_application').parent().length > 0) {

							++counter;
							if (counter == 1) {
								first_error = index + '_application';
							}
							$('.' + index + '_application').text(val).css({
								'display': 'block'
							});
						} else {
							errors += val + " <br/>";
						}
					});
					if (errors) {
						$('.error_application').html(errors).css({
							'display': 'block'
						});
					}

					if (first_error == '' & errors != "") {
						first_error = 'error_application';
					}

					if ($("." + first_error).parent().length > 0) {

						$('html, body').animate({
							scrollTop: $("." + first_error).parent().offset().top - 100
						}, 2000);
					}
				}
			},
			error: function(request, status, error) {
				$('.error_application').text(error).css({
					'display': 'block'
				});
			}
		});
	});


	$(document).ready(function() {
		$("#bank_offline").hide();
		$("#bank_online").hide();
		$("#affiliated").hide();
		$("#affiliated_collage").val(1)
		$("#domicile_cirtificate2").hide();
		$("#domicile_cirtificate1").hide();
		$("#admission_applying_through").hide();
		$("#ded").hide();
		$(".jee").hide();
		$('.upsee').hide();



	$('#offline').on('click',function(){
		$("#bank_offline").show(); 
		$("#bank_online").hide(); 
	});
	
	$('#online').on('click',function(){
		$("#bank_offline").hide(); 
		$("#bank_online").show(); 
	});


		/============ Address Code ============/
		$("#is_correspondence_same").on("click", function() {
			if (this.checked) {
				$('.correspondence_address_application,.correspondence_district_application').text('');
				$('.correspondence_police_station_application,.correspondence_nearest_railway_station_application').text('');
				$('.correspondence_country_application,.correspondence_state_union_territory_application').text('');
				$('.correspondence_pin_code_application,.correspondence_mobile_no_application').text('');
				$('.correspondence_landline_application').text('');
				$("#correspondence_address").val($("#p_address").val());
				$("#correspondence_district").html('<option >' + $("#p_district").val() + '</option>');
				$("#correspondence_police_station").val($("#p_police_station").val());
				$("#correspondence_nearest_railway_station").val($("#p_nearest_railway_station").val());
				$("#correspondence_country").val($("#p_country").val());
				$("#correspondence_state_union_territory").html('<option >' + $("#p_state_union_territory").val() + '</option>');
				$("#correspondence_pin_code").val($("#p_pin_code").val());
				$("#correspondence_mobile_no").val($("#p_mobile_no").val());
				$("#correspondence_landline").val($("#p_landline").val());
			}
		});


		
		$('#applicatio_for2').on('click',function() {
		$("#affiliated").show();
		
		});
		$('#applicatio_for1').on('click',function() {
		$("#affiliated").hide();
		$("#affiliated_collage").val(1)
		
		});
		
		/============ Course Type Code ============/
		$('#course_type').change(function() {
			setCourse();
		});
		$('#campus_id').change(function() {
			setCourse();
		});
		$('.application_for').click(function() {
			setCourse();
		});
		setCollege();
		function setCollege(){
			var affiliated = $('.application_for:checked').val();
			if(affiliated==1){
				$('.college_name').hide();
				$(".college_name option[value="+1+"]").show();
				$(".college_name option[value="+1+"]").prop('selected',true);
			}else{
				$('.college_name').show();
				$(".college_name option[value="+1+"]").hide();
				$(".college_name option[value="+1+"]").prop('selected',false);
			}
		}
		function setCourse(){
			setCollege();
			var affiliated = $('.application_for:checked').val();
			var course_type = $('#course_type').val();
			var campus_id = $('#campus_id').val();
			if(affiliated==undefined){
				$('.application_for_application').text('The application for field is required.');
				return false;
			}
			if(course_type==''){
				return false;
			}
			if(campus_id==''){
				$('.college_name_application').text('The College Name field is required.');
				return false;
			}else{
				$('.college_name_application').text('');
			}

			$("#course_id").find('option').remove().end();
			var formData = {
				campus_id:campus_id,
				affiliated:affiliated,
				course_type: course_type,
				"_token": "{{ csrf_token() }}"
			}; //Array 
			$.ajax({
				url: "{{url('get-programm-type')}}",
				type: "POST",
				data: formData,
				success: function(data, textStatus, jqXHR) {
					$('#course_id').append(data);
				},
			});
		}
		
		$('#correspondence_country').change(function() {
			var country_id = $('#correspondence_country').val();
			$("#correspondence_state_union_territory").find('option').remove().end();
			var formData = {
				country_id: country_id,
				"_token": "{{ csrf_token() }}"
			}; //Array 
			$.ajax({
				url: "{{url('get-state')}}",
				type: "POST",
				data: formData,
				success: function(data, textStatus, jqXHR) {
					$('#correspondence_state_union_territory').append(data);
				},
			});

		});
		$('#correspondence_country').change(function(){
			correspondence_country();

		});
		$('#correspondence_state_union_territory').change(function() {
			var state_id = $('#correspondence_state_union_territory').val();
			$("#correspondence_district").find('option').remove().end();
			var formData = {
				state_id: state_id,
				"_token": "{{ csrf_token() }}"
			}; //Array 
			$.ajax({
				url: "{{url('get-district')}}",
				type: "POST",
				data: formData,
				success: function(data, textStatus, jqXHR) {
					$('#correspondence_district').append(data);
				},
			});
		});
		$('#p_country').change(function() {
			var country_id = $('#p_country').val();
			$("#p_state_union_territory").find('option').remove().end();
			var formData = {
				country_id: country_id,
				"_token": "{{ csrf_token() }}"
			}; //Array 
			$.ajax({
				url: "{{url('get-state')}}",
				type: "POST",
				data: formData,
				success: function(data, textStatus, jqXHR) {
					$('#p_state_union_territory').append(data);
				},
			});
		});
		$('#p_state_union_territory').change(function() {
			var state_id = $('#p_state_union_territory').val();
			$("#p_district").find('option').remove().end();
			var formData = {
				state_id: state_id,
				"_token": "{{ csrf_token() }}"
			}; //Array 
			$.ajax({
				url: "{{url('get-district')}}",
				type: "POST",
				data: formData,
				success: function(data, textStatus, jqXHR) {
					$('#p_district').append(data);
				},
			});
		});
		$('#domicile').change(function() {

			var dom = $('#domicile').val();
			if(dom==''){
				return false;
			}
			if (dom == "Uttar Pradesh") {
				$("#domicile2").val(dom);
				$("#domicile1").hide();
				$("#domicile2").hide();
				$("#domicile_cirtificate2").show();
				$("#domicile_cirtificate1").show();

			} else {
				$("#domicile_cirtificate2").hide();
				$("#domicile_cirtificate1").hide();
				$("#domicile2").val("");
				$("#domicile1").show();
				$("#domicile2").show();
			}
		});

		

		$('#religion').change(function() {

			var dom = $('#religion').val();
			if(dom==''){
				return false;
			}
			if (dom == "Hindu") {
				$("#religion2").val(dom);
				$("#religion1").hide();
				$("#religion2").hide();
			}
			else if(dom == "Muslim") {
				$("#religion2").val(dom);
				$("#religion1").hide();
				$("#religion2").hide();
			}
			else if(dom == "Sikh") {
				$("#religion2").val(dom);
				$("#religion1").hide();
				$("#religion2").hide();
			}
			else if(dom == "Christian") {
				$("#religion2").val(dom);
				$("#religion1").hide();
				$("#religion2").hide();
			}
			else {
				$("#religion2").val("");
				$("#religion1").show();
				$("#religion2").show();
			} 
		});

		
		$('#nationality').change(function() {
			var nationality = $('#nationality').val();
			if (nationality == "Indian") {
				$("#nationality1").hide();
				$("#nationality11").hide();
				$("#nationality_value").val('Indian');
			} else {
				$("#nationality1").show();
				$("#nationality11").show();
				$("#nationality_value").val('');
			}
		});

		$('#enrollment').change(function() {
			var enrollment = $('#enrollment').val();
			if (enrollment == "Yes") {
				$("#enrollment1").show();
				$("#enrollment11").show();
				$("#enrollment_value").val('');
			} else {
				$("#enrollment1").hide();
				$("#enrollment11").hide();
				$("enrollment_value").val('Yes');
			}
		});

		$('#freedom_fighter_dependent').change(function() {
			var freedom_fighter_dependent = $('#freedom_fighter_dependent').val();
			if (freedom_fighter_dependent == "yes") {
				$("#freedom_fighter_dependent1").show();
				$("#freedom_fighter_dependent11").show();
				$("#freedom_fighter_dependent_value").val('');
			} else {
				$("#freedom_fighter_dependent1").hide();
				$("#freedom_fighter_dependent11").hide();
				$("freedom_fighter_dependent_value").val('yes');
			}
		});

		$('#ncc_cirtificate').change(function() {
			var ncc_cirtificate = $('#ncc_cirtificate').val();
			if (ncc_cirtificate == "yes") {
				$("#ncc_cirtificate1").show();
				$("#ncc_cirtificate11").show();
				$("#ncc_cirtificate_value").val('');
			} else {
				$("#ncc_cirtificate1").hide();
				$("#ncc_cirtificate11").hide();
				$("ncc_cirtificate_value").val('yes');
			}
		});

		$('#nss_cirtificate').change(function() {
			var nss_cirtificate = $('#nss_cirtificate').val();
			if (nss_cirtificate == "yes") {
				$("#nss_cirtificate1").show();
				$("#nss_cirtificate11").show();
				$("#nss_cirtificate_value").val('');
			} else {
				$("#nss_cirtificate1").hide();
				$("#nss_cirtificate11").hide();
				$("nss_cirtificate_value").val('yes');
			}
		});

		$('#sports').change(function() {
			var sports = $('#sports').val();
			if (sports == "yes") {
				$("#sports1").show();
				$("#sports11").show();
				$("#sportt_cirtificate1").show();
				$("#sportt_cirtificate11").show();
				$("#sports_value").val('');
			} else {
				$("#sports1").hide();
				$("#sports11").hide();
				$("#sportt_cirtificate1").hide();
				$("#sportt_cirtificate11").hide();
				$("sports_value").val('yes');
			}
		});

		$('#hostal_options').change(function() {
			var hostal_options = $('#hostal_options').val();
			if (hostal_options == "yes") {
				$("#hostal_options1").show();
				$("#hostal_options11").show();
				$("#hostel_distence1").show();
				$("#hostel_distence11").show();
				// $("#sports_value").val('');
			} else {
				$("#hostal_options1").hide();
				$("#hostal_options11").hide();
				$("#hostel_distence1").hide();
				$("#hostel_distence11").hide();
				// $("sports_value").val('yes');
			}
		});

		$("input").keypress(function() {
			$(this).parent().find('.invalid-feedback').text('');
		});

		$("select").keypress(function() {
			$(this).parent().find('.invalid-feedback').text('');
		});


		$("input").on('change', function() {
			$(this).parent().find('.invalid-feedback').text('');
		});

		$("select").on('change', function() {
			$(this).parent().find('.invalid-feedback').text('');
		});

		$('input:radio, input:checkbox').on('click', function() {
			$(this).parent().parent().find('.invalid-feedback').text('');
		});

		$('input[type=file]').on('change', function() {

			var count = 0;
			var max_size = 100; /* max size in kb */
			const size = (this.files[0].size / 1024 / 1024).toFixed(2);

			var size_in_kb = Math.floor(this.files[0].size/1000);
			if (size_in_kb > max_size) {
				$("#error").dialog().text('File must be less than the size of '+max_size+' KB');
				//alert("File must be less than the size of 500 KB");
				$(this).closest('input').val('');
			}
		});

		$('.numbersOnly').keyup(function() {
			this.value = this.value.replace(/[^0-9\.]/g, '');
		});

	});



	function addNewEducation($this) {
		//	alert($('.educationtable .name_of_exam:nth-child(1)').val());
		
		$.ajax({
			headers: {
				'X-CSRF-Token': $('meta[name=_token]').attr('content')
			},
			type: 'GET',
			url: "{{ route('education-single-row') }}?rows="+$this.closest('tbody').find('tr').length,
			success: function(data) {
				console.log(data.html);
				$('.educationtable tbody tr:last').after(data.html);
			}
		});

	}

	function delete_education($this) {
		$this.closest('tr').remove();
	}

	function check_caste_certificate_number(category) {
		if (category == '' || category == 'General') {
			$('.certificate_no_text').hide();
		} else {
			$('.certificate_no_text').show();
			
		}
	}

	function change_disability_category(disability) {
		if (disability == '') {
			$('.disability_certificate_no_text').hide();
		} else {
			$('.disability_certificate_no_text').show();
			
		}
	}

	function disability_cat_open(disabilityesno) {
		if (disabilityesno == 'yes') {
			$('.disability_category_box').show();
		} else {
			$('.disability_category_box').hide();
			$('.disability_certificate_no_text').hide();
			$("#disability_category")[0].selectedIndex = 0;
		}
	}

	function open_dsmnru_relationship(dsmnrurelation) {
		if (dsmnrurelation == 'yes') {
			$('.dsmnru_relationship').show();
		} else {
			$('.dsmnru_relationship').hide();
			// $("#disability_category")[0].selectedIndex = 0;
		}
	}
	
	function set_name_and_relation($this){
		if($this.val()=='yes'){
			$('.ward_emp_name_and_relation').show();
		}else{
			$('.ward_emp_name_and_relation').hide();
			$('.ward_emp_name_and_relation').find('input').val('');
		}
	}

	$(document).ready(function() {
		$('#p_country').val('India');
		var country = 'India';
	//	$("#p_state_union_territory").val('Uttar Pradesh');
		p_country();
		
	});

	function p_country() {
			var country_id = $('#p_country').val();
			$("#p_state_union_territory").find('option').remove().end();
			var formData = {
				country_id: country_id,
				"_token": "{{ csrf_token() }}"
			}; //Array 
			$.ajax({
				url: "{{url('get-state')}}",
				type: "POST",
				data: formData,
				success: function(data, textStatus, jqXHR) {
					$('#p_state_union_territory').append(data);
					p_state(country_id);
					p_district();
				},
			});
		}
		function p_state(country){
			if(country=='India'){
				//alert(country);
			$("#p_state_union_territory").val('Uttar Pradesh');
			}
		}
		function p_district(){
			$state='Uttar Pradesh';
			var state_id ='Uttar Pradesh';
			$("#p_district").find('option').remove().end();
			var formData = {
				state_id: state_id,
				"_token": "{{ csrf_token() }}"
			}; //Array 
			$.ajax({
				url: "{{url('get-district')}}",
				type: "POST",
				data: formData,
				success: function(data, textStatus, jqXHR) {
					$('#p_district').append(data);
				},
			});
			
			
		}
		function btech(value){
			if(value==41){
				$("#admission_applying_through").show();
				$("#ded").hide();

			}
			else if(value==42){
				$("#admission_applying_through").show();
				$("#ded").hide();

			}
			else if(value==43){
				$("#admission_applying_through").show();
				$("#ded").hide();

			}else if(value==44){
				$("#admission_applying_through").show();
				$("#ded").hide();
			}
			else if(value==45){
				$("#admission_applying_through").show();
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
				$("#ded").hide();

			}
		}
		function admissionthrough(val){
			if(val=='JEEE(MAIN)'){
				$('.upsee').hide();
				$('.jee').show();
				}
			else if(val=='UPSEE'){
				$('.upsee').show();
				$('.jee').hide();

			}else{
				$('.jee').hide();
				$('.upsee').hide();
			}
			

		}

/=============Education Code =========/
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


$("#priview").on("click", function() {
    $("body").scrollTop(0);
});

</script>

@endsection



@endsection
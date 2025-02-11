@extends('ums.admin.admin-meta')
@section('content')

<div class="app-content content ">
	<div class="content-overlay"></div>
	<div class="header-navbar-shadow"></div>
	<div class="content-wrapper container-xxl p-0">
		<div class="content-header row">
			<div class="content-header-left col-md-5 mb-2">
				<div class="row breadcrumbs-top">
					
				</div>
			</div>
			<div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
				<div class="form-group breadcrumb-right">

					<form id="examination_form" method="POST" action="{{route('examination-fee')}}" enctype="multipart/form-data">
						<button class="btn  btn-sm mb-50 mb-sm-0"
							onclick="window.location.reload(); "style="background:orange; color:white;"><i
								data-feather="arrow-left"></i>
							Back</button>
					<button class="btn  btn-sm mb-50 mb-sm-0 bg-primary text-white" data-bs-toggle="modal"
						data-bs-target="#searchModal"> <i data-feather="upload"></i>
						Submit</button>
						<a href="{{asset('bank-details/final_back_bank_details.pdf')}}" class="btn btn-sm btn-info" target="_blank">Fees Account Details</a>


				</div>
			</div>
		</div>
		<div class="content-body">

<section class="mb-3 mt-0">
<br/>
{{-- @include('ums.student.partials.notifications') --}}
	@csrf
		<div class="container-fluid">  
			<div class="col-md-12 text-right"></div>
			<div class="row mt-3 align-items-center">
				<input name="exam_fee_id" type="text" value="{{$slug}}" readonly hidden>
				<div class="col-4 mt-3">
					<h5>Candidates Exam Fees </h5>
				</div> 

				<div class="col-md-12">
					<div class="border-bottom mt-3 mb-2 border-innerdashed"> </div>
				</div>
			</div>
			
			<div class="row" id="bank_offline">

				<section class="col-md-12 connectedSortable">
 
				   <div class="row">
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page"> Student Name</span>
								<input type="text" class="form-control" value="{{$student->full_name}}" readonly>
							</div> 
					   </div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page"> Father Name</span>
								<input type="text" class="form-control" value="{{$student->father_name}}" readonly>
							</div> 
					   </div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page"> Enrollment Number</span>
								<input type="text" class="form-control" value="{{$student->enrollment_no}}" readonly>
							</div> 
					   </div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page"> Roll Number</span>
								<input type="text" class="form-control" value="{{$student->roll_number}}" readonly>
							</div> 
					   </div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page"> Course</span>
								<input type="text" class="form-control" value="{{$examfee->course->name}}" readonly>
							</div> 
					   </div>
						{{--<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page"> Semester</span>
								<input type="text" class="form-control" value="{{$examfee->semesters->name}}" readonly>
							</div> 
					   </div>--}}

						<div class="col-md-12"><hr/></div>
						<div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page"> Bank Name</span>
								<input id="exam_form" name="exam_form" type="text" class="form-control" value="{{Request()->exam_form}}" hidden><input id="bank_name" name="bank_name" type="text" class="form-control" value="{{old('bank_name')}}" placeholder="Enter Your bank name here" required>
							</div> 
							  <span class="text-danger">{{ $errors->first('bank_name') }}</span>
					   </div>
					   <div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Bank IFSC Code</span>
								<input id="bank_IFSC_code" name="bank_IFSC_code" type="text" class="form-control" value="{{old('bank_IFSC_code')}}" placeholder="Enter Your Bank IFSC Code here" required>
							</div> 
							<span class="text-danger">{{ $errors->first('bank_IFSC_code') }}</span>
					   </div>
					   <div class="col-md-4">
							<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page"> Challan/RTGS/NEFT Number</span>
								<input id="challan_number" name="challan_number" type="text" class="form-control" value="{{old('challan_number')}}" placeholder="Enter Your challan number here" required>
								
							</div> 
							<span class="text-danger">{{ $errors->first('challan_number') }}</span>
					   </div>
						
								
					     
					   
					   </div>
					   </section>
			    
			    <section class="col-md-12 connectedSortable"> 
				   <div class="row">

					   <div class="col-md-4">
								<div class="form-group position-relative custom-form-group inner-formnew">
									<span class="form-label main-page"> Challan/Fees Amount</span>
									<input id="fee_amount" name="fee_amount" type="number" class="form-control" @if($examfee && $examfee->form_type=='final_back_paper') value="{{$amount}}" readonly @endif required>
								</div> 
								<span class="text-danger">{{ $errors->first('fee_amount') }}</span>
						</div>
							<div class="col-md-4">
								<div class="form-group position-relative custom-form-group inner-formnew">
								<span class="form-label main-page">Challan/RTGS/NEFT Document</span>
									<input id="challan_document" name="challan" type="file" class="form-control" value="{{old('challan')}}" placeholder="Choose Your Challan/RTGS/NEFT Document here" accept="image/*" required>
									<span>(Uploaded Document should be in range of 100 Kb and only JPG, PNG ,PDF format accepted)</span>
								</div>
									<span class="text-danger">{{ $errors->first('challan') }}</span>
						</div>
							<div class="col-md-4">
								<div class="form-group position-relative custom-form-group inner-formnew">
									<span class="form-label main-page">Challan/Reciept date</span>
									<input id="challan_reciept_date" name="challan_reciept_date" type="date" class="form-control" value="{{old('challan_reciept_date')}}" placeholder="Enter Your challan reciept date here" required>
									<div class="invalid-feedback text-danger challan_reciept_date_exam"></div>
								</div> 
								<span class="text-danger">{{ $errors->first('challan_reciept_date') }}</span>
						</div>
					  
					   </div>
					   </section>
				</div>
		</div>
		{{-- <a href="{{ url()->previous() }}" class="btn btn-info">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> Back
		</a>
		<button type="submit" class="btn btn-warning">
			<i class="fa fa-send" aria-hidden="true"></i> Submit
		</button> --}}
		
	</form>
</section>
</div>
@endsection



{{-- Scripts --}}
@section('scripts')
@parent

<script src="/assets/admin/plugins/high/highcharts.js"></script>
<script src="/assets/admin/plugins/high/exporting.js"></script>
<script src="/assets/admin/plugins/high/export-data.js"></script>
<script src="/assets/admin/plugins/high/accessibility.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ajaxStart(function () {
	$("#loading-image").show();
 })
.ajaxStop(function () {
	$("#loading-image").hide();
});



$(document).ready(function(){
	$("input").keypress(function() {
			$(this).find('.text-danger').text('');
		});
		$("input").on('change', function() {
			$(this).find('.text-danger').text('');
		});
		$('input[type=file]').on('change', function() {

			var count = 0;


			const size =
				(this.files[0].size / 1024 / 1024).toFixed(2);

			if (size > 0.1) {
				$("#error").dialog().text('File must be less than the size of 100 KB');
				//alert("File must be less than the size of 500 KB");

				$(this).closest('input').val('');
			}
		});
	




});
</script>
@endsection
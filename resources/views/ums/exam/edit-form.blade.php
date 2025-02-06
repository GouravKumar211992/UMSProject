@extends("ums.admin.admin-meta")
{{-- Web site Title --}}
@section('title') Exam Form Edit :: @parent @stop
@section('content')
<div class="app-content content ">
	<div class="content-overlay"></div>
	<div class="header-navbar-shadow"></div>
	<div class="content-wrapper container-xxl p-0">
		<div class="content-header row">
			<div class="content-header-left col-md-5 mb-2">
				<div class="row breadcrumbs-top">
					<div class="col-12">
						<h2 class="content-header-title float-start mb-0">Exam Fee</h2>
						<div class="breadcrumb-wrapper">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.html">Home</a></li>
								<li class="breadcrumb-item active">Exam Fee List</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
				<div class="form-group breadcrumb-right">

					<form id="edit_exam_form" method="POST" action="{{route('edit-exam-form',$slug)}}">
					<button class="btn  btn-sm mb-50 mb-sm-0 bg-primary text-white" type="submit" 
						> <i data-feather="upload"></i>
						Update Subject</button>
					<button class="btn  btn-sm mb-50 mb-sm-0"
						onclick="window.location.reload(); "style="background:orange; color:white;"><i
							data-feather="refresh-cw"></i>
						Reset</button>


				</div>
			</div>
		</div>
		<div class="content-body">
	@csrf
	<div class="container-fluid">
		<div class="row">
			<section class="col-md-12 connectedSortable">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group position-relative custom-form-group inner-formnew">
							<span class="form-label main-page">Exam Fee ID</span>
							<input id="exam_fee_id" name="exam_fee_id" type="text" readonly
								value="{{$exam_fee->id}}" class="form-control">
							@if ($errors->has('exam_fee_id'))
							<span class="text-danger">{{ $errors->first('exam_fee_id') }}</span>
							@endif
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group position-relative custom-form-group inner-formnew">
							<span class="form-label main-page">Course</span>
							<input id="course_id" name="course_id" value="{{$exam_fee->course_id}}" type="text"
								hidden readonly class="form-control" placeholder="Enter Course Code here">
							<input id="course_name" name="course_name" type="text"
								value="{{$exam_fee->course->name}}" readonly class="form-control"
								placeholder="Enter Course Code here">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group position-relative custom-form-group inner-formnew">
							<span class="form-label main-page">Semester</span>
							<input id="semester_id" name="semester_id" type="text" readonly hidden
								value="{{$exam_fee->semester}}" class="form-control"
								placeholder="Enter Semester here">
							<input id="semester" name="semester" type="text" class="form-control" readonly
								value="{{$exam_fee->semesters->name}}" placeholder="Enter Semester here">

							@if ($errors->has('semester'))
							<span class="text-danger">{{ $errors->first('semester') }}</span>
							@endif
						</div>
					</div>


				</div>
			</section>
			<div class="col-md-4">
				<div class="form-group position-relative custom-form-group inner-formnew">
					<span class="form-label main-page" for="scribe">WRITER/SCRIBE REQUIRED</span>
					
					<input name="scribe" value="{{ $exam_fee->scribe }}" type="text" class="form-control"
						placeholder="Enter Writer/Scribe details here">

				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group position-relative custom-form-group inner-formnew">
					<span class="form-label main-page" for="vaccinated">Vaccination Status</span>
					
					<input name="vaccinated" value="{{ $exam_fee->vaccinated }}" type="text" class="form-control"
						placeholder="Enter Vaccination Status">

				</div>
			</div>
		
			<div class="col-md-4">
				<div class="form-group position-relative custom-form-group inner-formnew">
					<span class="form-label main-page" for="bank_name">Bank Name</span>
					
					<input name="bank_name" value="{{ $exam_fee->bank_name }}" type="text" class="form-control"
						placeholder="Enter bank_name">

				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group position-relative custom-form-group inner-formnew">
					<span class="form-label main-page" for="bank_IFSC_code">Bank IFSC Code</span>
					
					<input name="bank_IFSC_code" value="{{ $exam_fee->bank_IFSC_code }}" type="text" class="form-control"
						placeholder="Enter Bank IFSC Code">

				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group position-relative custom-form-group inner-formnew">
					<span class="form-label main-page" for="receipt_number">Challan/RTGS/NEFT Number</span>
					
					<input name="receipt_number" value="{{ $exam_fee->receipt_number }}" type="text" class="form-control"
						placeholder="Enter Challan/RTGS/NEFT Number">

				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group position-relative custom-form-group inner-formnew">
					<span class="form-label main-page" for="fee_amount">Challan/Fees Amount</span>
					
					<input name="fee_amount" value="{{ $exam_fee->fee_amount }}" type="text" class="form-control"
						placeholder="Enter Challan/Fees Amount">

				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group position-relative custom-form-group inner-formnew">
					<span class="form-label main-page" for="receipt_date">Challan/Reciept date</span>
					
					<input name="receipt_date" value="{{ $exam_fee->receipt_date }}" type="text" class="form-control"
						placeholder="Enter Challan/Reciept datee">

				</div>
			</div>
			<section class="col-md-12 connectedSortable">
				<div class="row">
					<div class="col-md-4 mb-4">
						<div class="form-group position-relative custom-form-group inner-formnew">
							<span class="form-label main-page">Subjects</span>
							<table id="" class="display" style="width:100%" border="1px">
								<thead>
									<tr>
										<th></th>
										<th>Subject Code</th>
										<th>Subject Name</th>
										<th>Subject Type</th>
									</tr>
								</thead>
								@php
								$key=0;

								@endphp
								<tbody>
									@foreach($subjects as $subject)
									<tr>
										<td><input type="checkbox" value="{{$subject->sub_code}}" name="paper[]"
												class="form-group"
												@if(str_contains($exam_fee->subject,$subject->sub_code)) checked
											@endif ></td>
										<td>{{$subject->sub_code}}</td>
										<td>{{$subject->name}}</td>
										<td>{{$subject->subject_type}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>


						</div>
					</div>

			</section>
		</div>

		

	</div>
</form>
		</div>

@endsection

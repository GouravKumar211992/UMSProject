@extends('frontend.layouts.user')
@section('content')

<style>
	.blink_me {
		font-size: 30px;
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
   position: inherit;
    left: 0;
    opacity: inherit;
}
.home-top-cour:hover{
	transform: scale(1);
}
</style>
<link href="{{asset('assets/bootstrap/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<!-- <link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet"> -->
<div class="udb">
@include('partials.notifications')
@if($lastApplication)
	@if($lastApplication->status=='Approved' && !$student_semster)
	<div class="udb-sec udb-cour">
	<h4><img src="images/icon/db2.png" alt="" />Apllication Approved</h4>
	<p>Your Application ID: <strong>{{$lastApplication->application_no}}</strong> has been approved And Now You Can Select Your Subject Which Are Provided During Counselling.</p>

	<div class="home-top-cour">  
		<div class="col-md-12 home-top-cour-desc">
			@include('student.partials.notifications')
			<div class="row">
			<form id="semesterfee_form" enctype="multipart/form-data" method="POST" action="{{route('generatesemester')}}">
				@csrf			
				<div class="col-md-4">
					<p>Application Id:</p>
					<input type="hidden"  name="application_id" id="application_id" value="{{$lastApplication->application_no}}">
					<h4>{{$lastApplication->application_no}}</h4>
				</div>
				<div class="col-md-8">
					<p>Program Name</p>
					<input type="hidden"  name="program_id" id="program_id" value="{{$lastApplication->category_id}}">
					<h3><i class="fa fa-book"></i> {{($lastApplication->category_id)?$lastApplication->categories->name:''}}</h3>
				</div><div class="col-md-8">
					<p>Course Name</p>
					<input type="hidden"  name="course_id" id="course_id" value="{{$lastApplication->course_id}}">
					<h3><i class="fa fa-book"></i> {{($lastApplication->course)?$lastApplication->course->name:''}}</h3>
				</div>
				{{--dd($subjectList)--}}
				@if(count($subjectList)>0)
				{{--dd($subjectList)--}}
				<div class="col-md-12">
					<h3 for="exampleFormControlSelect1">Select Subjects</h3>
					<input type="hidden"  name="semester_id" id="semester_id" value="{{$subjectList[0]->semester_id}}">
					
					<table id="subjectList" class="display" style="width:100%" border="1px">
							<thead>
								<tr> <th></th>
								<th>Subject Code</th>
								<th>Subject Name</th>
								<th>Type</th>
								<th>Subject Type</th>
								</tr>
							</thead>
                           
							<tbody>
							@foreach($subjectList as $subject)
							<tr><td><input type="checkbox" value="{{$subject->sub_code}}" {{(old('subject_id')=='$subject->sub_code') ? 'checked' : ''}} name="subject_id[]"></td>
							<td>{{$subject->sub_code}}</td>
							<td>{{$subject->name}}</td>
							<td>{{ucfirst($subject->subject_type)}}</td>
							<td>{{ucfirst($subject->type)}}</td></tr>
							@endforeach
							</tbody>
							</table>
							<span class="text-danger">{{ $errors->first('subject') }}</span>
					
						

					@if ($errors->has('subject_id'))
					<span class="text-danger">{{ $errors->first('subject_id') }}</span>
					@endif
				</div>
				@else
				<p>No Subject Found For Respective Course</p>
				@endif
				{{--dd($lastApplication)--}}
				<!--div class="row">
					<div class="col-md-12 mb-5">
						<label for="">Payment Mode:<span class="text-danger">*</span></label>
						<p class="newboxciradio">
							<input type="radio" class="filled-in" name="payment" value="1" id="online" {{(old('payment') == '1') ? 'checked' : ''}}>
							<label for="online" class="form-check-label"><strong>Online (Credit card/Debit card/Net Banking )</strong></label>

							<input type="radio" class="filled-in" name="payment" value="2" id="offline" {{(old('payment') == '2') ? 'checked' : ''}}>
							<label for="offline" class="form-check-label ml-5"><strong>Offline ( to Pay Offline the details given below)</strong></label>
						<span class="text-danger">{{ $errors->first('payment') }}</span>
						</p>
					</div>
				</div>
				<div class="row mb-5">
					<div class="col-md-6" id="bank_online">
						<a href="{{url('semester-payment?id='.$lastApplication->application_no)}}" class="btn btn-dark mt-4" style="margin:auto;">make payment</a>
					</div>
				</div>

				<div class="row" id="bank_offline">
						<h5 class="mb-3"><strong class="f-16">Bank Detail</strong></h5>
						<table class="table table-bordered" >
							<tr>
								<td width="30%">Account Holder<span class="text-danger">*</span></td>
								<td>
									<p class="f-16 text-black"><strong>DSMNRU</strong></p>
								</td>
							</tr>
							<tr>
								<td>Account Holder’s Name<span class="text-danger">*</span></td>
								<td>
									<p class="f-16 text-black"><strong>University</strong></p>
								</td>
							</tr>
							<tr>
								<td>Name of the Bank</td>
								<td>
									<p class="f-16 text-black"><strong>Axis Bank</strong></p>
								</td>
							</tr>
							<tr>
								<td>Account No.</td>
								<td>
									<p class="f-16 text-black"><strong>098787655655</strong></p>
								</td>
							</tr>
							<tr>
								<td>IFSC Code</td>
								<td>
									<p class="f-16 text-black"><strong>UTIB0000654</strong></p>
								</td>
							</tr>
						</table>
						<h5 class="mb-3"><strong class="f-16">After Payment: If Online than Auto else Candidate will fill offline Fee Details.</strong></h5>
						<table class="table table-bordered bottomform">
							<tr>
								<td width="30%">ORDER ID <strong class="text-danger">* </strong></td>
								<td>
									<input type="text" class="form-control" name="order_id" value="{{old('order_id')}}">
									<span class="text-danger">{{ $errors->first('order_id') }}</span>
								</td>
							</tr>
							<tr>
								<td>TRANSACTION ID <strong class="text-danger">* </strong></td>
								<td>
									<input type="text" class="form-control" name="transaction_id" value="{{old('transaction_id')}}">
									<span class="text-danger">{{ $errors->first('transaction_id') }}</span>
								</td>
							</tr>
							<tr>
								<td>AMOUNT PAYABLE <strong class="text-danger">* </strong></td>
								<td>@if($lastApplication->disability_category=='Not Applicable')
									<input type="text" class="form-control numbersOnly" name="paid_amount" readonly value="{{$total_non_disabled_fees}}">
								@else
									<input type="text" class="form-control numbersOnly" name="paid_amount" readonly value="{{$total_disabled_fees}}">
								@endif
									<span class="text-danger">{{ $errors->first('paid_amount') }}</span>
								</td>
							</tr>
							<tr>
								<td>TXN DATE <strong class="text-danger">* </strong></td>
								<td>
									<input type="date" class="form-control" name="txn_date" value="{{old('txn_date')}}">
									<span class="text-danger">{{ $errors->first('txn_date') }}</span>
								</td>
							</tr>
							<tr>
								<td>TXN STATUS <strong class="text-danger">* </strong></td>
								<td>
									<input type="text" class="form-control" name="txn_status" value="{{old('txn_status')}}">
									<span class="text-danger">{{ $errors->first('txn_status') }}</span>
								</td>
							</tr>
							<tr>
								<td>Upload Payment Supporting File<strong class="text-danger">* </strong></td>
								<td>
									<input type="file" class="form-control" name="payment_file" value="{{old('payment_file')}}">
									<span class="text-danger">{{ $errors->first('payment_file') }}</span>
								</td>
							</tr>
						</table>
					
				</div-->
				<div class="col-md-4 mt-3">
					<input type="submit" class="btn btn-primary" value="Submit">
				</div>
				</form>
				
			</div>
			
            
		</div>
	</div>
@endif

				

</div> @if($student_semster)
<div class="udb-sec udb-cour">
	<h4><img src="images/icon/db2.png" alt="" />Subject Selected</h4>
	<p>Your Application ID: <strong>{{$lastApplication->application_no}}</strong> has been approved And Now You Can Pay Your Enrollment Fee</p>
	@if(!$student_semster->receipt_number)
				<div class="row mb-5">
					<div class="col-md-6">
						<a href="{{url('semester-payment?id='.$lastApplication->id)}}" class="btn btn-dark mt-4" style="margin:auto;">make payment</a>
					</div>
				</div>
				@endif
				@endif
</div>

@if($lastApplication->counselling_date)
<div class="udb-sec udb-cour">
	<h4><img src="images/icon/db2.png" alt="" /> Counselling details</h4>
	<p>Your Application ID: <strong>{{$lastApplication->application_no}}</strong> has been approved. And Counselling has been Scheduled as per below details.</p>
	
	<div class="home-top-cour">  
		<div class="col-md-12 home-top-cour-desc">
			 
			<div class="row"> 
				<div class="col-md-4">
					<p>Scheduled Date</p>
					<h3 class="f-16"><i class="fa fa-calendar"></i> {{($lastApplication->counselling_date)?date('d-M-Y',strtotime($lastApplication->counselling_date)):''}} {{($lastApplication->counselling_time)?$lastApplication->counselling_time:''}}</h3>
				</div>
				<div class="col-md-8">
					<p>Course Name</p>
					<h3><i class="fa fa-book"></i> {{($lastApplication->course)?$lastApplication->course->name:''}}</h3>
				</div>
				<div class="col-md-12 mt-3">
					<p>Counselling Vanue</p>
					<h3><i class="fa fa-books"></i> {{($lastApplication->counselling_vanue)?$lastApplication->counselling_vanue:''}}</h3>
				</div>
				<div class="col-md-12 mt-3">
					<p>Remarks:</p>
					<h3><i class="fa fa-books"></i> {{($lastApplication->remarks)?$lastApplication->remarks:''}}</h3>
				</div>
			</div>
            @if($lastApplication->fees)
			<h4></h4>
			<p class="f-16"><strong>Note:</strong> {{($lastApplication->fees)?$lastApplication->fees->basic_eligibility:''}}</p>  
			 @endif
		</div>
	</div>



</div> 
@endif

@endif

    <!--div class="udb-sec udb-cour" style="display:none;">
        <h4><img src="images/icon/db2.png" alt="" /> Recently Applied Course</h4>

        @if($lastApplication)
        <div class="home-top-cour">

            <div class="table-responsive table-desi">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Course Name</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Views</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>1</td>
                            <td><a href="#"><span class="list-enq-name">{{($lastApplication->course)?$lastApplication->course->name:''}}</span></a></td>
                            <td>{{date('d-M-Y', strtotime($lastApplication->created_at))}}</td>
                            <td>
                                <span class="label label-success">{{$lastApplication->status}}</span>
                            </td>
                            <td><a href="{{route('view-application-form',['application_id'=>$lastApplication->id])}}" class="ad-st-view">View</a></td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
		@else
			You have not applied any Course!!!!<br>Please Apply First<br>
			<a href="/application-form">Click Here To Apply</a>
        @endif

    </div-->
	

    <div class="udb-sec udb-cour-stat">
        <h4><img src="images/icon/db3.png" alt="" /> Course Application Status</h4>
        {{-- @if(Auth::user()->course_type == 0)
        <p style="color:red"><strong>Notice:-</strong> Kindly upload qualifying marksheet and update marks and correct your data, if needed other wise ignore it.<br>Editing facility is available from (06 - August - 2022 to 12 - August - 2022).</p>
        @endif --}}

		@if(count($applications)>0)
        <div class="mt-4 pt-4">
            <div class="table-responsive">
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Course Name</th>
                            <th>Applied Date</th>
                            <th>View</th>
                            <th style="width: 300px;">Application Status</th>
                            <th>Action</th>	
                        </tr>
                    </thead>
                    <tbody>

                     @foreach ($applications as $key => $application)
                        <tr>
                            <td>{{$key +1 }}</td>
                            <td><a href="#"><span class="list-enq-name">{{$application->course->name}}</span></a></td>
                            <td>{{date('d-M-Y', strtotime($application->created_at))}}</td>
                            <td>
								<a href="{{route('view-application-form',['application_id'=>$application->id])}}" class="ad-st-view">View and Print</a>
								@if($application->course_id==94 && $application->phd_2023_entrance_details)
								<a href="{{url('phd-entrance-admitcard')}}?roll_number={{$application->phd_2023_entrance_details->roll_number}}" class="btn btn-info">View and Print Admit Card</a>
								@endif
								@if(admission_open_couse_wise($application->course_id,2,$application->academic_session))
								<br>
								<a href="{{route('view-application-form',['application_id'=>$application->id,'edit'=>'true'])}}" class="btn-sm btn-primary">Edit Application</a>
								@endif
								@if($application->entranceExamAdmitCard && $application->payment_status==1)
									<a href="{{url('entrance-admit-card/'.$application->id)}}" class="ad-st-view" target="_blank">Download Admit Card</a>
								@endif
	                            @if(checkAdminRoll())
								@if($application->course_id == 11 || $application->course_id == 26 || $application->course_id == 27)
									<!-- <a href="{{url('aiot-upload',['application_id'=>$application->id])}}" class="ad-st-view">AIOT Upload</a> -->
								@endif
								@if(Auth::user()->course_type == 0)
								@if($application->course_allowted_for_update_docs)
								<a href="{{url('additional-education-qualification',['application_id'=>$application->id])}}" class="ad-st-view">Upload Document</a>
								@endif	
								@endif	
                            @endif	
						</td>
							
							@if($application->payment)
								@if($application->payment_details())
								<th style="color:green;">
								<span class="label label-success">{{strtoupper($application->payment_status_text)}}</span> <a target="_blank" href="{{url('pay-success')}}?id={{$application->id}}" class="btn-sm btn-success" style="color: #fff;">Print Payment Slip</a>
								</th>
								@else
								<th style="color:red;">
									<span class="label label-success">{{strtoupper($application->status)}}</span>
									<a href="{{url('pay-success')}}?id={{$application->id}}" class="btn-sm btn-danger" style="color: #fff;">Payment Slip</a>
									@if($application->course->visible_in_application ==1)
									<a target="_blank" href="{{route('pay-now',['id'=>$application->id])}}" class="btn-sm btn-primary" style="color: #fff;">Pay Now</a>
									@endif
								</th>
								@endif
							@else
							@if($application->course->visible_in_application ==1)
                            	<td>{{$application->payment_status_text}}<br/><a target="_blank" href="{{route('pay-now',['id'=>$application->id])}}" class="btn-sm btn-primary" style="color: #fff;">Pay Now</a></td>
							@endif
							@endif
                            <td>
                            @if($application->payment_details()==false && Auth::guard('admin')->check()==true)
                            	<a href="{{route('delete-application',$application->id)}}" onClick="return confirm('Are you sure?');" class="ad-st-view">Delete</a>
                            @endif
                            </td>

                        </tr>
						@endforeach
					 <tr>
					 	<td colspan="6">
						@if($application->course_id!=94)
						 <a  class="btn-sm btn-success pull-right" style="color:#fff;" href="{{route('application-form')}}">Apply for more courses</a>
						 @endif
						 <div class="clearfix"></div>
					 	@if(Auth::guard('admin')->check())

							<br/>
							<h4>Update Photo/Signature of the user</h4>

					 		<form method="POST" action="{{url('update-photo-signature')}}"  enctype="multipart/form-data" autocomplete="off">
								@csrf
								<div class="row">
									<div class="col-md-6 form-group">
									 	<input type="hidden" name="user_id" value="{{$application->user_id}}">
										<label style="color: red;">Upload Photo</label>
											<div class="col-md-12 mb-12">
											<input type="file" class="form-control" name="upload_photo" accept="image/*">
											<div class="invalid-feedback text-danger upload_photo_application"></div>
											</div>
									</div>
									<div class="col-md-6 form-group">
										<label style="color: red;">Upload Signature</label>
										<input type="file" class="form-control" name="upload_signature" accept="image/*">
										<div class="invalid-feedback text-danger upload_signature_application"></div>
									</div>
									<div class="col-md-8 form-group">
									</div>
									<div class="col-md-4 form-group">
									<button type="submit" class="btn btn-warning btn-block" >Submit
									</button>
									</div>
								</div>
							</form>
						<!-- </td>
						 <td colspan="1"> -->
						 @endif
						</td>
					</tr>

                    </tbody>
                </table>
            </div>
        </div>
		@else
		<b>	You have not applied any Course!!!!<br>Please Apply First<br></b><br/>
		@if($course_type == 0)
			<a href="/application-form"> <span class="blink_me">👉</span><b>Click Here To Apply</b></a><br>
			@else
			<a href="/application-form-phd"> <span class="blink_me">👉</span><b>Click Here To Apply Ph.D. Application Form
			</b></a>
			@endif
		@endif

    </div>


</div>
@endsection
@section('scripts')
 <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script>

$(document).ready(function() {

	$("#bank_offline").hide();
	$("#bank_online").hide();

	$('#offline').on('click',function(){
		$("#bank_offline").show(); 
		$("#bank_online").hide(); 
	});

	$('#online').on('click',function(){
		$("#bank_offline").hide(); 
		$("#bank_online").show(); 
	});

	$('#subjectList').DataTable({searching: false, paging: false, info: false});

});


</script>
@endsection
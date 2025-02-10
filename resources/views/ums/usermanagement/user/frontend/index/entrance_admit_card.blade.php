<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/admitcard.css')}}" crossorigin="anonymous">
	<style>
	.txt-center {
    text-align: center;
}

.padding {
    padding: 15px;
}
.mar-bot {
    margin-bottom: 15px;
}
.divborder {
    border: 1px solid #000 !important;
}
.admitcard {
    border: 2px solid #000;
    padding: 15px;
    margin: 20px 0;
}
.headerdiv h5, .headerdiv p {
    margin: 0;
}
h5 {
    text-transform: uppercase;
}
table img {
    width: 100%;
    margin: 0 auto;
}
.table-bordered td, .table-bordered th, .table thead th {
    border: 1px solid #000000 !important;
}
.text-block {
    color: black;
    padding-left: 33px;
    padding-right: 20px;
	font-size: 15px;
    font-weight: lighter;
	border: 1px solid;
    padding: 55px 0px;
    text-align: center;
	height: 200px;
    width: 170px;
	margin: auto;
}
@media print{
	.print_break{
		page-break-after: always;
	}
	.noPrint{
		display: none !important;
	}
}

	</style>
    <title>Admit Card</title>



</head>

<body>
<p style="text-align: center; margin-top: 0px; cursor: pointer; margin-bottom: 0px;">
		<button onclick="window.print();" class="btn btn-info noPrint"> <img src="{{asset('assets\frontend\images\print.png')}}" style="height: 12px; margin-top: 2px;"> Print</button>
		@if(@$AdmitCard->scribe_eligibility_status==1)
			@if($scribe)
			<a href="{{route('phd-scribe-admitcard',[$AdmitCard->id])}}" class="btn btn-success noPrint">View Writer's/Scribe's Admit Card</a>
			@else
			<a href="{{route('phd-scribe-form',[$AdmitCard->id])}}" class="btn btn-warning noPrint">Writer's/Scribe's Form</a>
			@endif
		@endif
</p> 
<section>
	<div class="container" style="padding: 0px;">
		<div class="admitcard">
		<h6 class="text-left" style="font-size: 25px;font-weight: bold;"><code>Note :- </code>The applicants with visual impairment should arrange  their own scribe/writer</h6>
		<div class="headerdiv divborder padding mar-bot"> 
			<div class="row">
				<div class="col-md-12">
					<p style="text-align: center; margin-bottom: 0px; margin-top: 0px;"><img src="{{asset('assets\frontend\images\cerlogo.png')}}" alt=""></p>
					<h3 class="capitalize" style="text-align: center;  margin-bottom: 0px; margin-top: 10px; line-height: 15px;">Entrance Examination Admit Card, Session 2024-2025</h3>
				</div>
				
			</div>
		</div>
		<div class="BoxC divborder padding mar-bot">
			<div class="row">
				<div class="col-sm-5">
					<h5 class="text-left">Application No : {{($admitcard->application_no)?$admitcard->application_no :'NA'}}</h5>
					 <p class="text-left">Date : {{date('d-m-Y h:i:s A')}}</p>
				</div>
				<div class="col-sm-7">
					<h5 class="text-right">University / College Applied for </h5> 
					<p class="text-right">{{($admitcard->campus)?$admitcard->campus->name:'NA'}}</p>
				</div>
			</div>
		</div>
		<div class="row">
				<div class="col-sm-9">
					<table class="table table-bordered">
					<thead><tr>
					<td colspan="2"><b>Candidate's Personal Details: </b>
					</td></tr>
					</thead>
					  <tbody>
						<tr>
						  <td><b>Student Name: </b>{{$admitcard->full_name}}</td>
						  <td><b>DOB: </b>{{date(' jS F Y',strtotime($admitcard->date_of_birth))}}</td> 
						</tr>
						<tr>
						<td colspan="2"><b>Roll Number: </b>{{$admitcard->entrance_roll_number}}</td>
						
						  
						</tr>
						<tr>
						  <td><b>Mobile: </b>{{$admitcard->mobile}}</td>
						  <td><b>Email: </b>{{$admitcard->email}}</td>
						  
						</tr>
						<tr>
						  <td><b>Father/Husband Name: </b>{{$admitcard->father_first_name}}</td>
						  <td><b>Mother Name: </b>{{$admitcard->mother_first_name}}</td>
						  
						</tr>
						<tr>
						  <td><b>Category: </b>{{$admitcard->category}}</td>
						  <td><b>State: </b>{{($admitcard->applicationAddress)?$admitcard->applicationAddress->state_union_territory:'NA'}}</td>
						  
						</tr>
						<tr>
						  <td colspan="2"><b>Program Name: </b>{{$admitcard->course->course_description}}</td>
						</tr>
						<tr>
						  <td><b>Program Code: </b>{{$admitcard->course->name}}</td>
						  <td><b>Course Code: </b>{{$entranceExamData->Course->color_code}}</td>
						</tr>
						<tr>
						<td><b>Gender: </b>{{strtoupper($admitcard->gender)}}</td>
						<td>
							<b>Type of Disability: </b>{{($admitcard->disability_category)?$admitcard->disability_category:'NA'}}</td>

						</tr>
						<!-- <tr>
						  <td colspan="2" style="    height: 125px;"><b>Address: </b>{{@$AdmitCard->permanent_address}}</td>
						</tr> -->
					  </tbody>
					</table>
					<table class="table table-bordered">
						<thead><td colspan="2"><b>Examination Center Details:</b></td>
						</thead>
					  <tbody>
						<tr>
						  <td><b>Center Code:</b></td>
						  <td>{{($entranceExamData->centerName)?$entranceExamData->centerName->center_code:'NA'}}</td>
						</tr>
						<tr>
						  <td><b>Center Address:</b></td>
						  <td>
							{{($entranceExamData->centerName)?$entranceExamData->centerName->center_name:'NA'}}
							@if($admitcard->course_id==6)
							<b>(Academic Block A2)</b>
							@endif
						  </td>
						  
						</tr>
					  </tbody>
					</table>
				</div>
				<div class="col-sm-3 txt-center">
					<table class="table table-bordered">
					  <tbody>
						<tr>
						  <th style="text-align: center;"><img src="{{asset($admitcard->photo_url_user)}}" style="height: 200px;width: 170px;" /></th>
						</tr>
						<tr>
						  <th style="text-align: center;"><img src="{{asset($admitcard->signature_url_user)}}" style="height: 50px;width: 170px;"/></th>
						</tr>
						<tr>
						  <th><div class="text-block">Paste Your Recent Coloured Passport Size Photograph Here</div></th>
						</tr>
					  </tbody>
					</table>
				</div>
		</div>
		<div class="row">
				<div class="col-sm-12">
					<table class="table table-bordered">
						<thead><td colspan="2"><b>Examination Details:</b></td>
						</thead>
					  <tbody>
						<tr>
						  <td><b>Examination Date:</b></td>
						  <td> {{date('d-m-Y', strtotime($entranceExamData->entrance_exam_date)) }}</td>
						</tr>
						<tr>
						  <td><b>Reporting Timing:</b></td>
						  {{--<td>{{date('h:i A', strtotime($entranceExamData->reporting_time))}}</td>--}}
						  <td>12:00 Noon</td>
						</tr>
						<tr>
						  <td><b>Examination Timing:</b></td>
						  <td>
							@if($admitcard->course_id==37)
							{{date('h:i A', strtotime($entranceExamData->examination_time))}} - {{date('h:i A', strtotime($entranceExamData->end_time))}}
							<b>(Pencil drawing/object drawing in pencil on paper on given sheet)</b>
							@elseif($admitcard->course_id==8 || $admitcard->course_id==32 || $admitcard->course_id==33)
							Practical : 9:00AM-11:00AM
							<br>
							Viva/Interview : 11:30AM onwards
							@else
							{{date('h:i A', strtotime($entranceExamData->examination_time))}} - {{date('h:i A', strtotime($entranceExamData->end_time))}}
							@endif
						</td>
						</tr>
					  </tbody>
					</table>
				</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-bordered">
					
				  <tbody>
					<tr>
					  <td style="height: 100px;"></td>
					  <td style="height: 100px;"><center><img src="{{asset('signatures\admission-director-2024-25.png')}}" style="max-height:100px;width: auto;"></center></td>
					</tr>
					<tr class="text-center">
					  <td ><b>Candidate`s Signature</b><br>(In the presence of invigilator)</td>
					  <td><b>Sign : Admission Director <br>(Session : 2024-2025)</b></td>
					</tr>
				  </tbody>
				</table>
			</div>
		</div>
		<br/>
		<br/>
		<br/>
		<div class="row print_break">
		<div class="col-md-12">
		<table class="table table-bordered">
		<tbody>
		<tr>
		<td>
		<code>NOTE: </code>
			Candidates having admit card without proper/visible photograph and signature will not be allowed to appear in Entrance Examination {{date('F-Y')}} in any condition.
			Further please check the particulars and other details i.e. Name, Date of Birth, Gender, Category with the Final Confirmation Page. In case any particular(s) of Admit Card is not matching with Final Confirmation Page, the candidate may communicate the same to DSMNRU for necessary correction immediately.
			<p>
			Candidates have to attempt total 100 questions in all in a manner that Q. No. 1-80 are compulsory to all the candidates and 81-100 are from different subjects of Biology/ Psychology/ Statistics/ Mathematics/ Electronics/ Computer Science. 
			<br>The candidate has to attempt only one subject out of the above mentioned six subjects for Q No 81-100.
			<br>The candidates will essentially write the name of the subject like Biology/ Psychology/ Statistics/ Mathematics/ Electronics/ Computer Science on the top of the OMR sheet which they have attempted against Q. No. 81-100, otherwise their evaluation would not be done and result would not be declared.
			</p>
		</td>
		</tr>
		<tr> 
		<td>
		<code>
		<b> IMPORTANT DIRECTIONS FOR CANDIDATES:<br> </code></b>
		
		1. The Candidates should report at the examination centre at least <b>60 minutes before the time of examination</b> mentioned on the admit card. The candidate
		reporting at the centre after gate closure time will not be allowed to appear in the examination.<br>
		2. Candidate should bring their own blue/black ball point pen to write his/her particulars, if any.<br>
		3. Candidate without having proper admit card and photo id proof shall not be allowed in the examination centre under any circumstances by the
		Centre Superintendent.<br>
		4. Candidate shall not be allowed to leave the examination hall before the conducting of examination and signing the attendance sheet.<br>
		5. Candidate must follow the instructions strictly as mentioned in the information bulletin.<br>
		6. This Admit Card is issued provisionally to the candidate as per the information provided by him/her. The eligibility of the candidate has not been
		verified by the University. The University will verify the same before counselling/admission.<br>
		7. The Candidates are advised to visit their allotted examination centre one day before the date of examination in order to confirm its location, distance,
		mode of transport etc.<br>
		8. The candidates suffering from diabetes are allowed to carry into the examination hall, the eatables like sugar tablets/ chocolate/candy, fruits (like
		banana/apple/orange) and Snack items like sandwich in transparent polybag. However, the food items shall be kept with the Invigilators at the
		examination centre concerned, who on their demand, shall hand over the eatables to these candidates.<br>
		9. Paste recent colured passport size photograph on the space provided for the same.<br>
		10. Last entry time: only 15 minutes after the commencement of the exam.
		</td>
		</tr>
		</tbody>
		</table>
		<div  style="text-align: center;">----------------------------------------------------------------------Cut----------------------------------------------------------------------</div><br>
		<div class="row">
				<div class="col-sm-9">
					<table class="table table-bordered">
					<thead><tr>
					<td colspan="2"><b>Candidate's Personal Details: </b>
					</td></tr>
					</thead>
					  <tbody>
						<tr>
						  <td><b>Student Name: </b>{{$admitcard->full_name}}</td>
						  <td><b>DOB: </b>{{date(' jS F Y',strtotime($admitcard->date_of_birth))}}</td> 
						</tr>
						<tr>
						  <td colspan="2"><b>Roll Number: </b>{{$admitcard->entrance_roll_number}}</td>
						  
						  
						</tr>
						<tr>
						  <td><b>Mobile: </b>{{$admitcard->mobile}}</td>
						  <td><b>Email: </b>{{$admitcard->email}}</td>
						  
						</tr>
						<tr>
						  <td><b>Father/Husband Name: </b>{{$admitcard->father_first_name}}</td>
						  <td><b>Mother Name: </b>{{$admitcard->mother_first_name}}</td>
						  
						</tr>
						<tr>
						  <td><b>Category: </b>{{$admitcard->category}}</td>
						  <td><b>State: </b>{{($admitcard->applicationAddress)?$admitcard->applicationAddress->state_union_territory:'NA'}}</td>
						  
						</tr>
						<tr>
						  <td colspan="2"><b>Program Name: </b>{{$admitcard->course->course_description}}</td>
						</tr>
						<tr>
						  <td><b>Program Code: </b>{{$admitcard->course->name}}</td>
						  <td><b>Course Code: </b>{{$entranceExamData->Course->color_code}}</td>
						</tr>
						<tr>
							<td><b>Gender: </b>{{strtoupper($admitcard->gender)}}</td>
							<td>
							<b>Type of Disability: </b>
							{{($admitcard->disability_category)?$admitcard->disability_category:'NA'}}
						  	</td>
						</tr>
					  </tbody>
					</table>
				</div>
				<div class="col-sm-3 txt-center">
					<table class="table table-bordered">
					  <tbody>
						<tr>
						  <th style="text-align: center;"><img src="{{asset($admitcard->photo_url_user)}}" style="height: 200px;width: 170px;" /></th>
						</tr>
						<tr>
						  <th style="text-align: center;"><img src="{{asset($admitcard->signature_url_user)}}" style="height: 50px;width: 170px;"/></th>
						</tr>
						
					  </tbody>
					</table>
				</div>
		</div>
		</div>
		</div>
		</div>
		</div>
</section>
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>

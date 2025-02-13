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
    position: absolute;
    bottom: 80px;
    right: 10px;
    color: black;
    padding-left: 33px;
    padding-right: 20px;
	font-size: 15px;
    font-weight: lighter;
}
@media print{
	.print_break{
		page-break-after: always;
	}
}

.uppercase{
	text-transform: uppercase;
}
.capitalize{
	text-transform: capitalize;
}

	</style>
    <title>Admit Card</title>



</head>
<body>
<p style="text-align: center; margin-top: 0px; cursor: pointer; margin-bottom: 0px;">
		<button onclick="window.print();" class="btn btn-info noPrint"> <img src="{{asset('assets\frontend\images\print.png')}}" style="height: 12px; margin-top: 2px;"> Print</button>
		@if($scribe)
		<!--a href="{{route('phd-entrance-admitcard',['id'=>$AdmitCard->registration_no])}}" class="btn btn-success noPrint">Back</a-->
		@endif
</p> 
<section>
	<div class="container">
		<div class="admitcard">
		<div class="headerdiv divborder padding mar-bot"> 
			<div class="row">
				<div class="col-md-12">
					<p style="text-align: center; margin-bottom: 0px; margin-top: 0px;"><img src="{{asset('assets\frontend\images\cerlogo.png')}}" alt=""></p>
					<h3 class="capitalize" style="text-align: center;  margin-bottom: 0px; margin-top: 10px; line-height: 15px;"> Admit Card [May{{date('-Y')}}]<br><br><br>Writer's/Scribe's Admit Card</h3>
				</div>
				
			</div>
		</div>
		<div class="BoxC divborder padding mar-bot">
			<div class="row">
				<div class="col-sm-6">
					<h5>REGISTRATION No :{{$examfee->students->enrollment_no}}</h5>
				</div>
			</div>
		</div>
		<div class="row">
				<div class="col-sm-10">
					<table class="table table-bordered">
					<thead><tr>
					<td colspan="2"><b>Writer's/Scribe's Details </b>
					</td></tr>
					</thead>
					  <tbody>
						<tr>
						  <th class="capitalize">Name of The Writer/Scribe</th>
						  <td>{{$scribe->name}}</td> 
						</tr>
						<tr>
						  <th class="capitalize">highest educational qualification</th>
						  <td>{{$scribe->qualification}}</td> 
						</tr>
					  </tbody>
					</table>
				</div>
				<div class="col-sm-2 txt-center">
					<table class="table table-bordered">
					  <tbody>
						<tr>
						  <th scope="row txt-center"><img src="{{$scribe->scriber_photo}}" height="100px" /></th>
						</tr>
						<tr>
						  <th scope="row txt-center"><img src="{{$scribe->scriber_signature}}" width="123px" height="30px" /></th>
						</tr>
						
					  </tbody>
					</table>
				</div>
		</div>
		<div class="row">
				<div class="col-sm-10">
					<table class="table table-bordered">
					<thead><tr>
					<td colspan="2"><b>Candidate's Personal Details </b>
					</td></tr>
					</thead>
					  <tbody>
						<tr>
						  <td><b>Student Name: </b>{{$examfee->students->full_name}}</td>
						  <td><b>DOB: </b>{{date(' jS F Y',strtotime($examfee->students->date_of_birth))}}</td> 
						</tr>
						<tr>
						  <td><b>Mobile: </b>{{$examfee->students->mobile}}</td>
						  <td><b>Email: </b>{{$examfee->students->email}}</td>
						  
						</tr>
						<tr>
						  <td><b>Father/Husband Name: </b>{{$examfee->students->father_name}}</td>
						  <td><b>Mother Name: </b>{{$examfee->students->mother_name}}</td>
						  
						</tr>
						<tr>
						  <td><b>Category: </b>{{$examfee->students->category}}</td>
						  <td><b>State: </b>{{$examfee->students->state}}</td>
						  
						</tr>
						<tr>
						  <td><b>Program Name: </b>{{$examfee->course->name}}</td>
						  <td><b>Program Code: </b>{{$examfee->course->color_code}}</td>
						  
						</tr>
						<tr>
						  <td><b>Gender: </b>{{$examfee->students->gender}}</td>
							<td>
							<b>Type of Disability: </b>
							@if($examfee->students->scribe=='yes')
							{{$examfee->students->disabilty_category}} ({{$examfee->students->disabilty_category}})						  
							@else
							{{$examfee->students->disabilty_category}}
							@endif
						  </td>

						</tr>
						<tr>
						  <td colspan="2" style="    height: 125px;"><b>Address: </b>{{$examfee->students->address}}</td>
						</tr>
					  </tbody>
					</table>
				</div>
				
		</div>
		<div class="row">
				<div class="col-sm-12">
				<table class="table table-bordered">
						<thead><td colspan="2"><b>Examination Center Details:</b></td>
						</thead>
					  <tbody>
						<tr>
						  <td><b>Center Code:</b></td>
						  <td>{{($examfee->admitcard->center)?$examfee->admitcard->center->center_code:'NA'}}</td>
						</tr>
						<tr>
						  <td><b>Center Address:</b></td>
						  <td>{{($examfee->admitcard->center)?$examfee->admitcard->center->center_name:'NA'}}</td>
						  
						</tr>
					  </tbody>
					</table>
				</div>
		</div>
		<div class="row">
				<div class="col-sm-12">
					<table style="font-size: 12px; width: 100%; text-align: center;" cellspacing="0" cellpadding="0"><thead><td colspan="2"><b>Examination Details:</b></td>
        <tr>
            <td style="border: black thin solid; border-right: none;"> <strong>S No.</strong></td>
            <td style="border: black thin solid; border-right: none;">  <strong>Date</strong> </td>
            <td style="border: black thin solid; border-right: none;">  <strong>Shift</strong> </td>
            <td style="border: black thin solid; border-right: none;">  <strong>Paper Code</strong> </td>
            <td colspan="3" style="border: black thin solid;"> <strong>Paper Name</strong></td>
			@php $key = 0; @endphp
        </tr>@foreach($subjects as $subject)
        <tr>
            <td style="border: black thin solid;  border-right: none; border-top: none;">{{++$key}}</td>
            @if($subject->date)
            <td style="border: black thin solid;  border-right: none; border-top: none;">{{date('d-n-Y',strtotime($subject->date))}}</td>
            @else
            <td style="border: black thin solid;  border-right: none; border-top: none;">-</td>
            @endif
            @if($subject->shift)
            <td style="border: black thin solid;  border-right: none; border-top: none;">{{ucwords($subject->shift)}}</td>
            @else
            <td style="border: black thin solid;  border-right: none; border-top: none;">-</td>
            @endif
            <td style="border: black thin solid;  border-right: none; border-top: none;">{{$subject->sub_code}}</td>
            <td colspan="3" style="border: black thin solid;  border-top: none;">{{$subject->name}}</td>
        </tr>@endforeach
        
    </table>
				</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-bordered">
					
				  <tbody>
					<tr>
					  <td style="height: 100px;"></td>
					  <td style="height: 100px;"><center><img src="{{asset('signatures\coe.png')}}" style="max-width:80px;transform: rotate(-27deg);"></center></td>
					</tr>
					<tr class="text-center">
					  <td ><b>Candidate Signature</b></td>
					  <td><b>Examination Controller </b></td>
					</tr>
				  </tbody>
				</table>
			</div>
		</div>
		<div class="row  print_break">
		<div class="col-md-12">
		<table class="table table-bordered">
		<tbody>
		<tr>
		<td>
		<code>NOTE:
		</code> Candidates having admit card without proper/visible photograph and signature will not be allowed to appear in Ph.D Entrance {{date('F-Y')}} in any condition.
		Further please check the particulars and other details i.e. Name, Date of Birth, Gender, Category with the Final Confirmation Page. In case any particular(s) of Admit Card is not matching with Final Confirmation Page, the candidate may communicate the same to DSMNRU for necessary correction immediately.</td>
		</tr>
		<tr> 
		<td>
		<code>
		<b> IMPORTANT DIRECTIONS FOR CANDIDATES:<br> </code></b>
		<br>
		1. The Candidates should report at the examination centre at least <b>30 minutes before the reporting time</b> mentioned on the admit card. The candidate
		reporting at the centre after gate closure time will not be allowed to appear in the examination.<br>
		2. Candidate should bring their own blue/black ball point pen to write his/her particulars, if any.<br>
		3. Candidate without having proper admit card and photo id proof shall not be allowed in the examination centre under any circumstances by the
		Centre Superintendent.<br>
		4. Candidate shall not be allowed to leave the examination hall before the conclusion of examination, without signing the attendance sheet.<br>
		5. Candidate must follow the instructions strictly as mentioned in the information bulletin.<br>
		6. This Admit Card is issued provisionally to the candidate as per the information provided by him/her. The eligibility of the candidate has not been
		verified by the Board. The appointing authority/recruiting agency will verify the same before appointment/recruitment. Qualifying the DSMNRU would
		not confer a right on any person for recruitment/employment as it is only one of the eligibility criteria for appointment as the teacher.<br>
		7. The Candidates are advised to visit their allotted examination centre one day before the date of examination in order to confirm its location, distance,
		mode of transport etc.<br>
		8. The candidates suffering from diabetes are allowed to carry into the examination hall, the eatables like sugar tablets/ chocolate/candy, fruits (like
		banana/apple/orange) and Snack items like sandwich in transparent polybag. However, the food items shall be kept with the Invigilators at the
		examination centre concerned, who on their demand, shall hand over the eatables to these candidates.
		</td>
		</tr>
		</tbody>
		</table>
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

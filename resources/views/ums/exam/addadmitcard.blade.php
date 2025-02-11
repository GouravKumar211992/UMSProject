<<<<<<< HEAD
@extends('ums.admin.admin-meta')

@section('content')
@section('styles')
<style type="text/css">
    .viewapplication-form p {
        color: #6c757d;
        font-weight: bold;
        border: #eee thin solid;
        min-height: 40px;
        border-radius: 4px;
        padding: 6px;
        background: #f9f9f9;
=======
<!DOCTYPE html>
@extends('student.layouts.app1')
@section('content')

<style>
@media print {
    .hidden_print{
		display:none;
	}
}
 .page {
        width: 210mm;
        min-height: 297mm;
        /*padding: 20mm;*/
        margin: 10mm 0;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
    }
	@page  
{ 
    size: auto;   /* auto is the initial value */ 

    /* this affects the margin in the printer settings */ 
    margin: 0; 
		padding:0;
} 
</style>
<<<<<<< HEAD
@endsection
<div class="app-content content ">
    <div class="content-overlay"></div>
    @include('ums.admin.notifications')
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Category</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>  
                                <li class="breadcrumb-item active">Showing 1 to 10 of 2 category</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right"> 
                        <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="{{ url('category_list_add') }}"><i data-feather="file-text"></i> Add Category </a> 
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 

                        <button class="btn btn-warning box-shadow-2 btn-sm  mb-sm-0 mb-50" onClick="window.location.reload()"><i data-feather="refresh-cw"></i>Reset</button>

                        <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                         
                </div>
            </div>
        </div>
@include('ums.admin.notifications')

       
=======


 @include('student.partials.notifications')
<center class="page" style="margin: 15px auto;">
@if($form_data->is_agree==1)
	<button type="button" onclick="print()" class="btn btn-warning hidden_print"/>
		<i class="fa fa-print" aria-hidden="true"></i> Print Application
	</button>
    <a  href="{{url('student/exam/pay-success')}}?id={{$form_data->id}}" class="btn btn-info hidden_print"/>
        <i class="fa fa-print" aria-hidden="true"></i> Payment Slip
    </a>
    <a href="{{url('student/exam-form')}}" class="btn btn-warning hidden_print"/>
        <i class="" aria-hidden="true"></i> Go To Dashboard
    </a>
	@endif
	@php $admitcard= $form_data->admitcard; @endphp
	@if($admitcard)
	<a type="button" href="{{route('download-admit-card',['id'=>$form_data->id])}}"  class="btn btn-warning hidden_print">
		<i class="fa fa-download" aria-hidden="true"></i>Download Admit Card
	</a>
        @if($form_data->scribe=='yes' || $form_data->scribe=='Yes')
            @if($admitcard->scribeDetails)
                <a type="button" href="{{route('scribe-admitcard',['id'=>$form_data->id])}}"  class="btn btn-warning hidden_print">
                    <i class="fa fa-download" aria-hidden="true"></i>Download Scribe Admit Card
                </a>
            @else
                <a type="button" href="{{route('scribe-form',$form_data->id)}}"  class="btn btn-warning hidden_print">
                    <i class="fa fa-pencil" aria-hidden="true"></i>Fill Scribe Form 
                </a>	
            @endif
        @endif
	@endif
	@php 
function like($str, $searchTerm) {
    $searchTerm = strtolower($searchTerm);
    $str = strtolower($str);
    $pos = strpos($str, $searchTerm);
    if ($pos === false)
        return false;
    else
        return true;
}
$affiliate=0;
$found = like($form_data->enrollment_no, 'SA'); //returns true
$found1 = like($form_data->enrollment_no, 'DSMNRU21A'); //returns true

if($found || $found1){
    $affiliate=1;
}
$affiliate=1;
@endphp
	@if($form_data->bank_name==null)
		@if( $affiliate==1 || $form_data->course->campus_id == 2)
			<a type="button" href="{{route('examination-fee',['exam_fee_id'=>$form_data->id])}}"  class="btn btn-warning hidden_print">
			<i class="fa fa-pencil" aria-hidden="true"></i> Fill Fees Details</a>
		@endif
	@endif
    <div style="border: black thin solid; width:700px; padding: 10px; font-family:Arial;">

        <p style="text-align: center; margin-bottom: 5px;"><img src="{{asset('assets\frontend\images\cerlogo.png')}}"></p>
   <form id="examination_form" method="POST">
    <table style="font-size: 11px; width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="4" style="border: black thin solid; padding: 5px; border-bottom: none;">
                <strong>
                    ONLINE EXAM FORM SUBMISSION FOR SEMESTER END/YEARLY PROFESSIONAL EXAMINATION, {{$form_data->current_exam_session}}
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding-left: 3px;  border-right: none;"><strong>NAME OF THE STUDENT</strong></td>
            <td style="border: black thin solid; padding-left: 3px;  border-right: none;">{{$student->full_name}}</td>
            <td rowspan="3" style="border: black thin solid; padding-left: 5px; padding-top: 2px; vertical-align: top;">
                @if($student && $student->photo)
                    <img style="height:100px; width: 100px;" src="{{$student->photo}}" alt="">
                    @elseif($form_data && $form_data->photo)
                    <img style="height:100px; width: 100px;" src="{{@$form_data->photo}}" alt="">
                @endif
        
            </td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;"><strong>FATHER'S NAME</strong></td>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;">{{$student->father_first_name}}</td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;"><strong>MOTHER'S NAME</strong></td>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;">{{$student->mother_first_name}}</td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;"><strong>ROLL NO.</strong></td>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;">{{$form_data->roll_no}}</td>
            <td style="border: black thin solid; padding-left: 3px; border-top: none;">
                @if($student && $student->signature)
                    <img style="height:50px; width: 100px;" src="{{$student->signature}}" alt="">
                    @elseif($form_data && $form_data->signature)
                    <img style="height:50px; width: 100px;" src="{{@$form_data->signature}}" alt="">
                @endif
            </td>
        </tr>        
    </table>


    <!-- <p style="font-weight: bold; font-size: 12px;">LIST OF CURRENT SEMESTER/PROF./YEAR THEORY AND PRACTICAL PAPER</p> -->
    <table style="font-size: 11px; width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong> ENROLLMENT NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;">{{$form_data->enrollment_no}}</td>
            <td style="border: black thin solid; padding: 3px;border-right: none; border-top: none;"><strong>DATE OF BIRTH</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">@php
			$dob = $student->date_of_birth; @endphp
			{{date('M dS, Y', strtotime($dob))}}</td>
        </tr>
        <tr> 
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>MOBILE NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">{{$student->mobile}}</td>
			<td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>E-MAIL ID</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$student->email}}</td>
            
        </tr>
        <tr>
		{{--<!--td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>ALTERNATE MOBILE NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;"></td-->
            <!--td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>ALTERNATE E-MAIL ID</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$form_data->formdataone->alternate_email_id}}</td-->--}}
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px;border-top: none; border-right: none;"><strong>AADHAR CARD NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">{{strval($student->aadhar)}}</td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>PIN CODE</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$student->pin_code}}</td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>CATEGORY</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">{{$student->category}}</td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>Disabilty Category</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$student->disabilty_category}}</td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>GENDER</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">{{$student->gender}}</td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>SESSION</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$form_data->academic_session}}</td>
        </tr>
       
    </table>

    <table  style="font-size: 11px; width: 100%;  text-align: left;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>PERMANENT ADDRESS</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$student->address}}</td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>CORRESPONDENCE ADDRESS</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$student->address}}</td>
        </tr>
        
     </table> 
     
     <table  style="font-size: 11px; width: 100%;  text-align: left;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>COURSE</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;">{{$form_data->course->name}}</td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>BRANCH/STREAM</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">
            @if($enrollment->stream)
            {{$enrollment->stream->name}}
            @else
            {{$form_data->course->name}}
            @endif
            </td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>VACCINATED</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{ucfirst($form_data->vaccinated)}}</td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>BATCH</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{batchFunction($form_data->roll_no)}}</td>
        </tr>
        
     </table> 
	@if($form_data->bank_name!=null)
     <table style="font-size: 11px;  width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="4" style="border: black thin solid; padding: 5px; text-align: center; border-top: none;"><strong>CANDIDATE EXAM FEE DETAIL</strong></td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>BANK NAME</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;">{{$form_data->bank_name}}</td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>BANK BRANCH NAME</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;"></td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>CHALLAN/RTGS/NEFT NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;">{{$form_data->receipt_number}}</td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>CHALLAN RECEIPT DATE</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$form_data->receipt_date}}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>CHALLAN/RTGS/NEFT AMOUNT</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;">{{$form_data->fee_amount}}</td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>BANK IFSC CODE</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$form_data->bank_IFSC_code}}</td>
        </tr>
    </table>
	@endif
    <table style="font-size: 11px;  width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>SEMESTER/PROF./YEAR</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$form_data->semesters->name}}</td>
        </tr>
        <tr>@php
		$roll_number_format=$student->roll_no;
		$collage_code=substr($roll_number_format,2,3);
		$collage=App\Models\Campuse::where('campus_code',$collage_code)->first();
		@endphp
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>UNIVERSITY/AFFILIATED COLLAGE NAME</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">@if($collage){{strtoupper($collage->name) }} <b>{{ strtoupper($collage->address)}}@else  {{$form_data->semesters->course->campuse->name}} @endif</b></td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>DISABILITY CATEGORY</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{ucfirst($student ? $student->disabilty_category : '-')}}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>WRITER/SCRIBE REQUIRED</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{ucfirst($form_data->scribe)}}</td>
         
        </tr>
        
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>Religion</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{(isset($form_data->students))?$form_data->students->religion:''}}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>Cast Category</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{(isset($form_data->students))?$form_data->students->category:''}}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>EWS Status</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{(isset($form_data->students))?$form_data->students->ews_status:''}}</td>
        </tr>
    </table>
	@if($form_data->bank_name!=null)
    <table style="font-size: 11px;  width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>CHALLAN/RTGS/NEFT <BR> AMOUNT</strong></td>
            <td style="border: black thin solid; padding: 3px; vertical-align: bottom; border-right: none; border-top: none;"><img src="{{$form_data->challan}}" style="height:60px; width: 100px;" alt="">
                <a target="_blank" href="{{$form_data->challan}}">View Doc</a>
            </td>
           <!--  <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;">
                <strong>DISABILITY <BR>CERTIFICATE</strong>
            </td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">&nbsp;</td> -->
        </tr>
        
    </table>
@endif
    <table style="font-size: 11px; width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="4" style="border: black thin solid; padding: 5px; border-bottom: none; text-align: center; border-top: none;"><strong> LIST OF CURRENT SEMESTER/PROF./YEAR THEORY AND PRACTICAL PAPER</strong></td>
        </tr>
        <tr>
            <th style="border: black thin solid; padding: 3px; text-align: left; border-right: none; ">SL.</th>
            <th style="border: black thin solid; padding: 3px; text-align: left; border-right: none;">PAPER CODE</th>
            <th style="border: black thin solid; padding: 3px; text-align: left;">PAPER NAME</th>
            <th style="border: black thin solid; padding: 3px; text-align: left;">PAPER TYPE</th>
            <th style="border: black thin solid; padding: 3px; text-align: left;">REQUIRED/OPTIONAL</th>
			
        </tr>
		@foreach($subjectList as $key=>$value)
        <tr>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">{{$key+1}}</td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">{{$value->sub_code}}</td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$value->name}}</td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{strtoupper($value->subject_type)}}</td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{strtoupper($value->type)}}</td>
        </tr>
        @endforeach
    </table>
    <table  style="font-size: 11px; width: 100%; text-align: left;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: black thin solid; padding: 3px; width: 50%; border-right: none; border-top: none;" >
            @if($form_data->signature)
            <img src="{{$form_data->signature}}" style="height:30px; width: 100px;" alt="">
            @else
                @if($student)
                <img src="{{$student->signature}}" style="height:30px; width: 100px;" alt="">
                @else
                    <img src="{{$form_data->signature}}" style="height:30px; width: 100px;" alt="">
                @endif
            @endif
            <br>
                Signature/Thumb impression of the student
            </td>
            <td style="vertical-align: bottom; margin-top: 0; border: black thin solid; padding: 7px; width: 50%; border-top: none;"><br>
                Signature of the HoD/Dean/Principal
            </td>
        </tr>
        <tr>
            <td colspan="4"  style="border: black thin solid; border-top: none; text-align: justify; padding: 5px;"> 
				<ol><li><input type="checkbox" checked disabled readonly id="check" value="1" name="agree" @if($form_data->is_agree==1)checked disabled @endif >
				I hereby declare that I am
                aware of the examination rules of the University. I also affirm that i have appeared in all in the mid semester test, presentation and submitted
                assingnmentas applicable for the course filled in the online examination form. My registration for the course is valid and not time barred.
                If any of my statement is found to be untrue, i shall have no claim for taking examination and my examination result may be withheld/cancelled 
                at any stage. I undertake that i shall abide by the rules and regulations of the University.</li>
				<li>Please Submit a Attested Copy of Application Form along With Payment Details To Your Department.</li>@if ($errors->has('name'))<span class="text-danger"><li>{{ $errors->first('agree') }}</li></span>
				@endif</td> 
        </tr>
		<tr></tr>
     </table>  @if($form_data->is_agree==null) 

	@csrf
	<input type="text" name="rollno" class="form-control" value="{{$form_data->roll_no}}" hidden>
	<input type="text" name="course" class="form-control" value="{{$form_data->course_id}}" hidden>
	<input type="text" name="exam_form" class="form-control" value="{{Request()->exam_form}}" hidden>
	@if(Request()->exam_form=='compartment')
	<input type="text" name="subjects" class="form-control" value="{{(Request()->subjects)?Request()->subjects:$form_data->subject}}" hidden>@endif
	<a href="{{url('student/exam-form')}}" class="btn btn-secondary btn-back">Go Back</a> 
	<!-- <button type="submit" class="btn btn-warning"/>
						<i class="fa fa-send" aria-hidden="true"></i> Submit
					</button> -->
	
	</form>
	@endif
	
>>>>>>> 91bb0d65e1d166ca92c32f6a1e6b35c4f00d5d88
    </div>
	
	
</center>
@endsection
<script>
    $(document).ready(function() {
        alert('sdfdsfs');
         function disablePrev() { window.history.forward() }
         window.onload = disablePrev();
         window.onpageshow = function(evt) { if (evt.persisted) disableBack() }
      });
</script>
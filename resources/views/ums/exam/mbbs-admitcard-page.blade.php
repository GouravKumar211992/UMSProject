@if(Request()->segment(1)=='admin')
@php $layout_check = 'ums.admin.layouts.app'; @endphp
@php $layout_check = 'ums.student.layouts.app1'; @endphp
@elseif(Request()->segment(1)=='student')
@php $layout_check = 'ums.student.layouts.app1'; @endphp
@else
@php $layout_check = 'ums.student.layouts.app1'; @endphp
@endif

@extends($layout_check)

{{-- Web site Title --}} 
@section('title') Admit Card Download :: @parent @stop 
@section('content')

@section('styles')
<style type="text/css">
@media print {
	strong,p,td,th,h5,li,b,code{
		font-size:18px;
	}
	 .break_print{page-break-after: always;}
	 * {
    -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
    color-adjust: exact !important;                 /*Firefox*/
}
}
.admit-card {border: 2px solid #000;
				padding: 15px;
				margin: 20px 0;}
	body {background: #ffff !important;}
	div.heading {justify-content: space-between;
				margin-left: 25%;}
.viewapplication-form p {
    color: #939393;
    font-weight: bold;
    border: #eee thin solid;
    min-height: 40px;
    border-radius: 4px;
    padding: 6px;
    background: #fefefe;
}
.pae_conainter{
    border: black thin solid;
    width:700px;
    padding: 10px;
    font-family:Arial;
}
.candidate_photograph{
	width: 120px;
    height: 130px;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: top;
}
.candidate_signature{
	width: 120px;
    height: 50px;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
}
.headingfontsize{
    font-size: 14px;
}
</style>
@endsection
<!-- {{dd($AdmitCard)}} -->
@if($AdmitCard)

@php
if($examfee->enrollment){
	$icard = $examfee->enrollment->icard;
    if(!$icard){
        $icard = \App\Models\ums\Student::where('roll_number',$examfee->roll_no)->first();
    }
}else{
	$icard = \App\Models\ums\MbbsExamForm::where('rollno',$examfee->roll_no)->first();
}
@endphp
<center>
<button onclick="window.print();" class="" style="padding: 10px 15px ;background:green; border:none; margin:5px; color:white;"> Print</button>
</center>
<center>
<div class="pae_conainter">

        <p style="text-align: center; margin-bottom: 0px; margin-top: 0px;"><img src="{{asset('img\cerlogo.png')}}" alt=""></p>
        <p class="headingfontsize"><b>{{$icard->program}} Supplementry / Regular Examination {{$AdmitCard->current_exam_session}}</b></p>
        <h3 style="text-align: center;  margin-bottom: 0px; margin-top: 0px; line-height: 5px;">ADMIT CARD [{{strtoupper($AdmitCard->current_exam_session)}}]</h3>

        <table style="font-size: 12px; width: 100%; margin-top: 20px;" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table style="font-size: 12px; width: 100%;" cellspacing="0" cellpadding="0">
                        <tr>
                                <td>
                                    <p style="margin-top: 0;"> <strong>1. Roll No:</strong></p> 
                                </td>
            
                                <td style=" vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{$examfee->roll_no}}</p>
                                </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-top: 0;"> <strong>2. Enrollment No. :</strong> </p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                            <p style="border-bottom: black thin solid; margin-top: 0;">{{$examfee->enrollment_no}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-top: 0;"> <strong>3. Institute Name. :</strong> </p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">({{$examfee->course->campuse->campus_code}}) {{ $examfee->course->campuse->name }}</p>
                            </td>
                        </tr>
            
                        <tr>
                            <td>
                                <p style="margin-top: 0;"> <strong>4. Name of the Student :</strong> </p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                            <p style="border-bottom: black thin solid; margin-top: 0; text-transform: uppercase;">{{$examfee->students ? $examfee->students->name : ''}}</p>
                            </td>
                        </tr>
            
                        <tr>
                            <td >
                                <p style="margin-top: 0px;"> <Strong>5. Father’s Name :</Strong></p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{$examfee->students ? $examfee->students->father_name : ''}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <p style="margin-top: 0px;"> <Strong>6. Mother’s Name :</Strong></p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{$examfee->students ? $examfee->students->mother_name : ''}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <p style="margin-top: 0px;"> <Strong>7. Course :</Strong></p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{$examfee->course->name}}</p>
                            </td>
                        </tr>            
                        <tr>
                            <td >
                                <p style="margin-top: 0px;"> <Strong>8. Branch/Stream :</Strong></p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{$examfee->course->stream->name}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <p style="margin-top: 0px;"> <Strong>9. Sem/Prof :</Strong></p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{$examfee->semesters->name}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <p style="margin-top: 0px;"> <Strong>10.Category</Strong></p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{$student_details->category}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <p style="margin-top: 0px;"> <Strong>11. Category of Disability :<br>
                                    (if any)  </Strong></p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{$icard->disability_category}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <p style="margin-top: 0px;"> <Strong>12. Exam Center :</Strong></p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{$AdmitCard->center->center_name}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <p style="margin-top: 0px;"> <Strong>13. Writer/Scribe Required</Strong></p>
                            </td>
                            <td style="vertical-align: bottom; margin-top: 0;">
                                <p style="border-bottom: black thin solid; margin-top: 0;">{{ucfirst($examfee->scribe)}}</p>
                            </td>
                        </tr>
                       
                    </table>
                </td>
                <td style="vertical-align: top;">
                <table style="width: 50%; font-size: 12px; text-align: center; padding-left: 20px;float:right; margin-left:20px" cellspacing="0" cellpadding="0" border="2">
                    <tr>
                        <td>
						<div class="candidate_photograph"
                        @if($student_details)
							style="background-image:url({{$student_details->photo}})"
						@endif
						>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; border-top-width: 2px;">
						<div class="candidate_signature"
                        @if($student_details)
						style="background-image:url({{$student_details->signature}})"
                        @endif
						>
                        </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="font-size: 12px; width: 100%; text-align: center;" cellspacing="0" cellpadding="0">
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
            <td style="border: black thin solid;  border-right: none; border-top: none;">As per the exam schedule</td>
            @endif
            @if($subject->shift)
            <td style="border: black thin solid;  border-right: none; border-top: none;">{{ucwords($subject->shift)}}</td>
            @else
            <td style="border: black thin solid;  border-right: none; border-top: none;">As per the exam schedule</td>
            @endif
            <td style="border: black thin solid;  border-right: none; border-top: none;">{{$subject->sub_code}}
				{{--
			@if($subject->subject_type=='Practical') (P) @endif
			@if($subject->subject_type=='Theory') (T) @endif
			--}}
			</td>
            <td style="border: black thin solid;  border-top: none;">{{$subject->name}}</td>
        </tr>@endforeach
        
    </table>

 </table>

        <div class="col-4 mt-3 break_print" border="2px">
					
				</div>
				<section class="col-md-12 connectedSortable"> 
				   <div class="row">
					   <table width="100%" border="1" style="border:transparent;">
                       <tr> <th><h5 class="text-center">Exam Instruction</h5></th></tr>
                       <tr> <th> IMPORTANT INSTRUCTIONS:</th></tr>
					 <tr><td><ol style="padding-left: 20px;"><li>
Candidates are required to produce this Admit Card with the Photograph pasted along with Identity Card there in as proof of their identity for appearing in examination and entry to the exam hall will be prohibited in absence of the admit card.
</li>
<li>Fill all entries carefully in the Answer Booklet. Candidates must follow all instructions of the Controller of Examination/Supervisor/Invigilator. Any candidate who does not follow such instructions, their examination may be invalidated. Non-compliance with the direction may also result in suspension from all examinations.
</li>
<li>
Electronic and communication on devices like mobile Phone, Textual Material, Smart Watches etc, weapon, smoking, tobacco, gutkha and Bag/Baggage are strictly prohibited in the examination premises. The university will not own the responsibility, if any item is stolen /lost etc. Estables items and water bottles are not allowed inside examination premises.
</li>
<li>
Candidates are to be seated in their respective seat at least 2 minutes prior to scheduled start me of the examination. The entry to the Exam Hall will be permitted maximum up to after 15 minutes of the commencement of the examination.
</li>
<li>
Candidates must not communicate with another candidates or anyone else other than supervisory staff during the examination. If there is need to raise a point of immediate urgency, the candidate is required to raise their hand to draw the invigilator’s attention. The candidate may explain the matter in a quiet and non-disruptive manner.
</li>
<li>
Abusive behaviour in exam centre will not be tolerated. Any candidate in beach of this will be asked to leave the exam room immediately and will forfeit all exam fees paid.
</li>
<li>
All examination material such as Answer Sheet, Rough Work Sheet etc. musti be returned to the invigilator on complaint of the examination.
</li>
<li>
Eligible Scribes/Writers assisting the Visually Impaired (VI) and other than visually Impaired (VI) must bring original valid identity proof.
</li>
<li>
Candidates must not write or change any entry made in the Admit Card after it has been printed.
</li>
<li>
If any candidate is found guilty of beach of any rules mentioned for the examination or guilty of using unfair means, he/she will be liable to be punished by the competent authority of the university.
</li>
<li>
For date and time of exam, see the Examination schedule separately uploaded on University website.
</li>

</ol>
<br/>
<b><code>Note : </code> Evening (Timing-01:00 p.m.-04:00 p.m.)</b>
</td></tr>
<tfoot>

    <tr>
        <td class="text-right">
        <img src="{{asset('signatures\coe.png')}}" style="max-width:80px;">
      </td>
    </tr>
    <tr>
        <td>
        <p style="text-align: right; font-weight: bold; font-size: 11px; margin-bottom: 0px;">Controller of Examination</p>
      </td>
    </tr>
  </tfoot>

					   </table>
					</div>
				</section>
         

    </div>
	</center>

<style>
@media print {
  .noPrint{
    display:none;
  }
    .break_print{
      /*  page-break-after: always;*/
    }
    .pae_conainter{
        width:100%;
    }
}

 </style>


@endif



@endsection

@section('styles')
    <style type="text/css"></style>
@endsection

@section('scripts')

@endsection
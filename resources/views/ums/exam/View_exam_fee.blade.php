<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        @media print{
            .print_hidden{
                display:none;
            }
        }
    </style>
</head>
<center>

      <span class="btn btn-success"><a style="color: white;text-decoration:none;" href="{{route('edit-exam-form',$exam_fee->id)}}"><i class="iconly-boldCheck"></i> Edit</a></span>
<body>
<br/>
    <a class="btn btn-success print_hidden" href="javascript:window.open('','_self').close();">Back</a>
    <button class="btn btn-info print_hidden" onclick="return window.print();">Print</button>
    <br/>
    <br/>
    <div style="border: black thin solid; width:700px; padding: 10px; font-family:Arial;">

        <P style="text-align: center; margin-bottom: 5px;"><img src="{{asset('images/cerlogo.png')}}"></P>
   @if($data)
    <table style="font-size: 11px; width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="4" style="border: black thin solid; padding: 5px; border-bottom: none;"><strong>ONLINE EXAM FORM SUBMISSION FOR SEMESTER END/YEARLY
                PROFESSIONAL EXAMINATION, [{{--current_session()--}}JUNE-2022]
            </strong></td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding-left: 3px;  border-right: none;"><strong>NAME OF THE STUDENT</strong></td>
            <td style="border: black thin solid; padding-left: 3px;  border-right: none;">@if($student){{$student->full_name}} @endif</td>         
                 <td rowspan="4" style="border: black thin solid; padding-left: 5px; padding-top: 2px; vertical-align: top;">
                 @if($student)<img src="{{$student->photo_url}}" style="height:100px; width: 100px;" alt="">@elseif($id)<img src="{{$id->photo}}" style="height:100px; width: 100px;" alt="">@endif </td> 
        </tr>
        <tr>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;"><strong>ROLL NO.</strong></td>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;">{{$student->roll_number}}</td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;"><strong>FATHER'S NAME</strong></td>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;">@if($student){{$student->father_first_name}} @endif </td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;"><strong>MOTHER'S NAME</strong></td>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;">@if($student){{$student->mother_first_name}} @endif  </td>
        </tr>
        <tr>
             <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;"><strong>HINDI NAME</strong></td>
            <td style="border: black thin solid; padding-left: 3px; border-top: none; border-right: none;">@if($student){{$student->hindi_name}} @endif </td>
            <td style="border: black thin solid; padding-left: 3px; border-top: none;">
            @if($student)<img src="{{$student->signature_url}}" style="height:30px; width: 100px;" alt="">@endif
			</td>
        </tr>        
    </table>


    <!-- <p style="font-weight: bold; font-size: 12px;">LIST OF CURRENT SEMESTER/PROF./YEAR THEORY AND PRACTICAL PAPER</p> -->
    <table style="font-size: 11px; width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong> ENROLLMENT NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"></td>
            <td style="border: black thin solid; padding: 3px;border-right: none; border-top: none;"><strong>DATE OF BIRTH</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;"></td>
        </tr>
        <tr> 
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>MOBILE NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">@if($student) {{$student->mobile}}  @else{{$data['mbbs']->mobile}} @endif </td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>ALTERNATE MOBILE NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">--</td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>E-MAIL ID</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">@if($student) {{$student->email}}  @else{{$data['mbbs']->email}} @endif </td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>ALTERNATE E-MAIL ID</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">
                @if($student)   @else{{$data['mbbs']->alternate_email_id}} @endif
            </td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px;border-top: none; border-right: none;"><strong>AADHAR CARD NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">{{$student->aadhar}} </td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>PIN CODE</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">
            </td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>CATEGORY</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"> @if($student) {{$student->category}} @else{{$data['mbbs']->category}} @endif</td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>Disabilty Category</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">
                @if($student)  {{$student->disabilty_category}} @else  @endif
            </td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>GENDER</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"> @if($student) {{$student->gender}}@else{{$data['mbbs']->gender}} @endif</td>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;"></td>
        </tr>
       
    </table>

    <table  style="font-size: 11px; width: 100%;  text-align: left;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>PERMANENT ADDRESS</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$student->address}}</td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;"><strong>CORRESPONDENCE ADDRESS</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;"> </td>
        </tr>
        
     </table> 
     
     <table  style="font-size: 11px; width: 100%;  text-align: left;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>COURSE</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;">{{$exam_fee->course->name}}</td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>BRANCH/STREAM</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$exam_fee->course->stream->name}}</td>
        </tr>
        <tr>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>VACCINATED</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;"> {{$exam_fee->vaccinated}}</td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>BATCH</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"></td>
        </tr>
        
     </table> 
     @if($exam_fee->bank_name)
     <table style="font-size: 11px;  width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="4" style="border: black thin solid; padding: 5px; text-align: center; border-top: none;"><strong>CANDIDATE EXAM FEE DETAIL</strong></td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>BANK NAME</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;">
                {{$exam_fee->bank_name}}
            </td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>IFSC CODE</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$exam_fee->bank_IFSC_code}}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>CHALLAN/RTGS/NEFT NO.</strong></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;">{{$exam_fee->receipt_number}}</td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>CHALLAN RECEIPT DATE</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$exam_fee->receipt_date}}</td>
        </tr>
        <tr>
           <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>FEE AMOUNT</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$exam_fee->fee_amount}}</td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong></strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;"></td>
        </tr>
    </table>
    @endif
	
    <table style="font-size: 11px;  width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>SEMESTER/PROF./YEAR</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$exam_fee->semesters->name}}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>UNIVERSITY/AFFILIATED COLLAGE NAME</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$exam_fee->course->campuse->name}}</td>
        </tr>
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>WRITER/SCRIBE REQUIRED</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">{{$exam_fee->scribe}}</td>
        </tr>
        <tr>
        
    </table>
    <table style="font-size: 11px;  width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="1" style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>CHALLAN/RTGS/NEFT <BR> AMOUNT</strong></td>
            <td style="border: black thin solid; padding: 3px; vertical-align: bottom; border-right: none; border-top: none;"><img src="@if($student){{$student->challan}}@else {{$id->challan}} @endif" style="height:60px; width: 100px;" alt=""><a target="_blank" href="@if($student){{$student->challan}}@else {{$id->challan}} @endif">View Doc</a></td>
            <td style="border: black thin solid; padding: 3px; border-right: none; border-top: none;"><strong>DISABILITY <BR>CERTIFICATE</strong></td>
            <td style="border: black thin solid; padding: 3px; border-top: none;"><img src="@if($student){{$student->disability_certificate_url}}@else {{$id->disability_certificate_url}} @endif" style="height:60px; width: 100px;" alt=""></td>
        </tr>
        
    </table>
    <table style="font-size: 11px; width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="4" style="border: black thin solid; padding: 5px; border-bottom: none; text-align: center; border-top: none;"><strong> LIST OF CURRENT SEMESTER/PROF./YEAR THEORY AND PRACTICAL PAPER</strong></td>
        </tr>
        <tr>
            <th style="border: black thin solid; padding: 3px; text-align: left; border-right: none; ">SL.</th>
            <th style="border: black thin solid; padding: 3px; text-align: left; border-right: none;">PAPER CODE</th>
            <th style="border: black thin solid; padding: 3px; text-align: left;">PAPER NAME</th>
        </tr>
        @foreach($data['subjects'] as $key=> $subject)
        <tr> 
            
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">{{$key+1}}</td>
          
            <td style="border: black thin solid; padding: 3px; border-top: none; border-right: none;">{{$subject['sub_code']}}</td>
            <td style="border: black thin solid; padding: 3px; border-top: none;">
            {{$subject['name']}}</td>
        </tr>
        @endforeach

       
    </table>
    <table  style="font-size: 11px; width: 100%; text-align: left;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: black thin solid; padding: 3px; width: 50%; border-right: none; border-top: none;" ><img src="" style="height:30px; width: 100px;" alt=""><img src="{{$student->signature_url}}" style="height:30px; width: 100px;" alt=""><br>
                Signature/Thumb impression of the student
            </td>
            <td style="vertical-align: bottom; margin-top: 0; border: black thin solid; padding: 7px; width: 50%; border-top: none;"><br>
                Signature of the HoD/Dean/Principal
            </td>
        </tr>

        <tr>
            <td colspan="4"  style="border: black thin solid; border-top: none; text-align: justify; padding: 5px;"> <input type="checkbox" id="check"  > I hereby declare that i am
                aware of the examination rules of the University. I also affirm that i have appeared in all in the mid semester test, presentation and submitted
                assingnmentas applicable for the course filled in the online examination form. My registration for the course is valid and not time barred.
                If any of my statement is found to be untrue, i shall have no claim for taking examination and my examination result may be withheld/cancelled 
                at any stage. I undertake that i shall abide by the rules and regulations of the University.<td>
        </tr>
     </table>   
@endif
    </div>
	
</body>
</center>
</html>
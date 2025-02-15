@if(Request()->segment(1)=='admin')
@elseif(Request()->segment(1)=='student')
@php $layout_check = 'ums.student.layouts.app1'; @endphp
@else
@php $layout_check = 'ums.student.layouts.app1'; @endphp
@endif

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">



@extends($layout_check)

{{-- Web site Title --}} 
@section('title') Admit Card List :: @parent @stop 
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

<div class="container">
    <div class="row">
        <div class="com-md-12">
            <br/>
                <center><img src="{{asset('assets/frontend/images/cerlogo.png')}}" alt=""></center>
                <br/>
                <h3>List of all Admit Cards</h3>
                <table class="table table-hover">
                    <tr class="">
                        <th>SN#</th>
                        <th>Roll Number</th>
                        <th>Student Name</th>
                        <th>Exam Type</th>
                        <th>Course Name</th>
                        <th>Semester Name</th>
                        <th>Acacemic Session</th>
                        <th>Action</th>
                    </tr>
                    @foreach($examData as $index=>$exam)
                    <tr class="">
                        <td>{{++$index}}</td>
                        <td>{{$exam->roll_no}}</td>
                        <td>{{$exam->students->full_name}}</td>
                        <td>{{($exam->exam_types_details)?$exam->exam_types_details->result_exam_type:''}}</td>
                        <td>{{$exam->course->name}}</td>
                        @if($exam->form_type=='regular')
                        <td>{{$exam->semesters->name}}</td>
                        @else
                        <td>-</td>
                        @endif
                        <td>{{$exam->academic_session}}</td>
                        <td><a target="_blank" href="{{url('admitcard-download')}}?id={{$exam->id}}">View Admit Card</a></td>
                    </tr>
                    @endforeach
                </table>

        </div>
    </div>
</div>


@endsection

@section('styles')
    <style type="text/css"></style>
@endsection

@section('scripts')

@endsection
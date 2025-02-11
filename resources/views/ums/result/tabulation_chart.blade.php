
@extends('ums.admin.admin-meta')
  
@section('content')
    
<!-- BEGIN: Content--> 
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-4 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Tabulation Chart</h2>
                    </div>
                </div>
            </div> 
            <form method="get" id="form_data">
                <div class="content-header-right text-sm-start col-md-8 mb-50 mb-sm-0">
                    <div class="d-flex justify-content-start gap-1">
                        <button type="submit" class="btn btn-primary btn-sm mb-50 mb-sm-0" name="submit_form">Get Report</button>
                    </div>
                </div>
            
        </div>

        <div class="customernewsection-form poreportlistview p-1">
            <div class="row"> 
                <div class="col-md-4 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger">*</span></label>
                    <select data-live-search="true" name="campus_id" id="campus_id" class="form-control" onChange="return $('#form_data').submit();">
                        <option value="">--Choose Campus--</option>
                        @foreach($campuses as $campus)
                            <option value="{{$campus->id}}" @if(Request()->campus_id==$campus->id) selected @endif >{{$campus->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">COURSES <span class="text-danger">*</span></label>
                    <select data-live-search="true" name="course_id" id="course_id" class="form-control js-example-basic-single">
                        <option value="">--Choose Course--</option>
                        @foreach($courses as $course)
                            <option value="{{$course->id}}" @if(Request()->course_id==$course->id) selected @endif >{{$course->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Batch <span class="text-danger">*</span></label>
                    <select data-live-search="true" name="batch" id="batch" class="form-control">
                        <option value="">--Select Batch--</option>
                        @foreach($batches as $batch)
                            <option value="{{$batch}}" @if(Request()->batch==$batch) selected @endif >{{$batch}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
</form>
        <div class="content-body dasboardnewbody">
            <!-- ChartJS section start -->
            <section id="chartjs-chart">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="card new-cardbox">
                            <div class="table-responsive">
                            <h5>Course : {{$course_name}}</h5>
                            <h5>Batch : {{Request()->batch}}</h5>
                                <div class="d-flex justify-content-center">
                                    <table class="table datatables-basic myrequesttablecbox loanapplicationlist w-100">
                                        <thead>
                                            <tr>
                                                <th class="pe-0">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1">
                                                    </div>
                                                </th>
                                                <th>SN#</th>
                                                <th>ROLL NO</th>
                                                <th>UNIVERSITY/COLLEGE NAME</th>
                                                <th>COURSE</th>
                                                <th>ENROLLMENT</th>
                                                <th>NAME</th>
                                                <th>HINDI NAME</th>
                                                <th>FATHER'S NAME</th>
                                                <th>MOTHER'S NAME</th> 
                                                <th>CATEGORY</th>
                                                <th>DISABLED</th>
                                                <th>GENDER</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($students as $index=>$student)
                                            <tr>
                                                <td style="border:1px solid;" rowspan="2">{{++$index}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->roll_number}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->course->campuse->short_name}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->course->name}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->enrollment_no}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->first_Name}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->hindi_name}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->father_first_name}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->mother_first_name}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->category}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->disabilty_category}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->gender}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->address}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->mobile}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{$student->mobile}}</td>
                                                <td style="border:1px solid;" rowspan="2">{{($student->is_lateral==1)?'Yes':'No'}}</td>
                                                @php
                                                    $type = 'REGULAR';
                                                    $total_credit = 0;
                                                    $grand_obtained_marks = 0;
                                                    $grand_required_marks = 0;
                                                    $grand_qp = 0;
                                                    $grand_credit = 0;
                                                    $grand_cgpa = 0;
                                                    $grand_overall_result = 1;
                                                    $grand_percentage = 0;
                                                @endphp
                                                @foreach($semesters as $semester)
                                                    @php
                                                    $results_object = \App\Models\Result::where('roll_no',$student->roll_number)
                                                    ->where('semester',$semester->id)
                                                    ->first();
                                                    if($type=='REGULAR'){
                                                        $results_single = \App\Models\Result::where('roll_no',$student->roll_number)
                                                        ->where('semester',$semester->id)
                                                        ->where('back_status_text','REGULAR')
                                                        ->where('status',2)
                                                        ->first();
                                                    }else{
                                                        if(!$results_object){
                                                            $results_single = null;
                                                        }else{
                                                            $results_single = $results_object->get_semester_result_single();
                                                        }
                                                    }
                                                    if($type=='BACK' && $overall_passed_status=='PASSED'){
                                                        $results_single = null;
                                                    }
                                                    @endphp
                                                    @if($results_single)
                                                    <td style="border:1px solid;">{{$type}}</td>
                                                    <td style="border:1px solid;">{{$results_single->exam_session}}</td>
                                                        @if($results_single->result=='PASS' || $results_single->result=='P')
                                                        <td style="border:1px solid;">-</td>
                                                        @else
                                                        <td style="border:1px solid;">{{$results_single->semester_number}}</td>
                                                        @endif
                                                    @else
                                                    <td style="border:1px solid;">{{$type}}</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    @endif
                                                    <td style="border:1px solid;">{{$semester->semester_number}}</td>
                                                    @php $get_batch_wise_papers = $semester->get_subjects_from_result(Request()->batch); @endphp
                                                    @if($get_batch_wise_papers)
                                                    @foreach($get_batch_wise_papers as $subject)
                                                        @if($results_single)
                                                            @php
                                                            $type_check = $results_single->back_status_text;
                                                            if($type=='REGULAR'){
                                                                $type_check = $type;
                                                            }
                                                            $result = \App\Models\Result::where('roll_no',$student->roll_number)
                                                            ->where('semester',$semester->id)
                                                            ->where('back_status_text',$type_check)
                                                            ->where('subject_code',$subject->subject_code)
                                                            ->orderBy('exam_session','DESC')
                                                            ->first();
                                                            if($result){
                                                                $total_credit = ($total_credit + $result->credit);
                                                                $grand_obtained_marks = ($grand_obtained_marks + $result->total_marks);
                                                                $grand_required_marks = ($grand_required_marks + $result->max_total_marks);
                                                                $grand_credit = ($grand_credit + $result->credit);
                                                                $grand_cgpa = $result->cgpa;
                                                                if($result->result!='PASS' && $result->result!='P'){
                                                                    $grand_overall_result = 0;
                                                                }
                                                                $grand_percentage = round((($grand_obtained_marks * 100)/$grand_required_marks),2);
                                                            }
                                                            @endphp
                                                            <td style="border:1px solid;" @if($result && $result->grade_letter=='F') style="background:#c0c0c0" @endif>{{($result)?$result->internal_marks:'-'}}</td>
                                                            <td style="border:1px solid;" @if($result && $result->grade_letter=='F') style="background:#c0c0c0" @endif>{{($result)?$result->external_marks:'-'}}</td>
                                                            <td style="border:1px solid;" @if($result && $result->grade_letter=='F') style="background:#c0c0c0" @endif>{{($result)?$result->total_marks:'-'}}</td>
                                                            <td style="border:1px solid;" @if($result && $result->grade_letter=='F') style="background:#c0c0c0" @endif>{{($result)?$result->grade_letter:'-'}}</td>
                                                        @else
                                                            <td style="border:1px solid;">-</td>
                                                            <td style="border:1px solid;">-</td>
                                                            <td style="border:1px solid;">-</td>
                                                            <td style="border:1px solid;">-</td>
                                                        @endif
                                                    @endforeach
                                                        @if($results_single)
                                                        <td style="border:1px solid;">{{$results_single->qp}}</td>
                                                        @php $grand_qp = ($grand_qp + $results_single->qp); @endphp
                                                        <td style="border:1px solid;">{{$total_credit}}</td>
                                                        <td style="border:1px solid;">{{$results_single->sgpa}}</td>
                                                        <td style="border:1px solid;">{{$results_single->result_full}}</td>
                                                        @else
                                                        <td style="border:1px solid;">-</td>
                                                        <td style="border:1px solid;">-</td>
                                                        <td style="border:1px solid;">-</td>
                                                        <td style="border:1px solid;">-</td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @if($results_single)
                                                    <td style="border:1px solid;">{{$grand_obtained_marks}}</td>
                                                    <td style="border:1px solid;">{{$grand_required_marks}}</td>
                                                    <td style="border:1px solid;">{{$grand_qp}}</td>
                                                    <td style="border:1px solid;">{{$grand_credit}}</td>
                                                    @php $grand_cgpa = ($grand_qp/$grand_credit); @endphp
                                                    <td style="border:1px solid;">{{number_format($grand_cgpa,2)}}</td>
                                                    @if($grand_cgpa>0)
                                                    @php $overall_passed_status = ($grand_overall_result)?'PASSED':'FAILED'; @endphp
                                                    <td style="border:1px solid;">{{($grand_overall_result)?'PASSED':'FAILED'}}</td>
                                                    <td style="border:1px solid;">{{number_format(($grand_cgpa*10),2)}}</td>
                                                    <td style="border:1px solid;">{{($grand_overall_result)?'ELIGIBLE':'NOT ELIGIBLE'}}</td>
                                                    <td style="border:1px solid;">{{$grand_percentage}}</td>
                                                    @else
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    @endif
                                                    <td style="border:1px solid;">-</td>
                                                @else
                                                <td style="border:1px solid;">-</td>
                                                <td style="border:1px solid;">-</td>
                                                <td style="border:1px solid;">-</td>
                                                <td style="border:1px solid;">-</td>
                                                <td style="border:1px solid;">-</td>
                                                <td style="border:1px solid;">-</td>
                                                <td style="border:1px solid;">-</td>
                                                <td style="border:1px solid;">-</td>
                                                <td style="border:1px solid;">-</td>
                                                <td style="border:1px solid;">-</td>
                                                @endif
                                            </tr>
                                            <tr>
                                            @php
                                                    $type = 'BACK';
                                                    $total_credit = 0;
                                                    $grand_obtained_marks = 0;
                                                    $grand_required_marks = 0;
                                                    $grand_qp = 0;
                                                    $grand_credit = 0;
                                                    $grand_cgpa = 0;
                                                    $grand_overall_result = 1;
                                                    $grand_percentage = 0;
                                                @endphp
                                                @foreach($semesters as $semester)
                                                    @php
                                                    $results_object = \App\Models\Result::where('roll_no',$student->roll_number)
                                                    ->where('semester',$semester->id)
                                                    ->first();
                                                    if($type=='REGULAR'){
                                                        $results_single = \App\Models\Result::where('roll_no',$student->roll_number)
                                                        ->where('semester',$semester->id)
                                                        ->where('back_status_text','REGULAR')
                                                        ->where('status',2)
                                                        ->first();
                                                    }else{
                                                        if(!$results_object){
                                                            $results_single = null;
                                                        }else{
                                                            $results_single = $results_object->get_semester_result_single();
                                                        }
                                                    }
                                                    if($type=='BACK' && $overall_passed_status=='PASSED'){
                                                        $results_single = null;
                                                    }
                                                    @endphp
                                                    @if($results_single)
                                                    <td style="border:1px solid;">{{$type}}</td>
                                                    <td style="border:1px solid;">{{$results_single->exam_session}}</td>
                                                        @if($results_single->result=='PASS' || $results_single->result=='P')
                                                        <td style="border:1px solid;">-</td>
                                                        @else
                                                        <td style="border:1px solid;">{{$results_single->semester_number}}</td>
                                                        @endif
                                                    @else
                                                    <td style="border:1px solid;">{{$type}}</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    @endif
                                                    <td style="border:1px solid;">{{$semester->semester_number}}</td>
                                                    @php $get_batch_wise_papers = $semester->get_subjects_from_result(Request()->batch); @endphp
                                                    @if($get_batch_wise_papers)
                                                    @foreach($get_batch_wise_papers as $subject)
                                                        @if($results_single)
                                                            @php
                                                            $type_check = $results_single->back_status_text;
                                                            if($type=='REGULAR'){
                                                                $type_check = $type;
                                                            }
                                                            $result = \App\Models\Result::where('roll_no',$student->roll_number)
                                                            ->where('semester',$semester->id)
                                                            ->where('back_status_text',$type_check)
                                                            ->where('subject_code',$subject->subject_code)
                                                            ->orderBy('exam_session','DESC')
                                                            ->first();
                                                            if($result){
                                                                $total_credit = ($total_credit + $result->credit);
                                                                $grand_obtained_marks = ($grand_obtained_marks + $result->total_marks);
                                                                $grand_required_marks = ($grand_required_marks + $result->max_total_marks);
                                                                $grand_credit = ($grand_credit + $result->credit);
                                                                $grand_cgpa = $result->cgpa;
                                                                if($result->result!='PASS' && $result->result!='P'){
                                                                    $grand_overall_result = 0;
                                                                }
                                                                $grand_percentage = round((($grand_obtained_marks * 100)/$grand_required_marks),2);
                                                            }
                                                            @endphp
                                                            <td style="border:1px solid;" @if($result && $result->grade_letter=='F') style="background:#c0c0c0" @endif>{{($result)?$result->internal_marks:'-'}}</td>
                                                            <td style="border:1px solid;" @if($result && $result->grade_letter=='F') style="background:#c0c0c0" @endif>{{($result)?$result->external_marks:'-'}}</td>
                                                            <td style="border:1px solid;" @if($result && $result->grade_letter=='F') style="background:#c0c0c0" @endif>{{($result)?$result->total_marks:'-'}}</td>
                                                            <td style="border:1px solid;" @if($result && $result->grade_letter=='F') style="background:#c0c0c0" @endif>{{($result)?$result->grade_letter:'-'}}</td>
                                                        @else
                                                            <td style="border:1px solid;">-</td>
                                                            <td style="border:1px solid;">-</td>
                                                            <td style="border:1px solid;">-</td>
                                                            <td style="border:1px solid;">-</td>
                                                        @endif
                                                    @endforeach
                                                        @if($results_single)
                                                        <td style="border:1px solid;">{{$results_single->qp}}</td>
                                                        @php $grand_qp = ($grand_qp + $results_single->qp); @endphp
                                                        <td style="border:1px solid;">{{$total_credit}}</td>
                                                        <td style="border:1px solid;">{{$results_single->sgpa}}</td>
                                                        <td style="border:1px solid;">{{$results_single->result_full}}</td>
                                                        @else
                                                        <td style="border:1px solid;">-</td>
                                                        <td style="border:1px solid;">-</td>
                                                        <td style="border:1px solid;">-</td>
                                                        <td style="border:1px solid;">-</td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @if($results_single)
                                                    <td style="border:1px solid;">{{$grand_obtained_marks}}</td>
                                                    <td style="border:1px solid;">{{$grand_required_marks}}</td>
                                                    <td style="border:1px solid;">{{$grand_qp}}</td>
                                                    <td style="border:1px solid;">{{$grand_credit}}</td>
                                                    @php $grand_cgpa = ($grand_qp/$grand_credit); @endphp
                                                    <td style="border:1px solid;">{{number_format($grand_cgpa,2)}}</td>
                                                    @if($grand_cgpa>0)
                                                        @php $overall_passed_status = ($grand_overall_result)?'PASSED':'FAILED'; @endphp
                                                        <td style="border:1px solid;">{{($grand_overall_result)?'PASSED':'FAILED'}}</td>
                                                        <td style="border:1px solid;">{{number_format(($grand_cgpa*10),2)}}</td>
                                                        <td style="border:1px solid;">{{($grand_overall_result)?'ELIGIBLE':'NOT ELIGIBLE'}}</td>
                                                        <td style="border:1px solid;">{{$grand_percentage}}</td>
                                                    @else
                                                        <td style="border:1px solid;">-</td>
                                                        <td style="border:1px solid;">-</td>
                                                        <td style="border:1px solid;">-</td>
                                                        <td style="border:1px solid;">-</td>
                                                    @endif
                                                @else
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                    <td style="border:1px solid;">-</td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> 
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

    
       
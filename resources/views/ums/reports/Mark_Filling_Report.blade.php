@extends('ums.admin.admin-meta')

@section('content')

{{-- <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
  
<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Mark Filling Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>  
                                <li class="breadcrumb-item active">Filling Reports</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <form method="get" id="form_data">
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right"> 

                    <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                    <button class="btn btn-primary btn-sm mb-50 mb-sm-0" type="submit" name="submit_form">
                        <i data-feather="check-circle"></i> Get Report
                    </button>
                    <button class="btn   btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal">
                         Manage Subjects
                    </button> 
                    <!-- Reset Button -->
                    
                </div>
            </div>
            
        </div>
    
                <div class="customernewsection-form poreportlistview p-1">
                    <div class="row"> 
                        <div class="col-md-2">
                            <div class="mb-1 mb-sm-0"> 
                                <label class="form-label">Campus:</label>
                                <select data-live-search="true" name="campus_id" id="campus_id" style="border-color: #c0c0c0;" class="form-control" onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Campus--</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{$campus->id}}" @if(Request()->campus_id==$campus->id) selected @endif >{{$campus->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-1 mb-sm-0"> 
                                <label class="form-label">Courses:</label>
                                <select data-live-search="true" name="course_id" id="course_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Course--</option>
                                        @foreach($courses as $course)
                                        <option value="{{$course->id}}" @if(Request()->course_id==$course->id) selected @endif >{{$course->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-1 mb-sm-0"> 
                                <label class="form-label">Semester Type:</label>
                            	<select data-live-search="true" name="semester_type" id="semester_type" style="border-color: #c0c0c0;" class="form-control js-example-basic-single">
                                    <option value="">--Select Semester--</option>
                                    <option value="all" @if(Request()->semester_type=='all') selected @endif >All</option>
                                    <option value="odd" @if(Request()->semester_type=='odd') selected @endif >Odd</option>
                                    <option value="even" @if(Request()->semester_type=='even') selected @endif >Even</option>
                            </select>
                             </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-1 mb-sm-0"> 
                                <label class="form-label">Semester:</label>
                                <select data-live-search="true" name="semester_id" id="semester_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single">
                                    <option value="">--Select Semester--</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{$semester->id}}" @if(Request()->semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="mb-1 mb-sm-0"> 
                                <label class="form-label">Academic Session:</label>
                                <select name="academic_session" id="academic_session" class="form-control" style="border-color: #c0c0c0;">
                                    <option value="">--Select Semester--</option>
                                    @foreach($sessions as $session)
                                        <option value="{{$session->academic_session}}" @if(Request()->academic_session == $session->academic_session) selected @endif >{{$session->academic_session}}</option>
                                    @endforeach
                                </select>                         
                             </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
   
            <div class="content-body dasboardnewbody">
                <form method="post" id="saveSequence">
                    <input type="hidden" name="semester_id" value="{{Request()->semester_id}}">

                <!-- ChartJS section start -->
                <section id="chartjs-chart">
                    <div class="row">
						
						  
						
						<div class="col-md-12 col-12">
                            <div class="card  new-cardbox"> 
								 <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                      
                                        <thead>
                                            <tr class="text-center">
                                                <th rowspan="2">SN#</th>
                                                <th rowspan="2">Semester</th>
                                                <th rowspan="2">Paper Code</th>
                                                <th rowspan="2">Paper Name</th>
                                                <th rowspan="2">Internal Max</th>
                                                <th rowspan="2">External Max</th>
                                                <th rowspan="2">Credit</th>
                                                <th colspan="3">Filled</th>
                                                <th rowspan="2">Total To Be Filled</th>
                                            </tr>
                                            <tr class="text-center">
                                                <th>Internal</th>
                                                <th>External</th>
                                                <th>Practical</th>
                                            </tr>
                                        </thead>
  <tbody>
    @php 
    $serial_no = 0; 
    $internalMark_total = 0;
    $externalMark_total = 0;
    $practicalMark_total = 0;
    $all_count_total = 0;
@endphp

@foreach( $subjects as $subject)
@php $mark_filed_details = $subject->mark_filed_details(Request()->academic_session); @endphp
@php 
    $serial_no = $serial_no + 1;
    $internalMark_total = $internalMark_total + $mark_filed_details->internalMark;
    $externalMark_total = $externalMark_total + $mark_filed_details->externalMark;
    $practicalMark_total = $practicalMark_total + $mark_filed_details->practicalMark;
    $all_count_total = $all_count_total + $mark_filed_details->all_count;
@endphp
    <tr>  
        <td class="text-center">
            <input type="text" name="position[]" class="position position_style form-control-plaintext text-center" value="{{$subject->position}}">
            <input type="hidden" name="sub_code[]" value="{{$subject->sub_code}}">
        </td>
        <td>{{$subject->semester->name}}</td>
        <td class="text-center"><a href="{{url('subject_list_edit')}}/{{$subject->id}}" target="_blank">{{$subject->sub_code}}</a></td>
        <td>{{$subject->name}}</td>
        <td>{{$subject->internal_maximum_mark}}</td>
        <td>{{$subject->maximum_mark}}</td>
        <td>{{$subject->credit}}</td>
        <td class="text-center">{{$mark_filed_details->internalMark}}</td>
        <td class="text-center">{{$mark_filed_details->externalMark}}</td>
        <td class="text-center">{{$mark_filed_details->practicalMark}}</td>
        <td class="text-center"><a href="{{route('filledMarkDetails',['academic_session'=>Request()->academic_session, 'semester_id'=>$subject->semester_id, 'sub_code'=>$subject->sub_code])}}" target="_blank">{{$mark_filed_details->all_count}}</a></td>
    </tr>
    @endforeach
</tbody>
<tfoot>
<tr>
    <th colspan="7" class="text-right">Total</th>
    <th class="text-center">{{$internalMark_total}}</th>
    <th colspan="2" class="text-center">{{($externalMark_total + $practicalMark_total)}}</th>
    <th class="text-center">{{$all_count_total}}</th>
</tr>
</tfoot>
</tbody>                    
                </table>
            </form>
                </div>
								
								  
                            
                                
								 
								 
                                
                          </div>
                        </div>
						
						
						 
                         
                         
                    </div>
					
					 
                     
                </section>
                <!-- ChartJS section end -->

            </div>
    </div>
 
{{-- </body> --}}
@endsection


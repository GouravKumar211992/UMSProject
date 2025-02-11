@extends('ums.admin.admin-meta')

@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">

        <form method="GET" action="{{route('Mbbs-bulk-result')}}" id="form_data">

            <div class="customernewsection-form poreportlistview p-1">
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="mb-1 mb-sm-0"> 
                            <label class="form-label">COURSES:</label>
                            <select data-live-search="true" name="course_id" id="course_id" class="form-control js-example-basic-single" onChange="return $('#form_data').submit();">
                                <option value="">--Choose Course--</option>
                                @foreach($courses as $course)
                                    <option value="{{$course->id}}" @if(Request()->course_id==$course->id) selected @endif >{{$course->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="mb-1 mb-sm-0"> 
                            <label class="form-label">SEMESTER:</label>
                            <select name="semester_id" id="semester_id" class="form-control js-example-basic-single">
                                <option value="">--Choose Semester--</option>
                                @foreach($semesters as $semester)
                                    <option value="{{$semester->id}}" @if(Request()->semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="mb-1 mb-sm-0"> 
                            <label class="form-label">Batch:</label>
                            <select name="batch" id="batch" class="form-control">
                                <option value="">--Batch--</option>
                                @foreach($batches as $batch_row)
                                    <option value="{{$batch_row}}" @if(Request()->batch==$batch_row) selected @endif>{{$batch_row}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 

                    <div class="col-md-3">
                        <div class="mb-1 mb-sm-0"> 
                            <label class="form-label">Result Type:</label>
                            <select name="exam_type" id="exam_type" class="form-control">
                                <option value="0" @if(Request()->exam_type==0) selected @endif>Regular</option>
                                <option value="1" @if(Request()->exam_type==1) selected @endif>Scrutiny</option>
                                <option value="2" @if(Request()->exam_type==2) selected @endif>Challenge</option>
                                <option value="3" @if(Request()->exam_type==3) selected @endif>Supplementary</option>
                            </select>                
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Get Report</button>
                        <button type="button" class="btn btn-warning" onclick="window.location.reload();"><i data-feather="refresh-cw"></i> Reset</button>
                    </div>
                </div>

            </div> 
        </form>

    </div> 
</div> 



@endsection

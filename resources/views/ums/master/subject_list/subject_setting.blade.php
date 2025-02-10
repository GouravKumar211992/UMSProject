@extends('ums.admin.admin-meta')

@section('content')
    

  
   
    <div class="app-content content ">
        <h4>Subject Bulk Data</h4>

       

        <div class="submitss text-end me-3">


            <button form="form_data" class="btn btn-primary btn-sm mb-50 mb-sm-0r " type="submit">
                <i data-feather="check-circle"></i> Submit
            </button>
        </div>

<div class="content-body bg-white p-4 shadow">
<form method="GET" id="form_data" action="{{url('subject_setting')}}">

        <div class="col-md-12">
            <div class="row align-items-center mb-1">
                <!-- Campus Field -->
                <div class="col-md-4">
                    <label class="form-label mb-0 me-2">Campus <span class="text-danger">*</span></label>
                    <select name="campus_id" style="border-color: #c0c0c0;" class="form-control campus_id" id="campus_id" onchange="this.form.submit()">
                <option value="">--Select--</option>
                @foreach($campuses as $campus)
                <option value="{{$campus->id}}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>
                    {{$campus -> name}}
                </option>
                @endforeach
               </select>
                </div>
        
                <!-- Course Field -->
                <div class="col-md-4">
                    <label class="form-label mb-0 me-2">Course <span class="text-danger">*</span></label>
                    <select name="course_id" style="border-color: #c0c0c0;" class="form-control course_id" id="course_id" onchange="this.form.submit()">
                <option value="">--Select--</option>
                @foreach($courses as $course)
                <option value="{{$course->id}}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                    {{$course->name}}
                </option>
                @endforeach
               </select>
                </div>
        
                <!-- Semester Field -->
                <div class="col-md-4">
                    <label class="form-label mb-0 me-2">Semester <span class="text-danger">*</span></label>
                    <select  name="semester_id" style="border-color: #c0c0c0;" class="form-control semester_id" id="semester_id" onchange="this.form.submit()">
                <option value="">--Select--</option>
                @foreach($semesters as $semester)
                <option value="{{$semester->id}}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                {{$semester->name}}
                </option>
                @endforeach
            </select>
                </div>
            </div>

        </div>
        
    







        <!-- options section end-->

          
                
                    <div class="row mt-4">
                        <!-- Card 1 -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    Compulsory Papers
                                </div>
                                <div id="container1" class="panel-body box-container">
                    @foreach($subjects as $subject)
                    @if($subject->type=='compulsory')
                    <div style="text-wrap: wrap; text-align: left;" class="btn btn-default box-item">
                        {{$subject->sub_code}} ({{substr($subject->name,0,30)}})
                    </div>
                    @endif
                    @endforeach
                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    Optional Papers
                                </div>
                                <div id="container2" class="panel-body box-container">
                    @foreach($subjects as $subject)
                    <div style="text-wrap: wrap; text-align: left;" class="btn btn-default box-item">
                        {{$subject->sub_code}}  ({{$subject->name}})</div>
                   
                    @endforeach
                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-danger text-white">

                                    Optional Papers
                                </div>
                                <div id="container3" class="panel-body box-container">
                    @foreach($subjects as $subject)
                    <div style="text-wrap: wrap; text-align: left;" class="btn btn-default box-item">
                        {{$subject->sub_code}} ({{$subject->name }})</div>
                    @endforeach
                </div>
                            </div>
                        </div>
                    </div>
                </div>


                </form>
            </div>
        </div>
        <!-- END: Content-->

        
      


        <div class="modal fade" id="reallocate" tabindex="-1" aria-labelledby="shareProjectTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header p-0 bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-4 mx-50 pb-2">
                        <h1 class="text-center mb-1" id="shareProjectTitle">Re-Allocate Incident</h1>
                        <p class="text-center">Enter the details below.</p>

                        <div class="row mt-2">

                            <div class="col-md-12 mb-1">
                                <label class="form-label">Re-Allocate To <span class="text-danger">*</span></label>
                                <select class="form-select select2">
                                    <option>Select</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-1">
                                <label class="form-label">Re-Allocate Dept. <span class="text-danger">*</span></label>
                                <select class="form-select select2">
                                    <option>Select</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-1">
                                <label class="form-label">PDC Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" placeholder="Enter Name" />
                            </div>

                            <div class="col-md-12 mb-1">
                                <label class="form-label">Remarks <span class="text-danger">*</span></label>
                                <textarea class="form-control"></textarea>
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
                        <button type="reset" class="btn btn-primary">Re-Allocate</button>
                    </div>
                </div>
            </div>
        </div>




        <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
            <div class="modal-dialog sidebar-sm">
                <form class="add-new-record modal-content pt-0">
                    <div class="modal-header mb-1">
                        <h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close">×</button>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <div class="mb-1">
                            <label class="form-label" for="fp-range">Select Date Range</label>
                            <input type="text" id="fp-range" class="form-control flatpickr-range"
                                placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Select Incident No.</label>
                            <select class="form-select select2">
                                <option>Select</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Select Customer</label>
                            <select class="form-select select2">
                                <option>Select</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Assigned To</label>
                            <select class="form-select select2">
                                <option>Select</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option>Select</option>
                                <option>Open</option>
                                <option>Close</option>
                                <option>Re-Allocatted</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="btn btn-primary data-submit mr-1">Apply</button>
                        <button type="reset" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        @endsection
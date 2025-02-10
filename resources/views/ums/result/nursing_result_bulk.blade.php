@extends("ums.admin.admin-meta")
@section("content")

    @php

$course_name = '';
$semester_name = '';
@endphp
    <div class="app-content content ">
      {{-- @if($download!='pdf') --}}
      <form method="get" id="form_data">

      <div class="big-box d-flex justify-content-between mb-1 align-items-center">

        <div class="head">
    <div class="row d-flex justify-content-between">
        <div class="col-md-9">
            <h4>Tabular Record (TR)</h4>
        </div>
        <div class="col-md-3 text-right">
            <div class="breadcrumbs-top">
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <!-- <li class="breadcrumb-item active">List of Admins</li> -->
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>


        
        <div class="submitss text-start me-3 align-item-center">
            <input type="submit" class="btn-sm btn  mb-50 mb-sm-0r btn-primary mt-1" value="Generate TR">
            <button class="btn btn-warning btn-sm mb-50 mb-sm-0r mt-1"  onclick="window.location.reload();" type="reset">
                <i data-feather="refresh-cw"></i> Reset
            </button>
        </div>
      </div>

    <div class="row">
    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger ">*</span></label>
                <select data-live-search="true" name="campus_id" id="campus_id" style="border-color: #c0c0c0;" class="form-control" onChange="return $('#form_data').submit();">
                <option value="">--Choose Campus--</option>
                  @foreach($campuses as $campus)
                    <option value="{{$campus->id}}" @if(Request()->campus_id==$campus->id) selected @endif >{{$campus->name}}</option>
                    @endforeach
                  </select>
                  <span class="text-danger">{{ $errors->first('campus_id') }}</span>
                    </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Courses <span class="text-danger">*</span></label>
                <select data-live-search="true" name="course" id="course" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                  <option value="">--Choose Course--</option>
                      @foreach($courses as $course)
                        @if($course_id==$course->id)
                          @php
                            $course_name = $course->name;
                          @endphp
                        @endif
                        <option value="{{$course->id}}" @if($course_id==$course->id) selected @endif >{{$course->name}}</option>
                        @endforeach
                      </select>
                      <span class="text-danger">{{ $errors->first('course') }}</span>
                </div>
                
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Semester <span class="text-danger">*</span></label>
                <select data-live-search="true" name="semester" id="semester" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="$('#group_name').prop('selectedIndex',0); return $('#form_data').submit();">
                  <option value="">--Select Semester--</option>
                  @foreach($semesters as $semester)
                    @if($semester_id==$semester->id)
                      @php
                        $semester_name = $semester->name;
                      @endphp
                    @endif
                <option value="{{$semester->id}}" @if($semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                  @endforeach
                            
                    </select>
                  <span class="text-danger">{{ $errors->first('semester') }}</span>
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Session <span class="text-danger">*</span></label>
                    <select name="session" id="academic_session" class="form-control" style="border-color: #c0c0c0;" onChange="return $('#form_data').submit();">
						@foreach($sessions as $sessionRow)
						<option value="{{$sessionRow->academic_session}}" @if(Request()->academic_session == $sessionRow->academic_session) selected @endif >{{$sessionRow->academic_session}}</option>
						@endforeach
					</select>
					<span class="text-danger">{{ $errors->first('academic_session') }}</span>
                </div>
        </div>
    </div>

      {{-- @if($full_retult && count($full_retult) > 0)
      <div class="col-6">
        <br>
        <br>
        <a href="{{Request::getRequestUri()}}&finalize=true" class="btn btn-success">Finalize TR</a>
        <a href="{{Request::getRequestUri()}}&finalize=reset" class="btn btn-danger" style="margin-left:50;">Re-Fresh TR</a>
      </div>
      @endif --}}
      {{-- <div class="@if($full_retult && count($full_retult) > 0) col-6 @else col-12 @endif text-right"> --}}
        <br>
        <br>
      </div>
      </div>
    </form>
    {{-- @endif --}}
    <div class="container">
      <!-- or use any other number .col-md- -->
      
    </div>
  </div>
  {{-- @if($full_retult && count($full_retult) > 0)
    <div class="alert alert-success">Results of {{count($full_retult)}} students are generated.</div>
  @endif		  --}}
   


      
       <!-- END: Content-->

       <div class="sidenav-overlay"></div>
       <div class="drag-target"></div>

@endsection
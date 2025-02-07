@extends('ums.admin.admin-meta')
@section('content')

    {{-- <!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">

    <title>Presence 360</title>

    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../assets/css/favicon.png">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600;700"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
        href="../../../app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="../../../app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="../../../app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="../../../app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/css/iconsheet.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    @include('header')

    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    @include('sidebar')
    <!-- END: Main Menu--> --}}

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Exam</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item"><a>View Time Tables</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <form method="get" id="form_data">
                        <div class="form-group breadcrumb-right">
                            <button class="btn btn-primary btn-sm mb-50 mb-sm-0" type="submit" id="submitBtn"><i
                                    data-feather="clipboard"></i> Submit</button>
                            {{-- <button class="btn btn-secondary btn-sm mb-50 mb-sm-0">Go Back</button>
                         <a class="btn btn-primary btn-sm mb-50 mb-sm-0" href="/view_time_tables"> View Time Tables</a>  

                          
                        <button class="btn btn-success  btn-sm mb-50 mb-sm-0">Schedule Bulk Upload</button> --}}


                        </div>
                </div>
            </div>
            <div class="content-body bg-white p-4 shadow">

                @include('ums.admin.notifications')
                <div class="row">

                    <!-- Campus and Course Selection -->
                    <div class="col-md mt-4 mb-3">
                        <!-- Campus Selection -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Campus:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="campus_id" id="campus_id"
                                    style="border-color: #c0c0c0;" class="form-control js-example-basic-single "
                                    onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Campus--</option>
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}"
                                            @if (Request()->campus_id == $campus->id) selected @endif>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('campus_id') }}</span>

                            </div>
                        </div>

                        <!-- Course Selection -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course:<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="course" id="course" style="border-color: #c0c0c0;"
                                    class="form-control js-example-basic-single "
                                    onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Course--</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}"
                                            @if (Request()->course == $course->id) selected @endif>{{ $course->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('course') }}</span>

                            </div>
                        </div>
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Schedule Count<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select data-live-search="true" name="schedule_count" id="schedule_count"
                                    style="border-color: #c0c0c0;" class="form-control js-example-basic-single ">
                                    <option value="">Select Schedule Count</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}"
                                            @if (Request()->schedule_count == $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                                <span class="text-danger">{{ $errors->first('schedule_count') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Semester, Session, and other options -->
                    <div class="col-md mt-4 mb-3">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Semester:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="semester" id="semester"
                                    style="border-color: #c0c0c0;" class="form-control js-example-basic-single ">
                                    <option value="">Select Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}"
                                            @if (Request()->semester == $semester->id) selected @endif>{{ $semester->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('semester') }}</span>
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Session:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="session" id="session" style="border-color: #c0c0c0;"
                                    class="form-control js-example-basic-single ">
                                    <option value="">Select Session</option>
                                    @foreach ($sessions as $session)
                                        <option value="{{ $session->academic_session }}"
                                            @if (Request()->session == $session->academic_session) selected @endif>
                                            {{ $session->academic_session }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('session') }}</span>
                            </div>
                        </div>

                    </div>
                    </form>
                    @if ($exams)
                        @if (count($exams) > 0)
                            <div class="row">
                                <div class="col-sm-5">
                                    <span></span>

                                </div>
                                <div class="col-sm-4">
                                    <span></span><br>
                                    <a herf="#"onclick="window.print()" name=""
                                        class="btn btn-primary hidden-print" value="">PRINT</a>
                                </div>

                            </div>

                            <div class="container" style="background-color: #FFFFFF; margin-top: 15px;">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div id="" class="" style="color:black">
                                            <div class="panel-body">
                                                <div class="container">
                                                    <table border="1" id="tblTheory21" class="table table-hover">
                                                        <tr>
                                                            <td colspan="6">
                                                                <center>
                                                                    <h4>Exam Time Table</h4>
                                                                </center>
                                                        </tr>
                                                        <tr>
                                                           
                                                            @if (true)
                                                                <th class="thcenter hidden-print">Action</th>
                                                            @endif
                                                            <th class="thcenter">COURSE</th>
                                                            <th class="thcenter">DATE</th>
                                                            <th class="thcenter">SHIFT</th>
                                                            <th class="thcenter">PAPER CODE</th>
                                                            <th class="thcenter">PAPER NAME</th>
                                                        </tr>
                                                        @foreach ($exams as $exam)
                                                            <tr class="auto-style18 thcenter">
                                                                {{-- !Auth::guard('student')->user() --}}
                                                                @if (true)
                                                                    <td class="hidden-print"><a href="#!"
                                                                            data-bs-toggle="modal"

                                                                            
                                                                            data-bs-target="#exampleModal{{$exam->id}}"
                                                                            class="fa fa-pencil login_btn">
                                                                            Edit</a>
                                                                    </td>
                                                                @endif
                                                                <td><span
                                                                        id="lblPap1ID">{{ $exam->semester->name }}</span>
                                                                </td>
                                                                <td><span id="lblPap1Name"><input type="text"
                                                                            class="date" value="{{ $exam['date'] }}"
                                                                            name="date[]"
                                                                            hidden>{{ date('d-m-Y', strtotime($exam['date'])) }}</span>
                                                                </td>
                                                                <td><span id="lblPap1TE"><input type="text"
                                                                            class="shift" value="{{ $exam['shift'] }}"
                                                                            name="shift[]" hidden>
                                                                        {{ $exam['shift'] }}
                                                                    </span></td>
                                                                <td><span id="lblPap1TS">{{ $exam['paper_code'] }}
                                                                    </span></td>
                                                                <td><span
                                                                        id="lblPap1ID">{{ @$exam->subject->name }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="container" style="background-color: #FFFFFF; margin-top: 15px;">
                                <b style="color: black;">No Schedule Generated Please Wait... </b>
                            </div>
                        @endif
                    @endif

                    <!-- Submit Button -->

                </div>
            
  <!-- Modal -->
  @foreach($exams as $exam)
<div class="modal fade" id="exampleModal{{$exam->id}}" tabindex="-1" aria-labelledby="exampleModalLabel{{$exam->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel{{$exam->id}}">Change Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('schedule-update') }}" id="myform{{$exam->id}}">
                @csrf
                <div class="modal-body" style="background:#f0f0f0;">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <!-- Hidden input to store the exam ID -->
                            <input type="text" name="id" value="{{$exam->id}}" hidden>
                            <label for="date{{$exam->id}}" class="col-form-label">Date</label>
                        </div>
                        <div class="col-md-6">
                            <!-- Input for the exam date -->
                            <input style="border-color: #c0c0c0;" name="date" type="date" id="date{{$exam->id}}" class="form-control date" value="{{ date('Y-m-d', strtotime($exam->date)) }}">
                            <span class="text-danger">{{ $errors->first('date[]') }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="shift{{$exam->id}}" class="col-form-label">Shift</label>
                        </div>
                        <div class="col-md-6">
                            <!-- Dropdown for the exam shift -->
                            <select name="shift" class="form-control shift" id="shift{{$exam->id}}">
                                <option value="">--Choose Shift--</option>
                                <option value="morning" {{ $exam->shift == 'morning' ? 'selected' : '' }}>Morning</option>
                                <option value="evening" {{ $exam->shift == 'evening' ? 'selected' : '' }}>Evening</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" value="Save Changes" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

 




                <!-- END: Content-->




<script>

$(document).ready(function() {
    $('.login_btn').click(function() {
        var current_tr = $(this).closest('tr');
        var examId = $(this).data('mid'); 
        var date = current_tr.find('.date').val();
        var shift = current_tr.find('.shift').val();
        $('#exampleModal').find('.modal_id').val(examId); 
        $('#exampleModal').find('.date').val(date); 
        $('#exampleModal').find('.shift').val(shift); 
    });
});


    </script>
            @endsection

@extends('ums.result.layouts.app1')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SEMESTER RESULT</title>
        <style>
            .result {
                width: 1200px;
                height: 1780px;
                padding: 0px 20px;
                font-family: Arial;
                background: url("{{ asset('images/markbg.jpg') }}") no-repeat;
                background-size: cover;
                position: relative;
            }

            table.result-head {
                width: 100%;
                font-size: 14px;
                float: right;
                margin-top: 80px;
            }

            table.result-head td p {
                margin-top: 0;
                margin-bottom: 2px;
            }

            td.result-sr {
                vertical-align: bottom;
                margin-top: 0;
            }

            td.result-sr p {
                margin-top: 0 !important;
                margin-bottom: 2px !important;
            }

            tr.result-roll td p {
                margin-top: 0;
                margin-bottom: 1px
            }

            .header {
                height: 350px;
                text-align: center;
            }

            .text-center {
                text-align: center !important;
            }

            .header-1 p {
                text-align: center !important;
                margin-bottom: 8px !important;
                margin-top: 0px !important;
                font-size: 23px !important;
                font-weight: bold;
            }

            .course {
                text-transform: uppercase;
                font-size: 16px !important;
                font-weight: inherit !important
            }

            table.head2 {
                width: 100%;
                font-size: 13px;
            }

            table.student-details td {
                padding: 3px;
                padding-left: 0px;
            }

            .marks-footer td,
            .marks-footer1 td,
            .marks-footer2 td {
                padding: 3px !important;
            }

            .sameFontSize,
            .sameFontSize>p,
            table.result-footer,
            table.result-footer p,
            table.result-head,
            table.student-details,
            table.marks {
                font-size: 16px !important;
            }

            .sameFontSize,
            .sameFontSize>p {
                font-size: 14px !important;
            }

            table.marks {
                width: 100%;
                font-size: 12px;
                margin-top: 10px;
            }

            tr.marks-head {
                font-weight: bold;
            }

            tr.marks-head th {
                border: #000000 thin solid;
                width: 50px;
                padding: 7px;
                border-right: none;
            }

            .marks-head1 th {
                border: #000000 thin solid;
                padding: 7px;
                border-right: none;
                border-top: none;
            }

            .marks-data td {
                border-left: #000000 thin solid;
                padding: 3px 3px !important;
                vertical-align: top;
                text-align: center;
            }

            tr.marks-footer td:nth-child(1) {
                border: #000000 thin solid;
                padding: 4px 7px;
                border-bottom: none;
                border-right: none;
            }

            tr.marks-footer td:nth-child(2) {
                padding: 4px 7px;
                border-top: #000000 thin solid;
                border-bottom: none;
                font-weight: bold;
            }

            tr.marks-footer td:nth-child(3) {
                padding: 4px 7px;
                border-left: #000000 thin solid;
                border-top: #000000 thin solid;
            }

            tr.marks-footer td:nth-child(4) {
                border-right: #000000 thin solid;
                border-top: #000000 thin solid;
                font-weight: bold;
            }

            tr.marks-footer1 td:nth-child(1) {
                border: #000000 thin solid;
                padding: 4px 7px;
                border-bottom: none;
                border-right: none;
                border-top: none;
            }

            tr.marks-footer1 td:nth-child(2) {
                padding: 4px 7px;
                border-top: none;
                border-bottom: none;
                font-weight: bold;
            }

            tr.marks-footer1 td:nth-child(3) {
                padding: 4px 7px;
                border-left: #000000 thin solid;
                border-top: none;
            }

            tr.marks-footer1 td:nth-child(4) {
                border-right: #000000 thin solid;
                border-top: none;
                font-weight: bold;
            }

            tr.marks-footer2 td:nth-child(1) {
                border: #000000 thin solid;
                padding: 4px 7px;
                border-bottom: none;
                border-right: none;
                border-top: none;
                border-bottom: #000000 thin solid;
            }

            tr.marks-footer2 td:nth-child(2) {
                padding: 4px 7px;
                border-top: none;
                border-bottom: none;
                border-bottom: #000000 thin solid;
                font-weight: bold;
            }

            tr.marks-footer2 td:nth-child(3) {
                padding: 4px 7px;
                border-left: #000000 thin solid;
                border-top: none;
                border-bottom: #000000 thin solid;
                ;
            }

            tr.marks-footer2 td:nth-child(4) {
                border-right: #000000 thin solid;
                border-top: none;
                border-bottom: #000000 thin solid;
                font-weight: bold;
                font-size: 13px;
            }

            .example p {
                font-size: 12px;
                margin-bottom: 5px;
                text-align: left;
            }

            .header-1 p {
                margin: 0px !important;
            }

            .result-footer {
                width: 95%;
                margin-top: 20px;
                position: absolute;
                bottom: 60px;
                margin: auto;
                padding: 0px 0px;
            }

            .result-footer td:first-child {
                width: 140px;
                vertical-align: bottom;
            }

            .result-footer td:first-child p {
                margin: 0 0 10px;
            }

            .result-footer td {
                text-align: center;
                font-weight: bold;
            }

            @media print {
                .print_hide {
                    display: none !important;
                }

                .result {
                    width: auto !important;
                    height: 1500px !important;
                }

                .pagebreak {
                    page-break-before: always;
                }

                @page {
                    size: portrait
                }
            }

            body {
                margin: 0px !important;
            }

            .grecaptcha-badge {
                display: none !important;
            }

            tbody td {
                vertical-align: middle !important;
            }
        </style>
    </head>

    <body class="example" style="">
        <center>
            @if ($results->count() > 0)
                @include('ums.admin.notifications')

                <div class="row print_hide" style="padding: 30px;">
                    <br>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <input type="submit" onclick="print()" value="PRINT">
                        {{-- @if (Auth::guard('admin')->check() == true) --}}
                        {{-- @if (Request()->student == 'true') --}}
                        {{-- <a href="{{url('ums.result.semester_result')}}??id={{Request()->id}}&roll_number={{Request()->roll_number}}&student=false" style="background-color: green;color:#fff;padding:5px;text-decoration: none;">Admin View</a> --}}
                        {{-- @else --}}
                        <a href="{{ url('ums.result.semester_result') }}??id={{ Request()->id }}&roll_number={{ Request()->roll_number }}&student=true"
                            style="background-color: green;color:#fff;padding:5px;text-decoration: none;">Student
                            View</a>
                        {{-- @endif --}}
                        {{-- @endif --}}
                        {{-- Auth::guard('admin')->check() && (Auth::guard('admin')->user()->role==1 || Auth::guard('admin')->user()->role==4 --}}
                        @if (true)
                            <a href="{{ \Request::getRequestUri() }}&edit=true" class="btn btn-info">Edit Result</a>
                        @endif
                       
                    </div>
                    <div class="col-sm-4"></div>
                </div>
                <script>
                    function edit() {

                    }
                </script>
                {{-- Auth::guard('admin')->check() && --}}
                {{-- (Auth::guard('admin')->user()->role == 1 || Auth::guard('admin')->user()->role == 4) --}}
                @if (true)
                    @if (Request()->edit == 'true')
                        <!-- edit result section -->
                        <div class="container">
                            <form action="{{ \Request::getRequestUri() }}">
                                <div class="row">
                                    <div class="col-md-12 text-left">
                                        <h5>Edit Result</h5>
                                    </div>
                                    <div class="col-md-4 text-left">
                                        <br />
                                        <label for="">Edit Approval Date</label>
                                        @if ($result_single->approval_date)
                                            <input type="date" name="approval_date" class="form-control"
                                                value="{{ date('Y-m-d', strtotime($result_single->approval_date)) }}"
                                                required>
                                        @else
                                            <input type="date" name="approval_date" class="form-control" required>
                                        @endif
                                        <br />
                                        <label for="">Edit Exam Session</label>
                                        @if ($result_single->session_name)
                                            <input type="text" name="session_name" class="form-control"
                                                value="{{ $result_single->session_name }}" required>
                                        @else
                                            <input type="text" name="session_name" class="form-control" required>
                                        @endif
                                        <input type="text" name="cgpa" hidden class="form-control"
                                            value="{{ $result_single->cgpa }}" required>
                                        <br />
                                        <input type="hidden" name="id" value="{{ Request()->id }}">
                                        <input type="hidden" name="roll_number" value="{{ Request()->roll_number }}">
                                        <input type="submit" class="btn btn-success">
                                    </div>
                                    <div class="col-md-8 text-left">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br />
                    @endif
                @endif

                <!-- Result Section  -->
                <div class="result pagebreak" style="margin: auto;">
                    <table class="result-head" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width:74%;" rowspan="3">
                                @if ($result_single->course_id == 124)
                                    @php
                                        if (!$result_single->student) {
                                            dd('Student Not Found', $result_single);
                                        }
                                        $grand_total_obtained = 0;
                                        $grand_total_required = 0;
                                        foreach ($results as $result_row) {
                                            $grand_total_obtained +=
                                                ((int) $result_row->internal_marks) +
                                                ((int) $result_row->external_marks);
                                            $grand_total_required +=
                                                ((int) $result_row->max_internal_marks) +
                                                ((int) $result_row->max_external_marks);
                                        }

                                        // Generate QR text
                                        $qr_text =
                                            'Name: ' .
                                            $result_single->student->full_name .
                                            ' Enrollment No.: ' .
                                            $result_single->enrollment_no .
                                            ' Roll No.: ' .
                                            $result_single->roll_no .
                                            ' Marks: ' .
                                            $grand_total_obtained .
                                            '/' .
                                            $grand_total_required;
                                        $qr_content = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(
                                            $qr_text,
                                        );
                                    @endphp
                                    {!! $qr_content !!}
                                    @php
                                        // If you need another QR Code with CGPA
                                        $qr_text =
                                            'Name: ' .
                                            $result_single->student->full_name .
                                            ' Enrollment No.: ' .
                                            $result_single->enrollment_no .
                                            ' Roll No.: ' .
                                            $result_single->roll_no .
                                            ' CGPA: ' .
                                            number_format((float) $result_single->cgpa, 2, '.', '');
                                        $qr_content = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(
                                            $qr_text,
                                        );
                                    @endphp

                                    {!! $qr_content !!} <!-- This will display the second QR code with CGPA -->
                                @endif
                            </td>
                            <tr class="result-roll mb-4">
                                <th style="width:70px;">Serial No.</th>
                                <th style="width:20px;">:</th>
                                <th class="result-sr" style="width:100px;text-align: left;">
                                    {{ sprintf('%08d', $result_single->serial_no) }}
                                </th>
                            </tr>
                            
                            <tr class="result-roll mb-5">
                                <th>Roll No.</th>
                                <th style="width:20px;">:</th>
                                <th style="text-align: left;">{{ $result_single->roll_no }}</th>
                            </tr>
                            
                        <tr>
                            <th colspan="2">&nbsp;</th>
                        </tr>

                    </table>


                    <div class="header">
                        @if (Auth::guard('admin')->check() == false || Request()->student == 'true')
                            <img src="{{ asset('images/marklogo.png') }}" style="width:800px;">
                        @endif
                    </div>
                    <div class="header-1">
                        @if ($result_single->course_id == 124)
                            <p>Statement of Marks</p>
                        @else
                            <p>Statement of Grades</p>
                        @endif

                        <p class="course">{{ $result_single->course_description() }}</p>

                        <p>
                            @if ($result_single->semester_final == 1)
                                @if (str_contains($result_single->Semester->name, 'YEAR'))
                                    @if ($result_single->semester == 510)
                                        ANNUAL
                                    @else
                                        FINAL YEAR
                                    @endif
                                @else
                                    FINAL SEMESTER
                                @endif
                            @else
                                {{ $result_single->Semester->name }}
                            @endif
                            EXAMINATION, {{ $exam_session_details->session_name }}
                        </p>
                        <br />
                        <br />
                    </div>
                    <table class="head2" cellpadding="0" cellspacing="0">
                        <tr style="font-weight: bold;">
                            <td style="width: 80%;">
                                <table class="student-details" cellpadding="0" cellspacing="0">
                                    @if (
                                        $exam_session_details &&
                                            $exam_session_details->course &&
                                            $exam_session_details->course->campuse &&
                                            $exam_session_details->course->campuse->id > 1)
                                        <tr>
                                            <td style="font-weight: bold;">Name of the Institution</td>
                                            <th style="width:50px;"> :</th>
                                            <td><b>{{ $exam_session_details->course->campuse->name }}</b></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;"></td>
                                            <th style="width:50px;"> </th>
                                            <td></td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="font-weight: bold;">Enrollment No.</td>
                                        <th style="width:50px;"> :</th>
                                        <td><b>{{ $result_single->enrollment_no }}</b></td>
                                    </tr>
                                    <tr>
                                        <td style="width:200px;">Name of the Student</td>
                                        <th style="width:50px;"> :</th>
                                        <td style="font-weight:normal">
                                            {{ strtoupper($result_single->student->full_name) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Father’s Name</td>
                                        <th style="width:50px;"> :</th>
                                        <td style="font-weight:normal">
                                            {{ strtoupper($result_single->student->father_first_name) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Mother’s Name</td>
                                        <th style="width:50px;"> :</th>
                                        <td style="font-weight:normal">
                                            {{ strtoupper($result_single->student->mother_first_name) }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="text-align: right;">

                                <img src="{{ $result_single->student->photo }}"
                                    style="border: #afafaf thin solid;padding: 3px;margin-top: -40px;width: 130px;height: auto;"
                                    alt="">
                            </td>
                        </tr>

                    </table>
                    @if ($result_single->course_id == 124)
                        @include('student.result.semester-result-dpharma')
                    @else
                        <table class="marks" cellpadding="0" cellspacing="0">
                            <tr class="marks-head" style="text-align: center;">
                                <th rowspan="2" style="width:100px;">Paper Code</th>
                                <th style="width:350px;" rowspan="2">Title of the Paper</th>
                                <th rowspan="2" style="width:50px">Total Marks</th>
                                <th colspan="3">Marks Obtained</th>
                                <th rowspan="2" style="width:50px">Credits</th>
                                <th rowspan="2" style="width:50px">Grade Points</th>
                                <th rowspan="2"
                                    style="border-right-style:solid!important;border-right-width:thin!important;width:50px;">
                                    Letter Grade</th>
                            </tr>
                            <tr class="marks-head1" style="text-align: center;">
                                <th style="width:50px">Mid Sem</th>
                                <th style="width:50px">End Sem</th>
                                <th style="width:50px">Total</th>
                            </tr>
                            @foreach ($results as $result_row)
                                <tr class="marks-data" style="font-weight: bold">
                                    <td
                                        style="border: #000000 thin solid; padding: 0px 7px; border-top: none; border-bottom: none; border-right: none; vertical-align: middle; text-align:center; font-weight: bold;">
                                        {{ $result_row->subject_code }}</td>
                                    <td
                                        style="border-left:#000000 thin solid; padding: 0px 7px;vertical-align: middle; text-align:left;font-weight:normal;text-transform: uppercase;">
                                        {{ $result_row->subject_name }}</td>
                                    <td>{{ $result_row->max_total_marks }}</td>
                                    <td>{{ $result_row->internal_marks ? $result_row->internal_marks : '-' }}</td>
                                    <td>{{ $result_row->external_marks ? $result_row->external_marks : '-' }}</td>
                                    <td>{{ $result_row->total_marks }}</td>
                                    <td>{{ $result_row->credit }}</td>
                                    <td>{{ $result_row->grade_point }}</td>
                                    <td style="border-right: #000000 thin solid;">{{ $result_row->grade_letter }}</td>
                                </tr>
                            @endforeach

                            <tr class="marks-footer" style="font-weight: bold;">
                                <td>QP</td>
                                <td>{{ $result_single->qp }}</td>
                                <td colspan="4">CGPA</td>
                                <td class="text-center" colspan="3">
                                    {{ number_format((float) $result_single->cgpa, 2, '.', '') }}</td>
                            </tr>
                            <tr class="marks-footer1" style="font-weight: bold;">
                                <td>Total Credits</td>
                                <td>{{ $results->sum('credit') }}</td>
                                <td colspan="4">Equivalent Percentage</td>
                                @php $equivalent_percentage = ($result_single->cgpa*10); @endphp
                                <td class="text-center" colspan="3">
                                    {{ number_format((float) $equivalent_percentage, 1, '.', '') }}</td>
                            </tr>
                            <tr class="marks-footer2" style="font-weight: bold;">
                                <td>SGPA</td>
                                <td>{{ number_format((float) $result_single->sgpa, 2, '.', '') }}</td>
                                <td colspan="4">Result</td>
                                <td class="text-center" colspan="3">
                                    @if (
                                        $result_single->semester_final == 1 &&
                                            ($result_single->result_overall == 'FAILED' || $result_single->failed_semester_list_func() != ''))
                                        <span style="font-size: 16px">FAILED</span>
                                    @elseif($result_single->semester_final == 1 && $result_single->failed_semester_list_func() == '')
                                        <span style="font-size: 16px">PASSED</span>
                                    @elseif($result_single->semester_final == 0 && $result_single->failed_semester_list_func() != '')
                                        <span style="font-size: 16px">PCP</span>
                                    @elseif($result_single->semester_final == 0 && $result_single->failed_semester_list_func() == '')
                                        <span style="font-size: 16px">{{ $result_single->result_full }}</span>
                                    @endif
                                    <br />
                                    {{-- $result_single->failed_semester_list_func() --}}
                                </td>
                            </tr>
                        </table>
                        <strong class="sameFontSize">
                            <p style="font-weight: bold;">Conversion Formula : CGPA x 10</p>
                            <p style="font-weight: bold;">First Division with Distinction (CGPA) : 7.5 & Above, First
                                Division: 6.5 to 7.5, Second Division : 5.0 to 6.5, <br />Third Division: 4.0 to 5.0</p>
                            <p style="font-weight: bold;">PCP - Promoted with Carryover Papers</p>
                        </strong>
                    @endif


                    <br />
                    <table class="result-footer" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width: 20%;text-align: left;">
                                Date : {{ $result_single->approval_date ? $result_single->approval_date_latest() : '' }}
                                <br />
                                Place : Lucknow
                            </td>

                            {{-- @if (Auth::guard('admin')->check() == true && Request()->student != 'true') --}}
                            <td style="width: 20%;vertical-align: bottom;"><br>Prepared By</td>
                            <td style="width: 28%;vertical-align: bottom;"><br>Verified By</td>
                            <td style="width: 40%;vertical-align: bottom;text-align: right;font-size: 19px;">
                                {{-- <img src="{{asset('signatures/coe.png')}}" alt="" style="height:50px"/> --}}
                                <br>
                                Controller of Examination
                            </td>
                            {{-- @elseif(Request()->student == 'true' || Auth::guard('student')->check() == true) --}}
                            <td style="width: 25%;vertical-align: bottom;"></td>
                            <td style="width: 25%;vertical-align: bottom;"></td>
                            <td style="width: 25%;vertical-align: bottom;">
                                {{-- <img src="{{asset('signatures/coe.png')}}" alt="" style="height:50px"/> --}}
                                <br>
                                {{-- Controller of Examination --}}
                            </td>
                            {{-- @else
                @endif --}}
                        </tr>
                    </table>

                </div>
            @else
                <h4 class="text-center">Result Not Generated</h4>
            @endif

        </center>



    </body>

    </html>

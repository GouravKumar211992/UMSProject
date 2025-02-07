@extends('ums.student.student-meta')
@section('content')
    @php
        $student = Auth::guard('student')->user();
    @endphp

    <section class="content-body p-5">
        @include('ums.admin.notifications')
        <div class="container mt-2">

            <div class="card shadow-lg border-0 rounded-lg mb-4">
                <div class="card-body ">
                    <!-- Welcome Section -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h4 class="display-6 mb-2">
                                    <strong>Hello, </strong>
                                    {{ $student->name }}
                                </h4>
                                <p class="text-muted mb-0 lead">Nice to have you back, what an exciting day! Get ready and
                                    continue your lesson today.</p>
                            </div>
                           
                        </div>
                        @if ($student->exam_portal == 0)
                            <div class="text-end">
                                <p class="mb-0 text-muted">Showing results till <strong
                                        class="text-primary">{{ date('d M Y') }}</strong></p>
                            </div>
                        @endif
                    </div>

                    @include('ums.admin.notifications')

                    <!-- Student Profile Section -->
                    @if ($student->exam_portal == 0)
                        <div class="row g-4 align-items-center mb-0">
                            <!-- Student Card -->
                            <div class="col-lg p-2">
                                <div class="card bg-white p-1 border-0">
                                    <div class="card-body bg-white shadow p-4">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 col-12">
                                                <div class="text-center">
                                                    <img src="{{ asset('img/user.png') }}"
                                                        class="img-fluid rounded-3 shadow-sm" alt="Student ID Card"
                                                        style="max-width: 300px;">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12  ">
                                                <div class="ps-lg-4 " >
                                                    <div class="d-flex flex-column gap-3  " >
                                                        <h3 class="text-primary mb-0">{{ $student->enrollment_no }}</h3>
                                                        <h5 class="text-dark">Roll Number: {{ $student->roll_number }}</h5>
                                                        <h5 class="text-dark">Course:
                                                            {{ $student->enrollments->course->name }}</h5>
                                                        <p class="text-muted mb-3">
                                                            <i class="fas fa-university me-2"></i>
                                                            {{ $student->enrollments->course->campuse->name }}
                                                            ({{ $student->enrollments->course->campuse->campus_code }})
                                                        </p>
                                                        @if ($icard && $icard->father_mobile != null)
                                                            <a href="{{ route('view-icard', [$icard->id]) }}" target="_blank"
                                                                class="btn btn-primary">
                                                                <i class="fas fa-id-card me-2"></i>View ID Card
                                                            </a>
                                                        @else
                                                            <a hidden href="{{ route('icard-form') }}"
                                                                class="btn btn-primary">
                                                                <i class="fas fa-plus me-2"></i>ID Card Form
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Performance Chart -->
                            {{-- <div class="col-lg-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-transparent border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-0">Student Performance</h5>
                                                <p class="text-muted small mb-0">Monthly Progress</p>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">View Details</a></li>
                                                    <li><a class="dropdown-item" href="#">Download Report</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header border-0 pb-0">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h4 class="card-title float-none">Student Performence</h4>
                                                    <p class="f-14">Student Details </p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="mt-3 mb-0"> Showing results from <strong>{{date('d M Y')}}</strong> </p>
                                                    <!-- <div>
                                                        <span class="dot-seagreen f-12 mr-3"><i class="fa fa-circle"></i> Approved</span>
                                                        <span class="dot-grey f-12"><i class="fa fa-circle"></i> Pending</span>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div id="salechart" data-highcharts-chart="0" aria-hidden="false" role="region" aria-label=" . Highcharts interactive chart." style="overflow: hidden;"><div id="highcharts-screen-reader-region-before-0" aria-label="Chart screen reader information." role="region" aria-hidden="false" style="position: relative;"><div aria-hidden="false" style="position: absolute; width: 1px; height: 1px; overflow: hidden; white-space: nowrap; clip: rect(1px, 1px, 1px, 1px); margin-top: -3px; opacity: 0.01;"><div>Bar chart with 3 data series.</div><div><button id="hc-linkto-highcharts-data-table-0" tabindex="-1" aria-expanded="false">View as data table,  </button></div><div>The chart has 1 X axis displaying categories. </div><div>The chart has 1 Y axis displaying Student Details. Range: 0 to 12500.</div></div></div><div aria-hidden="false" style="position:relative" class="highcharts-announcer-container"><div aria-hidden="false" aria-live="polite" style="position: absolute; width: 1px; height: 1px; overflow: hidden; white-space: nowrap; clip: rect(1px, 1px, 1px, 1px); margin-top: -3px; opacity: 0.01;"></div><div aria-hidden="false" aria-live="assertive" style="position: absolute; width: 1px; height: 1px; overflow: hidden; white-space: nowrap; clip: rect(1px, 1px, 1px, 1px); margin-top: -3px; opacity: 0.01;"></div><div aria-hidden="false" aria-live="polite" style="position: absolute; width: 1px; height: 1px; overflow: hidden; white-space: nowrap; clip: rect(1px, 1px, 1px, 1px); margin-top: -3px; opacity: 0.01;"></div></div><div id="highcharts-yza81ae-0" dir="ltr" class="highcharts-container " style="position: relative; overflow: hidden; width: 858px; height: 400px; text-align: left; line-height: normal; z-index: 0; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); user-select: none;" aria-hidden="false" tabindex="0"><svg version="1.1" class="highcharts-root" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Arial, Helvetica, sans-serif;font-size:12px;" xmlns="http://www.w3.org/2000/svg" width="858" height="400" viewBox="0 0 858 400" aria-hidden="false" aria-label="Interactive chart"><desc aria-hidden="true">Created with Highcharts 9.0.0</desc><defs aria-hidden="true"><clipPath id="highcharts-yza81ae-1-"><rect x="0" y="0" width="772" height="317" fill="none"></rect></clipPath></defs><rect fill="#ffffff" class="highcharts-background" x="0" y="0" width="858" height="400" rx="0" ry="0" aria-hidden="true"></rect><rect fill="none" class="highcharts-plot-background" x="76" y="10" width="772" height="317" aria-hidden="true"></rect><g class="highcharts-grid highcharts-xaxis-grid" data-z-index="1" aria-hidden="true"><path fill="none" data-z-index="1" class="highcharts-grid-line" d="M 185.5 10 L 185.5 327" opacity="1"></path><path fill="none" data-z-index="1" class="highcharts-grid-line" d="M 296.5 10 L 296.5 327" opacity="1"></path><path fill="none" data-z-index="1" class="highcharts-grid-line" d="M 406.5 10 L 406.5 327" opacity="1"></path><path fill="none" data-z-index="1" class="highcharts-grid-line" d="M 516.5 10 L 516.5 327" opacity="1"></path><path fill="none" data-z-index="1" class="highcharts-grid-line" d="M 626.5 10 L 626.5 327" opacity="1"></path><path fill="none" data-z-index="1" class="highcharts-grid-line" d="M 737.5 10 L 737.5 327" opacity="1"></path><path fill="none" data-z-index="1" class="highcharts-grid-line" d="M 847.5 10 L 847.5 327" opacity="1"></path><path fill="none" data-z-index="1" class="highcharts-grid-line" d="M 75.5 10 L 75.5 327" opacity="1"></path></g><g class="highcharts-grid highcharts-yaxis-grid" data-z-index="1" aria-hidden="true"><path fill="none" stroke="#e6e6e6" stroke-width="1" data-z-index="1" class="highcharts-grid-line" d="M 76 327.5 L 848 327.5" opacity="1"></path><path fill="none" stroke="#e6e6e6" stroke-width="1" data-z-index="1" class="highcharts-grid-line" d="M 76 264.5 L 848 264.5" opacity="1"></path><path fill="none" stroke="#e6e6e6" stroke-width="1" data-z-index="1" class="highcharts-grid-line" d="M 76 200.5 L 848 200.5" opacity="1"></path><path fill="none" stroke="#e6e6e6" stroke-width="1" data-z-index="1" class="highcharts-grid-line" d="M 76 137.5 L 848 137.5" opacity="1"></path><path fill="none" stroke="#e6e6e6" stroke-width="1" data-z-index="1" class="highcharts-grid-line" d="M 76 73.5 L 848 73.5" opacity="1"></path><path fill="none" stroke="#e6e6e6" stroke-width="1" data-z-index="1" class="highcharts-grid-line" d="M 76 9.5 L 848 9.5" opacity="1"></path></g><rect fill="none" class="highcharts-plot-border" data-z-index="1" x="76" y="10" width="772" height="317" aria-hidden="true"></rect><g class="highcharts-axis highcharts-xaxis" data-z-index="2" aria-hidden="true"><path fill="none" class="highcharts-axis-line" stroke="#ccd6eb" stroke-width="1" data-z-index="7" d="M 76 327.5 L 848 327.5"></path></g><g class="highcharts-axis highcharts-yaxis" data-z-index="2" aria-hidden="true"><text x="23.2654972076416" data-z-index="7" text-anchor="middle" transform="translate(0,0) rotate(270 23.2654972076416 168.5)" class="highcharts-axis-title" style="color:#666666;fill:#666666;" y="168.5">Student Details</text><path fill="none" class="highcharts-axis-line" data-z-index="7" d="M 76 10 L 76 327"></path></g><path fill="none" class="highcharts-crosshair highcharts-crosshair-category undefined" data-z-index="2" stroke="rgba(204,214,235,0.25)" stroke-width="110.28571428571429" style="pointer-events:none;" visibility="hidden" d="M 130.5 10 L 130.5 327" aria-hidden="true"></path><g class="highcharts-series-group" data-z-index="3" aria-hidden="false"><g class="highcharts-series highcharts-series-0 highcharts-column-series highcharts-tracker" data-z-index="0.1" opacity="1" transform="translate(76,10) scale(1 1)" clip-path="url(#highcharts-yza81ae-1-)" aria-hidden="false" role="region" tabindex="-1" aria-label="Rejected, bar series 1 of 3 with 4 bars." style="outline: 0px;"><rect x="26" y="318" width="14" height="0" fill="rgb(201,209,216)" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="1. Jan, 4. Rejected." style="outline: 0px;"></rect><rect x="137" y="306" width="14" height="12" fill="#C9D1D8" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="2. Feb, 462. Rejected." style="outline: 0px;"></rect><rect x="247" y="283" width="14" height="35" fill="#C9D1D8" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="3. Mar, 1,376. Rejected." style="outline: 0px;"></rect><rect x="357" y="289" width="14" height="29" fill="#C9D1D8" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="4. Apr, 1,139. Rejected." style="outline: 0px;"></rect></g><g class="highcharts-markers highcharts-series-0 highcharts-column-series" data-z-index="0.1" opacity="1" transform="translate(76,10) scale(1 1)" aria-hidden="true" clip-path="none"></g><g class="highcharts-series highcharts-series-1 highcharts-column-series highcharts-tracker" data-z-index="0.1" opacity="1" transform="translate(76,10) scale(1 1)" clip-path="url(#highcharts-yza81ae-1-)" aria-hidden="false" role="region" tabindex="-1" aria-label="Approved, bar series 2 of 3 with 0 bars." style="outline: 0px;"></g><g class="highcharts-markers highcharts-series-1 highcharts-column-series" data-z-index="0.1" opacity="1" transform="translate(76,10) scale(1 1)" aria-hidden="true" clip-path="none"></g><g class="highcharts-series highcharts-series-2 highcharts-column-series highcharts-tracker" data-z-index="0.1" opacity="1" transform="translate(76,10) scale(1 1)" clip-path="url(#highcharts-yza81ae-1-)" aria-hidden="false" role="region" tabindex="-1" aria-label="Received, bar series 3 of 3 with 7 bars." style="outline: 0px;"><rect x="71" y="305" width="14" height="13" fill="rgb(37,218,230)" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="1. Jan, 507. Received." style="outline: 0px;"></rect><rect x="181" y="74" width="14" height="244" fill="#25DAE6" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="2. Feb, 9,619. Received." style="outline: 0px;"></rect><rect x="291" y="318" width="14" height="0" fill="#25DAE6" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="3. Mar, 4. Received." style="outline: 0px;"></rect><rect x="401" y="314" width="14" height="4" fill="#25DAE6" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="4. Apr, 155. Received." style="outline: 0px;"></rect><rect x="512" y="306" width="14" height="12" fill="#25DAE6" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="5. May, 464. Received." style="outline: 0px;"></rect><rect x="622" y="283" width="14" height="35" fill="#25DAE6" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="6. Jun, 1,376. Received." style="outline: 0px;"></rect><rect x="732" y="288" width="14" height="30" fill="#25DAE6" rx="8" ry="8" opacity="1" class="highcharts-point" tabindex="-1" role="img" aria-label="7. Jul, 1,200. Received." style="outline: 0px;"></rect></g><g class="highcharts-markers highcharts-series-2 highcharts-column-series" data-z-index="0.1" opacity="1" transform="translate(76,10) scale(1 1)" aria-hidden="true" clip-path="none"></g></g><text x="429" text-anchor="middle" class="highcharts-title" data-z-index="4" style="color:#333333;font-size:18px;fill:#333333;" y="24" aria-hidden="true"></text><text x="429" text-anchor="middle" class="highcharts-subtitle" data-z-index="4" style="color:#666666;fill:#666666;" y="24" aria-hidden="true"></text><text x="10" text-anchor="start" class="highcharts-caption" data-z-index="4" style="color:#666666;fill:#666666;" y="397" aria-hidden="true"></text><g class="highcharts-legend" data-z-index="7" transform="translate(290,360)" aria-hidden="true"><rect fill="none" class="highcharts-legend-box" rx="0" ry="0" x="0" y="0" width="277" height="25" visibility="visible"></rect><g data-z-index="1"><g><g class="highcharts-legend-item highcharts-column-series highcharts-color-undefined highcharts-series-0" data-z-index="1" transform="translate(8,3)"><text x="21" style="color:#333333;cursor:pointer;font-size:12px;font-weight:bold;fill:#333333;" text-anchor="start" data-z-index="2" y="15">Rejected</text><rect x="2" y="4" width="12" height="12" fill="#C9D1D8" rx="6" ry="6" class="highcharts-point" data-z-index="3"></rect></g><g class="highcharts-legend-item highcharts-column-series highcharts-color-undefined highcharts-series-1" data-z-index="1" transform="translate(98.9859619140625,3)"><text x="21" y="15" style="color:#333333;cursor:pointer;font-size:12px;font-weight:bold;fill:#333333;" text-anchor="start" data-z-index="2">Approved</text><rect x="2" y="4" width="12" height="12" fill="#63C276" rx="6" ry="6" class="highcharts-point" data-z-index="3"></rect></g><g class="highcharts-legend-item highcharts-column-series highcharts-color-undefined highcharts-series-2" data-z-index="1" transform="translate(195.93785095214844,3)"><text x="21" y="15" style="color:#333333;cursor:pointer;font-size:12px;font-weight:bold;fill:#333333;" text-anchor="start" data-z-index="2">Received</text><rect x="2" y="4" width="12" height="12" fill="#25DAE6" rx="6" ry="6" class="highcharts-point" data-z-index="3"></rect></g></g></g></g><g class="highcharts-axis-labels highcharts-xaxis-labels" data-z-index="7" aria-hidden="true"><text x="131.14285714285285" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="346" opacity="1">Jan</text><text x="241.42857142857284" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="346" opacity="1">Feb</text><text x="351.7142857142828" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="346" opacity="1">Mar</text><text x="462.0000000000028" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="346" opacity="1">Apr</text><text x="572.2857142857129" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="346" opacity="1">May</text><text x="682.5714285714329" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="346" opacity="1">Jun</text><text x="792.8571428571429" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="middle" transform="translate(0,0)" y="346" opacity="1">Jul</text></g><g class="highcharts-axis-labels highcharts-yaxis-labels" data-z-index="7" aria-hidden="true"><text x="61" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="end" transform="translate(0,0)" y="332" opacity="1">0</text><text x="61" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="end" transform="translate(0,0)" y="268" opacity="1">2.5k</text><text x="61" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="end" transform="translate(0,0)" y="205" opacity="1">5k</text><text x="61" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="end" transform="translate(0,0)" y="141" opacity="1">7.5k</text><text x="61" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="end" transform="translate(0,0)" y="78" opacity="1">10k</text><text x="61" style="color:#666666;cursor:default;font-size:11px;fill:#666666;" text-anchor="end" transform="translate(0,0)" y="15" opacity="1">12.5k</text></g><g class="highcharts-label highcharts-tooltip highcharts-color-undefined" style="cursor:default;white-space:nowrap;pointer-events:none;" data-z-index="8" transform="translate(115,-9999)" opacity="0" visibility="hidden" aria-hidden="true"><path fill="none" class="highcharts-label-box highcharts-tooltip-box highcharts-shadow" d="M 3.5 0.5 L 124.5 0.5 C 127.5 0.5 127.5 0.5 127.5 3.5 L 127.5 100.5 C 127.5 103.5 127.5 103.5 124.5 103.5 L 3.5 103.5 C 0.5 103.5 0.5 103.5 0.5 100.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" stroke="#000000" stroke-opacity="0.049999999999999996" stroke-width="5" transform="translate(1, 1)"></path><path fill="none" class="highcharts-label-box highcharts-tooltip-box highcharts-shadow" d="M 3.5 0.5 L 124.5 0.5 C 127.5 0.5 127.5 0.5 127.5 3.5 L 127.5 100.5 C 127.5 103.5 127.5 103.5 124.5 103.5 L 3.5 103.5 C 0.5 103.5 0.5 103.5 0.5 100.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" stroke="#000000" stroke-opacity="0.09999999999999999" stroke-width="3" transform="translate(1, 1)"></path><path fill="none" class="highcharts-label-box highcharts-tooltip-box highcharts-shadow" d="M 3.5 0.5 L 124.5 0.5 C 127.5 0.5 127.5 0.5 127.5 3.5 L 127.5 100.5 C 127.5 103.5 127.5 103.5 124.5 103.5 L 3.5 103.5 C 0.5 103.5 0.5 103.5 0.5 100.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" stroke="#000000" stroke-opacity="0.15" stroke-width="1" transform="translate(1, 1)"></path><path fill="rgba(247,247,247,0.85)" class="highcharts-label-box highcharts-tooltip-box" d="M 3.5 0.5 L 124.5 0.5 C 127.5 0.5 127.5 0.5 127.5 3.5 L 127.5 100.5 C 127.5 103.5 127.5 103.5 124.5 103.5 L 3.5 103.5 C 0.5 103.5 0.5 103.5 0.5 100.5 L 0.5 3.5 C 0.5 0.5 0.5 0.5 3.5 0.5" stroke="#C9D1D8" stroke-width="1"></path></g></svg><div class="highcharts-a11y-proxy-container" aria-hidden="false"><div aria-label="Toggle series visibility" role="region" aria-hidden="false"><button aria-label="Show Rejected" tabindex="-1" aria-pressed="true" class="highcharts-a11y-proxy-button" aria-hidden="false" style="border-width: 0px; background-color: transparent; cursor: pointer; outline: none; opacity: 0.001; z-index: 999; overflow: hidden; padding: 0px; margin: 0px; display: block; position: absolute; width: 68.9857px; height: 13.7273px; left: 299.999px; top: 366.999px; visibility: visible;"></button><button aria-label="Show Approved" tabindex="-1" aria-pressed="true" class="highcharts-a11y-proxy-button" aria-hidden="false" style="border-width: 0px; background-color: transparent; cursor: pointer; outline: none; opacity: 0.001; z-index: 999; overflow: hidden; padding: 0px; margin: 0px; display: block; position: absolute; width: 74.9517px; height: 13.7273px; left: 390.985px; top: 366.999px; visibility: visible;"></button><button aria-label="Show Received" tabindex="-1" aria-pressed="true" class="highcharts-a11y-proxy-button" aria-hidden="false" style="border-width: 0px; background-color: transparent; cursor: pointer; outline: none; opacity: 0.001; z-index: 999; overflow: hidden; padding: 0px; margin: 0px; display: block; position: absolute; width: 71.6562px; height: 13.7273px; left: 487.936px; top: 366.999px; visibility: visible;"></button></div></div><div class="highcharts-label highcharts-tooltip highcharts-color-undefined" aria-hidden="true" style="position: absolute; left: 115px; top: -9999px; opacity: 0; cursor: default; pointer-events: none; visibility: hidden;"><span data-z-index="1" style="position: absolute; font-family: &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Arial, Helvetica, sans-serif; font-size: 12px; white-space: nowrap; color: rgb(51, 51, 51); margin-left: 0px; margin-top: 0px; left: 8px; top: 8px;"><span style="font-size:10px">Jan</span><table><tbody><tr><td style="color:#C9D1D8;padding:0">Rejected: </td><td style="padding:0"><b>4.0 %</b></td></tr><tr><td style="color:#25DAE6;padding:0">Received: </td><td style="padding:0"><b>507.0 %</b></td></tr></tbody></table></span></div></div><div id="highcharts-screen-reader-region-after-0" aria-label="" aria-hidden="false" style="position: relative;"><div aria-hidden="false" style="position: absolute; width: 1px; height: 1px; overflow: hidden; white-space: nowrap; clip: rect(1px, 1px, 1px, 1px); margin-top: -3px; opacity: 0.01;"><div id="highcharts-end-of-chart-marker-0">End of interactive chart.</div></div></div><div class="highcharts-exit-anchor" tabindex="0" aria-hidden="false"></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

   
   
            {{-- <div class="col-md" >
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h5>Student Performance</h5>
                        <div class="btn-group float-end">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Select Year
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="updateChartData('2023')">2023</a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateChartData('2022')">2022</a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateChartData('2021')">2021</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="performanceChart"></div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Scripts -->
              </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <script>
        // Example data for student performance in various years
        const performanceData = {
            '2023': {
                categories: ['Math', 'Science', 'English', 'History'],
                data: [85, 90, 88, 75]
            },
            '2022': {
                categories: ['Math', 'Science', 'English', 'History'],
                data: [78, 80, 85, 70]
            },
            '2021': {
                categories: ['Math', 'Science', 'English', 'History'],
                data: [82, 88, 90, 80]
            }
        };

        // Initial chart configuration
        Highcharts.chart('performanceChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Student Performance'
            },
            xAxis: {
                categories: performanceData['2023'].categories,
                title: {
                    text: 'Subjects'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Scores'
                }
            },
            series: [{
                name: '2023',
                data: performanceData['2023'].data
            }]
        });

        // Update chart data based on dropdown selection
        function updateChartData(year) {
            Highcharts.chart('performanceChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Student Performance (' + year + ')'
                },
                xAxis: {
                    categories: performanceData[year].categories,
                    title: {
                        text: 'Subjects'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Scores'
                    }
                },
                series: [{
                    name: year,
                    data: performanceData[year].data
                }]
            });
        }
       
    </script>
              
@endsection

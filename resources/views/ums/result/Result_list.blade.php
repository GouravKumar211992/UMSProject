@extends('ums.admin.admin-meta')
@section('content')
    <!-- BEGIN: Body-->

    {{-- <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Result List</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Showing 1 to 10 of 0 category</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-12 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right d-flex flex-wrap justify-content-start">
                        <button class="btn btn-primary btn-md mt-1 mb-1 me-2" data-bs-toggle="modal"
                            data-bs-target="#filterModal">
                            <i data-feather="filter"></i> Filter
                        </button>
                        <a class="btn btn-secondary btn-md mt-1 mb-1 me-2" data-bs-toggle="modal"
                            data-bs-target="#searchModal">
                            <i data-feather="search"></i> Search
                        </a>
                        <a class="btn btn-warning box-shadow-2 btn-md mt-1 mb-1 me-2" onclick="window.location.reload();">
                            <i data-feather="refresh-cw"></i> Reset
                        </a>
                        <a href="{{ url('admin/semester-result-bulk') }}" class="btn btn-warning btn-md mt-1 mb-1 me-1">
                            Reset Bulk Download
                        </a>
                        <a href="{{ url('admin/nursing-result-bulk') }}" class="btn btn-primary btn-md mt-1 mb-1 me-2">
                            Nursing Bulk Download
                        </a>
                        <a href="{{ url('mbbs_result') }}" class="btn btn-warning btn-md mt-1 mb-1 me-2">
                            MBBS Results
                        </a>
                        <a href="{{ url('md_marksheet_list') }}" class="btn btn-dark btn-md mt-1 mb-1 me-2">
                            MD Results
                        </a>
                    </div>
                    <div class="form-group breadcrumb-right d-flex flex-wrap justify-content-start">
                        <a href="{{ url('regular_tr_generate') }}" class="btn btn-primary btn-md mt-1 mb-1 me-2">
                            Universty TR Report
                        </a>
                        <a href="{{ url('admin/internal-submit') }}" class="btn btn-warning btn-md mt-1 mb-1 me-2">
                            Internal/External/Practice Mark Submit
                        </a>
                        <a href="{{ route('tabulationChart') }}" class="btn btn-dark btn-md mt-1 mb-1 me-2">
                            Tabulation Chart Report
                        </a>
                    </div>
                </div>

            </div>

            <div class="content-body">

                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">


                                <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                        <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>Enrollment No.</th>
                                                <th>Roll No.</th>
                                                <th>Course</th>
                                                <th>Semester</th>
                                                <th>Academic Session</th>
                                                <th>Student Name</th>
                                                <th>Mobile Number</th>
                                                <th>Result Status</th>
                                                <th>Result Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results as $index => $result)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $result->enrollment_no }}</td>
                                                    <td>{{ $result->roll_no }}</td>
                                                    <td>{{ $result->semester_details && $result->semester_details->course ? $result->semester_details->course->name : '-' }}
                                                    </td>
                                                    <td>{{ $result->semester_details ? $result->semester_details->name : '-' }}
                                                    </td>
                                                    <td>{{ $result->exam_session }}</td>
                                                    <td>{{ $result->student ? $result->student->full_name : '-' }}</td>
                                                    <td>{{ $result->student ? $result->student->mobile : '-' }}</td>
                                                    <td>{{ $result->status_text }}</td>
                                                    <td>{{ $result->back_status_text }}</td>
                                                    <td class="tableactionnew">
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn btn-sm dropdown-toggle hide-arrow p-0 "
                                                                data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item"
                                                                    href="{{ url('semester_result/?id=' . @$result->semester_details->id) }}&roll_number={{ base64_encode($result->roll_no) }}"
                                                                    class="btn-sm btn-success">View</a>
                                                                <a class="dropdown-item"
                                                                    href="{{ url('one-view-result') }}/{{ base64_encode($result->roll_no) }}"
                                                                    class="btn-sm btn-info" >One
                                                                    View</a>
                                                                <a class="dropdown-item"
                                                                    href="{{ url('admin/get-single-result') }}/{{ $result->roll_no }}?semester={{ $result->semester }}"
                                                                    class="btn-sm btn-warning">Edit Record</a>

                                                                <a class="dropdown-item"
                                                                    href="{{ url('cgpa-update') }}?roll_no={{ $result->roll_no }}"
                                                                    class="btn-sm btn-dark">Update CGPA</a>

                                                                <a class="dropdown-item"
                                                                    href="{{ url('ResultSerialNoUpdate') }}?roll_no={{ $result->roll_no }}"
                                                                    class="btn-sm btn-dark">Update Serial Number</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Search Modal -->
                    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="searchModalLabel">Search by Roll Number</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Roll Number Input Form -->
                                    <form id="searchForm" method="GET" action="{{ url('result_list') }}">
                                        <div class="mb-3">
                                            <label for="rollNumber" class="form-label">Enter Roll Number</label>
                                            <input type="text" class="form-control" id="rollNumber" name="search"
                                                placeholder="Enter Roll Number" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Filters Modal -->
                    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header  text-white border-0">
                                    <h5 class="modal-title" id="filterModalLabel">
                                        <i class="bi bi-funnel me-2"></i>Apply Filters
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body p-4">
                                    <form method="GET" action="{{ url('result_list') }}" class="needs-validation"
                                        novalidate>
                                        <div class="row g-3">
                                            <!-- Roll Number Field -->
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">
                                                    <i class="bi bi-person-badge me-1"></i>Roll Number
                                                </label>
                                                <div class="input-group">
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ Request::get('name') }}"
                                                        placeholder="Enter roll number">
                                                </div>
                                            </div>

                                            <!-- Campus Selection -->
                                            <div class="col-md-6">
                                                <label for="campus" class="form-label">
                                                    <i class="bi bi-building me-1"></i>Campus
                                                </label>
                                                <select class="form-select" id="campus" name="campus">
                                                    <option value="" selected disabled>Choose campus</option>
                                                    @foreach ($campuselist as $campus)
                                                        <option value="{{ $campus->id }}"
                                                            {{ Request::get('campus') == $campus->id ? 'selected' : '' }}>
                                                            {{ $campus->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Programme Selection -->
                                            <div class="col-md-6">
                                                <label for="course_id" class="form-label">
                                                    <i class="bi bi-book me-1"></i>Programme
                                                </label>
                                                <select class="form-select" id="course_id" name="course_id">
                                                    <option value="" selected disabled>Select programme</option>
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}"
                                                            {{ Request::get('course_id') == $course->id ? 'selected' : '' }}>
                                                            {{ $course->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Semester Selection -->
                                            <div class="col-md-6">
                                                <label for="semester" class="form-label">
                                                    <i class="bi bi-calendar3 me-1"></i>Semester
                                                </label>
                                                <select class="form-select" id="semester" name="semester">
                                                    <option value="" selected disabled>Choose semester</option>
                                                    @foreach ($semesterlist as $semester)
                                                        <option value="{{ $semester->name }}"
                                                            {{ Request::get('semester') == $semester->name ? 'selected' : '' }}>
                                                            {{ $semester->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-4 pt-2 border-top">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-1"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-funnel me-1"></i>Apply Filters
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                    {{-- <div class="modal modal-slide-in fade" id="modals-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="add-new-record modal-content pt-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                        <input type="text" class="form-control dt-full-name"
                                            id="basic-icon-default-fullname" placeholder="John Doe"
                                            aria-label="John Doe" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-post">Post</label>
                                        <input type="text" id="basic-icon-default-post" class="form-control dt-post"
                                            placeholder="Web Developer" aria-label="Web Developer" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-email">Email</label>
                                        <input type="text" id="basic-icon-default-email" class="form-control dt-email"
                                            placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
                                        <small class="form-text"> You can use letters, numbers & periods </small>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                                        <input type="text" class="form-control dt-date" id="basic-icon-default-date"
                                            placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="basic-icon-default-salary">Salary</label>
                                        <input type="text" id="basic-icon-default-salary"
                                            class="form-control dt-salary" placeholder="$12000" aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div> --}}
                </section>


            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

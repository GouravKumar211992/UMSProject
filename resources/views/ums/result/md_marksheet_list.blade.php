@extends('ums.admin.admin-meta')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">MD Result List</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">MD Result List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-12 mb-50 mb-sm-0">
                    <!-- Move the Bulk Reset Button to the Top Right -->
                    <div class="form-group breadcrumb-right d-flex flex-wrap justify-content-end" style="margin-top: -60px ">
                        <a class="btn btn-secondary btn-md mt-1 mb-1 me-2" data-bs-toggle="modal"
                        data-bs-target="#searchModal">
                        <i data-feather="search"></i> Search
                    </a>
                        <a href="{{ url('md_marksheet') }}" class="btn btn-primary btn-md mt-1 mb-1 me-1">
                            <i class="fas fa-undo"></i> Bulk Reset
                        </a>
                    </div>
                </div>
            </div>
            <div class="content-body" style="margin-top: 80px">
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
                                                <th>Semester</th>
                                                <th>Academic Year</th>
                                                <th>Student Name</th>
                                                <th>Mobile Number</th>
                                                <th>Result Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($results as $index=>$result)
                                                <tr>  
                                                    <td>{{++$index}}</td>
                                                    <td>{{$result->enrollment_no}}</td>
                                                    <td>{{$result->roll_no}}</td>
                                                    <td>{{($result->semesters)?$result->semesters->name:'-'}}</td>
                                                    <td>{{$result->exam_session}}</td>
                                                    <td>{{($result->student)?$result->student->name:'-'}}</td>
                                                    <td>{{($result->student)?$result->student->mobile:'-'}}</td>
                                                    <td>{{$result->status_text}}</td>
                                                    <td>
                                                        @php
                                                        $result_query_string = [
                                                            'course_id' => $result->course_id,
                                                            'semester_id' => $result->semester,
                                                            'academic_session' => $result->exam_session,
                                                            'roll_no' => $result->roll_no,
                                                        ];
                                                        $result_query_string = base64_encode(serialize($result_query_string));
                                                        @endphp
                                                        <a target="_blank" href="{{url('md_marksheet')}}?result_query_string={{$result_query_string}}" class="btn-sm btn-success">View</a>
                                                    </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel">Search by Roll Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Form -->
                <form method="GET" action="{{ url('md_marksheet_list') }}">
                    <div class="mb-3">
                        <label for="rollNumber" class="form-label">Enter Roll Number</label>
                        <input type="text" class="form-control" id="rollNumber" name="search" placeholder="Enter Roll Number" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- END: Content-->
@endsection

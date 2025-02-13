@extends('ums.admin.admin-meta')

    <!-- Modal -->
   

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Students</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">List of Students</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#searchModal"
                            data-bs-toggle="modal"><i data-feather="search"></i> Search</button>
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter"
                            data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button>
                        <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                        <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50"
                            onClick="window.location.reload()"><i data-feather="refresh-cw"></i> Reset</button>
                    </div>
                </div>
            </div>
            <div class="content-body">
                 
                @include('ums.admin.notifications')

                {{-- <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">


                                <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                        <thead>
                                            <tr>
                                                <th>SN#</th>
                                                <th>Roll No</th>
                                                <th>Course</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>DOB</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                      
                                            @if (count($students) > 0)
                                                @foreach ($students as $index => $student)
                                        <tbody>
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $student->roll_number }}</td>
                                                <td>{{ $student->enrollments->course->name }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>{{ $student->mobile }}</td>
                                                <td>{{ $student->dob }}</td>
                                                <td class="tableactionnew">
                                                    <div class="dropdown">
                                                        <button type="button"
                                                            class="btn btn-sm dropdown-toggle hide-arrow p-0 "
                                                            data-bs-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{ url('admin_list_edit') }}">
                                                                <i data-feather="edit" class="me-50"></i>
                                                                <span>Edit</span>
                                                            </a>
                                                          
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                    <div class="modal modal-slide-in fade" id="modals-slide-in">
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
                                            id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe" />
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
                                        <input type="text" id="basic-icon-default-salary" class="form-control dt-salary"
                                            placeholder="$12000" aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section> --}}
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                        <thead>
                                            <tr>
                                                <th>SN#</th>
                                                <th>Roll No</th>
                                                <th>Course</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>DOB</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($students) > 0)
                                                @foreach ($students as $index => $student)
                                                    <tr>
                                                        <td>{{ ++$index }}</td>
                                                        <td>{{ $student->roll_number }}</td>
                                                        <td>{{ $student->enrollments->course->name }}</td>
                                                        <td>{{ $student->name }}</td>
                                                        <td>{{ $student->email }}</td>
                                                        <td>{{ $student->mobile }}</td>
                                                        <td>{{date('d-m-Y', strtotime($student->date_of_birth))}}</td>
                                                        <td class="tableactionnew">
                                                            <div class="dropdown">
                                                                <button type="button"
                                                                    class="btn btn-sm dropdown-toggle hide-arrow p-0"
                                                                    data-bs-toggle="dropdown">
                                                                    <i data-feather="more-vertical"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#infoModal{{$student->id}}">
                                                                        <i data-feather="edit" class="me-50"></i>
                                                                        <span>Change Email</span>
                                                                    </a>
                                                                    <a class="dropdown-item"  href="{{url('student-secret-login/'.$student->id)}}">
                                                                        <i data-feather="home" class="me-50"></i>
                                                                        <span>Dashboard</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center">NO DATA FOUND</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                 {{-- show email change modal  --}}
                 {{-- <div class="modal fade" id="infoModal{{$student->id}}" tabindex="-1" aria-labelledby="infoModalLabel{{$student->id}}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" >
                            <div class="modal-header" style="background-color: rgb(99, 22, 208)" >
                                <h5 class="modal-title text-white" id="infoModalLabel{{$student->id}}" > User Information</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Static Data in Header -->
                                <div class="mb-3">
                                    <p><strong>Roll No:</strong>{{$student->roll_number}} </p>
                                    <p><strong>Enrollment No:</strong>{{ $student->enrollment_no}} </p>
                                    <p><strong>First Name:</strong>{{ $student->name }} </p>
                                </div>
            
                                <!-- Editable Fields (Email & Password) -->
                                <form  method="POST" action="{{url('/student/save-email')}}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email{{$student->id}}" value="{{ $student->email }}" name="email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password{{$student->id}}" placeholder="Enter password" name="password">
                                    </div>
                               
                            </div>
                            <div class="modal-footer">
                                <input hidden type="text" name="id" value="{{$student->id}}">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal{{$student->id}}">Close</button>
                                <button type="submit"  class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div> --}}
                @foreach ($students as $index => $student)
                <div class="modal fade" id="infoModal{{$student->id}}" tabindex="-1" aria-labelledby="infoModalLabel{{$student->id}}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: rgb(99, 22, 208)">
                                <h5 class="modal-title text-white" id="infoModalLabel{{$student->id}}">User Information</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Static Data in Header -->
                                <div class="mb-3">
                                    <p><strong>Roll No:</strong> {{$student->roll_number}} </p>
                                    <p><strong>Enrollment No:</strong> {{ $student->enrollment_no}} </p>
                                    <p><strong>First Name:</strong> {{ $student->name}} </p>
                                </div>
                
                                <!-- Editable Fields (Email & Password) -->
                                <form method="POST" action="{{url('/student/save-email')}}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email{{$student->id}}" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email{{$student->id}}" value="{{ $student->email }}" name="email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password{{$student->id}}" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password{{$student->id}}" placeholder="Enter password" name="password">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <input hidden type="text" name="id" value="{{$student->id}}">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
                


            
                    <!-- Modal to add new record -->
                    <div class="modal modal-slide-in fade" id="modals-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="add-new-record modal-content pt-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                        <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname"
                                               placeholder="John Doe" aria-label="John Doe" />
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
                                        <input type="text" id="basic-icon-default-salary" class="form-control dt-salary"
                                               placeholder="$12000" aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    {{-- @include('footer') --}}
    <!-- END: Footer-->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form class="needs-validation" autocomplete="nope" novalidate>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search field in a single column -->
                    <div class="mb-3">
                        <label for="searchInput" class="form-label">Search</label>
                        <input type="text" class="form-control searchInput" id="input_keyword"
                            name="search" autocomplete="nope" placeholder="Type here..."
                            maxlength="26">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="searchBtn">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>


    <div class="modal fade" id="reallocate" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
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
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection
<script>
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
       <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</script>

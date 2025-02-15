{{-- @extends("admin.admin-meta")
@section("content") --}}


@extends('ums.master.faculty.faculty-meta')
<!-- END: Head-->

<!-- BEGIN: Body-->
 @section('content')

    <!-- BEGIN: Content-->
    <form method="get" href="{{ url('show_Attendance') }}" name="search-attendence" onsubmit="return validateForm()">
        
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h3 class="content-header-title float-start mb-0">Attendance Sheet</h3>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Home</a></li>  
                                    <li class="breadcrumb-item active">SHEET OF STUDENTS</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                        
                        <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                        <input  type="submit" class="btn  btn-dark  btn-sm mb-50 mb-sm-0" >
                        {{-- <i value="Print" onclick="print()" data-feather="check-circle"></i> Print --}}
                      
                    </div>
                </div>
                
            </div>
            <div class="content-body">
                <form id="rollNumberForm">
                    <div class="row">
                        <div class="col-md-7 mt-2 mb-2">
                            <!-- Session and Date fields in the same row -->
                            <div class="row align-items-center mb-1">
                                <div class="col-md-2">
                                    <label class="form-label">Session:<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-5"> 
                                    <select id="session" name="session" class="form-control">
                                        <option value="">---Select---</option>
                                        @foreach($sessionsList as $key => $session)
                                    <option value="{{ $session }}">{{ $session }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Date (Optional):</label>
                                </div>
                                <div class="col-md-3"> 
                                    <input type="date" name="date_of_attendence"  class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table id="attendanceTable" class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Roll No</th>
                                                <th>Registration Number</th>
                                                <th>Student Name</th>
                                                <th>Attendance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($searchAttendence && $searchAttendence->count())
                                                @foreach($searchAttendence as $key => $attendance)
                                                    <tr>
                                                        <th>{{ $key + 1 }}</th>
                                                        <th>{{ $attendance->roll_no }}</th>
                                                        <th>{{ $attendance->enrollment_no }}</th>
                                                        <th>{{ $attendance->students_name }}</th>
                                                        <th>{{ $attendance->attendence_status }}</th>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">No Data Found</td>
                                                </tr>
                                            @endif
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
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    


    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
        <div class="modal-dialog sidebar-sm">
            <form class="add-new-record modal-content pt-0">
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="mb-1">
                        <label class="form-label" for="fp-range">Select Date</label>
                        <!--                        <input type="text" id="fp-default" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />-->
                        <input type="text" id="fp-range" class="form-control flatpickr-range bg-white"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                    </div>

                    <div class="mb-1">
                        <label class="form-label">PO No.</label>
                        <select class="form-select">
                            <option>Select</option>
                        </select>
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Vendor Name</label>
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
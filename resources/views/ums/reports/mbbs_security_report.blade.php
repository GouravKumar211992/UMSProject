
@extends('ums.admin.admin-meta')

@section('content')
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col=""> --}}

  

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">MBBS Security Report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row" style="margin-top: -30px">
                        <form id="dateform" method="GET" action="{{ route('mbbs_security_report') }}">
                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <div class="form-group breadcrumb-right">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i data-feather="clipboard"></i> Show Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row align-items-center mb-1">
                                <div class="col-md-4">
                                    <label class="form-label">From Date:<span class="text-danger m-0">*</span></label>
                                    <input type="date" name="from" class="form-control" value="{{ request()->from }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">To Date:<span class="text-danger m-0">*</span></label>
                                    <input type="date" name="to" class="form-control" value="{{ request()->to }}" required>
                                </div>
                            </div>
                        </form>
                        
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table
                                        class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome">
                                        <thead>
                                            <tr>
                                                <th>S.NO</th>
                                                <th>Campus </th>
                                                <th>COURSE</th>
                                                <th>SEMESTER</th>
                                                <th>Roll Number</th>
                                                <th>Enrollment No</th>
                                                <th>Batch</th>
                                                <th>Name</th>
                                                <th>Father</th>
                                                <th>Mother</th>
                                                <th>Aadhar</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Sub_code</th>
                                                <th>Bank_name</th>
                                               <th>Branch_name</th>
                                               <th>Challan_reciept_date</th>
                                               <th>Amount</th>
                                               <th>Bank_ifsc_code</th>
                                               <th>Challan</th>
                                               <th>txn_status</th>
                                               <th>order_id</th>
                                               <th>Payment_mode</th>
                                               <th>Created_at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $index=>$row)
                                            <tr>
                                                <td>{{++$index}}</td>
                                                <td>{{$row->course->campuse->name}}</td>
                                                <td>{{$row->course->name}}</td>
                                                <td>{{$row->semester->name}}</td>
                                                <td>{{$row->roll_no}}</td>
                                                <td>{{$row->enrollment_no}}</td>
                                                <td>{{$row->batch}}</td>
                                                <td>{{$row->student_name}}</td>
                                                <td>{{$row->father}}</td>
                                                <td>{{$row->mother}}</td>
                                                <td>{{$row->aadhar}}</td>
                                                <td>{{$row->mobile}}</td>
                                                <td>{{$row->email}}</td>
                                                <td>{{$row->sub_code}}</td>
                                                <td>{{$row->bank_name}}</td>
                                                <td>{{$row->branch_name}}</td>
                                                <td>{{$row->challan_reciept_date}}</td>
                                                <td>{{$row->amount}}</td>
                                                <td>{{$row->bank_ifsc_code}}</td>
                                                {{-- <td>{{$row->challan}}</td> --}}
                                                <td>{{$row->challan_number}}</td>
                                                <td>{{$row->txn_status}}</td>
                                                <td>{{$row->order_id}}</td>
                                                <td>{{$row->payment_mode}}</td>
                                                <td>{{$row->created_at}}</td>
                                            </tr>
                                            @endforeach
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
                                            id="basic-icon-default-fullname" placeholder="John Doe"
                                            aria-label="John Doe" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-post">Post</label>
                                        <input type="text" id="basic-icon-default-post"
                                            class="form-control dt-post" placeholder="Web Developer"
                                            aria-label="Web Developer" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-email">Email</label>
                                        <input type="text" id="basic-icon-default-email"
                                            class="form-control dt-email" placeholder="john.doe@example.com"
                                            aria-label="john.doe@example.com" />
                                        <small class="form-text"> You can use letters, numbers & periods </small>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                                        <input type="text" class="form-control dt-date"
                                            id="basic-icon-default-date" placeholder="MM/DD/YYYY"
                                            aria-label="MM/DD/YYYY" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="basic-icon-default-salary">Salary</label>
                                        <input type="text" id="basic-icon-default-salary"
                                            class="form-control dt-salary" placeholder="$12000"
                                            aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
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
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a
                    class="ml-25" href="#" target="_blank">Presence 360</a><span
                    class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>

        <div class="footerplogo"><img src="../../../assets/css/p-logo.png" /></div>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


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


{{-- </body> --}}
@endsection


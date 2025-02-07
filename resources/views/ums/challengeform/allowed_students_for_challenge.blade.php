@extends('ums.admin.admin-meta')
@section("content")
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    {{-- @include('header') --}}

    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    {{-- @include('sidebar') --}}
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Challenge Allowed</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Report List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                    <form action="{{route('challenge_allowed_create')}} "id="rollNumberForm" method="post">
                        @csrf
                    <div class="row">
                        <div class="col-md-5 mt-2 mb-2">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Roll Number:<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-7">
                                    <input id="rollNumberInput"  type="text" name="roll_no"class="form-control" required>
                                </div>
                            </div>
                        </div>
                
                        <div class="col-md-5 mt-2 mb-2">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-2">
                                    <label class="form-label">Step:<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="step" class="form-control" required>
                                        <option value="">Selected</option>
                                        <option value="1">First Step</option>
                                        <option value="2">Second Step</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-sm mb-50 mb-sm-0 w-100">
                                        <i data-feather="plus"></i> <span class="d-inline">Add Roll Number</span>
                                    </button>
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
                                    <table
                                        class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome">
                                        <thead>
                                            <tr>
                                                <th>ID#</th>
                                                <th>Roll Number</th>
                                                <th>Student Name</th>
                                                <th>Step</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($challenges->count()>0)
                                            @foreach($challenges as $index=>$challenge)
                                            <tr>
                                                <td>{{++$index}}</td>
                                                <td><input type="hidden" name="roll_no" value="{{$challenge->roll_no}}">{{$challenge->roll_no}}</td>
                                                <td>{{$challenge->student->full_name}}</td>
                                                <td>
                                                    <select class="form-control" onchange="$(this).closest('tr').find('.step_selection').val($(this).val());">
                                                        <option value="1"  @if($challenge->step==1) selected @endif>First Step</option>
                                                        <option value="2"  @if($challenge->step==2) selected @endif>Second Step</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <form style="display: inline;margin-right: 15px;float: left;" action="{{route('challenge_allowed_edit',[$challenge->roll_no])}}" method="post">
                                                    @csrf
                                                        <input type="hidden" name="step_value" class="step_selection" value="{{$challenge->step}}">
                                                        <button type="submit" class="btn-sm btn-info">Save</button>
                                                    </form>
                                                    {{-- <a onclick="return confirm('Are you sure?')" style="float: left;" class="btn-sm btn-danger" action="{{route('challenge-allowed-delete'.$challenge->roll_no)}}">Delete</a> --}}
                                                    <a href="javascript:void(0);" onclick="if(confirm('Are you sure?')) document.getElementById('delete-form').submit();" class="btn-sm btn-danger" style="float: left;">Delete
                                                    <form id="delete-form" action="{{ route('challenge-allowed-delete', $challenge->roll_no) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                  </a> {{--add method('DELETE')--}}
                                                   
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
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

    @endsection

@extends('ums.admin.admin-meta')
@section('content')

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}


    <!-- BEGIN: Main Menu-->
    <div class="app-content content ">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Setting</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>  
                                <li class="breadcrumb-item active">Open Exam Form</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right">
                    <button onclick="javascript: history.go(-1)" class=" btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Create</button>
                    <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>
                        Reset</button> 
                </div>
            </div>
        </div>


    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Exam Type <span class="text-danger ">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">--Select--</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>    
                    </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Category <span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">All</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
                </div>
                
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Course <span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">All</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Semester <span class="text-danger">*</span></label>
                    <input type="text" class="form-control">
                </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Form Date <span class="text-danger ">*</span></label>
                <input type="date" class="form-control">
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">To Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Semester Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Session <span class="text-danger">*</span></label>
                <input type="text" class="form-control">
            </div>
            
        </div>
    </div>
    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Message <span class="text-danger ">*</span></label>
                <input type="date" class="form-control">
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Upload <span class="text-danger">*</span></label>
                <input class="form-control" type="file" name="paper_doc_url" accept="application/pdf,image.*">
            </div>
        </div>
        </div>
        {{-- MAIN --}}
    
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            
            </div>
            <div class="content-body">
                 
				<section id="basic-datatable">
                    <div class="row ">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Campus</th>
                                                    <th>Category</th>
                                                    <th>Course</th>
                                                    <th>From Date</th>
                                                    <th>To Date</th>
                                                    <th>Message</th>
                                                    <th>Application document</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($examsetting as $index=>$data)  
                                                <tr>
                                                <td>{{++$index}}</td>
                                                <td>{{($data->campus)?$data->campus->name:'All'}}</td>
                                                <td>{{($data->course)?$data->course->name:'All'}}</td>
                                                <td>{{($data->semester)?$data->semester->name:'All'}}</td>
                                                <td>{{$data->form_type}}</td>
                                                <td>@if($data->semester_type == 1){{'All'}}@elseif($data->semester_type ==2){{'Even'}}@elseif($data->semester_type ==3){{'Odd'}} @endif</td>
                                                <td>{{date('d-m-Y',strtotime($data->from_date))}}</td>
                                                <td>{{date('d-m-Y',strtotime($data->to_date))}}</td>
                                                <td>{{$data->session}}</td>
                                                <td>{{$data->message}}</td>
                                                <td style="border: black thin solid; padding: 3px; vertical-align: bottom; border-right: none; border-top: none;"><img src="{{$data->paper_doc_url}}" style="height:60px; width: 100px;" alt="">
                                                <a target="_blank" href="{{$data->paper_doc_url}}">View Doc</a>
                                                </td>
                                                <td class="tableactionnew">  
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{url('delete-from-setting',[$data->id])}}">
                                                                <i data-feather="trash-2" class="me-50"></i>
                                                                <span onclick="return confirm('Are you sure?');">Delete</span>                                                                </a>
                                                            </a>
                                                            {{-- <td><a href="{{url('delete-from-setting',[$data->id])}}"  onclick="return confirm('Are you sure?');" class="btn-md btn-add"> Delete</a> --}}

                                                        </div>
                                                    </div> 
                                                </td>                                               
                                             </td>
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                        <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-post">Post</label>
                                        <input type="text" id="basic-icon-default-post" class="form-control dt-post" placeholder="Web Developer" aria-label="Web Developer" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-email">Email</label>
                                        <input type="text" id="basic-icon-default-email" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
                                        <small class="form-text"> You can use letters, numbers & periods </small>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                                        <input type="text" class="form-control dt-date" id="basic-icon-default-date" placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="basic-icon-default-salary">Salary</label>
                                        <input type="text" id="basic-icon-default-salary" class="form-control dt-salary" placeholder="$12000" aria-label="$12000" />
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

    @endsection
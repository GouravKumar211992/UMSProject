@extends("ums.admin.admin-meta")
@section("content")

    <!-- Content Start -->
    <div class="app-content content">
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <h2 class="content-header-title float-start mb-0">Application Form</h2>
                </div>
                <div class="content-header-right col-md-6 mb-50 mb-sm-0 p-2 text-sm-end">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-primary btn-sm" data-bs-target="#bulkUploadModal" data-bs-toggle="modal">
                            Bulk Upload
                        </button>
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
                                                <th>Sr.No</th>
                                                <th>Campus Name</th>
                                                <th>Program Name</th>
                                                <th>Course Name</th>
                                                <th>Semester Name</th>
                                                <th>Session</th>
                                                <th>Subject Name</th>
                                                <th>Subject Code</th>
                                                <th>Permission</th>
                                                <th>Faculty Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($internals as $internal)
                                                <tr>
                                                    <td>#6699</td>
                                                    <td>{{$internal->Course->campuse->name}}</td>
                                                    <td>{{$internal->Category->name}}</td>
                                                    <td>{{$internal->Course->name}}</td>
                                                    <td>{{$internal->Semester->name}}</td>
                                                    <td>{{$internal->session}}</td>
                                                    <td>{{@$internal->subject->name}}</td>
                                                   
                                                    <td><span class="badge rounded-pill badge-light-secondary">{{$internal->sub_code}}</span></td>
                                                    <td>{{$internal->getPermissionAttribute() ? $internal->getPermissionAttribute() : ''}}</td>
                                                    <td>{{@$internal->faculty->name}}</td>
                                                    <td class="tableactionnew">
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="internal_mapping_edit">
                                                                    <i data-feather="edit-3"></i> Edit
                                                                </a>
                                                                <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2"></i> Delete
                                                                </a>
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
                </section>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal modal-slide-in fade" id="filter">
        <div class="modal-dialog sidebar-sm">
            <form class="add-new-record modal-content pt-0">
                <div class="modal-header mb-1">
                    <h5 class="modal-title">Apply Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body flex-grow-1">
                    <!-- Filter Fields -->
                    <div class="mb-1">
                        <label class="form-label">Select Organization</label>
                        <select class="form-select">
                            <option>Select</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Select Company</label>
                        <select class="form-select">
                            <option>Select</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Select Unit</label>
                        <select class="form-select">
                            <option>Select</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option>Select</option>
                            <option>Active</option>
                            <option>Inactive</option>
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
    <!-- End of Filter Modal -->

    <!-- Footer -->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block">Copyright &copy; 2024 <a href="#">Presence 360</a>, All rights Reserved</span></p>
    </footer>

@endsection

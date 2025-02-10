@extends('ums.admin.admin-meta')
@section('content')


<!-- BEGIN: Content -->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-6 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Bulk Uploads</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a>
                                </li>  
                                <li class="breadcrumb-item active">Bulk Uploads</li>


                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right mt-5 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right d-flex flex-wrap align-items-center">
                    <a class="btn btn-dark btn-sm me-2" href="#">
                        <i data-feather="plus-circle"></i> Bulk ID Cards
                    </a>
                    {{-- <a class="btn btn-warning btn-sm me-2" href="#">
                        <i data-feather="plus-circle"></i> Bulk Photos
                    </a>
                    <a class="btn btn-primary btn-sm me-2" href="#">
                        <i data-feather="plus-circle"></i> Bulk Signatures
                    </a>
                    <a class="btn btn-info btn-sm me-2" href="#">
                        <i data-feather="plus-circle"></i> Bulk Fee Receipt
                    </a> --}}
                    <div class="d-flex align-items-center ">
                        <label for="upload-file" class="btn btn-success btn-sm me-2">
                            <i data-feather="upload"></i> Upload File
                        </label>
                        <input type="file" id="upload-file" class="d-none" 
                               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                               <a class="btn btn-danger btn-sm" href="path/to/your-file.pdf" download="YourFileName.pdf">
    <i data-feather="download"></i> Download
</a>

                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Add your page content here -->
        </div>
    </div>
</div>
<!-- END: Content -->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer -->

<!-- END: Footer -->

<div class="modal modal-slide-in fade filterpopuplabel" id="filter">
    <div class="modal-dialog sidebar-sm">
        <form class="add-new-record modal-content pt-0"> 
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            </div>
            <div class="modal-body flex-grow-1">
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
                <button type="button" class="btn btn-primary data-submit me-1">Apply</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>


@endsection

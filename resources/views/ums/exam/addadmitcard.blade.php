@extends('ums.admin.admin-meta')

@section('content')
@section('styles')
<style type="text/css">
    .viewapplication-form p {
        color: #6c757d;
        font-weight: bold;
        border: #eee thin solid;
        min-height: 40px;
        border-radius: 4px;
        padding: 6px;
        background: #f9f9f9;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .content-header-title {
        font-size: 24px;
        font-weight: bold;
    }

    .btn-custom {
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 500;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .table-bordered {
        border: 1px solid #ddd;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #ddd;
    }

    .img-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endsection
<div class="app-content content ">
    <div class="content-overlay"></div>
    @include('ums.admin.notifications')
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Category</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>  
                                <li class="breadcrumb-item active">Showing 1 to 10 of 2 category</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right"> 
                        <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="{{ url('category_list_add') }}"><i data-feather="file-text"></i> Add Category </a> 
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 

                        <button class="btn btn-warning box-shadow-2 btn-sm  mb-sm-0 mb-50" onClick="window.location.reload()"><i data-feather="refresh-cw"></i>Reset</button>

                        <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                         
                </div>
            </div>
        </div>
@include('ums.admin.notifications')

       
    </div>
</div>
@endsection

@section('scripts')
<!-- Custom JS if needed -->
@endsection

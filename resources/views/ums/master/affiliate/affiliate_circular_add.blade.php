@extends('admin.admin-meta')
@section('content')
    
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}


    <!-- BEGIN: Main Menu-->
    <div class="app-content content ">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Master</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>  
                                <li class="breadcrumb-item active">Affiliate Circular Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right">
                    <button onclick="javascript: history.go(-1)" class=" btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Update</button>
                    <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>
                        Reset</button> 
                </div>
            </div>
        </div>

        <div class="content-body bg-white p-4 shadow">

    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-6 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3"> Affiliate Circular Description <span class="text-danger ">*</span></label>
                <input type="text" class="form-control"> 
                    </div>
                    
                <div class="col-md-6 d-flex align-items-center">
                        <label class="form-label mb-0 me-2 col-3">Circular Date <span class="text-danger">*</span></label>
                        <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                            <option value="7">2024-2025</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="75">75</option>
                            <option value="100">100</option>
                        </select>
                     </div>     
        </div>
    </div>

    <div class="col-md-12">
        <div class="row align-items-center mb-1">  
            <div class="col-md-6 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Circular Details <span class="text-danger">*</span></label>
                <input type="text" class="form-control">
                </div>
        </div>
    </div>
        </div>

      {{-- </body> --}}
        @endsection
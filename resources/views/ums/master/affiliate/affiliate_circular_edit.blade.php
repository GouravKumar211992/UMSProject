@extends('ums.admin.admin-meta')

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
                                <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>  
                                <li class="breadcrumb-item active">Affiliate Circular Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right">
                    <button form="edit-form" class=" btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> 
                    Update</button>
                    <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>
                        Reset</button> 
                </div>
            </div>
        </div>

        <div class="content-body bg-white p-4 shadow">
         <form id="edit-form" action="{{url('affiliate_circular_update',$info->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
       
    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-6 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3"> Affiliate Circular Description <span class="text-danger ">*</span></label>
                <input type="text" name="circular_description" value="{{ $info->circular_description}}" class="form-control"> 
                    </div>
                    
                <div class="col-md-6 d-flex align-items-center">
                        <label class="form-label mb-0 me-2 col-3">Circular Date <span class="text-danger">*</span></label>
                        <input type="date" name="circular_date" value="{{ $info->circular_date}}" class="form-control"> 
                     </div>     
        </div>
    </div>

    <div class="col-md-12">
        <div class="row align-items-center mb-1">  
            <div class="col-md-6 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Circular Details <span class="text-danger">*</span></label>
                <input type="file" name="circular_file" class="form-control">
                @if ($info->circular_file)
        <p class="mt-2">
            <a href="{{ url('storage/' . $info->circular_file) }}" target="_blank">View Current File</a>
        </p>
    @endif
                </div>
        </div>
    </div>
        </div>
        </form>
    </div>
        @endsection

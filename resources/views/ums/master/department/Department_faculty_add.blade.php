 @extends('ums.admin.admin-meta')
<!-- BEGIN: Body-->
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
                            <h2 class="content-header-title float-start mb-0">Add Facaulty
                            </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Add Facaulty
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0" onclick="location.href='{{url('/department_faculty')}}'"  href="javascript:history.back()"> <i data-feather="arrow-left-circle" ></i> GO BACK
                            </button>
                            <button type="submit" form="department_faculty_add" class="btn btn-primary btn-sm mb-50 mb-sm-0">
    <i data-feather="check-circle" style="font-size: 40px;"></i> Submit
</button>


                    </div>
                </div>
            </div>
            <div class="content-body bg-white py-2 mb-4  shadow">
                <div class="row g-0  mt-3 mb-3 text-center ">
                <form id="department_faculty_add" method="POST" action="{{ Route('department_facultyadd') }}" >
                @csrf

                    <div class="col-md-12 text-center ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">
                                    Enter Department Faculty Name:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control">
                                
                            </div>
                        </div>
                        

                       


                    </div>
                    </form>              
            </div>

            </div>

        </div>
    </div>
               

           
    @endsection
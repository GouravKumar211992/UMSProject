@extends('ums.admin.admin-meta')
<!-- BEGIN: Body-->
@section('content')
    

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col=""> --}}

  
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Add New Shift</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Add New Shift</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0" onclick="location.href='{{url('/shift_list')}}'"> <i data-feather="x"></i> Cancel
                        </button>
                        <button type="submit" form="edit_shift_form" class="btn btn-primary btn-sm mb-50 mb-sm-0"> <i data-feather="check-circle" style="font-size: 40px;"></i>
                            Submit</button>
                    </div>
                </div>
            </div>
    
            <!-- Form starts here -->
            <form action="{{route('update_shift')}}" method="POST" id="edit_shift_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="shift_id" value="{{$selected_shift->id}}">
                <div class="content-body bg-white py-4 mb-4 shadow">
                    <div class="container">
                        <div class="row g-0 mt-3 mb-3 text-center">
                            <div class="col-md-12 text-center">
                                <div class="row align-items-center mb-1">
                                    <div class="col-md-3">
                                        <label class="form-label">Shift Name<span class="text-danger m-0">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="course_name" name="name" value="{{$selected_shift->name}}" class="form-control">
                                    </div>
                                </div>
                                <div class="row align-items-center mb-1">
                                    <div class="col-md-3">
                                        <label class="form-label">Start Time<span class="text-danger m-0">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="time" name="start_time" value="{{$selected_shift->start_time}}" id="start_time" class="form-control">
                                    </div>
                                </div>
                                <div class="row align-items-center mb-1">
                                    <div class="col-md-3">
                                        <label class="form-label">End Time<span class="text-danger m-0">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="time" name="end_time" value="{{$selected_shift->end_time}}" id="end_time" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Form ends here -->
        </div>
    </div>
    
           
          
            
    <!-- END: Content-->
    <script>
        $(document).ready(function() {
    
            var selected_shift = {
                {
                    !!json_encode($selected_shift) !!
                }
            };
        });
    </script>
    
    <script>
        function submitCat(form) {
            //alert(1);
            document.getElementById('edit_shift_form').submit();
        }
    </script>
    @endsection
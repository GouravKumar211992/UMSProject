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
                            <h2 class="content-header-title float-start mb-0">Edit-Notification</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Edit-Notification</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0" onclick="location.href='{{url('/notification')}}'"> <i data-feather="x"></i> Cancel
                            </button>
                        <button form="cat_form" type="submit" class="btn btn-primary btn-sm mb-50 mb-sm-0" > <i data-feather="check-circle" style="font-size: 40px;"></i>
                            Submit</button>


                    </div>
                </div>
            </div>
            <form id="cat_form" method="POST" action="{{ route('notification_update', ['update' => $info->id]) }}">
            @csrf
            @method('PUT')
                <div class="content-body bg-white py-4 mb-4 shadow">
                <div class="row g-0  mt-3 mb-3 text-center">


                    <div class="col-md-12 text-center">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Notification Description:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-8">
                                <input type="text" name="notification_description"  value="{{$info->notification_description}}" class="form-control" placeholder="Enter notification description
">
                                
                            </div>
                        </div>
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Notification Started:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-8">
                              <input type="date" value="{{$info->notification_start}}" class="form-control" name="notification_start">                        </div>
                        </div>
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Notification Ended:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-8">
                              <input type="date" name="notification_end"  class="form-control" value="{{$info->notification_end}}">                        </div>
                        </div>

                       


                    </div>
                
            </div>
            </div>
        </form>
    @endsection
    <script>
        function submitCourse(form) {
            document.getElementById('cat_form').submit();
        }
        $('.numberOnly').keyup(function() {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        });
        function disableButton() {
            var btn = document.getElementById('btn');
            btn.disabled = true;
            btn.innerText = 'Submitting...'
        }
    // 	$(document).ready(function(){
    // 	$('#campus_id').change(function() {
    // 		var campus_id=$('#campus_id').val();
    // 		if(campus_id==''){
    // 			return false;
    // 		}
    // 		$("#course_id").find('option').remove().end();
    // 		$.ajax({
    // 			url: "/admin/master/stream-list/"+campus_id,
    // 			type: 'GET',
    // 			success: function(data,textStatus, jqXHR) {
    // 				$('#course_id').append(data);
    // 			}
    // 		});
    // 	});	
    // });	
    </script>
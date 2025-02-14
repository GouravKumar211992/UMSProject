






@extends('ums.master.faculty.faculty-meta')
@section('content')
{{-- Web site Title --}} 
@section('title') Practical Marks :: @parent @stop 

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0" style="display: flex; flex-direction: column; height: 100vh;">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Internal Marks</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active">Internal Marks</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right">
                    <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="{{route('practical-marks')}}"><i data-feather="file-text"></i> Add Campus </a>
                    <a href="{{route('practical-marks-show')}}"  class="btn btn-primary btn-sm mb-50 mb-sm-0" > <i data-feather="check-circle" style="font-size: 40px;"></i>
                        Show Internal Mark</a>
                </div>
            </div>
        </div>
        <div class="content-body bg-white p-4 shadow" >
            <div style="text-align: center;">
                <h3>Dr. SHAKUNTALA MISRA NATIONAL REHABILITATION UNIVERSITY, LUCKNOW</h3>
                <h3>AWARD SHEET OF INTERNAL MARKS</h3>
                <h3>MID SEMESTER & ASSIGNMENT / PRESENTATION</h3>
            </div>
        </div>

        <!-- Footer Section -->
        {{-- <div class="footer">
            <p style="text-align: center; margin: 20px 0;">© 2025 Dr. SHAKUNTALA MISRA UNIVERSITY. All rights reserved.</p>
        </div> --}}
    </div>
</div>
<script>
	function exportdata() {
	 
		var fullUrl_count = "{{count(explode('?',urldecode(url()->full())))}}";
		 var fullUrl = "{{url()->full()}}";
		 if(fullUrl_count>1){
			 fullUrl = fullUrl.split('?')[1];
			 fullUrl = fullUrl.replace(/&amp;/g, '&');
			 fullUrl = '?'+fullUrl;
		}else{
            fullUrl = '';
        }
         var url = "{{route('internalMarksExport')}}"+fullUrl;
        window.location.href = url;
		
	}
	function editCat(slug) {
		var url = "{{url('admin/user/edit-user')}}"+"/"+slug;
		window.location.href = url;
	}
	function deleteCat(slug) {
        var url = "{{url('admin/user/delete-model-trim')}}"+"/"+slug;
        window.location.href = url;
    }
</script>
@endsection

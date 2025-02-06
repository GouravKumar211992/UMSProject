@extends("ums.admin.admin-meta")
@section("content")


{{-- <!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    
    <title>Presence 360</title>
     	
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../assets/css/favicon.png">
     <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600;700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
	<link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/css/iconsheet.css">
    <!-- END: Custom CSS--> 

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    @include('header')
     
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    @include('sidebar')
    <!-- END: Main Menu--> --}}

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Master</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>  
                                    <li class="breadcrumb-item active"> Add Stream List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- CSRF token required for form submission -->
              
                  <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                      <div class="form-group breadcrumb-right">
                          <!-- Submit Button -->
                          <button type="submit" form="cat_form" class="btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                  <polyline points="22 4 12 14.01 9 11.01"></polyline>
                              </svg> Submit 
                          </button>
              
                          <!-- Reset Button -->
                          <button type="reset" class="btn btn-warning btn-sm mb-50 mb-sm-0">
                              <i data-feather="refresh-cw"></i> Reset
                          </button>
                      </div>
                  </div>
                  <form id="cat_form" method="POST" action="{{ route('add_stream_list') }}">
                    @csrf
                  <div class="content-body bg-white p-4 shadow">
                    <div class="row align-items-center mb-1">
                      <div class="col-md-4 ">
                        <div class="d-flex align-items-center">
                            <label class="form-label mb-0 me-2 col-3">Stream Name <span class="text-danger">*</span></label>
                            <input type="text" id="stream_name" name="stream_name" class="form-control"  placeholder="Enter here">
                        </div>
                    </div> 
                        
                    </div>

                      <div class="col-md-12">
                        <div class="row align-items-center mb-1">
                      <div class="col-md-4 d-flex align-items-center">
                          <label class="form-label mb-0 me-2 col-3">Category <span class="text-danger">*</span></label>
                          <select name="category_id" aria-controls="DataTables_Table_0" class="form-select" required>
                              <option value="">Please select</option>
                              @foreach ($categorylist as $category)
                                  <option value="{{$category->id}}">{{ ucfirst($category->name) }}</option>
                              @endforeach
                          </select>
                      </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4 d-flex align-items-center">
                                <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger">*</span></label>
                                <select name="university" aria-controls="DataTables_Table_0" class="form-select">
                                  <option value="">Please select Campus</option>
                                  @foreach ($courselist as $course)
                                      <option value="{{$course->id}}">{{ ucfirst($course->name) }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                  

                          <div class="col-md-12">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-4 d-flex align-items-center">
                                    <label class="form-label mb-0 me-2 col-3">course <span class="text-danger">*</span></label>
                                    <select name="course_id" aria-controls="DataTables_Table_0" class="form-select">
                                      <option value="">Please select course</option>
                                      @foreach ($campuselist as $campus)
                                          <option value="{{$campus->id}}">{{ ucfirst($campus->name) }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>
                      </div>
                        </div>
                 
                   
             
                </form>
      


				
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
											   
               
									
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

	
	 
    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
		<div class="modal-dialog sidebar-sm">
			<form class="add-new-record modal-content pt-0"> 
				<div class="modal-header mb-1">
					<h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Select Date</label>
<!--                        <input type="text" id="fp-default" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />-->
						  <input type="text" id="fp-range" class="form-control flatpickr-range bg-white" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
					</div>
					
					<div class="mb-1">
						<label class="form-label">PO No.</label>
						<select class="form-select">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Vendor Name</label>
						<select class="form-select select2">
							<option>Select</option> 
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Status</label>
						<select class="form-select">
							<option>Select</option>
							<option>Open</option>
							<option>Close</option>
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


    
  <script>
    function submitStream(form) {
      document.getElementById('cat_form').submit();
    }
  $(document).ready(function(){
  
  $('#category_id').change(function() {
    var university=$('#university').val();
    var id = $('#category_id').val();
    $("#course_id").find('option').remove().end();
    $.ajax({
          url: "/admin/master/stream/get-course-list",
          data: {"_token": "{{ csrf_token() }}",university:university ,id: id},
          type: 'POST',
          success: function(data,textStatus, jqXHR) {
            $('#course_id').append(data);
              
            
          }
      });
  });	
  });	
  </script>
@endsection

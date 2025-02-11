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
    <link rel="stylesheet" type="text/css" href="../../../assets/css/texp-mang.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/css/iconsheet.css">
    <!-- END: Custom CSS--> 

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    @include('header')
    @include('sidebar') --}}
    

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Students</h2>
                            {{-- <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Approval List</li>
                                </ol>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        {{-- <button class="btn btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button>  --}}
						<a class="btn btn-primary btn-sm mb-50 mb-sm-0" href="{{ url('bulk_icard_print') }}"><i data-feather="plus-circle"></i>Bulk icard</a> 
                    </div>
                </div>
            </div>
            <div class="content-body">
                 
                
				
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
									<table class="datatables-basic table myrequesttablecbox loanapplicationlist "> 
                                        <thead>
                                             <tr>
												<th>Enrollment No</th>
												<th>Name</th>
												<th>Email</th>
												<th>Mobile</th>
												<th>Created on</th>
												<th>Status</th>
												<th>Entry Type</th>
												<th>Action</th>
											  </tr>
											</thead>
											<tbody>

                        @foreach($students as $student)
                        <tbody>
          <tr>  
            <td>#{{$student->enrolment_number}}</td>
            <td>{{$student->student_name}}</td>
            <td>{{$student->email}}</td>
            <td>{{$student->student_mobile}}</td>
            <!--td>{{ucfirst($student->gender)}}</td>
                                <td>{{ucfirst($student->marital_status)}}</td>
            <td class="text-right">
                                    @if($student->date_of_birth != '')
                                    {{date('M dS, Y', strtotime($student->date_of_birth))}} @endif</td--->
            <td>{{date('M dS, Y', strtotime($student->created_at))}}</td>
                                <td><div class="admin-status progStat"><span></span>{{ucfirst($student->status)}}</div></td>
                                <td><div class="admin-status progStat"><span></span>{{ucfirst($student->type)}}</div></td>
                                  <td class="tableactionnew">
                                    <div class="dropdown">
                                      <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                      </button>
                                      <div class="dropdown-menu dropdown-menu-end">
                                      <a target="_new" href="{{route('view-icard',[$student->id])}}">View iCard</a>                                  <i data-feather="edit" class="me-50"></i>
                                         
                                             </a>
                                         <br>
                                             <a onClick="return confirm('Are you sure?');" href="{{route('single-icard-delete',[$student->id])}}">Delete</a>
                                             <i data-feather="trash-2" class="me-50"></i>
                                          
                                        </a>
                                      </div>
                                    </div>
                                  </td>
          </tr>
        </tbody>
                       
        @endforeach




									</table>
								</div>
								
								
								
								
								
                            </div>
                        </div>
                    </div>
                     
                </section>
                 

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->

    <!-- END: Footer-->
	
	 
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
					<button type="button" class="btn btn-primary data-submit mr-1">Apply</button>
					<button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

{{-- 
    <!-- BEGIN: Vendor JS-->
    
    <!-- BEGIN: Vendor JS-->
     <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="../../../app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
	<script src="../../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS--> 
    <script src="../../../app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
	<script src="../../../app-assets/js/scripts/forms/form-select2.js"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
        
		$(function () { 

  var dt_basic_table = $('.datatables-basic'),
    dt_date_table = $('.dt-date'),
    dt_complex_header_table = $('.dt-complex-header'),
    dt_row_grouping_table = $('.dt-row-grouping'),
    dt_multilingual_table = $('.dt-multilingual'),
    assetPath = '../../../app-assets/';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  // DataTable with buttons
  // --------------------------------------------------------------------

  if (dt_basic_table.length) {
    var dt_basic = dt_basic_table.DataTable({
      
      order: [[0, 'asc']],
      dom: 
        '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
        // {
        // //   extend: 'collection',
        // //   className: 'btn btn-outline-secondary dropdown-toggle',
        // //   text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
        //   buttons: [
        //     {
        //       extend: 'print',
        //       text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
        //       className: 'dropdown-item',
        //       exportOptions: { columns: [3, 4, 5, 6, 7] }
        //     },
        //     {
        //       extend: 'csv',
        //       text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
        //       className: 'dropdown-item',
        //       exportOptions: { columns: [3, 4, 5, 6, 7] }
        //     },
        //     {
        //       extend: 'excel',
        //       text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
        //       className: 'dropdown-item',
        //       exportOptions: { columns: [3, 4, 5, 6, 7] }
        //     },
        //     {
        //       extend: 'pdf',
        //       text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
        //       className: 'dropdown-item',
        //       exportOptions: { columns: [3, 4, 5, 6, 7] }
        //     },
        //     {
        //       extend: 'copy',
        //       text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
        //       className: 'dropdown-item',
        //       exportOptions: { columns: [3, 4, 5, 6, 7] }
        //     }
        //   ],
        //   init: function (api, node, config) {
        //     $(node).removeClass('btn-secondary');
        //     $(node).parent().removeClass('btn-group');
        //     setTimeout(function () {
        //       $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
        //     }, 50);
        //   }
        // },
         
      ],
      
      language: {
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      }
    });
    $('div.head-label').html('<h6 class="mb-0">Event List</h6>');
  }

  // Flat Date picker
  if (dt_date_table.length) {
    dt_date_table.flatpickr({
      monthSelectorType: 'static',
      dateFormat: 'm/d/Y'
    });
  }

  // Add New record
  // ? Remove/Update this code as per your requirements ?
  var count = 101;
  $('.data-submit').on('click', function () {
    var $new_name = $('.add-new-record .dt-full-name').val(),
      $new_post = $('.add-new-record .dt-post').val(),
      $new_email = $('.add-new-record .dt-email').val(),
      $new_date = $('.add-new-record .dt-date').val(),
      $new_salary = $('.add-new-record .dt-salary').val();

    if ($new_name != '') {
      dt_basic.row
        .add({
          responsive_id: null,
          id: count,
          full_name: $new_name,
          post: $new_post,
          email: $new_email,
          start_date: $new_date,
          salary: '$' + $new_salary,
          status: 5
        })
        .draw();
      count++;
      $('.modal').modal('hide');
    }
  });

  // Delete Record
  $('.datatables-basic tbody').on('click', '.delete-record', function () {
    dt_basic.row($(this).parents('tr')).remove().draw();
  });
	
	 
 
});
		
		 
		
		
    </script>
</body>
<!-- END: Body-->

</html> --}}

@endsection
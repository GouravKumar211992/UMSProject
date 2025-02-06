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
     @include('sidebar')
     @include('footer')
     
    <!-- END: Header-->
 --}}

    <!-- BEGIN: Main Menu-->
    @php

$course_name = '';
$semester_name = '';
@endphp
    <div class="app-content content ">
      @if($download!='pdf')
      <form method="get" id="form_data">

      <div class="big-box d-flex justify-content-between mb-1 align-items-center">

        <div class="head">
    <div class="row d-flex justify-content-between">
        <div class="col-md-9">
            <h4>Tabular Record (TR)</h4>
        </div>
        <div class="col-md-3 text-right">
            <div class="breadcrumbs-top">
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <!-- <li class="breadcrumb-item active">List of Admins</li> -->
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>


        
        <div class="submitss text-start me-3 align-item-center">
            <input type="submit" class="btn-sm btn  mb-50 mb-sm-0r btn-primary mt-1" value="Generate TR">
            <button class="btn btn-warning btn-sm mb-50 mb-sm-0r mt-1"  onclick="window.location.reload();" type="reset">
                <i data-feather="refresh-cw"></i> Reset
            </button>
        </div>
      </div>

    <div class="row">
    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger ">*</span></label>
                <select data-live-search="true" name="campus_id" id="campus_id" style="border-color: #c0c0c0;" class="form-control" onChange="return $('#form_data').submit();">
                <option value="">--Choose Campus--</option>
                  @foreach($campuses as $campus)
                    <option value="{{$campus->id}}" @if(Request()->campus_id==$campus->id) selected @endif >{{$campus->name}}</option>
                    @endforeach
                  </select>
                  <span class="text-danger">{{ $errors->first('campus_id') }}</span>
                    </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Courses <span class="text-danger">*</span></label>
                <select data-live-search="true" name="course" id="course" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                  <option value="">--Choose Course--</option>
                      @foreach($courses as $course)
                        @if($course_id==$course->id)
                          @php
                            $course_name = $course->name;
                          @endphp
                        @endif
                        <option value="{{$course->id}}" @if($course_id==$course->id) selected @endif >{{$course->name}}</option>
                        @endforeach
                      </select>
                      <span class="text-danger">{{ $errors->first('course') }}</span>
                </div>
                
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Semester <span class="text-danger">*</span></label>
                <select data-live-search="true" name="semester" id="semester" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="$('#group_name').prop('selectedIndex',0); return $('#form_data').submit();">
                  <option value="">--Select Semester--</option>
                  @foreach($semesters as $semester)
                    @if($semester_id==$semester->id)
                      @php
                        $semester_name = $semester->name;
                      @endphp
                    @endif
                <option value="{{$semester->id}}" @if($semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                  @endforeach
                            
                    </select>
                  <span class="text-danger">{{ $errors->first('semester') }}</span>
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Academic Session <span class="text-danger">*</span></label>
          <select name="academic_session" id="academic_session" class="form-control" style="border-color: #c0c0c0;" onChange="return $('#form_data').submit();">
						@foreach($sessions as $sessionRow)
						<option value="{{$sessionRow->academic_session}}" @if(Request()->academic_session == $sessionRow->academic_session) selected @endif >{{$sessionRow->academic_session}}</option>
						@endforeach
					</select>
					<span class="text-danger">{{ $errors->first('academic_session') }}</span>
                </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Form Type <span class="text-danger ">*</span></label>
                <select name="form_type" id="form_type" class="form-control" style="border-color: #c0c0c0;">
                  <option value="regular" @if(Request()->form_type=='regular') selected @endif >Regular</option>
                </select>
                <span class="text-danger">{{ $errors->first('form_type') }}</span> 
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Roll number<span class="text-danger">*</span></label>
                <input type="text" name="roll_no" class="form-control" style="border-color: #c0c0c0;" value="{{(Request()->roll_no)?Request()->roll_no:''}}">
                <span class="text-danger">{{ $errors->first('roll_no') }}</span> 
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Subject Group Name<span class="text-danger">*</span></label>
                <select name="group_name[]" id="group_name" class="form-control" style="border-color: #c0c0c0;" multiple>
                  <option value="">Please Select</option>
                  @foreach($subjects_header_group as $index_sub=>$subject)
                  <option value="{{$subject->subject}}" @if(Request()->group_name && in_array($subject->subject,Request()->group_name)) selected @endif >{{++$index_sub}}) {{$subject->subject}}</option>
                  @endforeach
                </select>
                <span class="text-danger">{{ $errors->first('group_name') }}</span>
            </div>
          
            
        </div>
        
      </div>
      @if($full_retult && count($full_retult) > 0)
      <div class="col-6">
        <br>
        <br>
        <a href="{{Request::getRequestUri()}}&finalize=true" class="btn btn-success">Finalize TR</a>
        <a href="{{Request::getRequestUri()}}&finalize=reset" class="btn btn-danger" style="margin-left:50;">Re-Fresh TR</a>
      </div>
      @endif
      <div class="@if($full_retult && count($full_retult) > 0) col-6 @else col-12 @endif text-right">
        <br>
        <br>
      </div>
      </div>
    </form>
    @endif
    <div class="container">
      <!-- or use any other number .col-md- -->
      
    </div>
  </div>
  @if($full_retult && count($full_retult) > 0)
    <div class="alert alert-success">Results of {{count($full_retult)}} students are generated.</div>
  @endif		 
   


      
       <!-- END: Content-->

       <div class="sidenav-overlay"></div>
       <div class="drag-target"></div>

       {{-- <!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a class="ml-25" href="#" target="_blank">Presence 360</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
    
    <div class="footerplogo"><img src="../../../assets/css/p-logo.png" /></div>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer--> --}}
  

    {{-- <script>
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
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle',
          text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
          buttons: [
            {
              extend: 'print',
              text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'csv',
              text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'excel',
              text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'pdf',
              text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'copy',
              text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            }
          ],
          init: function (api, node, config) {
            $(node).removeClass('btn-secondary');
            $(node).parent().removeClass('btn-group');
            setTimeout(function () {
              $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
            }, 50);
          }
        },
         
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
@extends('ums.admin.admin-meta')
@section('content')

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
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600;700"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
        href="../../../app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="../../../app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="../../../app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="../../../app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
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

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

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
                            <h2 class="content-header-title float-start mb-0">Exam</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item"><a>View Time Tables</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <form method="get" id="form_data">
                        <div class="form-group breadcrumb-right">
                            <button class="btn btn-primary btn-sm mb-50 mb-sm-0" type="submit" id="submitBtn"><i
                                    data-feather="clipboard"></i> Submit</button>
                            {{-- <button class="btn btn-secondary btn-sm mb-50 mb-sm-0">Go Back</button>
                         <a class="btn btn-primary btn-sm mb-50 mb-sm-0" href="/view_time_tables"> View Time Tables</a>  

                          
                        <button class="btn btn-success  btn-sm mb-50 mb-sm-0">Schedule Bulk Upload</button> --}}


                        </div>
                </div>
            </div>
            <div class="content-body bg-white p-4 shadow">

                @include('ums.admin.notifications')
                <div class="row">

                    <!-- Campus and Course Selection -->
                    <div class="col-md mt-4 mb-3">
                        <!-- Campus Selection -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Campus:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="campus_id" id="campus_id"
                                    style="border-color: #c0c0c0;" class="form-control js-example-basic-single "
                                    onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Campus--</option>
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}"
                                            @if (Request()->campus_id == $campus->id) selected @endif>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('campus_id') }}</span>

                            </div>
                        </div>

                        <!-- Course Selection -->
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course:<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="course" id="course" style="border-color: #c0c0c0;"
                                    class="form-control js-example-basic-single "
                                    onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Course--</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}"
                                            @if (Request()->course == $course->id) selected @endif>{{ $course->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('course') }}</span>

                            </div>
                        </div>
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Schedule Count<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select data-live-search="true" name="schedule_count" id="schedule_count"
                                    style="border-color: #c0c0c0;" class="form-control js-example-basic-single ">
                                    <option value="">Select Schedule Count</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}"
                                            @if (Request()->schedule_count == $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                                <span class="text-danger">{{ $errors->first('schedule_count') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Semester, Session, and other options -->
                    <div class="col-md mt-4 mb-3">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Semester:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="semester" id="semester"
                                    style="border-color: #c0c0c0;" class="form-control js-example-basic-single ">
                                    <option value="">Select Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}"
                                            @if (Request()->semester == $semester->id) selected @endif>{{ $semester->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('semester') }}</span>
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Session:<span class="text-danger m-0">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <select data-live-search="true" name="session" id="session" style="border-color: #c0c0c0;"
                                    class="form-control js-example-basic-single ">
                                    <option value="">Select Session</option>
                                    @foreach ($sessions as $session)
                                        <option value="{{ $session->academic_session }}"
                                            @if (Request()->session == $session->academic_session) selected @endif>
                                            {{ $session->academic_session }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('session') }}</span>
                            </div>
                        </div>

                    </div>
                    </form>
                    @if ($exams)
                        @if (count($exams) > 0)
                            <div class="row">
                                <div class="col-sm-5">
                                    <span></span>

                                </div>
                                <div class="col-sm-4">
                                    <span></span><br>
                                    <a herf="#"onclick="window.print()" name=""
                                        class="btn btn-primary hidden-print" value="">PRINT</a>
                                </div>

                            </div>

                            <div class="container" style="background-color: #FFFFFF; margin-top: 15px;">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div id="" class="" style="color:black">
                                            <div class="panel-body">
                                                <div class="container">
                                                    <table border="1" id="tblTheory21" class="table table-hover">
                                                        <tr>
                                                            <td colspan="6">
                                                                <center>
                                                                    <h4>Exam Time Table</h4>
                                                                </center>
                                                        </tr>
                                                        <tr>
                                                            {{-- !Auth::guard('student')->user() --}}
                                                            @if (true)
                                                                <th class="thcenter hidden-print">Action</th>
                                                            @endif
                                                            <th class="thcenter">COURSE</th>
                                                            <th class="thcenter">DATE</th>
                                                            <th class="thcenter">SHIFT</th>
                                                            <th class="thcenter">PAPER CODE</th>
                                                            <th class="thcenter">PAPER NAME</th>
                                                        </tr>
                                                        @foreach ($exams as $exam)
                                                            <tr class="auto-style18 thcenter">
                                                                {{-- !Auth::guard('student')->user() --}}
                                                                @if (true)
                                                                    <td class="hidden-print"><a href="#!"
                                                                            data-toggle="modal"
                                                                            data-mid="{{ $exam->id }}" id="login_btn"
                                                                            data-target="#exampleModal"
                                                                            class="fa fa-pencil login_btn">
                                                                            Edit</a>
                                                                    </td>
                                                                @endif
                                                                <td><span
                                                                        id="lblPap1ID">{{ $exam->semester->name }}</span>
                                                                </td>
                                                                <td><span id="lblPap1Name"><input type="text"
                                                                            class="date" value="{{ $exam['date'] }}"
                                                                            name="date[]"
                                                                            hidden>{{ date('d-m-Y', strtotime($exam['date'])) }}</span>
                                                                </td>
                                                                <td><span id="lblPap1TE"><input type="text"
                                                                            class="shift" value="{{ $exam['shift'] }}"
                                                                            name="shift[]" hidden>
                                                                        {{ $exam['shift'] }}
                                                                    </span></td>
                                                                <td><span id="lblPap1TS">{{ $exam['paper_code'] }}
                                                                    </span></td>
                                                                <td><span
                                                                        id="lblPap1ID">{{ @$exam->subject->name }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="container" style="background-color: #FFFFFF; margin-top: 15px;">
                                <b style="color: black;">No Schedule Generated Please Wait... </b>
                            </div>
                        @endif
                    @endif

                    <!-- Submit Button -->

                </div>



                <!-- END: Content-->




                <!-- BEGIN: Vendor JS-->

                {{-- <!-- BEGIN: Vendor JS-->
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
        $(function() {

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

                    order: [
                        [0, 'asc']
                    ],
                    dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    displayLength: 7,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    buttons: [{
                            extend: 'collection',
                            className: 'btn btn-outline-secondary dropdown-toggle',
                            text: feather.icons['share'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Export',
                            buttons: [{
                                    extend: 'print',
                                    text: feather.icons['printer'].toSvg({
                                        class: 'font-small-4 mr-50'
                                    }) + 'Print',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'csv',
                                    text: feather.icons['file-text'].toSvg({
                                        class: 'font-small-4 mr-50'
                                    }) + 'Csv',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'excel',
                                    text: feather.icons['file'].toSvg({
                                        class: 'font-small-4 mr-50'
                                    }) + 'Excel',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: feather.icons['clipboard'].toSvg({
                                        class: 'font-small-4 mr-50'
                                    }) + 'Pdf',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'copy',
                                    text: feather.icons['copy'].toSvg({
                                        class: 'font-small-4 mr-50'
                                    }) + 'Copy',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                }
                            ],
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                                $(node).parent().removeClass('btn-group');
                                setTimeout(function() {
                                    $(node).closest('.dt-buttons').removeClass('btn-group')
                                        .addClass('d-inline-flex');
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
            $('.data-submit').on('click', function() {
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
            $('.datatables-basic tbody').on('click', '.delete-record', function() {
                dt_basic.row($(this).parents('tr')).remove().draw();
            });



        });

        $(".myrequesttablecbox tr").click(function() {
            $(this).addClass('trselected').siblings().removeClass('trselected');
            value = $(this).find('td:first').html();
        });

        $(document).on('keydown', function(e) {
            if (e.which == 38) {
                $('.trselected').prev('tr').addClass('trselected').siblings().removeClass('trselected');
            } else if (e.which == 40) {
                $('.trselected').next('tr').addClass('trselected').siblings().removeClass('trselected');
            }
            $('html, body').scrollTop($('.trselected').offset().top - 100);
        });
    </script>
</body>
<!-- END: Body-->

</html> --}}

            @endsection

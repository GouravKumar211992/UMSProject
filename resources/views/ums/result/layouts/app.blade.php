<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@section('title') DSMNRU @show</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/css/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link href="/assets/admin/css/font-awesome.css" rel="stylesheet">
    <link href="/assets/admin/css/style.css" rel="stylesheet">
    <link href="/assets/admin/css/responsive.css" rel="stylesheet">
    <link href="/assets/admin/css/bootstrap-select.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet">


    @yield('styles')
</head>


<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
    @php
                                $student=Auth::guard('student')->user()->enrollment_no;
                                $en=\App\Models\Enrollment::where('enrollment_no',$student)->first();
                                $application=\App\Models\Application::where('id',$en->application_id)->first();
                                @endphp



    @if(Auth::guard('student')->user()->exam_portal==0)
        <aside class="main-sidebar sidebar-dark-primary">
            <div class="navbg">
                <img src="/assets/admin/img/navtopbg.svg" />
            </div>
    <!-- Code for Student Portal Aside bar Start From here -->
            <div class="sidebar">

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item"><a href="{{route('student-dashboard')}}" class="nav-link active"><i class="iconly-boldCategory"></i>
							<p>Dashboard</p>
                            </a>
						</li>

                       <!-- <li class="nav-item has-treeview"><a href="#" class="nav-link"><i class="iconly-boldPaper"></i>
                                <p>Semester Utility<i class="fa fa-angle-left right"></i></p>
                            </a> -->

                            <!--ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{route('student-semesterfee')}}">
                                        <p>Semester Fee Form</p>
                                    </a>
                                </li>
                                {{--<li class="nav-item"><a class="nav-link" href="{{route('exam-form')}}">
                                        <p>Exam Form</p>
                                    </a>
                                </li>--}}
								<li class="nav-item"><a target="_blank" class="nav-link" href="{{route('result-list')}}">
                                        <p>Semester Results</p>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#">
                                        <p>Fees Summary</p>
                                    </a>
                                </li>
                            </ul-->
                        <!-- </li> -->
                        <!-- <li class="nav-item"><a href="#" class="nav-link"><i class="iconly-card"></i>
						<p>Semester Fee Deposit & View <i class="fa fa-angle-left right"></i></p>
                            </a>
                        </li> -->

                       <!--  <li class="nav-item"><a href="#" class="nav-link"><i class="iconly-card"></i>
						<p>Admit Card<i class="fa fa-angle-left right"></i></p>
                            </a>
						    <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" target="_blank" href="{{route('view-admit')}}">
                                        <p>View Admit Card</p>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" target="_blank"href="{{route('download-admit-card',['id'=>Auth::guard('student')->user()->enrollment_no])}}">
                                        <p>Download Admit Card</p>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                        <li class="nav-item"><a href="{{url('student/exam-form')}}" class="nav-link"><i class="iconly-card"></i>
                            <p>Exam Forms<i class="fa fa-angle-left right"></i></p>
                            </a>
                        </li>
                        <li class="nav-item"><a href="{{url('student/semester-form')}}" class="nav-link"><i class="iconly-card"></i>
                            <p>Semester Forms<i class="fa fa-angle-left right"></i></p>
                            </a>
                        </li>
                        <li class="nav-item"><a href="#" class="nav-link"><i class="iconly-card"></i>
						<p>Special Forms<i class="fa fa-angle-left right"></i></p>
                            </a>
						    <ul class="nav nav-treeview">
                                <!-- <li class="nav-item">
                                    <a class="nav-link" target="_blank" @if(Auth::guard('student')->user()->challenge_allowed) href="challenge-form/{{$en->roll_number}}" @endif>
                                        <p>Challenge Form</p>
                                    </a>
                                </li> -->
                                <!-- <li class="nav-item"><a class="nav-link" target="_blank"href="{{route('download-admit-card',['id'=>Auth::guard('student')->user()->enrollment_no])}}">
                                        <p>Download Admit Card</p>
                                    </a>
                                </li> -->
                            </ul>
                        </li>
						<!-- <li class="nav-item"><a href="{{route('studentreport')}}" class="nav-link"><i class="iconly-card"></i>
						<p>Time Table<i class="fa fa-angle-left right"></i></p>
                            </a> -->
						    <!--ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{route('time-table')}}">
                                        <p>View Time Table</p>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('class-schedule')}}">
                                        <p>Class Schedule</p>
                                    </a>
                                </li>
                            </ul-->
                            </li>
                            <!--<li class="nav-item"><a href="#" class="nav-link"><i class="iconly-card"></i>
                                <p>Download Certificate<i class="fa fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" target="_blank" href="{{route('download-migration-certificate')}}">
                                        <p>Migration Certificate </p>
                                    </a>
                                </li>

                                <li class="nav-item"><a class="nav-link" target="_blank" href="{{route('download-provisional-certificate')}}">
                                        <p>Provisional Certificate</p>
                                    </a>
                                </li>
                            </ul>
                            </li>-->

                             <!--li class="nav-item"><a href="#" class="nav-link"><i class="iconly-card"></i>
                        <p>Attendances<i class="fa fa-angle-left right"></i></p>
                            </a>

                            </li-->

                             <li class="nav-item"><a href="{{route('questionbankdownload')}}" class="nav-link"><i class="iconly-card"></i>
                        <p>Question Bank<i class="fa fa-angle-left right"></i></p>
                            </a>
                            <!--ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{route('view-question-banks')}}">
                                        <p>Download Question Banks</p>
                                    </a>
                                </li>
                            </ul-->
                            </li>

                             <!--li class="nav-item"><a href="#" class="nav-link"><i class="iconly-card"></i>
                        <p>Leave Status<i class="fa fa-angle-left right"></i></p>
                            </a>

                            </li-->
                             <!-- <li class="nav-item"><a href="#" class="nav-link"><i class="iconly-card"></i> -->
                        <!-- <p>Notification Board<i class="fa fa-angle-left right"></i></p> -->
                            <!-- </a> -->
                            <!-- <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" target="_blank"href="{{route('download-admit-card',['id'=>Auth::guard('student')->user()->enrollment_no])}}">
                                        <p>Notice View</p>
                                    </a>
                                </li>
                            </ul> -->
                            <li class="nav-item"><a href="{{route('calender')}}" class="nav-link"><i class="iconly-card"></i>
                        <p>Holiday Calender<i class="fa fa-angle-left right"></i></p>
                            </a>
                            <li class="nav-item"><a href="{{route('studentNotification')}}" class="nav-link" target="_blank"><i class="iconly-card"></i>
                        <p>Notification<i class="fa fa-angle-left right"></i></p>
                            </a>
                            <li class="nav-item"><a href="{{url('student/profile')}}" class="nav-link"><i class="iconly-card"></i>
                        <p>Personal Information<i class="fa fa-angle-left right"></i></p>
                            </a>
                            <!-- <li class="nav-item"><a href="#" class="nav-link"><i class="iconly-card"></i>
                        <p>Academic Detail<i class="fa fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" target="_blank"href="#">
                                        <p>Attendance View</p>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" target="_blank"href="#">
                                        <p>Admission Form View</p>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" target="_blank"href="#">
                                        <p>Syllabus</p>
                                    </a>
                                </li>
                            </ul> -->
                            <!-- <li class="nav-item">
                                <a href="#" class="nav-link"><i class="iconly-card"></i>
						          <p>Semester <i class="fa fa-angle-left right"></i></p>
                                </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" target="_blank" href="{{route('studentNotification')}}">
                                        <p>Notification </p>
                                    </a>
                               </li>
                            </ul>
                            </li> -->




                    </ul>
                </nav>



            </div>

        </aside>
        @endif

<!-- Ending of Student Portal Aside bar coding -->

        @if(Auth::guard('student')->user()->exam_portal==0)
        <div class="content-wrapper">
        @else
        <div class="content" style="width:90%;margin:auto;">
        @endif
        <div class="content-header pb-0">
            <div class="container-fluid mt-3">

                <div class="row mb-2">
                    <div class="col-md-4 col-1">
                        <a class="d-block d-md-none" data-widget="pushmenu" href="#" role="button"><img
                                src="/assets/admin/img/menu-left-alt.svg" /></a>
                        <a class="d-block d-sm-none d-md-block" href="#" role="button"><img
                                src="/assets/admin/img/logo.png" /></a>
                    </div>
                    <div class="col-md-7 col-11 user-profile offset-md-1">
                        <div class="float-left notificationbar mt-4">
                            <!-- <a href="{{url('/student/dashboard')}}" class="position-relative">Examination Portal</a> -->
                        </div>
                        {{--  <div class="float-right notificationbar mt-4">
                            <a href="#" class="position-relative"><img src="/assets/admin/img/notification.svg"
                                    class="float-none" /><span class="top-notification">05</span></a>
                        </div>  --}}
                        <div class="float-right dropdown userData">
                            <a data-toggle="dropdown" href="#" aria-expanded="true">
                                <p>{{Auth::guard('student')->user()->name}}</p>
                                @php
                                    $student = Auth::guard('student')->user();
                                @endphp
                                <img src="{{$student->photo}}" alt="Photo" class="img-circle mr-1" height="50"/>
                                <img src="/assets/admin/img/dot.svg" class="float-none" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-md tableaction useraction-dropdown dropdown-menu-right"
                                style="left: inherit; right: 0px;">
                                <ul>
                                    <li><a href="{{route('student-profile')}}">My Profile</a></li>
                                    <li><a href="{{route('student-change-password')}}">Change Password</a></li>
                                    <li><a href="{{route('student-logout')}}">Logout</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="modal fade rightModal" id="forgetpassword" tabindex="-1" role="dialog"
                    aria-labelledby="loginpopupTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-slideout" role="document">

                        <div class="modal-content bg-light">
                            <form class="needs-validation" novalidate>
                                <div class="modal-header pt-5 pl-5 pr-5 border-0">
                                    <div class="pt-3">
                                        <button type="button" class="close search-btn addaddressbtn"
                                            data-dismiss="modal" aria-label="Close">
                                            <img src="/assets/admin/img/close.svg" width="70px" />
                                        </button>
                                        <div class="">
                                            <h2>Password</h2>
                                            <h5>Update</h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-body pt-3 pr-5 pl-5">

                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-label-group">
                                                <input type="text" id="pincode" class="form-control"
                                                    placeholder="Pincode" required>
                                                <label for="pincode">New password</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-label-group">
                                                <input type="text" id="state" class="form-control"
                                                    placeholder="State" required>
                                                <label for="state">Re-type new password</label>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center mt-4">
                                            <a href="#" class="btn btn-secondary btn-radius">Update password</a>
                                        </div>

                                    </div>

                                </div>

                            </form>
                        </div>

                    </div>
                </div>


            </div>
        </div>
            @yield('content')
    </div>

    <!-- </div> -->


    <div class="modal fade" id="editdesignation" tabindex="-1" role="dialog" aria-labelledby="loginpopupTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

            <div class="modal-content bg-light modalPad">
                <form class="needs-validation" novalidate>
                    <div class="modal-header position-relative border-0">
                        <div class="">
                            <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                                <img src="/assets/admin/img/close.svg" />
                            </button>
                            <div class="">
                                <h5>Edit</h5>
                                <p class="mb-0">Designation</p>
                            </div>

                        </div>
                    </div>
                    <div class="modal-body pt-0">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Designation">
                                </div>
                            </div>

                            <div class="col-md-12 mt-1">
                                <button href="#" class="btn btn-primary">Save</button>
                            </div>

                        </div>





                    </div>

                </form>
            </div>

        </div>
    </div>

    <div class="modal fade" id="adddesignation" tabindex="-1" role="dialog" aria-labelledby="loginpopupTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

            <div class="modal-content bg-light modalPad">
                <form class="needs-validation" novalidate>
                    <div class="modal-header position-relative border-0">
                        <div class="">
                            <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                                <img src="/assets/admin/img/close.svg" />
                            </button>
                            <div class="">
                                <h5>Add New</h5>
                                <p class="mb-0">Designation</p>
                            </div>

                        </div>
                    </div>
                    <div class="modal-body pt-0">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Designation">
                                </div>
                            </div>

                            <div class="col-md-12 mt-1">
                                <button href="#" class="btn btn-primary">Submit</button>
                            </div>

                        </div>





                    </div>

                </form>
            </div>

        </div>
    </div>

    <div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="loginpopupTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

            <div class="modal-content bg-light modalPad">
                <form class="needs-validation" novalidate>
                    <div class="modal-header position-relative border-0">
                        <div class="">
                            <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                                <img src="/assets/admin/img/close.svg" />
                            </button>
                            <div class="">
                                <h5>List of Designation</h5>
                                <p class="mb-0">Search</p>
                            </div>

                        </div>
                    </div>
                    <div class="modal-body pt-0">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control searchInput" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Type here...">
                                </div>
                            </div>

                            <div class="col-md-12 mt-1">
                                <button href="#" class="btn btn-primary" disabled>Search</button>
                            </div>

                        </div>


                    </div>

                </form>
            </div>

        </div>
    </div>

    <script src="/assets/admin/js/jquery.min.js"></script>
    <script src="/assets/admin/js/jquery-ui.min.js"></script>
    <script src="/assets/admin/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/admin/css/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="/assets/admin/js/adminlte.js"></script>
    <script src="/assets/admin/js/bootstrap-select.js"></script>
    <script src="/assets/admin/js/custom-app.js"></script>


    @yield('scripts')


</body>

</html>

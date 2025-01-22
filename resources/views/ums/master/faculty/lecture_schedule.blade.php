{{-- @extends("admin.admin-meta")
@section("content") --}}


@extends('master.faculty.faculty-meta')
<!-- END: Head-->

<!-- BEGIN: Body-->
 @section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <center><h1>LECTURE SCHEDULE</h1></center>
        <table border="5" cellspacing="0" align="center">
            <!--<caption>Timetable</caption>-->
            <tr>
                <td align="center" height="50"><b>Day/Period</b></td>
                <td align="center" height="50"><b>test</b><p>09:48-10:48</p></td>
            </tr>
            <tr>
                <td align="center" height="50">Monday</td>
                <td align="center" height="50">--</td>
            </tr>
            <tr>
                <td align="center" height="50">Tuesday</td>
                <td align="center" height="50">--</td>
            </tr>
            <tr>
                <td align="center" height="50">Wednesday</td>
                <td align="center" height="50">--</td>
            </tr>
            <tr>
                <td align="center" height="50">Thursday</td>
                <td align="center" height="50">--</td>
            </tr>
            <tr>
                <td align="center" height="50">Friday</td>
                <td align="center" height="50">--</td>
            </tr>
            <tr>
                <td align="center" height="50">Saturday</td>
                <td align="center" height="50">--</td>
            </tr>
            <tr>
                <td align="center" height="50">Sunday</td>
                <td align="center" height="50">--</td>
            </tr>
        </table>
        <center>
            <div class="col-8" style="margin-top:10px;">
                <a href="#" onclick="return window.print();" class="btn btn-info noPrint">Print</a>
            </div>
        </center>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0">
            <span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a class="ml-25" href="#" target="_blank">Presence 360</a>
                <span class="d-none d-sm-inline-block">, All rights Reserved</span>
            </span>
        </p>
        <div class="footerplogo"><img src="../../../assets/css/p-logo.png" /></div>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
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
                        <label class="form-label" for="fp-range">Select Date</label>
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

    @endsection
@extends("ums.admin.admin-meta")
@section("content")


    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Mark Filling Report</li>

                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="get" id="form_data">
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" name="submit_form" type="submit"><i data-feather="check-circle" ></i> Get Report
                            </button>
                        


                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row  ">


                    <div class="col-md mt-4 mb-3">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Campus:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select data-live-search="true" name="campus_id" id="campus_id" style="border-color: #c0c0c0;" class="form-control" onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Campus--</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{$campus->id}}" @if(Request()->campus_id==$campus->id) selected @endif >{{$campus->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Course:<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select data-live-search="true" name="course_id" id="course_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                                    <option value="">--Choose Course--</option>
                                        @foreach($courses as $course)
                                        <option value="{{$course->id}}" @if(Request()->course_id==$course->id) selected @endif >{{$course->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>


                    </div>
                    <div class="col-md mt-4 mb-3">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Semester:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select data-live-search="true" name="semester_id" id="semester_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single">
                                    <option value="">--Select Semester--</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{$semester->id}}" @if(Request()->semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Batch:<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select data-live-search="true" name="semester_id" id="semester_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single">
                                    <option value="">--Select Semester--</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{$semester->id}}" @if(Request()->semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>

                </form>
                </div>


                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <form method="post" id="saveSequence">
                                    <input type="hidden" name="semester_id" value="{{Request()->semester_id}}">
                                    <input type="hidden" name="batch_year" value="{{Request()->batch}}">
                                <div class="table-responsive">
                                    <table
                                        class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome">
                                        <thead>
                                            <tr>

                                                <th>S.NO</th>
                                                <th>Paper Code</th>
                                                <th>Paper Name</th>
                                                
                                               <th>Action</th>

                                            </tr>

                                        </thead>
                                <tbody>
                                    @php 
                                        $serial_no = 0; 
                                    @endphp
                                
                                    @foreach($results as $result)
                                    @php
                                    $subjectSuggetions = $result->subjectSuggetions(Request()->semester_id);
                                    $serial_no = $serial_no + 1;
                                    @endphp
                                        <tr class="main-tr">
                                            <td>
                                                <span class="position_show">{{$serial_no}}</span>
                                                <input type="hidden" name="subject_position[]" class="position position_style" value="{{$result->subject_position}}">
                                                <input type="hidden" name="subject_code[]" class="subject_code" value="{{$result->subject_code}}">
                                            </td>
                                            <td>
                                                {{$result->subject_code}}
                                            </td>
                                            <td>
                                                <input type="text" name="subject_name[]" class="subject_name position_style form-control text-left" style="width: 100%;" value="{{$result->subject_name}}">
                                                <hr>
                                                <span class="text-info fa fa-eye" style="cursor: pointer;" data-toggle="modal" data-target="#myModal{{$serial_no}}"> Suggetions</span>
                                                        <!-- Modal -->
                                                        <div id="myModal{{$serial_no}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Subject Suggetions</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-hover">
                                                                    @foreach($subjectSuggetions as $subjectSuggetion)
                                                                    <tr>
                                                                        <td>{{$result->subject_code}}</td>
                                                                        <td>{{$subjectSuggetion}}</td>
                                                                        <td><input type="button" data-subject="{{$subjectSuggetion}}" onclick="setSubjectSuggetion($(this))" value="User This" class="btn btn-sm btn-success"  data-dismiss="modal"></td>
                                                                    </tr>
                                                                    @endforeach
                                                                </table>
                                                            </div>
                                                            <!-- <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div> -->
                                                            </div>
                                
                                                        </div>
                                                        </div>
                                            </td>
                                            <td>
                                                <button type="button" class="brn btn-sm btn-info" onclick="marksheetSubjectNameUpdate($(this))">Update Subject Name Batch Wise</button>
                                            </td>
                                        </tr>
                                        @endforeach
                               </tbody>
                            </table>
                        </form>
                        </div>





                            </div>
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                    <div class="modal modal-slide-in fade" id="modals-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="add-new-record modal-content pt-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                        <input type="text" class="form-control dt-full-name"
                                            id="basic-icon-default-fullname" placeholder="John Doe"
                                            aria-label="John Doe" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-post">Post</label>
                                        <input type="text" id="basic-icon-default-post"
                                            class="form-control dt-post" placeholder="Web Developer"
                                            aria-label="Web Developer" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-email">Email</label>
                                        <input type="text" id="basic-icon-default-email"
                                            class="form-control dt-email" placeholder="john.doe@example.com"
                                            aria-label="john.doe@example.com" />
                                        <small class="form-text"> You can use letters, numbers & periods </small>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                                        <input type="text" class="form-control dt-date"
                                            id="basic-icon-default-date" placeholder="MM/DD/YYYY"
                                            aria-label="MM/DD/YYYY" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="basic-icon-default-salary">Salary</label>
                                        <input type="text" id="basic-icon-default-salary"
                                            class="form-control dt-salary" placeholder="$12000"
                                            aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
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
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a
                    class="ml-25" href="#" target="_blank">Presence 360</a><span
                    class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>

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
                        <!--                        <input type="text" id="fp-default" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />-->
                        <input type="text" id="fp-range" class="form-control flatpickr-range bg-white"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" />
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

    {{-- <script type="text/javascript">
        $(function () {
            $("#tblLocations").sortable({
                items: 'tbody tr',
                cursor: 'pointer',
                axis: 'y',
                dropOnEmpty: false,
                start: function (e, ui) {
                    ui.item.addClass("selected");
                },
                stop: function (e, ui) {
                    ui.item.removeClass("selected");
                    $(this).find("tr").each(function (index) {
                        if (index > 0) {
                            // $(this).find("td").eq(0).html((index-1));
                            $(this).find(".position").eq(0).val((index));
                            $(this).find(".position_show").eq(0).text((index));
                        }
                    });
                    saveSequence();
                }
            });
        });

        function saveSequence() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token() ?>'
                }
            });
            var batch = $('.batch').val();
            if(batch==''){
                alert('Please Select Batch');
                return false;
            }
            $('#myModalLoader').css({'display':'block','opacity':'1'});
            $.ajax({
            type:'POST',
            dataType: 'json',
            url:"{{route('marksheet-position-update')}}",
            data: $('#saveSequence').serialize(),
            success:function(data) {
                $('#myModalLoader').css({'display':'none','opacity':'0'});
                $("#msg").html(data.msg);
            }
            });

         }
        function marksheetSubjectNameUpdate($this) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '<?php echo csrf_token() ?>'
                }
            });
            var semester_id = $('#semester_id').val();
            var subject_name = $this.closest('tr').find('.subject_name').val();
            var subject_code = $this.closest('tr').find('.subject_code').val();
            var batch = $('.batch').val();
            if(semester_id==''){
                alert('Please Select Semester');
                return false;
            }
            if(subject_name==''){
                alert('Please Enter Subject Name');
                return false;
            }
            if(batch==''){
                alert('Please Select Batch');
                return false;
            }
            if(semester_id=='' || subject_name=='' || subject_code=='' || batch==''){
                alert('Please Fill Required Fields');
                return false;
            }
            $('#myModalLoader').css({'display':'block','opacity':'1'});
            $.ajax({
            type:'POST',
            dataType: 'json',
            url:"{{route('marksheet-subject-name-update')}}",
            data: {
                semester_id : semester_id,
                subject_name : subject_name,
                subject_code : subject_code,
                batch : batch,
            },
            success:function(data) {
                $('#myModalLoader').css({'display':'none','opacity':'0'});
                if(data=='true'){
                    alert('Updated Successfully.');
                }
            }
            });

         }

         function setSubjectSuggetion($this){
            var subject = $this.data('subject');
            $this.closest('tr.main-tr').find('.subject_name').val(subject);
            // alert($this.closest('tr.main-tr').find('.subject_name').val());
         }
    </script> --}}

@endsection

@extends('ums.admin.admin-meta')
@section('title') Exam Form Setting:  {{Request()->form_type}} @stop 
@section('content')
    

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col=""> --}}
  

    <div class="app-content content ">
        <h4>Subject Bulk Data</h4>



        <div class="submitss text-end me-3">
           
            <button type="submit" form="form_data"  class="btn btn-primary btn-sm mb-50 mb-sm-0r " type="submit">
                <i data-feather="check-circle"></i> Submit
            </button>
            <button  class="btn btn-warning btn-sm mb-50 mb-sm-0r " data-bs-toggle="modal" data-bs-target="#bulkUploadModal" >
                <i data-feather="refresh-cw"></i> Bulk Uplaod
            </button>
        </div>
        <form method="GET" id="form_data" action="">
            @csrf

        <div class="col-md-12">
            <div class="row align-items-center mb-1">
                <div class="col-md-4 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger ">*</span></label>
                    <select name="campus_id" id="campus_id" required  aria-controls="DataTables_Table_0" class="form-select" onChange="return $('#form_data').submit();">
                        <option value="">--Select--</option>
                        @foreach($campuses as $campus)
                        <option value="{{$campus->id}}" @if($campus->id==Request()->campus_id) selected @endif>{{$campus->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Course <span class="text-danger">*</span></label>
                    <select name="course_id" id="course_id" aria-controls="DataTables_Table_0" class="form-select" onChange="return $('#form_data').submit();">
                        <option value="">--Select--</option>
                @foreach($courses as $course)
                <option value="{{$course->id}}" @if($course->id==Request()->course_id) selected @endif>{{$course->name}}</option>
                @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <label class="form-label mb-0 me-2 col-3">Semester <span class="text-danger">*</span></label>
                    <select name="semester_id" aria-controls="DataTables_Table_0" class="form-select">
                        <option value="">--Select--</option>
                @foreach($semesters as $semester)
                <option value="{{$semester->id}}" @if($semester->id==Request()->semester_id) selected @endif>{{$semester->name}}</option>
                @endforeach
                    </select>
                </div>
            </div>
        </div>
        </form>






        <!-- options section end-->

        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">

            </div>
            <div class="content-body">

                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">


                                <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                        <thead>
                                            <tr>
                                                <th>SN#</th>
                                                <th>Campus</th>
                                                <th>Course</th>
                                                <th>Program</th>
                                                <th>Semester</th>
                                                <th>Stream</th>
                                                <th>Paper Code</th>
                                                <th>Paper Name</th>
                                                <th>Back Fees</th>
                                                <th>Scrutiny Fee</th>
                                                <th>Challenge Fee</th>
                                                <th>Status</th>
                                                <th>Subject Type</th>
                                                <th>Type</th>
                                                <th>Internal Maximum Mark</th>
                                                <th>Maximum Mark</th>
                                                <th>Oral</th>
                                                <th>Minimum Mark</th>
                                                <th>Credit</th>
                                                <th>Internal Marking Type</th>
                                                <th>Combined Subject Name</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subjects as $index=>$subject)
                                            <tr>
                                                <td>{{++$index}}</td>
                                                <td>{{$subject->course->campuse->name}}</td>
                                                <td>{{$subject->course->name}}</td>
                                                <td>{{$subject->category->name}}</td>
                                                <td>{{$subject->semester->name}}</td>
                                                <td>{{$subject->course->stream->name}}</td>
                                                <td>{{$subject->sub_code}}</td>
                                                <td>{{$subject->name}}</td>
                                                <td>{{$subject->back_fees}}</td>
                                                <td>{{$subject->scrutiny_fee}}</td>
                                                <td>{{$subject->challenge_fee}}</td>
                                                <td>{{$subject->status}}</td>
                                                <td>{{$subject->subject_type}}</td>
                                                <td>{{$subject->type}}</td>
                                                <td>{{$subject->internal_maximum_mark}}</td>
                                                <td>{{$subject->maximum_mark}}</td>
                                                <td>{{$subject->oral}}</td>
                                                <td>{{$subject->minimum_mark}}</td>
                                                <td>{{$subject->credit}}</td>
                                                <td>{{$subject->internal_marking_type}}</td>
                                                <td>{{$subject->combined_subject_name}}</td>
                                                <td>{{date('d-m-Y',strtotime($subject->created_at))}}</td>
                                                <td class="tableactionnew">  
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                           
                                                            <a class="dropdown-item" href="{{url('subject_list_edit')}}" onclick="editSubject('{{$subject->id}}')" >
                                                                <i data-feather="edit" class="me-50"></i>
                                                                <span>Edit</span>
                                                            </a> 
                                                           
                                                         <a class="dropdown-item" href="#" onclick="deleteSubject('{{$subject->id}}')">
                                                                <i data-feather="trash-2" class="me-50"></i>
                                                                <span>Delete</span>
                                                            </a>
                                                           
                                                        </div>
                                                    </div> 
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>


                        </div>
                    </div>
                    <!-- Modal to add new record -->
                  
{{-- model  --}}
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary ">
                <h5 class="modal-title text-white" id="bulkUploadModalLabel">Bulk File Upload</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fileInput" class="form-label">Choose a file to upload</label>
                        <input class="form-control" type="file" name="subject_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Click Here To Download Format Of Excel File</label>
                        <a href="{{url('bulk-uploading/BulkSubjects.xlsx')}}" class="btn btn-primary">Download</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

                </section>


            </div>
        </div>
    </div>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/datatables.min.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/buttons.dataTables.min.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/jquery.dataTables.min.css')}}"/>
@endsection

@section('scripts')
<script src="{{asset('assets/bootstrap/js/jquery-3.5.1.js')}}"></script>
<script src="{{asset('assets/bootstrap/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/bootstrap/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/bootstrap/js/jszip.min.js')}}"></script>
<script src="{{asset('assets/bootstrap/js/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/bootstrap/js/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/bootstrap/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/bootstrap/js/buttons.print.min.js')}}"></script>
<script>


function editSubject(slug) {
		var url = "{{url('edit_subject_bulk')}}"+"/"+slug;
		window.location.href = url;
	}
	function deleteSubject(slug) {
        var url = "{{url('delete_subject_bulk')}}"+"/"+slug;
        window.location.href = url;
    }
   $(document).ready( function () {
    $('#myTable').DataTable( {
        dom: 'Bfrtip',
        "bPaginate": false,
        buttons: [
            'excel', 'pdf', 'print'
        ]
    } );
} );
</script>
    <!-- END: Content-->
    @endsection
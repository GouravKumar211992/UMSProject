
@extends('ums.admin.admin-meta')

@section('content')
@php $course_name = ''; @endphp
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
    <style>
        @media print{
            .dt-buttons,
            .dataTables_filter,
            #form_data{
                display:none;
            }
        }
        table th{
            vertical-align: top !important;
        }
        table td,
        table th{
            border: 1px solid #000 !important;
        }
    </style>

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
              
                <div class="content-header-left col-md-5 mb-2">
                  
                    <div class="row breadcrumbs-top">
                      
                        <div class="col-12 d-flex justify-content-between mb-1 align-items-center">
                          
                          <div class="breadcrumb-wrapper">
                              <h2 class="content-header-title float-start mb-0">Degree Report</h2>
                              
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Report List</li>
                                </ol>
                            </div>

                        </div>
                        
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                      <div class="form-group breadcrumb-right mt-2"> 
                        <form method="get" id="form_data">

                        <button  class="btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><i data-feather="check-circle" ></i>Get Report</button>
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light">Remove Image</button>
                                <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50 waves-effect waves-float waves-light" onClick="window.location.reload()"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>  Reset</button>                    
                              </div>
							<!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                    </div>
                </div>
            </div>
            <div class="content-body">
            <div class="row mb-2">
            <div class="col-md-3">
                    <span style="color: black;">Campus Name:</span>
                    <select data-live-search="true" name="campus_id" id="campus_id" style="border-color: #c0c0c0;" class="form-control" onChange="return $('#form_data').submit();">
                        <option value="">--Choose Campus--</option>
                        @foreach($campuses as $campus)
                            <option value="{{$campus->id}}" @if(Request()->campus_id==$campus->id) selected @endif >{{$campus->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
            <span style="color: black;">Courses:</span>
            <select data-live-search="true" name="course_id" id="course_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onChange="return $('#form_data').submit();">
                <option value="">--Choose Course--</option>
                @foreach($courses as $course)
                    <option value="{{$course->id}}" @if(Request()->course_id==$course->id) selected @endif >{{$course->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
                    <span style="color: black;">Semester:</span>
                    <select data-live-search="true" name="semester_id" id="semester_id" style="border-color: #c0c0c0;" class="form-control js-example-basic-single">
                        @foreach($semesters as $semester)
                        @endforeach
                        @if($semesters->count()>0)
                        <option value="{{$semester->id}}" @if(Request()->semester_id==$semester->id) selected @endif >{{$semester->name}}</option>
                        @endif
                    </select>
                </div>
        <div class="col-sm-3">
                    <span style="color: black;">Session:</span>
                    <select name="session" id="session" style="border-color: #c0c0c0;" class="form-control" required>
                        <option value="">--Choose Session--</option>
                        @foreach($sessions as $session)
                        <option value="{{$session->academic_session}}" @if(Request()->session ==$session->academic_session) selected @endif >{{$session->academic_session}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <span style="color: black;">Result Type:</span>
                    <select name="back_status_text" id="back_status_text" class="form-control">
                        <option value="REGULAR" @if(Request()->back_status_text=='REGULAR') selected @endif >REGULAR</option>
                        <option value="BACK" @if(Request()->back_status_text=='BACK') selected @endif >BACK</option>
                        <option value="SPLBACK" @if(Request()->back_status_text=='SPLBACK') selected @endif >SPLBACK</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <span style="color: black;">Status:</span>
                    <select name="result_type" id="result_type" class="form-control">
                        <option value="">--Select Type--</option>
                        <option value="new" @if(Request()->result_type=='new') selected @endif >New</option>
                        <option value="old" @if(Request()->result_type=='old') selected @endif >Old</option>
                    </select>
                </div>
               </div>
            </form>

				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
                                    @php $loop_max = $semesters->count(); @endphp

                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            <thead>
                                                <tr>
                                                    <th>SERIAL</th>
                                                    <th>ROLLNO</th>
                                                    <th>UNIVERSITY / COLLEGE NAME</th>
                                                    <th>COURSE NAME</th>
                                                    <th>ENROLLMENT</th>
                                                    <th>NAME</th>
                                                    <th>HINDI NAME</th>
                                                    <th>FATHERS NAME</th>
                                                    <th>MOTHERS NAME</th>
                                                    <th>SESSION</th>
                                                    <th>PHOTO PATH</th>
                                                    <th>CATEGORY</th>
                                                    <th>DISABLED</th>
                                                    <th>GENDER</th>
                                                    <th>ADDRESS	</th>
                                                    <th>SEMESTER</th>
                                                    <th>CONTACT NO.</th>
                                                    <th>ALTERNATE CONTACT NO.</th>
                                                    <th>LATERAL</th>
                                                    <th>BACK IN SEM / YEAR</th>
                                                    @for($i=1;$i<=$loop_max;$i++)
                                                    <th><?php echo $i ?> SEM / YEAR RESULT STATUS</th>
                                                    <th><?php echo $i ?> SEM / YEAR QP</th>
                                                    <th><?php echo $i ?> SEM / YEAR SGPA</th>
                                                    <th><?php echo $i ?> SEM / YEAR OBT. MARKS</th>
                                                    <th><?php echo $i ?> SEM / YEAR MAX MARKS</th>
                                                    <th><?php echo $i ?> SEM / YEAR CREDIT</th>
                                                    @endfor
                                                    <th>TOTAL OBT. MARKS</th>
                                                    <th>TOTAL MAX MARKS</th>
                                                    <th>TOTAL QP</th>
                                                    <th>TOTAL CREDIT</th>
                                                    <th>CGPA</th>
                                                    <th>ELIGIBLE</th>
                                                    <th>PERCENTAGE</th>
                                                    <th>UG / PG</th>
                                                    <th class="text-center remove_image">Image</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($results as $index => $result)
                                                <tr>
                                                    <td>{{++$index}}</td>
                                                    <td>{{$result->roll_no}}</td>
                                                    <td>{{$result->course->campuse->name}}</td>
                                                    <td>{{$result->course_description()}}</td>
                                                    @php $course_name = $result->course_name(); @endphp
                                                    <td>{{$result->enrollment_no}}</td>
                                                    @if($result->student)
                                                    <td>{{$result->student->full_name}}</td>
                                                    <td>{{$result->student->hindi_name ? $result->student->hindi_name : 'N/A'}}</td>
                                                    <td>{{$result->student->father_name}}</td>
                                                    <td>{{$result->student->mother_name}}</td>
                                                    <td>{{$result->exam_session}}</td>
                                                    <td>{{$result->student->photo_path ? $result->student->photo_path : 'N/A'}}</td>
                                                    <td>{{$result->student->category ? $result->student->category : 'N/A'}}</td>
                                                    <td>{{$result->student->disabilty_category ? $result->student->disabilty_category : 'N/A'}}</td>
                                                    <td>{{$result->student->gender}}</td>
                                                    <td>{{$result->student->address ? $result->student->address : 'N/A'}}</td>
                                                    <td>{{$result->semesters->name}}</td>
                                                    <td>{{$result->student->mobile}}</td>
                                                    <td>{{$result->student->alternate_mobile ? $result->student->alternate_mobile : 'N/A'}}</td>
                                                    @else
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{$result->exam_session}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    @endif
                                                    <td>{{$result->lateral ? $result->lateral : 'N/A'}}</td>
                                                    <td>{{$result->back_in_semester ? $result->back_in_semester : 'N/A'}}</td>
            
                                                    @php
                                                        $total_obtained_marks = 0;
                                                        $total_required_marks = 0;
                                                    @endphp
                                                   @foreach($semesters as $semester)
                                                    @php $result_data = $semester->result_data($result->roll_no,1); @endphp
                                                    @php $result_data_single = $semester->result_data($result->roll_no,0); @endphp
                                                    @if($result_data)
                                                    <td>{{$result_data->result_full_text}}</td>
                                                    <td>{{$result_data->qp}}</td>
                                                    <td>{{$result_data->sgpa}}</td>
                                                    <td>
                                                        @php
                                                        $total = 0;
                                                        foreach($result_data_single as $get_semester_result_single){
                                                            if($get_semester_result_single->credit > 0){
                                                                $total = ($total + (int)$get_semester_result_single->total_marks);
                                                            }
                                                        }
                                                        $total_obtained_marks = ($total_obtained_marks + $total);
                                                        @endphp
                                                        {{$total}}
                                                    </td>
                                                    <td>
                                                        @php
                                                        $total = 0;
                                                        foreach($result_data_single as $get_semester_result_single){
                                                            if($get_semester_result_single->credit > 0){
                                                                $total = ($total + (int)$get_semester_result_single->max_total_marks);
                                                                if($result->course_id=='49' || $result->course_id=='64'){
                                                                    $total = (int)$get_semester_result_single->required_marks;
                                                                }
                                                            }
                                                        }
                                                        $total_required_marks = ($total_required_marks + $total);
                                                        @endphp
                                                        {{$total}}
                                                    </td>
                                                    <td>{{$result_data_single->sum('credit')}}</td>
                                                    @endif
                                                    @endforeach
            
                                                    @for($i=1;$i<=($loop_max-$semesters->count());$i++)
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    @endfor
            
                                                    @php
                                                        $get_semester_result_single = $result->get_semester_result_single();
                                                        $percentage = 0;
                                                        if($total_obtained_marks>0 && $total_required_marks>0){
                                                            $percentage = (($total_obtained_marks / $total_required_marks)*100);
                                                        }
                                                    @endphp
                                                    <td>{{$total_obtained_marks}}</td>
                                                    <td>{{$total_required_marks}}</td>
                                                    <td>{{@$get_semester_result_single->total_qp}}</td>
                                                    <td>{{@$get_semester_result_single->total_semester_credit}}</td>
                                                    <td>{{@$get_semester_result_single->cgpa}}</td>
                                                    <td>@if($result->eligible_for_medal()) Eligible @endif</td>
                                                    <td>
                                                        {{number_format((float)$percentage, 2, '.', '')}}
                                                    </td>
                                                    <td>-</td>
                                                    <td class="remove_image">
                                                    @php
                                                        $examData = \App\Models\ums\ExamFee::withTrashed()->where('roll_no',$result->roll_no)->first();
                                                        $student_details = \App\Models\ums\Student::withTrashed()->where('roll_number',$result->roll_no)->first();
                                                        @endphp
                                                        @if($student_details && $student_details->photo)
                                                        <img src="{{$student_details->photo}}" class="photo_download_image">
                                                        <a href="{{$student_details->photo}}" download="{{$result->roll_no}}.jpg" data-roll_no="{{$result->roll_no}}" class="btn btn-success photo_download">Download</a>
                                                        @elseif($examData && $examData->photo)
                                                        <img src="{{$examData->photo}}" class="photo_download_image">
                                                        <a href="{{$examData->photo}}" download="{{$result->roll_no}}.jpg" data-roll_no="{{$result->roll_no}}" class="btn btn-success photo_download">Download</a>
                                                        @endif
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

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <div class="modal fade" id="reallocate" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-4 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Re-Allocate Incident</h1>
					<p class="text-center">Enter the details below.</p>

					<div class="row mt-2"> 
						
						<div class="col-md-12 mb-1">
							<label class="form-label">Re-Allocate To <span class="text-danger">*</span></label>
							<select class="form-select select2">
                                <option>Select</option>
                            </select>
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">Re-Allocate Dept. <span class="text-danger">*</span></label>
							<select class="form-select select2">
                                <option>Select</option>
                            </select>
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">PDC Date <span class="text-danger">*</span></label>
							<input type="date" class="form-control" placeholder="Enter Name" />
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">Remarks <span class="text-danger">*</span></label>
							<textarea class="form-control"></textarea>
						</div>
                          
                         
				    </div>
                </div>
				
				<div class="modal-footer justify-content-center">  
						<button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
					<button type="reset" class="btn btn-primary">Re-Allocate</button>
				</div>
			</div>
		</div>
	</div>
    
      
    
	 
    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
		<div class="modal-dialog sidebar-sm">
			<form class="add-new-record modal-content pt-0"> 
				<div class="modal-header mb-1">
					<h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Select Date Range</label>
						  <input type="text" id="fp-range" class="form-control flatpickr-range" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
					</div>
					
					<div class="mb-1">
						<label class="form-label">Select Incident No.</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Select Customer</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Assigned To</label>
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
							<option>Re-Allocatted</option>
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
        $(document).ready( function () {
         $('#myTable22').DataTable( {
             dom: 'Bfrtip',
             "bPaginate": false,
             buttons: [
                 'excel', 'pdf', 'print'
             ]
         } );
     
         $('.photo_download_image').click(function(){
             $('.photo_download').each(function( index ) {
                 alert();
                 var current_href = $(this).attr('href');
                 var roll_no = $(this).data('roll_no');
                 var a = $("<a>")
                     .attr("href", current_href)
                     .attr("download", roll_no+".jpg")
                     .appendTo("body");
     
                 a[0].click();
     
                 a.remove();
             });
         });
     
     });
     
     $('#dd').on('click', function(e){
         var html = "<html><head><meta charset='utf-8' /></head><body>" + document.getElementsByTagName("table")[0].outerHTML + "</body></html>";
         var blob = new Blob([html], { type: "application/vnd.ms-excel" });
         var a = document.getElementById("dd");
         // Use URL.createObjectURL() method to generate the Blob URL for the a tag.
         a.href = URL.createObjectURL(blob);
         var selmonth = $("#month option:selected").text();
         var selyear = $("#year option:selected").text();
         var show_agreement_type = $("#agreement_type option:selected").text();
         $('.show_agreement_type').text(show_agreement_type);
         // Set the download file name.
         a.download = "{{$course_name}} Degree_Report.xls";
     });
     
     </script>
   
{{-- </body> --}}
@endsection

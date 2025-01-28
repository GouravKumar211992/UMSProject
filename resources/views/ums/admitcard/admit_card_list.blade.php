@extends("ums.admin.admin-meta")
@section("content")


<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
             <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Admit Card List</h2>
                            <div class="breadcrumb-wrapper">
                              
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">List of Admins</li>
                                </ol>
                            
                            </div>
                        </div>
                    </div>
                </div> 
				 <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                      <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="/Bulk_Admit_Card_Approval"><i data-feather="file-text"></i> Bulk Approve</a>  
                      <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
                    <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" onclick="window.location.reload();"><i data-feather="refresh-cw"></i> Reset</button>   
                    </div>
                </div>
            </div>
            <div class="content-body dasboardnewbody">
                 
                <!-- ChartJS section start -->
                <section id="chartjs-chart">
                    <div class="row">
						
						  
						
						<div class="col-md-12 col-12">
                            <div class="card  new-cardbox"> 
								 <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Enrollment No.</th>
                                                <th>Roll No.</th>
                                                <th>Course</th>
                                                <th>Semester</th>
                                                <th>Student Name</th>
                                                <th>Mobile No.</th>
                                                <th>Mailing Address</th>
                                                <th>status</th>
                                                <th>Form Type</th>
                                                <th>Created At </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                      
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
                                        <td>
                                            <div class="dropdown text-center">
                                                <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="true">
                                                    <img src="/assets/admin/img/dot.svg" class="editbtn">
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                                    <ul>
                                                        <li><a target="_new" href="{{route('single_icard',[$student->id])}}">View iCard</a></li> 
                                                        <!--li><a href="{{route('singleicard',[$student->id])}}">Edit iCard</a></li--> 
                                                        <li><a onClick="return confirm('Are you sure?');" href="{{route('single-icard-delete',[$student->id])}}">Delete</a></li> 
                                                    </ul>
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
                <!-- ChartJS section end -->

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

@endsection
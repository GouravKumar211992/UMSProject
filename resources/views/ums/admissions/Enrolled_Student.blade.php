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
                            <h2 class="content-header-title float-start mb-0">Enrolled Students</h2>
                            <div class="breadcrumb-wrapper">
                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
						{{-- <a class="btn btn-primary btn-sm mb-50 mb-sm-0" href="#"> Remove pagination</a>  --}}
                        <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" onclick="window.location.reload();"><i data-feather="refresh-cw"></i> Reset</button>
                        <a class="btn btn-primary btn-sm mb-50 mb-sm-0" href="{{ url('enrollment_summary')}}"> Enrollment Summary</a> 

                    </div>
                </div>
            </div>
            <div class="content-body">
                 
                
				
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
									<table class="datatables-basic table myrequesttablecbox "> 
                                            <thead>
                                             <tr>
												<th>Sr.No</th>
												<th>Cheak All</th>
												<th>Campuse</th>
												<th>Course</th>
												<th>Enrollment Number</th>
												<th>Roll No.</th>
												<th>Student Name</th>
												<th>Email</th>
												<th>Father Name</th>
												<th>Gender</th>
												<th>Disability Category</th>
												<th>Academic Session</th>
												
											  </tr>
											</thead>
											<tbody>
										 @if(count($applications) > 0)
                                @php $serial_no = ((($current_page - 1) * $per_page) + 1); @endphp
                                @foreach( $applications as $index => $app)
                                <tbody>
									<tr>
                                        <td>{{$serial_no++}}</td>
                                        <td><input type="checkbox" class="checkboxstyle cancel_enrollment" name="cancel_enrollment"></td>
                                        <td>{{($app->campus)?$app->campus->name:''}}</td>
                                        <td>{{($app->counselled_course)?$app->counselled_course->name:''}}</td>
                                        <td>{{@$app->enrollment->enrollment_no}}</td>
                                        <td>{{@$app->enrollment->roll_number}}<input type="hidden" name="roll_number[]" value="{{@$app->enrollment->roll_number}}"></td>
                                        <td>{{$app->full_name}}</td>
                                        <td>{{$app->email}}<input type="hidden" name="email[]" value="{{$app->email}}"></td>
                                        <td>{{$app->father_first_name}}</td>
                                        <td>{{$app->gender}}</td>
                                        <td>{{$app->disability_category}}</td>
                                        <td>{{$app->academic_session}}</td>
                                    </tr>
								</tbody>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="14" class="text-center">NO DATA FOUND</td>
                                    </tr>
                                @endif
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
						<label class="form-label">Service Name</label>
						<select class="form-select">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Menu Name</label>
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

@endsection
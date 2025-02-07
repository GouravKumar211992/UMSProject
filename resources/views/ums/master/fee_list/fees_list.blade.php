
@extends('ums.admin.admin-meta')

@section('content')
    
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        @include('ums.admin.notifications')
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Fee</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Fee List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                      <a class="btn  btn-dark btn-sm mb-50 mb-sm-0" href="add_fee_list"><i data-feather="plus-circle"></i> Add Fee</a> 
                        <button class="btn  btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>
                            Reset</button> 
                    </div>
                </div>
            </div>
            <div class="content-body">
                 
                
				
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
									<table class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome"> 
                                        <thead>
                                             <tr>
												<th>#ID</th>
												<th>Campus Name</th>
												<th>Course Name</th>
												<th>Academic Session</th>
												<th>Seat</th>
												<th>Basic Eligibility</th>
												<th>Mode Of Admission</th>
												<th>Course Duration</th>
                                                <th>Tution Fee for Divyang Per Sem</th>
                                                <th>Tution Fee for Other Per Sem</th>
                                                <th>Payable Fee for Divyang Per Sem</th>
                                                <th>Payable Fee for Other Per Sem</th>
                                                <th>Action</th>
											  </tr>
											</thead>
                                            @if(count($all_fee) > 0)
                                            @foreach($all_fee as $fee)
											<tbody>
												
                                           
												  <tr>
										<td>#{{$fee->id}}</td>
										<td>{{$fee->course->campuse->name}}</td>
										<td>{{$fee->course->name}}</td>
										<td>{{$fee->academic_session}}</td>
										<td>{{$fee->seat}}</td>
										<td>{{$fee->basic_eligibility}}</td>
										<td>{{$fee->mode_of_admission}}</td>
										<td>{{$fee->course_duration}}</td>
										<td>{{$fee->tuition_fee_for_divyang_per_sem}}</td>
										<td>{{$fee->tuition_fee_for_other_per_sem}}</td>
										<td>{{$fee->payable_fee_for_divyang_per_sem}}</td>
										<td>{{$fee->payable_fee_for_other_per_sem}}</td>
                                        <td>
                                                    
													<td class="tableactionnew">
														<div class="dropdown">
															<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
																<i data-feather="more-vertical"></i>
															</button>
															<div class="dropdown-menu dropdown-menu-end">
																<a class="dropdown-item" href="{{route('fee_list_edit',$fee->id)}}">
																	<i data-feather="edit-3" class="me-50"></i>
																	<span>Edit</span>
																</a>
                                                               
																<a class="dropdown-item" href="{{route('delete_fee',$fee->id)}}">
																	<i data-feather="trash-2" class="me-50"></i>
                                                          <span onclick="return confirm('Are you sure?');">Delete</span>                                                                </a>
																</a> 
															</div>
														</div>
													</td>
												  </tr>
												
											   </tbody>
                                               @endforeach
                                               @else
                                                   <tr>
                                                       <td colspan="12" class="text-center">NO DATA FOUND</td>
                                                   </tr>
                                               @endif

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

    
	
    @include('ums.admin.search-model', ['searchTitle' => 'Campus List Search'])

    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
		<div class="modal-dialog sidebar-sm">
			<form id="approveds-form" class="add-new-record modal-content pt-0" method="GET" action="{{ url('fees_list') }}"> 
				<div class="modal-header mb-1">
					<h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					
					
					<div class="mb-1">
						<label class="form-label">Course Name</label>
						<select class="form-select" name="course">
							<option value=""></option>
                                @foreach($courses as $course)
                                <option value="{{$course->id}}">{{$course->name}}</option>
                                @endforeach
						</select>
					</div> 
                    
                     
					 
				</div>
				<div class="modal-footer justify-content-start">
					<button type="submit" class="btn btn-primary data-submit mr-1">Apply</button>
					<button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

{{-- </body> --}}
@endsection

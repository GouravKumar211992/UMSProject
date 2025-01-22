
@extends('admin.admin-meta')

@section('content')
    
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
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
											<tbody>
												
                                           
												  <tr>
													<td>#5</td>
													<td>	Dr. Shakuntala Misra National Rehabilitation University</td>
													<td>B.B.A.</td>
													<td>
                                                        2021-2022
                                                     </td>
                                                     <td> 60</td>
                                                     <td>10+2 OR EQUIVALENT</td>
                                                     <td>Online</td>
                                                     <td>3</td>
                                                     <td>	0</td>
                                                     <td>19000</td>
                                                     <td>21315</td>
                                                     <td>135615</td>
                                                    
													<td class="tableactionnew">
														<div class="dropdown">
															<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
																<i data-feather="more-vertical"></i>
															</button>
															<div class="dropdown-menu dropdown-menu-end">
																<a class="dropdown-item" href="fee_list_edit">
																	<i data-feather="edit-3" class="me-50"></i>
																	<span>Edit</span>
																</a>
                                                               
																<a class="dropdown-item" href="#">
																	<i data-feather="trash-2" class="me-50"></i>
                                                          <span onclick="return confirm('Are you sure?');">Delete</span>                                                                </a>
																</a> 
															</div>
														</div>
													</td>
												  </tr>
												  <tr>
													<td>#5</td>
													<td >	Dr. Shakuntala Misra National Rehabilitation University</td>
													<td>B.B.A.</td>
													<td>
                                                        2021-2022
                                                     </td>
                                                     <td> 60</td>
                                                     <td>10+2 OR EQUIVALENT</td>
                                                     <td>Online</td>
                                                     <td>3</td>
                                                     <td>	0</td>
                                                     <td>19000</td>
                                                     <td>21315</td>
                                                     <td>135615</td>
                                                    
													<td class="tableactionnew">
														<div class="dropdown">
															<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
																<i data-feather="more-vertical"></i>
															</button>
															<div class="dropdown-menu dropdown-menu-end">
																<a class="dropdown-item" href="#">
																	<i data-feather="edit-3" class="me-50"></i>
																	<span>Edit</span>
																</a>
                                                               
																<a class="dropdown-item" href="#">
																	<i data-feather="trash-2" class="me-50"></i>
                                                         <span onclick="return confirm('Are you sure?');">Delete</span>                                                                </a>
																</a> 
															</div>
														</div>
													</td>
												  </tr>
												  <tr>
													<td>#5</td>
													<td >	Dr. Shakuntala Misra National Rehabilitation University</td>
													<td>B.B.A.</td>
													<td>
                                                        2021-2022
                                                     </td>
                                                     <td> 60</td>
                                                     <td>10+2 OR EQUIVALENT</td>
                                                     <td>Online</td>
                                                     <td>3</td>
                                                     <td>	0</td>
                                                     <td>19000</td>
                                                     <td>21315</td>
                                                     <td>135615</td>
                                                    
													<td class="tableactionnew">
														<div class="dropdown">
															<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
																<i data-feather="more-vertical"></i>
															</button>
															<div class="dropdown-menu dropdown-menu-end">
																
																<a class="dropdown-item" href="#">
																	<i data-feather="edit-3" class="me-50"></i>
																	<span>Edit</span>
																</a>
                                                               
																<a class="dropdown-item" href="#">
																	<i data-feather="trash-2" class="me-50"></i>
                                                         <span onclick="return confirm('Are you sure?');">Delete</span>                                                                </a>
																</a> 
															</div>
														</div>
													</td>
												  </tr>
												
												 
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

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a class="ml-25" href="#" target="_blank">Presence 360</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
        
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

{{-- </body> --}}
@endsection

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
                            <h2 class="content-header-title float-start mb-0">Entrance Exam Schedule</h2>
                            <div class="breadcrumb-wrapper">
                                
                            </div>
                        </div>
                    </div>
                </div> 
				 <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                    <a href="add-request.html" class="btn btn-primary  btn-sm mb-50 mb-sm-0"><i data-feather="plus-square" ></i> Add Entrance Exam Schedule</a>   

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
                                                <th>Campuse/College</th>
                                                <th>Course (Session)</th>
                                                <th>Examination (Date)</th>
                                                <th>Reporting (Timing)</th>
                                                <th>Examination Start-End Time</th>
                                                <th>Center Name</th>
                                                <th>Roll Number Total (Generated)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td class="fw-bolder text-dark">Dr. Shakuntala Misra National Rehabilitation University</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Nishu Garg</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Home</td> 
                                               
                                                <td>
                                                    <div class="action-rec">
                                                        <a href="#" class="me-50"><i data-feather="edit-3"></i></a>
                                                        <a href="#" class="text-danger"><i data-feather="trash-2"></i></a>
                                                    </div>
                                                </td>
                                                  
                                               
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td class="fw-bolder text-dark">Dr. Shakuntala Misra National Rehabilitation University</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Kundan Kumar</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Term</td> 

                                                <td>
                                                    <div class="action-rec">
                                                        <a href="#" class="me-50"><i data-feather="edit-3"></i></a>
                                                        <a href="#" class="text-danger"><i data-feather="trash-2"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td class="fw-bolder text-dark">Dr. Shakuntala Misra National Rehabilitation University</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Rahul Upadhyay</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Vehicle</td> 
                                                
                                                <td>
                                                    <div class="action-rec">
                                                        <a href="#" class="me-50"><i data-feather="edit-3"></i></a>
                                                        <a href="#" class="text-danger"><i data-feather="trash-2"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td class="fw-bolder text-dark">Dr. Shakuntala Misra National Rehabilitation University</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Ashish Kumar</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Home</td> 
                                                
                                                <td>
                                                    <div class="action-rec">
                                                        <a href="#" class="me-50"><i data-feather="edit-3"></i></a>
                                                        <a href="#" class="text-danger"><i data-feather="trash-2"></i></a>
                                                    </div>
                                                </td>
                                             
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td class="fw-bolder text-dark">Dr. Shakuntala Misra National Rehabilitation University</td>
                                                <td class="fw-bolder text-dark">APP001</td>
                                                <td>20-07-2024</td>
                                                <td>Inder Singh</td>
                                                <td>nishu@gmail.com</td>
                                                <td>9876787656</td>
                                                <td>Term</td> 
                                                <td>
                                                    <div class="action-rec">
                                                        <a href="#" class="me-50"><i data-feather="edit-3"></i></a>
                                                        <a href="#" class="text-danger"><i data-feather="trash-2"></i></a>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
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
 

@endsection
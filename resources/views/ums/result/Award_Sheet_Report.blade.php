@extends('admin.admin-meta')
@section("content")
<!-- BEGIN: Body-->

{{-- <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
  
<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
             <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Award Sheet Report</h2>
                            
                        </div>
                    </div>
                </div> 
                <div class="customernewsection-form poreportlistview p-1">
                  <div class="row"> 
                      <div class="col-md-4">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Campuse:</label>
                              <select class="form-select select2">
                                  <option>Select</option> 
                              </select> 
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Courses:</label>
                              <select class="form-select">
                                  <option>Select</option>
                                  <option>Raw Material</option>
                                  <option>Semi Finished</option>
                                  <option>Finished Goods</option>
                                  <option>Traded Item</option>
                                  <option>Asset</option>
                                  <option>Expense</option>
                              </select> 
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Semester:</label>
                              <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct mb-25" />
                          </div>
                      </div>
                  </div>
              
                  <div class="row">
                      <div class="col-md-4">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Sub Code:</label>
                              <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct mb-25" />
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Academic Session:</label>
                              <select class="form-select select2"> 
                                  <option value="">Select Academic Session</option>
                                  <option value="2021-2022">2021-2022</option>
                                  <option value="2022-2023">2022-2023</option>
                                  <option value="2023-2024">2023-2024</option>
                                  <option value="2023-2024FEB">2023-2024FEB</option>
                                  <option value="2023-2024JUL">2023-2024JUL</option>
                                  <option value="2023-2024AUG">2023-2024AUG</option>
                                  <option value="2024-2025">2024-2025</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Exam Type:</label>
                              <select class="form-select select2"> 
                                  <option>Select</option>
                                  <option>Raw Material</option>
                                  <option>Semi Finished</option>
                                  <option>Finished Goods</option>
                                  <option>Traded Item</option>
                                  <option>Asset</option>
                                  <option>Expense</option>
                              </select>
                          </div>
                      </div>
                  </div>
              
                  <div class="row">
                      <div class="col-md-4">
                          <div class="mb-1 mb-sm-0"> 
                              <label class="form-label">Batch:</label>
                              <select class="form-select">
                                  <option>Select</option>
                                  <option>Raw Material</option>
                                  <option>Semi Finished</option>
                                  <option>Finished Goods</option>
                                  <option>Traded Item</option>
                                  <option>Asset</option>
                                  <option>Expense</option>
                              </select> 
                          </div>
                      </div>
                      <div class="col-sm-2">
                        <br>
                        <button type="submit" class="btn btn-primary" name="submit_form">Get Report</button>
                    </div>  
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
                            <th>SN#</th>
                            <th>Enrollment No.</th>
                            <th>Roll No.</th>
                            <th>External Marks</th>
                            <th>External marks with words</th>
                           
                        </tr>
                    </thead>
                </div>
								
								  
                            
                                
								 
								 
                                
                          </div>
                        </div>
						
						
						 
                         
                         
                    </div>
					
					 
                     
                </section>
                <!-- ChartJS section end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
  @endsection
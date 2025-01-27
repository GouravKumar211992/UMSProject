@extends('ums.admin.admin-meta')

@section('content')

{{-- <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
  
<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
          <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Mark Filling Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>  
                                <li class="breadcrumb-item active">Filling Reports</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right"> 
                    
                    <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                    <a class="btn  btn-primary  btn-sm mb-50 mb-sm-0" href="">
                        <i data-feather="check-circle"></i> Get Report
                    </a>  
                    <button class="btn   btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal">
                         Manage Subjects
                    </button> 
                    <!-- Reset Button -->
                    
                </div>
            </div>
            
        </div>
                
                <div class="customernewsection-form poreportlistview p-1">
                    <div class="row"> 
                        <div class="col-md-2">
                            <div class="mb-1 mb-sm-0"> 
                                <label class="form-label">Campus:</label>
                                <select class="form-select select2">
                                    <option>Select</option> 
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-2">
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
                        <div class="col-md-3">
                            <div class="mb-1 mb-sm-0"> 
                                <label class="form-label">Semester Type:</label>
                                <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct mb-25" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-1 mb-sm-0"> 
                                <label class="form-label">Semester:</label>
                                <select class="form-select">
                                    <option>Select</option>
                                    <option>2021-2022</option>
                                    <option>2022-2023</option>
                                    <option>2023-2024</option>
                                    <option>2024-2025</option>
                                </select> 
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="mb-1 mb-sm-0"> 
                                <label class="form-label">Academic Session:</label>
                                <input type="date" class="form-control" />
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
                                            <tr class="text-center">
                                                <th rowspan="2">SN#</th>
                                                <th rowspan="2">Semester</th>
                                                <th rowspan="2">Paper Code</th>
                                                <th rowspan="2">Paper Name</th>
                                                <th rowspan="2">Internal Max</th>
                                                <th rowspan="2">External Max</th>
                                                <th rowspan="2">Credit</th>
                                                <th colspan="3">Filled</th>
                                                <th rowspan="2">Total To Be Filled</th>
                                            </tr>
                                            <tr class="text-center">
                                                <th>Internal</th>
                                                <th>External</th>
                                                <th>Practical</th>
                                            </tr>
                                        </thead>
                                        
                                    </table>
                                </div>
								
								  
                            
                                
								 
								 
                                
                          </div>
                        </div>
						
						
						 
                         
                         
                    </div>
					
					 
                     
                </section>
                <!-- ChartJS section end -->

            </div>
    </div>
 
{{-- </body> --}}
@endsection


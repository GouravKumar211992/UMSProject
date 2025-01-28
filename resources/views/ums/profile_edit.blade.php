@extends('ums.admin.admin-meta')

@section('content')
    
<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">

  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header pocreate-sticky">
				<div class="row">
					<div class="content-header-left col-md-6 mb-2">
						<div class="row breadcrumbs-top">
							<div class="col-12">
								<h2 class="content-header-title float-start mb-0 border-0">Vendor Registration</h2> 
							</div>
						</div>
					</div>
					<div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
						<div class="form-group breadcrumb-right">   
							<button onclick="javascript: history.go(-1)" class="btn btn-secondary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg> Back</button>  
							<button onclick="javascript: history.go(-1)" class="btn btn-primary btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Submit</button> 
						</div>
					</div>
				</div>
			</div>
            <div class="content-body">
                 
                
				
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">  
							
                            <div class="card">
								 <div class="card-body customernewsection-form"> 
											 
											<div class="row">
												<div class="col-md-12">
                                                    <div class="newheader  border-bottom mb-2 pb-25"> 
														<h4 class="card-title text-theme">Basic Information</h4>
														<p class="card-text">Fill the details</p> 
													</div>
                                                </div>
                                                
                                                
                                                <div class="col-md-9"> 
                                                    
                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Vendor Code <span class="text-danger">*</span></label>  
                                                        </div>  
  
                                                        <div class="col-md-6"> 
                                                            <input type="text" class="form-control" value="VEN001">
                                                        </div> 
                                                     </div>
                                                    
                                                    <div class="row align-items-center mb-1"> 
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Vendor Type <span class="text-danger">*</span></label>  
                                                        </div> 
                                                        
                                                        <div class="col-md-5"> 
                                                            <div class="demo-inline-spacing">
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input type="radio" id="customColorRadio1" name="customColorRadio1" class="form-check-input" checked="">
                                                                    <label class="form-check-label fw-bolder" for="customColorRadio1">Organisation</label>
                                                                </div> 
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input type="radio" id="customColorRadio2" name="customColorRadio1" class="form-check-input">
                                                                    <label class="form-check-label fw-bolder" for="customColorRadio2">Individual</label>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row align-items-center mb-1"> 
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Orgnization Type <span class="text-danger">*</span></label>  
                                                        </div> 
                                                        
                                                        <div class="col-md-5">  
                                                            <select class="form-select">
                                                                <option>Select</option>
                                                                <option>Public Limited</option>
                                                                <option>Private Limited</option>
                                                                <option>Proprietor</option>
                                                                <option>Partnership</option>
                                                                <option>Small Enterprise</option>
                                                                <option>Medium Enterprise</option> 
                                                            </select>  
                                                        </div>
                                                    </div>
                                                         
                                                    
                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Company Name <span class="text-danger">*</span></label>  
                                                        </div>  
  
                                                        <div class="col-md-6"> 
                                                            <input type="text" class="form-control">
                                                        </div> 
                                                     </div>
                                                    
                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Vendor Display Name</label>  
                                                        </div>  
  
                                                        <div class="col-md-6"> 
                                                            <input type="text" class="form-control">
                                                        </div> 
                                                     </div>
                                                    
                                                      
                                                    
                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Category Mapping</label>  
                                                        </div>  

                                                        <div class="col-md-3 pe-sm-0 mb-1 mb-sm-0"> 
                                                            <select class="form-select">
                                                                <option>Catgeory</option> 
                                                            </select> 
                                                        </div>
                                                        <div class="col-md-3"> 
                                                            <select class="form-select">
                                                                <option>Sub-Category</option> 
                                                            </select>
                                                        </div>   
                                                     </div>
                                                     
                                                    
												</div>
                                                
<!--
                                                <div class="col-md-3 border-start">
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-12"> 
                                                            <label class="form-label text-primary"><strong>Status</strong></label>   
                                                             <div class="demo-inline-spacing">
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input type="radio" id="customColorRadio3" name="customColorRadio3" class="form-check-input" checked="">
                                                                    <label class="form-check-label fw-bolder" for="customColorRadio3">Active</label>
                                                                </div> 
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input type="radio" id="customColorRadio4" name="customColorRadio3" class="form-check-input">
                                                                    <label class="form-check-label fw-bolder" for="customColorRadio4">Inactive</label>
                                                                </div> 
                                                            </div> 
                                                        </div> 
                                                     </div> 
                                                    
                                                       
                                                    
                                                    
                                                </div>
-->
											</div>
											  
 
											 
											
											
											<div class="mt-1">
                                                <div class="step-custhomapp bg-light">
                                                    <ul class="nav nav-tabs my-25 custapploannav" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-bs-toggle="tab" href="#payment">KYC Details</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#Shipping">Addresses</a>
                                                        </li> 
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#amend">Contact Persons</a>
                                                        </li>
                                                    </ul>
                                                </div>
												 <div class="tab-content pb-1 px-1">
														<div class="tab-pane active" id="payment">
                                                            
                                                                 
                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">Email</label>  
                                                                    </div>  

                                                                    <div class="col-md-3"> 
                                                                        <div class="input-group input-group-merge">
                                                                            <span class="input-group-text" id="basic-addon5"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></span>
                                                                            <input type="text" class="form-control" value="hello@vendor.com" placeholder="">
                                                                        </div>
                                                                    </div> 
                                                                 </div>

                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">Phone</label>  
                                                                    </div>  

                                                                    <div class="col-md-2"> 
                                                                        <div class="input-group input-group-merge">
                                                                            <span class="input-group-text" id="basic-addon5"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></span>
                                                                            <input type="text" class="form-control" placeholder="POC No.">
                                                                        </div>
                                                                    </div> 
                                                                    <div class="col-md-2"> 
                                                                        <div class="input-group input-group-merge">
                                                                            <span class="input-group-text" id="basic-addon5"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smartphone"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg></span>
                                                                            <input type="text" class="form-control" value="987654324" placeholder="Mobile">
                                                                        </div>
                                                                    </div> 
                                                                 </div>
                                                                <div class="row mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">Whatsapp Number</label>  
                                                                    </div>  

                                                                    <div class="col-md-3"> 
                                                                        <div class="input-group input-group-merge">
                                                                            <span class="input-group-text" id="basic-addon5"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></span>
                                                                            <input type="text" class="form-control">
                                                                        </div>
                                                                        <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                            <input type="checkbox" class="form-check-input" id="colorCheck1" checked="">
                                                                            <label class="form-check-label" for="colorCheck1">Same as Mobile No.</label>
                                                                        </div>
                                                                    </div> 
                                                                 </div>
                                                            
                                                            <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">Notification</label>  
                                                                    </div>  

                                                                    <div class="col-md-4"> 
                                                                         <div class="demo-inline-spacing">
                                                                            <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                                <input type="checkbox" class="form-check-input" id="Email">
                                                                                <label class="form-check-label" for="Email">Email</label>
                                                                            </div>
                                                                             <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                                <input type="checkbox" class="form-check-input" id="SMS">
                                                                                <label class="form-check-label" for="SMS">SMS</label>
                                                                            </div>
                                                                             <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                                <input type="checkbox" class="form-check-input" id="Whatsapp">
                                                                                <label class="form-check-label" for="Whatsapp">Whatsapp</label>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                     
                                                                 </div>
                                                             
                                                            
															  <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">PAN</label>  
                                                                    </div>  

                                                                    <div class="col-md-3 mb-1 mb-sm-0"> 
                                                                        <input type="text" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-3 mb-1 mb-sm-0">
                                                                      <input type="file" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1 mb-1 mb-sm-0">
                                                                      <a href="#" class="font-small-2">Verify</a>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-3">
                                                                      <input type="text" class="form-control" placeholder="Enter OTP">
                                                                    </div>
                                                                 </div>
                                                            
                                                            <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">Tin No.</label>  
                                                                    </div>  

                                                                    <div class="col-md-3 mb-1 mb-sm-0"> 
                                                                        <input type="text" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-3 mb-1 mb-sm-0">
                                                                      <input type="file" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1 mb-1 mb-sm-0">
                                                                      <a href="#" class="font-small-2">Verify</a>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-3">
                                                                      <input type="text" class="form-control" placeholder="Enter OTP">
                                                                    </div>
                                                                 </div>
                                                            
                                                            <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">Aadhar No.</label>  
                                                                    </div>  

                                                                    <div class="col-md-3 mb-1 mb-sm-0"> 
                                                                        <input type="text" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-3 mb-1 mb-sm-0">
                                                                      <input type="file" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1 mb-1 mb-sm-0">
                                                                      <a href="#" class="font-small-2">Verify</a>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-3">
                                                                      <input type="text" class="form-control" placeholder="Enter OTP">
                                                                    </div>
                                                                 </div>

                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">Currency</label>  
                                                                    </div>  

                                                                     <div class="col-md-3"> 
                                                                        <select class="form-select">
                                                                            <option>Select</option> 
                                                                            <option selected="">INR - Indian Rupee</option> 
                                                                        </select> 
                                                                    </div> 
                                                                 </div>
                                                            
                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">Opening Balance</label>  
                                                                    </div>  

                                                                    <div class="col-md-3"> 
                                                                        <div class="input-group">
                                                                            <span class="input-group-text bg-light" id="basic-addon1">INR</span>
                                                                            <input type="text" class="form-control">
                                                                        </div>
                                                                    </div> 
                                                                 </div>
                                                                
                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label class="form-label">Payment Terms</label>  
                                                                    </div>  

                                                                     <div class="col-md-3"> 
                                                                        <select class="form-select">
                                                                            <option>Select</option> 
                                                                            <option selected="">Due on Receipt</option> 
                                                                        </select> 
                                                                    </div> 
                                                                 </div>
                                                            
                                                                 
														</div>
														 <div class="tab-pane" id="attachment">
                                                                <div class="row">
                                                                     <div class="col-md-6">
                                                                         
                                                                         <h5 class="mt-1 mb-4 text-dark"><strong>Billing Address</strong></h5>
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Country/Region</label>  
                                                                            </div>  

                                                                             <div class="col-md-6"> 
                                                                                <select class="form-select">
                                                                                    <option>Select</option>
                                                                                </select> 
                                                                            </div> 
                                                                         </div>
                                                                          
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Address</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <textarea class="form-control" placeholder="Street 1"></textarea>
                                                                            </div> 
                                                                         </div>
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                &nbsp;
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <textarea class="form-control" placeholder="Street 2"></textarea>
                                                                            </div> 
                                                                         </div>
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">City</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">State</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <select class="form-select">
                                                                                    <option>Select</option>
                                                                                </select> 
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Pin Code</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Phone</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Fax Number</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                    
                                                                     </div>
                                                                    
                                                                    <div class="col-md-6">
                                                                         
                                                                        <div class="mt-1 mb-2 d-flex flex-column">
                                                                            <h5 class="text-dark mb-0 me-1"><strong>Shipping Address</strong></h5> 
                                                                        
                                                                            <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                                <input type="checkbox" class="form-check-input" id="colorCheck2" checked="">
                                                                                <label class="form-check-label" for="colorCheck2">Same As Billing Address</label>
                                                                            </div>
                                                                        </div>
                                                                         
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Country/Region</label>  
                                                                            </div>  

                                                                             <div class="col-md-6"> 
                                                                                <select class="form-select">
                                                                                    <option>Select</option>
                                                                                </select> 
                                                                            </div> 
                                                                         </div>
                                                                          
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Address</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <textarea class="form-control" placeholder="Street 1"></textarea>
                                                                            </div> 
                                                                         </div>
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                &nbsp;
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <textarea class="form-control" placeholder="Street 2"></textarea>
                                                                            </div> 
                                                                         </div>
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">City</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">State</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <select class="form-select">
                                                                                    <option>Select</option>
                                                                                </select> 
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Pin Code</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Phone</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Fax Number</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                    
                                                                     </div>
                                                                 </div> 
														</div> 
                                                         <div class="tab-pane" id="Shipping">
															 <div class="table-responsive"> 
                                                                        <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border"> 
                                                                            <thead>
                                                                                 <tr>
                                                                                    <th>#</th>
                                                                                    <th>Country/Region</th>
                                                                                     <th>State</th>
                                                                                     <th>City</th>
                                                                                    <th>Address</th>
                                                                                    
                                                                                    
                                                                                    <th>Pin Code</th>
                                                                                    <th>Phone</th>
                                                                                    <th>Fax Number</th>
                                                                                     <th>Type</th>
                                                                                    <th>Action</th>
                                                                                  </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                     <tr>
                                                                                        <td>#</td>
                                                                                        <td>
                                                                                           <select class="form-select mw-100">
                                                                                               <option>Select</option> 
                                                                                           </select> 
                                                                                        </td>
                                                                                        <td><input type="text" class="form-control mw-100"></td> 
                                                                                        <td><input type="text" class="form-control mw-100"></td> 
                                                                                        <td>
                                                                                           <select class="form-select mw-100">
                                                                                               <option>Select</option> 
                                                                                           </select> 
                                                                                        </td> 
                                                                                        <td><input type="text" class="form-control mw-100"></td> 
                                                                                        <td><input type="text" class="form-control mw-100"></td> 
                                                                                        <td><input type="text" class="form-control mw-100"></td>
                                                                                         <td>
                                                                                            <div class="demo-inline-spacing">
                                                                                                <div class="form-check form-check-primary mt-25">
                                                                                                    <input type="radio" id="isDefaultPurchase0" name="addresses[0][is_billing]" value="" class="form-check-input">
                                                                                                    <label class="form-check-label fw-bolder" for="isDefaultPurchase0">Billing</label>
                                                                                                </div>
                                                                                                <div class="form-check form-check-primary mt-25">
                                                                                                    <input type="radio" id="isDefaultSelling0" name="addresses[0][is_shipping]" value="" class="form-check-input">
                                                                                                    <label class="form-check-label fw-bolder" for="isDefaultSelling0">Shipping</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                         <td>
                                                                                            <a href="#" class="text-danger delete-contact-row"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                                                                            <a href="#" class="text-primary add-contact-row"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square me-50"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg></a>
                                                                                        </td>
                                                                                      </tr>
                                                                                    
                                                                                    <tr>
                                                                                        <td>1</td>
                                                                                        <td>India</td>
                                                                                        <td>Plot No. 14</td> 
                                                                                        <td>Gautam Budh Nagar</td> 
                                                                                        <td>Noida</td> 
                                                                                        <td>201301</td> 
                                                                                        <td>9876787656</td> 
                                                                                        <td>-</td> 
                                                                                        <td>Billing</td> 
                                                                                        <td><a href="#" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td>
                                                                                      </tr>


                                                                               </tbody>


                                                                        </table>
                                                                    </div>
                                                            
                                                            <a href="#" class="text-primary add-contactpeontxt"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Add New Address</a>
														</div>
                                                      
														<div class="tab-pane" id="amend">
															 <div class="table-responsive">
                                                                <table class="table myrequesttablecbox table-striped "> 
                                                                    <thead>
                                                                         <tr>
                                                                        <th>#</th>
                                                                        <th class="px-1">Salutation</th>
                                                                        <th class="px-1">Name</th> 
                                                                        <th class="px-1">Email</th>
                                                                        <th class="px-1">Mobile</th>
                                                                        <th class="px-1">Work Phone</th>
                                                                        <th class="px-1">Primary</th>
                                                                        <th class="px-1">Action</th>
                                                                      </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                         <tr valign="top">
                                                                            <td>1</td>
                                                                             <td class="px-1">
                                                                                 <select class="form-select">
                                                                                    <option>Select</option> 
                                                                                </select>
                                                                             </td>
                                                                            <td class="px-1"><input type="text" class="form-control"></td>
                                                                            <td class="px-1"><input type="text" class="form-control"></td>
                                                                            <td class="px-1"><input type="text" class="form-control"></td>
                                                                            <td class="px-1"><input type="text" class="form-control"></td>
                                                                             <td class="px-1">
                                                                                <div class="demo-inline-spacing">
                                                                                    <div class="form-check form-check-primary mt-25">
                                                                                        <input type="radio" id="primary1" name="primary" value="" class="form-check-input">
                                                                                        <label class="form-check-label fw-bolder" for="primary1"></label>
                                                                                    </div> 
                                                                                </div>
                                                                            </td>
                                                                            <td class="px-1">
                                                                                <a href="#" class="text-danger delete-contact-row"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                                                                <a href="#" class="text-primary add-contact-row"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square me-50"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg></a>
                                                                            </td>
                                                                          </tr> 

                                                                       </tbody>


                                                                </table>
                                                            </div>
                                                            
                                                            
														</div>
														<div class="tab-pane" id="schedule">
															  <div class="row">
                                                                     <div class="col-md-6">
                                                                         
                                                                         <h5 class="mt-1 mb-2 text-dark"><strong>TDS Details</strong></h5>
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">TDS Applicable</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                                    <input type="checkbox" class="form-check-input" id="colorCheck1" checked="">
                                                                                    <label class="form-check-label" for="colorCheck1">Yes/No</label>
                                                                                </div>
                                                                            </div>  
                                                                         </div>
                                                                         
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Wef Date</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="date" class="form-control">
                                                                            </div> 
                                                                         </div>  
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">TDS Certificate No.</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">TDS Tax Percentage</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">TDS Category</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">TDS Value Cab</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">TAN Number</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                    
                                                                     </div>
                                                                    
                                                                    <div class="col-md-6">
                                                                         
                                                                        <div class="mt-1 mb-2 d-flex flex-column">
                                                                            <h5 class="text-dark mb-0 me-1"><strong>GST Info</strong></h5>  
                                                                        </div>
                                                                        
                                                                        <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">GST Applicable</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <div class="demo-inline-spacing">
                                                                                    <div class="form-check form-check-primary mt-25">
                                                                                        <input type="radio" id="Registered" name="gstappl" class="form-check-input" checked="">
                                                                                        <label class="form-check-label fw-bolder" for="Registered">Registered</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-check-primary mt-25">
                                                                                        <input type="radio" id="nonRegistered" name="gstappl" class="form-check-input">
                                                                                        <label class="form-check-label fw-bolder" for="nonRegistered">Non-Registered</label>
                                                                                    </div> 
                                                                                </div>
                                                                            </div>  
                                                                         </div>


                                                                          <div class="row align-items-center mb-1">
                                                                                <div class="col-md-4"> 
                                                                                    <label class="form-label">GSTIN No.</label>  
                                                                                </div>  

                                                                                <div class="col-md-6"> 
                                                                                    <input type="text" class="form-control">
                                                                                </div> 
                                                                             </div>

                                                                        <div class="row align-items-center mb-1">
                                                                                <div class="col-md-4"> 
                                                                                    <label class="form-label">GST Registered Name</label>  
                                                                                </div>  

                                                                                <div class="col-md-6"> 
                                                                                    <input type="text" class="form-control">
                                                                                </div> 
                                                                             </div>


                                                                            <div class="row align-items-center mb-1">
                                                                                <div class="col-md-4"> 
                                                                                    <label class="form-label">GSTIN Reg. Date</label>  
                                                                                </div>  

                                                                                <div class="col-md-6"> 
                                                                                    <input type="date" class="form-control">
                                                                                </div>

                                                                             </div>



                                                                            <div class="row align-items-center mb-1">
                                                                                <div class="col-md-4"> 
                                                                                    <label class="form-label">Upload Certificate</label>  
                                                                                </div>  

                                                                                 <div class="col-md-6"> 
                                                                                    <input type="file" class="form-control">
                                                                                </div> 
                                                                             </div>
                                                                          
                                                                         
                                                                         
                                                                         
                                                                    
                                                                     </div>
                                                                  
                                                                  <div class="col-md-6">
                                                                         
                                                                         <h5 class="mt-1 mb-2 text-dark"><strong>MSME Details</strong></h5>
                                                                      
                                                                          <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">MSME Registered?</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                                    <input type="checkbox" class="form-check-input" id="colorCheck1" checked="">
                                                                                    <label class="form-check-label" for="colorCheck1">This vendor is MSME registered</label>
                                                                                </div>
                                                                            </div>  
                                                                         </div> 
                                                                         
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">MSME No.</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="text" class="form-control">
                                                                            </div> 
                                                                         </div>  
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">MSME Type</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <select class="form-select">
                                                                                    <option>Select</option>
                                                                                    <option>Micro</option>
                                                                                    <option>Small</option>
                                                                                    <option>Medium</option>
                                                                                </select>
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                         <div class="row align-items-center mb-1">
                                                                            <div class="col-md-4"> 
                                                                                <label class="form-label">Upload Certifcate</label>  
                                                                            </div>  

                                                                            <div class="col-md-6"> 
                                                                                <input type="file" class="form-control">
                                                                            </div> 
                                                                         </div> 
                                                                         
                                                                           
                                                                    
                                                                     </div>
                                                                 </div>
														</div>  
														  <div class="tab-pane" id="send">
                                                            
                                                             <div class="table-responsive-md"> 
                                                                <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border"> 
                                                                    <thead>
                                                                         <tr>
                                                                            <th>#</th>
                                                                            <th>Bank Name</th>
                                                                            <th>Benificiary Name</th>
                                                                            <th>Account Number</th>
                                                                            <th>Re-enter Account No.</th>
                                                                            <th>IFSC Code</th>
                                                                            <th>Cancel Cheque</th>
                                                                            <th>Action</th>
                                                                          </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                             <tr>
                                                                                <td>#</td>
                                                                                <td><input type="text" class="form-control mw-100"></td> 
                                                                                <td><input type="text" class="form-control mw-100"></td> 
                                                                                <td><input type="text" class="form-control mw-100"></td> 
                                                                                <td><input type="text" class="form-control mw-100"></td> 
                                                                                <td><input type="text" class="form-control mw-100"></td> 
                                                                                <td><input type="file" class="form-control mw-100"></td> 
                                                                                 <td><a href="#" class="text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square me-50"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg></a></td>
                                                                              </tr>

                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>HSFC Bank</td>
                                                                                <td>Aniket Singh</td> 
                                                                                <td>98765434</td> 
                                                                                <td>98765434</td> 
                                                                                <td>HDFC001</td> 
                                                                                <td><a href="#" class="text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></a></td> 
                                                                                <td><a href="#" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></td>
                                                                              </tr>


                                                                       </tbody>


                                                                </table>
                                                            </div>

                                                            <a href="#" class="text-primary add-contactpeontxt"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Add New</a>
                                                             
                                                                
														</div>
														  
                                                     
                                                      
												</div> 
											 
											</div>
 
								
								</div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                     
                </section>
                 

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></div>
    <div class="drag-target" style="touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright © 2024 <a class="ml-25" href="#" target="_blank">Presence 360</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
        
        <div class="footerplogo"><img src="../../../assets/css/p-logo.png"></div>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top waves-effect waves-float waves-light" type="button" style="display: none;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg></button>
    <!-- END: Footer-->
	 
	 
</body>
@endsection

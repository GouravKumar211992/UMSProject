@extends("admin.admin-meta")
@section("content")


     
     {{-- @include('header');
     <!-- END: Header-->
 
 
     <!-- BEGIN: Main Menu-->
   @include('master.faculty.facultysidebar')
     <!-- END: Main Menu--> --}}
 
     <!-- BEGIN: Content-->
     <div class="app-content content ">
         <div class="content-overlay"></div>
         <div class="header-navbar-shadow"></div>
         <div class="content-wrapper container-xxl p-0">
             
             <div class="content-header row">
                 <div class="content-header-left col-md-6 col-4 mb-2">
                     <div class="row breadcrumbs-top">
                         <div class="col-12">
                             <h2 class="content-header-title float-start mb-0">Dashboard Analytics</h2>
                             <div class="breadcrumb-wrapper">
                                 <ol class="breadcrumb">
                                     <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a>
                                     </li> 
                                     <li class="breadcrumb-item active">Dashboard
                                     </li>
                                 </ol>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="content-header-right col-md-6 col-8">
                    <div class="row">
                        <div class="col-md-4 ms-auto"> 
                            <div class="form-group row mb-3 align-item-center">
                                <label for="sessionSelect" class="form-label  flex-nowrap   col-md-6 ">Select Session</label>
                                <select name="session" id="sessionSelect" class="form-select w-auto">
                                    <option value="">Select Session</option>
                                    <option value="1">Session 1</option>
                                    <option value="2">Session 2</option>
                                    <option value="3">Session 3</option>
                                    <option value="4">Session 4</option>
                                    <option value="5">Session 5</option>
                                    <option value="6">Session 6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                 </div>
             </div>
              
            
             <div class="content-body dasboardnewbody">
               
            
                <!-- ChartJS section start -->
                <section id="chartjs-chart">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="holiday-box p-4">
                                <div><span>Papers</span></div>
                                <div>
                                    <h3 class="fw-lighter">1</h3>
                                    <h5>Alloted</h5>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-md-4 col-12">
                            <div class="holiday-box p-4">
                                <div><span>Students</span></div>
                                <div>
                                    <h3 class="fw-lighter">1</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-md-4 col-12">
                            <div class="holiday-box p-4">
                                <div><span>Marks Filled</span></div>
                                <div>
                                    <h3 class="fw-lighter">100</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-md-4 col-12">
                            <div class="holiday-box p-4">
                                <div><span>Mark Filling</span></div>
                                <div>
                                    <h3 class="fw-lighter">2408</h3>
                                    <h5>Pending</h5>
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
 
     <div class="sidenav-overlay"></div>
     <div class="drag-target"></div>
 
     <!-- BEGIN: Footer-->
     <footer class="footer footer-static footer-light">
         <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">COPYRIGHT © 2022<a class="ms-25" href="#" target="_blank">Staqo Presence</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
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
                           <label class="form-label" for="fp-range">Select Date Range</label>
                           <input type="text" id="fp-range" class="form-control flatpickr-range" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                     </div>
                     
                     <div class="mb-1">
                         <label class="form-label">Loan Type</label>
                         <select class="form-select">
                             <option>Select</option>
                             <option>Home Loan</option>
                             <option>Vehicle Loan</option>
                             <option>Term Loan</option>
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
 
 {{-- @include('footer') --}}


 @endsection
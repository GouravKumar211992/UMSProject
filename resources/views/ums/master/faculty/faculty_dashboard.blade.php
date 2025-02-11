@extends('ums.master.faculty.faculty-meta')


     <!-- BEGIN: Content-->
     @section('content')
  
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
                    <div class="row d-flex flex-column align-items-center text-center">
                        <form method="get" name="faculty_data">
                        <div class="col-md-5 ms-auto"> 
                            <div class="form-group row mb-3 align-item-center">
                                <label for="sessionSelect" class="form-label  flex-nowrap   col-md-6 mt-1">Select Session</label>
                                <select class="form-control" name="session" id="session" onChange="$('.show_data').trigger('click')">
                                    <option value="">--Select Session--</option>
                                    @foreach($sessions as $session)
                                    <option value="{{$session->academic_session}}"@if(Request()->session==$session->academic_session) selected @endif>{{$session->academic_session}}</option>
                                    @endforeach
                                    </select>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary  show_data" >
                    </form>
                    </div>
                  
                       
                       
                 
             </div>
             <div class="col-sm-6 textM-right">
                <p>Showing results till <strong>{{date('d-m-Y')}}</strong> </p>
            </div>
            
             <div class="content-body dasboardnewbody">
               
            
                <!-- ChartJS section start -->
                <section id="chartjs-chart " class="text-center">
                    <div class="row ">
                        <div class="col-md-6 col-12">
                            <div class="holiday-box p-5" style=" border-left: 10px solid #A0BC8B;">
                                <div><span style="background: rgba(160, 188, 139, 0.2); color: #A0BC8B;">Papers</span></div>
                                <div>
                                    <h3 class="fw-lighter">{{$mapped_papers}}</h3>
                                    <h5>Alloted</h5>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-md-6 col-12">
                            <div class="holiday-box p-5" style="border-left: 10px solid #62C3C0;">
                                <div><span style="background: rgba(110, 230, 226, 0.2); color: #62C3C0;">Students</span></div>
                                <div>
                                    <h3 class="fw-lighter">{{$student_count}}</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-md-6 col-12">
                            <div class="holiday-box p-5">
                                <div><span style="background: rgba(168, 139, 151, 0.2); color: #A88B97;">Marks Filled</span></div>
                                <div>
                                    <h3 class="fw-lighter">{{$internal_marks_filled}}</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-md-6 col-12">
                            <div class="holiday-box p-5" style="border-left: 10px solid #E3C852;">
                                <div><span style="background: rgba(227, 200, 82, 0.2); color: #E3C852;">Mark Filling</span></div>
                                <div>
                                    <h3 class="fw-lighter">{{$pending}}</h3>
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
       
     @endsection
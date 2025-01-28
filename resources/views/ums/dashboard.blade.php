 @extends('ums.dashboard.dashboard-meta')
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
                <div class="content-header-right text-end col-md-6 col-8">
                    {{-- <div class="form-group breadcrumb-right">  
							<button class="btn btn-primary box-shadow-2" data-bs-toggle="modal" data-bs-target="#filter"><i data-feather="filter"></i> Filter</button>  
                    </div> --}}
                </div>
            </div>
             
            <div class="content-body dasboardnewbody">
                 
                <!-- ChartJS section start -->
                <section id="chartjs-chart">
                    <div class="row">
                        
                        <div class="col-md-4 col-12 cursor-pointer"  data-bs-toggle="modal" data-bs-target="#myModal">
                            <div class="holiday-box p-4" style=" border-left: 10px solid #A0BC8B;">
                                <div><span style="background: rgba(160, 188, 139, 0.2); color: #A0BC8B;">Applications</span></div>
                                <div>
                                    <h3 class="fw-lighter">4631</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-4 col-12  cursor-pointer" data-bs-toggle="modal" data-bs-target="#myModal">
                            <div class="holiday-box p-4" style="border-left: 10px solid #62C3C0;">
                                <div><span style="background: rgba(110, 230, 226, 0.2); color: #62C3C0;">P.hD Applications</span></div>
                                <div>
                                    <h3 class="fw-lighter">1</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 cursor-pointer" >
                            <div class="holiday-box p-4">
                                <div><span style="background: rgba(168, 139, 151, 0.2); color: #A88B97;">Enrolled Students</span></div>
                                <div>
                                    <h3 class="fw-lighter">100</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 cursor-pointer">
                            <div class="holiday-box p-4" style="border-left: 10px solid #E3C852;">
                                <div><span style="background: rgba(227, 200, 82, 0.2); color: #E3C852;">Total Students</span></div>
                                <div>
                                    <h3 class="fw-lighter">2408</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 cursor-pointer">
                            <div class="holiday-box p-4"style="border-left: 10px solid #eb6623;" >
                                <div><span style="background: rgba(190, 136, 55, 0.2); color: #eb6623;" >Pending
                                    Applications</span></div>
                                <div>
                                    <h3 class="fw-lighter">0</h3>
                                    <h5>Total</h5>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header newheader d-flex justify-content-between align-items-start">
                                    <div class="header-left">
                                        <h4 class="card-title">Monthly</h4>
                                        <p class="card-text">Applications Submitted</p>
                                    </div> 
                                    <div class="header-right d-flex align-items-center mb-25">
                                        
                                    </div>
                                </div> 
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <canvas class="leavebar-chart-ex chartjs" data-height="300"></canvas>
                                        </div>
                                        <div class="col-md-4">
                                           
                                               
                                     <div id="donut-opentask"></div>
                                                
                                            
                                            
                                        </div>
                                         
                                    </div>
                                </div>
                            </div>
                        </div>
                         
					</div> 
                </section>
                <!-- ChartJS section end -->

            </div>
        </div>
    </div>
  <!-- Modal HTML Structure -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="shiftModalLabel">Total Applications (2024-2025)</h3>
                <button type="button" class="btn-close m-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Table for DataTables -->
                <section id="basic-datatable p-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-striped datatables-basic" id="myTable">
                                        <thead>
                                            <tr>
                                              <th>SN#</th>
                                              <th>Campuses Name</th>
                                              <th>Courses Name</th>
                                              <th>Application's Payments Failed</th>
                                              <th>Application's Payments Pending</th>
                                              <th>Application's Payments Paid</th>
                                              <th>Total Applications</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td>11</td>
                                              <td>Dr. Shakuntala Misra National Rehabilitation University</td>
                                              <td>MCA</td>
                                              <td>2</td>
                                              <td>13</td>
                                              <td>56</td>
                                              <td>71</td>
                                            </tr>
                                            <tr>
                                              <td>12</td>
                                              <td>Dr. Shakuntala Misra National Rehabilitation University</td>
                                              <td>M.A. (English)</td>
                                              <td>0</td>
                                              <td>5</td>
                                              <td>45</td>
                                              <td>50</td>
                                            </tr>
                                            <tr>
                                              <td>13</td>
                                              <td>Dr. Shakuntala Misra National Rehabilitation University</td>
                                              <td>M.A. (Sociology)</td>
                                              <td>0</td>
                                              <td>1</td>
                                              <td>111</td>
                                              <td>112</td>
                                            </tr>
                                            <tr>
                                              <td>14</td>
                                              <td>Dr. Shakuntala Misra National Rehabilitation University</td>
                                              <td>M.A. (Economics)</td>
                                              <td>1</td>
                                              <td>4</td>
                                              <td>31</td>
                                              <td>36</td>
                                            </tr>
                                            <tr>
                                              <td>15</td>
                                              <td>Dr. Shakuntala Misra National Rehabilitation University</td>
                                              <td>M.A. (Political Science)</td>
                                              <td>0</td>
                                              <td>4</td>
                                              <td>82</td>
                                              <td>86</td>
                                            </tr>
                                          </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Scroll to Top Button (Optional) -->
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
@endsection

{{-- @include('footer') --}}


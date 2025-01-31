  @extends('ums.admin.admin-meta')

<!-- BEGIN: Body-->
 @section('content')
     
 
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col=""> --}}


    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
               
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Add Department </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Add Department</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0"  onclick="location.href='{{url('department')}}'"> <i data-feather="arrow-left-circle"></i> Go Back
                            </button>
                        <button form="add-department-form" class="btn btn-primary btn-sm mb-50 mb-sm-0" > <i data-feather="check-circle" style="font-size: 40px;"></i>
                            Submit</button>


                    </div>
                </div>
            </div>
            <div class="content-body bg-white p-4 shadow">
            <form id="add-department-form" action="{{url('department_add')}}" method="POST">
            @csrf
                <div class="row gy-0  mt-3 p-2 ">


                    <div class="col-md-6 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3 ">
                                <label class="form-label">Enter Department Name:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                               <input type="text" name="name" class="form-control" placeholder="Enter Department name">
                                
                            </div>
                        </div>
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Head:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <input type="text" name="head" class="form-control" placeholder="Enter Head name">                         </div>
                        </div>

                       


                    </div>
                    <div class="col-md-6 ">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Dean:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input type="text"  name="dean" class="form-control" placeholder="Enter Dean name">                         </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Name Of Faculty:<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                            <select id="faculty" name="faculty" class="form-control">
                            	<option>--SELECT--</option>
                            	<option value="arts">Arts</option>
                            	<option value="special education">Special Education</option>
                            	<option value="law">Law</option>
                            	<option value="commerce &amp; management">Commerce &amp; Management</option>
                            	<option value="science &amp; technology">Science &amp; Technology</option>
                            	<option value="engineering &amp; technology">Engineering &amp; Technology</option>
                            </select>
                            </div>
                        </div>
                        

                       

                    </div>
                    <div class="col-md-6">

                     
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Contact:<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                               <input type="text" name="contact" class="form-control" placeholder="Enter email name">
                            </div>
                        </div>
                        


                      
                    </div>
                    <div class="col-md-6">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Email:<span class="text-danger m-0">*</span></label>
                            </div>
    
                            <div class="col-md-9">
                              <input type="text" name="email" placeholder="Enter email name" class="form-control">
                            </div>
                        </div>
                    </div>
                
</form>
                 
                 
                    
                 
                 
                   


                
                
                   
            </div>


               
</form>
            </div>
          
        </div>
    </div>
    @endsection
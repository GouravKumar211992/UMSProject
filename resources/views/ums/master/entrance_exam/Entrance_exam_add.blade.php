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
                            <h2 class="content-header-title float-start mb-0">Add Schedule </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Add Schedule</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0"onclick="location.href='{{url('/subject_list')}}'"> <i data-feather="arrow-left-circle"></i> Go Back
                            </button>
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" > <i data-feather="check-circle" style="font-size: 40px;"></i>
                            Publish</button>


                    </div>
                </div>
            </div>
            <div class="content-body bg-white p-3 shadow">
                <div class="row gy-0  mt-3 p-2 ">


                    <div class="col-md-4 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Campus<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-8">
                                <select name="" id="" class="form-control">
                                    <option value="">--Please select Campus--</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>
                                
                            </div>
                        </div>
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4 text-nowrap">
                                <label class="form-label">Examination Date:<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-8">
                             <input type="date" class="form-control">
                                
                            </div>
                        </div>
                       

                       


                    </div>
                    <div class="col-md-4 ">

                       
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Program<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-8">
                                <select name="" id="" class="form-control">
                                    <option value="">-- Select Program--</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-4 text-nowrap">
                                <label class="form-label">Reporting Timing:<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-8">
                               <input type="date" class="form-control">
                            </div>
                        </div>
                        

                       

                    </div>
                    <div class="col-md-4">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Course<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-8">
                                <select name="" id="" class="form-control">
                                    <option value="">--Select Course--</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>                            </div>
                        </div>
                       
                        


                        <div class="row align-items-center mb-1">
                            <div class="col-md-4 text-nowrap">
                                <label class="form-label">Examination Time:<span class="text-danger m-0">*</span></label>
                            </div>
    
                            <div class="col-md-8">
                              <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                

                 
                 
                    
                 
                 
                   


                
                
                    <div class="col-md-4">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Ending Time:<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-8">
                                <input type="date" class="form-control" >
                            </div>
                        </div>

                       

                    
                      
                    </div>
                    <div class="col-md-4 ">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Exam Center<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-8">
                                <select name="" id="" class="form-control">
                                    <option value="">--Select Type--</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>                            </div>
                        </div>

                     
                       

                    </div>

             
                 <div class="col-md-4">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-4">
                                <label class="form-label">Session<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-8">
                                <select name="selcet" id="" class="form-control">
                                    
                                    <option value="1">--Select Subject Type--</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>
                            </div>
                    


                    </div>

                 
                      

                    </div>

                  
               


                   
                       


               
                 
                    
                 
                 
                   


                </div>
            </div>


               

            </div>
    </div>
          
    @endsection
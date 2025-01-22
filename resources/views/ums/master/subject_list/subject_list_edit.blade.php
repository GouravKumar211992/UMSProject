  @extends('admin.admin-meta')
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
                            <h2 class="content-header-title float-start mb-0">Update Subject</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Update Subject</li>
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
                            Update</button>


                    </div>
                </div>
            </div>
            <div class="content-body bg-white p-4 shadow">
                <div class="row gy-0  mt-3 p-2 ">


                    <div class="col-md-6 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Campus<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
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
                            <div class="col-md-3">
                                <label class="form-label">Course<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="" id="" class="form-control">
                                    <option value="">--Select Course--</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>                            </div>
                        </div>

                       


                    </div>
                    <div class="col-md-6 ">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Program<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="" id="" class="form-control">
                                    <option value="">-- Select Program--</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Stream<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="selcet" id="" class="form-control">
                                    
                                    <option value="1">--Select Stream--</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>
                            </div>
                        </div>
                        

                       

                    </div>
                    <div class="col-md-6">

                     
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Semester<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="selcet" id="" class="form-control">
                                    
                                    <option value="1">--Select Semester--</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>
                            </div>
                        </div>
                        


                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject Code<span class="text-danger m-0">*</span></label>
                            </div>
    
                            <div class="col-md-9">
                              <input type="text" placeholder="Enter here" class="form-control">
                            </div>
                        </div>
                    </div>
                

                 
                 
                    
                 
                 
                   


                
                
                    <div class="col-md-6">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject name<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter here">
                            </div>
                        </div>

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Back Fees<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input type="text" placeholder="Enter here" class="form-control">
                            </div>
                        </div>

                    
                      
                    </div>
                    <div class="col-md-6 ">
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Type<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="" id="" class="form-control">
                                    <option value="">--Select Type--</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>                            </div>
                        </div>

                     
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject Internal Maximum Mark<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input type="text" class="form-control" placeholder="Enter here">
                            </div>
                        </div>

                    </div>

             
                 <div class="col-md-6">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject Type<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="selcet" id="" class="form-control">
                                    
                                    <option value="1">--Select Subject Type--</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>
                            </div>
                    


                    </div>

                    <div class="row align-items-center mb-1">
                        <div class="col-md-3">
                            <label class="form-label">Subject External Maximum Mark<span class="text-danger">*</span></label>
                        </div>

                        <div class="col-md-9">
                           <input type="text" class="form-control" placeholder="Enter here">
                        </div>
                    </div>
                      

                    </div>

                  
               


                    <div class="col-md-6 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject Minimum Mark<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input type="text" placeholder="Enter here" class="form-control">
                            </div>
                        </div>

                      


                    </div>
                    <div class="col-md-6 ">

                       
                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Subject Credit<span class="text-danger">*</span></label>
                            </div>

                            <div class="col-md-9">
                              <input type="text" class="form-control" placeholder="Enter here">
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6 ">

                        <div class="row align-items-center mb-1">
                            <div class="col-md-3">
                                <label class="form-label">Batch<span class="text-danger m-0">*</span></label>
                            </div>

                            <div class="col-md-9">
                                <select name="" id="" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>                            </div>
                        </div>
                    </div>
                        <div class="col-md-6">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Status<span class="text-danger m-0">*</span></label>
                                </div>
    
                                <div class="col-md-9">
                                    <select name="" id="" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                    
                                    </select>                            </div>
                            </div> 
                        </div>

                       


               
                 
                    
                 
                 
                   


                </div>
            </div>


               

            </div>
          
            
    <!--@endsection
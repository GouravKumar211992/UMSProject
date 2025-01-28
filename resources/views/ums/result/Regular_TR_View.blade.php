@extends("ums.admin.admin-meta")
@section("content")



    <!-- BEGIN: Content-->

    <div class="app-content content">
        <div class="big-box d-flex justify-content-between mb-1 align-items-center">

         
        <div class="head">
    <div class="row d-flex justify-content-between">
        <div class="col-md-9">
            <h4>Tabular Record (TR)</h4>
        </div>
        <div class="col-md-3 text-right">
            <div class="breadcrumbs-top">
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <!-- <li class="breadcrumb-item active">List of Admins</li> -->
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
        <div class="submitss text-start me-3 align-item-center">
            <button class="dt-button buttons-collection btn btn-outline-secondary dropdown-toggle" tabindex="0" aria-controls="DataTables_Table_0" type="button" aria-haspopup="true"><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share font-small-4 mr-50"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>Export</span></button>
            <button onclick="window.location.reload()" onclick="javascript: history.go(-1)" class=" mt-1 btn btn-primary btn-sm mb-50 mb-sm-0r waves-effect waves-float waves-light "><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Get Data</button>
            <button onclick="window.location.reload()" class="btn btn-warning btn-sm mb-50 mb-sm-0r mt-1" type="reset">
                <i data-feather="refresh-cw"></i> Reset
            </button>
        </div>
    </div>


        <!-- options section -->
    {{-- <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-4 text-center">
                <label class="form-label ">Base Rate % <span class="text-danger">*</span></label>
                <input type="number" value="5" class="form-control ">
            </div>
            <div class="col-md-4 text-center">
                <label class="form-label">Effective from <span class="text-danger">*</span></label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-4 text-center">
                <label class="form-label">Additional Input <span class="text-danger">*</span></label>
                <input type="text" class="form-control">
            </div>
        </div>
    </div> --}}


    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Campus <span class="text-danger">*</span></label>
                <select data-live-search="true" name="campus_id" id="campus_id" style="border-color: #c0c0c0;" class="form-control" onchange="return $('#form_data').submit();">
		<option value="">--Choose Campus--</option>
							<option value="28">Dr. RPS INSTITUTE OF EDUCATION</option>
								<option value="1">Dr. Shakuntala Misra National Rehabilitation University</option>
								<option value="9">GRAMODYOG SEWA SANSTHAN</option>
								<option value="20">Handicapped Development Council, Sikandara, Agra</option>
								<option value="5">Hind Mahavidyalaya, Barabanki</option>
								<option value="21">JAY NAND SPECIAL TEACHERS’ TRAINING INSTITUTE, AYODHYA </option>
								<option value="8">K.S. Memorial College for Research &amp; Rehabilitation, Raebareli</option>
								<option value="2">Kalyanam Karoti, Mathura</option>
								<option value="18">MAA BALIRAJI SEWA SANSTHAN(ALLAHABAD)</option>
								<option value="17">MAA BALIRAJI SEWA SANSTHAN(MIRZAPUR)</option>
								<option value="26">MAHARISHI DAYANAND REHABILITATION INSTITUTE</option>
								<option value="3">Nai Subah,Village-Khanav, Post-Bachhav, Varanasi</option>
								<option value="27">PRAKASH KIRAN EDUCATIONAL INSTITUTION</option>
								<option value="13">PRAMILA KATIYAR SPECIAL EDUCATION INSTITUTE</option>
								<option value="24">Sarveshwari Shikshan Sansthan, Kausambi</option>
								<option value="25">SHRI CHANDRABHAN SINGH DEGREE COLLEGE</option>
								<option value="29">SOCIETY FOR INSTITUTE OF PSYCHOLOGICAL RESEARCH &amp; HEALTH</option>
								<option value="23">T. S. Misra College of Paramedical Sciences, Lucknow</option>
								<option value="6">T.S. Misra College of Nursing, Lucknow</option>
								<option value="4">T.S. Misra Medical College and Hospital, Lucknow</option>
							</select>
            </div>
    
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Courses <span class="text-danger">*</span></label>
                <select data-live-search="true" name="course" id="course" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onchange="return $('#form_data').submit();">
					<option value="">--Choose Course--</option>
														</select>
            </div>
    
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Semester <span class="text-danger">*</span></label>
                <select data-live-search="true" name="semester" id="semester" style="border-color: #c0c0c0;" class="form-control js-example-basic-single " onchange="$('#group_name').prop('selectedIndex',0); return $('#form_data').submit();">
                        <option value="">--Select Semester--</option>
                                            </select>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Result Type <span class="text-danger">*</span></label>
                <select name="form_type" id="form_type" class="form-control" style="border-color: #c0c0c0;">
						<option value="regular">Regular</option>
						<option value="UFM">UFM</option>
					</select>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Academic Session <span class="text-danger">*</span></label>
                <select name="academic_session" id="academic_session" class="form-control" style="border-color: #c0c0c0;" onchange="return $('#form_data').submit();">
                        						<option value="2024-2025">2024-2025</option>
												<option value="2023-2024AUG">2023-2024AUG</option>
												<option value="2023-2024JUL">2023-2024JUL</option>
												<option value="2023-2024FEB">2023-2024FEB</option>
												<option value="2023-2024">2023-2024</option>
												<option value="2022-2023">2022-2023</option>
												<option value="2021-2022">2021-2022</option>
											</select>           
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Paper Size <span class="text-danger">*</span></label>
                <select name="paper_size" id="paper_size" class="form-control" style="border-color: #c0c0c0;">
						<option value="">Select</option>
						<!-- <option value="a4">A4</option>
						<option value="a3">A3</option>
						<option value="a2">A2</option> -->
						<option value="a1">A1</option>
						<!-- <option value="a0">A0</option> -->
					</select>           
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">One Paper Group <span class="text-danger">*</span></label>
                <select name="onepagegroup" id="onepagegroup" class="form-control" style="border-color: #c0c0c0;" required="">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
					</select>           
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Paper SN# <span class="text-danger">*</span></label>
                <input type="number" class="form-control">
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row align-items-center mb-1">
            
            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">From Serial Number<span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">All</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">To Serial Number<span class="text-danger">*</span></label>
                <input type="number" class="form-control">
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Roll Number <span class="text-danger">*</span></label>
                <input type="number" class="form-control">
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <label class="form-label mb-0 me-2 col-3">Subject List<span class="text-danger">*</span></label>
                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="7">All</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>
    
        </div>
    </div>


<!-- options section end-->

        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                {{-- <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Admin <br> <small>List of Admins</small></h2>
                            {{-- <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Incident List</li>
                                </ol>
                            </div> --}}
                        {{-- </div>
                    </div>
                </div>  --}}
                {{-- <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal">
                            <i data-feather="filter"></i> Filter
                        </button> 
                        <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                        <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="incident-add.html">
                            <i data-feather="user-plus"></i> Add Admin
                        </a>  
                        <!-- Reset Button -->
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" type="reset">
                            <i data-feather="refresh-cw"></i> Reset
                        </button>
                    </div>
                </div> --}}
                
            </div>
            <div class="content-body">
                 
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                {{-- <div class="table-responsive">
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>ROLL NO</th>
                                                    <th>NAME</th>
                                                    <th>GRAND TOTAL</th>
                                                    <th>RESULT</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td class="fw-bolder text-dark">Description will come here</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td>
                                                    <td><span class="badge rounded-pill badge-light-danger badgeborder-radius">Description will come here</span></td> 
                                                    <td>pass</td> 
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item">
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td class="fw-bolder text-dark">05-Sep-2024</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td>
                                                    <td><span class="badge rounded-pill badge-light-warning badgeborder-radius">Open</span></td> 
                                                    <td>pass</td> 
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" >
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                </tr>
                                                  <tr>
                                                    <td>3</td>
                                                    <td class="fw-bolder text-dark">05-Sep-2024</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td>
                                                    <td><span class="badge rounded-pill badge-light-success badgeborder-radius">Close</span></td> 
                                                    <td>pass</td> 
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item">
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>4</td>
                                                    <td class="fw-bolder text-dark">05-Sep-2024</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td>
                                                    <td><span class="badge rounded-pill badge-light-danger badgeborder-radius">Re-Allocatted</span></td> 
                                                    <td>pass</td> 
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" >
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td class="fw-bolder text-dark">05-Sep-2024</td>
                                                    <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Sarah Burley</span></td> 
                                                    <td><span class="badge rounded-pill badge-light-warning badgeborder-radius">Open</span></td>
                                                    <td>pass</td>   
                                                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item">
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                                <a class="dropdown-item" href="incident-view.html">
                                                                    <i data-feather="eye" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a> <a class="dropdown-item" data-bs-toggle="modal" href="#reallocate">
                                                                    <i data-feather="copy" class="me-50"></i>
                                                                    <span>Re-Allocate</span>
                                                                </a> <a class="dropdown-item" href="#">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
                                                </tr>
                                            </tbody>
                                            
                                        </table>
                                        
                                    </div>
								
                            </div> --}}

                            
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                    <div class="modal modal-slide-in fade" id="modals-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="add-new-record modal-content pt-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                        <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-post">Post</label>
                                        <input type="text" id="basic-icon-default-post" class="form-control dt-post" placeholder="Web Developer" aria-label="Web Developer" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-email">Email</label>
                                        <input type="text" id="basic-icon-default-email" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
                                        <small class="form-text"> You can use letters, numbers & periods </small>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                                        <input type="text" class="form-control dt-date" id="basic-icon-default-date" placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="basic-icon-default-salary">Salary</label>
                                        <input type="text" id="basic-icon-default-salary" class="form-control dt-salary" placeholder="$12000" aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                 

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; 2024 <a class="ml-25" href="#" target="_blank">Presence 360</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
        
        <div class="footerplogo"><img src="../../../assets/css/p-logo.png" /></div>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->
	
     
    
    <div class="modal fade" id="reallocate" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-4 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Re-Allocate Incident</h1>
					<p class="text-center">Enter the details below.</p>

					<div class="row mt-2"> 
						
						<div class="col-md-12 mb-1">
							<label class="form-label">Re-Allocate To <span class="text-danger">*</span></label>
							<select class="form-select select2">
                                <option>Select</option>
                            </select>
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">Re-Allocate Dept. <span class="text-danger">*</span></label>
							<select class="form-select select2">
                                <option>Select</option>
                            </select>
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">PDC Date <span class="text-danger">*</span></label>
							<input type="date" class="form-control" placeholder="Enter Name" />
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">Remarks <span class="text-danger">*</span></label>
							<textarea class="form-control"></textarea>
						</div>
                          
                         
				    </div>
                </div>
				
				<div class="modal-footer justify-content-center">  
						<button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
					<button type="reset" class="btn btn-primary">Re-Allocate</button>
				</div>
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
						<label class="form-label">Select Incident No.</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Select Customer</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Assigned To</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Status</label>
						<select class="form-select">
							<option>Select</option> 
							<option>Open</option>
							<option>Close</option>
							<option>Re-Allocatted</option>
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

    {{-- @include('footer'); --}}

    <!-- BEGIN: Vendor JS-->
    <!-- BEGIN: Vendor JS-->
     {{-- <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="../../../app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="../../../app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
	<script src="../../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS--> 
    <script src="../../../app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
	<script src="../../../app-assets/js/scripts/forms/form-select2.js"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
		$(function () { 

  var dt_basic_table = $('.datatables-basic'),
    dt_date_table = $('.dt-date'),
    dt_complex_header_table = $('.dt-complex-header'),
    dt_row_grouping_table = $('.dt-row-grouping'),
    dt_multilingual_table = $('.dt-multilingual'),
    assetPath = '../../../app-assets/';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  // DataTable with buttons
  // --------------------------------------------------------------------

  if (dt_basic_table.length) {
    var dt_basic = dt_basic_table.DataTable({
      
      order: [[0, 'asc']],
      dom: 
        '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle',
          text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
          buttons: [
            {
              extend: 'print',
              text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'csv',
              text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'excel',
              text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'pdf',
              text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            },
            {
              extend: 'copy',
              text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
              className: 'dropdown-item',
              exportOptions: { columns: [3, 4, 5, 6, 7] }
            }
          ],
          init: function (api, node, config) {
            $(node).removeClass('btn-secondary');
            $(node).parent().removeClass('btn-group');
            setTimeout(function () {
              $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
            }, 50);
          }
        },
         
      ],
      
      language: {
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      }
    });
    $('div.head-label').html('<h6 class="mb-0">Event List</h6>');
  }

  // Flat Date picker
  if (dt_date_table.length) {
    dt_date_table.flatpickr({
      monthSelectorType: 'static',
      dateFormat: 'm/d/Y'
    });
  }

  // Add New record
  // ? Remove/Update this code as per your requirements ?
  var count = 101;
  $('.data-submit').on('click', function () {
    var $new_name = $('.add-new-record .dt-full-name').val(),
      $new_post = $('.add-new-record .dt-post').val(),
      $new_email = $('.add-new-record .dt-email').val(),
      $new_date = $('.add-new-record .dt-date').val(),
      $new_salary = $('.add-new-record .dt-salary').val();

    if ($new_name != '') {
      dt_basic.row
        .add({
          responsive_id: null,
          id: count,
          full_name: $new_name,
          post: $new_post,
          email: $new_email,
          start_date: $new_date,
          salary: '$' + $new_salary,
          status: 5
        })
        .draw();
      count++;
      $('.modal').modal('hide');
    }
  });

  // Delete Record
  $('.datatables-basic tbody').on('click', '.delete-record', function () {
    dt_basic.row($(this).parents('tr')).remove().draw();
  });
	
	 
 
});
		
		 
		
		
    </script>
</body>
<!-- END: Body-->

</html> --}}

@endsection
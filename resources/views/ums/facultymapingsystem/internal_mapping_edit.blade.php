@extends("ums.admin.admin-meta")
@section("content")

{{-- <!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    
    <title>Presence 360</title>
     	
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../assets/css/favicon.png">
     <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600;700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
	<link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/editors/quill/katex.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/editors/quill/monokai-sublime.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/editors/quill/quill.snow.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/editors/quill/quill.bubble.css">
	<link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/css/iconsheet.css">
    <!-- END: Custom CSS--> 

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

    <!-- BEGIN: Header-->
    {{-- <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav d-block container-xxl erpnewheader">
        <div class="header-navbar navbar-light navbar-shadow new-navbarfloating">
					<div class="navbar-container d-flex content">
						<div class="bookmark-wrapper d-flex align-items-center"> 
                            <ul class="nav navbar-nav headerlogo">
                                <li><img src="../../../assets/css/logo.svg" /></li>
                            </ul>
                            <ul class="nav navbar-nav left-baricontop"> 
								<li class="nav-item">
                                    <a class="nav-link menu-toggle" href="#">
                                        <i></i>
                                    </a>
                                </li>
							</ul>
<!--
							<ul class="nav navbar-nav bookmark-icons">
								<li class="nav-item nav-search"> 									
									<a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
									<div class="search-input">
										<div class="search-input-icon"><i data-feather="search"></i></div>
										<input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="-1" data-search="search">
										<div class="search-input-close"><i data-feather="x"></i></div>
										<ul class="search-list search-list-main"></ul>
									</div>
								</li>
							</ul> 
-->
						</div>
						<ul class="nav navbar-nav align-items-center ms-auto"> 
							<li class="nav-item d-none d-lg-block select-organization-menu">
								<select class="form-select">
                                    <option>Select Organization</option>
                                    <option>Sheelaform</option>
                                    <option>Staqo</option>
                                </select>
							</li>
							<li class="nav-item d-none d-lg-block">
								<div class="theme-switchbox">
									
									<div class="themeswitchstyle">
										<span class="dark-lightmode"><i data-feather="moon"></i></span>
										<span class="day-lightmode"><i data-feather="sun"></i></span>
									</div>
									
								</div> 
							</li>
                            

							<li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span class="badge rounded-pill bg-danger badge-up">5</span></a>
								<ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
									<li class="dropdown-menu-header">
										<div class="dropdown-header d-flex">
											<h4 class="notification-title mb-0 me-auto">Notifications</h4>
											<div class="badge rounded-pill badge-light-primary">6 New</div>
										</div>
									</li>
									<li class="scrollable-container media-list"><a class="d-flex" href="#">
											<div class="list-item d-flex align-items-start">
												<div class="me-1">
													<div class="avatar"><img src="../../../app-assets/images/portrait/small/avatar-s-15.jpg" alt="avatar" width="32" height="32"></div>
												</div>
												<div class="list-item-body flex-grow-1">
													<p class="media-heading"><span class="fw-bolder">Congratulation Sam 🎉</span>winner!</p><small class="notification-text"> Won the monthly best seller badge.</small>
												</div>
											</div>
										</a><a class="d-flex" href="#">
											<div class="list-item d-flex align-items-start">
												<div class="me-1">
													<div class="avatar"><img src="../../../app-assets/images/portrait/small/avatar-s-3.jpg" alt="avatar" width="32" height="32"></div>
												</div>
												<div class="list-item-body flex-grow-1">
													<p class="media-heading"><span class="fw-bolder">New message</span>&nbsp;received</p><small class="notification-text"> You have 10 unread messages</small>
												</div>
											</div>
										</a><a class="d-flex" href="#">
											<div class="list-item d-flex align-items-start">
												<div class="me-1">
													<div class="avatar bg-light-danger">
														<div class="avatar-content">MD</div>
													</div>
												</div>

												<div class="list-item-body flex-grow-1">
													<p class="media-heading"><span class="fw-bolder">Revised Order 👋</span>&nbsp;checkout</p><small class="notification-text"> MD Inc. order updated</small>
												</div>
											</div>
										</a>
										<div class="list-item d-flex align-items-center">
											<h6 class="fw-bolder me-auto mb-0">System Notifications</h6>
											<div class="form-check form-check-primary form-switch">
												<input class="form-check-input" id="systemNotification" type="checkbox" checked="">
												<label class="form-check-label" for="systemNotification"></label>
											</div>
										</div><a class="d-flex" href="#">
											<div class="list-item d-flex align-items-start">
												<div class="me-1">
													<div class="avatar bg-light-danger">
														<div class="avatar-content"><i class="avatar-icon" data-feather="x"></i></div>
													</div>
												</div>
												<div class="list-item-body flex-grow-1">
													<p class="media-heading"><span class="fw-bolder">Server down</span>&nbsp;registered</p><small class="notification-text"> USA Server is down due to high CPU usage</small>
												</div>
											</div>
										</a><a class="d-flex" href="#">
											<div class="list-item d-flex align-items-start">
												<div class="me-1">
													<div class="avatar bg-light-success">
														<div class="avatar-content"><i class="avatar-icon" data-feather="check"></i></div>
													</div>
												</div>
												<div class="list-item-body flex-grow-1">
													<p class="media-heading"><span class="fw-bolder">Sales report</span>&nbsp;generated</p><small class="notification-text"> Last month sales report generated</small>
												</div>
											</div>
										</a><a class="d-flex" href="#">
											<div class="list-item d-flex align-items-start">
												<div class="me-1">
													<div class="avatar bg-light-warning">
														<div class="avatar-content"><i class="avatar-icon" data-feather="alert-triangle"></i></div>
													</div>
												</div>
												<div class="list-item-body flex-grow-1">
													<p class="media-heading"><span class="fw-bolder">High memory</span>&nbsp;usage</p><small class="notification-text"> BLR Server using high memory</small>
												</div>
											</div>
										</a>
									</li>
									<li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="#">Read all notifications</a></li>
								</ul>
							</li>
							<li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="avatar">
<!--                                        <img class="round" src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="32" width="32">-->
                                        NG
                                    </span>
								</a>
								<div class="dropdown-menu drop-newmenu dropdown-menu-end" aria-labelledby="dropdown-user">
									<a class="dropdown-item" href="#"><i class="me-50" data-feather="user"></i> Profile</a>
									<a class="dropdown-item" href="#"><i class="me-50" data-feather="credit-card"></i> Visiting Card</a>
									<a class="dropdown-item" href="#"><i class="me-50" data-feather="log-in"></i> Request</a>
									<a class="dropdown-item" href="#"><i class="me-50" data-feather="check-circle"></i> Approval</a> 
									<a class="dropdown-item" href="#"><i class="me-50" data-feather="tool"></i> Setting</a> 
									<a class="dropdown-item" href="#"><i class="me-50" data-feather="power"></i> Logout</a>
								</div>
							</li>
                            
                            <li class="nav-item dropdown dropdown-notification">
                                <a class="nav-link d-inline-block drivebtnsect" href="#" data-bs-toggle="dropdown">
                                    <img src="img/menuiconlist.png"/> 
                                </a>
                                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end worksdrivebox">
                                        <li class="dropdown-menu-header">
                                            <div class="dropdown-header text-center">
                                                <h4 class="notification-title mb-0 me-auto">My Favourites</h4> 
                                            </div>
                                        </li>
                                        <li class="scrollable-container media-list">
                                            <div class="row">
                                                <div class="col-md-4 col-6">
                                                    <a href="#">
                                                        <div class="drivework">
                                                            <img src="img/d4.png" />
                                                            <p>Gmail</p>
                                                        </div>                                            
                                                    </a>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <a href="#">
                                                        <div class="drivework">
                                                            <img src="img/d3.png" />
                                                            <p>Outlook</p>
                                                        </div>                                            
                                                    </a>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <a href="#">
                                                        <div class="drivework">
                                                            <img src="img/d2.png" />
                                                            <p>Google Drive</p>
                                                        </div>                                            
                                                    </a>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <a href="#">
                                                        <div class="drivework">
                                                            <img src="img/d1.png" />
                                                            <p>Whatsapp</p>
                                                        </div>                                            
                                                    </a>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <a href="#">
                                                        <div class="drivework">
                                                            <img src="img/d4.png" />
                                                            <p>Gmail</p>
                                                        </div>                                            
                                                    </a>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <a href="#">
                                                        <div class="drivework">
                                                            <img src="img/d3.png" />
                                                            <p>Outlook</p>
                                                        </div>                                            
                                                    </a>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <a href="#">
                                                        <div class="drivework">
                                                            <img src="img/d2.png" />
                                                            <p>Google Drive</p>
                                                        </div>                                            
                                                    </a>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <a href="#">
                                                        <div class="drivework">
                                                            <img src="img/d1.png" />
                                                            <p>Whatsapp</p>
                                                        </div>                                            
                                                    </a>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <a href="#">
                                                        <div class="drivework">
                                                            <img src="img/d4.png" />
                                                            <p>Gmail</p>
                                                        </div>                                            
                                                    </a>
                                                </div> 

                                            </div>
                                        </li>
                                    </ul>
                                </li>
						</ul>
					</div>
				</div>
    </nav> --}}
     
     
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    {{-- <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow erpnewsidemenu" data-scroll-to-active="true">
        
        <div class="shadow-bottom"></div>
        <div class="main-menu-content newmodulleftmenu">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item"><a class="d-flex align-items-center" href="dashboard.html"><i data-feather="database"></i><span class="menu-title text-truncate">CRM</span></a>
                </li>
                <li class="active nav-item"><a class="d-flex align-items-center" href="incident.html"><i data-feather="alert-triangle"></i><span class="menu-title text-truncate">Incident</span></a>
                </li>
            </ul>
        </div>
		
    </div> --}}
    <!-- END: Main Menu-->

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
								<h2 class="content-header-title float-start mb-0">Edit Internal Mapping</h2>
								<div class="breadcrumb-wrapper">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="index.html">Home</a>
										</li>  
										<!-- <li class="breadcrumb-item active">Add New</li> -->


									</ol>
								</div>
							</div>
						</div>
					</div>
					<div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
						<div class="form-group breadcrumb-right">   
            <a href="{{ url('faculty_mapping') }}" class="btn btn-secondary btn-sm mb-50 mb-sm-0">
    <i data-feather="arrow-left-circle"></i> Go Back
</a>
							<button  class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather="check-circle"></i>Reset</button> 
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
                                                    <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between"> 
														<div>
                                                            <!-- <h4 class="card-title text-theme">Basic Information</h4> -->
														    <!-- <p class="card-text">Fill the details</p> -->
                                                        </div> 
													</div>
                                                    
                                                </div> 
                                                
                                                
                                                <div class="col-md-8"> 
                                                      
                              
                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            
                                                            <label class="form-label">Campus Name<span class="text-danger">*</span></label>  
                                                        </div>  

                                                        <div class="col-md-5"> 
                                                        <div class="form-group">
										
                                                        <select class="form-control selectpicker-back campuse_id" id="campuse_id" name="campuse_id">
		                                    <option value="">--Select Campus--</option>
		                                    		                                    <option value="1" selected="">Dr. Shakuntala Misra National Rehabilitation University</option>
		                                    		                                    <option value="2">Kalyanam Karoti, Mathura</option>
		                                    		                                    <option value="3">Nai Subah,Village-Khanav, Post-Bachhav, Varanasi</option>
		                                    		                                    <option value="4">T.S. Misra Medical College and Hospital, Lucknow</option>
		                                    		                                    <option value="5">Hind Mahavidyalaya, Barabanki</option>
		                                    		                                    <option value="6">T.S. Misra College of Nursing, Lucknow</option>
		                                    		                                    <option value="8">K.S. Memorial College for Research &amp; Rehabilitation, Raebareli</option>
		                                    		                                    <option value="9">GRAMODYOG SEWA SANSTHAN</option>
		                                    		                                    <option value="13">PRAMILA KATIYAR SPECIAL EDUCATION INSTITUTE</option>
		                                    		                                    <option value="17">MAA BALIRAJI SEWA SANSTHAN(MIRZAPUR)</option>
		                                    		                                    <option value="18">MAA BALIRAJI SEWA SANSTHAN(ALLAHABAD)</option>
		                                    		                                    <option value="20">Handicapped Development Council, Sikandara, Agra</option>
		                                    		                                    <option value="21">JAY NAND SPECIAL TEACHERS’ TRAINING INSTITUTE, AYODHYA </option>
		                                    		                                    <option value="23">T. S. Misra College of Paramedical Sciences, Lucknow</option>
		                                    		                                    <option value="24">Sarveshwari Shikshan Sansthan, Kausambi</option>
		                                    		                                    <option value="25">SHRI CHANDRABHAN SINGH DEGREE COLLEGE</option>
		                                    		                                    <option value="26">MAHARISHI DAYANAND REHABILITATION INSTITUTE</option>
		                                    		                                    <option value="27">PRAKASH KIRAN EDUCATIONAL INSTITUTION</option>
		                                    		                                    <option value="28">Dr. RPS INSTITUTE OF EDUCATION</option>
		                                    		                                    <option value="29">SOCIETY FOR INSTITUTE OF PSYCHOLOGICAL RESEARCH &amp; HEALTH</option>
		                                    		                                </select>
																			</div>
                                                        </div> 
                                                    
                                                     </div> 

                                                     <div class="row align-items-center mb-1">
  <div class="col-md-3">
    <label class="form-label">Program<span class="text-danger">*</span></label>
  </div>
  <div class="col-md-5">
    <div class="form-group">
      <select class="form-select selectpicker-back program_id" id="program_id" name="program_id">
        <option value="">--Select Program--</option>
        <option value="2">Non Professional</option>
        <option value="3">Professional</option>
      </select>
    </div>
  </div>
</div>


<div class="row align-items-center mb-1">
  <div class="col-md-3">
    <label class="form-label">Course<span class="text-danger">*</span></label>
  </div>
  <div class="col-md-5">
    <div class="form-group">
    <select class="form-control" id="course" name="course">  
		                                   
		                                 <option value="">Select Course</option>
		                                                                     <option value="1">B.A.</option>
                                                                    <option value="3">B.Com.</option>
                                                                    <option value="4">B.Com. LL.B. (Hons.)</option>
                                                                    <option value="5" selected="">B.B.A.</option>
                                                                    <option value="6">B.ASLP</option>
                                                                    <option value="8">MVA (Sculpture)</option>
                                                                    <option value="9">B.Ed. S.E. (HI)</option>
                                                                    <option value="10">M.Ed. S.E. (HI)</option>
                                                                    <option value="11">D.Ed. S.E. (HI)</option>
                                                                    <option value="12">M.Sc. (Physics)</option>
                                                                    <option value="13">M.A. (Hindi)</option>
                                                                    <option value="14">MCA</option>
                                                                    <option value="16">M.A. (English)</option>
                                                                    <option value="17">M.A (Bhojpuri)</option>
                                                                    <option value="18">M.A. (Sociology)</option>
                                                                    <option value="19">M.A. (Economics)</option>
                                                                    <option value="20">M.A. (Political Science)</option>
                                                                    <option value="21">M.A. (History)</option>
                                                                    <option value="22">M.Sc. (Chemistry)</option>
                                                                    <option value="23">M.Sc. (Statistics)</option>
                                                                    <option value="24">M.Sc. (Microbiology)</option>
                                                                    <option value="25">M.Sc. (IT)</option>
                                                                    <option value="26">D.Ed. S.E. (ID)</option>
                                                                    <option value="27">D.Ed. S.E. (VI)</option>
                                                                    <option value="28">M.Ed. S.E. (ID)</option>
                                                                    <option value="29">M.Ed. S.E. (VI)</option>
                                                                    <option value="30">B.Ed. S.E. (ID)</option>
                                                                    <option value="31">B.Ed. S.E. (VI)</option>
                                                                    <option value="32">MVA (Applied Arts)</option>
                                                                    <option value="33">MVA (Painting)</option>
                                                                    <option value="34">M.B.A.</option>
                                                                    <option value="35">M.S.W.</option>
                                                                    <option value="36">M.Com.</option>
                                                                    <option value="37">BVA</option>
                                                                    <option value="38">B.P.O.</option>
                                                                    <option value="39">LL.M.</option>
                                                                    <option value="40">P.D.C.D.</option>
                                                                    <option value="41">B.Tech. (CE)</option>
                                                                    <option value="42">B.Tech. (CSE)</option>
                                                                    <option value="43">B.Tech. (ECE)</option>
                                                                    <option value="44">B.Tech. (EE)</option>
                                                                    <option value="45">B.Tech. (ME)</option>
                                                                    <option value="46">B.Ed. S.E. (HI)</option>
                                                                    <option value="47">B.Ed. S.E. (ID)</option>
                                                                    <option value="48">P.G.D.R.P.</option>
                                                                    <option value="49">MBBS</option>
                                                                    <option value="52">B.A.</option>
                                                                    <option value="53">M.A. (English)</option>
                                                                    <option value="54">M.A. (Hindi)</option>
                                                                    <option value="55">M.A. (Urdu)</option>
                                                                    <option value="56">M.A. (Education)</option>
                                                                    <option value="57">M.A. (Sociology)</option>
                                                                    <option value="58">M.A. (Psychology)</option>
                                                                    <option value="59">M.A. (Geography)</option>
                                                                    <option value="60">B.Ed. S.E. (HI)</option>
                                                                    <option value="61">B.Sc. (ZBC)</option>
                                                                    <option value="62">B.Sc. (PCM)</option>
                                                                    <option value="64">B.Sc. (Nursing)</option>
                                                                    <option value="65">B.Sc. (MLT)</option>
                                                                    <option value="67">B.Ed. S.E. (ID)</option>
                                                                    <option value="68">BVA (Sculpture)</option>
                                                                    <option value="69">BVA (Painting)</option>
                                                                    <option value="70">BVA (Applied Arts)</option>
                                                                    <option value="80">B.Ed. (GEN)</option>
                                                                    <option value="81">BCA</option>
                                                                    <option value="84">M.Phil.(Cl.Psy.)</option>
                                                                    <option value="85">M.Sc. (Math)</option>
                                                                    <option value="86">M.Sc. (Ag)</option>
                                                                    <option value="87">B.Ed. S.E. (ID)</option>
                                                                    <option value="88">M.A.(Education)</option>
                                                                    <option value="91">B.Ed. S.E. (ID)</option>
                                                                    <option value="94">Ph.D.</option>
                                                                    <option value="95">BPT</option>
                                                                    <option value="96">BMLT</option>
                                                                    <option value="97">M.P.O.</option>
                                                                    <option value="98">B.Ed. S.E. (HI)</option>
                                                                    <option value="100">M.Tech. (AIML)</option>
                                                                    <option value="101">M.Tech.(AIDS)</option>
                                                                    <option value="106">B.Ed. S.E. (HI)</option>
                                                                    <option value="107">B.ASLP</option>
                                                                    <option value="108">B.ASLP</option>
                                                                    <option value="109">B.Ed. S.E. (ID)</option>
                                                                    <option value="110">B.Ed. S.E. (HI)</option>
                                                                    <option value="111">B.Ed. S.E. (ID)</option>
                                                                    <option value="112">B.Ed. S.E. (HI)</option>
                                                                    <option value="113">B.Ed. S.E.</option>
                                                                    <option value="114">B.Tech.</option>
                                                                    <option value="115">B.Sc. (CS&amp;IT)</option>
                                                                    <option value="117">B.Ed. S.E.</option>
                                                                    <option value="118">B.Ed. S.E.</option>
                                                                    <option value="119">B.Ed. S.E.</option>
                                                                    <option value="120">B.Ed. S.E.</option>
                                                                    <option value="121">B.Ed. S.E.</option>
                                                                    <option value="122">B.Ed. S.E.</option>
                                                                    <option value="123">M.Tech.</option>
                                                                    <option value="124">D. Pharm</option>
                                                                    <option value="125">B. Pharm</option>
                                                                    <option value="126">B.Tech. Lateral Entry</option>
                                                                    <option value="127">M.Ed. S.E. (HI)</option>
                                                                    <option value="128">M.Ed. S.E. (ID)</option>
                                                                    <option value="129">B.Ed. S.E.</option>
                                                                    <option value="130">B.Ed. S.E. (ID)</option>
                                                                    <option value="131">M.D.-PATHOLOGY</option>
                                                                    <option value="132">M.D.-COMMUNITY MEDICINE</option>
                                                                    <option value="133">B.Tech(Hons) CSE (AIDS)</option>
                                                                    <option value="134">B.Tech(Hons) CSE (AIFM)</option>
                                                                    <option value="135">CERTIFICATE COURSE IN AUTOMOBILE INSURANCE(CCAI)</option>
                                                                    <option value="136">CERTIFICATE COURSE IN COMMUNICATIVE ENGLISH(CCCE)</option>
                                                                    <option value="137">UG DIPLOMA IN INDIAN CAPITAL MARKETS(UGDICM)</option>
                                                                    <option value="138">B.Sc.</option>
                                                                    <option value="139">POST GRADUATE DIPLOMA IN INVESTMENT AND PORTFOLIO  MANAGEMENT(PGDIPM)</option>
                                                                    <option value="140">CERTIFICATE COURSE IN  INDIAN CLASSICAL VOCAL MUSIC(CCICVM)</option>
                                                                    <option value="141">CERTIFICATE COURSE IN  INDIAN KNOWLEDGE SYSTEM(CCIKS)</option>
                                                                    <option value="142">CERTIFICATE COURSE IN  TALLEY AND ACCOUNTING(CCTA)</option>
                                                                    <option value="143">DTISL</option>
                                                                    <option value="144">DISLI</option>
                                                                    <option value="145">PGDAVT</option>
                                                                    <option value="146">DHAEMT</option>
                                                                    <option value="147">B.Ed. S.E. (HI)</option>
                                                                    <option value="148">B.Ed. S.E. (HI)</option>
                                                                    <option value="149">B.Ed. S.E. (VI)</option>
                                                                    <option value="150">P.G.D.R.P.</option>
                                                                    <option value="151">B.Ed. S.E. (VI)</option>
                                		                                </select>
    </div>
  </div>
</div>


<div class="row align-items-center mb-1">
  <div class="col-md-3">
    <label class="form-label">Branch<span class="text-danger">*</span></label>
  </div>
  <div class="col-md-5">
    <div class="form-group">
    <select class="form-control" id="branch" name="branch">
		                                  <option value="">Select Branch</option>

		                                                                    <option value="2">MBBS</option>
                                                                    <option value="3">BVA (SCULPTURE)</option>
                                                                    <option value="4">BVA (APPLIED ARTS)</option>
                                                                    <option value="5">BVA (PAINTING)</option>
                                                                    <option value="6">MVA (APPLIED ART)</option>
                                                                    <option value="7">MVA (PAINTING)</option>
                                                                    <option value="8">MVA (SCULPTURE)</option>
                                                                    <option value="9">B.TECH (CSE)</option>
                                                                    <option value="10">B.TECH (EE)</option>
                                                                    <option value="11">B.TECH (ME)</option>
                                                                    <option value="12">B.TECH (ECE)</option>
                                                                    <option value="13">B.TECH (CE)</option>
                                                                    <option value="14">B.A.S.L.P</option>
                                                                    <option value="15">B.Ed. SE. (HI)</option>
                                                                    <option value="16">B.Ed. SE. (ID)</option>
                                                                    <option value="17">B.Ed. SE. (VI)</option>
                                                                    <option value="18">B.Sc. (PCM)</option>
                                                                    <option value="19">B.Sc (ZBC)</option>
                                                                    <option value="20" selected="">B.B.A</option>
                                                                    <option value="21">LL.M</option>
                                                                    <option value="22">M.A. (ENGLISH)</option>
                                                                    <option value="23">M.A (SOCIOLOGY)</option>
                                                                    <option value="24">M.A (GEOGRAPHY)</option>
                                                                    <option value="25">M.A (HINDI)</option>
                                                                    <option value="26">M.A (HISTORY)</option>
                                                                    <option value="27">M.A (URDU)</option>
                                                                    <option value="28">M.S.W</option>
                                                                    <option value="29">M.Sc. (CHEMISTRY)</option>
                                                                    <option value="30">M.Sc. (PHYSICS)</option>
                                                                    <option value="31">M.Sc.(MICROBIOLOGY)</option>
                                                                    <option value="32">M.Sc. (APPLIED STATISTICS)</option>
                                                                    <option value="33">M.Com.</option>
                                                                    <option value="34">M.A (BHOJPURI)</option>
                                                                    <option value="35">D.Ed. SE. (HI)</option>
                                                                    <option value="36">D.Ed. SE. (ID)</option>
                                                                    <option value="37">D.Ed. SE. (VI)</option>
                                                                    <option value="38">M.Ed. SE. (HI)</option>
                                                                    <option value="39">M.Ed. SE. (ID)</option>
                                                                    <option value="40">M.Ed. SE. (VI)</option>
                                                                    <option value="41">B.P.O</option>
                                                                    <option value="42">M.B.A.</option>
                                                                    <option value="43">MCA</option>
                                                                    <option value="44">M.A (ECONOMICS)</option>
                                                                    <option value="45">M.A (POLITICAL SCIENCE)</option>
                                                                    <option value="46">M.Sc. (IT)</option>
                                                                    <option value="47">B.Com.</option>
                                                                    <option value="48">B.Com. LL.B. (Hons.)</option>
                                                                    <option value="49">P.D.C.D.</option>
                                                                    <option value="50">BVA</option>
                                                                    <option value="51">P.G.D.R.P.</option>
                                                                    <option value="52">M.A (EDUCATION)</option>
                                                                    <option value="53">M.A (PSYCHOLOGY)</option>
                                                                    <option value="54">M.D. in Community Medicine</option>
                                                                    <option value="55">M.D. in Pathology</option>
                                                                    <option value="56">BPT</option>
                                                                    <option value="57">B.Sc (Nursing)</option>
                                                                    <option value="58">B.Sc (MLT)</option>
                                                                    <option value="59">B.A</option>
                                                                    <option value="61">B.ED (GEN)</option>
                                                                    <option value="62">BCA</option>
                                                                    <option value="63">BMLT</option>
                                                                    <option value="64">CCBH</option>
                                                                    <option value="66">M.Sc (Math)</option>
                                                                    <option value="67">M.A (HINDI)</option>
                                                                    <option value="68">B.Ed. SE. (ID)</option>
                                                                    <option value="70">BA</option>
                                                                    <option value="71">B.Ed. S.E. (HI)</option>
                                                                    <option value="72">B.Ed. S.E. (ID)</option>
                                                                    <option value="73">M.Sc. (Statistics)</option>
                                                                    <option value="74">B.Ed. S.E. (ID)</option>
                                                                    <option value="75">B.Ed. S.E. (ID)</option>
                                                                    <option value="76">B.Ed. S.E. (HI)</option>
                                                                    <option value="77">B.Ed. S.E. (ID)</option>
                                                                    <option value="78">Ph.D</option>
                                                                    <option value="79">BPT</option>
                                                                    <option value="80">BMLT</option>
                                                                    <option value="81">M.Tech (AIML)</option>
                                                                    <option value="82">M.Tech (AIDS)</option>
                                                                    <option value="83">M.A.(Education)</option>
                                                                    <option value="84">B.ASLP</option>
                                                                    <option value="85">B.Ed. S.E. (HI)</option>
                                                                    <option value="86">B.ASLP</option>
                                                                    <option value="87">B.Ed. S.E. (ID)</option>
                                                                    <option value="88">B.Ed. S.E. (HI)</option>
                                                                    <option value="89">B.Ed. S.E. (HI)</option>
                                                                    <option value="92">B.Ed.S.E. (HI)</option>
                                                                    <option value="93">B,Ed.S.E.(VI)</option>
                                                                    <option value="94">M.P.O.</option>
                                                                    <option value="95">B.Ed. S.E. (ID)</option>
                                                                    <option value="96">B.Ed. S.E. (HI)</option>
                                                                    <option value="97">B.Sc. (CS&amp;IT)</option>
                                                                    <option value="98">B.Pharm</option>
                                                                    <option value="99">D.Pharm</option>
                                                                    <option value="100">M.A. (Sociology)</option>
                                                                    <option value="101">M.D.-PATHOLOGY</option>
                                                                    <option value="102">M.D.-COMMUNITY MEDICINE</option>
                                                                    <option value="103">M.Ed.S.E. (HI)</option>
                                                                    <option value="104">M.Ed.S.E. (ID)</option>
                                                                    <option value="105">B.Ed. S.E. (ID)</option>
                                                                    <option value="106">M.Phil.(Cl.Psy.)</option>
                                                                    <option value="107">B.Ed. S.E. (HI)</option>
                                                                    <option value="108">B.Ed. S.E. (HI)</option>
                                                                    <option value="109">B.Ed. SE. (VI)</option>
                                                                    <option value="110">P.G.D.R.P.</option>
                                                                    <option value="111">B.Ed. SE. (VI)</option>
                                                                    <option value="112">B.Tech(Hons) CSE (AIDS)</option>
                                                                    <option value="113">B.Tech(Hons) CSE (AIFM)</option>
                                                                    <option value="114">B.Sc.</option>
                                		                                </select>
    </div>
  </div>
</div>


<div class="row align-items-center mb-1">
  <div class="col-md-3">
    <label class="form-label">Semester<span class="text-danger">*</span></label>
  </div>
  <div class="col-md-5">
    <div class="form-group">
    <select class="form-control" id="semester" name="semester">   
		                                    <option value="">Select Semester</option>
		                                                                        <option value="7">FIRST PROFESSIONAL</option>
                                                                    <option value="8">SECOND PROFESSIONAL</option>
                                                                    <option value="9">THIRD PROFESSIONAL (PART-I)</option>
                                                                    <option value="10">THIRD PROFESSIONAL (PART-II)</option>
                                                                    <option value="11">FIRST SEMESTER</option>
                                                                    <option value="12">SECOND SEMESTER</option>
                                                                    <option value="13">THIRD SEMESTER</option>
                                                                    <option value="14">FOURTH SEMESTER</option>
                                                                    <option value="15">FIFTH SEMESTER</option>
                                                                    <option value="16">SIXTH SEMESTER</option>
                                                                    <option value="17">SEVENTH SEMESTER</option>
                                                                    <option value="18">EIGHT SEMESTER</option>
                                                                    <option value="19">FIRST SEMESTER</option>
                                                                    <option value="20">SECOND SEMESTER</option>
                                                                    <option value="21">THIRD SEMESTER</option>
                                                                    <option value="22">FOURTH SEMESTER</option>
                                                                    <option value="23">FIRST SEMESTER</option>
                                                                    <option value="24">SECOND SEMESTER</option>
                                                                    <option value="25">THIRD SEMESTER</option>
                                                                    <option value="26">FOURTH SEMESTER</option>
                                                                    <option value="27">FIRST SEMESTER</option>
                                                                    <option value="28">SECOND SEMESTER</option>
                                                                    <option value="29">THIRD SEMESTER</option>
                                                                    <option value="30">FOURTH SEMESTER</option>
                                                                    <option value="31">FIRST SEMESTER</option>
                                                                    <option value="32">SECOND SEMESTER</option>
                                                                    <option value="33">THIRD SEMESTER</option>
                                                                    <option value="34">FOURTH SEMESTER</option>
                                                                    <option value="35">FIFTH SEMESTER</option>
                                                                    <option value="36">SIXTH SEMESTER</option>
                                                                    <option value="37">FIRST SEMESTER</option>
                                                                    <option value="38">SECOND SEMESTER</option>
                                                                    <option value="39">THIRD SEMESTER</option>
                                                                    <option value="40">FOURTH SEMESTER</option>
                                                                    <option value="41">FIRST SEMESTER</option>
                                                                    <option value="42">SECOND SEMESTER</option>
                                                                    <option value="43">THIRD SEMESTER</option>
                                                                    <option value="44">FOURTH SEMESTER</option>
                                                                    <option value="47">FIRST SEMESTER</option>
                                                                    <option value="48">SECOND SEMESTER</option>
                                                                    <option value="49">THIRD SEMESTER</option>
                                                                    <option value="50">FOURTH SEMESTER</option>
                                                                    <option value="51">FIRST SEMESTER</option>
                                                                    <option value="52">SECOND SEMESTER</option>
                                                                    <option value="53">THIRD SEMESTER</option>
                                                                    <option value="54">FOURTH SEMESTER</option>
                                                                    <option value="55">FIRST SEMESTER</option>
                                                                    <option value="56">SECOND SEMESTER</option>
                                                                    <option value="57">THIRD SEMESTER</option>
                                                                    <option value="58">FOURTH SEMESTER</option>
                                                                    <option value="63">FIRST SEMESTER</option>
                                                                    <option value="64">SECOND SEMESTER</option>
                                                                    <option value="65">THIRD SEMESTER</option>
                                                                    <option value="66">FOURTH SEMESTER</option>
                                                                    <option value="67">FIFTH SEMESTER</option>
                                                                    <option value="68">SIXTH SEMESTER</option>
                                                                    <option value="71">FIRST SEMESTER</option>
                                                                    <option value="72">SECOND SEMESTER</option>
                                                                    <option value="73">THIRD SEMESTER</option>
                                                                    <option value="74">FOURTH SEMESTER</option>
                                                                    <option value="75">FIRST SEMESTER</option>
                                                                    <option value="76">SECOND SEMESTER</option>
                                                                    <option value="77">THIRD SEMESTER</option>
                                                                    <option value="78">FOURTH SEMESTER</option>
                                                                    <option value="79">FIRST SEMESTER</option>
                                                                    <option value="80">SECOND SEMESTER</option>
                                                                    <option value="81">THIRD SEMESTER</option>
                                                                    <option value="82">FOURTH SEMESTER</option>
                                                                    <option value="83">FIFTH SEMESTER</option>
                                                                    <option value="84">SIXTH SEMESTER</option>
                                                                    <option value="85">SEVENTH SEMESTER</option>
                                                                    <option value="86">EIGHTH SEMESTER</option>
                                                                    <option value="87">FIRST SEMESTER</option>
                                                                    <option value="88">SECOND SEMESTER</option>
                                                                    <option value="89">THIRD SEMESTER</option>
                                                                    <option value="90">FOURTH SEMESTER</option>
                                                                    <option value="91">FIFTH SEMESTER</option>
                                                                    <option value="92">SIXTH SEMESTER</option>
                                                                    <option value="93">SEVENTH SEMESTER</option>
                                                                    <option value="94">EIGHTH SEMESTER</option>
                                                                    <option value="95">FIRST SEMESTER</option>
                                                                    <option value="96">SECOND SEMESTER</option>
                                                                    <option value="97">THIRD SEMESTER</option>
                                                                    <option value="98">FOURTH SEMESTER</option>
                                                                    <option value="99">FIFTH SEMESTER</option>
                                                                    <option value="100">SIXTH SEMESTER</option>
                                                                    <option value="101">SEVENTH SEMESTER</option>
                                                                    <option value="102">EIGHTH SEMESTER</option>
                                                                    <option value="103">FIRST SEMESTER</option>
                                                                    <option value="104">SECOND SEMESTER</option>
                                                                    <option value="105">THIRD SEMESTER</option>
                                                                    <option value="106">FOURTH SEMESTER</option>
                                                                    <option value="107">FIFTH SEMESTER</option>
                                                                    <option value="108">SIXTH SEMESTER</option>
                                                                    <option value="109">SEVENTH SEMESTER</option>
                                                                    <option value="110">EIGHTH SEMESTER</option>
                                                                    <option value="111">FIRST SEMESTER</option>
                                                                    <option value="112">SECOND SEMESTER</option>
                                                                    <option value="113">THIRD SEMESTER</option>
                                                                    <option value="114">FOURTH SEMESTER</option>
                                                                    <option value="115">FIFTH SEMESTER</option>
                                                                    <option value="116">SIXTH SEMESTER</option>
                                                                    <option value="117">SEVENTH SEMESTER</option>
                                                                    <option value="118">EIGHTH SEMESTER</option>
                                                                    <option value="119">FIRST SEMESTER</option>
                                                                    <option value="121">THIRD SEMESTER</option>
                                                                    <option value="122">FOURTH SEMESTER</option>
                                                                    <option value="123">FIFTH SEMESTER</option>
                                                                    <option value="124">SIXTH SEMESTER</option>
                                                                    <option value="125">SEVENTH SEMESTER</option>
                                                                    <option value="126">EIGHTH SEMESTER</option>
                                                                    <option value="127">FIRST SEMESTER</option>
                                                                    <option value="129">THIRD SEMESTER</option>
                                                                    <option value="130">FOURTH SEMESTER</option>
                                                                    <option value="131">FIFTH SEMESTER</option>
                                                                    <option value="132">SEVENTH SEMESTER</option>
                                                                    <option value="133">EIGHTH SEMESTER</option>
                                                                    <option value="134">SIXTH SEMESTER</option>
                                                                    <option value="135">FIRST SEMESTER</option>
                                                                    <option value="136">SECOND SEMESTER</option>
                                                                    <option value="137">THIRD SEMESTER</option>
                                                                    <option value="138">FOURTH SEMESTER</option>
                                                                    <option value="139">FIFTH SEMESTER</option>
                                                                    <option value="140">SIXTH SEMESTER</option>
                                                                    <option value="141">SEVENTH SEMESTER</option>
                                                                    <option value="142">EIGHTH SEMESTER</option>
                                                                    <option value="143">FIRST SEMESTER</option>
                                                                    <option value="144">SECOND SEMESTER</option>
                                                                    <option value="146">FOURTH SEMESTER</option>
                                                                    <option value="147">THIRD SEMESTER</option>
                                                                    <option value="148">FIRST SEMESTER</option>
                                                                    <option value="149">SECOND SEMESTER</option>
                                                                    <option value="150">THIRD SEMESTER</option>
                                                                    <option value="151">FOURTH SEMESTER</option>
                                                                    <option value="152">FIRST SEMESTER</option>
                                                                    <option value="153">SECOND SEMESTER</option>
                                                                    <option value="154">THIRD SEMESTER</option>
                                                                    <option value="155">FOURTH SEMESTER</option>
                                                                    <option value="156">FIRST SEMESTER</option>
                                                                    <option value="157">SECOND SEMESTER</option>
                                                                    <option value="158">THIRD SEMESTER</option>
                                                                    <option value="159">FOURTH SEMESTER</option>
                                                                    <option value="160">FIRST SEMESTER</option>
                                                                    <option value="161">SECOND SEMESTER</option>
                                                                    <option value="162">THIRD SEMESTER</option>
                                                                    <option value="163">FOURTH SEMESTER</option>
                                                                    <option value="164">FIRST SEMESTER</option>
                                                                    <option value="165">SECOND SEMESTER</option>
                                                                    <option value="166">THIRD SEMESTER</option>
                                                                    <option value="167">FOURTH SEMESTER</option>
                                                                    <option value="173">FIRST SEMESTER</option>
                                                                    <option value="175">SECOND SEMESTER</option>
                                                                    <option value="176">THIRD SEMESTER</option>
                                                                    <option value="177">FOURTH SEMESTER</option>
                                                                    <option value="180">FIRST SEMESTER</option>
                                                                    <option value="181">SECOND SEMESTER</option>
                                                                    <option value="182">THIRD SEMESTER</option>
                                                                    <option value="183">FOURTH SEMESTER</option>
                                                                    <option value="184">FIRST SEMESTER</option>
                                                                    <option value="185">SECOND SEMESTER</option>
                                                                    <option value="186">THIRD SEMESTER</option>
                                                                    <option value="187">FOURTH SEMESTER</option>
                                                                    <option value="188">FIRST SEMESTER</option>
                                                                    <option value="189">SECOND SEMESTER</option>
                                                                    <option value="190">THIRD SEMESTER</option>
                                                                    <option value="191">FOURTH SEMESTER</option>
                                                                    <option value="192">THIRD SEMESTER</option>
                                                                    <option value="193">THIRD SEMESTER</option>
                                                                    <option value="194">THIRD SEMESTER</option>
                                                                    <option value="199">FIRST SEMESTER</option>
                                                                    <option value="200">SECOND SEMESTER</option>
                                                                    <option value="201">THIRD SEMESTER</option>
                                                                    <option value="202">FOURTH SEMESTER</option>
                                                                    <option value="203">FIFTH SEMESTER</option>
                                                                    <option value="204">SIXTH SEMESTER</option>
                                                                    <option value="205">SEVENTH SEMESTER</option>
                                                                    <option value="206">EIGHT SEMESTER</option>
                                                                    <option value="207">NINTH SEMESTER</option>
                                                                    <option value="208">TENTH SEMESTER</option>
                                                                    <option value="209">FIRST SEMESTER</option>
                                                                    <option value="210">SECOND SEMESTER</option>
                                                                    <option value="211">THIRD SEMESTER</option>
                                                                    <option value="212">FOURTH SEMESTER</option>
                                                                    <option value="213">FIRST SEMESTER</option>
                                                                    <option value="214">SECOND SEMESTER</option>
                                                                    <option value="215">THIRD SEMESTER</option>
                                                                    <option value="216">FOURTH SEMESTER</option>
                                                                    <option value="217">FIFTH SEMESTER</option>
                                                                    <option value="218">SIXTH SEMESTER</option>
                                                                    <option value="223">FIRST SEMESTER</option>
                                                                    <option value="224">SECOND SEMESTER</option>
                                                                    <option value="225">THIRD SEMESTER</option>
                                                                    <option value="226">FOURTH SEMESTER</option>
                                                                    <option value="227">FIRST SEMESTER</option>
                                                                    <option value="228" selected="">SECOND SEMESTER</option>
                                                                    <option value="229">THIRD SEMESTER</option>
                                                                    <option value="230">FOURTH SEMESTER</option>
                                                                    <option value="231">FIFTH SEMESTER</option>
                                                                    <option value="232">SIXTH SEMESTER</option>
                                                                    <option value="233">FIRST SEMESTER</option>
                                                                    <option value="234">SECOND SEMESTER</option>
                                                                    <option value="235">THIRD SEMESTER</option>
                                                                    <option value="236">FOURTH SEMESTER</option>
                                                                    <option value="237">FIRST YEAR</option>
                                                                    <option value="238">SECOND YEAR</option>
                                                                    <option value="239">THIRD YEAR</option>
                                                                    <option value="240">FOURTH YEAR</option>
                                                                    <option value="241">FIRST SEMESTER</option>
                                                                    <option value="242">SECOND SEMESTER</option>
                                                                    <option value="243">THIRD SEMESTER</option>
                                                                    <option value="244">FOURTH SEMESTER</option>
                                                                    <option value="253">FIRST SEMESTER</option>
                                                                    <option value="254">SECOND SEMESTER</option>
                                                                    <option value="255">THIRD SEMESTER</option>
                                                                    <option value="256">FOURTH SEMESTER</option>
                                                                    <option value="257">FIRST SEMESTER</option>
                                                                    <option value="258">SECOND SEMESTER</option>
                                                                    <option value="259">THIRD SEMESTER</option>
                                                                    <option value="260">FOURTH SEMESTER</option>
                                                                    <option value="265">FIRST SEMESTER</option>
                                                                    <option value="266">SECOND SEMESTER</option>
                                                                    <option value="267">THIRD SEMESTER</option>
                                                                    <option value="268">FOURTH SEMESTER</option>
                                                                    <option value="269">FIFTH SEMESTER</option>
                                                                    <option value="270">SIXTH SEMESTER</option>
                                                                    <option value="272">FIRST SEMESTER</option>
                                                                    <option value="273">SECOND SEMESTER</option>
                                                                    <option value="274">THIRD SEMESTER</option>
                                                                    <option value="275">FOURTH SEMESTER</option>
                                                                    <option value="276">FIFTH SEMESTER</option>
                                                                    <option value="277">SIXTH SEMESTER</option>
                                                                    <option value="278">FIRST YEAR</option>
                                                                    <option value="279">FOURTH YEAR</option>
                                                                    <option value="280">THIRD YEAR</option>
                                                                    <option value="281">SECOND YEAR</option>
                                                                    <option value="282">FIRST YEAR</option>
                                                                    <option value="283">SECOND YEAR</option>
                                                                    <option value="284">FIRST SEMESTER</option>
                                                                    <option value="285">SECOND SEMESTER</option>
                                                                    <option value="286">FOURTH SEMESTER</option>
                                                                    <option value="287">FIRST SEMESTER</option>
                                                                    <option value="288">SECOND SEMESTER</option>
                                                                    <option value="289">FOURTH SEMESTER</option>
                                                                    <option value="290">FIRST SEMESTER</option>
                                                                    <option value="291">SECOND SEMESTER</option>
                                                                    <option value="292">FOURTH SEMESTER</option>
                                                                    <option value="296">FIRST SEMESTER</option>
                                                                    <option value="297">SECOND SEMESTER</option>
                                                                    <option value="298">THIRD SEMESTER</option>
                                                                    <option value="299">FOURTH SEMESTER</option>
                                                                    <option value="300">FIRST SEMESTER</option>
                                                                    <option value="301">SECOND SEMESTER</option>
                                                                    <option value="302">THIRD SEMESTER</option>
                                                                    <option value="303">FOURTH SEMESTER</option>
                                                                    <option value="304">FIRST SEMESTER</option>
                                                                    <option value="305">SECOND SEMESTER</option>
                                                                    <option value="306">THIRD SEMESTER</option>
                                                                    <option value="307">FOURTH SEMESTER</option>
                                                                    <option value="308">FIRST SEMESTER</option>
                                                                    <option value="309">SECOND SEMESTER</option>
                                                                    <option value="310">THIRD SEMESTER</option>
                                                                    <option value="311">FOURTH SEMESTER</option>
                                                                    <option value="312">FIRST SEMESTER</option>
                                                                    <option value="313">SECOND SEMESTER</option>
                                                                    <option value="314">THIRD SEMESTER</option>
                                                                    <option value="315">FOURTH SEMESTER</option>
                                                                    <option value="487">FIRST SEMESTER</option>
                                                                    <option value="488">SECOND SEMESTER</option>
                                                                    <option value="489">THIRD SEMESTER</option>
                                                                    <option value="490">FOURTH SEMESTER</option>
                                                                    <option value="492">FIRST SEMESTER</option>
                                                                    <option value="493">SECOND SEMESTER</option>
                                                                    <option value="494">THIRD SEMESTER</option>
                                                                    <option value="495">FOURTH SEMESTER</option>
                                                                    <option value="496">FIFTH SEMESTER</option>
                                                                    <option value="497">FIRST SEMESTER</option>
                                                                    <option value="498">SECOND SEMESTER</option>
                                                                    <option value="499">THIRD SEMESTER</option>
                                                                    <option value="500">FOURTH SEMESTER</option>
                                                                    <option value="501">FIFTH SEMESTER</option>
                                                                    <option value="502">SIXTH SEMESTER</option>
                                                                    <option value="503">FIRST YEAR</option>
                                                                    <option value="504">SECOND YEAR</option>
                                                                    <option value="505">FIRST SEMESTER</option>
                                                                    <option value="508">FIRST SEMESTER</option>
                                                                    <option value="509">FOURTH SEMESTER</option>
                                                                    <option value="510">YEAR</option>
                                                                    <option value="511">FIRST SEMESTER</option>
                                                                    <option value="512">SECOND SEMESTER</option>
                                                                    <option value="513">THIRD SEMESTER</option>
                                                                    <option value="514">FOURTH SEMESTER</option>
                                                                    <option value="515">SEVENTH SEMESTER</option>
                                                                    <option value="517">FIFTH SEMESTER</option>
                                                                    <option value="518">SIXTH SEMESTER</option>
                                                                    <option value="519">FIRST SEMESTER</option>
                                                                    <option value="520">FIRST SEMESTER</option>
                                                                    <option value="521">FIRST SEMESTER</option>
                                                                    <option value="522">SECOND SEMESTER</option>
                                                                    <option value="523">THIRD SEMESTER</option>
                                                                    <option value="524">FOURTH SEMESTER</option>
                                                                    <option value="525">FIRST SEMESTER</option>
                                                                    <option value="526">SECOND SEMESTER</option>
                                                                    <option value="527">THIRD SEMESTER</option>
                                                                    <option value="528">FOURTH SEMESTER</option>
                                                                    <option value="533">SECOND SEMESTER</option>
                                                                    <option value="535">SECOND SEMESTER</option>
                                                                    <option value="536">SECOND SEMESTER</option>
                                                                    <option value="537">First Year</option>
                                                                    <option value="538">Second Year</option>
                                                                    <option value="539">Third Year</option>
                                                                    <option value="540">FIRST YEAR</option>
                                                                    <option value="541">SECOND YEAR</option>
                                                                    <option value="542">THIRD YEAR</option>
                                                                    <option value="543">FOURTH YEAR</option>
                                                                    <option value="544">FIRST YEAR</option>
                                                                    <option value="545">SECOND YEAR</option>
                                                                    <option value="546">THIRD YEAR</option>
                                                                    <option value="548">THIRD SEMESTER</option>
                                                                    <option value="549">FOURTH SEMESTER</option>
                                                                    <option value="550">SECOND SEMESTER</option>
                                                                    <option value="551">THIRD SEMESTER</option>
                                                                    <option value="552">FOURTH SEMESTER</option>
                                                                    <option value="553">FIRST SEMESTER</option>
                                                                    <option value="554">SECOND SEMESTER</option>
                                                                    <option value="555">THIRD SEMESTER</option>
                                                                    <option value="556">FOURTH SEMESTER</option>
                                                                    <option value="557">FIRST SEMESTER</option>
                                                                    <option value="558">SECOND SEMESTER</option>
                                                                    <option value="559">THIRD SEMESTER</option>
                                                                    <option value="560">FOURTH SEMESTER</option>
                                                                    <option value="561">FIRST SEMESTER</option>
                                                                    <option value="562">SECOND SEMESTER</option>
                                                                    <option value="563">THIRD SEMESTER</option>
                                                                    <option value="564">FOURTH SEMESTER</option>
                                                                    <option value="565">THIRD YEAR</option>
                                                                    <option value="566">FIRST SEMESTER</option>
                                                                    <option value="567">SECOND SEMESTER</option>
                                                                    <option value="568">THIRD SEMESTER</option>
                                                                    <option value="569">FOURTH SEMESTER</option>
                                                                    <option value="570">FIRST SEMESTER</option>
                                                                    <option value="571">SECOND SEMESTER</option>
                                                                    <option value="572">THIRD SEMESTER</option>
                                                                    <option value="573">FOURTH SEMESTER</option>
                                                                    <option value="574">FIRST SEMESTER</option>
                                                                    <option value="575">SECOND SEMESTER</option>
                                                                    <option value="576">THIRD SEMESTER</option>
                                                                    <option value="577">FOURTH SEMESTER</option>
                                                                    <option value="579">FIRST SEMESTER</option>
                                                                    <option value="580">SECOND SEMESTER</option>
                                                                    <option value="581">THIRD SEMESTER</option>
                                                                    <option value="582">FOURTH SEMESTER</option>
                                                                    <option value="583">FIRST SEMESTER</option>
                                                                    <option value="584">SECOND SEMESTER</option>
                                                                    <option value="585">THIRD SEMESTER</option>
                                                                    <option value="586">FOURTH SEMESTER</option>
                                                                    <option value="587">FIRST SEMESTER</option>
                                                                    <option value="588">SECOND SEMESTER</option>
                                                                    <option value="589">THIRD SEMESTER</option>
                                                                    <option value="590">FOURTH SEMESTER</option>
                                                                    <option value="591">FIFTH SEMESTER</option>
                                                                    <option value="592">SIX SEMESTER</option>
                                                                    <option value="593">THIRD SEMESTER</option>
                                                                    <option value="595">FIRST SEMESTER</option>
                                                                    <option value="596">SECOND SEMESTER</option>
                                                                    <option value="597">THIRD SEMESTER</option>
                                                                    <option value="598">FOURTH SEMESTER</option>
                                                                    <option value="606">FIRST SEMESTER</option>
                                                                    <option value="607">SECOND SEMESTER</option>
                                                                    <option value="608">THIRD SEMESTER</option>
                                                                    <option value="609">FOURTH SEMESTER</option>
                                                                    <option value="610">FIRST SEMESTER</option>
                                                                    <option value="611">SECOND SEMESTER</option>
                                                                    <option value="612">THIRD SEMESTER</option>
                                                                    <option value="613">FOURTH SEMESTER</option>
                                                                    <option value="614">FIRST SEMESTER</option>
                                                                    <option value="615">SECOND SEMESTER</option>
                                                                    <option value="616">THIRD SEMESTER</option>
                                                                    <option value="617">FOURTH SEMESTER</option>
                                                                    <option value="618">FIRST SEMESTER</option>
                                                                    <option value="619">SECOND SEMESTER</option>
                                                                    <option value="620">THIRD SEMESTER</option>
                                                                    <option value="621">FOURTH SEMESTER</option>
                                                                    <option value="623">FIRST YEAR</option>
                                                                    <option value="625">SECOND YEAR</option>
                                                                    <option value="626">FIRST SEMESTER</option>
                                                                    <option value="627">SECOND SEMESTER</option>
                                                                    <option value="628">THIRD SEMESTER</option>
                                                                    <option value="629">FOURTH SEMESTER</option>
                                                                    <option value="632">FIRST SEMESTER</option>
                                                                    <option value="633">SECOND SEMESTER</option>
                                                                    <option value="634">THIRD SEMESTER</option>
                                                                    <option value="635">FOURTH SEMESTER</option>
                                                                    <option value="636">FIFTH SEMESTER</option>
                                                                    <option value="637">SIXTH SEMESTER</option>
                                                                    <option value="638">FIRST SEMESTER</option>
                                                                    <option value="639">SECOND SEMESTER</option>
                                                                    <option value="640">THIRD SEMESTER</option>
                                                                    <option value="641">FOURTH SEMESTER</option>
                                                                    <option value="642">FIFTH SEMESTER</option>
                                                                    <option value="643">SIXTH SEMESTER</option>
                                                                    <option value="644">SEVENTH SEMESTER</option>
                                                                    <option value="645">EIGHTH SEMESTER</option>
                                                                    <option value="646">FIRST YEAR</option>
                                                                    <option value="647">SECOND YEAR</option>
                                                                    <option value="649">THIRD YEAR</option>
                                                                    <option value="650">THIRD YEAR</option>
                                                                    <option value="651">FIRST SEMESTER</option>
                                                                    <option value="652">SECOND SEMESTER</option>
                                                                    <option value="653">THIRD SEMESTER</option>
                                                                    <option value="654">FOURTH SEMESTER</option>
                                                                    <option value="655">FIRST SEMESTER</option>
                                                                    <option value="656">SECOND SEMESTER</option>
                                                                    <option value="657">THIRD SEMESTER</option>
                                                                    <option value="658">FOURTH SEMESTER</option>
                                                                    <option value="659">FIRST SEMESTER</option>
                                                                    <option value="660">SECOND SEMESTER</option>
                                                                    <option value="661">THIRD SEMESTER</option>
                                                                    <option value="662">FOURTH SEMESTER</option>
                                                                    <option value="663">FIRST YEAR</option>
                                                                    <option value="664">SECOND YEAR</option>
                                                                    <option value="665">FIRST SEMESTER</option>
                                                                    <option value="666">SECOND SEMESTER</option>
                                                                    <option value="667">THIRD SEMESTER</option>
                                                                    <option value="668">FOURTH SEMESTER</option>
                                                                    <option value="669">FIRST SEMESTER</option>
                                                                    <option value="670">SECOND SEMESTER</option>
                                                                    <option value="671">THIRD SEMESTER</option>
                                                                    <option value="672">FOURTH SEMESTER</option>
                                                                    <option value="673">FIRST SEMESTER</option>
                                                                    <option value="674">SECOND SEMESTER</option>
                                                                    <option value="675">THIRD SEMESTER</option>
                                                                    <option value="676">FOURTH SEMESTER</option>
                                                                    <option value="677">YEAR</option>
                                                                    <option value="678">FIRST SEMESTER</option>
                                                                    <option value="679">SECOND SEMESTER</option>
                                                                    <option value="680">THIRD SEMESTER</option>
                                                                    <option value="681">FOURTH SEMESTER</option>
                                                                    <option value="682">FIRST SEMESTER</option>
                                                                    <option value="683">SECOND SEMESTER</option>
                                                                    <option value="684">FIRST SEMESTER</option>
                                                                    <option value="685">SECOND SEMESTER</option>
                                                                    <option value="686">FIRST SEMESTER</option>
                                                                    <option value="687">SECOND SEMESTER</option>
                                                                    <option value="688">THIRD SEMESTER</option>
                                                                    <option value="689">FOURTH SEMESTER</option>
                                                                    <option value="690">FIFTH SEMESTER</option>
                                                                    <option value="691">SIXTH SEMESTER</option>
                                                                    <option value="692">SEVENTH SEMESTER</option>
                                		                                </select>
    </div>
  </div>
</div>


<div class="row align-items-center mb-1">
  <div class="col-md-3">
    <label class="form-label">Subject Name<span class="text-danger">*</span></label>
  </div>
  <div class="col-md-5">
    <div class="form-group">
    <select class="form-control" id="subject" name="subject">
		                                    <option value="">Select Subject</option>
		                                                                         <option value="NBBA-205">Advertising Management</option>
                                                                    <option value="NCBBA-206" selected="">Agripreneurship &amp; Rural Business</option>
                                                                    <option value="NBBA-202">Business Finance</option>
                                                                    <option value="BBA-205">BUSINESS STATISTIC</option>
                                                                    <option value="BBA-204">FINANCIAL MATHEMATICS</option>
                                                                    <option value="NBBA-203">Human Resource Development</option>
                                                                    <option value="NBBA-204">Marketing Theory and Practices</option>
                                                                    <option value="NBBA-201">Organizational Behaviour</option>
                                		                                </select>
    </div>
  </div>
</div>


<div class="row align-items-center mb-1">
  <div class="col-md-3">
    <label class="form-label">Faculty Name<span class="text-danger">*</span></label>
  </div>
  <div class="col-md-5">
    <div class="form-group" data-select2-id="select2-data-4-rsid">
      <select class="form-control selectpicker-back faculty select2-hidden-accessible" id="faculty" name="faculty" data-select2-id="select2-data-faculty" tabindex="-1" aria-hidden="true">
        <option value="" data-select2-id="select2-data-2-b9fb">--Select Faculty--</option>
        <!-- Faculty options can be appended here -->
        <option value="1" data-select2-id="select2-data-12-d1e0">DR.HARI PRASAD</option>
        <option value="2" data-select2-id="select2-data-13-nu9b">DR. RASHMI MISHRA</option>
        <!-- ... -->
        <option value="480" data-select2-id="select2-data-428-5pmu">KUMARPAL SINGH</option>
      </select>
      <span class="select2 select2-container select2-container--default select2-container--above" dir="ltr" data-select2-id="select2-data-1-7wgs" style="width: 341.656px;">
        <span class="selection">
          <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-faculty-container" aria-controls="select2-faculty-container">
            <span class="select2-selection__rendered" id="select2-faculty-container" role="textbox" aria-readonly="true" title="--Select Faculty--">--Select Faculty--</span>
            <span class="select2-selection__arrow" role="presentation">
              <b role="presentation"></b>
            </span>
          </span>
        </span>
        <span class="dropdown-wrapper" aria-hidden="true"></span>
      </span>
    </div>
  </div>
</div>

{{-- <div class="row align-items-center mb-1">
  <div class="col-md-3">
    <label class="form-label">Subject Name<span class="text-danger">*</span></label>
  </div>
  <div class="col-md-5">
    <div class="form-group">
      <select class="form-select selectpicker-back" id="subject" name="subject">
        <option value="">--Select Subject--</option>
        <!-- Dynamic subject options can be appended here -->
      </select>
    </div>
  </div>
</div> --}}


<div class="row align-items-center mb-1">
  <div class="col-md-3">
    <label class="form-label">Session<span class="text-danger">*</span></label>
  </div>
  <div class="col-md-5">
    <div class="form-group">
    <select class="form-control" id="session" name="session">
		                                    <option value="">Select Session</option>
		                                    		                                     		                                    
                                    <option value="2021-2022">2021-2022</option>
                                		                                     		                                    
                                    <option value="2022-2023">2022-2023</option>
                                		                                     		                                    
                                    <option value="2023-2024">2023-2024</option>
                                		                                     		                                    
                                    <option value="2023-2024FEB">2023-2024FEB</option>
                                		                                     		                                    
                                    <option value="2023-2024JUL">2023-2024JUL</option>
                                		                                     		                                    
                                    <option value="2023-2024AUG" selected="">2023-2024AUG</option>
                                		                                     		                                    
                                    <option value="2024-2025">2024-2025</option>
                                		                                </select>
    </div>
  </div>
</div>



<div class="row align-items-center mb-1">
  <div class="col-md-3">
    <label class="form-label" for="permissions">Permission<span class="text-danger">*</span></label>
  </div>
  <div class="col-md-5">
    <div class="form-group">
    <select class="form-control selectpicker-back" id="permissions" name="permissions" required="">
		                                    <option value="">Select Permission</option>
		                                    <option value="all" selected="">All</option>
		                                    <option value="1">Internal</option>
		                                    <option value="2">External</option>
		                                    <option value="3">Practical External</option>
		                                </select>
    </div>
  </div>
</div>

                                                     
                                                    
                
                 

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
	
	
	  <div class="modal fade" id="attribute" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-2 mx-50 pb-2">
                    <h1 class="text-center mb-1" id="shareProjectTitle">Attribute Selling Pricing</h1>
                    <p class="text-center">Enter the details below.</p>

                    <div class="table-responsive-md customernewsection-form">
                                <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail"> 
                                    <thead>
                                         <tr>  
                                            <th>#</th>
                                            <th>Attribute Name</th>
                                            <th>Attribute Value</th>
                                            <th>Extra Selling Cost</th>
                                            <th>Actual Selling Price</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                             <tr> 
                                                <td>1</td>
                                                <td class="fw-bolder text-dark">Color</td>
                                                <td>Black</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>

                                            <tr>   
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>White</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>Red</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>Golden</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>Silver</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>2</td>
                                              <td class="fw-bolder text-dark">Size</td>
                                              <td>5.11 Inch</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td> 
                                              <td>6.0 Inch</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td>&nbsp;</td>
                                              <td>6.25 Inch</td>
                                                <td><input type="text" value="20" class="form-control mw-100" /></td> 
                                                <td><input type="text" disabled value="2000" class="form-control mw-100" /></td> 
                                            </tr> 
                                       </tbody>


                                </table>
                            </div>
                </div>

                <div class="modal-footer justify-content-center">  
                        <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button> 
                    <button type="reset" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>
	 
	
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
	 <script src="../../../app-assets/vendors/js/editors/quill/katex.min.js"></script>
    <script src="../../../app-assets/vendors/js/editors/quill/highlight.min.js"></script>
    <script src="../../../app-assets/vendors/js/editors/quill/quill.min.js"></script>
	<script src="../../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../../app-assets/js/core/app-menu.js"></script>
    <script src="../../../app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS--> 
	 <script src="../../../app-assets/js/scripts/forms/form-quill-editor.js"></script>
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
        
         $(function() {
           $("input[name='goodsservice']").click(function() {
             if ($("#service").is(":checked")) {
               $(".hsn").hide();
               $(".sac").show();
             } else {
               $(".hsn").show();
               $(".sac").hide();
             }
           });
         });
        
         $(".select2").select2({
            placeholder: "Select", 
        });
		 
		 
		
    </script>
</body>
<!-- END: Body-->

</html> --}}

@endsection

@extends('master.faculty.faculty-meta')
  @section('content') 
    
  
<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

 
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Edit-Faculty
 </h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                                    <li class="breadcrumb-item active">Edit-Faculty
</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0"onclick="history.go(-1)"> <i data-feather="arrow-left-circle"></i> Go Back
                            </button>
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" > <i data-feather="check-circle" style="font-size: 40px;"></i>
                            Update</button>


                    </div>
                </div>
            </div>
            <div class="content-body bg-white p-3 shadow">
                <form>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="campus">Campus</label>
                            <select class="form-control" id="campus">
                                <option>--Select Campus--</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter name here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter password here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="mobile">Mobile</label>
                            <input type="text" class="form-control" id="mobile" placeholder="Enter mobile here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="dob">Date Of Birth</label>
                            <input type="text" class="form-control" id="dob" placeholder="mm/dd/yyyy">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender">
                                <option>Select Gender</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="fatherName">Father Name</label>
                            <input type="text" class="form-control" id="fatherName" placeholder="Enter father name here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="aadhar">Aadhar Number</label>
                            <input type="text" class="form-control" id="aadhar" placeholder="Enter aadhar number here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="pan">Pan Number</label>
                            <input type="text" class="form-control" id="pan" placeholder="Enter pan card number here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="department">Department Name</label>
                            <input type="text" class="form-control" id="department" placeholder="Enter Department Name here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="designation">Designation</label>
                            <select class="form-control" id="designation">
                                <option>Select Designation</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="maritalStatus">Marital Status</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="maritalStatus" id="married" value="married">
                                    <label class="form-check-label" for="married">Married</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="maritalStatus" id="unmarried" value="unmarried">
                                    <label class="form-check-label" for="unmarried">UnMarried</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="bankAccount">Bank Account Number</label>
                            <input type="text" class="form-control" id="bankAccount" placeholder="Enter bank account number here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="ifsc">IFSC Code</label>
                            <input type="text" class="form-control" id="ifsc" placeholder="Enter IFSC Code here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="highSchoolMarksheet">High School Marksheet</label>
                            <input type="file" class="form-control" id="highSchoolMarksheet">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="highSchoolCertificate">High School Certificate</label>
                            <input type="file" class="form-control" id="highSchoolCertificate">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="intermediateMarksheet">Intermediate Marksheet</label>
                            <input type="file" class="form-control" id="intermediateMarksheet">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="intermediateCertificate">Intermediate Certificate</label>
                            <input type="file" class="form-control" id="intermediateCertificate">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="graduationMarksheet">Graduation Marksheet</label>
                            <input type="file" class="form-control" id="graduationMarksheet">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="graduationDegree">Graduation Degree</label>
                            <input type="file" class="form-control" id="graduationDegree">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="postGraduationMarksheet">Post Graduation Marksheet</label>
                            <input type="file" class="form-control" id="postGraduationMarksheet">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="postGraduationDegree">Post Graduation Degree</label>
                            <input type="file" class="form-control" id="postGraduationDegree">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="phdDegree">PHD Degree</label>
                            <input type="file" class="form-control" id="phdDegree">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="mPhilMarksheet">M Phil Marksheet</label>
                            <input type="file" class="form-control" id="mPhilMarksheet">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="mPhilDegree">M Phil Degree</label>
                            <input type="file" class="form-control" id="mPhilDegree">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF format accepted</small>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="researchPaperType">Research Paper Type</label>
                            <select class="form-control" id="researchPaperType">
                                <option>--Select--</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="numberOfResearchPaper">Number Of Research Paper</label>
                            <input type="text" class="form-control" id="numberOfResearchPaper" placeholder="Enter number of research paper here">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="passportSizePhoto">Passport Size Photo</label>
                            <input type="file" class="form-control" id="passportSizePhoto">
                            <small class="form-text">Uploaded doc should not be more than 200KB and only PDF/JPEG format accepted</small>
                        </div>
                    </div>
                </form>

               

            </div>
    </div>
          
            
    @endsection
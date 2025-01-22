@extends("ums.admin.admin-meta")
@section("content")

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Admit Card Form</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"> Admin form</li>  
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right"> 
                  <button class="btn btn-primary btn-sm" href="#">
                    Admit Card Already Generated
                </button>
              
                </div>
            </div>
        </div>
        <div class="customernewsection-form poreportlistview bg-white shadow p-5">
            <div class="row ">
                                                <!-- Candidate Information Section -->
<div class="col-md-3">
    <div class="mb-1">
        <label class="form-label" for="paperType">Enrollment Number</label>
        <input type="text" id="paperType" placeholder="Enter Enrollment Number" class="form-control">
    </div>
</div>
<div class="col-md-3">
    <div class="mb-1">
        <label class="form-label" for="campus">Roll Number</label>
        <input type="text" id="campus" placeholder="Enter Roll Number" class="form-control">
    </div>
</div>
<div class="col-md-3">
    <div class="mb-1">
        <label class="form-label" for="course">Student Name</label>
        <input type="text" id="course" placeholder="Enter Student Name" class="form-control">
    </div>
</div>
<div class="col-md-3">
    <div class="mb-1">
        <label class="form-label" for="semester">Gender</label>
        <select id="semester" class="form-select">
            <option value="">--Select Gender--</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="mb-1">
        <label class="form-label" for="academicSession">Mobile Number</label>
        <input type="text" id="academicSession" placeholder="Enter Mobile Number" class="form-control">
    </div>
</div>
<div class="col-md-3">
    <div class="mb-1">
        <label class="form-label" for="month">Email</label>
        <input type="email" id="month" placeholder="Enter Email" class="form-control">
    </div>
</div>
<div class="col-md-3">
    <div class="mb-1">
        <label class="form-label" for="year">Father Name</label>
        <input type="text" id="year" placeholder="Enter Father Name" class="form-control">
    </div>
</div>
<div class="col-md-3">
    <div class="mb-1">
        <label class="form-label" for="center">Address</label>
        <input type="text" placeholder="Enter Address" class="form-control">
    </div>
</div>

<!-- Candidate Photograph Section -->
<div class="col-md-3">
    <div class="form-group position-relative custom-form-group inner-formnew">
        <span class="form-label main-page">Candidate Photograph</span>
        <img src="" width="10%" name="candidate_photograph" alt="Photograph">
        <input type="file" class="form-control mt-2" name="candidate_photograph_upload" accept="image/*">
    </div>
</div>

<!-- Candidate Signature Section -->
<div class="col-md-3">
    <div class="form-group position-relative custom-form-group inner-formnew">
        <span class="form-label main-page">Candidate's Signature</span>
        <img src="" width="10%" name="candidate_signature" alt="Signature">
        <input type="file" class="form-control mt-2" name="candidate_signature_upload" accept="image/*">
    </div>
</div>

    
            </div>
        </div>
        <hr class="bold">
        <div class="col-md-3 p-1">
            <label class="form-label mb-sm-1">Exam Center Address <span class="text-danger">*</span></label>  
        </div>  
        
        <div class="col-md-5 p-1">
            <input type="text" placeholder="SHRI CHANDRABHAN SINGH DEGREE COLLEGE, CHANDPUR GONAPAR JAUNPUR U.P." class="form-control">
        </div> 
        
    
        
        <!-- ChartJS section start -->
        <section id="chartjs-chart">
            <div class="row ">
                <div class="col-md-12 col-12">
                    <div class="card new-cardbox">
                        <div class="table-responsive">
                            <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                <thead>
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Nishu Garg</td>
                                        <td>nishu@gmail.com</td>
                                        <td>9876787656</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>   
            </div>     
        </section>
        <!-- ChartJS section end -->
        

    </div>
  </div>
  

  

    <!-- END: Content-->


@endsection
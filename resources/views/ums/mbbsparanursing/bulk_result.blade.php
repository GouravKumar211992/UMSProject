@extends("ums.admin.admin-meta")
@section('content')
{{-- <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
  
<!-- BEGIN: Content-->
<div class="app-content content ">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper container-xxl p-0">
      <div class="content-header row">
          <div class="content-header-left col-md-5 mb-2">
              <div class="row breadcrumbs-top">
                  <div class="col-12">
                      <h2 class="content-header-title float-start mb-0">Exam Schedule</h2>
                      <div class="breadcrumb-wrapper">
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>  
                          </ol>
                      </div>
                  </div>
              </div>
          </div>
          <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
              <div class="form-group breadcrumb-right"> 
                <button class="btn btn-primary btn-sm" href="#">
                  <i data-feather="check-circle" ></i>
                  Get Report
              </button>
              <button class="btn btn-outline-secondary btn-sm" id="exportButton">
                <i data-feather="share" class="font-small-4 me-50"></i>Export
            </button>
           
             <button class="btn btn-warning box-shadow-2 btn-sm  mb-sm-0 mb-50" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>Reset</button>
             
                     
                       
              </div>
          </div>
      </div>
      <div class="customernewsection-form poreportlistview p-1">
          <div class="row">
              <!-- First Row -->
              <div class="col-md-3">
                  <div class="mb-1">
                      <label class="form-label">Courses:</label>
                      <select class="form-select">
                          <option>--Choose Course--</option>
                          <option>Raw Material</option>
                          <option>Semi Finished</option>
                          <option>Finished Goods</option>
                          <option>Traded Item</option>
                          <option>Asset</option>
                          <option>Expense</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-1">
                      <label class="form-label">Semester:</label>
                      <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-1">
                      <label class="form-label">Batch:</label>
                      <select class="form-select select2">
                          <option value="">--Batch--</option>
                          <option value="2021-2022">2021-2022</option>
                          <option value="2022-2023">2022-2023</option>
                          <option value="2023-2024">2023-2024</option>
                          <option value="2023-2024FEB">2023-2024FEB</option>
                          <option value="2023-2024JUL">2023-2024JUL</option>
                          <option value="2023-2024AUG">2023-2024AUG</option>
                          <option value="2024-2025">2024-2025</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="mb-1">
                      <label class="form-label">Result Type:</label>
                      <select class="form-select select2">
                          <option>Regular</option>
                          <option>Raw Material</option>
                          <option>Semi Finished</option>
                          <option>Finished Goods</option>
                      </select>
                  </div>
              </div>
          </div>
         
      </div>
  </div>
</div>

   
  @endsection
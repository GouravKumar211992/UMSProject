@extends('ums.student.student-meta')

@section('content')

<div class="app-content content ">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper container-xxl p-0">
      <div class="content-header row">
          <div class="content-header-left col-md-5 mb-2">
              <div class="row breadcrumbs-top">
                  <div class="col-12">
                      <h2 class="content-header-title float-start mb-0">Personal Information</h2>
                      <div class="breadcrumb-wrapper">
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="stu-dashboard">Home</a></li>  
                              <li class="breadcrumb-item active">Profile</li>
                          </ol>
                      </div>
                  </div>
              </div>
          </div>
          <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
              <div class="form-group breadcrumb-right"> 
                  
                @if(Auth::guard('admin')->check())
                <a href="{{url('student/edit-stu-profile', base64_encode($student->roll_number))}}" 
                   class="btn btn-primary btn-lg">
                    Update Profile
                </a>
            @endif
              </div>
          </div>
          
      </div>
      
<section class="content-body ">
    <!-- Profile Card -->
    <div class="card shadow-lg rounded-4">
        <!-- Profile Header -->
        

        <!-- Profile Content Section -->
        <div class="row g-4 p-4">
            <!-- Profile Image and Signature -->
            <div class="col-md-4 text-center shadow p-2">
                {{-- <div class="d-flex flex-column align-items-center">
                    <img src="{{$student->photo}}" alt="Student Photo" class="img-fluid rounded-circle border-4 border-primary mb-4" style="width: 200px; height: 200px; object-fit: cover;">
                    <img src="{{$student->signature}}" alt="Student Signature" class="img-fluid mb-4" style="max-width: 100%; height: auto;">
                </div> --}}
                
                <div class="d-flex flex-column  align-items-center" style="justify-content: space-around">
                    <!-- Default photo if not available -->
                    <img src="{{ $student->photo ? $student->photo : asset('img/user.png') }}" alt="Student Photo" class="img-fluid rounded-circle border-4 border-primary mb-4" style="width: 230px; height: 230px; object-fit: cover;">
                    
                    <!-- Default signature if not available -->
                    <span>Signature:</span><img src="{{ $student->signature ? $student->signature : asset('img/Signature.jpg') }}" alt="Student Signature"  width="100px" class="img-fluid mb-4" style="max-width: 100%; height: auto;">
                </div>
                
            </div>

            <!-- Student Information -->
            <div class="col-md-8">
                <div class="row row-cols-2 g-3">
                    {{-- <div class="col p-2" style="background: rgba(160, 188, 139, 0.2); color: #A0BC8B;">
                        <div class="bg-light p-3 rounded-3 shadow-sm">
                            <p class="fw-bold">Full Name</p>
                            <p>{{$student->first_Name}}</p>
                        </div>
                    </div> --}}
                    <div class="col">
                      <div class="holiday-box p-5" style=" border-left: 10px solid #8d3fc1">

                          <div>
                            <p class="fw-bolde"><span style="background: rgba(128, 35, 171, 0.258); color: #b18ac8;">Full Name</span></p>
                            <p class="fw-bolder">{{$student->first_Name}}</p>
                          </div>
                      </div>
                  </div>
                    {{-- <div class="col">
                        <div class="bg-light p-3 rounded-3 shadow-sm">
                            <p class="fw-bold">Roll Number</p>
                            <p>{{$student->roll_number}}</p>
                        </div>
                    </div> --}}
                    <div class="col">
                      <div class="holiday-box p-5" style=" border-left: 10px solid #0c78dc;">

                          <div>
                            <p class="fw-bold"><span style="background: rgba(31, 105, 190, 0.653); color: #4f6c89;">Roll Number</span></p>
                            <p class="fw-bolder">{{$student->roll_number}}</p>
                          </div>
                      </div>
                  </div>
                    {{-- <div class="col">
                        <div class="bg-light p-3 rounded-3 shadow-sm">
                            <p class="fw-bold">Email</p>
                            <p>{{$student->email}}</p>
                        </div>
                    </div> --}}
                    <div class="col">
                      <div class="holiday-box p-5" style=" border-left: 10px solid #A0BC8B;">

                          <div>
                            <p class="fw-bold"><span style="background: rgba(160, 188, 139, 0.2); color: #A0BC8B;">Email</span></p>
                            <p class="fw-bolder">{{$student->email}}</p>
                          </div>
                      </div>
                  </div>
                    {{-- <div class="col">
                        <div class="bg-light p-3 rounded-3 shadow-sm">
                            <p class="fw-bold">Mobile Number</p>
                            <p>{{$student->mobile}}</p>
                        </div>
                    </div> --}}
                    <div class="col">
                      <div class="holiday-box p-5" style=" border-left: 10px solid #e67d0e;">

                          <div>
                            <p class="fw-bold"><span style="background: rgba(216, 126, 51, 0.2); color: #ed9805;">Mobile Number:</span></p>
                            <p class="fw-bolder">{{$student->mobile}}</p>
                          </div>
                      </div>
                  </div>
                </div>
            </div>
        </div>

        <!-- Personal Details Section -->
        <div class="card-body">
            <div class="row p-3 gap-2">
              {{-- <div class="col-md shadow" >
                  <h5 class="fw-bold mb-3 p-2   bg-info text-white">Personal Details</h5>
                    <div class="mb-3 d-flex gap-2">
                        <p class="fw-bold">Father's Name   <i data-feather='arrow-right' class="ms-2 text-info"></i></p>
                        <p>{{$student->father_first_name}}</p>
                    </div>
                    <div class="mb-3 d-flex gap-2">
                        <p class="fw-bold">Mother's Name<i data-feather='arrow-right' class="ms-2 text-info"></i></p>
                        <p>{{$student->mother_first_name}}</p>
                    </div>
                    <div class="mb-3 d-flex gap-2">
                        <p class="fw-bold">Date of Birth<i data-feather='arrow-right' class="ms-3 text-info"></i></p>
                        <p>{{date('d-m-Y', strtotime($student->date_of_birth))}}</p>
                    </div>
                    <div class="mb-3 d-flex gap-2">
                        <p class="fw-bold">Gender<i data-feather='arrow-right' class="ms-4 text-justify text-info"></i></p>
                        <p>{{ucwords($student->gender)}}</p>
                    </div>
                </div>

                <div class="col-md shadow  " >
                    <h5 class="fw-bold mb-3  p-2   bg-danger text-white">Additional Information</h5>
                    <div class="mb-3 d-flex gap-2 " style="justify-content: space-around">
                        <p class="fw-bold">Enrollment Number <i data-feather='arrow-right' class="ms-2 text-danger"></i></p>
                        <p>{{$student->enrollment_no}}</p>
                    </div>
                    <div class="mb-3 d-flex gap-2 " style="justify-content: space-around">
                        <p class="fw-bold">Category <i data-feather='arrow-right' class="ms-3 text-danger"></i></p>
                        <p>{{ucwords($student->category)}}</p>
                    </div>
                    <div class="mb-3 d-flex gap-2" style="justify-content: space-around">
                        <p class="fw-bold">Nationality <i data-feather='arrow-right' class="ms-3 text-danger"></i></p>
                        <p>{{ucwords($student->nationality)}}</p>
                    </div>
                    <div class="mb-3 d-flex gap-2" style="justify-content: space-around">
                        <p class="fw-bold">Religion <i data-feather='arrow-right' class="ms-3 text-danger"></i></p>
                        <p>{{ucwords($student->religion)}}</p>
                    </div>
                </div>

                <div class="col-md shadow " >
                    <h5 class="fw-bold mb-3  p-2  bg-warning text-white">Extended Details</h5>
                    <div class="mb-3 d-flex gap-2"  style="justify-content: space-around">
                        <p class="fw-bold">Marital Status<i data-feather='arrow-right' class="ms-3 text-warning"></i></p>
                        <p>{{ucwords($student->marital_status)}}</p>
                    </div>
                    <div class="mb-3 d-flex gap-2"  style="justify-content: space-around">
                        <p class="fw-bold">Disability Category<i data-feather='arrow-right' class="ms-3 text-warning"></i></p>
                        <p>{{ucwords($student->disabilty_category)}}</p>
                    </div>
                    <div class="mb-3 d-flex gap-2"  style="justify-content: space-around">
                        <p class="fw-bold">Aadhar Number<i data-feather='arrow-right' class="ms-3 text-warning"></i></p>
                        <p>{{$student->aadhar}}</p>
                    </div>
                </div>
            </div> --}}
            <div class="col-md shadow">
                <h5 class="fw-bold mb-3 p-2 rounded bg-info text-white">Personal Details</h5>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Father's Name</p>
                    <i data-feather='arrow-right' class="ms-2 text-info"></i>
                    <p>{{$student->father_first_name}}</p>
                </div>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Mother's Name</p>
                    <i data-feather='arrow-right' class="ms-2 text-info"></i>
                    <p>{{$student->mother_first_name}}</p>
                </div>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Date of Birth</p>
                    <i data-feather='arrow-right' class="ms-3 text-info"></i>
                    <p>{{date('d-m-Y', strtotime($student->date_of_birth))}}</p>
                </div>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Gender</p>
                    <i data-feather='arrow-right' class="ms-5 text-info"></i>
                    <p>{{ucwords($student->gender)}}</p>
                </div>
            </div>
            
            <div class="col-md shadow">
                <h5 class="fw-bold mb-3 p-2 bg-danger text-white">Additional Information</h5>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Enrollment Number</p>
                    <i data-feather='arrow-right' class="ms-2 text-danger"></i>
                    <p>{{$student->enrollment_no}}</p>
                </div>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Category</p>
                    <i data-feather='arrow-right' class="ms-5 text-danger"></i>
                    <p>{{ucwords($student->category)}}</p>
                </div>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Nationality</p>
                    <i data-feather='arrow-right' class="ms-5 text-danger"></i>
                    <p>{{ucwords($student->nationality)}}</p>
                </div>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Religion</p>
                    <i data-feather='arrow-right' class="ms-5 text-danger"></i>
                    <p>{{ucwords($student->religion)}}</p>
                </div>
            </div>
            
            <div class="col-md shadow">
                <h5 class="fw-bold mb-3 p-2 bg-warning text-white">Extended Details</h5>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Marital Status</p>
                    <i data-feather='arrow-right' class="ms-3 text-warning"></i>
                    <p>{{ucwords($student->marital_status)}}</p>
                </div>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Disability Category</p>
                    <i data-feather='arrow-right' class="ms-3 text-warning"></i>
                    <p>{{ucwords($student->disabilty_category)}}</p>
                </div>
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <p class="fw-bold">Aadhar Number</p>
                    <i data-feather='arrow-right' class="ms-3 text-warning"></i>
                    <p>{{$student->aadhar}}</p>
                </div>
            </div>
            

            <!-- Address Section -->
            <div class="mt-2 col-4">
                <h5 class="fw-bold mb-3">Contact Address</h5>
                <div class="bg-white p-3 rounded-3 shadow-sm">
                    <p>{{$student->address}}</p>
                    <p><strong>Pin Code:</strong> {{$student->pin_code}}203131</p>
                </div>
            </div>
        </div>

        <!-- Profile Update Button (Admin only) -->
        <div class="card-footer text-end">
            @if(Auth::guard('admin')->check())
                <a href="{{url('student/edit-stu-profile', base64_encode($student->roll_number))}}" 
                   class="btn btn-primary btn-lg">
                    Update Profile
                </a>
            @endif
        </div>
    </div>
</section>
@endsection

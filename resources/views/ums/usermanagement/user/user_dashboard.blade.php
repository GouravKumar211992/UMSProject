
@extends('ums.admin.admin-meta')

@section('content')
{{-- {{dd()}} --}}
    <style>
.blink_me {
  font-size: 30px;
  animation: blinker 1s linear infinite;
}

@keyframes  blinker {
  50% {
    opacity: 0;
  }
}

    .responsive-table td {
        word-wrap: break-word;
        white-space: normal;
    }
    .responsive-table td:first-child {
        width: 180px; /* Adjust width for the first column */
        font-weight: bold;
    }
    .responsive-table td:nth-child(2) {
        width: 10px; /* Add width for the colon separator */
        text-align: center;
    }
    .responsive-table td:nth-child(3) {
        max-width: 300px; /* Adjust max width for the third column */
    }

.heading{
    background-color: #f3f2f7;
    padding: 8px;
}

    </style>
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                 
                @include('ums.admin.notifications')
                <section class="app-user-view-billing">
                    <div class="row">
                        <!-- User Sidebar -->
                        <div class="col-xl col-lg col-md order-1 order-md-0 mt-1">
                            <!-- User Card -->
                            <div class="card py-1">
                                <h4 class="fw-bolder border-bottom mt-1 text-center">
                                    <img src="images/icon/db1.png" alt=""> My Profile
                                </h4>
                                <div class="card-body">
                                    <div class="user-avatar-section">
                                        <div class="d-flex align-items-center flex-column">
                                            <img class="img-fluid rounded mt-2 mb-2" 
                                                src="../../../app-assets/images/portrait/small/avatar-s-4.jpg" 
                                                height="110" width="110" alt="User avatar">
                                            <div class="user-info text-center">
                                                <h4>{{ $user_data->name }}</h4> <!-- Display User Name -->
                                                <span class="badge bg-light-secondary">Roll No. {{ $user_data->id }}</span> <!-- Display Roll Number -->
                                            </div>
                                        </div>
                                    </div>
    
                                    {{-- <div class="udb-sec udb-prof py-1">
                                        <div class="sdb-tabl-com sdb-pro-table">
                                            <table class="responsive-table bordered">
                                                <tbody>
                                                    <tr>
                                                        <td>Student Name</td>
                                                        <td>:</td>
                                                        <td>{{ $user_data->first_name }}{{$user_data->last_name}}</td> <!-- Display Student Name -->
                                                    </tr>
                                                    <tr>
                                                        <td>Email</td>
                                                        <td>:</td>
                                                        <td>{{ $user_data->email }}</td> <!-- Display Email -->
                                                    </tr>
                                                    <tr>
                                                        <td>Phone</td>
                                                        <td>:</td>
                                                        <td>{{ $user_data->mobile }}</td> <!-- Display Phone Number -->
                                                    </tr>                       
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> --}}
                                    @if($data && $data['application']) 
                                    <div class="udb " > 
                                        <div class="udb-sec udb-prof">
                                            <h4><img src="images/icon/db1.png" alt=""> My Profile</h4>
                                            <p style="display:none;">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed
                                                to using 'Content here, content here', making it look like readable English.</p>
                                            <div class="sdb-tabl-com sdb-pro-table">
                                                <table class="responsive-table bordered p-2 " style="min-height:399px;">
                                                    <tbody class="">
                                                        <tr >
                                                            <td>Student Name</td>
                                                            <td>:</td>
                                                            <td>{{$data['application']->first_Name}} {{$data['application']->middle_Name}} {{$data['application']->last_Name}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Student Id</td>
                                                            <td>:</td>
                                                            <td>{{$data['application']->application_no}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <td>:</td>
                                                            <td>{{$data['application']->email}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Phone</td>
                                                            <td>:</td>
                                                            <td>{{$data['application']->mobile}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Date of birth</td>
                                                            <td>:</td>
                                                            <td>{{date('d-M-Y',strtotime($data['application']->date_of_birth))}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Address</td>
                                                            <td>:</td>
                                                            <td>{{$address}}</td>
                                                        </tr>   
                                                        <!-- <tr>
                                                            <td>Status</td>
                                                            <td>:</td>
                                                            <td><span class="db-done">{{$data['application']->status}}</span> </td>
                                                        </tr> -->
                                                    </tbody>
                                                </table>
                                                <!--<div class="sdb-bot-edit"> 
                                                    <a href="#" class="waves-effect waves-light btn-large sdb-btn"><i class="fa fa-pencil"></i> Edit my profile</a>
                                                </div>-->
                                            </div>
                                        </div> 
                                    </div>
                                         
                                @else 
                                    <div class="udb"> 
                                            <div class="udb-sec udb-prof">
                                            <h4><img src="images/icon/db1.png" alt=""> My Profile</h4>
                                            <div class="sdb-tabl-com sdb-pro-table">
                                                <table class="responsive-table bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td>Student Name</td>
                                                            <td>:</td>
                                                            <td>{{$data['user_data']->first_name}} {{$data['user_data']->middle_name}} {{$data['user_data']->last_name}}
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Email</td>
                                                            <td>:</td>
                                                            <td>{{$data['user_data']->email}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Phone</td>
                                                            <td>:</td>
                                                            <td>{{$data['user_data']->mobile}}</td>
                                                        </tr>                       
                                                    </tbody>
                                                </table>
                                                <!--<div class="sdb-bot-edit"> 
                                                    <a href="#" class="waves-effect waves-light btn-large sdb-btn"><i class="fa fa-pencil"></i> Edit my profile</a>
                                                </div>-->
                                            </div>
                                        </div> 
                                    </div>
                                         
                                @endif
                                </div>
                            </div>
                        </div>
                        <!--/ User Sidebar -->
    
                        <!-- User Content -->
                        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1 me-1">
                            <div class="sdb-tabl-com sdb-pro-table py-1">
                                <div class="row shadow p-1 bg-white">
                                    <div class="text-center">
                                        <h1 class="auto-style14">Notification-Board</h1>
                                    </div>
                                    <br><br>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Notification Description</th>
                                                <th scope="col">Notification Started</th>
                                                <th scope="col">Notification Ended</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($notifications as $notification)
                                            <tr>
                                                   
                                                {{-- {{dd($notification_description)}} --}}
                                                    <td>{{ $notification->notification_description }}</td> <!-- Display Notification Description -->
                                                    <td>{{ $notification->notification_start }}</td> <!-- Display Notification Start Date -->
                                                    <td>{{ $notification->notification_end}}</td> <!-- Display Notification End Date -->
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
    
                            <div class="col-md-12 mt py-1">
                                <div class="row udb py-1 shadow bg-white">
                                    <div class="udb-sec udb-cour-stat text-center">
                                        <h1 class="text-center">My-Dashboard</h1>
                                        <h4 class="heading">
                                            <img src="images/icon/db3.png" alt=""> Course Application Status
                                        </h4>
                                        
    
                                        {{-- @if($lastApplication)
                                            <p><b>You have already applied for the course!</b></p>
                                            <p>Course: {{ $lastApplication->course->name }}</p> <!-- Display Course Name -->
                                            <p>Status: {{ $lastApplication->status }}</p> <!-- Display Application Status -->
                                        @else
                                            <b>You have not applied for any Course yet! Please Apply First.</b><br><br>
                                            <a href="{{url('/user-application-form')}}">
                                                <span class="blink_me">👉</span><b>Click Here To Apply</b>
                                            </a><br>
                                        @endif --}}
                                        @if (count($applications) > 0)
                                        <div class="mt-4 pt-4">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Course Name</th>
                                                            <th>Applied Date</th>
                                                            <th>View</th>
                                                            <th style="width: 300px;">Application Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                            
                                                        @foreach ($applications as $key => $application)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td><a href="#"><span
                                                                            class="list-enq-name">{{ $application->course->name }}</span></a></td>
                                                                <td>{{ date('d-M-Y', strtotime($application->created_at)) }}</td>
                                                                <td>
                                                                    <a href="{{ route('view-application-form', ['application_id' => $application->id]) }}"
                                                                        class="ad-st-view">View and Print</a>
                                                                    @if ($application->course_id == 94 && $application->phd_2023_entrance_details)
                                                                        <a href="{{ url('phd-entrance-admitcard') }}?roll_number={{ $application->phd_2023_entrance_details->roll_number }}"
                                                                            class="btn btn-info">View and Print Admit Card</a>
                                                                    @endif
                                                                    @if (admission_open_couse_wise($application->course_id, 2, $application->academic_session))
                                                                        
                                                                        <a href="{{ route('view-application-form', ['application_id' => $application->id, 'edit' => 'true']) }}"
                                                                            class="btn-sm btn-primary">Edit Application</a>
                                                                    @endif
                                                                    @if ($application->entranceExamAdmitCard && $application->payment_status == 1)
                                                                        <a href="{{ url('entrance-admit-card/' . $application->id) }}"
                                                                            class="ad-st-view" target="_blank">Download Admit Card</a>
                                                                    @endif
                                                                    @if (checkAdminRoll())
                                                                        @if ($application->course_id == 11 || $application->course_id == 26 || $application->course_id == 27)
                                                                            <!-- <a href="{{ url('aiot-upload', ['application_id' => $application->id]) }}" class="ad-st-view">AIOT Upload</a> -->
                                                                        @endif
                                                                        @if (Auth::user()->course_type == 0)
                                                                            @if ($application->course_allowted_for_update_docs)
                                                                                <a href="{{ url('additional-education-qualification', ['application_id' => $application->id]) }}"
                                                                                    class="ad-st-view">Upload Document</a>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </td>
                            
                                                                @if ($application->payment)
                                                                    @if ($application->payment_details())
                                                                        <th style="color:green;">
                                                                            <span
                                                                                class="label label-success">{{ strtoupper($application->payment_status_text) }}</span>
                                                                            <a target="_blank"
                                                                                href="{{ url('pay-success') }}?id={{ $application->id }}"
                                                                                class="btn-sm btn-success" style="color: #fff;">Print Payment Slip</a>
                                                                        </th>
                                                                    @else
                                                                        <th style="color:red;">
                                                                            <span
                                                                                class="label label-success">{{ strtoupper($application->status) }}</span>
                                                                            <a href="{{ url('pay-success') }}?id={{ $application->id }}"
                                                                                class="btn-sm btn-danger" style="color: #fff;">Payment Slip</a>
                                                                            @if ($application->course->visible_in_application == 1)
                                                                                <a target="_blank"
                                                                                    href="{{ route('pay-now', ['id' => $application->id]) }}"
                                                                                    class="btn-sm btn-primary" style="color: #fff;">Pay Now</a>
                                                                            @endif
                                                                        </th>
                                                                    @endif
                                                                @else
                                                                    @if ($application->course->visible_in_application == 1)
                                                                        <td>{{ $application->payment_status_text }}<br /><a target="_blank"
                                                                                href="{{ route('pay-now', ['id' => $application->id]) }}"
                                                                                class="btn-sm btn-primary" style="color: #fff;">Pay Now</a></td>
                                                                    @endif
                                                                @endif
                                                                <td>
                                                                    @if ($application->payment_details() == false && Auth::guard('admin')->check() == true)
                                                                        <a href="{{ route('delete-application', $application->id) }}"
                                                                            onClick="return confirm('Are you sure?');" class="ad-st-view">Delete</a>
                                                                    @endif
                                                                </td>
                            
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td colspan="6">
                                                                @if ($application->course_id != 94)
                                                                    <a class="btn-sm btn-success pull-right" style="color:#fff;"
                                                                        href="{{ url('user-application-form') }}">Apply for more courses</a>
                                                                @endif
                                                                <div class="clearfix"></div>
                                                                {{-- Auth::guard('admin')->check() --}}
                                                                @if (true)
                                                                    <br />
                                                                    <h4>Update Photo/Signature of the user</h4>
                            
                                                                    <form method="POST" action="{{ url('update-photo-signature') }}"
                                                                        enctype="multipart/form-data" autocomplete="off">
                                                                        @csrf
                                                                        <div class="row">
                                                                            <div class="col-md-6 form-group">
                                                                                <input type="hidden" name="user_id"
                                                                                    value="{{ $application->user_id }}">
                                                                                <label style="color: red;">Upload Photo</label>
                                                                                <div class="col-md-12 mb-12">
                                                                                    <input type="file" class="form-control" name="upload_photo"
                                                                                        accept="image/*">
                                                                                    <div class="invalid-feedback text-danger upload_photo_application">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 form-group">
                                                                                <label style="color: red;">Upload Signature</label>
                                                                                <input type="file" class="form-control" name="upload_signature"
                                                                                    accept="image/*">
                                                                                <div class="invalid-feedback text-danger upload_signature_application">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 mt-1 form-group text-center">
                                                                                <button type="submit" class="btn btn-warning btn-block">Submit
                                                                                </button>
                                                                            </div>
                                                                            <div class="col-md-4 form-group text">
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                    <!-- </td>
                                      <td colspan="1"> -->
                                                                @endif
                                                            </td>
                                                        </tr>
                            
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                        <b> You have not applied any Course!!!!<br>Please Apply First<br></b><br />
                                        @if ($course_type == 0)
                                            <a href="{{url('user-application-form')}}"> <span class="blink_me">👉</span><b>Click Here To Apply</b></a><br>
                                        @else
                                            <a href="{{url('application-form-phd')}}"> <span class="blink_me">👉</span><b>Click Here To Apply Ph.D. Application
                                                    Form
                                                </b></a>
                                        @endif
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ User Content -->
                    </div>
                </section>
            </div>
        </div>
    </div>
    
           
          

        </div>
    </div>
</div>


{{-- </body> --}}
@endsection

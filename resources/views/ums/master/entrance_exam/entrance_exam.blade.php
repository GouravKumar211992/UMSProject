<<<<<<< HEAD
 @extends('ums.admin.admin-meta')
=======
@extends('ums.admin.admin-meta')
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

<!-- BEGIN: Body-->
 @section('content')

     
 

{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}

   
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        @include('ums.admin.notifications')
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Entrance Exam Schedule</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>  
                                    <li class="breadcrumb-item active">List of Entrance Exam Schedule</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                        {{-- <button class="btn btn-warning box-shadow-2 btn-sm  mb-sm-0 mb-50"><i data-feather="refresh-cw"></i>Reset</button> --}}
                        <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="{{url('Entrance_exam_add')}}"><i data-feather="file-text"></i> Add Entrance Exam Schedule</a> 

							<button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
                            <!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                             
                    </div>
                </div>
            </div>
            <div class="content-body">
                 
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                            <thead>
                                                <tr>
                                                    <th>ID#</th>
                                                    <th>Campus/Collage</th>
                                                    <th>Course(Session)</th>
                                                    <th>Examination Date</th>
                                                    <th>Reporting Timing</th>
                                                    <th>Examination Start-End Time</th>
                                                    <th>Center Name</th>
                                                    
                                                    <th>Roll Number Total (Generated)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        
                                               
                                                   @if(count($entranceExamData) > 0)
                                                     @foreach($entranceExamData as $index=>$data)
                                                     @php
                                                     $total_applications = $data->total_applications();
                                                     $generated_roll_number_applications = $data->generated_roll_number_applications();
                                                     @endphp
                                                     <tbody>
						                             	<tr>  
							                             	<td>{{++$index}}</td>
							                             	<td class="text-left">{{$data->campus->name}}</td>
                                                             <td style="width: 150px;">{{isset($data->course) ? $data->course->name :''}}<br>({{$data->session}})</td>
                                                             <td>{{isset($data->entrance_exam_date) ? $data->entrance_exam_date :''}}</td>
                                                             <td>{{date('h:i A', strtotime($data->reporting_time))}}</td>
                                                             <td>{{date('h:i A', strtotime($data->examination_time))}}-{{date('h:i A', strtotime($data->end_time))}}</td>
                                                             <td class="text-left">{{isset($data->centerName) ? $data->centerName->center_name :''}}</td>
                                                             <td>{{$total_applications}}({{$generated_roll_number_applications}})</td>
                                                             <td class="tableactionnew">  
                                                                 <div class="dropdown">
                                                                     <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                         <i data-feather="more-vertical"></i>
                                                                     </button>
                                                                     <div class="dropdown-menu dropdown-menu-end">
                                                                          
                                                                      <a class="dropdown-item"  onclick="return confirm_delete()" href="delete-entrance-exam/{{$data->id}}">
                                                                             <i data-feather="trash-2" class="me-50"></i>
                                                                             <span>Delete</span>
                                                                         </a>
                                                                     </div>
                                                                 </div> 
                                                             </td>
						                             	</tr>
                                                         
						
                                                     @endforeach
                                                     @else
                                                         <tr>
                                                             <td colspan="6" class="text-center">NO DATA FOUND</td>
                                                         </tr>
                                                     @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
								
                            </div>
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
    
      
    
    @include('ums.admin.search-model', ['searchTitle' => 'Campus List Search'])

    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
		<div class="modal-dialog sidebar-sm">
			<form class="add-new-record modal-content pt-0" id="approveds-form" method="GET" action="{{route('get-entrance-exam')}}"> 
				<div class="modal-header mb-1">
					<h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Name</label>
						  <input type="text" name="name"  value="{{Request::get('name')}}" class="form-control " placeholder="search here" />
					</div>
					
					<div class="mb-1">
						<label class="form-label">campus</label>
						<select class="form-select select2" id="campus" name="campus">
							<option value="">Select</option>
                                    @foreach($campuselist as $campus)
                                    
                                    <option value="{{$campus->id}}" {{ (Request::get('campus') == $campus->id) ? 'selected':'' }}>{{$campus->name}}</option>
                                    @endforeach
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Program Type</label>
						<select class="form-select select2" id="courseType" name="category_id">
                            <option value="">Select</option>
                            @foreach($categories as $category)
                            
                            <option value="{{$category->id}}" {{ (Request::get('category_id') == $category->id) ? 'selected':'' }}>{{$category->name}}</option>
                            @endforeach
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Name of Programme</label>
						<select class="form-select select2"  id="course_id" name="course_id">
                            <option value="">Select</option>
                            @foreach($courses as $course)
                            
                            <option value="{{$course->id}}" {{ (Request::get('course_id') == $course->id) ? 'selected':'' }}>{{$course->name}}</option>
                            @endforeach
						</select>
					</div> 
                    
                   
					 
				</div>
				<div class="modal-footer justify-content-start">
					<button type="submit" class="btn btn-primary data-submit mr-1">Apply</button>
					<button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
  @endsection
  <script>

    function confirm_delete()
  {
    var v=confirm('Do you really want to delete this entrance exam schedule');
    if(v==true)
     return true;
    else
     return false;
    
  }
   function exportdata() {
        var fullUrl_count = "{{count(explode('?',urldecode(url()->full())))}}";
        var fullUrl = "{{url()->full()}}";
        if(fullUrl_count>1){
            fullUrl = fullUrl.split('?')[1];
            fullUrl = fullUrl.replace(/&amp;/g, '&');
            fullUrl = '?'+fullUrl;
       }else{
           fullUrl = '';
       }
       var url = "{{url('admin/master/entranceexamschedule/entranceexamschedule-export')}}"+fullUrl;
       window.location.href = url;
   }
   function editFee(slug) {
       var url = "{{url('admin/master/fee/edit-fee')}}"+"/"+slug;
       window.location.href = url;
   }
   function deleteFee(slug) {
       var url = "{{url('delete-entrance-exam')}}"+"/"+slug;
       window.location.href = url;
   }
</script>
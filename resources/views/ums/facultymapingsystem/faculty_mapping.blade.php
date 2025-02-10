@extends("ums.admin.admin-meta")
@section("content")


 <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
              <div class="content-header-left col-md-6  mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Application Form</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item">faculty_mapping</a>
                                </li>

                            </ol>
                        </div>
                    </div>
                </div>
            </div>
                <div class="content-header-right text-sm-end col-md mb-50 mb-sm-0 p-2">
                    <div class="form-group breadcrumb-right">
                        {{-- <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50"><i data-feather="refresh-cw"></i>Reset</button> --}}
                       
                            <button for="" class="btn btn-primary btn-sm" data-bs-target="#bulkUploadModal" data-bs-toggle="modal">
                            Bulk Upload
                            </button>
                            
                                   
                          
                <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50"><i data-feather="refresh-cw"></i>Reset</button>
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
						<a class="btn btn-primary btn-sm mb-50 mb-sm-0" href="internal_mapping_add"><i data-feather="plus-circle"></i>Add internal mark mapping</a> 
                    </div>
                  </div>
                </div>
            <div class="content-body">
                 
                
				
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
									<table class="datatables-basic table myrequesttablecbox loanapplicationlist "> 
                                        <thead>
                                             <tr>
												<th>Sr.No</th>
												<th>Campus <br> Name</th>
												<th>Program <br> Name</th>
												<th>Course Name</th>
												<th>Semester Name</th>
												<th>Session</th>
												<th>subject Code</th>
												<th>Permission</th>
												<th>Faculty Name</th>
												<th>Action</th>
											  </tr>
											</thead>
                      @foreach($internals as $key => $internal)
											<tbody>
										<td>#{{$internal->id}}</td>
										<td>{{$internal->Course->campuse ? $internal->Course->campuse->name : 'N/A'}}</td>
										<td>{{$internal->Category ? $internal->Category->name : 'N/A'}}</td>
										<td>{{$internal->Course ? $internal->Course->name : 'N/A'}}</td>
										<td>{{$internal->Semester ? $internal->Semester->name : 'N/A'}}</td>
										<td>{{$internal->session ?? 'N/A'}}</td>
										<td>{{$internal->sub_code ?? 'N/A'}}</td>
										<td>{{$internal->getPermissionAttribute() ?? 'N/A'}}</td>
										<td>{{$internal->faculty ? $internal->faculty->name : 'N/A'}}</td>
                    <td class="tableactionnew">  
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0 " data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="{{url('internal_mapping_edit',$internal->id) }}" >
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a> 
                                                             <a class="dropdown-item" href="{{url('internal_mapping/delete', $internal->id )}}">
                                                                    <i data-feather="trash-2" class="me-50"></i>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div> 
                                                    </td>
									</tr>
											   </tbody>

@endforeach

									</table>
								</div>
								
								
								
								
								
                            </div>
                        </div>
                    </div>
                     
                </section>
                 

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    @include('ums.admin.notifications')
    <div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header bg-primary ">
                  <h5 class="modal-title text-white" id="bulkUploadModalLabel">Bulk File Upload</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form action="/your-upload-endpoint" method="POST" enctype="multipart/form-data">
                      <div class="mb-3">
                          <label for="fileInput" class="form-label">Choose a file to upload</label>
                          <input class="form-control" type="file" id="fileInput" name="file[]" accept=".csv, .xlsx, .xls" required>
                      </div>
                      <div class="mb-3">
                          <label for="description" class="form-label">Click Here To Download Format Of Excel File</label>
                          <a href="#" class="btn btn-primary">Download</a>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Upload</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
	 
  @include('ums.admin.search-model', ['searchTitle' => 'faculty mapping search'])
  <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
		<div class="modal-dialog sidebar-sm">
			<form class="add-new-record modal-content pt-0" id="approveds-form" method="GET"  action="{{ url('category_list') }}" > 
				<div class="modal-header mb-1">
					<h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Campus</label>
                          <select class="form-control selectpicker campuse_id" id="campuse_id" name="campuse_id">
                                    <option value="">Select Campuse</option>
                                    @foreach($campuseName as $campuse)
                                    
                                    <option value="{{$campuse->id}}" >{{$campuse->name}}</option>
                                    @endforeach
                                </select>					</div>
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Program</label>
                          <select class="form-control selectpicker" id="program" name="program">
                                    <option value="">Select Program</option>
                                    @foreach($programs as $program) 
                                    <option value="{{$program->id}}" >{{$program->name}}</option>
                                    @endforeach
                                </select>					</div>
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Course</label>
                          <select class="form-control selectpicker-back" id="course" name="course">
		                            <option value="">Select Course</option>
                                    @foreach($courses as $course)

                                    <option value="{{$course->id}}" >{{$course->name}}</option>
                                    @endforeach
		                        </select>					</div>
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Semester</label>
                          <select class="form-control selectpicker-back" id="semester" name="semester">
		                            <option value="">Select Semester</option>
                                    @foreach($semesters as $semester)

                                    <option value="{{$semester->id}}" >{{$semester->name}}</option>
                                    @endforeach
		                                   
		                        </select>					</div>
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Subject Name</label>
                          <select class="form-control selectpicker-back" id="subject" name="subject">
		                                <option value="">Select Subject</option>
		                                    
		                            </select>					</div>
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Faculty Name</label>
                          <select class="form-control selectpicker faculty" id="faculty" name="faculty">
                                    <option value="">Select Faculty</option>
                                    @foreach($facultys as $faculty)
                                    
                                    <option value="{{$faculty->id}}" >{{$faculty->name}}</option>
                                    @endforeach
                                </select>					</div>
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Session</label>
                          <select class="form-control selectpicker" id="session" name="session">
                                    <option value="">Select Session</option>
                                    @foreach($sessions as $session)
                                    
                                    <option value="{{$session->academic_session}}" >{{$session->academic_session}}</option>
                                    @endforeach
                                </select>					</div>
					
				
                    
                     
					 
				</div>
				<div class="modal-footer justify-content-start">
					<button type="submit" form="approveds-form" class="btn btn-primary data-submit mr-1">Apply</button>
					<button type="reset"  class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>


</div>

@endsection
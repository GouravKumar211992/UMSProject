@extends('ums.admin.admin-meta')

@section('content')
    
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}


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
                            <h2 class="content-header-title float-start mb-0">Master</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard">Home</a></li>  
                                    <li class="breadcrumb-item active">Stream List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                      <a href="{{ route('add_stream') }}" class="btn  btn-dark  btn-sm mb-50 mb-sm-0" ><i data-feather="plus-circle"></i> Add Stream</a> 
                        <button class="btn  btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button> 
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" onclick="window.location.reload();" ><i data-feather="refresh-cw"></i>
                            Reset</button> 

						 
                    </div>
                </div>
            </div>
            <div class="content-body">
                 
                
				
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
									<table class="datatables-basic table myrequesttablecbox tableistlastcolumnfixed newerptabledesignlisthome"> 
                                        <thead>
                                             <tr>
												<th>#ID</th>
												<th>	Stream</th>
												<th>Stream Code</th>
												<th>Category</th>
												<th>Course</th>
												<th>Campus</th>
												<th>Created on</th>
												<th>Action</th>
											  </tr>
											</thead>
											<tbody>
												@foreach ($all_stream as $item)
													
												
                                           
												  <tr>
													<td>#5</td>
													<td class="fw-bolder text-dark">{{$item->name}}</td>
													<td>{{$item->stream_code}}</td>
													<td>{{isset($item->category->name) ? $item->category->name : ''}}</td>
                                                     <td>{{isset($item->course->name) ? $item->course->name :''}}</td>
                                                     <td>{{isset($item->course->campuse) ? $item->course->campuse->name :''}}</td>
                                                     <td>{{$item->created_at}}</td>
													<td class="tableactionnew">
														<div class="dropdown">
															<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
																<i data-feather="more-vertical"></i>
															</button>
															<div class="dropdown-menu dropdown-menu-end">
																<button class="dropdown-item"   onclick="editStream('{{$item->id}}')">
																	<i data-feather="edit-3" class="me-50"></i>
																	<span>Edit</span>
																</button>
                                                               
																<button class="dropdown-item" href="#" onclick="if (window.confirm('Are you sure you want to delete this data?')) { deleteStream('{{$item->id}}'); }">
																	<i data-feather="trash-2" class="me-50"></i>
                                                              <span >Delete</span>                                                                </button>
                                                                </button> 
															</div>
														</div>
													</td>
												  </tr>
												  @endforeach
												 
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
    <!-- END: Content-->

	
    @include('ums.admin.search-model', ['searchTitle' => 'Campus List Search'])

    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
		<div class="modal-dialog sidebar-sm">
			<form class="add-new-record modal-content pt-0" method="GET" action="{{ url('stream_list') }}"> 
				<div class="modal-header mb-1">
					<h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Name</label>
<!--                        <input type="text" id="fp-default" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />-->
						  <input type="text" name="name" id="fp-range" class="form-control bg-white" value="{{Request::get('name')}}" placeholder="search here" />
					</div>
					
					<div class="mb-1">
						<label class="form-label">Campus</label>
						<select class="form-select" id="campus" name="campus">
							<option value="">Select</option>
                                    @foreach($campuselist as $campus)
                                    
                                    <option value="{{$campus->id}}" {{ (Request::get('campus') == $campus->id) ? 'selected':'' }}>{{$campus->name}}</option>
                                    @endforeach
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Programme Type</label>
						<select class="form-select select2" id="courseType" name="category_id">
                            <option value="">Select</option>
                            @foreach($categories as $category)
                            
                            <option value="{{$category->id}}" {{ (Request::get('category_id') == $category->id) ? 'selected':'' }}>{{$category->name}}</option>
                            @endforeach
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Name of Programme</label>
						<select class="form-select" id="course_id" name="course_id">
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

{{-- </body> --}}
@endsection
<script>
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
        var url = "{{url('admin/master/stream/stream-export')}}"+fullUrl;
        window.location.href = url;
    }
	function editStream(slug) {
		var url = "{{url('stream_list_edit')}}"+"/"+slug;
        // alert(url);
		window.location.href = url;
	}
    function deleteStream(slug) {
        var url = "{{url('stream_list_delete')}}"+"/"+slug;
        window.location.href = url;
    }
</script>
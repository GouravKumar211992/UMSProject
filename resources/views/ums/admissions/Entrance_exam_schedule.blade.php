@extends("ums.admin.admin-meta")
@section("content")

<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
             <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Entrance Exam Schedule</h2>
                            <div class="breadcrumb-wrapper">
                                
                            </div>
                        </div>
                    </div>
                </div> 
				 <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                    <a href="add-request.html" class="btn btn-primary  btn-sm mb-50 mb-sm-0"><i data-feather="plus-square" ></i> Add Entrance Exam Schedule</a>   

							<button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button>  
                            <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" onclick="window.location.reload();"><i data-feather="refresh-cw"></i> Reset</button>

                            
                    </div>
                </div>
            </div>
            <div class="content-body dasboardnewbody">
                 
                <!-- ChartJS section start -->
                <section id="chartjs-chart">
                    <div class="row">
						
						  
						
						<div class="col-md-12 col-12">
                            <div class="card  new-cardbox"> 
								 <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Campuse/College</th>
                                                <th>Course (Session)</th>
                                                <th>Examination (Date)</th>
                                                <th>Reporting (Timing)</th>
                                                <th>Examination Start-End Time</th>
                                                <th>Center Name</th>
                                                <th>Roll Number Total (Generated)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
                                <td>
                                    @if($total_applications > $generated_roll_number_applications)
                                    <a href="{{url('generate-entrance-roll-number')}}?academic_session={{$data->session}}&course_id={{$data->course_id}}"  onclick="return confirm('Are you sure?');" target="_blank" class="btn-md btn-dark"> Generate Roll Number For Entrance</a>
                                    @endif
                                	<a href="{{url('admin/master/entrance/delete-model-trim',[$data->id])}}"  onclick="return confirm('Are you sure?');" class="btn-md btn-add"> Delete</a>
                                </td>
							</tr>
						</tbody>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">NO DATA FOUND</td>
                            </tr>
                        @endif
                                    </table>
                                </div>
								
								  
                            
                                
								 
								 
                                
                          </div>
                        </div>
						
						
						 
                         
                         
                    </div>
					
					 
                     
                </section>
                <!-- ChartJS section end -->

            </div>
        </div>
    </div>
 

@endsection
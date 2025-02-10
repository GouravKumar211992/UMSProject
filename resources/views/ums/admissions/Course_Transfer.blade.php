@extends("ums.admin.admin-meta")
@section("content")


    <div class="app-content content ">
        <div class="content-overlay"></div>
        {{-- @include('ums.admin.notifications') --}}
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
             <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Bulk Course Transfer</h2>
                            <div class="breadcrumb-wrapper">
                                
                            </div>
                        </div>
                    </div>
                </div> 
				
            
                  
                
            </div>
            <div class="content-body dasboardnewbody">
                <form method="POST" action=""  enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="margin-top:-30px">
                        <div class="content-header-right text-sm-end col-md-12 mb-50 mb-sm-0">
                            <div class="form-group breadcrumb-right">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i data-feather="check-circle"></i> Add file
                                </button>
                                {{-- <span class="text-danger">{{ $errors->first('subject') }}</span> --}}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-1" style="margin-top: 30px">
                        
                        
                        <div class="col-md-2 ms-4">
                            <label class="form-label">Upload File Here<span class="text-danger m-0">*</span></label>
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="course_switching_file"class="form-control" required  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            {{-- <span class="text-danger">{{ $errors->first('semester') }}</span> --}}
                        </div>
                    </div>
                    <div class="col-md-2 ms-4 mb-2"><a class="btn btn-info" target="_blank" href="{{asset('bulk-uploading/CourseSwitchingDataFormat.xlsx')}}">Download</a></div>
                    
                </form>
                <!-- ChartJS section start -->
                <section id="chartjs-chart">
                    <div class="row">
						
						  
						
						<div class="col-md-12 col-12">
                            <div class="card  new-cardbox"> 
								 <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                        <thead>
                                            <tr>
                                                <th>SN#</th>
                                                <th>Name</th>
                                                <th>Roll NO.</th>
                                                <th>Old Course ID </th>
                                                <th>New Course ID</th>
                                                <th>STatus</th>
                                                <th>IP Address</th>
                                                <th> Date </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($students as $index=>$student)
				<tr @if($student->status==0) style="background: pink;" @endif>
					<td>{{++$index}}</td>
					<td>{{$student->name}}</td>
					<td>{{$student->roll_no}}</td>
					<td>{{$student->course_old->name}}</td>
					<td>{{$student->course_new->name}}</td>
					<td>{{($student->status==1)?'DONE':'NOT'}}</td>
					<td>{{$student->ip_address}}</td>
					<td>{{date('d-m-Y',strtotime($student->created_at))}}</td>
				</tr>
				@endforeach
                                            

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
    </div>


@endsection
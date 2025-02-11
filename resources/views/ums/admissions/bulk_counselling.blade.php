@extends("ums.admin.admin-meta")
@section("content")


    <div class="app-content content ">
        <div class="content-overlay"></div>
        @include('ums.admin.notifications')
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
             <div class="content-header row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Bulk Councelling</h2>
                            <div class="breadcrumb-wrapper">
                                
                            </div>
                        </div>
                    </div>
                </div> 
				
            
                  
                
            </div>
            <div class="content-body dasboardnewbody">
                <div class="row">
                    <form method="POST" action="" enctype="multipart/form-data">
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
                                <input type="file" name="councelling_file" class="form-control" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                {{-- <span class="text-danger">{{ $errors->first('semester') }}</span> --}}
                            </div>
                        </div>
                        <div class="col-md-2 ms-4 mb-2"><a class="btn btn-info" target="_blank" href="{{asset('bulk-uploading/CouncellingDataFormat.xlsx')}}">Download</a></div>
                        
                    </form> 
                    </div>


                <!-- ChartJS section start -->
                <section id="chartjs-chart">
                    <div class="row">
						
						  
						
						<div class="col-md-12 col-12">
                            <div class="card  new-cardbox"> 
								 <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox loanapplicationlist" id="dataTableStyle">
                                        <thead>
                                        <tr>
					<th>SN#</th>
					<th>Name</th>
					<th>Father Name</th>
					<th>Email</th>
					<th>DOB</th>
					<th>Gender</th>
					<th>Mobile</th>
					<th>Course Name</th>
					<th>Course ID From Admin (Course Master)</th>
					<th>Subject</th>
					<th>Accademic Session</th>
					<th>Status</th>
				</tr>
                                        </thead>
                                        <tbody>
                                        @foreach($applications as $index=>$application)
				<tr @if($application->status==0) style="background: pink;" @endif>
					<td>{{++$index}}</td>
					<td>{{$application->name}}</td>
					<td>{{$application->father_name}}</td>
					<td>{{$application->email}}</td>
					<td>{{$application->dob}}</td>
					<td>{{$application->gender}}</td>
					<td>{{$application->mobile}}</td>
					<td>{{$application->course_name}}</td>
					<td>{{$application->course_id}}</td>
					<td>{{$application->subject}}</td>
					<td>{{$application->accademic_session}}</td>
					<td>{{($application->status==1)?'DONE':'NOT'}}</td>
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    {{-- <script>
    $('#dataTableStyle').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    </script> --}}

@endsection
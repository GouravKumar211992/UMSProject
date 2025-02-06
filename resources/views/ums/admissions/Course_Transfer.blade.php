@extends("ums.admin.admin-meta")
@section("content")


    <div class="app-content content ">
        <div class="content-overlay"></div>
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
				 <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
                        <td><a href="#"><img src="resume.png" /></a>Click here to download!</td>                           
                        <div class="upload-btn">
                            <label for="course_switching_file" class="btn btn-primary btn-sm">
                            Upload File
                            </label>
                            <input type="file" name="course_switching_file" id="course_switching_file" 
                                   class="custom-file-input" 
                                   accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                          </div>
                            
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
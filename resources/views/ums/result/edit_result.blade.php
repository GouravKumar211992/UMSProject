@extends('ums.admin.admin-meta')
  
@section('content')
    

<!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
             <div class="content-header row">
                <div class="content-header-left col-md-4 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Edit Result</h2>
                            <div class="breadcrumb-wrapper">
                                
                            </div>
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
                                                <th class="text-left">ID#</th>
                                                <th class="text-left">Enrollment No.</th>
                                                <th class="text-left">Roll No.</th>
                                                <th class="text-left">Name</th>
                                                <th class="text-left">Exam Type</th>
                                                <th class="text-left">Session</th>
                                                <th class="text-left">Course</th>
                                                <th class="text-left">Sem</th>
                                                <th class="text-left">Sub_code</th>
                                                <th class="text-left">Oral</th>
                                                <th class="text-left">IA / Internal Obtained Marks</th>
                                                <th class="text-left">External Obtained Marks</th>
                                                <th class="text-left">Practical Obtained Marks</th>
                                                <th class="text-left">Total Obtained Marks</th>
                                                <th class="text-left">Internal Required Marks</th>
                                                <th class="text-left">External Required Marks</th> 
                                                <th class="text-left">Total Required Marks</th>
                                                <th class="text-left">Credit</th>
                                                <th class="text-left">Grade Letter</th>
                                                <th class="text-left">Grade Point</th>
                                                <th class="text-left">QP</th>
                                                <th class="text-left">SGPA</th>
                                                <th class="text-left">CGPA</th>
                                                <th class="text-left">Result</th>
                                                <th class="text-left">Year Back</th>
                                                <th class="text-left">Result Type</th>
                                                <th class="text-left">Result Status</th>
                                                <th class="text-left">External Marks Cancell</th>
                                                <th class="text-left">Current Internal Marks (Only for BACK)</th>
                                                <th class="text-left">Current External Marks (Only for BACK)</th>
                                                <th class="text-left">Change Type</th>
                                                <th class="text-left">Comments</th>
                                                <th class="text-left">Action</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @foreach($results as $index=>$result)
                                              <tr>  
                                                  <td>{{++$index}}</td>
                                                  <td>{{$result->enrollment_no}}</td>
                                                  <td>{{$result->roll_no}}</td>
                                                  <td>{{($result->student)?$result->student->full_name:'-'}}</td>
                                                  <td>{{$result->back_status_text}}</td>
                                                  <td>{{$result->exam_session}}</td>
                                                  <td>{{($result->semester_details && $result->semester_details->course)?$result->semester_details->course->name:'-'}}</td>
                                                  <td>{{($result->semester_details)?$result->semester_details->name:'-'}}</td>
                                                  <td>{{($result->subject_code)?$result->subject_code:'-'}}</td>
                                                  
                                                  <td>
                                                      <input class="col-sm-12 result_values" type="text" name="oral" value="{{$result->oral}}">
                                                      <input type="hidden" class="result_values" name="result_id" value="{{$result->id}}">
                                                  </td>
                                                  @if($result->internal_marks==49)
                                                  <td><input class="col-sm-12 result_values" type="text" name="internal_marks" value="{{$result->internal_marks}}"></td>
                                                  @else
                                                  <td><input class="col-sm-12 result_values" type="text" name="internal_marks" value="{{((int)$result->internal_marks - (int)$result->oral)}}"></td>
                                                  @endif
                                                  <td><input class="col-sm-12 result_values" type="text" name="external_marks" value="{{$result->external_marks}}"></td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="practical_marks" value="{{$result->practical_marks}}"></td>
                                                  <td>{{$result->total_marks}}</td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="max_internal_marks" value="{{$result->max_internal_marks}}"></td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="max_external_marks" value="{{$result->max_external_marks}}"></td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="max_total_marks" value="{{$result->max_total_marks}}"></td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="credit" value="{{$result->credit}}"></td>
                                                  <td>
                                                      <select class="col-sm-12 result_values" name="grade_letter">
                                                          <option value="">-Select-</option>
                                                          @foreach($grades as $grade)
                                                          <option value="{{$grade->grade_letter}}" @if($grade->grade_letter==$result->grade_letter)selected @endif>{{$grade->grade_letter}}</option>
                                                          @endforeach
                                                      </select>
                                                  </td>
                                                  <td>
                                                      <select class="col-sm-12 result_values" name="grade_point">
                                                          <option value="">-Select-</option>
                                                          @foreach($grades as $grade)
                                                          <option value="{{$grade->grade_point}}" @if($grade->grade_point==$result->grade_point)selected @endif>{{$grade->grade_point}}</option>
                                                          @endforeach
                                                      </select>
                                                  </td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="qp" value="{{$result->qp}}"></td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="sgpa" value="{{$result->sgpa}}"></td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="cgpa" value="{{$result->cgpa}}"></td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="result" value="{{$result->result}}"></td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="year_back" value="{{$result->year_back}}"></td>
                                                  <td>{{$result->result_type}}</td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="status" value="{{$result->status}}"></td>
                                                  <td>{{($result->external_marks_cancelled)?$result->external_marks_cancelled:'NULL'}}</td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="current_internal_marks" value="{{$result->current_internal_marks}}"></td>
                                                  <td><input class="col-sm-12 result_values" type="text" name="current_external_marks" value="{{$result->current_external_marks}}"></td>
                                                  <td>
                                                      <select class="col-sm-12 result_values" name="scrutiny">
                                                          <option value="0" @if($result->scrutiny==0)selected @endif>Regular</option>
                                                          <option value="1" @if($result->scrutiny==1)selected @endif>Scrutiny</option>
                                                          <option value="2" @if($result->scrutiny==2)selected @endif>Challenge</option>
                                                          <option value="3" @if($result->scrutiny==3)selected @endif>Supplementary</option>
                                                      </select>
                                                  </td>
                                                  <td><textarea class="col-sm-12 result_values" style="width:200px;" rows="3" name="comment">{{$result->comment}}</textarea></td>
                                                  <td>
                                                      {{-- @if(Auth::guard('admin')->user()->role==1)
                                                          <button type="button" class="btn-sm btn-success" onclick="save_result($(this))">Save</button>
                                                          <a onClick="return confirm('Are you sure?');" href="{{url('admin/delete-single-result')}}/{{$result->id}}" aria-hidden="true" class="btn-sm btn-danger">Delete</a>
                                                      @endif --}}
                                                  </td>
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
    <!-- END: Content-->

    @endsection
 

@extends('student.layouts.app1')
@section('content')
<section class="content mb-3">
    <div class="container">
        <div class="mt-3 mb-3">
            <div class="dashbed-border-bottom"></div>
        </div>
       

        <div class="row mb-3">
            <div class="col-sm-8">
                <h4>Result List</h4>
            </div>
		</div>


        <div class="row">
            <section class="col-lg-12 connectedSortable"> 
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered admintable border-0" cellspacing="0" cellpadding="0" id="myTable">
                                <thead>
									<tr> 
										<th class="text-left">ID#</th>
										<th class="text-left">Enrollment No.</th>
										<th class="text-left">Roll No.</th>
										<th class="text-left">Course</th>
										<th class="text-left">Semester</th>
										<th class="text-left">Student Name</th>
										<th class="text-left">Mobile Number</th>
										<th class="text-left">Result Type</th>
										<th class="text-left">Result Status</th>
										<th class="text-left">Action</th> 
									</tr>
								</thead>
                  <tbody>
                    @foreach($results as $index=>$result)
                    <tr>
                        <td>{{++$index}}</td>
                        <td>{{$result->enrollment_no}}</td>
                        <td>{{$result->roll_no}}</td>
                        <td>{{$result->course_name()}}</td>
                        <td>{{($result->semester_details)?$result->semester_details->name:'-'}}</td>
                        <td>{{($result->student)?$result->student->full_name:'-'}}</td>
                        <td>{{($result->student)?$result->student->mobile:'-'}}</td>
                        <td>{{$result->back_status_text}}</td>
                        <td>{{$result->status_text}}</td>
                        <td>
                        @if($result->result_type == 'new')
                            @if($result->status == 2)
                                <a target="_blank" href="{{url('admin/view-results/?id='.$result->semester_details->id)}}&roll_number={{base64_encode($result->roll_no)}}&student=true" class="btn-sm btn-success">View</a>
                                @elseif($result->status == 1)
                                <span style="color:red;">Not Approved By Admin. Please Contact To Admin</span>
                            @endif
                        @elseif($result->result_type == 'old' && $result->semester_final == 1)
                            @if($result->status == 2)
                            <a target="_blank" href="{{url('admin/view-results/?id='.$result->semester_details->id)}}&roll_number={{base64_encode($result->roll_no)}}&student=true" class="btn-sm btn-success">View</a>
                            @elseif($result->status == 1)
                            <span style="color:red;">Not Approved By Admin. Please Contact To Admin</span>
                            @endif
                        @else
                        <span style="color:red;">Result Generated but can't Display</span>
                        @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                        </table>
                        </div>
<br/>
            </section>
        </div>
    </div>
</section>

@endsection




@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <style type="text/css">
        
    </style>
@endsection

@section('scripts')
<script src="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"></script>
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
   $(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>
@endsection

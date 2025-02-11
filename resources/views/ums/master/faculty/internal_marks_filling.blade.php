@extends('ums.master.faculty.faculty-meta')
@section('content')
{{-- Web site Title --}} 
@section('title') Internal Marks :: @parent @stop 
@section('content')
<section class="content-body">
  
        <div class="mt-3 mb-3">
            <div class="dashbed-border-bottom"></div>
        </div>
		<div class="row mb-3">
            <div class="col-sm-12">
                <h4>Internal Marks</h4>
                <p>Showing Internal Marks</p>
				{{-- @include('faculty.partials.notifications') --}}
				<a href="{{url('internal-marks')}}"  class="btn-md btn-add"><i class="iconly-boldPlus"></i> Add Internal Mark</a>
				<a href="{{url('internal-marks-show')}}"  class="btn-md btn-success"><i class="iconly-boldPlus"></i> Show Internal Mark</a>
              {{--<a href="#" data-toggle="modal" data-target="#filter" class="btn-md btn-warning"><i class="iconly-boldFilter-2"></i> Filter</a>
                <a href="#" data-toggle="modal" data-target="#search" class="btn-md btn-dark"><i class="iconly-boldSearch"></i> Search</a>

                <a href="{{url('faculty/internal-marks-list')}}" class="btn btn-secondary"><i class="fa fa-undo"></i> Reset</a>
                <a href="javascript:void(0);" class="text-dark" onclick="exportdata();"><img src="/assets/admin/img/export.svg" /> Export Internal Marks</a>
            </div>
		</div>
       {{--<div class="row">

            <section class="col-lg-12 connectedSortable"> 
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered admintable border-0" cellspacing="0" cellpadding="0">
                                <thead>
									<tr> 
										<th>Sr. No.</th>
										<th>Roll No</th>
										<th>Registration Number</th>
										<th>Student Name</th>
										<th>Mid Semester Marks</th>
										<th>Assignment Marks</th>
										<th>Total Marks</th>
										<th>Total Marks (Words)</th>
									</tr>
								</thead>

                                @foreach($internal_marks as $key=> $internal_mark)
                                <tbody>
									<tr>  
										<td>{{$key+1}}</td>
										<td>{{$internal_mark->roll_number}}</td>
										<td>{{$internal_mark->enrollment_number}}</td>
										<td>{{$internal_mark->student_name}}</td>
										<td>{{$internal_mark->mid_semester_marks}}</td>
										<td>{{$internal_mark->assignment_marks}}</td>
										<td>{{$internal_mark->total_marks}}</td>
										<td>{{numberFormat($internal_mark->total_marks)}}</td>
                                        
									</tr>
								</tbody>
                                @endforeach
                            </table>
                        </div>

                        {{$internal_marks->links('partials.pagination')}}
            </section>
        </div> --}} 
    </div>
</section>
@endsection


@section('styles')
    <style type="text/css"></style>
@endsection

@section('scripts')
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
         var url = "{{route('internalMarksExport')}}"+fullUrl;
        window.location.href = url;
		
	}
	function editCat(slug) {
		var url = "{{url('admin/user/edit-user')}}"+"/"+slug;
		window.location.href = url;
	}
	function deleteCat(slug) {
        var url = "{{url('admin/user/delete-model-trim')}}"+"/"+slug;
        window.location.href = url;
    }
</script>
@endsection

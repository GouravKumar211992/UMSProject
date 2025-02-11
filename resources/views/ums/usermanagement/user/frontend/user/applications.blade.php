@extends('frontend.layouts.user')
@section('content')

<div class="udb">

    <div class="udb-sec udb-cour-stat">
        <h4><img src="images/icon/db3.png" alt="" /> Course Application Status</h4>
        
		@if(count($applications)>0)
        <div class="mt-4 pt-4">
            <div class="table-responsive table-desi">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Course Name</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>

                     @foreach ($applications as $key => $application)
                        <tr>
                            <td>{{$key +1 }}</td>
                            <td><a href="#"><span class="list-enq-name">{{$application->course->name}}</span></a></td>
                            <td>{{date('d-M-Y', strtotime($application->created_at))}}</td>
                            <td>
                                <span class="label label-success">{{$application->status}}</span>
                            </td>
                            <td><a href="{{route('view-application-form',['application_id'=>$application->id])}}" class="ad-st-view">View</a></td>
                        </tr>
                     @endforeach

                    </tbody>
                </table>
            </div>
        </div>
		@else
		<b>	You are Not Applied for Any course!!!!<br>Please Apply First<br></b>
			<a href="/application-form">Click Here To Apply</a>
		@endif

    </div>

</div>

@endsection
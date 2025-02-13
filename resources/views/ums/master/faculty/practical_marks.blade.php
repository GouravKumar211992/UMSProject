@extends('ums.master.faculty.faculty-meta')
@section('content')
{{-- Web site Title --}} 
@section('title') Practical Marks :: @parent @stop 
@section('content')
<section class="content mb-3">
    <div class="container-fluid">
        <div class="mt-3 mb-3">
            <div class="dashbed-border-bottom"></div>
        </div>
		<div class="row mb-3">
            <div class="col-sm-12">
                <h4>Practical Marks</h4>
                <p>Showing Practical Marks</p>
				{{-- @include('faculty.partials.notifications') --}}
				<a href="{{route('practical-marks')}}"  class="btn-md btn-add"><i class="iconly-boldPlus"></i> Add Practical Mark</a>
				<a href="{{route('practical-marks-show')}}"  class="btn-md btn-success"><i class="iconly-boldPlus"></i> Show Practical Mark</a>
            </div>
		</div>
    </div>
</section>
@endsection


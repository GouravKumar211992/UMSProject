@if($errors->any())
    <div class="alert alert-danger">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{$errors->first()}}
    </div>
@endif


@if (\Session::has('error'))
    <div class="alert alert-danger">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! \Session::get('error') !!}
    </div>
@endif

@if (\Session::has('success'))
    <div class="alert alert-success">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! \Session::get('success') !!}
    </div>
@endif

@if (\Session::has('message'))
    <div class="alert alert-success">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! \Session::get('message') !!}
    </div>
@endif<?php if(\Session::has('error_msg')): ?>    <div class="alert alert-danger">    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>        {!! \Session::get('error_msg') !!}    </div>@endif
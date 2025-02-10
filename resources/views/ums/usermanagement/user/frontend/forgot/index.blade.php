
@extends('frontend.layouts.app')
@section('content')

<style>
.education_btn{
	cursor:pointer;
}
</style>

	<!--SECTION START-->
    <section>
        <div class="container com-sp pad-bot-70 pg-inn pt-3">
            <div class="n-form-com admiss-form">
            
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mt-0 mb-3 mobileadmintxt">Forgot Password</h2>
						<hr />
                    </div>
                </div>
    @if($user)
			<form method="POST" action="{{ route('user-forgot-password-change') }}" id="myform_application">
			@csrf

				<div class="row">
                    <div class="col-md-12">
						@include('frontend.layouts.notifications')
                    </div>
				</div>
				<div class="row mb-5">
                    <div class="col-md-12">
						<h5 class="front-form-head"><i class="fa fa-angle-down"></i> Forgot Password</h5> 
                    </div>

					<div class="col-md-4 mb-2">
						<div class="form-group"> 
							<label>Password</label> 
							<input type="hidden" name="email" class="validate" value="{{$user->email}}">
							<input type="text" name="password" class="validate" value="{{old('password')}}">
							<div class="invalid-feedback text-danger annual_income_application">{{$errors->first('password')}}</div>
						</div> 
					</div>

					<div class="clearfix"></div> 
					<div class="col-md-4 mb-2">
						<div class="form-group"> 
							<label>Confirm Password</label> 
							<input type="text" name="password_confirmation" class="validate" value="{{old('password_confirmation')}}">
							<div class="invalid-feedback text-danger annual_income_application">{{ $errors->first('password_confirmation') }}</div>
						</div> 
					</div>
					<div class="clearfix"></div>

					<div class="col-md-4 mb-2">
						<button type="submit" class="btn btn-warning">
							<i class="fa fa-send" aria-hidden="true"></i> Submit
						</button>
					</div>
					
					
					
                    </div>
                </div>
    
                           
		</form>
	@else
		@if (\Session::has('success'))
                <div class="row">
                    <div class="col-md-12">
						<div class="alert alert-success">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							{!! \Session::get('success') !!}
						</div>
                    </div>
                </div>
		@else
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Token Has Expired
				</div>
			</div>
		</div>
		@endif
	@endif
   


           

            </div>
        </div>
    </section>
    <!--SECTION END-->


@endsection

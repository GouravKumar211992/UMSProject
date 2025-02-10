
@extends('frontend.layouts.app')
@section('content')

	<div class="col-sm-12 position-relative min-Loginheight">
        <div class="uplogo">
            <img src="{{asset('assets/admin/img/login-logo.png')}}" />
        </div>
	</div>
	
<div class="container com-sp pad-bot-70">
    <div class="row">
        <div class="col-md-12">
            <div class="login-box mt-2 min-Loginheight">
                <div class="row">
                    <div class="col-sm-12 col-md-7">
                        <div class="yogiimg">
                                <img src="{{asset('assets/admin/img/loginbg.png')}}"   /> 
                        </div>
                        </div>
                    <div class="col-sm-12 col-md-4 text-center">
                        <a href="{{url('admission-merit-list')}}" class="btn btn-block btn-success">Click here to see Admission Merit List</a>
                        <br/>
                        <a href="{{'https://dsmnru.ac.in/'}}" class="btn btn-block btn-warning">Click here to see notice board</a>
                        <br/>
                        <a href="{{url('phd-entrance-admitcard')}}" class="btn btn-block btn-primary">Click download Ph.D. Entrance Admit Card</a>
                        <br/>
                        <h4 class="mb-3 text-success">Welcome to Admission Portal</h4>
                        <div class="row my-4">
                        @if(Auth::user())
                            <!-- <h4 class="pb-2 border-bottom mb-2"><a href="{{url('application-form')}}" class="btn btn-primary">Application Form</a></h4> -->
                            <h4 class="pb-2 border-bottom mb-2"><a href="{{url('dashboard')}}" class="btn btn-primary">Dashboard</a></h4>
                        @else
                
                            @include('frontend.layouts.partials.login-page')
                        
                        @endif
                
                        </div>
                    </div>
                </div>
                    
            </div>
    </div>
    </div>
</div>
 </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <code>Note:</code>
            If Any Query Please Call or Whatsapp on :<a href="tel:+919667277184">+919667277184</a> between 10:00AM to 7:00PM
            <p>Or Mail To <a href="mailto:dsmnru.help@gmail.com">dsmnru.help@gmail.com</a></p>
        </div>
    </div>
 

    @section('scripts')
        
    @endsection


@endsection
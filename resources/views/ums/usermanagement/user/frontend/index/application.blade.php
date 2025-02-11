
@extends('frontend.layouts.app')
@section('content')

    
	<!--SECTION START-->
    <section>
        <div class="pro-cover">
        </div>
        <div class="pro-menu">
            <div class="container">
                <div class="col-md-9 col-md-offset-3">
                    <ul>
                        <li><a href="{{route('enquery-dashboard')}}">My Dashboard</a></li>
                        <li><a href="{{route('enquery-profile')}}">Profile</a></li>
                        <li><a href="{{route('enquery-courses')}}">Applied Courses</a></li>
                        <li><a href="{{route('application')}}" class="pro-act">Application Status</a></li> 
                    </ul>
                </div>
            </div>
        </div>
        <div class="stu-db">
            <div class="container pg-inn mt-0 pt-0">
                <div class="col-md-3">
                    <div class="pro-user">
                        <img src="images/user.jpg" alt="user">
                    </div>
                    <div class="pro-user-bio">
                        <ul>
                            <li>
                                <h4><strong>Nishu Garg</strong></h4>
                            </li>
                            <li>Student Id: ST17241</li>
                            <li><a href="#!"><i class="fa fa-envelope-o"></i> nishu@gmail.com</a></li>
                            <li><a href="#!"><i class="fa fa-phone-square"></i> +91 9988776548</a></li> 
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="udb">

                         
                        <div class="udb-sec udb-cour">
						
                            
							<a class="btn gtn-success" target="_blank" href="{{route('application-form')}}">Fill Application Form</a>
							
                        </div>
                        
						
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--SECTION END-->

@endsection
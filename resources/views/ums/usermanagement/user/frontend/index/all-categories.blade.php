
@extends('frontend.layouts.app')
@section('content')


    <!-- POPULAR COURSES -->
    <section >
        <div class="container com-sp pad-bot-70">
            <div class="row">
                <div class="con-title">
                    <h2><span>Courses for DSMNRU CAMPUS</span></h2>
                    <p>University Course/Program details for Admission Academic Session 2021-22</p>
                </div>
            </div>
            <div class="row">
				@foreach($campus_courses as $key=>$course)
                <div class="col-md-6">
                    <div>
                        <!--POPULAR COURSES-->
                        <div class="home-top-cour">
                            <!--POPULAR COURSES IMAGE-->
                            <div class="col-md-3"> <img src="images/course/sm-1.jpg" alt=""> </div>
                            <!--POPULAR COURSES: CONTENT-->
                            <div class="col-md-9 home-top-cour-desc">
                                <a href="course-details.html">
                                    <h3>{{$course->name}}</h3>
                                </a>
                                <h4>{{$course->category->name}}</h4>
                                <p>{{$course->course_description}}</p> <span class="home-top-cour-rat">4.2</span>
                                <div class="hom-list-share">
                                    <ul>
                                        <li><a href="{{route('course-details',[$course->id])}}"><i class="fa fa-eye" aria-hidden="true"></i> View More </a> </li>
                                        <li><a href="course-details.html"><i class="fa fa-rupee" aria-hidden="true"></i>15805</a> </li>
                                        <li><a href="course-details.html"><i class="fa fa-clock-o" aria-hidden="true"></i> 2 Years</a> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
				@endforeach

            </div>
        </div>
    </section>

    <section >
        <div class="container com-sp pad-bot-70">
            <div class="row">
                <div class="con-title">
                    <h2><span>Courses for AFFILIATED COLLEGES</span></h2>
                    <p>University Course/Program details for Admission Academic Session 2021-22</p>
                </div>
            </div>
            <div class="row">
				@foreach($aff_courses as $key=>$course)
                <div class="col-md-6">
                    <div>
                        <!--POPULAR COURSES-->
                        <div class="home-top-cour">
                            <!--POPULAR COURSES IMAGE-->
                            <div class="col-md-3"> <img src="images/course/sm-1.jpg" alt=""> </div>
                            <!--POPULAR COURSES: CONTENT-->
                            <div class="col-md-9 home-top-cour-desc">
                                <a href="course-details.html">
                                    <h3>{{$course->name}}</h3>
                                </a>
                                <h4>{{$course->category->name}}</h4>
                                <p>{{$course->course_description}}</p> <span class="home-top-cour-rat">4.2</span>
                                <div class="hom-list-share">
                                    <ul>
                                        <li><a href="{{route('course-details',[$course->id])}}"><i class="fa fa-eye" aria-hidden="true"></i> View More </a> </li>
                                        <li><a href="course-details.html"><i class="fa fa-rupee" aria-hidden="true"></i>15805</a> </li>
                                        <li><a href="course-details.html"><i class="fa fa-clock-o" aria-hidden="true"></i> 2 Years</a> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
				@endforeach

            </div>
        </div>
    </section>


@endsection
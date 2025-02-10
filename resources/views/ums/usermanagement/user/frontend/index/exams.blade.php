
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
                        <li><a href="{{route('enquery-dashboard')}}" class="pro-act">My Dashboard</a></li>
                        <li><a href="{{route('enquery-profile')}}">Profile</a></li>
                        <li><a href="{{route('enquery-courses')}}">Applied Courses</a></li>
                        <li><a href="{{route('application')}}">Application Status</a></li> 
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
                            <h4><img src="images/icon/db2.png" alt="" /> Recently Applied Course</h4>
                            <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.The point of using Lorem Ipsummaking it look like readable English.</p>
                            
							<div class="home-top-cour">
								<!--POPULAR COURSES IMAGE-->
								<div class="col-md-3"> <img src="images/course/sm-1.jpg" alt=""> </div>
								<!--POPULAR COURSES: CONTENT-->
								<div class="col-md-9 home-top-cour-desc">
									<a href="course-details.html">
										<h3>D.Ed. Special Education (V.I.)</h3>
									</a>
									<h4>Visual Impairment</h4>
									<p>10+2 or equivalent examination in any stream with 50% marks from any recognized board</p> <span class="home-top-cour-rat">4.2</span>
									<div class="hom-list-share">
										<ul>
											<li><a href="course-details.html"><i class="fa fa-eye" aria-hidden="true"></i> View More </a> </li>
											<li><a href="course-details.html"><i class="fa fa-rupee" aria-hidden="true"></i>15805</a> </li>
											<li><a href="course-details.html"><i class="fa fa-clock-o" aria-hidden="true"></i> 2 Years</a> </li>
										</ul>
									</div>
								</div>
							</div>
							
							
							
                        </div>
                        <div class="udb-sec udb-cour-stat">
                            <h4><img src="images/icon/db3.png" alt="" /> Course Application Status</h4>
                            <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.The point of using Lorem Ipsummaking it look like readable English.</p>
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
                                                <tr>
                                                    <td>1</td>
                                                    <td><a href="#"><span class="list-enq-name">Software Testing</span></a></td> 
													<td>03 Jun 2021</td> 
                                                    <td>
                                                        <span class="label label-success">In Progress</span>
                                                    </td>
													<td><a href="#" class="ad-st-view">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td><a href="#"><span class="list-enq-name">Mechanical Design</span></a></td> 
													<td>03 Jun 2021</td> 
                                                    <td>
                                                        <span class="label label-success">In Progress</span>
                                                    </td>
													<td><a href="#" class="ad-st-view">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td><a href="#"><span class="list-enq-name">Architecture & Planning</span></a></td> 
													<td>03 Jun 2021</td> 
                                                    <td>
                                                        <span class="label label-success">In Progress</span>
                                                    </td>
													<td><a href="#" class="ad-st-view">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td><a href="#"><span class="list-enq-name">Board Exam Training</span></a></td> 
													<td>03 Jun 2021</td> 
                                                    <td>
                                                        <span class="label label-success">In Progress</span>
                                                    </td>
													<td><a href="#" class="ad-st-view">View</a></td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td><a href="#"><span class="list-enq-name">Yoga Training Classes</span></a></td> 
													<td>03 Jun 2021</td> 
                                                    <td>
                                                        <span class="label label-success">In Progress</span>
                                                    </td>
													<td><a href="#" class="ad-st-view">View</a></td>
                                                </tr>
                                                   
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                             
                        </div>
                         
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--SECTION END-->

@endsection
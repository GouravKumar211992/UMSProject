@extends('frontend.layouts.user')
@section('content')

	<!--SECTION START-->
        @if($data && $data['application']) 
            <div class="udb"> 
                <div class="udb-sec udb-prof">
                    <h4><img src="images/icon/db1.png" alt=""> My Profile</h4>
                    <p style="display:none;">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed
                        to using 'Content here, content here', making it look like readable English.</p>
                    <div class="sdb-tabl-com sdb-pro-table">
                        <table class="responsive-table bordered">
                            <tbody>
                                <tr>
                                    <td>Student Name</td>
                                    <td>:</td>
                                    <td>{{$data['application']->first_Name}} {{$data['application']->middle_Name}} {{$data['application']->last_Name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Student Id</td>
                                    <td>:</td>
                                    <td>{{$data['application']->application_no}}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{$data['application']->email}}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>:</td>
                                    <td>{{$data['application']->mobile}}</td>
                                </tr>
                                <tr>
                                    <td>Date of birth</td>
                                    <td>:</td>
                                    <td>{{date('d-M-Y',strtotime($data['application']->date_of_birth))}}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>:</td>
                                    <td>{{$address}}</td>
                                </tr>   
                                <!-- <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td><span class="db-done">{{$data['application']->status}}</span> </td>
                                </tr> -->
                            </tbody>
                        </table>
                        <!--<div class="sdb-bot-edit"> 
                            <a href="#" class="waves-effect waves-light btn-large sdb-btn"><i class="fa fa-pencil"></i> Edit my profile</a>
                        </div>-->
                    </div>
                </div> 
            </div>
                 
		@else 
            <div class="udb"> 
                    <div class="udb-sec udb-prof">
                    <h4><img src="images/icon/db1.png" alt=""> My Profile</h4>
                    <div class="sdb-tabl-com sdb-pro-table">
                        <table class="responsive-table bordered">
                            <tbody>
                                <tr>
                                    <td>Student Name</td>
                                    <td>:</td>
                                    <td>{{$data['user_data']->first_name}} {{$data['user_data']->middle_name}} {{$data['user_data']->last_name}}
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{$data['user_data']->email}}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>:</td>
                                    <td>{{$data['user_data']->mobile}}</td>
                                </tr>                       
                            </tbody>
                        </table>
                        <!--<div class="sdb-bot-edit"> 
                            <a href="#" class="waves-effect waves-light btn-large sdb-btn"><i class="fa fa-pencil"></i> Edit my profile</a>
                        </div>-->
                    </div>
                </div> 
            </div>
                 
		@endif
     
    

@endsection
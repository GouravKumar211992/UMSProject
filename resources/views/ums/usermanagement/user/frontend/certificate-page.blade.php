<!DOCTYPE html>
<html lang="en">

<head>
    <title>Certificate Portal</title>
     

    <link rel="shortcut icon" href="/assets/frontend/images/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700%7CJosefin+Sans:600,700" rel="stylesheet">
    <link rel="stylesheet" href="/assets/frontend/css/font-awesome.min.css">
    <link href="/assets/frontend/css/materialize.css" rel="stylesheet">
    <link href="/assets/frontend/css/bootstrap.css" rel="stylesheet" />
    <link href="/assets/frontend/css/style.css" rel="stylesheet" />
    <link href="/assets/frontend/css/style-mob.css" rel="stylesheet" />
    <style>
  .blink_me {
    font-size: 30px;
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
    @yield('styles')
</head>
<body>
       <section class="bg-white py-3 border-bottomdashed">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="{{asset('assets/frontend/css/landing/mainpagelogo.png')}}" />
                    </div>
                </div>
            </div>
        </section>  
		
<section style="background: url({{asset('assets/frontend/css/landing/university-bgmain.jpg')}})">

<section>
  
    <div class="container  pad-bot-70 ">
       <u><center><h1>Download Your Certificates    </h1></center></u><br><br><br><br>

	@include('partials.notifications')

<div class="row">
  @if($result)
     
  <div class="col-sm-4">

    <form style=" width: 400px; padding: 50px;border: 5px solid gray;margin: 0;">
      <ul>
	@php
	$roll_no = base64_decode(Request()->roll_no);
	$roll_number = \App\Models\Result::where('roll_no',$roll_no)->first();
	@endphp

	   
      <a href="{{url('student-results')}}/{{Request()->roll_no}}" target="_blank"> <li style="color: blue;"><span class="blink_me">👉</span>Download Mark Sheet</li></a><br>
      <a href="{{url('provisional-certificate',[Request()->roll_no])}}" target="_blank"> <li style="color: blue;"> <span class="blink_me">👉</span>Download Provisional Certificate</li></a><br>
      <a href="{{url('migration-certificate',[Request()->roll_no])}}" target="_blank"> <li style="color: blue;"> <span class="blink_me">👉</span>Download Migration Certificate</li></a>
      
      </ul>

    </form>

  </div>
  

@else
<center><h3 >Result Not Generated.</h3></center>
@endif
</div>

    </div>
  
</section>
</section>
</body>
</html>


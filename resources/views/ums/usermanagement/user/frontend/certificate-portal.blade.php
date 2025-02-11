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

    @yield('styles')
      @yield('scripts')
      <style>
          body .certificate{
            background: {{asset('images/slider/3.jpg')}}
          }
      </style>
</head>
<body>
        <!--Import jQuery before materialize.js-->
    <script src="/assets/frontend/js/main.min.js"></script>
    <script src="/assets/frontend/js/bootstrap.min.js"></script>
    <script src="/assets/frontend/js/materialize.min.js"></script>
    <script src="/assets/frontend/js/custom.js"></script>

    

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
    <div class="container" style="margin-top:40px;">

       @include('admin.partials.notifications')
<div class="row">

  <div class="col-sm-4">

    <form method="POST"  action="{{route('certificate-portal')}}" style=" width: 500px; ">
    @csrf
    <div class="col-sm-6 col-md-4 " style="min-width:435px;margin-top: 20px;">
        <div class="panel panel-default" style="background:transparent;border: transparent;">
                    <div class="panel-heading">
                        <strong><center>PROVISIONAL/MIGRATION/MARK SHEET PORTAL</center></strong>
                    </div>
                    <div class="panel-body" >
                        <fieldset>
                                <div class="row">
                                    <div class="center-block text-center">
                                        <img src="assets\frontend\images\icon.png" class="circular--square"  />
                                    </div>
                                </div><br><br>
                                <div class="row">
                                    <div class="col-sm-12 col-md-10  col-md-offset-1 ">

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-lock"></i>
                                                </span>

                                                <input name="roll_number" type="input" id="roll_number" class="form-control" placeholder="Enter Roll Number" style="border: 1px solid #c0c0c0;padding-left: 5px;" required/> 
                                            </div>
                                            <div class="input-group" style="margin-top: 10px;">
                                                <span class="input-group-addon">
                                                   <i class="glyphicon glyphicon-lock"></i>
                                                </span>
                                                <input type="input" id="dob" class="form-control" name="password" placeholder="Password" required>
                                            </div>
                                        </div><br>


                                    </div>
                                </div>
                                <div class="col-md-12 removeError text-center">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-lg btn-primary btn-block" />
                                    </div>
                            </fieldset>
                    </div>

                    
         </div>
 </div>
</div>


                </div>
 </div>

        </div>

    </form>

  </div>
    </div>

    </div>

</section>

</body>

</html>


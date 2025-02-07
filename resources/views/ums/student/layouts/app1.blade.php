<!DOCTYPE html>
<html lang="en">

<head>
    <title>@section('title') DSMNRU @show</title>
    <!-- META TAGS -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="content-type" content="dsmnru, dsmru, dsmu, Dr Shakuntala Misra National Rehabilitation University , rehabilitation, disability, disabled university India, disabled, lucknow,
			u.p., uttar pradesh, india, north india, northern india, indian, best University for disabled, first university of Asia for disableds, best disablity support, University with best disablity support,
		 dsmru, for differently abled, rehabilitation, university differently abled, shakuntala,
		  misra, dr., mohaan road,disabled, visually impaired, mental retardation, hearing impaired" />
    <meta name="keywords" content="dsmnru, dsmru, dsmu, Dr Shakuntala Misra National Rehabilitation University , rehabilitation, disability, disabled university India, disabled, lucknow,
			u.p., uttar pradesh, india, north india, northern india, indian, best University for disabled, best university, Asia's first university, first university of Asia for disableds, best disablity support, University with best disablity support,
		 dsmru, for differently abled, rehabilitation, university differently abled, shakuntala, misra, dr., mohaan road, disabled, visually impaired, mental retardation,
		   hearing impaired" />
    <meta name="description" content="dsmnru, dsmru, dsmu, Dr Shakuntala Misra National Rehabilitation University , rehabilitation, disability, disabled university India, disabled, lucknow,
			u.p., uttar pradesh, india, north india, northern india, indian, best University for disabled, first university of Asia for disableds, best disablity support, University with best disablity support,
		  for differently abled, rehabilitation, university differently abled, shakuntala,
		  misra, dr., mohaan road, disabled, visually impaired, mental retardation, hearing impaired" />


      <link rel="shortcut icon" href="{{asset('assets/frontend/images/favicon.png')}}" type="image/x-icon">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{asset('assets/admin/css/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
      <link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
      <link href="{{asset('assets/admin/css/font-awesome.css')}}" rel="stylesheet">
      <link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet">
      <link href="{{asset('assets/admin/css/responsive.css')}}" rel="stylesheet">
      <link href="{{asset('assets/admin/css/bootstrap-select.css')}}" rel="stylesheet">
      <!-- Bootstrap CSS -->
      
      @yield('styles')
    </head>
    
    <body class="hold-transition sidebar-mini layout-fixed">
      <div class="wrapper">
        
        
        
        
        
        
        
        @yield('content')
        
        <!--Import jQuery before materialize.js-->
        <script src="{{asset('assets/bootstrap/js/jquery-3.5.1.js')}}"></script>
    <script src="{{asset('assets/frontend/js/main.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/materialize.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/custom.js')}}"></script>
    <!-- Bootstrap JS -->

    @yield('scripts')
	</div>
    
</body>

</html>
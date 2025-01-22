<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">

   
    <title>@yield('title', 'Presence 360')</title>

    <link rel="apple-touch-icon" href="{{url('/app-assets/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/assets/css/favicon.png')}}">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600;700"
        rel="stylesheet">

    <!-- <script src = "{{asset('app-assets/js/common-script.js')}}"> </script> -->
    @include('layouts.partials.css')

    @yield('styles')

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  menu-collapsed" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    @include('layouts.partials.header')
    @if ($organization_id != 8)
        @include('layouts.partials.side-menu-permission-wise')   
    @else 
        @include('layouts.partials.side-menu')
    @endif
    
    @yield('content')

    @include('layouts.partials.footer')

    @yield('modals')

    @include('layouts.partials.js')

    @yield('scripts')

</body>
<!-- END: Body-->

</html>
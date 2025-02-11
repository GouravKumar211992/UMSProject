<!DOCTYPE html>
<html lang="en">
<head id="Starter-Site">
    <meta charset="UTF-8">
    
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Miditech</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="/assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/css/admin.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    
    <meta name="keywords" content="@yield('keywords')" />
    <meta name="author" content="@yield('author')" />
    <!-- Google will often use this as its description of your page/site. Make it good. -->
    <meta name="description" content="@yield('description')" />
    <!-- Speaking of Google, don't forget to set your site up: http://google.com/webmasters -->
    <meta name="google-site-verification" content="">
    <!-- Dublin Core Metadata : http://dublincore.org/ -->
    <meta name="DC.title" content="Project Name">
    <meta name="DC.subject" content="@yield('description')">
    <meta name="DC.creator" content="@yield('author')">
    <!--  Mobile Viewport Fix -->
    <link href="{{{ asset('assets/admin/css/jquery-ui-1.10.3.custom.css') }}}" rel="stylesheet" type="text/css">
    <link href="{{{ asset('assets/admin/css/colorbox.css') }}}"	rel="stylesheet) }}}" type="text/css">
    <link href="{{{ asset('assets/admin/css/jquery.multiselect.css') }}}"	rel="stylesheet" type="text/css">
    <link href="{{{ asset('assets/admin/css/style_modal.min.css') }}}"	rel="stylesheet" type="text/css">
    <link href="{{{ asset('assets/admin/css/select2.css') }}}"	rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/css/summernote.css')}}"	rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/css/summernote-bs3.css')}}"	rel="stylesheet" type="text/css">
    <link href="/assets/admin/css/custom.css" rel="stylesheet" type="text/css" />

    @yield('styles')

    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!-- start: Favicon and Touch Icons -->
    <link rel="shortcut icon"	href="{{{ asset('assets/admin/ico/favicon.ico') }}}">
    <!-- end: Favicon and Touch Icons -->
</head>
<body>
	<!-- Container -->
	<div class="container">

        <style type="text/css">
            .notify{
                /*display: none;*/
                top: 20px; right: 20px; 
                position: fixed; 
                width: 310px; height: auto; 
                margin: 0px; padding: 0px; 
                list-style-type: none; 
                z-index: 10000000;
            }
            .notify .alert {
                display: none;
            }
        </style>

        <div class="notify">
            <div class="alert alert-danger alert-dismissible">
                <div class="alert-msg"></div>
            </div>
            <div class="alert alert-info alert-dismissible">
                <div class="alert-msg"></div>
            </div>
            <div class="alert alert-warning alert-dismissible">
                 <div class="alert-msg"></div>
            </div>
            <div class="alert alert-success alert-dismissible">
                <div class="alert-msg"></div>
            </div>
        </div>
		@yield('content')
	</div>
	<!-- ./ container -->
	<!--[if !IE]>-->
	<script src="/assets/admin/js/jQuery-2.1.4.min.js"></script>
	<!--<![endif]-->
	<!--[if IE]>
		<script src="{{{ asset('assets/admin/js/jquery-1.11.1.min.js') }}}"></script>
		<![endif]-->
	<script src="{{{ asset('assets/admin/js/bootstrap.min.js') }}}"></script>
	<script src="{{{ asset('assets/admin/js/jquery-ui.1.11.2.min.js') }}}"></script>
	<script src="{{{ asset('assets/admin/js/jquery.colorbox.js') }}}"></script>
    <script src="{{  asset('assets/admin/js/summernote.js')}}"></script>
	<script type="text/javascript">
			$(function() {
                $('.summernote').summernote({height: 250});
				$('.close_popup').click(function() {
					parent.$.colorbox.close()
					window.parent.location.reload();
				});
			});
		</script>
	@yield('scripts')
</body>
</html>

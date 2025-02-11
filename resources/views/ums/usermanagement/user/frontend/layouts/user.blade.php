<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dr Shakuntala Misra National Rehabilitation University</title>
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


    <link rel="shortcut icon" href="/assets/frontend/images/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700%7CJosefin+Sans:600,700" rel="stylesheet">
    <link rel="stylesheet" href="/assets/frontend/css/font-awesome.min.css">
    <link href="/assets/frontend/css/materialize.css" rel="stylesheet">
    <link href="/assets/frontend/css/bootstrap.css" rel="stylesheet" />
    <link href="/assets/frontend/css/style.css" rel="stylesheet" />
    <link href="/assets/frontend/css/style-mob.css" rel="stylesheet" />

@yield('styles')
<style>
.pro-menu {
    z-index: 0 !important;
}
.pro-user {
    box-shadow: 0px 0px 0px 0px rgb(150 150 150 / 0%);
}
</style>
</head>

<body>

    @include('frontend.layouts.partials.header')
    <!--SECTION START-->
    <section>
        <div class="pro-cover">
        </div>
        <div class="pro-menu">
            <div class="container">
                <div class="col-md-9 col-md-offset-3">
                    <ul>
                        <li><a href="{{route('user-dashboard')}}" @if($section == "user-dashboard") class="pro-act" @endif>My Dashboard</a></li>
                        <li><a href="{{route('user-profile')}}" @if($section == "user-profile") class="pro-act" @endif>Profile</a></li>
                        <li><a href="{{route('notice-board')}}" @if($section == "notice-board") class="pro-act" @endif>Notice Board</a></li>
                        <!--li><a href="{{route('user-courses')}}" @if($section == "user-courses") class="pro-act" @endif>Applied Courses</a></li>
                        <li><a href="{{route('user-applications')}}" @if($section == "user-applications") class="pro-act" @endif>Application Status</a></li-->
                    </ul>
                </div>
            </div>
        </div>
        <div class="stu-db">
            <div class="container pg-inn mt-0 pt-0">
                <div class="col-md-3">
                    <div class="pro-user">
					@if($section == "user-dashboard")
                        @if($lastApplication)
                        <img src="{{$lastApplication->photo_url_user}}" width="255px" alt="user">
                        @else
                        <img  src="{{asset('images\default-user-icon.jpg')}}" width="255px" alt="user">
                        @endif 
					@endif
					@if($section == "user-profile") 
                    <img  @if($data['application']) src="{{$data['application']->photo_url_user}}" @else src="{{asset('images\default-user-icon.jpg')}}" @endif width="255px" alt="user">
					@endif
                    @if($section == "notice-board") 
                        <img  @if($data['application']) src="{{$data['application']->photo_url_user}}" @else src="{{asset('images\default-user-icon.jpg')}}" @endif width="255px" alt="user">
					@endif
                    </div>
                    <div class="pro-user-bio">
                        <ul>
                            <li>
                                <h4><strong>{{Auth::user()->name}}</strong></h4>
                            </li>
                            <!-- <li>Student Id: ST17241</li> -->
                            <li><a><i class="fa fa-envelope-o"></i> {{Auth::user()->email}}</a></li>
                            <li><a><i class="fa fa-phone-square"></i> {{Auth::user()->mobile}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">

                    @yield('content')

                </div>
            </div>
        </div>
    </section>
    <!--SECTION END-->


    @include('frontend.layouts.partials.footer')

    <!--Import jQuery before materialize.js-->
    <script src="/assets/frontend/js/main.min.js"></script>
    <script src="/assets/frontend/js/bootstrap.min.js"></script>
    <script src="/assets/frontend/js/materialize.min.js"></script>
    <script src="/assets/frontend/js/custom.js"></script>
    @yield('scripts')

    <script>
        $(document).ajaxStart(function() {
                $("#loading-image").show();
            })
            .ajaxStop(function() {
                $("#loading-image").hide();
            });

        $('#success-alert-modal').on('hidden.bs.modal', function() {
            window.location.href = "{{ route('admin-dashboard') }}";
        })

        function enquery_login() {
            $('.invalid-feedback').text('');
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                type: 'POST',
                dataType: "json",
                url: "{{ route('enquery-login') }}",
                data: $('#myform').serialize(),
                success: function(data) {
                    console.log(data);
                    if (data.status == true) {
                        $('#login-alert-modal').addClass('show');
                        $('#login-alert-modal').addClass('in');
                    } else {
                        $.each(data, function(index, val) {
                            $('.' + index + '_login').text(val).css({
                                'display': 'block'
                            });
                        });
                        $('.login_login').text(data.message).css({
                            'display': 'block'
                        });
                    }
                },
                error: function(request, status, error) {
                    $('.login_login').text(error).css({
                        'display': 'block'
                    });
                }
            });
        }

        function enquery_register() {
            //	$('#register').find('.pop-close').trigger('click');

            $('.invalid-feedback').text('');
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                type: 'POST',
                dataType: "json",
                url: "{{ route('enquery-register') }}",
                data: $('#myform_register').serialize(),
                success: function(data) {
                    console.log(data);
                    if (data.status == true) {
                        $('#register-alert-modal').addClass('show');
                        $('#register-alert-modal').addClass('in');
                    } else {
                        $.each(data, function(index, val) {
                            $('.' + index + '_register').text(val).css({
                                'display': 'block'
                            });
                        });
                        $('.register_register').text(data.message).css({
                            'display': 'block'
                        });
                    }
                },
                error: function(request, status, error) {
                    $('.register_register').text(error).css({
                        'display': 'block'
                    });
                }
            });
        }
    </script>
    <script>
            $(document).ready(function(){
                $(".darktheme").click(function(){
                    $("body").addClass("dark-theme");
                });
                $(".maintheme").click(function(){
                    $("body").removeClass("dark-theme");
                });
            });
        </script>

<script>
	$(document).ready(function(){
		$(".darktheme").click(function(){
			$("body").addClass("dark-theme");
		});
		$(".maintheme").click(function(){
			$("body").removeClass("dark-theme");
		});
	});
</script>

</body>

</html>
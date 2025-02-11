<!DOCTYPE html>
<html lang="en">

<head>
	<title>Dr Shakuntala Misra National Rehabilitation University</title>
	<!-- META TAGS -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
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
    <!-- Latest jQuery version -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	{{-- {!! RecaptchaV3::initJs() !!} --}}
	
	@yield('styles')
</head>

<body>

    
     @include('ums.admin.header')
	{{-- @include('ums.usermanagement.user.frontend.layouts.partials.header') --}}

	@yield('content')

	{{-- @include('ums.usermanagement.user.frontend.layouts.partials.footer') --}}

	{{-- <!--Import jQuery before materialize.js-->
	<script src="/assets/frontend/js/main.min.js"></script>
	<script src="/assets/frontend/js/bootstrap.min.js"></script>
	<script src="/assets/frontend/js/materialize.min.js"></script>
	<script src="/assets/frontend/js/custom.js"></script>
	<script src="{{asset('js/sweetalert.js')}}"></script> --}}

	{{-- {!! NoCaptcha::renderJs() !!} --}}
	@include('ums.admin.script')
    @include('ums.admin.footer')
	@yield('scripts')

<script>
$(document).ajaxStart(function () {
	$("#loading-image").show();
 })
.ajaxStop(function () {
	$("#loading-image").hide();
});

$('#success-alert-modal').on('hidden.bs.modal', function () {
  window.location.href = "{{ url('/dashboard') }}";
})

function enquery_login($this){
	$('.invalid-feedback').text('');
    $_token = "{{ csrf_token() }}";
	$.ajax({
	    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
		type:'POST',
		dataType:  "json",
		url : "{{ url('login') }}",
		data : $this.closest('#myform').serialize(),
		success:function(data) {
			//grecaptcha.reset();
			// console.log(data);
			if(data.status==true){
				$('#login-alert-modal').addClass('show');
				$('#login-alert-modal').addClass('in');
			}else{
				$.each(data, function(index,val){
					$this.closest('#myform').find('.'+index+'_login').text(val).css({'display':'block'});
				});
				$this.closest('#myform').find('.login_login').html(data.message).css({'display':'block'});
			}
	   },
		error: function (request, status, error) {
			$this.closest('#myform').find('.login_login').text(error).css({'display':'block'});
		}
	});
}

function enquery_register($this){
//	$('#register').find('.pop-close').trigger('click');

	$('.invalid-feedback').text('');
    $_token = "{{ csrf_token() }}";
	$.ajax({
	    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
		type:'POST',
		dataType:  "json",
		url : "{{ url('register') }}",
		data : $this.closest('#myform_register').serialize(),
		success:function(data) {
			//console.log('hhhii');
			//grecaptcha.reset();
			// console.log(data);
			if(data.status==true){
				$('#register-alert-modal').addClass('show');
				$('#register-alert-modal').addClass('in');
			}else{
				$.each(data, function(index,val){
					$('.'+index+'_register').text(val).css({'display':'block'});
				});
				$('.register_register').text(data.message).css({'display':'block'});
			}
	   },
		error: function (request, status, error) {
			$('.register_register').text(error).css({'display':'block'});
		}
	});
}

function enquery_gorgot_password(){
//	$('#register').find('.pop-close').trigger('click');

	$('.invalid-feedback').text('');
    $_token = "{{ csrf_token() }}";
	$.ajax({
	    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
		type:'POST',
		dataType:  "json",
		url : "{{ route('user-forgot-password') }}",
		data : $('#myform_forgot').serialize(),
		success:function(data) {
			// console.log(data);
			if(data.status==true){
				$('#forgot-alert-modal').addClass('show');
				$('#forgot-alert-modal').addClass('in');
			}else{
				$.each(data, function(index,val){
					$('.'+index+'_forgot').text(val).css({'display':'block'});
				});
				$('.forgot_forgot').text(data.message).css({'display':'block'});
			}
	   },
		error: function (request, status, error) {
			$('.forgot_forgot').text(error).css({'display':'block'});
		}
	});
}

$( '#login, #register, #modal3' ).on('hidden.bs.modal', function (e) {
	$('form').trigger("reset");
	$('.invalid-feedback').text('');
});

$('.numbersOnly').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
$("input").keypress(function() {
	$(this).closest('.removeError').find('.invalid-feedback').text('');
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

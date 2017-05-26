<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title> @yield('title') </title>

	<!-- favicon -->
    <link rel="icon" href="{{asset('img/favicon/logo_32.png')}}" sizes="16x16" type="image/png">
    <link rel="icon" href="{{asset('img/favicon/logo_48.png')}}" sizes="16x16" type="image/png">
    <link rel="icon" href="{{asset('img/favicon/logo_62.png')}}" sizes="16x16" type="image/png">
    <link rel="icon" href="{{asset('img/favicon/logo_192.png')}}" sizes="16x16" type="image/png">

	<!-- 模拟APP效果 -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="菁英启航">
	<!-- Touch Icons -->
	<!-- In most cases, one 180×180px touch icon in the head is enough -->
	<link rel="apple-touch-icon" href="{{asset('img/iosApp/icon.png')}}">
	
	<link rel="stylesheet" href="{{asset('css/reset.css')}}" />
	<link rel="stylesheet" href="{{asset('css/semantic.min.css')}}" />
	<link rel="stylesheet" href="{{asset('css/header.css')}}" />
	@yield('style')
	
	<script src="{{asset('js/jquery2.min.js')}}"></script>
	<script src="{{asset('css/semantic.min.js')}}"></script>
	<script type="text/javascript">
		var pageTitle = document.title;
		$(window).blur(function(){ document.title = "【欢迎再来 <@●-●@>】"; });
		$(window).focus(function(){ document.title = pageTitle; });
	</script>
	@yield('script')
</head>
<body>
	@include('header')

	<div class="contenBox">
		@yield('content')
	</div>

	@yield('footer')
</body>
</html>
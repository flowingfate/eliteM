<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title> @yield('title') </title>
	<link rel="stylesheet" href="{{asset('css/semantic.min.css')}}" />
	<link rel="stylesheet" href="{{asset('css/reset.css')}}" />
	<link rel="stylesheet" href="{{asset('css/header.css')}}" />
	@yield('style')
	<script src="{{asset('js/jquery2.min.js')}}"></script>
	<script src="{{asset('css/semantic.min.js')}}"></script>
	@yield('script')
</head>
<body>
	@include('header')

	<div class="contenBox">
		@yield('content')
	</div>
</body>
</html>
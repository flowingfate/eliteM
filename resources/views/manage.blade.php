@extends('layout')

@section('title','菁英启航')

@if($role=='teacher')
    @section('style')
        <link rel="stylesheet" href="{{asset('css/flatpickr.min.css')}}" />
        <link rel="stylesheet" href="{{asset('css/dateTheme/dark.css')}}" />
    @endsection
@endif

@section('content')
	<div id="interact">
        <interact> </interact>
    </div>
    <script>
    	var globalLoginInfo = 
    	{
    		role:"{{$role}}",
    		id:{{$id}},
    		route:"{{url('/')}}"
    	};

        $.ajaxSetup({headers:{'X-CSRF-TOKEN':"{{csrf_token()}}"}});
    </script>

    @if(isTest())
        <script src="http://localhost:8080/app.js"></script>
    @else
        <script src="{{asset('eliteManage/dist/'.$role.'.js')}}"></script>
    @endif
    
@endsection
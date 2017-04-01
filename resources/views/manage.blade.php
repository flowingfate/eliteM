@extends('layout')

<?php
    $roleTitle = ['admin'=>'管理员','teacher'=>'导师','student'=>'学员','vindicator'=>'运营'];
?>

@section('title','启航系统【'.$roleTitle[$role].'版】')

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
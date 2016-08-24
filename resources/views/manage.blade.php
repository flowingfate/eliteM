@extends('layout')

@section('title','登录')

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
    	}
    </script>
    <script src="{{asset('eliteManage/dist/app.js')}}"></script>
@endsection
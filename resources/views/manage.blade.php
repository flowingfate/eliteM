@extends('layout')

@section('title','菁英启航')

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
    <script src="{{asset('eliteManage/dist/'.$role.'.js')}}"></script>
@endsection
@extends('layout')

@section('title','测试页面')

@section('content')

	<div class="ui segment attached">
		<form action="{{url('vindicator/addSub1')}}" method="post">
			{{ csrf_field() }}
			<input name="number" type="text"/>
			<input name="title" type="text"/>
			<input type="submit" />
		</form>
	</div>
@endsection
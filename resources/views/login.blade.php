@extends('layout')

@section('title','登录')

@section('style')
<style type="text/css">
.loginBox
{
	height:auto; width: 330px; margin:150px auto;
	background-color: white; border-radius: 5px;
	box-shadow: 0 0 10px rgba(0,0,0,0.1);
	position: relative;
}
.v-message
{
	position:fixed; width:100%; left:0; bottom: 0; background-color:rgba(0,0,0,0.2);
	padding:10px 30px; text-align:right;height: 60px; transition: 0.3s ease-in-out;opacity:1;
}
.v-message span { font-size:18px; line-height:40px; padding-right:10px; color:#DB2828; }
.photo
{
	text-align: center;
	position: absolute; width: 100%; left:0; top:-100px;
}
.captcha img { display: inline-block; line-height: 42px;width: 100%; border-radius: 2px; margin-top:1px; }

</style>
@endsection


@section('content')

	@if(session('errMsg'))
		<div class="v-message">
			<button class="ui icon circular button right floated red">
				<i class="icon remove"></i>
			</button>
			<span>{{session('errMsg')}}</span>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){ 
				$('.v-message button').click(function(){$('.v-message').css({height:0,opacity:0});}); 
			});
		</script>
	@endif

	<div class="loginBox" style="padding-top:1px;">
		<div class="photo">
			<i class="circular massive inverted grey user icon"
				style="font-size:96px; padding:32px;margin:0;"></i>
		</div>
		<div style="padding:16px 16px 40px; margin-top:90px;">
			<form class="ui form" action="{{url('login/'.$role)}}" method="post">
				{{ csrf_field() }}
				<div class="fields">
					<div class="field sixteen wide">
						<input type="text" name="username" placeholder="用户名">
					</div>
				</div>
				<div class="fields">
					<div class="field sixteen wide">
						<input type="text" name="password" placeholder="密码">
					</div>
				</div>
				<div class="fields">
					<div class="field nine wide">
						<input type="text" name="captcha" placeholder="验证码">
					</div>
					<div class="field seven wide captcha" >
						<img src="{{url('code')}}" onclick="this.src='{{ url('code') }}?r='+Math.random();" />
					</div>
				</div>
				<button class="ui animated fade orange fluid button" tabindex="0" type="submit">
					<div class="visible content">登录账户</div>
					<div class="hidden content">
						<?php
							$arr = ['admin'=>'管理员','teacher'=>'导师','student'=>'学员'];
							echo $arr[$role];
						?>
					</div>
				</button>
			</form>
		</div>
	</div>
@endsection
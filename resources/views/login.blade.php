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
.photo
{
	text-align: center;
	position: absolute; width: 100%; left:0; top:-100px;
}
.photo i.icon
{
	display: inline-block; padding:.5em 0!important;
	text-align: center;font-size:96px;margin:0;
}
.captcha img { display: inline-block; line-height: 42px;width: 100%; border-radius: 2px; margin-top:1px; }
.footer
{
	position:absolute; bottom:0; left:0; width:100%; height:60px; line-height:60px;
	text-align:center; color:white; background-color:#222e4c; font-size:14px; font-weight:lighter;
	position:fixed;
	
}

@if($role=='teacher'||$role=='student')
body 
{
	background-image:url("{{asset('img/'.$role.'.png')}}");
	background-size: cover; background-position: center; background-repeat: none;
}
@endif

</style>
@endsection


@section('content')

	@if(count($errors)>0)
		<div class="ui error message" style="margin:20px 50px;">
			<i class="close icon"></i>
			<div class="header"> 您填写的登录数据存在一些问题 </div>
			<ul class="list">
				@foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
			</ul>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){$('.message .close').on('click', function(){$(this).closest('.message').transition('fade');});});
		</script>
	@endif
		

	<div class="loginBox" style="padding-top:1px;">
		<div class="photo">
			<i class="circular massive inverted grey <?php
				$icons = ['admin'=>'settings','teacher'=>'doctor','student'=>'student','vindicator'=>'spy'];
				echo $icons[$role]; ?> icon"></i>
		</div>
		<div style="padding:16px 16px 40px; margin-top:90px;">
			<form class="ui form attached fluid" action="{{url('login/'.$role)}}" method="post">
				{{ csrf_field() }}
				<div class="fields">
					<div class="field sixteen wide">
						<input type="text" name="username" placeholder="用户名" value="{{old('username')}}">
					</div>
				</div>
				<div class="fields">
					<div class="field sixteen wide">
						<input type="password" name="password" placeholder="密码" value="{{old('password')}}">
					</div>
				</div>

				@if(session('captcha'))
					<div class="fields">
						<div class="field nine wide">
							<input type="text" name="captcha" placeholder="验证码">
						</div>
						<div class="field seven wide captcha" >
							<img src="{{url('code')}}" onclick="this.src='{{ url('code') }}?r='+Math.random();" />
						</div>
					</div>
				@endif

				<button class="ui animated fade orange fluid button" tabindex="0" type="submit">
					<div class="visible content">登录账户</div>
					<div class="hidden content">
						<?php
							$btn = ['admin'=>'管理员','teacher'=>'导师','student'=>'学员','vindicator'=>'运营维护者'];
							echo $btn[$role];
						?>
					</div>
				</button>
			</form>
		</div>
	</div>
@endsection

@section('footer')
	<div class="footer">
		Copyright © 2016 北京菁英天下国际教育咨询有限公司 版权所有 京ICP备15000012号 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 技术支持：北京赛亚数据科技有限公司
	</div>
@endsection

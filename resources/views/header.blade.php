<div class="head">
	<div class="logo"><img src="{{asset('img/logo.png')}}" /></div>
	<div class="topinfo">
		@if(session('user'))
			<div class="helloUser">
				<i class="big user icon"></i>
				<span>Hello,
					<?php
						$arr = ['admin'=>'管理员','teacher'=>'导师','student'=>'学员','vindicator'=>'运营维护者'];
						echo $arr[$role];
					?>
				</span>
			</div>
		@endif
		@if(!session('user'))
			<a class="iconBox" href="http://www.elitebackground.cn">
				<i class="big home icon"></i>
			</a>
		@endif
		@if(session('user'))
			<a class="iconBox" href="{{url('quit/'.$role)}}">
				<i class="big power icon"></i>
			</a>
		@endif
	</div>
</div>
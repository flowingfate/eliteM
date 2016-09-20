<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

</style>
<template>

<div class="ui small modal" v-el:add>
	<div class="header">添加用户</div>
	<div class="content">
		<div class="ui form">
			<div class="inline field">
				<div class="ui radio checkbox">
					<input type="radio" v-model="role" value="teacher" tabindex="0" class="hidden">
					<label>导师</label>
				</div>
				<div class="ui radio checkbox">
					<input type="radio" v-model="role" value="student" tabindex="0" class="hidden">
					<label>学员</label>
				</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="ui tag label orange">新加用户默认密码为：12345678</div>
			</div>
		</div>
	</div>
	<div class="ui divider" style="margin:0;"></div>
	<div class="content">
		<div v-show="role=='teacher'" class="ui form">
			<h3 class="ui dividing header">请填写导师信息</h3>
			<div class="two fields">
				<div class="field">
					<label>姓名</label>
					<input type="text" v-model="teacher.name">
				</div>
				<div class="field">
					<label>用户名</label>
					<input type="text" v-model="teacher.username">
				</div>
			</div>
			<div class="two fields">
				<div class="field">
					<label>学校</label>
					<input type="text" v-model="teacher.school">
				</div>
				<div class="field">
					<label>实验室</label>
					<input type="text" v-model="teacher.laboratory">
				</div>
			</div>
			<div class="field">
				<label>备注</label>
				<input type="text" v-model="teacher.comment">
			</div>
			<div class="two fields">
				<div class="field">
					<label>email</label>
					<input type="text" v-model="teacher.email">
				</div>
				<div class="field">
					<label>QQ</label>
					<input type="text" v-model="teacher.qq">
				</div>
			</div>
		</div>
		<div v-show="role=='student'" class="ui form">
			<h3 class="ui dividing header">请填学员师信息</h3>
			<div class="two fields">
				<div class="field">
					<label>姓名</label>
					<input type="text" v-model="student.name">
				</div>
				<div class="field">
					<label>用户名</label>
					<input type="text" v-model="student.username">
				</div>
			</div>
			<div class="two fields">
				<div class="field">
					<label>学校</label>
					<input type="text" v-model="student.school">
				</div>
				<div class="field">
					<label>申请方向</label>
					<input type="text" v-model="student.direction">
				</div>
			</div>
			<div class="field">
				<label>备注</label>
				<input type="text" v-model="student.comment">
			</div>
			<div class="two fields">
				<div class="field">
					<label>email</label>
					<input type="text" v-model="student.email">
				</div>
				<div class="field">
					<label>QQ</label>
					<input type="text" v-model="student.qq">
				</div>
			</div>
			<div class="two fields">
				<div class="field">
					<label>手机号</label>
					<input type="text" v-model="student.phone">
				</div>
				<div class="field">
					<label>微信</label>
					<input type="text" v-model="student.wechat">
				</div>
			</div>
		</div>
	</div>
	<div class="actions">
		<div class="ui cancel button" @click="clearInput">取消</div>
		<div class="ui approve button" @click="addUser">确认</div>
	</div>
</div>

</template>

<script>
	module.exports =
	{
		data() {return {

			role:'student',
			teacher:{name:'',username:'',school:'',laboratory:'',comment:'',email:'',qq:''},
			student:{name:'',username:'',school:'',direction:'',comment:'',email:'',qq:'',phone:'',wechat:''}
		}},
		vuex: { getters: {route: ({route})=>{return route;},} },
		methods:
		{
			clearInput()
			{
				Object.keys(this.student).forEach((k)=>{this.student[k]='';});
				Object.keys(this.teacher).forEach((k)=>{this.teacher[k]='';});
			},
			addUser()
			{
				var _this = this;
				var role = this.role;
				var user = this[role];

				var flag = (user.name!='')&&(user.username!='')&&(user.school!='');
				var msg = '姓名、用户名、学校';

				if(role=='student') {
					flag = flag&&(user.direction!='');
					msg = msg+"、申请方向 都不能为空";
				}
				if(role=='teacher') {
					flag = flag&&(user.laboratory!='');
					msg = msg+"、实验室 都不能为空";
				}

				if(!flag) {
					this.$store.dispatch('newMessage',{type:'err',content:msg});
					return;
				}
				
				var data = Object.assign(user,{role:role});
				var route = this.route+'/addUser';
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						// 需要刷新页面获取新信息
						if(data.type=='err') return;
						_this.$parent.loadData();
						_this.clearInput();
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
		},
		events:
		{
			'add':function()
			{
				var _this = this;
				setTimeout(()=>{$(_this.$els.add).modal('show');},100);
			},
		},
		ready() { }
	}
</script>
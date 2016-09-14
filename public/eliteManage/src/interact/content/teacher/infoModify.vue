<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-infoModify
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;

		>div { box-shadow:0 0 10px rgba(0,0,0,0.1); }
		>.left{ float:left; width:35%; }
		>.right{ float:right; width:60%; }
	}
}
</style>
<template>
<div class="v-infoModify">
	<div class="panel">
		<div class="left">
			<h3 class="ui header attached top" style="text-align:center;">修改密码</h3>
			<div class="ui segment attached">
				<div class="ui form" style="height:140px;">
					<div class="field">
						<input type="password" v-model="passInput.origin" placeholder="原密码" />
					</div>
					<div class="field">
						<input type="password" v-model="passInput.new" placeholder="新密码" />
					</div>					
					<div class="field">
						<input type="password" v-model="passInput.again" placeholder="重复密码" />
					</div>
				</div>
			</div>
			<div class="ui segment attached bottom">
				<div class="ui two buttons">
					<button class="ui button" @click="clearPassword">清空</button>
					<div class="or"></div>
					<button class="ui orange button" @click="modifyPassword">确认修改</button>
				</div>
			</div>
		</div>
		<div class="right">
			<h3 class="ui header attached top" style="text-align:center;">修改信息</h3>
			<div class="ui segment attached">
				<div class="ui form" style="height:140px;">
					<div class="two fields">
						<div class="field">
							<div class="ui labeled input">
								<div class="ui basic label">用户名</div>
								<input type="text" v-model="infoInput.username" placeholder="your username">
							</div>
						</div>
						<div class="field">
							<div class="ui labeled input">
								<div class="ui basic label">实验室</div>
								<input type="text" v-model="infoInput.laboratory" placeholder="your laboratory">
							</div>
						</div>
					</div>
					<div class="two fields">
						<div class="field">
							<div class="ui labeled input">
								<div class="ui basic label">学&nbsp;&nbsp;&nbsp;校</div>
								<input type="text" v-model="infoInput.school" placeholder="your school">
							</div>
						</div>
						<div class="field">
							<div class="ui labeled input">
								<div class="ui basic label">邮&nbsp;&nbsp;&nbsp;箱</div>
								<input type="text" v-model="infoInput.email" placeholder="your email">
							</div>
						</div>
					</div>	
					<div class="two fields">		
						<div class="field disabled">
							<div class="ui transparent input">
								<input type="text" placeholder="直接在输入框中修改即可">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ui segment attached bottom">
				<div class="ui two buttons">
					<button class="ui button" @click="resetPersonalInfo">复原</button>
					<div class="or"></div>
					<button class="ui orange button" @click="modifyPersonalInfo">确认修改</button>
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {
			passInput: { origin:'', new:'', again:'' },
			infoInput: { username:'',laboratory:'', school:'',email:''},
			infoOrigin: { username:'',laboratory:'', school:'',email:''}
		}},
		vuex:
		{
			getters:
			{
				route: ({route})=>{return route;},
				myId: ({myId})=>{return myId;}
			}
		},
		methods:
		{
			clearPassword() { this.passInput = { origin:'', new:'', again:'' }; },
			modifyPassword()
			{
				if(this.passInput.origin=='')
				{
					this.$store.dispatch('newMessage',{type:'err',content:'原密码不能为空'});
					return;
				}
				if(this.passInput.new=='')
				{
					this.$store.dispatch('newMessage',{type:'err',content:'新密码不能为空'});
					return;
				}
				if(this.passInput.origin==this.passInput.new)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'新密码应与原密码不同'});
					return;
				}
				if(this.passInput.new!=this.passInput.again)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'两次新密码输入必须相同'});
					return;
				}

				var _this = this;
				var route = this.route+'/teacher/modifyPassword';
				var data = {id:this.myId,originPass:this.passInput.origin,newPass:this.passInput.new};

				$.ajax(
				{
					type:'GET',
					url:route,
					data:data,
					success:(data)=>{
						_this.$store.dispatch('newMessage',data);
						_this.passInput = { origin:'', new:'', again:'' };
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			resetPersonalInfo() 
			{
				Object.keys(this.infoInput).forEach((val)=>{
					this.infoInput[val] = this.infoOrigin[val];
				});
			},
			modifyPersonalInfo()
			{
				var flag = false;
				Object.keys(this.infoInput).forEach((val)=>{
					if(this.infoInput[val] != this.infoOrigin[val]) flag=true;
				});
				if(!flag)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'您好，您并没有做任何修改！'});
					return;
				}

				var _this = this;
				var route = this.route+'/teacher/modifyPersonalInfo';
				var data = {id:this.myId};
				Object.assign(data,this.infoInput);

				$.ajax(
				{
					type:'GET',
					url:route,
					data:data,
					success:(data)=>{
						_this.$store.dispatch('newMessage',data);
						Object.keys(_this.infoInput).forEach((val)=>{
							_this.infoOrigin[val] = _this.infoInput[val];
						});
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			}
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/teacher/personalInfo';
			var data = {id:this.myId};
			$.ajax(
			{
				type:'GET',
				url:route,
				data:data,
				success:(data)=>{ 
					Object.keys(data).forEach((val)=>{
						_this.infoInput[val] = _this.infoOrigin[val] = data[val];
					});
				},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});
		}
	}
</script>
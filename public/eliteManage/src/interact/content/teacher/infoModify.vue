<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-infoModify
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
}
</style>
<template>
<div class="v-infoModify">
	<div class="panel">
		<div class="ui padded grid">

			<div class="five wide stretched column">
				<h3 class="ui header attached top" style="text-align:center;">修改密码</h3>
				<div class="ui segment attached">
					<div class="ui form">
						<div class="field">
							<label>输入原密码</label>
							<input type="password" v-model="passInput.origin" placeholder="原密码" />
						</div>
						<div class="field">
							<label>输入新密码</label>
							<input type="password" v-model="passInput.new" placeholder="新密码" />
						</div>					
						<div class="field">
							<label>再次输入新密码</label>
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

			<div class="eleven wide stretched column">
				<h3 class="ui header attached top" style="text-align:center;">修改基础信息</h3>
				<div class="ui segment attached">
					<div class="ui form">
						<div class="two fields">
							<div class="field">
								<div class="ui labeled input">
									<div class="ui basic label">姓<i v-for="i in 6">&nbsp;</i>名</div>
									<input type="text" v-model="infoInput.name" placeholder="your name">
								</div>
							</div>
							<div class="field">
								<div class="ui labeled input">
									<div class="ui basic label">用户名</div>
									<input type="text" v-model="infoInput.username" placeholder="your username">
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="field">
								<div class="ui labeled input">
									<div class="ui basic label">研究方向</div>
									<input type="text" v-model="infoInput.laboratory" placeholder="your laboratory">
								</div>
							</div>
							<div class="field">
								<div class="ui labeled input">
									<div class="ui basic label">学&nbsp;&nbsp;&nbsp;校</div>
									<input type="text" v-model="infoInput.school" placeholder="your school">
								</div>
							</div>
						</div>
						<div class="two fields">
							<div class="field">
								<div class="ui labeled input">
									<div class="ui basic label">邮<i v-for="i in 6">&nbsp;</i>箱</div>
									<input type="text" v-model="infoInput.email" placeholder="your email">
								</div>
							</div>
							<div class="field">
								<div class="ui labeled input">
									<div class="ui basic label">&nbsp;&nbsp;QQ&nbsp;&nbsp;</div>
									<input type="text" v-model="infoInput.qq" placeholder="your qq">
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
						<button class="ui button" @click="resetInfo">复原</button>
						<div class="or"></div>
						<button class="ui orange button" @click="modifyInfo">确认修改</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel" style="padding:14px;">
		<h3 class="ui header attached top" style="text-align:center;">研究背景修改</h3>
		<div class="ui segment attached grid">
			<div class="eight wide stretched column">
				<div class="ui form">
					<div class="field">
						<label>论文经历</label>
						<textarea v-model="infoInput.paper"></textarea>
					</div>
				</div>
			</div>
			<div class="eight wide stretched column">
				<div class="ui form">
					<div class="field">
						<label>科研经历</label>
						<textarea v-model="infoInput.research"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="ui segment attached bottom">
			<div class="ui two buttons">
				<button class="ui button" @click="resetInfo">复原</button>
				<div class="or"></div>
				<button class="ui orange button" @click="modifyInfo">确认修改</button>
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
			infoInput: { name:'',username:'',laboratory:'', school:'',email:'',qq:'',research:'',paper:''},
			infoOrigin: { name:'',username:'',laboratory:'', school:'',email:'',qq:'',research:'',paper:''}
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
				var p = this.passInput;
				var flag = (p.origin!='')&&(p.new!='')&&(p.again!='');

				if(!flag)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'表单都不能为空'});
					return;
				}

				if(p.origin==p.new)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'新密码应与原密码不能相同'});
					return;
				}
				if(p.new!=p.again)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'两次新密码输入必须相同'});
					return;
				}

				var _this = this;
				var route = this.route+'/modifyPassword';
				var data = {id:this.myId,originPass:p.origin,newPass:p.new};

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{
						_this.$store.dispatch('newMessage',data);
						_this.passInput = { origin:'', new:'', again:'' };
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			resetInfo() 
			{
				Object.keys(this.infoInput).forEach((k)=>{
					this.infoInput[k] = this.infoOrigin[k];
				});
			},
			modifyInfo()
			{
				var o = this.infoOrigin;
				var i = this.infoInput;

				var flag = (i.name!='')&&(i.username!='')&&(i.laboratory!='')&&(i.school!='');
				if(!flag)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'姓名、用户名、研究方向、学校 都不能为空'});
					return;
				}

				var obj = {};
				Object.keys(i).forEach( (k)=>{if(i[k]!=o[k]) obj[k]=i[k];} );
				if(Object.keys(obj).length==0)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'您好，您并没有做任何修改！'});
					return;
				}

				var _this = this;
				var route = this.route+'/modifyPersonalInfo';
				var data = Object.assign(obj,{id:this.myId});
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{
						_this.$store.dispatch('newMessage',data);
						if(data.type=='err') return;
						Object.keys(obj).forEach((k)=>{o[k] = obj[k]; });
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			}
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/personalInfo';
			var data = {id:this.myId};
			$.ajax(
			{
				type:'GET', url:route, data:data,
				success:(data)=>{ 
					if(data.type=='err')
					{
						_this.$store.dispatch('newMessage',data);
						return;
					}
					Object.keys(data).forEach((val)=>{
						_this.infoInput[val] = _this.infoOrigin[val] = data[val];
					});
				},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});
		}
	}
</script>
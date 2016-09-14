<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

</style>
<template>

<div class="ui small modal" v-el:edit>
	<div class="header">编辑<span v-text="{teacher:'导师',student:'学员'}[role]"></span>信息</div>
	<div class="ui divider" style="margin:0;"></div>
	<div class="content">
		<div v-show="role=='teacher'" class="ui form">
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
		<div class="ui cancel button">取消</div>
		<div class="ui button" @click="resetPassword(user.id,role)">重置密码</div>
		<div class="ui approve button" @click="modify">确认</div>
	</div>
</div>

</template>

<script>
	module.exports =
	{
		data() {return {

			teacher:{id:'',name:'',username:'',school:'',laboratory:'',comment:'',email:'',qq:''},
			student:{id:'',name:'',username:'',school:'',direction:'',comment:'',email:'',qq:'',phone:'',wechat:''}
		}},
		vuex: {getters: {route: ({route})=>{return route;},}},
		props:
		{
			user:{type:Object,default:()=>{return {};}},
			role:{type:String,default:'student'}
		},
		watch:
		{
			'user':function()
			{
				this[this.role] = Object.assign({},this.user);
			}
		},
		methods:
		{
			resetPassword(id,role)
			{
				var _this = this;
				var route = this.route+'/resetPassword';

				$.ajax(
				{
					type:'GET', url:route,
					data:{id:id,role:role},
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			modify()
			{
				var _this = this;
				var role = this.role;
				var user = this[role];

				// 先检查表单内容是否为空
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

				// 再检查修改部分的差异性
				var origin = this.user, obj={};
				Object.keys(user).forEach((k)=>{if(user[k]!=origin[k]) obj[k] = user[k];});

				if(Object.keys(obj).length==0) {
					this.$store.dispatch('newMessage',{type:'err',content:"没做任何修改"});
					return;
				}
				
				var data = Object.assign(obj,{role:role,id:user.id});
				var route = this.route+'/modify';
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						Object.keys(obj).forEach((k)=>{origin[k] = obj[k];});
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			}
		},
		events:
		{
			'edit':function()
			{
				var _this = this;
				setTimeout(()=>{$(_this.$els.edit).modal('show');},100);
			},
		},
		ready()
		{
		}
	}
</script>
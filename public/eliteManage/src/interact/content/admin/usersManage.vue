<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.v-usersManage
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
}
</style>
<template>
<div class="v-usersManage">
	<div class="panel">
		<h1 class="ui top attached header">
			<div class="ui right floated animated orange fade button" 
				tabindex="0"
				@click="actionModal">
				<div class="visible content">添加</div>
				<div class="hidden content">&nbsp;&nbsp;&nbsp;<i class="icon add user"></i></div>
			</div>
			用户总览
		</h1>
		<div class="ui segment attached">
			<div class="ui form">
				<div class="inline fields" style="margin:0;">
					<div class="two wide field">
						<div class="ui radio checkbox">
							<input type="radio" v-model="role" value="teacher" tabindex="0" class="hidden">
							<label>导师</label>
						</div>
					</div>
					<div class="two wide field">
						<div class="ui radio checkbox">
							<input type="radio" v-model="role" value="student" tabindex="0" class="hidden">
							<label>学员</label>
						</div>
					</div>
					<div class="twelve wide field">
						<div class="ui action input">
							<input type="text" placeholder="搜索..."  v-model="filter.keyword">
							<select class="ui compact selection dropdown"  v-model="filter.field">
								<option v-for="(key,val) in options"
										v-text="val"
										:value="key">
								</option>
							</select>
							<div class="ui left pointing label" style="line-height:24px; font-size:14px;">
								直接输入过滤词就可以查看你感兴趣的人
							</div>
						</div>
					</div>
				</div>
			</div>

					
		</div>
		<div class="ui segment attached">
			<table class="ui orange selectable celled table">
				<thead>
					<tr> 
						<th class="center aligned">姓名</th> 
						<th class="center aligned">用户名</th> 
						<th class="center aligned" colspan="2">备注</th> 
						<th class="center aligned">重置密码</th> 
						<th class="center aligned">删除</th> 
					</tr>
				</thead>
				<tbody @mouseleave="users.rowIndex=-1">
					<tr v-for="user in users[role]" 
						v-show="match(user)"
						@mouseenter="users.rowIndex=$index">
						<td class="center aligned" v-text="user.name"></td>
						<td class="center aligned" v-text="user.username"></td>
						<td>
							<div class="ui icon input" style="width:100%;">
								<input type="text" v-model="user.comment" placeholder="修改备注" />
								<i class="edit icon"></i>
							</div>
						</td>
						<td class="center aligned">
							<button class="ui icon basic circular button" 
								:class="{orange:users.rowIndex==$index}"
								@click="modifyCmt(user.id,$index)" >
								<i class="checkmark icon"></i>
							</button>
						</td>
						<td class="center aligned">
							<button class="ui icon basic circular button" 
								:class="{orange:users.rowIndex==$index}"
								@click="resetPassword(user.id)" >
								<i class="undo icon"></i>
							</button>
						</td>
						<td class="center aligned">
							<button class="ui icon basic circular button" 
								:class="{orange:users.rowIndex==$index}"
								@click="removeUser(user.id,$index)" >
								<i class="remove user icon"></i>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="ui small modal" v-el:adduser>
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
					</div>
				</div>
				<div class="field">
					<input type="text" v-model="addInput.name" placeholder="姓名">
				</div>
				<div class="field">
					<input type="text" v-model="addInput.username" placeholder="用户名">
				</div>
				<div class="field">
					<textarea v-model="addInput.comment" placeholder="备注" rows="2"></textarea>
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui cancel button">取消</div>
			<div class="ui approve button" @click="addUser">确认</div>
		</div>
	</div>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			role:'teacher',
			options:{name:'姓名',username:'用户名',comment:'评论',id:'编号'},
			filter: { keyword:'', field:'name'},
			addInput:{name:'',username:'',comment:''},
			users:
			{
				rowIndex:-1,
				teacher: [{name:'',username:'',comment:'',id:'',originCmt:''}],
				student: [{name:'',username:'',comment:'',id:'',originCmt:''}]
			}
		}},
		vuex:
		{
			getters: {route: ({route})=>{return route;},}
		},
		methods:
		{
			actionModal() { $(this.$els.adduser).modal('show'); },
			addUser()
			{
				var data = 
				{
					name : this.addInput.name,
					username : this.addInput.username,
					comment : this.addInput.comment,
					role : this.role
				};
				var _this = this;
				var route = this.route+'/admin/addUser';

				$.ajax(
				{
					type:'GET',
					url:route,
					data:data,
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						// 需要刷新页面获取新信息
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			removeUser(id,index)
			{
				var _this = this;
				var route = this.route+'/admin/removeUser';

				$.ajax(
				{
					type:'GET',
					url:route,
					data:{id:id,role:_this.role},
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						_this.users[_this.role].splice(index,1);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			resetPassword(id)
			{
				var _this = this;
				var route = this.route+'/admin/resetPassword';

				$.ajax(
				{
					type:'GET',
					url:route,
					data:{id:id,role:_this.role},
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			modifyCmt(id,index)
			{
				var user = this.users[this.role][index];
				if(user.originCmt==user.comment)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'您好，您并没有做任何修改！'});
					return;
				}

				var route = this.route+'/admin/modifyCmt';
				var _this = this;

				$.ajax(
				{
					type:'GET',
					url:route,
					data:{id:id,role:_this.role,content:user.comment},
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						user.originCmt = user.comment;
					},
					error:()=>{ 
						_this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'});
						user.comment = user.originCmt;
					}
				});
			},
			match(user)
			{
				var filter = this.filter;
				if(!filter.keyword) return true;
				if(!user[filter.field]) false;

				var str = ""+user[filter.field]
				return str.match(filter.keyword);
			}
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/admin/users';
			$.ajax(
			{
				type:'GET',
				url:route,
				success:(data)=>{
					_this.users.teacher = data.teachers;
					_this.users.teacher.forEach((user)=>{user.originCmt = user.comment;});
					_this.users.student = data.students;
					_this.users.student.forEach((user)=>{user.originCmt = user.comment;});
				},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});

			$('.ui.radio.checkbox').checkbox();
			$('select.dropdown').dropdown();
		}
	}
</script>
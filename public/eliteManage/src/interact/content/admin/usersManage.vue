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
				@click="$broadcast('add')">
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
			<table v-show="role=='teacher'" class="ui orange selectable celled table">
				<thead class="center aligned">
					<tr> 
						<th width="40">ID</th> 
						<th width="80">姓名</th> 
						<th>学校</th> 
						<th>实验室</th> 
						<th>备注</th> 
						<th width="100">email</th> 
						<th width="100">QQ</th> 
						<th width="70">编辑</th> 
						<th width="70">删除</th> 
					</tr>
				</thead>
				<tbody @mouseleave="users.rowIndex=-1">
					<tr v-for="user in users.teacher" 
						v-show="match(user)"
						class="center aligned"
						@mouseenter="users.rowIndex=$index">
						<td v-text="user.id"></td>
						<td v-text="user.name"></td>
						<td v-text="user.school"></td>
						<td v-text="user.laboratory"></td>
						<td v-text="user.comment"></td>
						<td v-text="user.email"></td>
						<td v-text="user.qq"></td>
						
						<td>
							<button class="ui icon basic circular button" 
								:class="{orange:users.rowIndex==$index}"
								@click="toEdit(user)" >
								<i class="edit icon"></i>
							</button>
						</td>
						<td>
							<button class="ui icon basic circular button" 
								:class="{orange:users.rowIndex==$index}"
								@click="removeUser(user.id,$index)" >
								<i class="remove user icon"></i>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
			<table v-show="role=='student'" class="ui orange selectable celled table">
				<thead class="center aligned">
					<tr> 
						<th width="40">ID</th> 
						<th width="80">姓名</th> 
						<th>学校</th> 
						<th>方向</th> 
						<th>备注</th> 
						<th width="100">email</th> 
						<th width="100">QQ</th> 
						<th width="100">电话</th> 
						<th width="100">微信</th> 
						<th width="70">编辑</th> 
						<th width="70">删除</th> 
					</tr>
				</thead>
				<tbody @mouseleave="users.rowIndex=-1">
					<tr v-for="user in users.student" 
						v-show="match(user)"
						class="center aligned"
						@mouseenter="users.rowIndex=$index">
						<td v-text="user.id"></td>
						<td v-text="user.name"></td>
						<td v-text="user.school"></td>
						<td v-text="user.direction"></td>
						<td v-text="user.comment"></td>
						<td v-text="user.email"></td>
						<td v-text="user.qq"></td>
						<td v-text="user.phone"></td>
						<td v-text="user.wechat"></td>
						
						<td>
							<button class="ui icon basic circular button" 
								:class="{orange:users.rowIndex==$index}"
								@click="toEdit(user)" >
								<i class="edit icon"></i>
							</button>
						</td>
						<td>
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

	<add-user></add-user>
	<edit-user :user="nowEdit" :role="role"></edit-user>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			role:'teacher',
			options:{name:'姓名',school:'学校',id:'编号'},
			filter: { keyword:'', field:'name'},
			users:
			{
				rowIndex:-1,
				teacher: [{id:'',name:'',username:'',school:'',laboratory:'',comment:'',email:'',qq:''}],
				student: [{id:'',name:'',username:'',school:'',direction:'',comment:'',email:'',qq:'',phone:'',wechat:''}]
			},
			nowEdit:{},
		}},
		vuex: {getters: {route: ({route})=>{return route;},}},
		components:
		{
			addUser: require('./manage/add.vue'),
			editUser:require('./manage/edit.vue'),
		},
		methods:
		{
			// 需要注意的是，删除用户可能也意味着清楚与之相关联的数据
			// 某些用户应当设置不允许删除
			removeUser(id,index)
			{
				// 删除需要给提示，同意才能继续
				var flag = confirm("是否确认删除 ？");
				if(!flag) return false;
				
				var _this = this;
				var route = this.route+'/removeUser';

				$.ajax(
				{
					type:'GET', url:route,
					data:{id:id,role:_this.role},
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data.msg);
						if(data.flag) _this.users[_this.role].splice(index,1);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			match(user)
			{
				var filter = this.filter;
				if(!filter.keyword) return true;
				if(!user[filter.field]) false;

				var str = ""+user[filter.field]
				return str.match(filter.keyword);
			},
			toEdit(user)
			{
				this.nowEdit = user;
				this.$broadcast('edit');
			},
			loadData()
			{
				var _this = this;
				var route = this.route+'/users';
				$.ajax(
				{
					type:'GET', url:route,
					success:(data)=>{
						_this.users.teacher = data.teachers;
						_this.users.student = data.students;
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			}
		},
		ready()
		{
			this.loadData();
			$('.ui.radio.checkbox').checkbox();
			$('select.dropdown').dropdown();
		}
	}
</script>
<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.v-teachers
{
	.mycard
	{
		padding:1px; background-color:white;
		>span{ display:block; margin:25px auto; text-align:center;}
		span.id
		{ 
			height:60px; width:60px; border:1px solid #E4E4E4;border-radius:50px; line-height:60px;
			color:#FE7E1B; font-size:18px;
		}
		span.name{ font-size:18px; color:black; }
	}
}
</style>
<template>
<div class="v-teachers">
	<filter-box :filter="filter" role="teacher"></filter-box>

	<div class="ui six cards">
		<a class="card orange" 
			v-for="teacher in teachers" 
			v-show="match(teacher)"
			@click="actionModal(teacher.id,$index)">
			<div class="image" style="overflow:hidden;">
			<div class="mycard">
				<span class="id" v-text="teacher.id"></span>
				<span class="name" v-text="teacher.name"></span>
			</div></div>
		</a>
	</div>

	<div class="ui modal" v-el:info>
		<div class="header">导师信息</div>
		<div class="content">
			<div class="ui horizontal list">
				<div class="item">
					<i class="sort numeric ascending circular icon"></i>
					<div class="content"><span v-text="teachers[index].id"></span></div>
				</div>
				<div class="item">
					<i class="user circular icon"></i>
					<div class="content"><span v-text="teachers[index].name"></span></div>
				</div>
				<div class="item">
					<i class="university circular icon"></i>
					<div class="content"><span v-text="teachers[index].school"></span></div>
				</div>
				<div class="item">
					<i class="mail circular icon"></i>
					<div class="content"><span v-text="teachers[index].email"></span></div>
				</div>
				<div class="item">
					<i class="lab circular icon"></i>
					<div class="content"><span v-text="teachers[index].laboratory"></span></div>
				</div>
			</div>
			<template v-for="student in students">
				<h3 class="ui header">
					<i class="student icon"></i>
					<div class="content" v-text="student.name">Uptime Guarantee </div>
				</h3>
				<table class="ui orange selectable celled table">
					<thead>
						<tr> <th>日期</th> <th>导师</th> <th>导师内容</th> <th>导师时间</th> <th>学员任务</th> <th>完成度</th> </tr>
					</thead>
					<tbody>
						<tr v-for="task in student.tasks">
							<td v-text="task.up_time"></td>
							<td v-text="task.teacher==teachers[index].name?'当前导师':task.teacher"></td>
							<td v-text="task.discribe"></td>
							<td v-text="task.work_time*0.5+' h'"></td>
							<td v-text="task.mission"></td>
							<td v-text="task.progress?'完成':'未完成'"></td>
						</tr>
					</tbody>
				</table>
			</template>
		</div>
		<div class="actions">
			<div class="ui cancel button">取消</div>
		</div>
	</div>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			filter: { keyword:'', field:'name'},
			teachers: [{name:'',id:'',email:'',school:'',laboratory:''}],
			index:0,
			students: [{name:'',tasks: [{dicribe:'',mission:'',progress:'',work_time:'',up_time:''}]}]
		}},
		vuex:
		{
			getters: {route: ({route})=>{return route;},}
		},
		components:
		{
			filterBox:require('../filter.vue'),
		},
		methods:
		{
			actionModal(id,index)
			{
				this.index = index;
				this.getTeacherInfo(id);
				var _this = this;
				setTimeout(()=>{$(_this.$els.info).modal('show');},100);
			},
			getTeacherInfo(id)
			{
				var _this = this;
				var route = this.route+'/admin/teacherInfo';
				var data = {id:id};
				$.ajax(
				{
					type:'GET',
					url:route,
					data:data,
					success:(data)=>{ 
						_this.students = data;
						// data = JSON.stringify(data);
						// data = JSON.parse(data);
						// console.dir(data); 
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			match(teacher)
			{
				var filter = this.filter;
				if(!filter.keyword) return true;
				if(!teacher[filter.field]) false;

				var str = ""+teacher[filter.field]
				return str.match(filter.keyword);
			}
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/admin/teacherlist';
			$.ajax(
			{
				type:'GET',
				url:route,
				success:(data)=>{_this.teachers = data;},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});
		}
	}
</script>
<style lang="sass" scoped>
	* { font-family: 'PingFang SC','微软雅黑' ;}
.v-studentFinished
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
}
</style>
<template>
<div class="v-studentFinished">
	<div class="panel">
		<div class="ui header attached top">
			<div class="ui large buttons basic">
				<div class="ui button animated basic fade" tabindex="0" 
					v-for="student in students"
					:class="{orange:index==$index}"
					@click="getStudentInfo(student.id,$index)" >
					<div class="visible content"><i class="student icon"></i><span v-text="student.name"></span></div>
					<div class="hidden content">查看信息</div>
				</div>
			</div>
		</div>
		<div class="ui segment attached">
			<div class="ui horizontal list">
				<div class="item">
					<i class="sort numeric ascending circular icon"></i>
					<div class="content"><span v-text="students[index].id"></span></div>
				</div>
				<div class="item">
					<i class="user circular icon"></i>
					<div class="content"><span v-text="students[index].name"></span></div>
				</div>
				<div class="item">
					<i class="university circular icon"></i>
					<div class="content"><span v-text="students[index].school"></span></div>
				</div>
				<div class="item">
					<i class="idea circular icon"></i>
					<div class="content"><span v-text="students[index].direction"></span></div>
				</div>
			</div>
		</div>
		<div class="ui segment attached">
			<table class="ui orange selectable celled table">
				<thead>
					<tr> <th>日期</th> <th>导师</th> <th>导师内容</th> <th>导师时间</th> <th>学员任务</th> <th>完成度</th> </tr>
				</thead>
				<tbody>
					<tr v-for="task in tasks">
						<td v-text="task.up_time"></td>
						<td v-text="task.teacher"></td>
						<td v-text="task.discribe"></td>
						<td v-text="task.work_time*0.5+' h'"></td>
						<td v-text="task.mission"></td>
						<td v-text="task.progress?'完成':'未完成'"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
</template>

<script>

	module.exports =
	{
		data() {return {
			teacher:{name:''},
			students: [{name:'',school:'',direction:'',id:''}],
			tasks: [{dicribe:'',mission:'',progress:'',work_time:'',teacher:'',up_time:''}],
			index:0  // 判断当前操作对象是谁
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
			addTask() {},
			changeTask(index) {}, 
			deleteTask(index) {},
			getStudentInfo(id,index)
			{
				this.index = index;
				var _this = this;
				var route = this.route+'/teacher/studentInfo';
				var data = {id:id};
				$.ajax(
				{
					type:'GET',
					url:route,
					data:data,
					success:(data)=>{ _this.tasks = data; },
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			}
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/teacher/relatedStudents';
			var data = {id:this.myId,isfinish:1};
			$.ajax(
			{
				type:'GET',
				url:route,
				data:data,
				success:(data)=>{ 
					_this.students = data.students;
					_this.teacher = data.teacher; 
					
					_this.getStudentInfo(_this.students[_this.index].id,_this.index);
				},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});
		}
	}
</script>
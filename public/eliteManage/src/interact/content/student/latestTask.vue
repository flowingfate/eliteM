<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.v-latestTask
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
}
</style>

<template>
<div class="v-latestTask">
	<div class="panel">
		<div class="ui segment">
			<template v-for="teacher in teachers">
				<div class="ui horizontal list">
					<div class="item">
						<i class="user circular icon"></i>
						<div class="content"><span v-text="'导师：'+teacher.name"></span></div>
					</div>
					<div class="item">
						<i class="university circular icon"></i>
						<div class="content"><span v-text="'学校：'+teacher.school"></span></div>
					</div>
					<div class="item">
						<i class="lab circular icon"></i>
						<div class="content"><span v-text="'实验室：'+teacher.laboratory"></span></div>
					</div>
					<div class="item">
						<i class="mail circular icon"></i>
						<div class="content"><span v-text="'邮箱：'+teacher.email"></span></div>
					</div>
				</div>
			</template>
		</div>
	</div>
	<div class="panel">
		<h2 class="ui header attached top">最新任务 </h2>
		<div class="ui segment attached">
			<table class="ui orange selectable celled table">
				<thead>
					<tr> <th>日期</th> <th>导师</th> <th>任务内容</th> <th>完成度</th> </tr>
				</thead>
				<tbody>
					<tr v-for="task in nowtasks">
						<td v-text="task.up_time"></td>
						<td v-text="task.teacher"></td>
						<td v-text="task.mission"></td>
						<td v-text="task.progress?'完成':'未完成'"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<h2 class="ui header attached">历史任务 </h2>
		<div class="ui segment attached">
			<table class="ui orange selectable celled table">
				<thead>
					<tr> <th>日期</th> <th>导师</th> <th>任务内容</th> <th>完成度</th> </tr>
				</thead>
				<tbody>
					<tr v-for="task in histasks">
						<td v-text="task.up_time"></td>
						<td v-text="task.teacher"></td>
						<td v-text="task.mission"></td>
						<td v-text="task.progress?'完成':'未完成'"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<!-- <div class="ui segment attached"> </div> -->
	</div>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			teachers:[{name:'',school:'',laboratory:'',email:'',id:''}],
			nowtasks:[{mission:'',progress:'',up_time:''}],
			histasks:[{mission:'',progress:'',up_time:''}]
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
			
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/student/relatedInfo';
			var data = {id:this.myId};
			$.ajax(
			{
				type:'GET',
				url:route,
				data:data,
				success:(data)=>{
					_this.teachers = data.teachers;
					_this.nowtasks = data.nowtasks;
					_this.histasks = data.histasks;
				},
				error:()=>{ alert('something goes wrong!'); }
			});
		}
	}
</script>
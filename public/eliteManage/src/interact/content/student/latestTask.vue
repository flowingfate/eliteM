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
			<table class="ui selectable basic table">
				<tbody>
					<tr  v-for="teacher in teachers">
						<td width="100">导师信息：</td>
						<td width="100"><i class="user icon"></i><span v-text="teacher.name"></span></td>
						<td><i class="university icon"></i><span v-text="teacher.school"></span></td>
						<td><i class="lab icon"></i><span v-text="teacher.laboratory"></span></td>
						<td><i class="mail icon"></i><span v-text="teacher.email"></span></td>
						<td><i class="qq icon"></i><span v-text="teacher.qq"></span></td>
						<td width="130">
							<i class="star icon"
							:class="{olive:teacher.stars==2,green:teacher.stars==3,teal:teacher.stars>=4}"></i>
							<span>导师星级：</span><span v-text="teacher.stars"></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="panel">
		<h2 class="ui header attached top">最新任务 </h2>
		<div class="ui segment attached">
			<table class="ui orange center aligned selectable celled table">
				<thead>
					<tr> 
						<th width="140">日期</th> 
						<th width="120">导师</th> 
						<th>任务内容</th> 
						<th width="120">完成度</th> 
					</tr>
				</thead>
				<tbody>
					<tr v-for="task in nowtasks">
						<td v-text="task.up_time"></td>
						<td v-text="task.teacher"></td>
						<td class="left aligned" v-text="task.mission"></td>
						<td v-text="task.progress?'完成':'未完成'"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<h2 class="ui header attached">历史任务 </h2>
		<div class="ui segment attached">
			<table class="ui orange center aligned selectable celled table">
				<thead>
					<tr> 
						<th width="140">日期</th> 
						<th width="120">导师</th> 
						<th>任务内容</th> 
						<th width="120">完成度</th> 
					</tr>
				</thead>
				<tbody>
					<tr v-for="task in histasks">
						<td v-text="task.up_time"></td>
						<td v-text="task.teacher"></td>
						<td class="left aligned" v-text="task.mission"></td>
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
			var route = this.route+'/relatedInfo';
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
					_this.teachers = data.teachers;
					_this.nowtasks = data.nowtasks;
					_this.histasks = data.histasks;
				},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});
		}
	}
</script>
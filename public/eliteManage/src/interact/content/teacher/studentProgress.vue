<style lang="sass" scoped>
	* { font-family: 'PingFang SC','微软雅黑' ;}
.v-studentProgress
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
}
</style>
<template>
<div class="v-studentProgress">
	<div class="panel">
		<div class="ui header attached top">
			<div class="ui large buttons basic">
				<div class="ui button animated basic fade" tabindex="0" 
					v-for="student in students"
					:class="{orange:index==$index}"
					@click="getStudentInfo(student.id,$index)" >
					<div class="visible content">
						<i class="student icon"></i><span v-text="student.name"></span>
					</div>
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
			<div class="ui right floated animated orange fade button" 
				tabindex="0"
				@click="addTaskModal()">
				<div class="visible content">添加</div>
				<div class="hidden content">&nbsp;<i class="icon add circle"></i></div>
			</div>
		</div>
		<div class="ui segment attached">
			<table class="ui orange selectable celled table">
				<thead>
					<tr> 
						<th class="center aligned">日期</th> 
						<th>导师</th>
						<th>导师内容</th> 
						<th class="center aligned">导师时间</th> 
						<th>学员任务</th> 
						<th class="center aligned">完成度</th> 
						<th class="center aligned">编辑</th>
						<th class="center aligned">删除</th>
					</tr>
				</thead>
				<tbody @mouseleave="hoverTaskIndex=-1">
					<tr v-for="task in tasks" @mouseenter="hoverTaskIndex=$index">
						<td class="center aligned" v-text="task.up_time"></td>
						<td v-text="task.teacher==teacher.name?'自己':task.teacher"></td>
						<td v-text="task.discribe"></td>
						<td class="center aligned" v-text="task.work_time*0.5+' h'"></td>
						<td v-text="task.mission"></td>
						<td class="center aligned" v-text="task.progress?'完成':'未完成'"></td>
						<td class="center aligned">
							<button class="ui icon basic circular button" 
								:class="{orange:hoverTaskIndex==$index&&task.teacherId==myId,disabled:task.teacherId!=myId}"
								@click="editTaskModal($index)" >
								<i class="edit icon"></i>
							</button>
						</td>
						<td class="center aligned">
							<button class="ui icon basic circular button" 
								:class="{orange:hoverTaskIndex==$index&&task.teacherId==myId,disabled:task.teacherId!=myId}"
								@click="removeTask(task.id,$index)" >
								<i class="remove icon"></i>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="ui small modal" v-el:addtask>
		<div class="header">添加任务</div>
		<div class="content">
			<div class="ui form">
				<div class="field">
					<textarea type="text" v-model="taskinput.discribe" placeholder="工作内容" rows="2"></textarea>
				</div>
				<div class="field">
					<textarea type="text" v-model="taskinput.mission" placeholder="任务内容" rows="2"></textarea>
				</div>
				<div class="inline field">
					<select class="ui search dropdown" v-model="taskinput.work_time">
						<option value="1">0.5 小时</option>
						<option value="2">1 小时</option>
						<option value="3">1.5 小时</option>
						<option value="4">2 小时</option>
						<option value="5">2.5 小时</option>
						<option value="6">3 小时</option>
						<option value="7">3.5 小时</option>
						<option value="8">4 小时</option>
						<option value="9">4.5 小时</option>
						<option value="10">5 小时</option>
					</select>
					<div class="ui left pointing label" style="line-height:24px; font-size:14px;">
						选择工作时长
					</div>
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui cancel button">取消</div>
			<div class="ui netraul button">清空</div>
			<div class="ui approve button" @click="addTask">确认</div>
		</div>
	</div>
	<div class="ui small modal" v-el:edittask>
		<div class="header">修改任务</div>
		<div class="content">
			<div class="ui form">
				<div class="field">
					<textarea type="text" v-model="editTask.discribe" placeholder="工作内容" rows="2"></textarea>
				</div>
				<div class="field">
					<textarea type="text" v-model="editTask.mission" placeholder="任务内容" rows="2"></textarea>
				</div>
				<div class="inline field">
					<select class="ui search dropdown" v-model="editTask.work_time" >
						<option value="1">0.5 小时</option>
						<option value="2">1 小时</option>
						<option value="3">1.5 小时</option>
						<option value="4">2 小时</option>
						<option value="5">2.5 小时</option>
						<option value="6">3 小时</option>
						<option value="7">3.5 小时</option>
						<option value="8">4 小时</option>
						<option value="9">4.5 小时</option>
						<option value="10">5 小时</option>
					</select>
					<div class="ui left pointing label" style="line-height:24px; font-size:14px;">
						选择工作时长
					</div>
				</div>
			</div>
		</div>
		<div class="actions">
			<div class="ui cancel button">取消</div>
			<div class="ui netraul button">清空</div>
			<div class="ui approve button" @click="modifyTask">确认</div>
		</div>
	</div>
</div>
</template>

<script>	
	module.exports =
	{
		data() {return {
			teacher:{name:''},
			taskinput:{discribe:'',mission:'',work_time:1},
			students:[{name:'',school:'',direction:'',id:''}],
			tasks:[{discribe:'',mission:'',progress:'',work_time:'',teacher:'',teacherId:'',up_time:''}],
			editTask:{discribe:'',mission:'',work_time:'',id:''},
			hoverTaskIndex:-1,
			editTaskIndex:-1,
			index:0,  // 判断当前操作对象是谁
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
			addTaskModal() { $(this.$els.addtask).modal('show'); },
			editTaskModal(index) 
			{
				this.editTaskIndex = index;

				Object.keys(this.editTask).forEach((val)=>{
					this.editTask[val]=this.tasks[index][val];
				});
				$(this.$els.edittask).modal('show');
			},
			addTask()
			{
				var data = 
				{
					discribe : this.taskinput.discribe,
					mission : this.taskinput.mission,
					work_time : this.taskinput.work_time,
					student_id : this.students[this.index].id,
					teacher_id : this.myId,
				};
				// console.dir(data);
				var _this = this;
				var route = this.route+'/teacher/addTask';

				$.ajax(
				{
					type:'GET',
					url:route,
					data:data,
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						_this.taskinput={discribe:'',mission:'',work_time:1}
						setTimeout(()=>{window.location.reload();},2000);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			modifyTask()
			{
				var data = this.editTask;
					
				var flag = false;
				Object.keys(data).forEach((val)=>{
					if(data[val]!=this.tasks[this.editTaskIndex][val]) flag=true;
				});
				if(!flag)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'您好，您并没有做任何修改！'});
					return;
				}

				var _this = this;
				var route = this.route+'/teacher/modifyTask';

				$.ajax(
				{
					type:'GET',
					url:route,
					data:data,
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);

						Object.keys(_this.editTask).forEach((val)=>{
							_this.tasks[_this.editTaskIndex][val]=_this.editTask[val];
						});
						// 需要刷新页面获取新信息
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
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
			},
			removeTask(id,index)
			{
				var _this = this;
				var route = this.route+'/teacher/removeTask';

				$.ajax(
				{
					type:'GET',
					url:route,
					data:{id:id},
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						_this.tasks.splice(index,1);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/teacher/relatedStudents';
			var data = {id:this.myId,isfinish:0};
			$.ajax(
			{
				type:'GET',
				url:route,
				data:data,
				success:(data)=>{ 
					_this.students = data.students;
					_this.teacher = data.teacher; 
					_this.getStudentInfo(data['students'][_this.index].id,_this.index);
				},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});
			$('select.dropdown').dropdown();
		}
	}
</script>
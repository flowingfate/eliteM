<style lang="sass" scoped>
	* { font-family: 'PingFang SC','微软雅黑' ;}
.v-studentProgress
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
	.ui.pointing.label:before { z-index:1;}
}
</style>
<template>
<div class="v-studentProgress">
	<div class="panel">
		<div class="ui segment">
			<span>我的星级：</span>
			<div v-el:stars class="ui star rating"></div>
		</div>
	</div>
	<div class="panel">
		<div class="ui header attached top">
			<div class="ui celled horizontal list">
				<div class="item" style="border:none; padding:0.4em 0.2em;" 
					v-for="student in students">
					<div class="ui labeled button" tabindex="0" :data-tooltip="'上次联系学生在 '+student.time+' 天前'">
						<div class="ui button"
							@click="getStudentInfo(student.id,$index)"
							:class="{basic:index!=$index,orange:index==$index}">
							<i class="student icon"></i>
							<span v-text="student.name"></span>
						</div>
						<a class="ui basic left pointing label"
							:class="{
									green:student.time<7,
									yellow:student.time>=7&&student.time<15,
									red:student.time>=15
							}">{{student.time}}</a>
					</div>
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
				<div class="item">
					<i class="mail circular icon"></i>
					<div class="content"><span v-text="students[index].email"></span></div>
				</div>
				<div class="item">
					<i class="phone circular icon"></i>
					<div class="content"><span v-text="students[index].phone"></span></div>
				</div>
				<div class="item">
					<i class="wechat circular icon"></i>
					<div class="content"><span v-text="students[index].wechat"></span></div>
				</div>
				<div class="item">
					<i class="qq circular icon"></i>
					<div class="content"><span v-text="students[index].qq"></span></div>
				</div>
			</div>
			<div class="ui right floated animated orange fade button" 
				tabindex="0"
				@click="$broadcast('add')">
				<div class="visible content">添加任务</div>
				<div class="hidden content">&nbsp;<i class="icon add circle"></i></div>
			</div>
		</div>
		<!-- <div class="ui segment attached">
			<table class="ui olive center aligned selectable celled table">
				<thead>
					<tr> 
						<th width="120">备注来源</th> 
						<th>备注内容</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>管理员</td>
						<td></td>
					</tr>
					<tr>
						<td>教师</td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div> -->
		<div class="ui segment attached">
			<table class="ui orange center aligned selectable celled table">
				<thead>
					<tr> 
						<th width="110">日期</th> 
						<th width="80">导师</th> 
						<th>导师任务</th> 
						<th width="80">导师时间</th> 
						<th>学员任务</th> 
						<th width="110">deadline</th>
						<th width="60">编辑</th> 
						<th width="60">删除</th>
					</tr>
				</thead>
				<tbody @mouseleave="hoverTaskIndex=-1">
					<tr v-for="t in tasks" @mouseenter="hoverTaskIndex=$index">
						<td v-text="t.up_time"></td>
						<td v-text="t.teacherId==myId?'自己':'其他导师'"></td>
						<td v-text="t.discribe"></td>
						<td v-text="t.work_time*0.5+' h'"></td>
						<td v-text="t.mission"></td>
						<td v-text="t.deadline"></td>
						<td>
							<button class="ui icon basic circular button" 
								:class="{orange:hoverTaskIndex==$index&&t.teacherId==myId,disabled:t.teacherId!=myId}"
								@click="toEdit(t)" >
								<i class="edit icon"></i>
							</button>
						</td>
						<td>
							<button class="ui icon basic circular button" 
								:class="{orange:hoverTaskIndex==$index&&t.teacherId==myId,disabled:t.teacherId!=myId}"
								@click="removeTask(t.id,$index)" >
								<i class="remove icon"></i>
							</button>
						</td>
					</tr>
					<tr v-if="students[index].comment">
						<td colspan="9" class="left aligned">
							<span class="ui tag large label" v-text="'备注：'+students[index].comment"></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<addtask :stu-id="parseInt(students[index].id)"></addtask>
	<edittask :task="editTask"></edittask>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {
			teacher:{name:''},  //包括其他一堆信息
			
			students: [{name:'张小三',school:'',direction:'',id:'',comment:'',time:-1},],   //包括其他一堆信息
			tasks:[{id:'',discribe:'',mission:'',work_time:'',teacher:'',teacherId:'',up_time:'',deadline:''}],
			hoverTaskIndex:-1,
			editTask:{},
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
		components:
		{
			addtask: require('./progress/add.vue'),
			edittask:require('./progress/edit.vue'),
		},
		methods:
		{
			getStudentInfo(id,index)
			{
				this.index = index;
				var _this = this;
				var route = this.route+'/studentInfo';
				var data = {id:id};
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ _this.tasks = data; },
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			removeTask(id,index)
			{
				// 删除需要给提示，同意才能继续
				var flag = confirm("是否确认删除 ？");
				if(!flag) return false;
				
				var _this = this;
				var route = this.route+'/removeTask';

				$.ajax(
				{
					type:'GET', url:route, data:{id:id},
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						_this.tasks.splice(index,1);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			toEdit(task)
			{
				this.editTask = task;
				this.$broadcast('edit');
			},
			loadData()
			{
				var _this = this;
				var route = this.route+'/relatedStudents';
				var data = {id:this.myId,finish:0};
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ 
						if(data.type=='err')
						{
							_this.$store.dispatch('newMessage',data);
							return;
						}
						_this.students = data.students;
						_this.teacher = data.teacher;
						$(_this.$els.stars).rating({initialRating:data.teacher.stars, maxRating:5});
						$('.ui.rating').rating('disable');
						_this.getStudentInfo(data['students'][_this.index].id,_this.index);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			}
		},
		ready() { this.loadData();}
	}
</script>
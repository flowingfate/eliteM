<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.v-studentProgress
{
	.mycard
	{
		padding:1px; background-color:white;
		>span{ display:block; margin:20px auto; text-align:center;}
		span.id
		{ 
			height:50px; width:50px; border:1px solid #E4E4E4;border-radius:50px; line-height:50px;
			color:#FE7E1B; font-size:18px;
		}
		span.name{ font-size:18px; color:black; }
		span.teacher { color:#868686;font-size:14px; }
		&.noTeacher
		{
			span.id{ color:#E4E4E4; }
			span.teacher{ width:80%; border:1px solid #E4E4E4; padding:5px; border-radius:20px; }
		}
	}
}

</style>
<template>
<div class="v-studentProgress">
	
	<filter-box :filter="filter" role="student"></filter-box>
	
	<div class="ui six cards">
		<a class="card" :class="{orange:student.teachers.length!=0,grey:student.teachers.length==0}" 
			v-for="student in students" 
			v-show="match(student)"
			@click="actionModal(student.teachers,student.id,$index)">
			<div class="image" style="overflow:hidden;">
				<div class="mycard" :class="{noTeacher:student.teachers.length==0}">
					<span class="id" v-text="student.id"></span>
					<span class="name" v-text="student.name"></span>
					<span class="teacher" v-text="student.teachers.length!=0?teachersToStr(student.teachers):'未指派导师'">
					</span>
				</div>
			</div>
		</a>
	</div>
	<div class="ui modal" v-el:info>
		<div class="header">学员信息</div>
		<div class="content">
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
					<i class="doctor circular icon"></i>
					<div class="content"><span v-text="teachersToStr(students[index].teachers)"></span></div>
				</div>
			</div>
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
		<div class="actions">
			<div class="ui cancel button">OK</div>
		</div>
	</div>
	<div class="ui modal" v-el:set>
		<div class="header">指派导师</div>
		<div class="content">
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
					<i class="doctor circular icon"></i>
					<div class="content"><span v-text="students[index].teacher"></span></div>
				</div>
			</div>
			<table class="ui orange selectable celled table">
				<thead>
					<tr> <th>姓名</th> <th>学校</th> <th>实验室</th> <th>邮箱</th> <th>指派</th> </tr>
				</thead>
				<tbody>
					<tr v-for="teacher in teachers">
						<td v-text="teacher.name"></td>
						<td v-text="teacher.school"></td>
						<td v-text="teacher.laboratory"></td>
						<td v-text="teacher.email"></td>
						<td>
							<div class="ui slider checkbox">
								<input type="checkbox" :value="teacher.id" v-model="teacherIds">
								<label>选择导师</label>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="actions">
			<div class="ui cancel button" @click="teacherIds=[]">取消</div>
			<div class="ui approve button" @click="setTeacher(students[index].id,teacherIds)">确认</div>
		</div>
	</div>
</div>
</template>

<script>

	module.exports =
	{
		data() {return {

			filter: { keyword:'', field:'name'},
			students: [{name:'',school:'',direction:'',teachers:[],id:''}],
			index:0,
			tasks: [{dicribe:'',mission:'',progress:'',work_time:'',teacher:'',up_time:''}],
			teachers: [{name:'',email:'',school:'',laboratory:'',id:''}],
			teacherIds:[]
		}},
		vuex:
		{
			getters: {route: ({route})=>{return route;}, }
		},
		components:
		{
			filterBox:require('../filter.vue'),
		},
		methods:
		{
			teachersToStr(teachers) {return teachers.map((teacher)=>{return teacher.name}).join("、"); },
			actionModal(teachers,id,index)
			{
				var _this = this
				this.index = index;
				if(teachers.length==0) setTimeout(()=>{$(_this.$els.set).modal('show');},100);
				else 
				{
					this.getStudentInfo(id);
					setTimeout(()=>{$(_this.$els.info).modal('show');},100);
				}
			},
			setTeacher(studentId,teacherIds)
			{
				var data = {studentId:studentId,teacherIds:teacherIds};
				var _this = this;
				var route = this.route+'/admin/setTeacher';

				$.ajax(
				{
					type:'GET',
					url:route,
					data:data,
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						_this.teacherIds = [];
						setTimeout(()=>{window.location.reload();},2000);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'});}
				});
			},
			getStudentInfo(id)
			{
				var _this = this;
				var route = this.route+'/admin/studentInfo';
				var data = {id:id};
				$.ajax(
				{
					type:'GET',
					url:route,
					data:data,
					success:(data)=>{ 
						_this.tasks = data;
						// var d = JSON.stringify(data);
						// console.dir(JSON.parse(d));
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			match(student)
			{
				var filter = this.filter;
				if(!filter.keyword) return true;
				if(!student[filter.field]) return false;

				if(filter.field=='teachers')
				{
					var flag = false;
					student.teachers.forEach((teacher)=>{
						var str = ""+teacher.name;
						if(str.match(filter.keyword)) flag = true;
					});
					return flag;
				}

				var str = ""+student[filter.field];
				return str.match(filter.keyword);
			}
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/admin/progress';
			$.ajax(
			{
				type:'GET',
				url:route,
				success:(data)=>{_this.students = data;},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});

			$.ajax(
			{
				type:'GET',
				url:_this.route+'/admin/teacherlist',
				success:(data)=>{_this.teachers = data;},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'});}
			});

			$('.ui.checkbox').checkbox();
		}
	}
</script>
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
			<div class="extra content">
				<i class="star icon" 
    			:class="{olive:teacher.stars==2,green:teacher.stars==3,teal:teacher.stars>=4}"></i> 
    			<span>导师星级：</span><span v-text="teacher.stars"></span> 
			</div>
		</a>
	</div>

	<div class="ui modal" v-el:info>
		<div class="header">导师信息</div>
		<div class="content" style="padding:21px 21px 0;">
			<div class="ui styled fluid accordion">
				<div class="title">
					<i class="dropdown icon"></i>
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
				</div>
				<div class="content">
					<div class="ui message">
						<div class="header" style="border-bottom:1px solid #DFE6EF;padding: 0 0 8px">基础信息</div>
						<ul class="list">
							<li>导师姓名：{{teachers[index].name}}</li>
							<li>导师学校：{{teachers[index].school}}</li>
							<li>研究方向：{{teachers[index].laboratory}}</li>
							<li>电子邮件：{{teachers[index].email}}</li>
							<li>导师星级：<i class="star icon" :class="{olive:teachers[index].stars==2,green:teachers[index].stars==3,teal:teachers[index].stars>=4}"></i>{{teachers[index].stars}}</li>
						</ul>
					</div>
					<div class="ui message" v-if="teachers[index].paper">
						<div class="header" style="border-bottom:1px solid #DFE6EF;padding: 0 0 8px">论文背景</div>
						<ul class="list">
							<li track-by="$index" v-for="p in teachers[index].paper.split('\n')">{{p}}</li>
						</ul>
					</div>
					<div class="ui message" v-if="teachers[index].research">
						<div class="header" style="border-bottom:1px solid #DFE6EF;padding: 0 0 8px">科研背景</div>
						<ul class="list">
							<li track-by="$index" v-for="r in teachers[index].research.split('\n')">{{r}}</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<br/>
		<h2 class="ui header">
			<div class="content">学员进度</div>
			<div @click="toggle($els.progress)" class="ui button right floated">展开/收起</div>
		</h2>
		<div class="content" v-el:progress>
			<!-- 这里是正在进行的学员 -->
			<template v-for="stu in students.progress">
				<h3 class="ui header">
					<i class="student icon"></i>
					<div class="content" v-text="stu.id+'-'+stu.name"></div>
				</h3>
				<table class="ui orange selectable celled table">
					<thead>
						<tr> 
							<th width="110">日期</th> 
							<th width="80">导师</th> 
							<th>导师内容</th> 
							<th width="80">导师时间</th> 
							<th>学员任务</th> 
							<th width="110">deadline</th> 
						</tr>
					</thead>
					<tbody>
						<tr v-for="task in stu.tasks">
							<td v-text="task.up_time"></td>
							<td v-text="task.teacher_id==teachers[index].id?'当前导师':'其他导师'"></td>
							<td v-text="task.discribe"></td>
							<td v-text="task.work_time*0.5+' h'"></td>
							<td v-text="task.mission"></td>
							<td v-text="task.deadline"></td>
						</tr>
					</tbody>
				</table>
			</template>
		</div>
		
		<h2 class="ui header">
			<div class="content">结项学员</div>
			<div @click="toggle($els.finish)" class="ui button right floated">展开/收起</div>
		</h2>
		<div class="content" v-el:finish>
			<!-- 这里是已经结项的的学员 -->
			<template v-for="stu in students.finish">
				<h3 class="ui header">
					<i class="student icon"></i>
					<div class="content" v-text="stu.id+'-'+stu.name"></div>
				</h3>
				<table class="ui orange selectable celled table">
					<thead>
						<tr> 
							<th width="110">日期</th> 
							<th width="80">导师</th> 
							<th>导师内容</th> 
							<th width="80">导师时间</th> 
							<th>学员任务</th> 
							<th width="110">deadline</th>  
						</tr>
					</thead>
					<tbody>
						<tr v-for="task in stu.tasks">
							<td v-text="task.up_time"></td>
							<td v-text="task.teacher_id==teachers[index].id?'当前导师':'其他导师'"></td>
							<td v-text="task.discribe"></td>
							<td v-text="task.work_time*0.5+' h'"></td>
							<td v-text="task.mission"></td>
							<td v-text="task.deadline"></td>
						</tr>
					</tbody>
				</table>
			</template>
		</div>
		<div class="actions">
			<div class="ui cancel button"> OK </div>
		</div>
	</div>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			filter: { keyword:'', field:'name'},
			teachers: [{name:'',id:'',email:'',school:'',laboratory:'',stars:1,research:'',paper:''}],
			index:0,
			students: 
			{
				progress:[{id:'',name:'',tasks: [{dicribe:'',mission:'',work_time:'',up_time:'',teacher_id:''}]}],
				finish:[{id:'',name:'',tasks: [{dicribe:'',mission:'',work_time:'',up_time:'',teacher_id:''}]}],
			},
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
				var route = this.route+'/teacherInfo';
				var data = {id:id};
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ 
						if(data.type=='err')
						{
							_this.$store.dispatch('newMessage',data);
							return;
						}
						_this.students = data;
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
			},
			toggle(el){ $(el).slideToggle(); },
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/teacherlist';
			$.ajax(
			{
				type:'GET', url:route,
				success:(data)=>{_this.teachers = data;},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});

			$('.ui.accordion').accordion();
		}
	}
</script>
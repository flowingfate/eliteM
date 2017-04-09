<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
</style>
<template>
<div class="ui modal" v-el:set>
	<div class="header">指派导师</div>
	<div class="content">
		<div class="ui horizontal list">
			<div class="item">
				<i class="sort numeric ascending circular icon"></i>
				<div class="content"><span v-text="student.id"></span></div>
			</div>
			<div class="item">
				<i class="user circular icon"></i>
				<div class="content"><span v-text="student.name"></span></div>
			</div>
			<div class="item">
				<i class="university circular icon"></i>
				<div class="content"><span v-text="student.school"></span></div>
			</div>
			<div class="item">
				<i class="idea circular icon"></i>
				<div class="content"><span v-text="student.direction"></span></div>
			</div>
		</div>
		<table class="ui orange selectable celled table">
			<thead>
				<tr> <th>姓名</th> <th>学校</th> <th>研究方向</th> <th>邮箱</th> <th>指派</th> </tr>
			</thead>
			<tbody>
				<tr v-for="teacher in teachers">
					<td v-text="teacher.name"></td>
					<td v-text="teacher.school"></td>
					<td v-text="teacher.laboratory"></td>
					<td v-text="teacher.email"></td>
					<td>
						<div class="ui slider checkbox">
							<input v-if="disList[teacher.id]" disabled type="checkbox" :value="teacher.id" v-model="teacherIds">
							<input v-else type="checkbox" :value="teacher.id" v-model="teacherIds">
							<label>选择导师</label>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="actions">
		<div class="ui cancel button">取消</div>
		<div class="ui approve button" @click="setTeacher(student.id,teacherIds)">确认</div>
	</div>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {
			
			teachers: [{id:'',name:'',email:'',school:'',laboratory:''}],
			teacherIds:[],
			disList:{}
		}},
		vuex: { getters: { route: ({route})=>{return route;}, } },
		props:
		{
			student:{type:Object,default:()=>{return {id:'',name:'',school:'',direction:'',numThr:'',teachers:[{id:'',name:'',time:''}]}; }},
		},
		watch:
		{
			'student':function()
			{
				var Ts=this.student.teachers, arr=[], list={};
				Ts.forEach((T)=>{
					arr.push(T.id); 
					if(T.time==0) list[T.id] = false;
					else list[T.id] = true;
				});
				this.teacherIds = arr;
				this.disList = list;
			}
		},
		methods:
		{
			setTeacher(studentId,teacherIds)
			{
				// 添加导师无条件允许
				// 删除导师只能是没有发布过任务的老师
				var data = {studentId:this.student.id,teacherIds:teacherIds};
				var _this = this;
				var route = this.route+'/setTeacher';

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						if(data.type=='err') return;
						setTimeout(()=>{_this.$parent.loadData();},2000);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
		},
		events:
		{
			'showSet':function()
			{
				var _this = this;
				setTimeout(()=>{$(_this.$els.set).modal('show');},100);
			},
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/teacherlist';
			$.ajax(
			{
				type:'GET', url:route,
				success:(data)=>{_this.teachers = data;},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'});}
			});

			$('.ui.checkbox').checkbox();
		}
	}
</script>
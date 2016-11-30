<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
</style>
<template>
<div class="ui modal" v-el:info>
	<div class="header">
		<div class="ui right floated buttons">
			<button class="ui button" :class="{orange:!isOpen}" @click="toggle" v-text="isOpen?'收起设置':'结项设置'"></button>
			<div class="or"></div>
			<button class="ui orange button" @click="toSetTeacher">导师配置</button>
		</div>学员信息
	</div>
	<div class="content" style="display:none;" v-el:set>
		<div class="ui four cards" style="text-align:center;">
		    <div class="card" v-for="t in student.teachers">
		        <div class="content"><div class="header" v-text="t.name"></div></div>
		        <div v-if="t.time==-1" @click="setFinish(t.id,student.id,'revert')" class="ui bottom attached button"></i>恢复</div>
		        <div v-else @click="setFinish(t.id,student.id,'finish')" class="ui bottom orange attached button"></i>结项</div>
		    </div>
		</div>
	</div>
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
			<div class="item">
				<i class="call circular icon"></i>
				<div class="content"><span v-text="student.phone"></span></div>
			</div>
			<div class="item" v-for="t in student.teachers">
				<div class="ui basic label" :class="{orange:t.time!=-1}">
					<i class="doctor circular icon"></i><span v-text="t.name"></span>
					<i style="margin:0;cursor:pointer;" 
						class="toggle large icon" 
						:class="{on:showId[t.id],off:!showId[t.id]}"
						@click="showId[t.id]=!showId[t.id]"></i>
				</div>
			</div>
		</div>
		<table class="ui orange selectable celled table">
			<thead>
				<tr> 
					<th width="110">日期</th> 
					<th width="80">导师</th> 
					<th>导师内容</th> 
					<th width="80">导师时间</th> 
					<th>学员任务</th> 
					<th width="80">完成度</th> 
				</tr>
			</thead>
			<tbody>
				<tr v-for="task in tasks" v-show="showId[task.teacherId]">
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
</template>

<script>
	module.exports =
	{
		data() {return {
			isOpen:false,
			showId:{},
			tasks: [{dicribe:'',mission:'',progress:'',work_time:'',teacher:'',teacherId:'',up_time:''}],
		}},
		vuex: { getters: { route: ({route})=>{return route;}, } },
		props:
		{
			student:{type:Object,default:()=>{return {id:-1,name:'',school:'',direction:'',phone:'',numThr:'',teachers:[{id:'',name:'',time:''}]}; }},
		},
		methods:
		{
			getStudentInfo()
			{
				var _this = this;
				var route = this.route+'/studentInfo';
				if(this.student.id==-1) return false;
				var data = {id:this.student.id};
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ 
						_this.tasks = data;
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			toggle(){ this.isOpen=!this.isOpen; $(this.$els.set).slideToggle(); },
			toSetTeacher() { this.$parent.$broadcast('showSet'); },
			setFinish(t_id,s_id,flag)
			{
				// flag=>'finish'-------结项; flag=>'revert'--------恢复
				var _this = this;
				var route = this.route+'/setFinish';
				var data = {teacher_id:t_id,student_id:s_id,flag:flag};
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ 
						// 成功之后刷新页面，重新load所有data
						_this.$store.dispatch('newMessage',data);
						setTimeout(()=>{_this.$parent.loadData();},2000);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
		},
		watch:
		{
			'student':function()
			{
				var Ts = this.student.teachers, obj = {};
				Ts.forEach((T)=>{
					if(T.time==-1) obj[T.id]=false;
					else obj[T.id]=true;
				});
				this.showId = obj;
				this.getStudentInfo();
			}
		},
		events:
		{
			'showInfo':function()
			{
				var _this = this;
				setTimeout(()=>{$(_this.$els.info).modal('show');},100);
			},
		},
		ready() {}
	}
</script>
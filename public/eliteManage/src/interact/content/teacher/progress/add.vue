<style lang="sass" scoped>
	* { font-family: 'PingFang SC','微软雅黑' ;}

</style>
<template>

<div class="ui small modal" v-el:add>
	<div class="header">添加任务</div>
	<div class="content">
		<div class="ui form">
			<div class="field">
				<label>导师工作内容</label>
				<textarea type="text" v-model="task.discribe" placeholder="工作内容" rows="2"></textarea>
			</div>
			<div class="field">
				<label>学员任务内容</label>
				<textarea type="text" v-model="task.mission" placeholder="任务内容" rows="2"></textarea>
			</div>
			<div class="field">
				<label>工作时长</label>
				<div class="ui divider"></div>
				<div class="ui grid">
					<div class="three wide column" v-for="n in 10">
					<div class="ui radio checkbox">
						<input type="radio" v-model="task.work_time" :value="n+1" tabindex="0" class="hidden">
						<label v-text="(n+1)*0.5+' 小时'"></label>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="actions">
		<div class="ui cancel button">取消</div>
		<div class="ui netraul button" @click="clearInput">清空</div>
		<div class="ui approve button" @click="addTask">确认</div>
	</div>
</div>

</template>

<script>	
	module.exports =
	{
		data() {return {

			task:{discribe:'',mission:'',work_time:1},
		}},
		vuex:
		{
			getters:
			{
				route: ({route})=>{return route;},
				myId: ({myId})=>{return myId;}
			}
		},
		props:{ stuId:{type:Number,default:1} },
		methods:
		{
			clearInput()
			{
				Object.keys(this.task).forEach((k)=>{this.task[k]='';});
			},
			addTask()
			{
				var _this = this;
				var task = this.task;

				task.discribe = task.discribe||'无';
				task.mission = task.mission||'无';

				/**
				 * 检查表单是否为空
				 * 
				 var flag = (task.discribe!='')&&(task.mission!='')&&(task.work_time!='');

				if(!flag)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'表单内容都不能为空！'});
					return;
				}*/

				var data = Object.assign(this.task,{teacher_id:this.myId,student_id:this.stuId});
				
				var route = this.route+'/addTask';
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data.msg);
						_this.clearInput();
						_this.$parent.tasks.push(data.task);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
		},
		events:
		{
			'add':function()
			{
				var _this = this;
				setTimeout(()=>{$(_this.$els.add).modal('show');},100);
			},
		},
		ready() 
		{ 
			$('select.dropdown').dropdown(); 
			$('.ui.radio.checkbox').checkbox();
		}
	}
</script>
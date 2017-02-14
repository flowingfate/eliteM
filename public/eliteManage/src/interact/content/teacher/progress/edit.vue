<style lang="sass" scoped>
	* { font-family: 'PingFang SC','微软雅黑' ;}

</style>
<template>

<div class="ui small modal" v-el:edit>
		<div class="header">修改任务</div>
		<div class="content">
			<div class="ui form">
				<div class="field">
					<textarea type="text" v-model="edit.discribe" placeholder="工作内容" rows="2"></textarea>
				</div>
				<div class="field">
					<textarea type="text" v-model="edit.mission" placeholder="任务内容" rows="2"></textarea>
				</div>
				<br/>
				<div class="field">
					<label>工作时长</label>
					<div class="ui divider"></div>
					<div class="ui grid">
						<div class="three wide column" v-for="n in 10">
						<div class="ui radio checkbox">
							<input type="radio" v-model="edit.work_time" :value="n+1" tabindex="0" class="hidden">
							<label v-text="(n+1)*0.5+' 小时'"></label>
						</div>
						</div>
					</div>
				</div>
				<br/>
				<div class="field">
					<label>Deadline</label>
					<div class="ui divider"></div>
					<div class="ui action right labeled input">
						<div class="ui basic label">选择日期</div>
						<input v-el:picker type="text" v-model="edit.deadline" />
						<button class="ui right labeled icon button"  @click="clearDate"><i class="remove icon"></i>清除</button>
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

</template>

<script>	

	const Flatpickr = require("flatpickr");
	const Chinese = require("flatpickr/dist/l10n/zh.js").zh;
	
	module.exports =
	{
		data() {return {

			edit:{discribe:'',mission:'',work_time:'',id:'',deadline:''},
			picker:null,
		}},
		props:{task:{type:Object,default:()=>{return {};}} },
		vuex:
		{
			getters:
			{
				route: ({route})=>{return route;},
				myId: ({myId})=>{return myId;}
			}
		},
		watch:
		{
			'task': function() { this.edit = Object.assign({},this.task); },
		},
		methods:
		{
			modifyTask()
			{
				var _this = this;
				var edit = this.edit;
				var task = this.task;

				edit.discribe = edit.discribe||'无';
				edit.mission = edit.mission||'无';
				edit.deadline = edit.deadline||'无';

				var flag = (edit.discribe!='')&&(edit.mission!='')&&(edit.work_time!='');
				if(!flag)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'表单内容都不能为空！'});
					return;
				}

				var obj = {};
				Object.keys(edit).forEach((k)=>{ if(task[k]!=edit[k]) obj[k]=edit[k]; });
				if(Object.keys(obj).length==0)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'您好，您并没有做任何修改！'});
					return;
				}

				var route = this.route+'/modifyTask';
				var data = Object.assign(obj,{id:task.id});

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{ 
						_this.$store.dispatch('newMessage',data);
						if(data.type=='err') return;
						Object.keys(obj).forEach((k)=>{ task[k] = obj[k]; });
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			clearDate() { this.edit.deadline = '无'; }
		},
		events:
		{
			'edit':function()
			{
				var _this = this;
				var D = this.edit.deadline;
				if(D&&(D!='无')) this.picker.setDate(D);
				setTimeout(()=>{$(_this.$els.edit).modal('show');},100);
			},
		},
		ready() 
		{ 
			$('select.dropdown').dropdown();
			this.picker = new Flatpickr(this.$els.picker,{
				locale: Chinese,
				allowInput: true,
				minDate: new Date(),
				maxDate: new Date().fp_incr(14),
				appendTo: $('.pickContain').get(0),
			});
		}
	}
</script>
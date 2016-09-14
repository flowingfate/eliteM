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
	module.exports =
	{
		data() {return {

			edit:{discribe:'',mission:'',work_time:'',id:''},
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
			'task':function() { this.edit = Object.assign({},this.task); }
		},
		methods:
		{
			modifyTask()
			{
				var _this = this;
				var edit = this.edit;
				var task = this.task;
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
						Object.keys(obj).forEach((k)=>{ task[k] = obj[k]; });
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
		},
		events:
		{
			'edit':function()
			{
				var _this = this;
				setTimeout(()=>{$(_this.$els.edit).modal('show');},100);
			},
		},
		ready() { $('select.dropdown').dropdown(); }
	}
</script>
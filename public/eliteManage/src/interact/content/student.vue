<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.panel-transition 
{ 
	transition:0.4s ease-in-out; position:absolute; width:100%;left:0; 
	opacity:1; top:0; 
}
.panel-enter,.panel-leave{ opacity:0; top:75px;}

.v-student
{
	margin:30px 50px; position:relative;
}
</style>
<template>
	<menu :menu="menu" :now-menu.sync="nowMenu"></menu>
	<div class="v-student">
		<component :is="nowMenu"  transition="panel"></component>
	</div>
</template>

<script>

	module.exports =
	{
		data() {return {
			
			menu:
			{
				latestTask:'最新任务',
				infoModify:'修改信息',
				library:'访问智库'
			},
			nowMenu:'library'
		}},
		vuex:
		{
			getters: { role: ({role})=>{return role;} }
		},
		components:
		{
			menu:require('./menu.vue'),
			latestTask:require('./student/latestTask.vue'),
			infoModify:require('./student/infoModify.vue'),
			library:require('./student/library/index.vue')
		}
	}
</script>
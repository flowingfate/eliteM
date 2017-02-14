<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.panel-transition 
{ 
	transition:0.4s ease-in-out; position:absolute; width:100%;left:0; 
	opacity:1; top:0; 
}
.panel-enter,.panel-leave{ opacity:0; top:75px;}

.v-teacher
{
	margin:30px 50px; position:relative;
}
.pickContain { position:absolute; z-index:10000; top:0; }
</style>
<template>
	<div class="pickContain"></div>
	<menu :menu="menu" :now-menu.sync="nowMenu"></menu>
	<div class="v-teacher">
		<component :is="nowMenu"  transition="panel"></component>
	</div>
</template>

<script>

	module.exports =
	{
		data() {return {
			
			menu:
			{
				studentProgress:'学员进度',
				studentFinished:'结项学员',
				infoModify:'修改信息',
			},
			nowMenu:'studentProgress'
			
		}},
		vuex:
		{
			getters: {role: ({role})=>{return role;} }
		},
		components:
		{
			menu:require('./menu.vue'),
			studentProgress:require('./teacher/studentProgress.vue'),
			studentFinished:require('./teacher/studentFinished.vue'),
			infoModify:require('./teacher/infoModify.vue')
		}
	}
</script>
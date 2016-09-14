<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.panel-transition 
{ 
	transition:0.4s ease-in-out; position:absolute; width:100%;left:0; 
	opacity:1; top:0; 
}
.panel-enter,.panel-leave{ opacity:0; top:75px;}

.v-admin
{
	margin:30px 50px; position:relative;
}
</style>
<template>
	<menu :menu="menu" :now-menu.sync="nowMenu"></menu>
	<div class="v-admin">
		<component :is="nowMenu" transition="panel"></component>
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
				teachers:'查看导师',
				usersManage:'账户管理'
			},
			nowMenu:'studentProgress'
		}},
		components:
		{
			menu:require('./menu.vue'),
			studentProgress:require('./admin/studentProgress.vue'),
			studentFinished:require('./admin/studentFinished.vue'),
			teachers:require('./admin/teachers.vue'),
			usersManage:require('./admin/usersManage.vue')
		}
	}
</script>
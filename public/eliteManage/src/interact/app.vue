<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
</style>
<template>
	<component :is="role"></component>
	<message></message>
</template>

<script>
	module.exports =
	{
		data() {return {
			
		}},
		components:
		{
			admin: require('./content/admin.vue'),
			teacher: require('./content/teacher.vue'),
			student: require('./content/student.vue'),
			message: require('./content/message.vue'),
		},
		vuex:
		{
			getters: { role: ({role})=>{return role;} }
		},
		ready()
		{
			// console.dir(JSON.parse(JSON.stringify(data)));
		}

	}
</script>
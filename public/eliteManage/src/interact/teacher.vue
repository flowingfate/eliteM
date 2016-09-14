<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
</style>
<template>
	<teacher></teacher>
	<message></message>
</template>

<script>
	module.exports =
	{
		data() {return {
			
		}},
		components:
		{
			teacher: require('./content/teacher.vue'),
			message: require('./content/message.vue'),
		},
		ready()
		{
			// console.dir(JSON.parse(JSON.stringify(data)));
		}
	}
</script>
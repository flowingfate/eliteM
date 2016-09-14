<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
</style>
<template>
	<admin></admin>
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
			message: require('./content/message.vue'),
		},
		ready()
		{
			// console.dir(JSON.parse(JSON.stringify(data)));
		}
	}
</script>
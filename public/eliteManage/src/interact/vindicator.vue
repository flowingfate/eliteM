<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
</style>
<template>
	<vindicator></vindicator>
	<message></message>
</template>

<script>
	// 限制显示的字符串的字数
	const Vue = require('vue');
	Vue.filter('wordsnum', (str,number)=>{
		if(!str) return "";
		return str.substr(0,number)+(str.length>number?'···':'');
	});
	// 设置显示的文件大小
	Vue.filter('filesize', (str)=>{
		if(!str) return ""; var num = parseInt(str);
		if(num<1024) return num+'b';
		else if(num<1024*1024) return parseInt(num/1024)+'kb';
		else return (num/1024/1024).toFixed(2)+'M';
	});
	module.exports =
	{
		data() {return {
			
		}},
		components:
		{
			vindicator: require('./content/vindicator/index.vue'),
			message: require('./content/message.vue'),
		},
		ready()
		{
			// console.dir(JSON.parse(JSON.stringify(data)));
		}
	}
</script>
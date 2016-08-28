<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-library
{
	position:relative;
	.subjects-transition {transition:0.4s ease-in-out; opacity:1;}
	.subjects-enter,.subjects-leave{ opacity:0; top:75px;}

	.subCont-transition 
	{ 
		transition:0.4s ease-in-out; position:absolute; width:100%;left:0; 
		opacity:1; top:0; 
	}
	.subCont-enter,.subCont-leave{ opacity:0; top:75px;}
}
</style>
<template>
<div class="v-library">
	<subjects :showcont.sync="showCont" v-show="!showCont" transition="subjects"></subjects>
	<subject-cont :showcont.sync="showCont" v-show="showCont" transition="subCont"></subject-cont>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			showCont:true
		}},
		components: 
		{	
			subjects:require('./subjects.vue'),
			subjectCont:require('./subjectCont.vue')
		},
		ready()
		{
			
		}
	}
</script>
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
	<subjects  @detail="toDetail" v-show="!showCont" transition="subjects"></subjects>
	<sub-cont @list="toList" :id="contId" v-show="showCont" transition="subCont"></sub-cont>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			showCont:false,
			contId:-1
		}},
		components: 
		{	
			subjects:require('./subject1s.vue'),
			subCont:require('./subCont.vue')
		},
		methods:
		{
			toDetail(id){ this.showCont = true; this.contId = parseInt(id); },
			toList(){ this.showCont = false; }
		},
		ready()
		{
			
		}
	}
</script>
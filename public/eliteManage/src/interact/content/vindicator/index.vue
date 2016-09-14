<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-head
{
	$H:75px;
	height:$H; background-color:#FBFCFD; box-shadow:0 0 6px rgba(0,0,0,0.1);
	h2{ height:$H;line-height:$H;color:#FE7E1B;text-align:center; }
}
.v-library
{
	margin:30px 50px; position:relative;
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
<div class="v-head">
	<h2> 智库内容维护 </h2>
</div>
<div class="v-library">
	<subject1s @detail="toDetail" v-show="!showCont" transition="subjects"></subject1s>
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
			subject1s:require('./subject1s.vue'),
			subCont:require('./subject2/index.vue'),
		},
		methods:
		{
			toDetail(id){ this.showCont = true; this.contId = parseInt(id); },
			toList(){ this.showCont = false; }
		},
		ready() { }
	}
</script>
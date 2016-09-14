<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-subject2
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
}
</style>
<template>
<div class="v-subject2">
	<profile :info="info"></profile>
	<files :files="files" :subid="parseInt(info.id)"></files>
	<classes :classes="classes" :subid="parseInt(info.id)"></classes>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			info:{ id:'',number:'',title:'',img_url:'',keywords:'',profile:'' },
			files:[{id:'',origin_name:'',author:'',description:'',type:'',size:''}],
			classes:[{id:'',title:'',url:'',time:'',img_url:''}]
		}},
		components:
		{
			profile: require('./profile.vue'),
			files: require('./files.vue'),
			classes: require('./classes.vue')
		},
		props:{ id:{type:Number,default:-1} },
		vuex: { getters: {route: ({route})=>{return route;}} },
		methods:
		{
			loadData()
			{
				if( this.id == -1 ) return false;
				var _this = this;
				var route = this.route+'/subject2';
				$.ajax(
				{
					type:'GET', url:route, data:{ id:this.id },
					success:(data)=>{
						_this.info = data.info;
						_this.files = data.files;
						_this.classes = data.classes;
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			}
		},
		watch: { 'id':function(){ this.loadData(); }, },
		ready() { this.loadData(); }
	}
</script>
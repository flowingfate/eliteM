<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-subjectCont
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
}
</style>
<template>
<div class="v-subjectCont">
	<div class="panel">
		<h2 class="ui header attached top">
			<div class="ui right floated animated orange fade button" tabindex="0" @click="$dispatch('list')">
				<div class="visible content">返回学科列表</div>
				<div class="hidden content">&nbsp;&nbsp;<i class="icon reply"></i></div>
			</div> 学科简介
		</h2>
		<div class="ui segment attached">
			<div class="ui divided items">
			    <div class="item">
			        <div class="image">
			            <img v-show="info.img_url" :src="info.img_url">
			            <img v-else src="./img/none.png">
			        </div>
			        <div class="content">
			            <div class="header" v-text="info.title"></div>
			            <div class="meta"><div class="ui divider" style="margin:7px 0;"></div></div>
			            <div class="description">
			                <p v-text="info.profile"></p>
			            </div>
			            <div class="extra">
							<template v-if="info.keywords">
				            	<div class="ui label" track-by="$index" v-for="word in kws">
				            		<i class="tag orange icon"></i><span v-text="word"></span>
				            	</div>
			            	</template>
						</div>
			        </div>
			    </div>
			</div>
		</div>
	</div>

	<div class="panel">
		<h2 class="ui header attached top">文件列表</h2>
		<div class="ui segment attached">
			<div class="ui cards">
			    <div class="card" v-for="file in files">
			        <div class="content">
			            <i class="right floated file outline big orange icon" :class="iconType[file.type]"></i>
			            <div class="header" v-text="file.origin_name"></div>
			            <div class="meta">
			            	<span v-text="file.author"></span>&nbsp;&nbsp;
		            		<span v-text="file.size|filesize"></span>
			            </div>
			            <div class="description" v-text="file.description"></div>
			        </div>
			        <div class="extra content">
			            <a :href="route+'/getfile/'+file.id" 
			            	class="ui basic green fluid button">下载</a>
			        </div>
			    </div>
			</div>
		</div>
	</div>

	<div class="panel">
		<h2 class="ui header attached top">公开课</h2>
		<div class="ui segment attached">
			<div class="ui five cards">
			    <div class="ui card" v-for="item in classes">
			        <div class="content" style="padding:8px 10px;">
			            <span><i class="film orange icon"></i><span v-text="item.title"></span></span>
					    <div class="meta"> <span class="date" v-text="'时长：'+item.time+'min'"></span> </div>
			        </div>
			        <a class="image" :href="item.url" target="_blank">
			        	<img v-if="item.img_url" :src="item.img_url">
			            <img v-else src="./img/none.png">
			        </a>
			    </div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			iconType:{ pdf:'pdf',ppt:'powerpoint',pptx:'powerpoint',doc:'word',docx:'word' },
			info:{ id:'',number:'',title:'',img_url:'',keywords:'',profile:'' }, kws:[],
			files:[{id:'',url:'',origin_name:'',author:'',description:'',type:'',size:''}],
			classes:[{id:'',title:'',url:'',time:'',img_url:''}]
		}},
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
		watch: 
		{ 
			'id':function(){ this.loadData(); }, 
			'info.keywords':function(val){  this.kws = val===null?[]:val.split('+');  }
		},
		ready() { this.loadData(); }
	}
</script>
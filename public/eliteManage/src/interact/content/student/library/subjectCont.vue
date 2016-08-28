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
			<div class="ui right floated animated orange fade button" 
				tabindex="0"
				@click="toSubjects">
				<div class="visible content">返回学科列表</div>
				<div class="hidden content">&nbsp;&nbsp;<i class="icon reply"></i></div>
			</div>
			学科简介
		</h2>
		<div class="ui segment attached">
			<div class="ui divided items">
			    <div class="item">
			        <div class="image">
			            <img src="./img/none.png">
			        </div>
			        <div class="content">
			            <a class="header">马克思主义</a>
			            <div class="meta"><div class="ui divider" style="margin:7px 0;"></div></div>
			            <div class="description">
			                <p>卡尔·海因里希·马克思（德语：Karl Heinrich Marx，公元1818年5月5日—公元1883年3月14日），19世纪德国著名作家、哲学家。马克思主义的创始人之一，第一国际的组织者和领导者，被称为全世界无产阶级和劳动人民的伟大导师。[1]  无产阶级的精神领袖，国际共产主义运动的先驱。马克思是德国伟大的思想家、政治家、哲学家、经济学家、革命家和社会学家。主要著作有《资本论》[2]  、《共产党宣言》等。</p>
			            </div>
			            <div class="extra">
							<div class="ui label"><i class="tag orange icon"></i>工人阶级</div>
							<div class="ui label"><i class="tag orange icon"></i>共产主义</div>
							<div class="ui label"><i class="tag orange icon"></i>革命精神</div>
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
			    <div class="card">
			        <div class="content">
			            <i class="right floated file excel outline big orange icon"></i>
			            <div class="header">文件名</div>
			            <div class="meta">作者</div>
			            <div class="description">关于本文件的简单介绍</div>
			        </div>
			        <div class="extra content">
			            <a class="ui basic green fluid button">下载</a>
			        </div>
			    </div>
			    <div class="card">
			        <div class="content">
			            <i class="right floated file pdf outline big orange icon"></i>
			            <div class="header">文件名</div>
			            <div class="meta">作者</div>
			            <div class="description">关于本文件的简单介绍</div>
			        </div>
			        <div class="extra content">
			            <a class="ui basic green fluid button">下载</a>
			        </div>
			    </div>
			</div>
		</div>
	</div>

	<div class="panel">
		<h2 class="ui header attached top">公开课</h2>
		<div class="ui segment attached">
			<div class="ui five cards">
			    <div class="ui card">
			        <div class="content" style="padding:8px 10px;">
			            <span><i class="film orange icon"></i>马克思主义中国化的百年变迁</span>
					    <div class="meta"> <span class="date">时长：140min</span> </div>
			        </div>
			        <div class="image"> <img src="./img/none.png"> </div>
			    </div>
			    <div class="ui card">
			        <div class="content" style="padding:8px 10px;">
			            <span><i class="film orange icon"></i>马克思主义中国化的百年变迁</span>
					    <div class="meta"> <span class="date">时长：140min</span> </div>
			        </div>
			        <div class="image"> <img src="./img/none.png"> </div>
			    </div>
			    <div class="ui card">
			        <div class="content" style="padding:8px 10px;">
			            <span><i class="film orange icon"></i>马克思主义中国化的百年变迁</span>
					    <div class="meta"> <span class="date">时长：140min</span> </div>
			        </div>
			        <div class="image"> <img src="./img/none.png"> </div>
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

			fileType:['excel','image','pdf','powerpoint','text','word','archive']
			
		}},
		props: { showcont:{ type:Boolean,default:false} },
		vuex:
		{
			getters: {route: ({route})=>{return route;}}
		},
		methods:
		{
			toSubjects(){ this.showcont = false; }
		},
		ready()
		{
			
		}
	}
</script>
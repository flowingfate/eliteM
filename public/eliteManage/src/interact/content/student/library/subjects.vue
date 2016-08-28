<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-subjects
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
	.cards
	{
		img.content { padding:0; }
		.card>div.content { padding:10px; a.header{font-size:1.1em;}}
		.card>div.extra.content { padding:8px; }
	}
}
</style>
<template>
<div class="v-subjects">
	<filter-box :filter="filter"></filter-box>
	<div class="panel">
		<h2 class="ui header attached top">一级学科列表</h2>
		<div class="ui segment attached">
			<div class="ui styled fluid accordion">
				<div v-for="subject1 in subject1s" v-show="match1(subject1)">
			    <div class="title">
			    	<div class="ui horizontal large list">
						<div class="item">
							<i class="sort alphabet ascending circular icon orange"></i>
							<div class="content"><span v-text="subject1.number"></span></div>
						</div>
						<div class="item">
							<i class="sitemap circular icon orange"></i>
							<div class="content"><span v-text="subject1.title"></span></div>
						</div>
					</div>
			    </div>
			    <div class="content" style="padding-left:50px;">
			    	<h4 class="ui horizontal divider header"><i class="grid layout icon"></i>二级学科</h4>
			    	<div class="ui five cards">
			    		<div class="ui card link" 
			    			v-for="subject2 in subject1.subject2s" 
			    			v-show="match2(subject2)"
			    			@click="showSubjectCont(subject2.id)">
						    <div class="image">
						        <img :src="subject2.img_url" v-if="subject2.img_url!=null">
						        <img src="./img/img1.png" v-if="subject2.img_url==null">
						    </div>
						    <div class="content">
						        <a class="header" v-text="subject2.title"></a>
						        <div class="meta">
						            <span class="date">二级学科</span>
						        </div>
						    </div>
						    <div class="extra content">
						        <span><i class="sort alphabet ascending circular icon"></i><span v-text="'编号'+subject2.number"></span></span>
						    </div>
						</div>
						<div class="ui card" v-if="subject1.subject2s.length==0">
						    <div class="image"> <img src="./img/none.png"> </div>
						    <div class="content"> <a class="header">没有内容</a> </div>
						</div>
			    	</div>
			    </div>
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

			filter:{keyword1:'',field1:'title',keyword2:'',field2:'title'},
			subject1s:
			[
				{id:'',number:'',title:'',subject2s:[{id:'',number:'',title:'',img_url:'',profile:''}]},
			],
		}},
		components: {filterBox:require('./filter.vue')},
		props: { showcont:{ type:Boolean,default:false} },
		vuex:
		{
			getters: {route: ({route})=>{return route;}}
		},
		methods:
		{
			match1(sub1)
			{
				var filter = this.filter;
				if(!filter.keyword1) return true;
				if(!sub1[filter.field1]) return false;

				var str = ""+sub1[filter.field1];
				return str.match(filter.keyword1);
			},
			match2(sub2)
			{
				var filter = this.filter;
				if(!filter.keyword2) return true;
				if(!sub2[filter.field2]) return false;

				var str = ""+sub2[filter.field2];
				return str.match(filter.keyword2);
			},
			showSubjectCont(id)
			{
				this.showcont = true;
			}
		},
		ready()
		{
			var _this = this;
			var route = this.route+'/student/subjects';
			$.ajax(
			{
				type:'GET',
				url:route,
				success:(data)=>{_this.subject1s = data;},
				error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
			});

			$('.ui.accordion').accordion();
		}
	}
</script>
<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-subjects
{
	.panel
	{
		width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1);padding:0;
		background-color:white; border-radius:3px; margin-bottom:20px;
	}
}
</style>
<template>
<div class="v-subjects">
	<div class="panel">
		<h2 class="ui header attached top">学科列表</h2>
		
		<div class="ui segment attached">
			<div class="ui grid">
			    <div class="four wide stretched column">
			        <div class="ui vertical fluid pointing tabular menu">
					    <div class="item">
					        <div class="ui action fluid input">
					            <input type="text" placeholder="搜索..." v-model="filter.keyword">
					            <select class="ui compact selection dropdown" v-model="filter.field">
					                <option value="number">编号</option>
					                <option value="title">标题</option>
					            </select>
					        </div>
					    </div>
					    <a class="item" 
					    	v-for="sub1 in subject1s" 
					    	v-show="match(sub1)"
					    	@click="index=$index"
					    	:class="{active:$index==index,orange:$index==index}">
					    	<i class="grid layout icon"></i>
					    	<span v-text="(sub1.number+'：'+sub1.title) | wordsnum 10"></span>
					    </a>
					</div>
			    </div>
			    <div class="twelve wide stretched column">
				    <subject2s :subjects="subject1s[index].subject2s"></subject2s>
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

			filter:{keyword:'',field:'title'},
			subject1s: [{id:'',number:'',title:'',subject2s:[{id:'',number:'',title:'',img_url:'',keywords:'',profile:''}]},],
			index:0,
		}},
		components: 
		{
			subject2s: require('./subject2s.vue'),
		},
		vuex: {getters: {route: ({route})=>{return route;}} },
		methods:
		{
			match(sub1)
			{
				var filter = this.filter;
				if(!filter.keyword) return true;
				if(!sub1[filter.field]) return false;

				var str = ""+sub1[filter.field];
				return str.match(filter.keyword);
			},
			loadData()
			{
				var _this = this;
				var route = this.route+'/subject1s';
				$.ajax(
				{
					type:'GET', url:route,
					success:(data)=>{_this.subject1s = data;},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			}
		},
		ready()
		{
			this.loadData();
			$('.ui.accordion').accordion();
			$('select.dropdown').dropdown();
			$('.ui.dropdown').dropdown();
		}
	}
</script>
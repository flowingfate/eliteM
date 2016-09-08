<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
</style>
<template>
<div class="ui orange segment">
	<div class="ui segment">
	    <div class="ui action fluid input">
	        <input type="text" v-model="filter.keyword" placeholder="搜索...">
	        <select v-model="filter.field" class="ui compact selection dropdown">
	            <option value="number">编号</option>
	            <option value="title">标题</option>
	        </select>
	        <div class="ui left pointing label" style="line-height:24px; font-size:14px;">
				<span>直接输入过滤词，查看你感兴趣的学科</span>
			</div>
	    </div>
	</div> 

    <div class="ui three cards">
        <div class="card" v-for="sub2 in subjects" v-show="match(sub2)">
            <div class="content">
            	<img v-if="sub2.img_url" class="right floated mini ui image" :src="sub2.img_url">
                <img v-else class="right floated mini ui image" src="./img/none.png">
                <div class="header" v-text="sub2.title"></div>
                <div class="meta" v-text="'二级编号：'+sub2.number"></div>
                <div class="description" v-text="sub2.profile|wordsnum 45"></div>
            </div>
            <div class="extra content">
	            <div class="ui basic green fluid button" @click="toDetail(sub2.id)">查看详情</div>
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
		}},
		props: 
		{ 
			subjects:{type:Array,default:()=>{return [{id:'',number:'',title:'',img_url:'',keywords:'',profile:''},] }},
		},
		vuex: { getters: { route: ({route})=>{return route;} } },
		methods:
		{
			match(sub2)
			{
				var filter = this.filter;
				if(!filter.keyword) return true;
				if(!sub2[filter.field]) return false;

				var str = ""+sub2[filter.field];
				return str.match(filter.keyword);
			},
			toDetail(id) { this.$dispatch('detail',id); }
		},
		ready()
		{
			$('.ui.dropdown').dropdown();
		}
	}
</script>
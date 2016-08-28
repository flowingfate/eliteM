<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.v-filter
{
	width:100%;box-shadow:0 0 10px rgba(0,0,0,0.1); padding:20px;
	background-color:white; border-radius:3px; margin-bottom:40px;
}

</style>
<template>
	<div class="v-filter">
		<div class="ui action fluid input">
			<input type="text" placeholder="搜索一级学科" v-model="filter.keyword1">
			<select class="ui compact selection dropdown" v-model="filter.field1">
				<option value="number">编号</option>
				<option value="title">标题</option>
			</select>
			<div class="ui left pointing label" style="line-height:24px; font-size:14px;">
				<span>输入过滤词，查找你感兴趣的<span style="color:orange;">一级</span>学科</span>
			</div>
		</div>
		<div class="ui divider"></div>
		<div class="ui action fluid input">
			<input type="text" placeholder="搜索二级学科" v-model="filter.keyword2">
			<select class="ui compact selection dropdown" v-model="filter.field2">
				<option value="number">编号</option>
				<option value="title">标题</option>
			</select>
			<div class="ui left pointing label" style="line-height:24px; font-size:14px;">
				<span>输入过滤词，查找你感兴趣的<span style="color:orange;">二级</span>学科</span>
			</div>
		</div>
	</div>
</template>

<script>
	module.exports =
	{
		data() {return {
			
		}},
		props:
		{
			filter:{ type:Object,default:()=>{return {keyword1:'',field1:'title',keyword2:'',field2:'title'};}},
		},
		ready()
		{
			$('select.dropdown').dropdown();
		}
	}
</script>
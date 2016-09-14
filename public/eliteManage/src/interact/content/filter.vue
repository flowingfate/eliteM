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
			<input type="text" placeholder="搜索..." v-model="filter.keyword">
			<select class="ui compact selection dropdown" v-model="filter.field">
				<option v-for="(key,val) in options[role]"
						v-text="val"
						:value="key">
				</option>
			</select>
			<div class="ui left pointing label" style="line-height:24px; font-size:14px;">
				<span>直接输入过滤词，查看你感兴趣的人</span>
			</div>
		</div>
	</div>
</template>

<script>
	module.exports =
	{
		data() {return {
			options:
			{
				student: {name:'姓名',id:'编号',school:'学校',teachers:'导师',direction:'方向'},
				teacher: {name:'姓 名',id:'编 号',school:'学 校',laboratory:'实验室'},
			}
		}},
		props:
		{
			filter:{ type:Object,default:()=>{return {keyword:'',field:'name'};}},
			role: { type:String, default:"student" },
		},
		ready()
		{
			$('select.dropdown').dropdown();
		}
	}
</script>
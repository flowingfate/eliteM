<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-menu
{
	height:50px;
	background-color:#FBFCFD; box-shadow:0 0 6px rgba(0,0,0,0.1);

	ul 
	{
		height:100%; margin:0 auto; position:relative;
		li
		{
			float:left;height:100%;line-height:50px; width:150px; text-align:center;
			border-bottom:3px solid transparent; font-size:18px; cursor:pointer;
			transition:0.3s ease-in-out;
		}
		li.nowMenu { border-bottom-color: #FE7E1B; color:#FE7E1B;}
	}
}
</style>
<template>
	<div class="v-menu">
		<ul :style="{width:num*150+'px'}">
			<li v-for="(key,val) in menu"
				v-text="val"
				@click="nowMenu=key"
				:class="{nowMenu:key==nowMenu}" >
			</li>
		</ul>
	</div>
</template>

<script>
	module.exports =
	{
		data() {return {

		}},
		vuex:
		{
			getters:
			{
				role: ({role})=>{return role;}
			}
		},
		props:
		{
			menu:{ type:Object,default:()=>{return {};}},
			nowMenu: { type:String, default:"" }
		},
		computed:
		{
			num() 
			{
				var arr = Object.keys(this.menu)
				return arr.length;
			}
		},
	}
</script>
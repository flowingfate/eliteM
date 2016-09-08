<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
.v-message
{
	position:fixed; width:100%; left:0; bottom:50%; background-color:rgba(0,0,0,0.2);
	padding:10px 30px; text-align:right; overflow:hidden;
	span{ font-size:18px; line-height:40px; padding-right:10px;}
	span.ok { color:#22BA45; }
	span.err { color:#DB2828; }
}
.switch-transition {transition: 0.3s ease-in-out; height: 60px; opacity:1; }
.switch-enter, .switch-leave { height: 0; opacity: 0; }

</style>
<template>
<div class="v-message" 
	v-show="toshow"
	transition="switch"
	@mouseenter="keep" 
	@mouseleave="toClose">
	<button class="ui icon circular button right floated" 
		:class="[color[hereType]]"
		@click="close" >
		<i class="icon remove"></i>
	</button>
	<span :class="hereType" v-text="message.content"></span>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			toshow:false,
			color:{ok:'green',err:'red'},
			timer:{}
		}},
		vuex: 
		{
			getters: { message: ({message})=>{return message;} }
		},
		computed:
		{
			hereType()
			{
				var i = this.message.type.indexOf('?');
				if(i==-1) return this.message.type;
				return this.message.type.substr(0,i);
			}
		},
		methods:
		{
			close()
			{
				clearTimeout(this.timer);
				this.toshow = false;
			},
			toClose()
			{
				var _this = this;
				this.timer = setTimeout(()=>{_this.toshow=false;},2000);
			},
			keep()
			{
				clearTimeout(this.timer);
				this.toshow = true;
			}
		},
		watch:
		{
			'message.type':function(val) { this.toshow = true; clearTimeout(this.timer); this.toClose(); },
		}
	}
</script>
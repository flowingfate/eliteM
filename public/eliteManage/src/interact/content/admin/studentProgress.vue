<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.v-studentProgress
{
	.checker
	{
		position:fixed; z-index:100; $W:40px; $H:40px;
		width:$W; height:$H; top:50%; margin-top:-$H/2; right:0; line-height:$H;
		border-radius:$H 0 0 $H; border:1px solid #ffdac2; border-right:none;
		background-color:#F5F5F5; box-shadow:0 0 5px rgba(0,0,0,0.1);
		text-align:center; transition:0.3s ease-in-out; white-space:nowrap;
		&:hover{ width:100px; box-shadow:0 0 50px rgba(0,0,0,0.3); border-color:red; }
	}
}

</style>
<template>
<div class="v-studentProgress">
	
	<filter-box :filter="filter" role="student"></filter-box>
	
	<div class="checker">
		<div class="ui transparent input" style="margin-left:15px;">
			<input type="text" v-model="check">
		</div>
	</div>

	<div class="ui six cards">
		<div class="ui card" :class="{orange:stu.numThr!=0,grey:stu.numThr==0}"
			v-for="stu in students"
			v-show="match(stu)">
		    <div class="content" style="text-align:center;">
		    	<i class="right floated disabled user icon"></i>
		        <div class="header" style="margin-top:10px;">
					<span v-text="stu.id"></span>. <span v-text="stu.name"></span>
		        </div>
				<div class="meta"> <div class="ui divider"></div> </div>
				<div class="description">
					<div class="ui two column padded grid">
						<div class="column" style="padding:5px;" v-for="t in stu.teachers"> 
							<div class="ui fluid label" :class="{basic:t.time==-1,red:t.time>=check,yellow:t.time==0}" v-text="t.name"></div>
						</div>
					</div>
				</div>
		    </div>
		    <div class="extra content">
		        <div v-if="stu.numThr" class="ui basic orange circular fluid button" @click="showModal($index,'showInfo')">查看详情</div>
		        <div v-else class="ui basic circular fluid button" @click="showModal($index,'showSet')">指派导师</div>
		    </div>
		</div>
	</div>
	<info :student="students[index]"></info>
	<set :student="students[index]"></set>
</div>
</template>

<script>
	/**
	 * 目前全都采用全局数据重新拉去的策略
	 * 按理说应该针对单个学员的数据进行局部更新
	 * 但是由于牵涉到是否全部结项的判断，逻辑比较复杂，需求延后作为3.0版本优化
	 */
	module.exports =
	{
		data() {return {

			check:15,
			filter: { keyword:'', field:'name'},
			students: [{id:'',name:'',school:'',direction:'',phone:'',numThr:'',teachers:[{id:'',name:'',time:''}]}],
			index:0,
		}},
		vuex: { getters: { route: ({route})=>{return route;}, } },
		components: 
		{ 
			filterBox:require('../filter.vue'), 
			info:require('./progress/info.vue'),
			set:require('./progress/setTeacher.vue'),
		},
		methods:
		{
			showModal(index,event)
			{
				this.index = index;
				this.$broadcast(event);
			},
			match(student)
			{
				var filter = this.filter;
				if(!filter.keyword) return true;
				if(!student[filter.field]) return false;

				if(filter.field=='teachers')
				{
					var flag = false;
					student.teachers.forEach((teacher)=>{
						var str = ""+teacher.name;
						if(str.match(filter.keyword)) flag = true;
					});
					return flag;
				}

				var str = ""+student[filter.field];
				return str.match(filter.keyword);
			},
			loadData()
			{
				var _this = this;
				var route = this.route+'/progress';
				$.ajax(
				{
					type:'GET', url:route,
					success:(data)=>{_this.students = data;},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			}
		},
		ready()
		{
			this.loadData();
			$('.ui.checkbox').checkbox();
		}
	}
</script>
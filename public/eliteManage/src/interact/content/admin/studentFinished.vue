<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

.v-studentFinished
{

}

</style>
<template>
<div class="v-studentFinished">
	<filter-box :filter="filter" role="student"></filter-box>
	
	<div class="ui six cards">
		<div class="ui card orange"
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
							<div class="ui fluid basic label" v-text="t.name"></div>
						</div>
					</div>
				</div>
		    </div>
		    <div class="extra content">
		        <div class="ui basic orange circular fluid button" @click="showModal($index,'showInfo')">查看详情</div>
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

			filter: { keyword:'', field:'name'},
			students: [{id:'',name:'',school:'',direction:'',phone:'',comment:'',teachers:[]}],
			index:0,
		}},
		vuex: { getters:{ route:({route})=>{return route;}, } },
		components:
		{
			filterBox:require('../filter.vue'),
			info:require('./finished/info.vue'),
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
				var route = this.route+'/finished';
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
		}
	}
</script>
<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}
</style>
<template>
<div class="ui orange segment">
	
	<div class="ui form"><div class="fields" style="margin:0 7px;">
        <div class="three wide inline field">
            <div class="ui labeled input">
                <div class="ui basic label">二级编号</div>
                <input type="text" v-model="addSubject2.number" placeholder="编号">
            </div>
        </div>
        <div class="nine wide inline field">
            <div class="ui labeled input">
                <div class="ui basic label">二级学科名</div>
                <input type="text" v-model="addSubject2.title" placeholder="二级学科名">
            </div>
        </div>
        <div class="field four wide">
            <div class="ui fluid animated basic orange fade button" tabindex="0" @click="addSub2">
				<div class="visible content">添加二级学科</div>
				<div class="hidden content">&nbsp;&nbsp;<i class="icon add"></i></div>
			</div>
        </div>
    </div></div>

    <div class="ui divider"></div>

    
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
            <div class="ui bottom attached two buttons">
                <div class="ui basic green button" @click="toEdit($index,sub2.id)">编辑</div>
                <div class="ui basic red button" @click="rmSub2($index,sub2.id)">删除</div>
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
			addSubject2:{number:'',title:''}
		}},
		props: 
		{ 
			belongto:{ type:Number,default:1} ,
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
			addSub2()
			{
				var _this = this;
				var route = this.route+'/addSub2';
				var data = this.addSubject2;
				Object.assign(data,{subject1_id:this.belongto});

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{
						// 返回一个新对象，添加到学科列表数组中
						_this.$store.dispatch('newMessage',data.msg);
						if(data.msg.type=='err') return;
						_this.addSubject2 = {number:'',title:''};
						_this.subjects.push(data.subject);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			rmSub2(index,id)
			{
				// 删除学科必须要给提示，同意才能继续
				var flag = confirm("删除二级学科会清除所有下属内容，是否确认？");
				if(!flag) return false;

				var _this = this;
				var route = this.route+'/rmSub2';
				var data = {id:id};

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{
						_this.$store.dispatch('newMessage',data);
						_this.subjects.splice(index,1);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			toEdit(index,id) { this.$dispatch('detail',id); }
		},
		ready()
		{
			$('.ui.dropdown').dropdown();
		}
	}
</script>
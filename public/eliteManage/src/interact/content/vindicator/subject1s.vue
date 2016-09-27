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
		<h2 class="ui header attached top">一级学科列表</h2>
		<div class="ui attached segment">
			<div class="ui form"><div class="fields" style="margin:0 7px;">
		        <div class="three wide inline field">
		            <div class="ui labeled input">
		                <div class="ui basic label">一级编号</div>
		                <input type="text" v-model="addSubject1.number" placeholder="一级编号">
		            </div>
		        </div>
		        <div class="nine wide inline field">
		            <div class="ui labeled input">
		                <div class="ui basic label">一级学科名</div>
		                <input type="text" v-model="addSubject1.title" placeholder="一级学科名">
		            </div>
		        </div>
		        <div class="field four wide">
		            <div class="ui fluid animated black basic fade button" tabindex="0" @click="addSub1">
						<div class="visible content">添加一级学科</div>
						<div class="hidden content">&nbsp;&nbsp;<i class="icon add"></i></div>
					</div>
		        </div>
		    </div></div>
		</div>
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
					    	@click="switchEdit($index)"
					    	:class="{active:$index==index,orange:$index==index}">
					    	<i class="grid layout icon"></i>
					    	<span v-text="(sub1.number+'：'+sub1.title) | wordsnum 10"></span>
					    </a>
					</div>
			    </div>
			    <div class="twelve wide stretched column">
			    	<div class="ui segments">
				        <div class="ui segment">
				        	<div class="ui form"><div class="fields" style="margin:0 7px;">
							    <div class="three wide inline field">
							        <div class="ui labeled input">
										<div class="ui basic label">一级编号</div>
										<input type="text" v-model="editSubject1.number" placeholder="一级编号">
									</div>
							    </div>
							    <div class="nine wide inline field">
							        <div class="ui labeled input">
										<div class="ui basic label">一级学科名</div>
										<input type="text" v-model="editSubject1.title" placeholder="一级学科名">
									</div>
							    </div>
							    <div class="field four wide">
							    	<div class="ui two fluid buttons">
									    <div class="ui basic orange button" @click="chSub1">修改</div>
									    <div class="ui basic red button" @click="rmSub1">删除</div>
									</div>
							    </div>
							</div></div>
				        </div>
				        <subject2s :subjects="subject1s[index].subject2s" 
				        	:belongto="parseInt(subject1s[index].id)"></subject2s>
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

			filter:{keyword:'',field:'title'},
			subject1s: [{id:'',number:'',title:'',subject2s:[{id:'',number:'',title:'',img_url:'',keywords:'',profile:''}]},],
			editSubject1:{id:'',number:'',title:''},
			addSubject1:{number:'',title:''},
			index:0,
		}},
		components: { subject2s: require('./subject2s.vue'), },
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
			switchEdit(index)
			{
				this.index = index;
				var subject1 = this.subject1s[index]; 
				this.editSubject1.id = subject1.id;
				this.editSubject1.number = subject1.number;
				this.editSubject1.title = subject1.title;
			},
			addSub1()
			{
				var _this = this;
				var route = this.route+'/addSub1';
				var data = this.addSubject1;

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{
						// 添加一级学科比较特殊，所以重新load数据比较好
						_this.$store.dispatch('newMessage',data);
						if(data.type=='err') return;
						_this.addSubject1 = {number:'',title:''};
						setTimeout(()=>{_this.loadData();},2000);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			chSub1()
			{
				var o = this.subject1s[this.index];
				var e = this.editSubject1;

				var obj = {};
				Object.keys(e).forEach( (k)=>{if(e[k]!=o[k]) obj[k]=e[k];} );
				if(Object.keys(obj).length==0)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'您好，您并没有做任何修改！'});
					return;
				}

				var _this = this;
				var route = this.route+'/chSub1';
				var data = Object.assign(obj,{id:e.id});
				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{
						_this.$store.dispatch('newMessage',data);
						if(data.type=='err') return;
						o.number = _this.editSubject1.number;
						o.title = _this.editSubject1.title;
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			rmSub1()
			{
				// 删除学科必须要给提示，同意才能继续
				var flag = confirm("删除一级学科会清除所有下属内容，是否确认？");
				if(!flag) return false;

				var _this = this;
				var route = this.route+'/rmSub1';
				var data = {id:this.subject1s[this.index].id};

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(data)=>{
						_this.$store.dispatch('newMessage',data);
						if(data.type=='err') return;
						_this.subject1s.splice(_this.index,1);
						_this.switchEdit(_this.index!=0?_this.index-1:_this.index);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			loadData()
			{
				var _this = this;
				var route = this.route+'/subject1s';
				$.ajax(
				{
					type:'GET', url:route,
					success:(data)=>{_this.subject1s = data;_this.switchEdit(_this.index);},
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
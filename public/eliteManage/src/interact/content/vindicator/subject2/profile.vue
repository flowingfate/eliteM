<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

</style>
<template>
<div class="panel">
	<h2 class="ui header attached top">
		<div class="ui right floated animated orange fade button" tabindex="0" @click="$dispatch('list')">
			<div class="visible content">返回学科列表</div>
			<div class="hidden content">&nbsp;&nbsp;<i class="icon reply"></i></div>
		</div>
		学科简介
	</h2>
	<div class="ui segment attached">
		<div class="ui divided items">
		    <div class="item">
		        <div class="image">
		            <img v-if="!info.img_url" src="../img/none.png">
		            <img v-if="info.img_url" :src="info.img_url">
		        </div>
		        <div class="content">
		            <div class="header" v-text="info.title"></div>
		            <div class="meta"><div class="ui divider" style="margin:7px 0;"></div></div>
		            <div class="description"> <p v-html="filtStr(info.profile)"></p> </div>
		            <div class="extra">
		            	<template v-if="info.keywords">
			            	<div class="ui label" track-by="$index" v-for="word in kws">
			            		<i class="tag orange icon"></i><span v-text="word"></span>
			            	</div>
		            	</template>
						<div class="ui right floated basic green button"
							@click="toggle"
							:class="{green:!isEditOpen,grey:isEditOpen}"
							v-text="isEditOpen?'收起':'编辑'"></div>
					</div>
		        </div>
		    </div>
		</div>
	</div>
	<div class="ui segment attached" v-el:edit style="display:none;">
		<div class="ui form">
			<div class="fields">
				<div class="field two wide">
					<label>学科编号</label>
					<input type="text" v-model="editInfo.number" placeholder="学科编号">
				</div>
				<div class="field five wide">
					<label>学科名</label>
					<input type="text" v-model="editInfo.title" placeholder="学科名">
				</div>
				<div class="field nine wide">
					<label>关键词</label>
					<input type="text" v-model="editInfo.keywords" placeholder="关键词">
				</div>
			</div>
			<div class="field">
				<label>学科简介</label>
				<textarea v-model="editInfo.profile" placeholder="学科简介"></textarea>
			</div>
			<div class="ui middle aligned grid">
		        <div class="three wide column">
		            <form v-el:form enctype="multipart/form-data"><label>
						<label>图像</label>
						<input style="display:none" type="file" :accept="fileType" @change="getFile($event.currentTarget)">
						<img v-show="(!img.url)&&(!info.img_url)" class="ui fluid bordered rounded image" src="../img/none.png" />
						<img v-else class="ui fluid bordered rounded image" :src="img.url?img.url:info.img_url" />
					</label></form>
		        </div>
		        <div class="thirteen wide column">
		        	<h4 class="ui header">点击选择左侧选择新图片</h4>
					<div v-show="img.name" class="ui horizontal list">
                        <div class="item">
                            <i class="file image outline circular icon"></i>
                            <div class="content"><span v-text="img.name"></span></div>
                        </div>
                        <div class="item">
                            <i class="clone circular icon"></i>
                            <div class="content"><span v-text="img.type"></span></div>
                        </div>
                        <div class="item">
                            <i class="expand circular icon"></i>
                            <div class="content"><span v-text="img.size|filesize"></span></div>
                        </div>
                        <div class="item">
                            <div class="ui button" @click="clearFile">清除</div>
                        </div>
                    </div>
		        </div>
			</div>
			<div class="ui divider"></div>
			<div class="ui buttons fluid">
				<div class="ui button" @click="reset">复原</div>
				<div class="or"></div>
				<div class="ui positive button" @click="chInfo">保存</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
	module.exports =
	{
		data() {return {

			fileType:"image/png,image/jpeg",
			isEditOpen:false,
			kws:[],
			img:{name:'',type:'',size:'',url:'',file:''},
			editInfo:{ id:'',number:'',title:'',img_url:'',keywords:'',profile:''},
		}},
		vuex: {getters: {route: ({route})=>{return route;}} },
		props:{ info:{type:Object,default:()=>{return { id:'',number:'',title:'',img_url:'',keywords:'',profile:''}}}},
		watch:
		{
			'info':function()
			{ 
				this.editInfo = Object.assign({},this.info);
				this.img = {name:'',type:'',size:'',url:'',file:''};
				this.$els.form.reset();
			},
			'info.keywords':function(val) { this.kws = val===null?[]:val.split('+'); }
		},
		methods:
		{
			filtStr(str) { return str?str.replace(/\n/g,'<br/>').replace(/ /g,'&nbsp;'):''; },
			chInfo()
			{
				var _this = this;
				var route = this.route+'/chSub2';
				var data = new FormData(); data.append('id',this.editInfo.id);

				var flag = true;
				Object.keys(this.info).forEach((key)=>{ 
					if(this.info[key]!=this.editInfo[key]) 
					{
						flag=false;
						data.append(key,this.editInfo[key]);
					}
				});

				if(flag&&(!this.img.name)) 
				{
					this.$store.dispatch('newMessage',{type:'err',content:'没有做出任何修改'});
					return false;
				}

				if(this.img.name) data.append('file',this.img.file);
				
				$.ajax(
				{
					type:'POST', url:route, data:data,
					cache:false, processData:false, contentType:false,
					success:(data)=> {
						// 如果成功了，最好是返回一个新的info去替代原来父组件的info
						_this.$store.dispatch('newMessage',data.msg);
						if(data.msg.type=='err') return;
						_this.$parent.info = data.info;  //改变了info之后会自动触发更新，所以不必手动清空
						_this.$dispatch('profile',data.info);
					},
					error:()=>{_this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'});}
				}); return false;
			},
			getFile(val)
			{
				var _this = this;
				var file = val.files[0];
				if(file)
				{
					var reader = new FileReader();
					reader.onload = (e)=>{_this.img.url=e.target.result;};
					reader.readAsDataURL(file);

					_this.img.file = file;
					_this.img.name = file.name;
					_this.img.type = file.type;
					_this.img.size = file.size;
				}
			},
			toggle() {$(this.$els.edit).slideToggle();this.isEditOpen=!this.isEditOpen;},
			clearFile() { this.img = {name:'',type:'',size:'',url:'',file:''}; this.$els.form.reset(); },
			reset() { this.clearFile(); this.editInfo = Object.assign({},this.info); }
		},
		ready() { }
	}
</script>
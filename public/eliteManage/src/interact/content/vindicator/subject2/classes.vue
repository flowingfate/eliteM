<style lang="sass" scoped>
* { font-family: 'PingFang SC','微软雅黑' ;}

</style>
<template>
<div class="panel">
	<h2 class="ui header attached top">
		<div class="ui right floated basic button" 
			tabindex="0" 
			@click="toggle"
			:class="{orange:!isAddOpen,grey:isAddOpen}"
			v-text="isAddOpen?'收起':'添加'">
		</div> 公开课
	</h2>
	<div class="ui segment attached" v-el:add style="display:none;">
		<div class="ui form">
			<div class="fields">
				<div class="field two wide">
					<label>时长(min)</label>
					<input type="text" v-model="addClass.time" placeholder="时长">
				</div>
				<div class="field five wide">
					<label>课程名</label>
					<input type="text" v-model="addClass.title" placeholder="课程名">
				</div>
				<div class="field nine wide">
					<label>url地址</label>
					<input type="text" v-model="addClass.url" placeholder="url地址">
				</div>
			</div>
			<div class="ui middle aligned grid">
		        <div class="three wide column">
		            <form v-el:addform enctype="multipart/form-data"><label>
						<label>视频封面</label>
						<input style="display:none" type="file" :accept="fileType" @change="getFile($event.currentTarget,addImg)">
						<img v-show="!addImg.url" class="ui fluid bordered rounded image" src="../img/none.png" />
						<img v-show="addImg.url" class="ui fluid bordered rounded image" :src="addImg.url" />
					</label></form>
		        </div>
		        <div class="thirteen wide column">
		        	<h4 class="ui header">点击选择左侧选择新图片</h4>
					<div v-show="addImg.name" class="ui horizontal list" style="margin-bottom:20px;">
                        <div class="item">
                            <i class="file image outline circular icon"></i>
                            <div class="content"><span v-text="addImg.name"></span></div>
                        </div>
                        <div class="item">
                            <i class="clone circular icon"></i>
                            <div class="content"><span v-text="addImg.type"></span></div>
                        </div>
                        <div class="item">
                            <i class="expand circular icon"></i>
                            <div class="content"><span v-text="addImg.size|filesize"></span></div>
                        </div>
                        <div class="item">
                            <div class="ui button" @click="clearFile(addImg,$els.addform)">清除</div>
                        </div>
                    </div>
		        </div>
			</div>
			<div class="ui divider"></div>
			<div class="ui buttons fluid">
				<div class="ui button" @click="clearAdd">清空</div>
				<div class="or"></div>
				<div class="ui positive button" @click="classAdd">添加</div>
			</div>
		</div>
	</div>
	<div class="ui segment attached" v-el:edit style="display:none;">
		<div class="ui form">
			<div class="fields">
				<div class="field two wide">
					<label>时长(min)</label>
					<input type="text" name="number" v-model="editClass.time" placeholder="时长">
				</div>
				<div class="field five wide">
					<label>课程名</label>
					<input type="text" name="title" v-model="editClass.title" placeholder="课程名">
				</div>
				<div class="field nine wide">
					<label>url地址</label>
					<input type="text" name="keywords" v-model="editClass.url" placeholder="url地址">
				</div>
			</div>
			<div class="ui middle aligned grid">
		        <div class="three wide column">
		            <form v-el:editform enctype="multipart/form-data"><label>
						<label>视频封面</label>
						<input style="display:none" type="file" :accept="fileType" @change="getFile($event.currentTarget,editImg)">
						<img v-show="(!editImg.url)&&(!editClass.img_url)" class="ui fluid bordered rounded image" src="../img/none.png" />
						<img v-else class="ui fluid bordered rounded image" :src="editImg.url?editImg.url:editClass.img_url" />
					</label></form>
		        </div>
		        <div class="thirteen wide column">
		        	<h4 class="ui header">点击选择左侧选择新图片</h4>
					<div v-show="editImg.name" class="ui horizontal list" style="margin-bottom:20px;">
                        <div class="item">
                            <i class="file image outline circular icon"></i>
                            <div class="content"><span v-text="editImg.name"></span></div>
                        </div>
                        <div class="item">
                            <i class="clone circular icon"></i>
                            <div class="content"><span v-text="editImg.type"></span></div>
                        </div>
                        <div class="item">
                            <i class="expand circular icon"></i>
                            <div class="content"><span v-text="editImg.size|filesize"></span></div>
                        </div>
                        <div class="item">
                            <div class="ui button" @click="clearFile(editImg,$els.editform)">清除</div>
                        </div>
                    </div>
		        </div>
			</div>
			<div class="ui divider"></div>
			<div class="ui buttons fluid">
				<div class="ui button" @click="editCanel">返回</div>
				<div class="or"></div>
				<div class="ui positive button" @click="classModify">保存修改</div>
			</div>
		</div>
	</div>
	<div class="ui segment attached" v-el:list>
		<div class="ui five cards">
		    <div class="ui card" v-for="item in classes">
		        <div class="content" style="padding:8px 10px;">
		            <span><i class="film orange icon"></i><span v-text="item.title"></span></span>
				    <div class="meta"> <span class="date" v-text="'时长：'+item.time+'min'"></span> </div>
		        </div>
		        <div class="image"> 
		        	<img v-if="!item.img_url" src="../img/none.png"> 
		        	<img v-if="item.img_url" :src="item.img_url">
		        </div>
		        <div class="ui bottom attached two buttons">
	                <div class="ui basic green button" @click="switchEdit($index)">编辑</div>
	                <div class="ui basic red button" @click="classRm($index,item.id)">删除</div>
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

			isAddOpen:false,
			fileType:"image/png,image/jpeg",

			editIndex:0,
			editClass:{id:'',title:'a',url:'',time:'120',img_url:''},
			editImg:{name:'',type:'',size:'',url:'',file:''},

			addClass:{title:'',url:'',time:''},
			addImg:{name:'',type:'',size:'',url:'',file:''},
		}},
		props:
		{ 
			classes:{type:Array,default:()=>{return [{id:'',title:'a',url:'',time:'120',img_url:''}]}},
			subid:{type:Number,default:1},
		},
		vuex: {getters: {route: ({route})=>{return route;}} },
		watch:
		{
			'subid':function()
			{ 
				// 对数组元素的删除，添加和修改都会触发watch,所以最好是watch subid，而不是classes
				Object.keys(this.editImg).forEach((k)=>{this.editImg[k]="";});
				Object.keys(this.editClass).forEach((k)=>{this.editClass[k]="";});

				Object.keys(this.addImg).forEach((k)=>{this.addImg[k]="";});
				Object.keys(this.addClass).forEach((k)=>{this.addClass[k]="";});

				this.$els.addform.reset(); this.$els.editform.reset();
			}
		},
		methods:
		{
			toggle() {$(this.$els.add).slideToggle();this.isAddOpen=!this.isAddOpen;},
			editCanel() {$(this.$els.list).slideDown(); $(this.$els.edit).slideUp();},
			switchEdit(index)
			{
				this.editIndex = index;
				// 下面这部分写到watch里面或许会更好
				var item = this.classes[index]; 
				Object.keys(item).forEach((k)=>{this.editClass[k] = item[k];});
				Object.keys(this.editImg).forEach((k)=>{this.editImg[k]="";});
				this.$els.editform.reset();
				$(this.$els.edit).slideDown(); $(this.$els.list).slideUp();
			},
			classModify()
			{
				var _this = this;
				var route = this.route+'/chClass';
				var data = new FormData(); data.append('id',this.editClass.id);

				var flag = true , eClass = this.editClass;
				var item = this.classes[this.editIndex]; 
				Object.keys(eClass).forEach((k)=>{ 
					if(item[k]!=eClass[k]) { flag=false; data.append(k,eClass[k]); }
				});

				if(flag&&(!this.editImg.name)) 
				{
					this.$store.dispatch('newMessage',{type:'err',content:'没有做出任何修改'});
					return false;
				}
				if(this.editImg.name) data.append('img',this.editImg.file);

				$.ajax(
				{
					type:'POST', url:route, data:data,
					cache:false, processData:false, contentType:false,
					success:(data)=> {
						// 如果成功了，最好是返回一个新的class去替代原来的class
						_this.$store.dispatch('newMessage',data.msg);
						if(data.msg.type=='err') return;
						_this.classes.$set(_this.editIndex,data.item);
						_this.$els.editform.reset();
						Object.keys(_this.editImg).forEach((k)=>{_this.editImg[k]="";});
					},
					error:()=>{_this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'});}
				}); return false;
			},
			classRm(index,id)
			{
				var _this = this;
				var route = this.route+'/rmClass';
				var data = {id:id};

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(msg)=>{
						_this.$store.dispatch('newMessage',msg);
						if(msg.type=='err') return;
						_this.classes.splice(index,1);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			classAdd()
			{
				var _this = this;
				var route = this.route+'/addClass';
				var data = new FormData();

				var add=this.addClass;  var img=this.addImg;
				if(!add.title)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'课程名不能为空!'}); 
					return false;
				} 
				if(!add.url)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'url地址不能为空!'});
					return false;
				} 
				
				data.append('subject2_id',this.subid);
				data.append('time',add.time);
				data.append('title',add.title);
				data.append('url',add.url);
				data.append('img',img.file);
				
				$.ajax(
				{
					type:'POST', url:route, data:data,
					cache:false, processData:false, contentType:false,
					success:(data)=>{
						// 如果成功了，返回一个新的item，添加到数组里
						_this.$store.dispatch('newMessage',data.msg);
						_this.classes.push(data.item);  _this.clearAdd();
					},
					error:()=>{_this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'});}
				}); return false;
			},
			getFile(val,obj)
			{
				var file = val.files[0];
				if(file)
				{
					var reader = new FileReader();
					reader.onload = (e)=>{obj.url=e.target.result;};
					reader.readAsDataURL(file);
					obj.file = file;       obj.name = file.name; 
					obj.type = file.type;  obj.size = file.size;
				}
			},
			clearFile(img,form){ Object.keys(img).forEach((k)=>{img[k]="";}); form.reset(); },
			clearAdd()
			{
				var i=this.addImg, c=this.addClass, f=this.$els.addform;
				this.clearFile(i,f); Object.keys(c).forEach((k)=>{c[k]="";});
			}
		},
		ready() { }
	}
</script>
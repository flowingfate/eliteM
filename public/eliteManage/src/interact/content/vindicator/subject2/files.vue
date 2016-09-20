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
		</div> 文件列表
	</h2>

	<div class="ui segment attached" v-el:add style="display:none;">
		<div class="ui form">
			<div class="ui middle aligned divided grid">
		        <div class="fourteen wide column">
		        	<div class="fields">
						<div class="field two wide">
							<label>作者</label> <input type="text" v-model="addFile.author" placeholder="文件作者">
						</div>
						<div class="field fourteen wide">
							<label>简介</label> <input type="text" v-model="addFile.description" placeholder="关于文件的简单介绍">
						</div>
					</div>
					<div class="ui horizontal list" style="margin-bottom:15px;">
						<div class="item">
                            <form v-el:form enctype="multipart/form-data"><label class="ui right labeled button">
								<div class="ui icon button">&nbsp;<i class="file icon"></i>&nbsp;&nbsp;</div>
								<span class="ui basic left pointing label">点击此按钮选择要上传的文件</span>
								<input v-el:inputfile style="display:none" type="file" :accept="fileType" @change="getFile($event.currentTarget)">
							</label></form>
                        </div>
                        <div v-show="addFile.name" class="item">
                            <i class="file circular icon"></i>
                            <div class="content"><span v-text="addFile.name"></span></div>
                        </div>
                        <div v-show="addFile.name" class="item">
                            <i class="clone circular icon"></i>
                            <div class="content"><span v-text="addFile.type"></span></div>
                        </div>
                        <div v-show="addFile.name" class="item">
                            <i class="expand circular icon"></i>
                            <div class="content"><span v-text="addFile.size|filesize"></span></div>
                        </div>
                        <div v-show="addFile.name" class="item">
                            <div class="ui button" @click="clearAdd">清除</div>
                        </div>
                    </div>
		            <div v-show="addFile.name" class="ui indicating progress" :data-percent="progress">
						<div class="label" v-text="'上传进度：'+progress+'%'"></div>
						<div class="bar"><div class="progress"></div></div>
					</div>
		        </div>
		        <div class="two wide column">
			        <div class="ui fluid basic green button" @click="addfile">保存</div>
		        </div>
			</div>
		</div>
	</div>
	<div class="ui segment attached" v-el:edit style="display:none;">
		<div class="ui form">
			<div class="ui horizontal list">
                <div class="item">
                    <i class="file circular icon"></i>
                    <div class="content"><span v-text="files[editIndex]?files[editIndex].origin_name:''"></span></div>
                </div>
                <div class="item">
                    <i class="clone circular icon"></i>
                    <div class="content"><span v-text="files[editIndex]?files[editIndex].type:''"></span></div>
                </div>
                <div class="item">
                    <i class="expand circular icon"></i>
                    <div class="content"><span v-text="files[editIndex]?files[editIndex].size:0|filesize"></span></div>
                </div>
            </div>
            <div class="ui divider" style="margin:6px 0 10px;"></div>
        	<div class="fields">
				<div class="field two wide">
					<label>作者</label>
					<input type="text" v-model="editFile.author" placeholder="文件作者">
				</div>
				<div class="field fourteen wide">
					<label>简介</label>
					<input type="text" v-model="editFile.description" placeholder="关于文件的简单介绍">
				</div>
			</div>
	        <div class="ui divider"></div>
			<div class="ui buttons fluid">
				<div class="ui button" @click="editCanel">返回</div>
				<div class="or"></div>
				<div class="ui positive button" @click="fileModify">修改</div>
			</div>
		</div>
	</div>
	<div class="ui segment attached" v-el:list>
		<div class="ui cards">
		    <div class="card" v-for="file in files">
		        <div class="content">
		            <i class="right floated file outline big orange icon" :class="iconType[file.type]"></i>
		            <div class="header" v-text="file.origin_name"></div>
		            <div class="meta">
		            	<span v-text="file.author"></span>&nbsp;&nbsp;
		            	<span v-text="file.size|filesize"></span>
		            </div>
		            <div class="description" v-text="file.description"></div>
		        </div>
		        <div class="ui bottom attached two buttons">
	                <div class="ui basic green button" @click="switchEdit($index)">编辑</div>
	                <div class="ui basic red button" @click="rmFile($index,file.id)">删除</div>
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
			isEditOpen:false,
			progress:0,

			iconType:{ pdf:'pdf',ppt:'powerpoint',pptx:'powerpoint',doc:'word',docx:'word' },
			fileType:"application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation",
			
			addFile:{name:'',type:'',size:'',file:'',author:'',description:''},
			editFile:{id:'',author:'',description:''},
			editIndex:0,
		}},
		props:
		{ 
			files:{type:Array,default:()=>{return [{id:'',origin_name:'',author:'',description:'',type:'',size:''}]}},
			subid:{type:Number,default:1},
		},
		vuex: {getters: {route: ({route})=>{return route;}} },
		watch:
		{
			'subid':function()
			{ 
				// 对数组元素的删除，添加和修改都会触发watch,所以最好是watch subid，而不是classes
				Object.keys(this.addFile).forEach((k)=>{this.addFile[k]="";});
				Object.keys(this.editFile).forEach((k)=>{this.editFile[k]="";});
				this.$els.form.reset();
				this.progress=0; $('.ui.progress').progress('reset');
			}
		},
		methods:
		{
			switchEdit(index)
			{
				this.editIndex = index;
				// 以下这部分写到watch里面或许会更好
				var file = this.files[index]; 
				Object.keys(this.editFile).forEach((key)=>{this.editFile[key] = file[key];});
				$(this.$els.edit).slideDown(); $(this.$els.list).slideUp();
			},
			fileModify()
			{
				var _this = this;
				var route = this.route+'/chFile';
				var data = this.editFile;
				var file = this.files[this.editIndex];
				if(data.description==file.description&&data.author==file.author)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'没有做任何修改'});
					return false;
				}

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(msg)=>{
						_this.$store.dispatch('newMessage',msg);
						file.description=data.description;  file.author=data.author;
						$(_this.$els.list).slideDown(); $(_this.$els.edit).slideUp();
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			rmFile(index,id)
			{
				var _this = this;
				var route = this.route+'/rmFile';
				var data = {id:id};

				$.ajax(
				{
					type:'GET', url:route, data:data,
					success:(msg)=>{
						_this.$store.dispatch('newMessage',msg);
						_this.files.splice(index,1);
					},
					error:()=>{ _this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'}); }
				});
			},
			addfile()
			{
				var _this = this;
				var route = this.route+'/addFile';
				var data = new FormData();
				if(!this.addFile.file)
				{
					this.$store.dispatch('newMessage',{type:'err',content:'请选择要上传的文件'});
					return false;
				}
				data.append('subject2_id',this.subid);
				data.append('file',this.addFile.file);
				data.append('author',this.addFile.author);
				data.append('description',this.addFile.description);
				
				$.ajax(
				{
					type:'POST', url:route, data:data,
					cache:false, processData:false, contentType:false,
					xhr:()=>{
						var myXhr = $.ajaxSettings.xhr();
						if(myXhr.upload) myXhr.upload.addEventListener('progress',(ev)=>{
							_this.progress=ev.loaded/ev.total*100;
							$('.ui.progress').progress('set progress',_this.progress);
						}, false);  return myXhr;
					},
					success:(data)=> {
						// 如果成功了，返回一个新的file，添加到数组里
						_this.$store.dispatch('newMessage',data.msg);
						_this.files.push(data.file);
						_this.$els.form.reset();
						setTimeout(()=>{
							_this.progress=0; $('.ui.progress').progress('reset');
							Object.keys(_this.addFile).forEach((k)=>{_this.addFile[k]="";});
						},1500);
					},
					error:()=>{ 
						_this.$store.dispatch('newMessage',{type:'err',content:'请求出错了！'});
						_this.progress = 0; $('.ui.progress').progress('reset');
					}
				}); return false;
			},
			getFile(val)
			{
				var _this = this;
				var file = val.files[0];
				var type = {
					"application/pdf":"Pdf文件",
					"application/msword":"Word文件",
					"application/vnd.openxmlformats-officedocument.wordprocessingml.document":"Word文件",
					"application/vnd.ms-powerpoint":"PowerPoint文件",
					"application/vnd.openxmlformats-officedocument.presentationml.presentation":"PowerPoint文件"
				};
				if(file)
				{
					_this.addFile.file = file;
					_this.addFile.name = file.name;
					_this.addFile.type = type[file.type];
					_this.addFile.size = file.size;
				}
			},
			editCanel() {$(this.$els.list).slideDown(); $(this.$els.edit).slideUp();},
			toggle() {$(this.$els.add).slideToggle();this.isAddOpen=!this.isAddOpen;},
			clearAdd()
			{
				this.$els.form.reset(); var f=this.addFile;
				f.name = f.type = f.size = f.file = "";
			}
		},
		ready() { $('.ui.progress').progress(); }
	}
</script>
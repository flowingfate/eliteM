@extends('layout')

@section('title','登录')

@section('content')

	<div class="ui segment attached">
		<form id="file" action="{{url('up')}}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input name="file" type="file"/>
			<button>上传</button>
			<input type="submit" />
		</form>
	</div>

    <script>
    	$.ajaxSetup({headers:{'X-CSRF-TOKEN':"h9Uu6SryGIjSbU5awhwjquNojy0nHbg85XZW5twj"}});
    	$('#file button').click(function()
    	{
    		var fd = new FormData(document.getElementById("file"));
    		$.ajax(
			{
				type:'POST',
				url:"http://localhost:8080/up",
				processData:false,
				contentType: false,
				data:fd,
				beforeSend:(xhr)=>{
					// xhr.upload.onprogress=(ev)=>{_this.progress = ev.loaded/ev.total;}
					console.log(xhr.upload);
				},
				success:function(data){console.dir(JSON.parse(JSON.stringify(data)));},
				error:function(){}
			});

			return false;
    	});
    </script>
@endsection
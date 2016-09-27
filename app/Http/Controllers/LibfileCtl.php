<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Subject2;
use App\Models\Libfile;

use Storage;

class LibfileCtl extends Controller
{
    public function addFile(Request $request)
    {
    	if(!$request->hasFile('file')) return response()->json([
    		'file'=>[],
            'msg'=>['type'=>'err','content'=>'没有获取到上传的文件'],
    	]);

        $arr = [];
        $arr['subject2_id'] = $request->input('subject2_id');
        $arr['author'] = $request->input('author');
        $arr['description'] = $request->input('description');

    	$file = $request->file('file');
        $origin_name = $file->getClientOriginalName();  // 原始文件的全名
        $type = $file->getClientOriginalExtension();   // 原始文件的扩展名
        $size = $file->getClientSize();   // 原始文件大小(b)

        $saved_name = date('YmdHis').str_random(4).'.'.$type;

        $arr['origin_name'] = $origin_name;
        $arr['type'] = $type;
        $arr['size'] = $size;
        $arr['saved_name'] = $saved_name;

        $file->move(public_path('upload/files'),$saved_name);
        $libfile = Libfile::create($arr);
        
        $arr['id'] = $libfile->id;
        unset($arr['subject2_id']);
        unset($arr['saved_name']);

        return response()->json([
    		'file'=>$arr,
            'msg'=>['type'=>'ok','content'=>'上传文件成功'],
    	]);
    }

    public function chFile(Request $request)
    {
        $id = $request->input('id');
        $file = Libfile::find($id);
        if(!$file) return response()->json(['type'=>'err','content'=>'未找到文件']);

        $file->author = $request->input('author');
        $file->description = $request->input('description');
        $file->save();

        return response()->json(['type'=>'ok','content'=>'文件信息修改成功']);
    }

    public function rmFile(Request $request)
    {
        // 任务ID
        $id = $request->input('id');
        $file = Libfile::find($id);
        if(!$file) return response()->json(['type'=>'err','content'=>'未找到文件']);

        $name = $file->saved_name;
        if($name&&Storage::disk('upload')->has('files/'.$name))
        {
            Storage::disk('upload')->delete('files/'.$name);
        }
        $file->delete();

        return response()->json(['type'=>'ok','content'=>'文件删除成功']);
    }

    public function download(Request $request,$id)
    {
        $file = Libfile::find($id);
        $name = $file->origin_name;
        $saved_name = $file->saved_name;

        $path = public_path('upload/files').'/'.$saved_name;

        return response()->download($path, $name);
    }
}

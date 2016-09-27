<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Subject2;
use App\Models\Libclass;

use Storage;

class LibclassCtl extends Controller
{
    public function addClass(Request $request)
    {
        $arr = [];
        $arr['subject2_id'] = $request->input('subject2_id');
        $arr['time'] = $request->input('time','未知');
        $arr['title'] = $request->input('title');
        $arr['url'] = $request->input('url');

        if($request->hasFile('img'))
        {
        	$imgfile = $request->file('img');
	        $type = $imgfile->getClientOriginalExtension();   // 原始文件的扩展名

	        $saved_name = date('YmdHis').str_random(4).'.'.$type;
	        $img_url = asset('upload/classImg').'/'.$saved_name;

	        $arr['saved_name'] = $saved_name;
	        $arr['img_url'] = $img_url;
	        $imgfile->move(public_path('upload/classImg'),$saved_name);
        }
	        
        $libclass = Libclass::create($arr);
        $id = $libclass->id;

        return response()->json([
    		'item'=>Libclass::select(['id','title','url','time','img_url'])->find($id)->toArray(),
            'msg'=>['type'=>'ok','content'=>'添加课程成功'],
    	]);
    }

    public function rmClass(Request $request)
    {
        // 任务ID
        $id = $request->input('id');
        $class = Libclass::find($id);
        if(!$class) return response()->json(['type'=>'err','content'=>'未找到该课程']);

        $name = $class->saved_name;
        if($name&&Storage::disk('upload')->has('classImg/'.$name))
        {
            Storage::disk('upload')->delete('classImg/'.$name);
        }
        $class->delete();

        return response()->json(['type'=>'ok','content'=>'课程删除成功']);
    }

    public function chClass(Request $request)
    {
    	// ID
        $id = $request->input('id');
        $class = Libclass::find($id);
        if(!$class) return response()->json(['type'=>'err','content'=>'未找到该课程']);

        $inputs = $request->except(['img','id']);
        foreach ($inputs as $key => $value) { $class[$key] = $value; }

        if ($request->hasFile('img')) 
        {
            $imgfile = $request->file('img');
            $type = $imgfile->getClientOriginalExtension();
            $lastFileName = $class->saved_name;

            $saved_name = date('YmdHis').str_random(4).'.'.$type;
            $img_url = asset('upload/classImg').'/'.$saved_name;

            $imgfile->move(public_path('upload/classImg'),$saved_name);

            $class->saved_name = $saved_name;
            $class->img_url = $img_url;

            if($lastFileName&&Storage::disk('upload')->has('classImg/'.$lastFileName))
            {
                Storage::disk('upload')->delete('classImg/'.$lastFileName);
            }
        }
        $class->save();
        
        $data = [
            'item'=>Libclass::select(['id','title','url','time','img_url'])->find($id)->toArray(),
            'msg'=>['type'=>'ok','content'=>'编辑课程成功！'],
        ];

        return response()->json($data);
    }
}

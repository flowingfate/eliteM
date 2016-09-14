<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;

class TeacherCtl extends Controller
{

    // 获取导师列表
    public function teacherlist()
    {
        $result = Teacher::select(['id','name','email','school','laboratory'])->get()->toArray();

        return response()->json($result);
    }

    // 获取单个导师的信息
    public function teacherInfo(Request $request)
    {
        $id = $request->input('id');

        $teacher = Teacher::find($id);
        $students = ['progress'=>[],'finish'=>[]];
        $val = [0=>'progress',1=>'finish'];

        foreach ($teacher->students as $student) 
        {
            $tasks = $student->tasks()->select(['discribe','mission','progress','work_time','teacher_id','up_time'])->get()->toArray();
        	$one = [
                'id'=>$student->id,
        		'name'=>$student->name,
        		'tasks'=>$tasks
        	];
            $state = $student->pivot->finish;

        	array_push($students[$val[$state]], $one);
        }

        $data = $students;
        // dd($data);
        return response()->json($data);
    }

    // 获取特定导师的学生们
    public function relatedStudents(Request $request)
    {
    	$id = $request->input('id');

        /*---导师只能获取自己的学员信息---*/
        if($id!=session('user')['id']) return response('无权限访问');

    	$finish = $request->input('finish');
    	$teacher = Teacher::find($id);
    	$students = [];

        foreach ($teacher->students as $s) 
        {
            if($s->pivot->finish==$finish) array_push($students,$s->toArray());
        }

        // 注意处理查询结果为空的情况
        $data = [
            'teacher'=>$teacher->toArray(),
            'students'=>$students
        ];
        // dd($data);
        return response()->json($data);
    }

    public function personalInfo(Request $request)
    {
        $id = $request->input('id');

        /*---导师只能获取自己的个人信息---*/
        if($id!=session('user')['id']) return response('无权限访问');

        $teacher = Teacher::select(['name','username','laboratory','school','qq','email'])->where('id',$id)->first();
        return response()->json($teacher->toArray());
    }

    public function modifyPersonalInfo(Request $request)
    {
        $id = $request->input('id');

        /*---导师只能修改自己的个人信息---*/
        if($id!=session('user')['id']) return response('无权限访问');

        $inputs = $request->except(['id']);

        $teacher = Teacher::find($id);
        foreach ($inputs as $k => $val) { $teacher[$k] = $val; }
        $teacher->save();
        
        return response()->json(['type'=>'ok','content'=>'信息修改成功']);
    }

    public function modifyPassword(Request $request)
    {
        $id = $request->input('id');

        /*---导师只能修改自己的个人密码---*/
        if($id!=session('user')['id']) return response('无权限访问');

        $originPass = $request->input('originPass');
        $newPass = $request->input('newPass');

        $teacher = Teacher::find($id);
        if($teacher->password!=$originPass)
        {
            return response()->json(['type'=>'err','content'=>'原密码输入不正确！']);
        }
        else
        {
            $teacher->password = $newPass;
            $teacher->save();
            return response()->json(['type'=>'ok','content'=>'密码修改成功']);
        }
    }
}

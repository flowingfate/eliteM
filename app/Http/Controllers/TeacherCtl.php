<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;

use Validator;

class TeacherCtl extends Controller
{

    // 获取导师列表
    public function teacherlist()
    {
        $result = Teacher::all(['id','name','email','school','laboratory','stars','research','paper'])->toArray();

        return response()->json($result);
    }

    // 获取单个导师的信息
    public function teacherInfo(Request $request)
    {
        $id = $request->input('id');

        $teacher = Teacher::find($id);
        if(!$teacher) return response()->json(['type'=>'err','content'=>'没有找到该导师的信息']);

        $students = ['progress'=>[],'finish'=>[]];
        $val = [0=>'progress',1=>'finish'];

        foreach ($teacher->students as $student) 
        {
            $tasks = $student->tasks()->select(['discribe','mission','work_time','teacher_id','up_time','deadline'])->get()->toArray();
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
    	$finish = $request->input('finish');
    	$teacher = Teacher::find($id);
        if(!$teacher) return response()->json(['type'=>'err','content'=>'没有找到该导师的信息']);

    	$students = [];
        $arrTemp = [];
        foreach ($teacher->students as $s) 
        {
            if($s->pivot->finish==$finish)
            {
                $arrTemp = $s->toArray();

                // 获取导师联系时间间隔
                if($finish==0)
                {
                    $task = $teacher->tasks()->select(['up_time'])->where('student_id',$s->id)->orderBy('id','desc')->first();
                    
                    // 有任务，记录时间
                    if($task) $arrTemp['time'] = round((time()-strtotime($task->up_time))/60/60/24);
                    // 无任务，或者刚布置完任务，标记为0
                    else $arrTemp['time'] = 0;
                }

                array_push($students,$arrTemp);
            }
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

        $teacher = Teacher::where('id',$id)->first(['name','username','laboratory','school','qq','email','research','paper']);
        if(!$teacher) return response()->json(['type'=>'err','content'=>'没有找到该导师的信息']);

        return response()->json($teacher->toArray());
    }

    public function modifyPersonalInfo(Request $request)
    {
        $id = $request->input('id');
        $inputs = $request->except(['id']);
        foreach ($inputs as $k=>$v) { if(empty($inputs[$k])) $inputs[$k]=NULL; }

        $msg = [
            'username.unique'=>'已经有人使用了这个用户名，请更换！！',
            'email.unique'=>'已经有人使用了相同的邮箱，请更换！',
            'email.email'=>'您填写的邮箱不合法，请重填！',
            'qq.unique'=>'已经有人使用了相同的QQ，请更换！',
        ];
        $rules = [
            'username'=>'unique:teacher',
            'email'=>'email|unique:teacher',
            'qq'=>'unique:teacher',
        ];
        $validator = Validator::make($inputs,$rules,$msg);
        $errs = $validator->errors()->all();
        if(count($errs)!=0) return response()->json(['type'=>'err','content'=>$errs[0]]);

        $teacher = Teacher::find($id);
        foreach ($inputs as $k => $val) { $teacher[$k] = $val; }
        $teacher->save();
        
        return response()->json(['type'=>'ok','content'=>'信息修改成功']);
    }

    public function modifyPassword(Request $request)
    {
        $id = $request->input('id');
        $originPass = $request->input('originPass');
        $newPass = $request->input('newPass');

        $teacher = Teacher::find($id);
        if(!$teacher) return response()->json(['type'=>'err','content'=>'没有找到该导师的信息']);
        
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

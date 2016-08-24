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
        $students = [];

        foreach ($teacher->students as $student) 
        {
            $tasks = [];
            foreach ($student->tasks as $task) 
            {
                $arr = [
                    'discribe'=>$task->discribe,
                    'mission'=>$task->mission,
                    'progress'=>$task->progress,
                    'work_time'=>$task->work_time,
                    'teacher'=>$task->teacher->name,
                    'up_time'=>$task->up_time
                ];
                array_push($tasks, $arr);
            }

        	$one = [
        		'name'=>$student->name,
        		'tasks'=>$tasks
        	];
        	array_push($students, $one);
        }

        $data = $students;
        // dd($data);
        return response()->json($data);
    }

    // 获取特定导师的学生们
    public function relatedStudents(Request $request)
    {
    	$id = $request->input('id');
    	$isfinish = $request->input('isfinish');
    	$teacher = Teacher::find($id);
    	$students = $teacher->students()->where('isfinish',$isfinish)->get()->toArray();

        // 注意处理查询结果为空的情况
        $data = [
            'teacher'=>$teacher->toArray(),
            'students'=>$students
        ];
        // dd($students);
        return response()->json($data);
    }

    public function personalInfo(Request $request)
    {
        $id = $request->input('id');
        $teacher = Teacher::select(['username','laboratory','school','email'])->where('id',$id)->first();
        return response()->json($teacher->toArray());
    }

    public function modifyPersonalInfo(Request $request)
    {
        $id = $request->input('id');
        $username = $request->input('username');
        $laboratory = $request->input('laboratory');
        $school = $request->input('school');
        $email = $request->input('email');

        $teacher = Teacher::find($id);
        if($teacher->username!=$username) $teacher->username=$username;
        if($teacher->laboratory!=$laboratory) $teacher->laboratory=$laboratory;
        if($teacher->school!=$school) $teacher->school=$school;
        if($teacher->email!=$email) $teacher->email=$email;

        $teacher->save();
        
        return response()->json(['type'=>'ok','content'=>'信息修改成功']);
    }

    public function modifyPassword(Request $request)
    {
        $id = $request->input('id');
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

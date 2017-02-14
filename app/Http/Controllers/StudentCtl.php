<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;

use Validator;

class StudentCtl extends Controller
{
    // 给学员指定导师
    public function setTeacher(Request $request)
    {
        // 添加导师无条件允许
        // 删除导师只能是没有发布过任务的老师
        // 按理说应当在后端做检查，但是放在前端更利于减小负担
        // 如果非要删除的话，那也应该删除导师布置过的任务
        $studentId = $request->input('studentId');
        $student = Student::find($studentId);
        if(!$student) return response()->json(['type'=>'err','content'=>'没有找到该学员的信息']);

        $inputIds = $request->input('teacherIds',[]);
        $nowIds = [];
        foreach ($student->teachers as $T) array_push($nowIds,$T->id);

        $add = array_diff($inputIds, $nowIds);
        $del = array_diff($nowIds, $inputIds);
        
        if(count($add)>0) $student->teachers()->attach($add);
        if(count($del)>0)
        {
            $student->teachers()->detach($del);
            $student->tasks()->whereIn('teacher_id',$del)->delete();
        }
        
        return response()->json(['type'=>'ok','content'=>'设置导师成功']);
    }

    // 获取单个学员的任务信息
    public function studentInfo(Request $request)
    {
        $id = $request->input('id');

        $student = Student::find($id);
        $tasks = $student->tasks;

        $result = [];
        foreach ($tasks as $task) 
        {
            $arr = [
                'id'=>$task->id,
                'discribe'=>$task->discribe,
                'mission'=>$task->mission,
                'work_time'=>$task->work_time,
                'teacher'=>$task->teacher->name,
                'teacherId'=>$task->teacher_id,
                'up_time'=>$task->up_time,
                'deadline'=>$task->deadline,
            ];
            array_push($result, $arr);
        }

        return response()->json($result);
    }

    // 获取特定学员导师信息及最近任务
    public function relatedInfo(Request $request)
    {
    	$id = $request->input('id');
    	$student = Student::find($id);
        if(!$student) return response()->json(['type'=>'err','content'=>'没有找到该学员的信息']);

    	$teachers = $student->teachers->toArray();
        $tasks = [];
    	$nowtasks = [];
        $histasks = [];

        foreach ($student->tasks()->orderBy('id', 'desc')->get() as $task) 
        {
            $arr = $task->toArray();
            $arr['teacher'] = $task->teacher->name;
            $arr['teacherId'] = $task->teacher->id;

            // if($arr['progress']==0) array_push($nowtasks, $arr);
            // if($arr['progress']==1) array_push($histasks, $arr);
            array_push($tasks, $arr);
        }

    	$data = ['teachers'=>$teachers, 'tasks'=>$tasks, 'nowtasks'=>$nowtasks, 'histasks'=>$histasks ];

    	return response()->json($data);
    }

    public function personalInfo(Request $request)
    {
        $id = $request->input('id');

        $student = Student::select(['name','username','direction','school','email','qq','phone','wechat'])->where('id',$id)->first();
        if(!$student) return response()->json(['type'=>'err','content'=>'没有找到该学员的信息']);
        return response()->json($student);
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
            'wechat.unique'=>'已经有人使用了相同的微信，请更换！',
            'phone.unique'=>'已经有人使用了相同的电话，请更换！'
        ];
        $rules = [
            'username'=>'unique:student',
            'email'=>'email|unique:student',
            'qq'=>'unique:student',
            'wechat'=>'unique:student',
            'phone'=>'unique:student',
        ];
        $validator = Validator::make($inputs,$rules,$msg);
        $errs = $validator->errors()->all();
        if(count($errs)!=0) return response()->json(['type'=>'err','content'=>$errs[0]]);

        $student = Student::find($id);
        foreach ($inputs as $k => $val) { $student[$k] = $val; }
        $student->save();
        
        return response()->json(['type'=>'ok','content'=>'信息修改成功']);
    }

    public function modifyPassword(Request $request)
    {
        $id = $request->input('id');
        
        $originPass = $request->input('originPass');
        $newPass = $request->input('newPass');

        $student = Student::find($id);
        if(!$student) return response()->json(['type'=>'err','content'=>'没有找到该学员的信息']);
        if($student->password!=$originPass)
        {
            return response()->json(['type'=>'err','content'=>'原密码输入不正确！']);
        }
        else
        {
            $student->password = $newPass;
            $student->save();
            return response()->json(['type'=>'ok','content'=>'密码修改成功']);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;

class StudentCtl extends Controller
{
    // 获取正在学习中的学员的信息
    public function progress(Request $request)
    {
    	$students = Student::select(['id','name','school','direction'])->where('isfinish','0')->get();

        $result = [];

        foreach ($students as $student) 
        {
            $arr = [
                'id'=>$student->id,
                'name'=>$student->name,
                'school'=>$student->school,
                'teachers'=>$student->teachers()->select(['name'])->get()->toArray(),
                'direction'=>$student->direction,
            ];
            array_push($result, $arr);
        }
        // dd($result);

        return response()->json($result);
    }

    // 获取已经结项的学员的信息
    public function finished()
    {
    	$students = Student::select(['id','name','school','direction'])->where('isfinish','1')->get();

        $result = [];

        foreach ($students as $student) 
        {
            $arr = [
                'id'=>$student->id,
                'name'=>$student->name,
                'school'=>$student->school,
                'teachers'=>$student->teachers()->select(['name'])->get()->toArray(),
                'direction'=>$student->direction,
            ];
            array_push($result, $arr);
        }
        // dd($result);

        return response()->json($result);
    }

    // 给学员指定导师
    public function setTeacher(Request $request)
    {
        $studentId = $request->input('studentId');
        $teacherIds = $request->input('teacherIds');

        $student = Student::find($studentId);
        
        $student->teachers()->attach($teacherIds);

        
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
                'discribe'=>$task->discribe,
                'mission'=>$task->mission,
                'progress'=>$task->progress,
                'work_time'=>$task->work_time,
                'teacher'=>$task->teacher->name,
                'teacherId'=>$task->teacher_id,
                'up_time'=>$task->up_time
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
    	$teachers = $student->teachers->toArray();
    	$nowtasks = [];
        foreach ($student->tasks()->where('progress',0)->orderBy('updated_at', 'desc')->get() as $task) 
        {
            $arr = $task->toArray();
            $arr['teacher'] = $task->teacher->name;
            array_push($nowtasks, $arr);
        }
        $histasks = [];
        foreach ($student->tasks()->where('progress',1)->orderBy('updated_at', 'desc')->get() as $task) 
        {
            $arr = $task->toArray();
            $arr['teacher'] = $task->teacher->name;
            array_push($histasks, $arr);
        }

    	$data = ['teachers'=>$teachers, 'nowtasks'=>$nowtasks, 'histasks'=>$histasks ];

    	return response()->json($data);
    }

    public function personalInfo(Request $request)
    {
        $id = $request->input('id');
        $student = Student::select(['username','direction','school'])->where('id',$id)->first();
        return response()->json($student);
    }

    public function modifyPersonalInfo(Request $request)
    {
        $id = $request->input('id');
        $username = $request->input('username');
        $direction = $request->input('direction');
        $school = $request->input('school');

        $student = Student::find($id);
        if($student->username!=$username) $student->username=$username;
        if($student->direction!=$direction) $student->direction=$direction;
        if($student->school!=$school) $student->school=$school;

        $student->save();
        
        return response()->json(['type'=>'ok','content'=>'信息修改成功']);
    }

    public function modifyPassword(Request $request)
    {
        $id = $request->input('id');
        $originPass = $request->input('originPass');
        $newPass = $request->input('newPass');

        $student = Student::find($id);
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

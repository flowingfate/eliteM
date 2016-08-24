<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;

class TaskCtl extends Controller
{
    // 添加任务
    public function add(Request $request)
    {
        $arr = [
        	'discribe' => $request->input('discribe'),
            'mission' => $request->input('mission'),
            'work_time' => $request->input('work_time'),
            'student_id' => $request->input('student_id'),
            'teacher_id'=> $request->input('teacher_id'),
            'up_time'=>date('Y-m-d H:i:s'),
            'progress'=>0
        ];

        Task::create($arr);

        return response()->json(['type'=>'ok','content'=>'添加任务成功！']);
    }

    // 删除任务
    public function remove(Request $request)
    {
    	// 任务ID
        $id = $request->input('id');
        Task::destroy($id);

        return response()->json(['type'=>'ok','content'=>'删除任务成功！']);
    }

    // 修改任务
    public function modify(Request $request)
    {
    	// 任务ID
    	$id = $request->input('id');
        $discribe = $request->input('discribe');
        $mission = $request->input('mission');
        $work_time = $request->input('work_time');

    	$task = Task::find($id);
        $task->discribe = $discribe;
        $task->mission = $mission;
        $task->work_time = $work_time;
        $task->save();

        return response()->json(['type'=>'ok','content'=>'修改任务成功！']);
    }
}

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
            'up_time'=>date('Y-m-d'),
            'progress'=>0
        ];

        $T = Task::create($arr);

        $task = [
            'id'=>$T->id,
            'discribe'=>$T->discribe,
            'mission'=>$T->mission,
            'progress'=>$T->progress,
            'work_time'=>$T->work_time,
            'teacher'=>$T->teacher->name,
            'teacherId'=>$T->teacher_id,
            'up_time'=>$T->up_time
        ];

        return response()->json([
            'msg'=>['type'=>'ok','content'=>'添加任务成功！'],
            'task'=>$task
        ]);
    }

    // 删除任务
    public function remove(Request $request)
    {
    	// 任务ID
        $id = $request->input('id');
        Task::destroy($id);

        return response()->json(['type'=>'ok','content'=>'删除任务成功！']);
    }

    // 切换任务的状态：完成--未完成
    public function finish(Request $request)
    {
        // 任务ID
        $id = $request->input('id');
        $task = Task::find($id);
        if(!$task) return response()->json(['type'=>'err','content'=>'任务查找失败！']);

        $task->progress = 1-$task->progress;
        $task->save();

        return response()->json(['type'=>'ok','content'=>'设置成功']);
    }

    // 修改任务
    public function modify(Request $request)
    {
    	// 任务ID
    	$id = $request->input('id');
    	$task = Task::find($id);
        if(!$task) return response()->json(['type'=>'err','content'=>'没找到任务相关信息']);

        $inputs = $request->except(['id']);

        foreach ($inputs as $k => $val) { $task[$k] = $val; }
        $task->save();

        return response()->json(['type'=>'ok','content'=>'修改任务成功！']);
    }
}

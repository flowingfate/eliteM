<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;
use App\Models\M2m;

class AdminCtl extends Controller
{
    /**
     * 获取正在学习中的学员的信息
     * 其中包括了最新任务时间与当前时间的时差(时)，用于做过滤判断
     */
    public function progress(Request $request)
    {
        $students = Student::select(['id','name','school','direction'])->get();

        $result = [];
        foreach ($students as $student) 
        {
            $Ts = []; $flag = false;
            foreach ($student->teachers as $t) 
            {
                $T['id']=$t->id;  $T['name']=$t->name;
                if($t->pivot->finish==0)
                {
                    // 这里肯定是比较大的计算量，后期优化
                    // 如果时间要能排序，需要使用时间戳而不是日期
                    // 默认新加的任务id是递增的，所以可以用id来排序，可代表时间先后
                    $flag=true;  
                    $task = $t->tasks()->select(['up_time'])->where('student_id',$student->id)->orderBy('id','desc')->first();
                    if($task) $T['time'] = round((time()-strtotime($task->up_time))/60/60/24);  // 表示时间
                    else $T['time'] = 0;  // 表示还未开始任务
                }
                else $T['time'] = -1;  // 表示已经结项了
                array_push($Ts, $T);
            }

            // 如果$flag为false，并且$Ts长度不为零，说明该学员所有老师都结项了
            if($flag||count($Ts)==0)
            {
                $arr = $student->toArray();
                $arr['teachers'] = $Ts;
                $arr['numThr'] = count($Ts);
                array_push($result, $arr);
            }
        }
        // dd($result);
        return response()->json($result);
    }
    public function finished(Request $request)
    {
        $students = Student::select(['id','name','school','direction'])->get();

        $result = [];
        foreach ($students as $student) 
        {
            $Ts = []; $flag = true;
            foreach ($student->teachers as $t) 
            {
                $T['id']=$t->id;  
                $T['name']=$t->name;
                if($t->pivot->finish==0)
                {
                    $flag = false;
                    break;
                } 
                array_push($Ts, $T);
            }

            if($flag&&(count($Ts)!=0))
            {
                $arr = $student->toArray();
                $arr['teachers'] = $Ts;
                array_push($result, $arr);
            }
        }
        // dd($result);
        return response()->json($result);
    }
    public function setFinish(Request $request)
    {
        $t_id = $request->input('teacher_id');
        $s_id = $request->input('student_id');
        $flag = $request->input('flag');
        $val = ['finish'=>1,'revert'=>0];

        $m2m = M2m::where('student_id',$s_id)->where('teacher_id',$t_id)->first();
        $m2m->finish = $val[$flag];
        $m2m->save();

        return response()->json(['type'=>'ok','content'=>'结项设置成功！']);
    }

	// 获取所有的用户，包括所有教师和学生
    public function users()
    {
        $teachers = Teacher::select(['id','username','name','school','laboratory','comment','email','qq'])->get()->toArray();
        $students = Student::select(['id','username','name','school','direction','comment','email','qq','phone','wechat'])->get()->toArray();

        $data = ['teachers'=>$teachers,'students'=>$students];

        return response()->json($data);
    }

    // 添加一个用户，教师或是学员
    public function addUser(Request $request)
    {
        $role = $request->input('role');
        $arr = $request->except(['role']);

        // dd($arr);

        if($role=="teacher")
        {
            Teacher::create($arr);
            return response()->json(['type'=>'ok','content'=>'添加导师成功！']);
        }
        if($role=="student")
        {
            Student::create($arr);
            return response()->json(['type'=>'ok','content'=>'添加学员成功！']);
        }

        return response()->json(['type'=>'err','content'=>'添加用户失败！']);
    }

    // 删除一个用户，教师或是学员
    public function removeUser(Request $request)
    {
        // 需要注意的是，删除用户可能也意味着清楚与之相关联的数据
        // 包括《关系表》以及《任务表》
        // 所以，按理说某些用户应当不允许删除
        $role = $request->input('role');
        $id = $request->input('id');

        if($role=="teacher")
        {
            $t = Teacher::find($id);
            $num = count($t->students->toArray());
            if($num==0)
            {
                $t->delete();
                return response()->json([
                    'msg'=>['type'=>'ok','content'=>'删除导师成功！'],
                    'flag'=>true
                ]);
            } 
            else 
            {
                return response()->json([
                    'msg'=>['type'=>'err','content'=>'已给导师分配了学员，不能删除！'],
                    'flag'=>false
                ]);
            }
        }
        if($role=="student")
        {
            $s = Student::find($id);
            $num = count($s->teachers->toArray());
            if($num==0)
            {
                $s->delete();
                return response()->json([
                    'msg'=>['type'=>'ok','content'=>'删除学员成功！'],
                    'flag'=>true
                ]);
            }
            else 
            {
                return response()->json([
                    'msg'=>['type'=>'err','content'=>'已给学员分配了导师，不能删除！'],
                    'flag'=>false
                ]);
            }
        }

        return response()->json([
            'msg'=>['type'=>'err','content'=>'删除失败！'],
            'flag'=>false
        ]);
    }


    // 重置密码
    public function resetPassword(Request $request)
    {
        $role = $request->input('role');
        $id = $request->input('id');
        $pass = '12345678';

        if($role=="teacher")
        {
            $user = Teacher::find($id);
            $user->password = $pass;
            $user->save();
            return response()->json(['type'=>'ok','content'=>'重置导师密码成功！']);
        }
        if($role=="student")
        {
            $user = Student::find($id);
            $user->password = $pass;
            $user->save();
            return response()->json(['type'=>'ok','content'=>'重置学员密码成功！']);
        }

        return response()->json(['type'=>'err','content'=>'重置密码失败！']);
    }

    // 修改用户信息
    public function modify(Request $request)
    {
        $role = $request->input('role');
        $id = $request->input('id');
        $inputs = $request->except(['id','role']);

        if($role=="teacher")
        {
            $user = Teacher::find($id);
            foreach ($inputs as $k => $val) { $user[$k] = $val; }
            $user->save();
            return response()->json(['type'=>'ok','content'=>'修改导师信息成功！']);
        }
        if($role=="student")
        {
            $user = Student::find($id);
            foreach ($inputs as $k => $val) { $user[$k] = $val; }
            $user->save();
            return response()->json(['type'=>'ok','content'=>'修改学员信息成功！']);
        }
        
        return response()->json(['type'=>'err','content'=>'修改信息失败！']);
    }
}

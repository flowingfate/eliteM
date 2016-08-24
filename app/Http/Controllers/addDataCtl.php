<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Task;
use App\Models\Teacher;
use App\Models\M2m;

class addDataCtl extends Controller
{
    public function index()
    {
        $school = ['北大','清华','人大','北邮','北航','中科院','央财','北理','北科','北郊','复旦','同济'];
        $direction = ['设计','艺术','java','经济','管理','网络安全','电子','交互','心理学','机械','自动化','物流','影视'];

        // 添加学员数据
        for($i=1;$i<=36;$i++)
        {
            $arr = [
                'username'=>'student'.$i,
                'name'=>'学员'.$i,
                'school'=>$school[rand(0,11)],
                'direction'=>$direction[rand(0,12)]
            ];

            Student::create($arr);
        }

        // 添加导师数据
        for($i=1;$i<=12;$i++)
        {
            $arr = [
                'username'=>'teacher'.$i,
                'name'=>'导师'.$i,
                'school'=>$school[rand(0,11)],
                'laboratory'=>'实验室'.$i,
                'email'=>(168610+$i).'@163.com'
            ];

            Teacher::create($arr);
        }
        
        // 添加多对多关联数据
        for($i=1;$i<=18;$i++)
        {
            M2m::create(['teacher_id'=>rand(1,12),'student_id'=>rand(1,36)]);
        }

        // 添加任务数据
        for($i=1;$i<=108;$i++)
        {
            $m2m = M2m::find(rand(1,18));
            $arr = 
            [
                'teacher_id'=>$m2m->teacher_id,
                'student_id'=>$m2m->student_id,
                'discribe'=>'指导学生完成学习任务',
                'mission'=>'读论文+做作品',
                'progress'=>rand(0,1),
                'up_time'=>date('Y-m-d H:i:s'),
                'work_time'=>rand(1,10)
            ];

            Task::create($arr);
        }
    }
}

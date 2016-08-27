<?php

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\M2m;

class TaskSdr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

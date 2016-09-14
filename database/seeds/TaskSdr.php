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
        $date = ['2016-09-01','2016-09-02','2016-09-03','2016-09-04','2016-09-05','2016-09-06','2016-09-07'];
        for($i=1;$i<=108;$i++)
        {
            $m2m = M2m::find(rand(1,36));
            $arr = 
            [
                'teacher_id'=>$m2m->teacher_id,
                'student_id'=>$m2m->student_id,
                'discribe'=>'指导学生完成学习任务',
                'mission'=>'读论文+做作品',
                'up_time'=>$date[rand(0,6)],
                'work_time'=>rand(1,10),
                'progress'=>rand(0,1)
            ];

            Task::create($arr);
        }
    }
}

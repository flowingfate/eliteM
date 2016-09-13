<?php

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSdr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$school = ['北大','清华','人大','北邮','北航','中科院','央财','北理','北科','北郊','复旦','同济'];
    	
        for($i=1;$i<=12;$i++)
        {
            $arr = [
                'username'=>'teacher'.$i,
                'name'=>'导师'.$i,
                'school'=>$school[rand(0,11)],
                'laboratory'=>'实验室'.$i,
                'email'=>(168610+$i).'@163.com',
                'qq'=>(83782312+$i)
            ];

            Teacher::create($arr);
        }

        $firstTeacher = Teacher::first();
        $firstTeacher->username = 'teacher';
        $firstTeacher->save();
    }
}

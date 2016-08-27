<?php

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSdr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $school = ['北大','清华','人大','北邮','北航','中科院','央财','北理','北科','北郊','复旦','同济'];
        $direction = ['设计','艺术','java','经济','管理','网络安全','电子','交互','心理学','机械','自动化','物流','影视'];

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

        $firstStudent = Student::first();
        $firstStudent->name = 'student';
        $firstStutend->save();
    }
}

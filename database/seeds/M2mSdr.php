<?php

use Illuminate\Database\Seeder;
use App\Models\M2m;

class M2mSdr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=36;$i++)
        {
            M2m::create(['teacher_id'=>rand(1,12),'student_id'=>rand(1,36),'finish'=>rand(0,1)]);
        }
    }
}

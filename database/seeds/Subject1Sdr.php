<?php

use Illuminate\Database\Seeder;
use App\Models\Subject1;

class Subject1Sdr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
        	['number'=>'A','title'=>'马克思主义、列宁主义、毛泽东思想、邓小平理论'],
        	['number'=>'B','title'=>'哲学、宗教'],
        	['number'=>'C','title'=>'社会科学总论'],
        	['number'=>'D','title'=>'政治、法律'],
        	['number'=>'E','title'=>'军事'],
        	['number'=>'F','title'=>'经济'],
        	['number'=>'G','title'=>'文化、科学、教育、体育'],
        	['number'=>'H','title'=>'语言、文字'],
        	['number'=>'I','title'=>'文学'],
        	['number'=>'J','title'=>'艺术'],
        	['number'=>'K','title'=>'历史、地理'],
        	['number'=>'L','title'=>'自然科学总论']
        ];

        foreach ($subjects as $subject) 
        {
        	Subject1::create($subject);
        }
    }
}

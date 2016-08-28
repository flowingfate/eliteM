<?php

use Illuminate\Database\Seeder;
use App\Models\Subject1;
use App\Models\Subject2;

class Subject2Sdr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
        	['subject1_id'=>1,'number'=>'A1','title'=>'马克思恩格斯著作','keywords'=>'工人阶级+共产主义+革命精神','profile'=>"卡尔·海因里希·马克思（德语：Karl Heinrich Marx，公元1818年5月5日—公元1883年3月14日），19世纪德国著名作家、哲学家。马克思主义的创始人之一，第一国际的组织者和领导者，被称为全世界无产阶级和劳动人民的伟大导师。[1]  无产阶级的精神领袖，国际共产主义运动的先驱。马克思是德国伟大的思想家、政治家、哲学家、经济学家、革命家和社会学家。主要著作有《资本论》[2]  、《共产党宣言》等。"],
        	['subject1_id'=>1,'number'=>'A2','title'=>'列宁著作','keywords'=>'工人阶级+共产主义+革命精神','profile'=>'nothing to say'],
        	['subject1_id'=>1,'number'=>'A3','title'=>'斯大林著作','keywords'=>'工人阶级+共产主义+革命精神','profile'=>'nothing to say'],
        	['subject1_id'=>1,'number'=>'A4','title'=>'毛泽东著作','keywords'=>'工人阶级+共产主义+革命精神','profile'=>'nothing to say'],
        	['subject1_id'=>1,'number'=>'A49','title'=>'邓小平著作','keywords'=>'工人阶级+共产主义+革命精神','profile'=>'nothing to say']
        ];

        foreach ($subjects as $subject) 
        {
        	Subject2::create($subject);
        }
    }
}

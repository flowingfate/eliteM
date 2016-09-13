<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M2m extends Model
{
    protected $table = 'teacher_student';
    public $timestamps = false;
    protected $guarded = [];  //设置不可被批量赋值的属性


    public function student()
    {
    	return $this->belongsTo('App\Models\Student','student_id');
    }

    public function teacher()
    {
    	return $this->belongsTo('App\Models\Teacher','teacher_id');
    }
}

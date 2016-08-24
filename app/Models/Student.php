<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * https://segmentfault.com/a/1190000004681874#articleHeader9
 * 模型关联方法
**/

class Student extends Model
{
    protected $table = 'student';

    protected $guarded = ['id','password','isfinish'];  //设置不可被批量赋值的属性

    public function teachers()
    {
    	return $this->belongsToMany('App\Models\Teacher','teacher_student','student_id','teacher_id');
    }

    public function tasks()
    {
    	return $this->hasMany('App\Models\Task','student_id','id');
    }
}

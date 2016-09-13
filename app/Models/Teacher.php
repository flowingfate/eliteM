<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * https://segmentfault.com/a/1190000004681874#articleHeader9
 * 模型关联方法
**/

class Teacher extends Model
{
    protected $table = 'teacher';

    protected $guarded = ['id','password'];  //设置不可被批量赋值的属性

    public function students()
    {
    	return $this->belongsToMany('App\Models\Student','teacher_student','teacher_id','student_id')->withPivot('finish');
    }

    public function tasks()
    {
    	return $this->hasMany('App\Models\Task','teacher_id','id');
    }

    public function m2ms()
    {
        return $this->hasMany('App\Models\M2m','teacher_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject1 extends Model
{
    protected $table = 'subject1';

    protected $guarded = ['id'];  //设置不可被批量赋值的属性

    public function subject2s()
    {
    	return $this->hasMany('App\Models\Subject2','subject1_id','id');
    }
}

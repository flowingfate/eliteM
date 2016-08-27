<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject2 extends Model
{
    protected $table = 'subject2';
    
    protected $guarded = ['id'];  //设置不可被批量赋值的属性

    public function subject1()
    {
    	return $this->belongsTo('App\Models\Subject1','subject1_id');
    }

    public function libfiles()
    {
    	return $this->hasMany('App\Models\Libfile','subject2_id','id');
    }

    public function libclasses()
    {
    	return $this->hasMany('App\Models\Libclass','subject2_id','id');
    }
}

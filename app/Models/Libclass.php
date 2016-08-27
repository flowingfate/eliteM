<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libclass extends Model
{
    protected $table = 'libfile';

    protected $guarded = ['id'];  //设置不可被批量赋值的属性

    public function subject2()
    {
    	return $this->belongsTo('App\Models\Subject2','subject2_id');
    }
}

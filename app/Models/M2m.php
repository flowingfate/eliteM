<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M2m extends Model
{
    protected $table = 'teacher_student';
    public $timestamps = false;
    protected $guarded = [];  //设置不可被批量赋值的属性
}

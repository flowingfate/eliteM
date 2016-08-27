<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';

    protected $guarded = ['id','password'];  //设置不可被批量赋值的属性
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_id');
            $table->string('teacher_id');
            $table->string('discribe')->default('未指定!');
            $table->string('mission')->default('未指定!');
            $table->boolean('progress')->default(false);
            $table->string('up_time')->nullable();
            $table->integer('work_time')->default(0);   // 以半小时为单位
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('task');
    }
}

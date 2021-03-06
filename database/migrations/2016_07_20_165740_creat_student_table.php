<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password')->default('12345678');
            $table->string('name')->nullable();
            $table->string('school')->nullable();
            $table->string('direction')->nullable();
            $table->string('comment')->nullable();

            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('wechat')->nullable()->unique();
            $table->string('qq')->nullable()->unique();

            // 不再这里标记结项了
            
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
        Schema::drop('student');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubject2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject2', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject1_id');
            $table->string('number')->unique();
            $table->string('title')->unique();
            $table->string('img_url')->nullable();
            $table->string('saved_name')->nullable();
            $table->string('keywords')->nullable();
            $table->text('profile')->nullable();
            
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
        Schema::drop('subject2');
    }
}

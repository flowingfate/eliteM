<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libfile', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject2_id');
            $table->string('origin_name');
            $table->string('saved_name');
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->string('type');
            $table->string('size')->nullable();
            
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
        Schema::drop('libfile');
    }
}

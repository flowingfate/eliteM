<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibclassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libclass', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject2_id');
            $table->string('title');
            $table->string('url');
            $table->string('time')->nullable();
            $table->string('img_url')->nullable();
            $table->string('saved_name')->nullable();
            
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
        Schema::drop('libclass');
    }
}

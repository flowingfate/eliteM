<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeacherReserchAndPaper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher', function ($table) 
        {
            $table->text('research')->nullable()->default("");
            $table->text('paper')->nullable()->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher', function ($table) 
        {
            $table->dropColumn('research');
            $table->dropColumn('paper');
        });
    }
}

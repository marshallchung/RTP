<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpScoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dp_course_id')->unsigned();
            $table->integer('dp_student_id')->unsigned()->index('role_user_role_id_foreign');
            $table->float('score', 10, 0)->default(-1);
            $table->integer('user_id')->unsigned();
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
        Schema::drop('dp_scores');
    }
}

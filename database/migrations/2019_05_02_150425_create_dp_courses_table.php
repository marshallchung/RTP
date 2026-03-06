<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpCoursesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->smallInteger('county_id')->unsigned();
            $table->string('name');
            $table->mediumText('content');
            $table->string('email', 45)->default('');
            $table->string('phone', 45)->default('');
            $table->string('url', 256)->default('');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->boolean('active');
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
        Schema::drop('dp_courses');
    }
}

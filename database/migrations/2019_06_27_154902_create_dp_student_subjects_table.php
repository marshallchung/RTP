<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpStudentSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_student_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dp_student_id');
            $table->unsignedInteger('dp_subject_id');
            $table->timestamps();

            $table->foreign('dp_student_id')->references('id')->on('dp_students')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dp_subject_id')->references('id')->on('dp_subjects')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dp_student_subjects');
    }
}

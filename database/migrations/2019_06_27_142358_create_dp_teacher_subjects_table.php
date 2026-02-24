<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpTeacherSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_teacher_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dp_teacher_id');
            $table->unsignedInteger('dp_subject_id');
            $table->string('type')->nullable()->comment('師資類型');
            $table->timestamps();

            $table->foreign('dp_teacher_id')->references('id')->on('dp_teachers')
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
        Schema::drop('dp_teacher_subjects');
    }
}

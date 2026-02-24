<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dp_advance_student_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dp_student_id');
            $table->unsignedInteger('dp_course_subject_id');
            $table->timestamps();
            $table->foreign('dp_student_id')->references('id')->on('dp_students')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dp_course_subject_id')->references('id')->on('dp_subjects')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_advance_student_subjects');
    }
};

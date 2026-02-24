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
        Schema::table('dp_advance_student_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('dp_advance_course_subjects');
            $table->foreign('dp_advance_course_subjects')->references('id')->on('dp_advance_course_subjects')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

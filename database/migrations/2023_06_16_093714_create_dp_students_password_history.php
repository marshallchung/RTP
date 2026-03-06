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
        Schema::create('dp_students_password_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dp_students_id')->index('idx_dp_students_password_history_dp_students_id');
            $table->foreign('dp_students_id')->references('id')->on('dp_students')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_students_password_history');
    }
};

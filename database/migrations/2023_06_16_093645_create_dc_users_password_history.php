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
        Schema::create('dc_users_password_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dc_users_id')->index('idx_dc_users_password_history_dc_users_id');
            $table->foreign('dc_users_id')->references('id')->on('dc_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dc_users_password_history');
    }
};

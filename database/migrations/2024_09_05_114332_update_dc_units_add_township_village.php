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
        Schema::table('dc_units', function (Blueprint $table) {
            $table->string('township', 50)->nullable(true)->default(null)->comment('鄉鎮市區');
            $table->string('village', 50)->nullable(true)->default(null)->comment('村里');
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

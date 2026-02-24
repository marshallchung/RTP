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
        Schema::table('dp_students', function (Blueprint $table) {
            $table->string('residence_county', 50)->nullable(true)->default(null)->comment('現居地址-縣市');
            $table->string('township', 50)->nullable(true)->default(null)->comment('現居地址-鄉鎮市區');
            $table->string('household_county', 50)->nullable(true)->default(null)->comment('戶籍地址-縣市');
            $table->string('household_township', 50)->nullable(true)->default(null)->comment('戶籍地址-鄉鎮市區');
            $table->string('household_address', 250)->nullable(true)->default(null)->comment('戶籍地址-地址');
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

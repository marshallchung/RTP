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
        Schema::table('dp_courses', function (Blueprint $table) {
            $table->boolean('exclusive')->default(false)->after('active');
            $table->boolean('stop_signup')->default(false)->after('exclusive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dp_courses', function (Blueprint $table) {
            $table->dropColumn('exclusive');
            $table->dropColumn('stop_signup');
        });
    }
};

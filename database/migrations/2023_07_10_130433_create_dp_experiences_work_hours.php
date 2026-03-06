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
        Schema::table('dp_experiences', function (Blueprint $table) {
            $table->integer('work_hours')->default(0)->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dp_experiences', function (Blueprint $table) {
            $table->dropColumn('work_hours');
        });
    }
};

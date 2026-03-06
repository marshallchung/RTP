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
            $table->boolean('within_plan')->default(false)->after('active');
            $table->boolean('native')->default(false)->after('within_plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dc_units', function (Blueprint $table) {
            $table->dropColumn('within_plan');
            $table->dropColumn('native');
        });
    }
};

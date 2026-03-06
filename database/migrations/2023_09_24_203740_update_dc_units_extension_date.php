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
            $table->date('extension_date')->nullable(true)->default(null)->after('date_extension');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dc_units', function (Blueprint $table) {
            $table->dropColumn('extension_date');
        });
    }
};

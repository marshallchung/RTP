<?php

use App\Report;
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
        Schema::table('report_public_dates', function (Blueprint $table) {
            $table->timestamp('expire_soon_date')->nullable(true);
            $table->timestamp('expire_date')->nullable(true);
            $table->string('date_type')->default('reports');
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

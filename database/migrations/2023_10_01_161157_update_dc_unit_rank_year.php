<?php

use App\DcUnit;
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
            $table->integer('rank_year')->default(3)->after('created_at');
        });
        DcUnit::where('rank', '三星')->update(['rank_year' => 5]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rank_year');
    }
};

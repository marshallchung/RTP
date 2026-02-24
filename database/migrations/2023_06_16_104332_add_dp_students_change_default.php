<?php

use App\DpStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->boolean('change_default')->default(true)->after('password');
            $table->dateTime('next_change')->nullable()->after('change_default');
        });
        DpStudent::where('change_default', true)->update(['change_default' => false, 'next_change' => DB::raw('DATE_ADD(updated_at,INTERVAL 3 MONTH)')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->dropColumn('change_default');
        });
    }
};

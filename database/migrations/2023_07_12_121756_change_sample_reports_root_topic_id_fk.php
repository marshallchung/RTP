<?php

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
        DB::table('sample_reports')->delete();

        Schema::table('sample_reports', function (Blueprint $table) {
            DB::statement('ALTER TABLE sample_reports DROP FOREIGN KEY sample_reports_root_topic_id_foreign');
            $table->foreign('root_topic_id')->references('id')->on('topics')
                ->onUpdate('cascade')->onDelete('cascade');
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

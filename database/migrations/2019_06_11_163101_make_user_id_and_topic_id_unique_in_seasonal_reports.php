<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakeUserIdAndTopicIdUniqueInSeasonalReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seasonal_reports', function (Blueprint $table) {
            $table->unique(['user_id', 'topic_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seasonal_reports', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'topic_id']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SetDefaultOfYearAndSeasonInSeasonalReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seasonal_reports', function (Blueprint $table) {
            $table->smallInteger('year')->unsigned()->default(0)->change();
            $table->boolean('season')->default(0)->change();
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
            $table->smallInteger('year')->unsigned()->change();
            $table->boolean('season')->change();
        });
    }
}

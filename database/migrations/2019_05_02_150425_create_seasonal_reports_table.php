<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeasonalReportsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasonal_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('topic_id');
            $table->smallInteger('year')->unsigned();
            $table->boolean('season');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seasonal_reports');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDcStagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dc_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index('USER_ID');
            $table->integer('dc_unit_id')->unsigned()->index('DC_UNIT_ID');
            $table->boolean('stage');
            $table->boolean('term');
            $table->boolean('active');
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
        Schema::drop('dc_stages');
    }
}

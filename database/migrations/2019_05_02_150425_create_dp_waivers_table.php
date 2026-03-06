<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpWaiversTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_waivers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dp_score_id')->unique('score_id_UNIQUE');
            $table->string('name', 256)->default('');
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
        Schema::drop('dp_waivers');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDcUnitsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dc_units', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->integer('population')->unsigned()->default(0);
            $table->smallInteger('county_id')->unsigned();
            $table->text('location');
            $table->boolean('is_experienced')->default(0);
            $table->text('environment');
            $table->text('risk');
            $table->string('pattern', 45);
            $table->string('manager', 45);
            $table->string('phone', 45);
            $table->string('email', 128);
            $table->string('manager_position', 256)->default('');
            $table->string('manager_address', 256)->default('');
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
        Schema::drop('dc_units');
    }
}

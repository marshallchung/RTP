<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpTrainingInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_training_institutions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('名稱');
            $table->smallInteger('county_id')->unsigned()->comment('縣市');
            $table->string('phone')->nullable()->comment('連絡電話');
            $table->string('address')->nullable()->comment('訓練地址');
            $table->text('charging_standard')->nullable()->comment('收費標準');
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
        Schema::drop('dp_training_institutions');
    }
}

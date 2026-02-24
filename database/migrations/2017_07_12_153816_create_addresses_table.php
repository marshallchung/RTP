<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unit')->default('')->comment('單位');
            $table->string('title')->default('')->comment('職稱');
            $table->string('name')->default('')->comment('姓名');
            $table->string('phone')->default('')->comment('公務電話');
            $table->string('mobile')->default('')->comment('行動電話');
            $table->string('email')->default('')->comment('電子郵件');
            $table->unsignedInteger('county_id')->nullable()->comment('縣市');
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
        Schema::drop('addresses');
    }
}

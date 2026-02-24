<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpCivilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_civils', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('名稱');
            $table->string('phone')->nullable()->comment('連絡電話');
            $table->string('address')->nullable()->comment('機構地址');
            $table->string('front_man')->nullable()->comment('代表人');
            $table->text('business')->nullable()->comment('辦理業務');
            $table->string('url')->nullable()->comment('網址');
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
        Schema::drop('dp_civils');
    }
}

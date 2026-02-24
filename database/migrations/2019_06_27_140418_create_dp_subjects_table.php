<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('科目名稱');
            $table->integer('position')->default(0)->comment('排序位置');
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
        Schema::drop('dp_subjects');
    }
}

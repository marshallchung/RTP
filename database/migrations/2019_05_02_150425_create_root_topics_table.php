<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRootTopicsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('root_topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('work_type', 25);
            $table->string('year', 25);
            $table->string('title', 256);
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
        Schema::drop('root_topics');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpTeachersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('type', 45)->default('');
            $table->string('name');
            $table->string('belongsTo', 128)->default('');
            $table->mediumText('content');
            $table->string('email', 45)->default('');
            $table->string('phone', 45)->default('');
            $table->string('mobile', 45)->default('');
            $table->string('courses', 256)->default('');
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
        Schema::drop('dp_teachers');
    }
}

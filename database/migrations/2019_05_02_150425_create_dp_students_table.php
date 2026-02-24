<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpStudentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->smallInteger('county_id')->unsigned();
            $table->string('username');
            $table->string('password');
            $table->string('gender', 10)->default('');
            $table->string('TID', 45)->default('');
            $table->string('name');
            $table->string('birth', 45);
            $table->string('field', 64)->nullable()->default('');
            $table->string('email', 45)->default('');
            $table->string('phone', 45)->default('');
            $table->string('mobile', 45)->default('');
            $table->string('address', 256)->default('');
            $table->string('community', 45)->nullable();
            $table->string('unit_first_course', 128)->default('');
            $table->dateTime('date_first_finish')->nullable();
            $table->string('unit_second_course', 128)->default('');
            $table->dateTime('date_second_finish')->nullable();
            $table->boolean('active');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->dateTime('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dp_students');
    }
}

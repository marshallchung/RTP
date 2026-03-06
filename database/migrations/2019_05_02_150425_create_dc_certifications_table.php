<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDcCertificationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dc_certifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('dc_unit_id')->unsigned();
            $table->string('type', 45)->default('');
            $table->string('name');
            $table->mediumText('content');
            $table->string('email', 45)->default('');
            $table->string('phone', 45)->default('');
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
        Schema::drop('dc_certifications');
    }
}

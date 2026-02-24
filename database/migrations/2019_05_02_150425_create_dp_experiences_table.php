<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDpExperiencesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dp_experiences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dp_student_id')->index('dp_student_id');
            $table->string('unit', 45)->nullable();
            $table->string('document_code', 45)->nullable();
            $table->string('name', 256)->default('');
            $table->string('date', 45);
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
        Schema::drop('dp_experiences');
    }
}

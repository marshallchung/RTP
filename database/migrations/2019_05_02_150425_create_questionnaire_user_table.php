<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionnaireUserTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->smallInteger('status')->unsigned();
            $table->mediumText('answers')->nullable();
            $table->mediumText('comments')->nullable();
            $table->timestamps();
            $table->index(['questionnaire_id', 'user_id'], 'INDEXES');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questionnaire_user');
    }
}

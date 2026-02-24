<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('questionnaire_id')->index('INDEX');
            $table->integer('seq')->unsigned();
            $table->string('code', 45);
            $table->boolean('indent');
            $table->string('type', 45);
            $table->text('content')->nullable();
            $table->text('options')->nullable();
            $table->boolean('upload')->default(0);
            $table->float('gain', 10, 0)->nullable();
            $table->float('extra_gain', 10, 0)->nullable();
            $table->float('score_limit', 10, 0)->nullable();
            $table->timestamps();
            $table->boolean('comment')->default(0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questions');
    }
}

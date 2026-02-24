<?php

use Illuminate\Database\Migrations\Migration;

class StoreScoreTypeInQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $repo = app(\App\Nfa\Repositories\QuestionRepository::class);
        $repo->updateAllScoreType(true);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('questions')->update(['score_type' => null]);
    }
}

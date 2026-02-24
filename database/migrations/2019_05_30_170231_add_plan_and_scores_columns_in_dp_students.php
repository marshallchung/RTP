<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPlanAndScoresColumnsInDpStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->string('plan')->nullable()->comment('培訓計畫名稱')->after('date_second_finish');
            $table->integer('score_academic')->nullable()->comment('學科測驗成績')->after('plan');
            $table->integer('score_physical')->nullable()->comment('術科測驗成績')->after('score_academic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->dropColumn('plan');
            $table->dropColumn('score_academic');
            $table->dropColumn('score_physical');
        });
    }
}

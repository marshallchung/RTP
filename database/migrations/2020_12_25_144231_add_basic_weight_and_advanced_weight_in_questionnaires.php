<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBasicWeightAndAdvancedWeightInQuestionnaires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->float('basic_weight')->default(1)->comment('基本指標加權')->after('original_total_score');
            $table->float('advanced_weight')->default(1)->comment('進階指標加權')->after('basic_weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('basic_weight');
            $table->dropColumn('advanced_weight');
        });
    }
}

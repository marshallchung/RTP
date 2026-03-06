<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddReviewColumnsInDpWaivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_waivers', function (Blueprint $table) {
            $table->boolean('review_result')->nullable()->comment('審查結果')->after('name');
            $table->text('review_comment')->nullable()->comment('審查意見')->after('review_result');
            $table->timestamp('review_at')->nullable()->comment('審查時間')->after('review_comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dp_waivers', function (Blueprint $table) {
            $table->dropColumn('review_result');
            $table->dropColumn('review_comment');
            $table->dropColumn('review_at');
        });
    }
}

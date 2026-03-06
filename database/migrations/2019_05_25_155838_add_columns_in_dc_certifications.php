<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnsInDcCertifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dc_certifications', function (Blueprint $table) {
            $table->string('term')->nullable()->after('dc_unit_id');
            $table->boolean('review_result')->nullable()->comment('審查結果')->after('term');
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
        Schema::table('dc_certifications', function (Blueprint $table) {
            $table->dropColumn('term');
            $table->dropColumn('review_result');
            $table->dropColumn('review_comment');
            $table->dropColumn('review_at');
        });
    }
}

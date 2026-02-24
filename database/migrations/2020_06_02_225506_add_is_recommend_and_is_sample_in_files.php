<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIsRecommendAndIsSampleInFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->boolean('is_recommend')->default(false)->comment('推薦為優良範本')->after('opendata');
            $table->boolean('is_sample')->default(false)->comment('優良範本')->after('is_recommend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('is_recommend');
            $table->dropColumn('is_sample');
        });
    }
}

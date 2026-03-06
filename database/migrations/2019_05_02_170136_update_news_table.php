<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['news_type_id']);
            $table->dropColumn('news_type_id');

            $table->string('sort', 45)->default('')->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('sort');

            $table->unsignedInteger('news_type_id')->nullable()->comment('分類');

            $table->foreign('news_type_id')->references('id')->on('news_types')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }
}

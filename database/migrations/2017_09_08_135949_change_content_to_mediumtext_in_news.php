<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeContentToMediumtextInNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('content', 65536)->change();
        });
        Schema::table('news', function (Blueprint $table) {
            $table->mediumText('content')->change();
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
            $table->string('content', 65536)->change();
        });
        Schema::table('news', function (Blueprint $table) {
            $table->text('content')->change();
        });
    }
}

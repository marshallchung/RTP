<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTitleInDpTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_teachers', function (Blueprint $table) {
            $table->string('title')->nullable()->comment('職別')->after('belongsTo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dp_teachers', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
}

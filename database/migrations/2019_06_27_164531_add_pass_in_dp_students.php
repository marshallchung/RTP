<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPassInDpStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->boolean('pass')->default(false)->comment('認證結果是否合格')->after('score_physical');
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
            $table->dropColumn('pass');
        });
    }
}

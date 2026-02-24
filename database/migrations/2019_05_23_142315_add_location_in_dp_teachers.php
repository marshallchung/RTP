<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLocationInDpTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_teachers', function (Blueprint $table) {
            $table->string('location')->nullable()->comment('區域位置');
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
            $table->dropColumn('location');
        });
    }
}

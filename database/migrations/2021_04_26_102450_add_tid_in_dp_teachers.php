<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTidInDpTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_teachers', function (Blueprint $table) {
            $table->string('tid', 20)->nullable()->comment('身分證')->after('user_id');
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
            $table->dropColumn('tid');
        });
    }
}

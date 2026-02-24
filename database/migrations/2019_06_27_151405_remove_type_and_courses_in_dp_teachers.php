<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveTypeAndCoursesInDpTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_teachers', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('courses');
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
            $table->string('type', 45)->default('')->after('user_id');
            $table->string('courses', 256)->default('')->after('mobile');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakeUnitSecondCourseNullableInDpStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->string('unit_second_course', 128)->default(null)->nullable()->change();
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
            $table->string('unit_second_course', 128)->default('')->nullable(false)->change();
        });
    }
}

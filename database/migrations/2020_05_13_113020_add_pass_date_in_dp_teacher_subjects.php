<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPassDateInDpTeacherSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_teacher_subjects', function (Blueprint $table) {
            $table->date('pass_date')->nullable()->comment('通過日期')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dp_teacher_subjects', function (Blueprint $table) {
            $table->dropColumn('pass_date');
        });
    }
}

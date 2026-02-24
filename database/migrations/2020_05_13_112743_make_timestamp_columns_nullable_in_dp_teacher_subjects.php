<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakeTimestampColumnsNullableInDpTeacherSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_teacher_subjects', function (Blueprint $table) {
            $table->timestamp('created_at')->default(null)->nullable()->change();
            $table->timestamp('updated_at')->default(null)->nullable()->change();
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
            //
        });
    }
}

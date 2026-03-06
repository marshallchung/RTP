<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleToDpStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->string('Multiple')->nullable()->comment('多元化防災士')->after("date_second_finish");
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
            $table->dropColumn('Multiple');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddActiveInDpStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->boolean('active');
        });
        \App\DpStudent::query()->update(['active' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}

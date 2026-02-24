<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOrganizerInDpCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_courses', function (Blueprint $table) {
            $table->string('organizer')->nullable()->comment('主辦單位')->after('county_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dp_courses', function (Blueprint $table) {
            $table->dropColumn('organizer');
        });
    }
}

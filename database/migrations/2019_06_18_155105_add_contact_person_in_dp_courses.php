<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddContactPersonInDpCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_courses', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->comment('聯絡人')->after('content');
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
            $table->dropColumn('contact_person');
        });
    }
}

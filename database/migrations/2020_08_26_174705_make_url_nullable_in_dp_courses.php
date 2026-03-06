<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUrlNullableInDpCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_courses', function (Blueprint $table) {
            $table->string('url', 256)->default(null)->nullable()->change();
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
            $table->string('url', 256)->default('')->nullable(false)->change();
        });
    }
}

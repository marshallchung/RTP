<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveColumnsInDcCertifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dc_certifications', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('name');
            $table->dropColumn('content');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('courses');
            $table->dropColumn('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dc_certifications', function (Blueprint $table) {
            $table->string('type', 45)->default('')->after('dc_unit_id');
            $table->string('name')->after('type');
            $table->mediumText('content')->after('name');
            $table->string('email', 45)->default('')->after('content');
            $table->string('phone', 45)->default('')->after('email');
            $table->string('courses', 256)->default('')->after('phone');
            $table->boolean('active')->after('courses');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnsInDpStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_students', function (Blueprint $table) {
            $table->string('certificate')->nullable()->comment('證書編號')->after('name');
            $table->string('education')->nullable()->comment('最高學歷')->after('field');
            $table->string('service')->nullable()->comment('服務單位')->after('education');
            $table->string('title')->nullable()->comment('職稱')->after('service');
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
            $table->dropColumn('certificate');
            $table->dropColumn('education');
            $table->dropColumn('service');
            $table->dropColumn('title');
        });
    }
}

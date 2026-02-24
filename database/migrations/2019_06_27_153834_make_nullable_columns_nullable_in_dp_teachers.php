<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakeNullableColumnsNullableInDpTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_teachers', function (Blueprint $table) {
            $table->string('belongsTo', 128)->default('')->nullable()->change();
            $table->mediumText('content')->nullable()->change();
            $table->string('email', 45)->default('')->nullable()->change();
            $table->string('phone', 45)->default('')->nullable()->change();
            $table->string('mobile', 45)->default('')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dp_teachers', function (Blueprint $table) {
            $table->string('belongsTo', 128)->default('')->nullable(false)->change();
            $table->mediumText('content')->nullable(false)->change();
            $table->string('email', 45)->default('')->nullable(false)->change();
            $table->string('phone', 45)->default('')->nullable(false)->change();
            $table->string('mobile', 45)->default('')->nullable(false)->change();
        });
    }
}

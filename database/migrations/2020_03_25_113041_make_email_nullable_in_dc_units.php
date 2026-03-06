<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakeEmailNullableInDcUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dc_units', function (Blueprint $table) {
            $table->string('email', 128)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dc_units', function (Blueprint $table) {
            $table->string('email', 128)->nullable(false)->change();
        });
    }
}

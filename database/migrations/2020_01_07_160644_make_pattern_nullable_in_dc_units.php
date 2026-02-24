<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakePatternNullableInDcUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dc_units', function (Blueprint $table) {
            $table->string('pattern', 45)->nullable()->change();
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
            $table->string('pattern', 45)->nullable(false)->change();
        });
    }
}

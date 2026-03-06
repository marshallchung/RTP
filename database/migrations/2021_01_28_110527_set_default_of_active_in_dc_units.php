<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetDefaultOfActiveInDcUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dc_units', function (Blueprint $table) {
            $table->boolean('active')->default(false)->change();
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
            $table->boolean('active')->default(null)->change();
        });
    }
}

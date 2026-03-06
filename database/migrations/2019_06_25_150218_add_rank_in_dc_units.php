<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRankInDcUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dc_units', function (Blueprint $table) {
            $table->string('rank')->nullable()->comment('星等')->after('manager_address');
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
            $table->dropColumn('rank');
        });
    }
}

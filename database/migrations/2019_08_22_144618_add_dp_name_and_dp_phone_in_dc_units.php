<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddDpNameAndDpPhoneInDcUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dc_units', function (Blueprint $table) {
            $table->string('dp_name')->nullable()->comment('防災士姓名')->after('manager_address');
            $table->string('dp_phone')->nullable()->comment('防災士電話')->after('dp_name');
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
            $table->dropColumn('dp_name');
            $table->dropColumn('dp_phone');
        });
    }
}

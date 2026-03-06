<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveChargingStandardInDpTrainingInstitutions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_training_institutions', function (Blueprint $table) {
            $table->dropColumn('charging_standard');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dp_training_institutions', function (Blueprint $table) {
            $table->text('charging_standard')->nullable()->comment('收費標準')->after('address');
        });
    }
}

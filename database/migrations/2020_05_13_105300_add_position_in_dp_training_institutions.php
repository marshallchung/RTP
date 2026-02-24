<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPositionInDpTrainingInstitutions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_training_institutions', function (Blueprint $table) {
            $table->integer('position')->default(0)->comment('排序位置')->after('active');
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
            $table->dropColumn('position');
        });
    }
}

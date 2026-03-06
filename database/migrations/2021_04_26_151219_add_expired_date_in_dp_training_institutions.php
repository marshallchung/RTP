<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiredDateInDpTrainingInstitutions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_training_institutions', function (Blueprint $table) {
            $table->date('expired_date')->nullable()->comment('有效期限')->after('active');
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
            $table->dropColumn('expired_date');
        });
    }
}

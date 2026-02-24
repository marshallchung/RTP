<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUrlInDpTrainingInstitutions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_training_institutions', function (Blueprint $table) {
            $table->text('url')->nullable()->comment('官方網址')->after('address');
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
            $table->dropColumn('url');
        });
    }
}

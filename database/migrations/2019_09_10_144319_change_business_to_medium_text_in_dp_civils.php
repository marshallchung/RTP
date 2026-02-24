<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeBusinessToMediumTextInDpCivils extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dp_civils', function (Blueprint $table) {
            $table->dropColumn('business');
        });
        Schema::table('dp_civils', function (Blueprint $table) {
            $table->mediumText('business')->nullable()->comment('辦理業務')->after('front_man');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dp_civils', function (Blueprint $table) {
            $table->dropColumn('business');
        });
        Schema::table('dp_civils', function (Blueprint $table) {
            $table->text('business')->nullable()->comment('辦理業務')->after('front_man');
        });
    }
}

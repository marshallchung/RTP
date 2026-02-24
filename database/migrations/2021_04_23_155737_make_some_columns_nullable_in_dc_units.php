<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSomeColumnsNullableInDcUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dc_units', function (Blueprint $table) {
            $table->text('location')->nullable()->change();
            $table->text('environment')->nullable()->change();
            $table->text('risk')->nullable()->change();
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
            $table->text('location')->nullable(false)->change();
            $table->text('environment')->nullable(false)->change();
            $table->text('risk')->nullable(false)->change();
        });
    }
}

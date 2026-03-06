<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountableInCounters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->string('countable_type')->nullable();
            $table->string('countable_id')->nullable();
            $table->unique(['countable_type', 'countable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->dropUnique(['countable_type', 'countable_id']);
            $table->dropColumn('countable_type');
            $table->dropColumn('countable_id');
        });
    }
}

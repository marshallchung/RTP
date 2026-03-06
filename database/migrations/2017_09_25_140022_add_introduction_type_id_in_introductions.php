<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIntroductionTypeIdInIntroductions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('introductions', function (Blueprint $table) {
            $table->unsignedInteger('introduction_type_id')->nullable();
            $table->foreign('introduction_type_id')->references('id')->on('introduction_types')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('introductions', function (Blueprint $table) {
            $table->dropForeign(['introduction_type_id']);
            $table->dropColumn('introduction_type_id');
        });
    }
}

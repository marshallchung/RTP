<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakeColumnsNullableInAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('unit')->default(null)->nullable()->change();
            $table->string('title')->default(null)->nullable()->change();
            $table->string('name')->default(null)->nullable()->change();
            $table->string('phone')->default(null)->nullable()->change();
            $table->string('mobile')->default(null)->nullable()->change();
            $table->string('email')->default(null)->nullable()->change();
            $table->timestamp('created_at')->default(null)->nullable()->change();
            $table->timestamp('updated_at')->default(null)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('unit')->default('')->nullable(false)->change();
            $table->string('title')->default('')->nullable(false)->change();
            $table->string('name')->default('')->nullable(false)->change();
            $table->string('phone')->default('')->nullable(false)->change();
            $table->string('mobile')->default('')->nullable(false)->change();
            $table->string('email')->default('')->nullable(false)->change();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;

class SetDefaultUserTypeInRoleUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('role_user')->update(['user_type' => \App\User::class]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('role_user')->update(['user_type' => null]);
    }
}

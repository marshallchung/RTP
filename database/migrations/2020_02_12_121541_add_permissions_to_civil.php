<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPermissionsToCivil extends Migration
{
    private static $permissionNames = [
        'DP-news-manage',
        'DP-training-institution-manage',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('civil', function (Blueprint $table) {
            $role = \App\Role::whereName('civil')->first();
            $permissions = \App\Permission::whereIn('name', self::$permissionNames)->get();
            $role->attachPermissions($permissions);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('civil', function (Blueprint $table) {
            $role = \App\Role::whereName('civil')->first();
            $permissions = \App\Permission::whereIn('name', self::$permissionNames)->get();
            $role->detachPermissions($permissions);
        });
    }
}

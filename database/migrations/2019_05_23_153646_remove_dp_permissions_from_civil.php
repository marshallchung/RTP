<?php

use Illuminate\Database\Migrations\Migration;

class RemoveDpPermissionsFromCivil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var \App\Role $civilRole */
        $civilRole = \App\Role::where('name', 'civil')->first();
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Permission[] $permissions */
        $permissions = \App\Permission::whereIn('name', [
            'DP-teachers-manage',
            'DP-students-manage',
            'DP-courses-manage',
            'DP-scores-manage',
            'DP-waivers-manage',
        ])->get();
        $civilRole->detachPermissions($permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** @var \App\Role $civilRole */
        $civilRole = \App\Role::where('name', 'civil')->first();
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Permission[] $permissions */
        $permissions = \App\Permission::whereIn('name', [
            'DP-teachers-manage',
            'DP-students-manage',
            'DP-courses-manage',
            'DP-scores-manage',
            'DP-waivers-manage',
        ])->get();
        $civilRole->permissions()->syncWithoutDetaching($permissions);
    }
}

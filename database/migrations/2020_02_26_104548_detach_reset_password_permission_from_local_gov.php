<?php

use Illuminate\Database\Migrations\Migration;

class DetachResetPasswordPermissionFromLocalGov extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var \App\Permission $permission */
        $permission = \App\Permission::whereName('reset-password')->first();
        /** @var \App\Role $role */
        $role = \App\Role::whereName('localGov')->first();
        if (!$permission || !$role) {
            return;
        }
        $role->detachPermission($permission);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** @var \App\Permission $permission */
        $permission = \App\Permission::whereName('reset-password')->first();
        /** @var \App\Role $role */
        $role = \App\Role::whereName('localGov')->first();
        if (!$permission || !$role) {
            return;
        }
        $role->attachPermission($permission);
    }
}

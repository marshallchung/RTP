<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class RemoveDpStudentsCreatePermissionFromDpTraining extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::whereName('DP-students-create')->first();
        $role = Role::whereName('dp-training')->first();
        if ($permission && $role) {
            $role->detachPermission($permission);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $permission = Permission::whereName('DP-students-create')->first();
        $role = Role::whereName('dp-training')->first();
        if ($permission && $role) {
            $role->attachPermission($permission);
        }
    }
}

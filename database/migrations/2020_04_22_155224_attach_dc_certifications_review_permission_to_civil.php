<?php

use Illuminate\Database\Migrations\Migration;

class AttachDcCertificationsReviewPermissionToCivil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var \App\Permission $permission */
        $permission = \App\Permission::whereName('DC-certifications-review')->first();
        /** @var \App\Role $role */
        $role = \App\Role::whereName('civil')->first();
        if (!$permission || !$role) {
            return;
        }
        $role->attachPermission($permission);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** @var \App\Permission $permission */
        $permission = \App\Permission::whereName('DC-certifications-review')->first();
        /** @var \App\Role $role */
        $role = \App\Role::whereName('civil')->first();
        if (!$permission || !$role) {
            return;
        }
        $role->detachPermission($permission);
    }
}

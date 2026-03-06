<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddDpExperiencesManageToCivil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Permission $permission */
        $permission = Permission::where('name', 'DP-experiences-manage')->first();
        /** @var Role $civil */
        $civil = Role::where('name', 'civil')->first();

        $civil->attachPermission($permission);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** @var Permission $permission */
        $permission = Permission::where('name', 'DP-experiences-manage')->first();
        /** @var Role $civil */
        $civil = Role::where('name', 'civil')->first();

        $civil->detachPermission($permission);
    }
}

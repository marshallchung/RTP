<?php

use Illuminate\Database\Migrations\Migration;

class AddDpWaiversReviewPermissionToRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = \App\Permission::whereName('DP-waivers-review')->first();

        $civil = \App\Role::whereName('civil')->first();
        $dpTraining = \App\Role::whereName('dp-training')->first();

        $civil->attachPermission($permission);
        $dpTraining->attachPermission($permission);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $permission = \App\Permission::whereName('DP-waivers-review')->first();

        $civil = \App\Role::whereName('civil')->first();
        $dpTraining = \App\Role::whereName('dp-training')->first();

        $civil->detachPermission($permission);
        $dpTraining->detachPermission($permission);
    }
}

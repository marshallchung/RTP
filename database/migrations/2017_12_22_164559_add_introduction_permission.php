<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddIntroductionPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $introductionManagePerm = new Permission;
        $introductionManagePerm->name = 'introduction-manage';
        $introductionManagePerm->display_name = 'Manage Introduction';
        $introductionManagePerm->description = 'Manage Introduction';
        $introductionManagePerm->save();

        $admin = Role::where('name', 'admin')->first();
        $nfa = Role::where('name', 'nfa')->first();

        DB::table('permission_role')->insert([
            ['permission_id' => $introductionManagePerm->id, 'role_id' => $admin->id],
            ['permission_id' => $introductionManagePerm->id, 'role_id' => $nfa->id],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        Permission::where('name', 'introduction-manage')->delete();
    }
}

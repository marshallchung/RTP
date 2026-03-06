<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddResultiiiManagePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'resultiii-manage',
            'display_name' => 'Resultiii Manage',
            'description'  => '三期成果資料管理',
        ]);

        $admin = Role::where('name', 'admin')->first();
        $nfa = Role::where('name', 'nfa')->first();

        DB::table('permission_role')->insert([
            ['permission_id' => $permission->id, 'role_id' => $admin->id],
            ['permission_id' => $permission->id, 'role_id' => $nfa->id],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'resultiii-manage')->delete();
    }
}

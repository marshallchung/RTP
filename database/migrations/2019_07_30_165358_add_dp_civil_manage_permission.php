<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddDpCivilManagePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'DP-civil-manage',
            'display_name' => 'Manage DP Civil',
            'description'  => 'Manage Disaster Preventor Civil',
        ]);

        $admin = Role::where('name', 'admin')->first();
        $nfa = Role::where('name', 'nfa')->first();
        $civil = Role::where('name', 'civil')->first();

        DB::table('permission_role')->insert([
            ['permission_id' => $permission->id, 'role_id' => $admin->id],
            ['permission_id' => $permission->id, 'role_id' => $nfa->id],
            ['permission_id' => $permission->id, 'role_id' => $civil->id],
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
        Permission::where('name', 'DP-civil-manage')->delete();
    }
}

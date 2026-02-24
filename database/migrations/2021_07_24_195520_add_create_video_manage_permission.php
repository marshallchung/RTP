<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddCreateVideoManagePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'create-video',
            'display_name' => 'create-video',
            'description'  => '宣導影片及文宣專區',
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
        Permission::where('name', 'create-video')->delete();
    }
}

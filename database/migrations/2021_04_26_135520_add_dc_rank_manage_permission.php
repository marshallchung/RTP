<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddDcRankManagePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'DC-rank-manage',
            'display_name' => 'Manage DC Rank',
            'description'  => 'Manage Rank of DcUnit',
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
        Permission::where('name', 'DC-rank-manage')->delete();
    }
}

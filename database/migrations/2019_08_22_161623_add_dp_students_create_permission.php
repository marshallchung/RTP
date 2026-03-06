<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddDpStudentsCreatePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'DP-students-create',
            'display_name' => 'Create DP Student',
            'description'  => 'Create Disaster Preventor Student',
        ]);

        $admin = Role::where('name', 'admin')->first();
        $nfa = Role::where('name', 'nfa')->first();
        $civil = Role::where('name', 'civil')->first();
        $dpTraining = Role::where('name', 'dp-training')->first();

        DB::table('permission_role')->insert([
            ['permission_id' => $permission->id, 'role_id' => $admin->id],
            ['permission_id' => $permission->id, 'role_id' => $nfa->id],
            ['permission_id' => $permission->id, 'role_id' => $civil->id],
            ['permission_id' => $permission->id, 'role_id' => $dpTraining->id],
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
        Permission::where('name', 'DP-students-create')->delete();
    }
}

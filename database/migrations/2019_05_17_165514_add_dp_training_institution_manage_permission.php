<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddDpTrainingInstitutionManagePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'DP-training-institution-manage',
            'display_name' => 'Manage DP Training Institution',
            'description'  => 'Manage Disaster Preventor Training Institution',
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
     * @throws Exception
     */
    public function down()
    {
        Permission::where('name', 'DP-training-institution-manage')->delete();
    }
}

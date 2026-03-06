<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddCreatePlansForCountyPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'create-plans-for-county',
            'display_name' => 'Create Plans for other counties',
            'description'  => 'Create Plans for other counties',
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
        Permission::where('name', 'create-plans-for-county')->delete();
    }
}

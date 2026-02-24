<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddDpWaiversReviewPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'DP-waivers-review',
            'display_name' => 'Review DP Waivers',
            'description'  => 'Review Disaster Preventor Waivers',
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
        Permission::where('name', 'DP-waivers-review')->delete();
    }
}

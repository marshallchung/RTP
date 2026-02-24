<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddModifyStaticPagePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'modify-static-page',
            'display_name' => 'Modify StaticPage',
            'description'  => 'Modify StaticPage',
        ]);

        $admin = Role::where('name', 'admin')->first();

        DB::table('permission_role')->insert([
            ['permission_id' => $permission->id, 'role_id' => $admin->id],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'modify-static-page')->delete();
    }
}

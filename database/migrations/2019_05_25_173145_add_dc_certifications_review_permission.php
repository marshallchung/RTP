<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddDcCertificationsReviewPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'DC-certifications-review',
            'display_name' => 'Review DP Certifications',
            'description'  => 'Review Disaster Preventor Certifications',
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
        Permission::where('name', 'DC-certifications-review')->delete();
    }
}

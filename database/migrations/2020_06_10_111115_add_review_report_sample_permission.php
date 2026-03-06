<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddReviewReportSamplePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::create([
            'name'         => 'review-report-sample',
            'display_name' => 'Review report sample',
            'description'  => 'Review report sample recommended by county',
        ]);

        /** @var Role $admin */
        $admin = Role::where('name', 'admin')->first();
        /** @var Role $nfa */
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
        Permission::where('name', 'review-report-sample')->delete();
    }
}

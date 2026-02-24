<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddNewPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // View reports
        $viewReports = new Permission;

        $viewReports->name = 'view-all-reports';
        $viewReports->display_name = 'View All Reports';
        $viewReports->description = 'View reports regardless of public date';

        $viewReports->save();

        // Create report public date
        $createReportPublicDate = new Permission;
        $createReportPublicDate->name = 'create-report-public-date';
        $createReportPublicDate->display_name = 'Create Report Public Date';
        $createReportPublicDate->description = 'CRUD Report public date';

        $createReportPublicDate->save();

        $admin = Role::where('name', 'admin')->first();
        $nfa = Role::where('name', 'nfa')->first();
        $committee = Role::where('name', 'committee')->first();

        DB::table('permission_role')->insert([
            ['permission_id' => $viewReports->id, 'role_id' => $admin->id],
            ['permission_id' => $createReportPublicDate->id, 'role_id' => $admin->id],
            ['permission_id' => $viewReports->id, 'role_id' => $nfa->id],
            ['permission_id' => $createReportPublicDate->id, 'role_id' => $nfa->id],
            ['permission_id' => $viewReports->id, 'role_id' => $committee->id],
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
        Permission::where('name', 'view-all-reports')->delete();
        Permission::where('name', 'create-report-public-date')->delete();
    }
}

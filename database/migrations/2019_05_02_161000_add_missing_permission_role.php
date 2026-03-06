<?php

use Illuminate\Database\Migrations\Migration;

class AddMissingPermissionRole extends Migration
{
    private static $permissionRoles = [
        ['create-news', 'admin'],
        ['create-uploads', 'admin'],
        ['view-committee', 'admin'],
        ['reset-password', 'admin'],
        ['view-all-reports', 'admin'],
        ['create-report-public-date', 'admin'],
        ['introduction-manage', 'admin'],
        ['switch-role', 'admin'],
        ['DP-news-manage', 'admin'],
        ['DP-teachers-manage', 'admin'],
        ['DP-courses-manage', 'admin'],
        ['DP-students-manage', 'admin'],
        ['DP-scores-manage', 'admin'],
        ['DP-waivers-manage', 'admin'],
        ['DP-experiences-manage', 'admin'],
        ['DC-news-manage', 'admin'],
        ['DC-schedules-manage', 'admin'],
        ['DC-units-manage', 'admin'],
        ['DC-stages-manage', 'admin'],
        ['DC-certifications-manage', 'admin'],
        ['create-references', 'admin'],
        ['admin-operations', 'admin'],
        ['view-all-seasonalReports', 'admin'],
        ['create-QAs', 'admin'],
        ['view-plans', 'admin'],
        ['create-guidance', 'admin'],
        ['create-reportTerms', 'admin'],
        ['create-publicTerms', 'admin'],
        ['create-publicUrls', 'admin'],
        ['DP-resources-manage', 'admin'],
        ['front-introduction-manage', 'admin'],
        ['create-central-reports', 'admin'],
        ['create-questionnaires', 'admin'],
        ['create-news', 'nfa'],
        ['create-uploads', 'nfa'],
        ['view-committee', 'nfa'],
        ['reset-password', 'nfa'],
        ['view-all-reports', 'nfa'],
        ['create-report-public-date', 'nfa'],
        ['introduction-manage', 'nfa'],
        ['switch-role', 'nfa'],
        ['DP-news-manage', 'nfa'],
        ['DP-teachers-manage', 'nfa'],
        ['DP-courses-manage', 'nfa'],
        ['DP-students-manage', 'nfa'],
        ['DP-scores-manage', 'nfa'],
        ['DP-waivers-manage', 'nfa'],
        ['DP-experiences-manage', 'nfa'],
        ['DC-news-manage', 'nfa'],
        ['DC-schedules-manage', 'nfa'],
        ['DC-units-manage', 'nfa'],
        ['DC-certifications-manage', 'nfa'],
        ['create-references', 'nfa'],
        ['admin-operations', 'nfa'],
        ['view-all-seasonalReports', 'nfa'],
        ['create-QAs', 'nfa'],
        ['view-plans', 'nfa'],
        ['create-guidance', 'nfa'],
        ['create-reportTerms', 'nfa'],
        ['create-publicTerms', 'nfa'],
        ['create-publicUrls', 'nfa'],
        ['DP-resources-manage', 'nfa'],
        ['front-introduction-manage', 'nfa'],
        ['create-central-reports', 'nfa'],
        ['create-questionnaires', 'nfa'],
        ['create-reports', 'localGov'],
        ['reset-password', 'localGov'],
        ['switch-role', 'localGov'],
        ['DP-teachers-manage', 'localGov'],
        ['DP-courses-manage', 'localGov'],
        ['DP-students-manage', 'localGov'],
        ['DP-scores-manage', 'localGov'],
        ['DP-waivers-manage', 'localGov'],
        ['DP-experiences-manage', 'localGov'],
        ['DC-schedules-manage', 'localGov'],
        ['DC-units-manage', 'localGov'],
        ['DC-stages-manage', 'localGov'],
        ['DC-certifications-manage', 'localGov'],
        ['create-seasonalReports', 'localGov'],
        ['create-plans', 'localGov'],
        ['view-plans', 'localGov'],
        ['create-oldReports', 'localGov'],
        ['create-publicUrls', 'localGov'],
        ['create-reports', 'districts'],
        ['switch-role', 'districts'],
        ['DC-schedules-manage', 'districts'],
        ['DC-units-manage', 'districts'],
        ['DC-stages-manage', 'districts'],
        ['DC-certifications-manage', 'districts'],
        ['view-plans', 'districts'],
        ['create-oldReports', 'districts'],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::$permissionRoles as $permissionRole) {
            $permission = \App\Permission::where('name', $permissionRole[0])->first();
            $role = \App\Role::where('name', $permissionRole[1])->first();
            if (!$permission || !$role) {
                continue;
            }
            try {
                $role->attachPermission($permission);
            } catch (\Exception $exception) {
                //
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (self::$permissionRoles as $permissionRole) {
            $permission = \App\Permission::where('name', $permissionRole[0])->first();
            $role = \App\Role::where('name', $permissionRole[1])->first();
            if (!$permission || !$role) {
                continue;
            }
            try {
                $role->detachPermission($permission);
            } catch (\Exception $exception) {
                //
            }
        }
    }
}

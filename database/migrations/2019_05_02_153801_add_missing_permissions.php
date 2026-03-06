<?php

use Illuminate\Database\Migrations\Migration;

class AddMissingPermissions extends Migration
{
    private static $permissions = [
        ['name' => 'switch-role', 'display_name' => 'Switch Role', 'description' => 'Switch User Role',],
        ['name' => 'DP-news-manage', 'display_name' => 'Manage DP News', 'description' => 'Manage Disaster Preventor News',],
        ['name' => 'DP-teachers-manage', 'display_name' => 'Manage DP Teachers', 'description' => 'Manage Disaster Preventor Teachers',],
        ['name' => 'DP-courses-manage', 'display_name' => 'Manage DP Courses', 'description' => 'Manage Disaster Preventor Courses',],
        ['name' => 'DP-students-manage', 'display_name' => 'Manage DP Students', 'description' => 'Manage Disaster Preventor Students',],
        ['name' => 'DP-scores-manage', 'display_name' => 'Manage DP Scores', 'description' => 'Manage Disaster Preventor Scores',],
        ['name' => 'DP-waivers-manage', 'display_name' => 'Manage DP Waivers', 'description' => 'Manage Disaster Preventor Waivers',],
        ['name' => 'DP-experiences-manage', 'display_name' => 'Manage DP Experiences', 'description' => 'Manage Disaster Preventor Experiences',],
        ['name' => 'DC-news-manage', 'display_name' => 'Manage DC News', 'description' => 'Manage Disaster Preventor News',],
        ['name' => 'DC-schedules-manage', 'display_name' => 'Manage DC Schedules', 'description' => 'Manage Disaster Preventor Schedules',],
        ['name' => 'DC-units-manage', 'display_name' => 'Manage DC Units', 'description' => 'Manage Disaster Preventor Units',],
        ['name' => 'DC-stages-manage', 'display_name' => 'Manage DC Stages', 'description' => 'Manage Disaster Preventor Stages',],
        ['name' => 'DC-certifications-manage', 'display_name' => 'Manage DC Certifications', 'description' => 'Manage Disaster Preventor Certifications',],
        ['name' => 'create-references', 'display_name' => 'Create References', 'description' => 'CRUB references',],
        ['name' => 'create-newReports', 'display_name' => 'Create newReports', 'description' => 'CRUD newReports',],
        ['name' => 'admin-operations', 'display_name' => 'Admin Operations', 'description' => 'Admin Operations',],
        ['name' => 'create-seasonalReports', 'display_name' => 'Create Seasonal Reports', 'description' => 'CRUD seasonal reports',],
        ['name' => 'create-plans', 'display_name' => 'Create Plans', 'description' => 'Create Plans',],
        ['name' => 'view-all-seasonalReports', 'display_name' => 'View All Seasonal Reports', 'description' => 'View All Seasonal Reports',],
        ['name' => 'create-QAs', 'display_name' => 'create QAs', 'description' => 'create QAs',],
        ['name' => 'view-plans', 'display_name' => 'view-plans', 'description' => 'view-plans',],
        ['name' => 'create-oldReports', 'display_name' => 'create-oldReports', 'description' => 'create-oldReports',],
        ['name' => 'create-guidance', 'display_name' => 'create-guidance', 'description' => 'create-guidance',],
        ['name' => 'create-reportTerms', 'display_name' => 'create-reportTerms', 'description' => 'create-reportTerms',],
        ['name' => 'create-publicTerms', 'display_name' => 'create-publicTerms', 'description' => 'create-publicTerms',],
        ['name' => 'create-publicUrls', 'display_name' => 'create-publicUrls', 'description' => 'create-publicUrls',],
        ['name' => 'DP-resources-manage', 'display_name' => 'DP Resources Manage', 'description' => 'DP-resources-manage',],
        ['name' => 'front-introduction-manage', 'display_name' => 'front-introduction-manage', 'description' => 'front-introduction-manage',],
        ['name' => 'create-central-reports', 'display_name' => 'create-central-reports', 'description' => 'create-central-reports',],
        ['name' => 'create-questionnaires', 'display_name' => 'create-questionnaires', 'description' => 'create-questionnaires',],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::$permissions as $permissionData) {
            \App\Permission::updateOrCreate(['name' => $permissionData['name']], $permissionData);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        foreach (self::$permissions as $permissionData) {
            \App\Permission::where('name', $permissionData['name'])->delete();
        }
    }
}

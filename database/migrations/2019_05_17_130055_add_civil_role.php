<?php

use Illuminate\Database\Migrations\Migration;

class AddCivilRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $civilRole = \App\Role::create([
            'name'         => 'civil',
            'display_name' => '社團法人臺灣防災教育訓練學會',
        ]);
        $permissions = \App\Permission::whereIn('name', [
            'DP-teachers-manage',
            'DP-students-manage',
            'DP-courses-manage',
            'DP-scores-manage',
            'DP-waivers-manage',
            'DC-units-manage',
            'DC-stages-manage',
            'DC-certifications-manage',
        ])->get();
        $civilRole->attachPermissions($permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        \App\Role::where('name', 'civil')->delete();
    }
}

<?php

use Illuminate\Database\Migrations\Migration;

class AddDpTrainingRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dpTrainingRole = \App\Role::create([
            'name'         => 'dp-training',
            'display_name' => '防災士培訓機構',
        ]);
        $permissions = \App\Permission::whereIn('name', [
            'DP-teachers-manage',
            'DP-students-manage',
            'DP-courses-manage',
            'DP-scores-manage',
            'DP-waivers-manage',
        ])->get();
        $dpTrainingRole->attachPermissions($permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        \App\Role::where('name', 'dp-training')->delete();
    }
}

<?php

use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddNewRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = new Role();
        $role->name = 'admin';
        $role->display_name = '管理員';
        $role->save();

        $role = new Role();
        $role->name = 'nfa';
        $role->display_name = '消防署';
        $role->save();

        $role = new Role();
        $role->name = 'committee';
        $role->display_name = '深耕評委';
        $role->save();

        $role = new Role();
        $role->name = 'localGov';
        $role->display_name = '縣市公所';
        $role->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        Role::where('name', 'admin')->delete();
        Role::where('name', 'nfa')->delete();
        Role::where('name', 'committee')->delete();
        Role::where('name', 'localGov')->delete();
    }
}

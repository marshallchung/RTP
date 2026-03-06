<?php

use Illuminate\Database\Migrations\Migration;

class AddDistrictsRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Role::create([
            'name'         => 'districts',
            'display_name' => '鄉鎮市區公所',
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
        \App\Role::where('name', 'districts')->delete();
    }
}

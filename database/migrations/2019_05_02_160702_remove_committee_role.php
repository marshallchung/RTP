<?php

use Illuminate\Database\Migrations\Migration;

class RemoveCommitteeRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        \App\Role::where('name', 'committee')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Role::create([
            'name'         => 'committee',
            'display_name' => '深耕評委',
        ]);
    }
}

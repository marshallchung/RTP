<?php

use App\User;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $firstChange = User::where('username', 'LIKE', 'CHW-09')->first();
        if ($firstChange) {
            $firstChange->name = '埔鹽鄉';
            $firstChange->update();
        }

        $secondChange = User::where('username', 'LIKE', 'CHW-23')->first();
        if ($secondChange) {
            $secondChange->name = '員林市';
            $secondChange->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $firstChange = User::where('username', 'LIKE', 'CHW-23')->first();
        if ($firstChange) {
            $firstChange->name = '鹽埔鄉';
            $firstChange->update();
        }

        $secondChange = User::where('username', 'LIKE', 'CHW-23')->first();
        if ($secondChange) {
            $secondChange->name = '員林鎮';
            $secondChange->update();
        }
    }
}

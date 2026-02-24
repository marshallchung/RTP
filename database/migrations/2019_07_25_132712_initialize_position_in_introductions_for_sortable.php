<?php

use Illuminate\Database\Migrations\Migration;

class InitializePositionInIntroductionsForSortable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $position = 1;
        \App\Introduction::orderBy('created_at', 'desc')
            ->chunk(10, function ($introductions) use (&$position) {
                foreach ($introductions as $introduction) {
                    $introduction->update(['position' => $position]);
                    $position++;
                }
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Introduction::query()->update(['position' => 0]);
    }
}

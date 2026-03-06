<?php

use Illuminate\Database\Migrations\Migration;

class InitializePositionForSortable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $position = 1;
        DB::table('news')->orderBy('created_at', 'desc')
            ->chunk(100, function ($rows) use (&$position) {
                foreach ($rows as $row) {
                    DB::table('news')->where('id', $row->id)->update(['position' => $position]);
                    $position++;
                }
            });
        $position = 1;
        DB::table('uploads')->orderBy('created_at', 'desc')
            ->chunk(100, function ($rows) use (&$position) {
                foreach ($rows as $row) {
                    DB::table('uploads')->where('id', $row->id)->update(['position' => $position]);
                    $position++;
                }
            });
        $position = 1;
        DB::table('dp_news')->orderBy('created_at', 'desc')
            ->chunk(100, function ($rows) use (&$position) {
                foreach ($rows as $row) {
                    DB::table('dp_news')->where('id', $row->id)->update(['position' => $position]);
                    $position++;
                }
            });
        $position = 1;
        DB::table('dc_news')->orderBy('created_at', 'desc')
            ->chunk(100, function ($rows) use (&$position) {
                foreach ($rows as $row) {
                    DB::table('dc_news')->where('id', $row->id)->update(['position' => $position]);
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
        DB::table('news')->update(['position' => 0]);
        DB::table('uploads')->update(['position' => 0]);
        DB::table('dp_news')->update(['position' => 0]);
        DB::table('dc_news')->update(['position' => 0]);
    }
}

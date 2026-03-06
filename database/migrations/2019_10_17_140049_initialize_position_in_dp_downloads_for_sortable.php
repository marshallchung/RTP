<?php

use Illuminate\Database\Migrations\Migration;

class InitializePositionInDpDownloadsForSortable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $position = 1;
        \App\DpDownload::orderBy('created_at', 'desc')
            ->chunk(10, function ($dpDownloads) use (&$position) {
                foreach ($dpDownloads as $dpDownload) {
                    $dpDownload->update(['position' => $position]);
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
        \App\DpDownload::query()->update(['position' => 0]);
    }
}

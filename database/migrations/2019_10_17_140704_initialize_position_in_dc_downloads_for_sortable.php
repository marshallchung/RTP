<?php

use Illuminate\Database\Migrations\Migration;

class InitializePositionInDcDownloadsForSortable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $position = 1;
        \App\DcDownload::orderBy('created_at', 'desc')
            ->chunk(10, function ($dcDownloads) use (&$position) {
                foreach ($dcDownloads as $dcDownload) {
                    $dcDownload->update(['position' => $position]);
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
        \App\DcDownload::query()->update(['position' => 0]);
    }
}

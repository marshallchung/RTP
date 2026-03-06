<?php

namespace App\Observers;

use App\Upload;
use Illuminate\Support\Facades\DB;

class UploadObserver
{
    public function creating(Upload $upload)
    {
        $upload->position = 1;
        Upload::query()->increment('position');
    }

    public function deleting(Upload $upload)
    {
        $upload->next()->decrement('position');
    }

    public function updating(Upload $upload)
    {
        if ($upload->isDirty('position')) {
            $originPosition = $upload->getOriginal('position');
            $newPosition = $upload->position;
            if ($newPosition > $originPosition) {
                DB::table('uploads')
                    ->whereBetween('position', [$originPosition + 1, $newPosition])
                    ->decrement('position');
            } elseif ($newPosition < $originPosition) {
                DB::table('uploads')
                    ->whereBetween('position', [$newPosition, $originPosition - 1])
                    ->increment('position');
            }
        }
    }
}

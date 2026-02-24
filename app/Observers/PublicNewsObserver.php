<?php

namespace App\Observers;

use App\PublicNews;

class PublicNewsObserver
{
    public function creating(PublicNews $publicNews)
    {
        $publicNews->position = 1;
        PublicNews::query()->increment('position');
    }

    public function deleting(PublicNews $publicNews)
    {
        $publicNews->next()->decrement('position');
    }

    public function updating(PublicNews $publicNews)
    {
        if ($publicNews->isDirty('position')) {
            $originPosition = $publicNews->getOriginal('position');
            $newPosition = $publicNews->position;
            if ($newPosition > $originPosition) {
                \DB::table('news')
                    ->whereBetween('position', [$originPosition + 1, $newPosition])
                    ->decrement('position');
            } elseif ($newPosition < $originPosition) {
                \DB::table('news')
                    ->whereBetween('position', [$newPosition, $originPosition - 1])
                    ->increment('position');
            }
        }
    }
}

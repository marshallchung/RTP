<?php

namespace App\Observers;

use App\News;
use Illuminate\Support\Facades\DB;

class NewsObserver
{
    public function creating(News $news)
    {
        $news->position = 1;
        News::query()->increment('position');
    }

    public function deleting(News $news)
    {
        $news->next()->decrement('position');
    }

    public function updating(News $news)
    {
        if ($news->isDirty('position')) {
            $originPosition = $news->getOriginal('position');
            $newPosition = $news->position;
            if ($newPosition > $originPosition) {
                DB::table('news')
                    ->whereBetween('position', [$originPosition + 1, $newPosition])
                    ->decrement('position');
            } elseif ($newPosition < $originPosition) {
                DB::table('news')
                    ->whereBetween('position', [$newPosition, $originPosition - 1])
                    ->increment('position');
            }
        }
    }
}

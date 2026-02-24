<?php

namespace App\Observers;

use App\Introduction;

class IntroductionObserver
{
    public function creating(Introduction $introduction)
    {
        $introduction->position = 1;
        Introduction::query()->increment('position');
    }

    public function deleting(Introduction $introduction)
    {
        $introduction->next()->decrement('position');
    }

    public function updating(Introduction $introduction)
    {
        if ($introduction->isDirty('position')) {
            $originPosition = $introduction->getOriginal('position');
            $newPosition = $introduction->position;
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

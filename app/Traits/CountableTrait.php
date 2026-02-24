<?php

namespace App\Traits;

use App\Counter;

trait CountableTrait
{
    public function counter(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Counter::class, 'countable');
    }

    public function getCounterCountAttribute(): int
    {
        if (!$this->counter) {
            return 0;
        }

        return $this->counter->count;
    }
}

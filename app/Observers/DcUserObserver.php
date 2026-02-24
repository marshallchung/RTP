<?php

namespace App\Observers;

use App\DcUser;

class DcUserObserver
{
    /**
     * Handle the DcUser "created" event.
     */
    public function created(DcUser $dcUser): void
    {
        //
    }

    /**
     * Handle the DcUser "updated" event.
     */
    public function updated(DcUser $dcUser): void
    {
        //
    }

    /**
     * Handle the DcUser "deleted" event.
     */
    public function deleted(DcUser $dcUser): void
    {
        //
    }

    /**
     * Handle the DcUser "restored" event.
     */
    public function restored(DcUser $dcUser): void
    {
        //
    }

    /**
     * Handle the DcUser "force deleted" event.
     */
    public function forceDeleted(DcUser $dcUser): void
    {
        //
    }
}

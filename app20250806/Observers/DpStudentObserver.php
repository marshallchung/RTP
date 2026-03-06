<?php

namespace App\Observers;

use App\DpStudent;
use App\Services\MailService;

class DpStudentObserver
{
    /**
     * Handle the dp student "created" event.
     *
     * @param \App\DpStudent $dpStudent
     * @return void
     */
    public function created(DpStudent $dpStudent)
    {
        $this->createEmailLog($dpStudent);
    }

    /**
     * Handle the dp student "updated" event.
     *
     * @param \App\DpStudent $dpStudent
     * @return void
     */
    public function updated(DpStudent $dpStudent)
    {
        if (request('resend_email')) {
            $this->createEmailLog($dpStudent);
        }
    }

    /**
     * @param DpStudent $dpStudent
     */
    private function createEmailLog(DpStudent $dpStudent)
    {
        /** @var MailService $mailService */
        $mailService = app(MailService::class);
        $mailService->addDpStudentMailToQueue($dpStudent);
    }
}

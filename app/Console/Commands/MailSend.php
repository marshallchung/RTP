<?php

namespace App\Console\Commands;

use App\Services\MailService;
use Illuminate\Console\Command;

class MailSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send {--count= : Amount of mails will be sent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mails in EmailLog';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param MailService $mailService
     */
    public function handle(MailService $mailService)
    {
        $count = $this->option('count');
        $this->info('Mail sending starting...');
        $mailService->sendMails($count);
        $this->info('Mail sending finished.');
    }
}

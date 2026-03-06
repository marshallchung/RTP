<?php

namespace App\Console\Commands;

use App\DpStudent;
use App\Services\MailService;
use Illuminate\Console\Command;

class MailAddAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:add-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All to DpStudents to EmailLog';

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
        $dpStudentCount = DpStudent::count();
        $this->info('Mail creating for {$dpStudentCount} DpStudents(s)...');
        $bar = $this->output->createProgressBar($dpStudentCount);
        DpStudent::query()->chunk(100, function ($dpStudents) use ($mailService, &$bar) {
            /** @var DpStudent $dpStudent */
            foreach ($dpStudents as $dpStudent) {
                $mailService->addDpStudentMailToQueue($dpStudent);
                $bar->advance();
            }
        });
        $this->info('');
        $bar->finish();
        $this->info('');
        $this->info("Mail creation of {$dpStudentCount} DpStudents(s) successful.");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MailService;
use App\User;
use Illuminate\Support\Facades\App;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily report to county';

    /**
     * Execute the console command.
     */
    public function handle(MailService $mailService)
    {
        $subject = (intval(date("Y")) - 1911) . date("年n月d日計畫管考項目狀態通知");
        $county_list = User::join('addresses', function ($join) {
            $join->on('addresses.county_id', '=', 'users.id')
                ->whereNotNull('addresses.email');
        })->where('users.type', 'county')
            ->orderBy('addresses.id', 'ASC')
            ->orderBy('addresses.position', 'ASC')
            ->get(['users.id', 'users.type', 'users.name', 'addresses.name AS contact', 'addresses.title', 'addresses.email']);
        if ($county_list) {
            $countyCount = count($county_list);
            $this->info("Mail creating for {$countyCount} County(s)...");
            $bar = $this->output->createProgressBar($countyCount);
            foreach ($county_list as $user) {
                $mailService->addDailyMailToQueue($user, $subject);
                $bar->advance();
                if (App::environment(['local', 'staging'])) {
                    break;
                }
            }
            $this->info('');
            $bar->finish();
            $this->info('');
            $this->info("Mail creation of {$countyCount} County(s) successful.");
        }
    }
}

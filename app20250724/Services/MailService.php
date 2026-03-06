<?php

namespace App\Services;

use App\DcUnit;
use App\DcUser;
use App\DpStudent;
use App\DpTeacher;
use App\EmailLog;
use App\Http\Controllers\Admin\Nfa\DashboardController;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * @param string $recipientEmail
     * @param string $subject
     * @param string $content
     * @param Model|null $recipient
     */
    public function addToQueue(string $recipientEmail, string $subject, string $content, ?Model $recipient = null)
    {
        EmailLog::create([
            'recipient_email' => $recipientEmail,
            'subject'         => $subject,
            'content'         => $content,
            'recipient_id'    => $recipient ? $recipient->id : null,
            'recipient_type'  => $recipient ? get_class($recipient) : null,
        ]);
    }

    /**
     * @param int|null $count
     */
    public function sendMails(?int $count = 300)
    {
        ini_set('max_execution_time', 1800);
        /** @var Collection|EmailLog[] $emailLogs */
        $emailLogs = EmailLog::whereNull('sent_at')
            ->orderBy('failed_time')
            ->orderBy('created_at')->take($count)->get();
        foreach ($emailLogs as $emailLog) {
            try {
                Mail::html($emailLog->content, function ($message) use ($emailLog) {
                    $message->from('pdmcb@nfa.gov.tw', 'PDMCB')
                        ->to($emailLog->recipient_email)
                        ->subject($emailLog->subject);
                });
                $emailLog->sent_at = now();
                $emailLog->save();
            } catch (\Exception $exception) {
                \Log::error($exception);
                $emailLog->failed_time++;
                $emailLog->save();
            }
            sleep(1);
        }
    }

    public function addDcUserMailToQueue(DcUnit $dcUser, $formUrl)
    {
        try {
            $subject = '會員重設密碼認證信';
            $content = view('admin.email.dc-user-reset-password', compact('formUrl', 'dcUser'))->render();
            $this->addToQueue($dcUser->email, $subject, $content, $dcUser);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTrace());
        }
    }

    public function addDpTeacherMailToQueue(DpTeacher $dpTeacher, $formUrl)
    {
        try {
            $subject = '【內政部消防署】請協助防災士師資聯絡資料更新';
            $content = view('admin.email.dp-teacher-update-profile-notification', compact('formUrl', 'dpTeacher'))->render();
            $this->addToQueue($dpTeacher->email, $subject, $content, $dpTeacher);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTrace());
        }
    }

    public function addDpStudentMailToQueue(DpStudent $dpStudent)
    {
        try {
            $subject = '防災士帳號系統通知mail';
            $content = view('admin.email.user-create-notification', compact('dpStudent'))->render();
            $this->addToQueue($dpStudent->email, $subject, $content, $dpStudent);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTrace());
        }
    }

    public function addDailyMailToQueue(User $user, $subject)
    {
        try {
            $data = DashboardController::reportData($user);
            $data['user'] = $user;
            $content = view('admin.email.daily-report', $data)->render();
            if (App::environment(['local', 'staging'])) {
                $this->addToQueue('albert@spolit.com.tw', $subject, $content);
            } else {
                $this->addToQueue($user->email, $subject, $content);
            }
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTrace());
        }
    }
}

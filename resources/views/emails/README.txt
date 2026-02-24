# php artisan tinker
### Ubuntu下，中文標題解決辦法，先下指令
setlocale(LC_ALL,'en_US.UTF-8');


###寄信的指令
Mail::send('admin.email.dp-student-notification-20221114', [], function ($message) { $message->to('emmanuelbarturen@gmail.com')->subject('【內政部消防署】問卷調查aa'); });

###寄給防災士
DpStudent::whereNotNull('email')->where('id', '>=', 11700)->where('id', '<', 11700)->each(function($s){Mail::send('admin.email.dp-student-notification-20221114', [], function($m)use($s){$m->to($s->email)->subject('【內政部消防署】問卷調查');});})
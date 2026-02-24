<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\EmailLog
 *
 * @property int $id
 * @property string|null $recipient_email 收件者信箱
 * @property string|null $subject 標題
 * @property string|null $content 內文
 * @property \Illuminate\Support\Carbon|null $sent_at 寄信時間
 * @property int $failed_time 失敗次數
 * @property int|null $recipient_id 收件者ID
 * @property string|null $recipient_type 收件者類型
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read Model|\Eloquent $recipient
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereFailedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereRecipientEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereRecipientType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailLog extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'recipient_email',
        'subject',
        'content',
        'sent_at',
        'failed_time',
        'recipient_id',
        'recipient_type',
    ];

    protected $dates = [
        'sent_at',
    ];

    public function recipient()
    {
        return $this->morphTo();
    }
}

<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * App\CentralReport
 *
 * @property int $id
 * @property int $user_id
 * @property int $topic_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|CentralReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CentralReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CentralReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|CentralReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CentralReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CentralReport whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CentralReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CentralReport whereUserId($value)
 * @mixin \Eloquent
 */
class CentralReport extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'topic_id',
    ];

    static public function getReportFiles($topicId, $search = null, $county = null)
    {
        $centralReportQuery = self::join('topics', function ($join) use ($topicId) {
            $join->on('topics.id', '=', 'central_reports.topic_id');
        })
            ->join('files', function ($join) use ($search) {
                $join->on('files.post_id', '=', 'central_reports.id')
                    //->where('files.opendata', '=', 1)
                    ->where('files.post_type', '=', self::class);
                if ($search) {
                    $join->where('files.name', 'LIKE', "%{$search}%");
                }
            })
            ->leftJoin('users', 'users.id', '=', 'central_reports.user_id')
            ->orderBy('files.created_at', 'desc');
        if ($county) {
            $centralReportQuery->where('users.county_id', '=', $county);
        }
        return $centralReportQuery;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topics()
    {
        $user = Auth::user();
        $level = $user->details->level;
        $type = $user->details->type;

        $topics = Topic::with('centralReports.files')
            ->where('levels', 'LIKE', "%{$level}%")
            ->where('type', $type)->get();

        return $this->organizeTopics($topics);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}

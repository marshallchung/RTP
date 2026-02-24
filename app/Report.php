<?php

namespace App;

use App\Traits\LogModelEvent;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Report
 *
 * @property int $id
 * @property int $user_id
 * @property int $topic_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\Topic|null $topic
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUserId($value)
 * @mixin \Eloquent
 */
class Report extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'topic_id',
    ];

    static public function getReportFiles($topicId, $search = null, $county = null)
    {
        $reportQuery = self::select(DB::raw('users.name AS area,parent_county.name AS county,GROUP_CONCAT(DISTINCT reports.id) AS report_list'))
            ->join('users', function ($join) use ($county) {
                $join->on('users.id', '=', 'reports.user_id');
                if ($county) {
                    $join->where('users.county_id', '=', $county);
                }
            })
            ->join('files', function ($join) use ($search) {
                $join->on('files.post_id', '=', 'reports.id')
                    ->where('files.opendata', '=', 1)
                    ->where('files.post_type', '=', self::class);
                if ($search) {
                    $join->where('files.name', 'LIKE', "%$search%");
                }
            })
            ->leftJoin('users AS parent_county', 'parent_county.id', '=', 'users.county_id')
            ->where('reports.topic_id', '=', $topicId)
            ->groupBy(['reports.created_at', 'reports.user_id'])
            ->orderBy('reports.created_at', 'desc');
        return $reportQuery;
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

        $topics = Topic::with('reports.files')->where('levels', 'LIKE', "%{$level}%")->where('type', $type)->get();

        return $this->organizeTopics($topics);
    }

    /**
     * @param Collection|Topic[] $topics
     * @return array
     */
    private function organizeTopics($topics)
    {
        $categories = [
            ['title' => '管考情形', 'items' => []],
            ['title' => '地區災害潛勢特性評估', 'items' => []],
            ['title' => '災害防救體系', 'items' => []],
            ['title' => '培植災害防救能力', 'items' => []],
            ['title' => '災時緊急應變處置機制', 'items' => []],
            ['title' => '災害防救資源', 'items' => []],
        ];

        $topics->each(function ($topic) use (&$categories) {
            array_push($categories[$topic->category]['items'], $topic);
        });

        return array_filter($categories, function ($category) {
            return !empty($category['items']);
        });
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}

<?php

namespace App;

use App\Traits\LogModelEvent;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\SeasonalReport
 *
 * @property int $id
 * @property int $user_id
 * @property int $topic_id
 * @property int $year
 * @property int $season
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport whereSeason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeasonalReport whereYear($value)
 * @mixin \Eloquent
 */
class SeasonalReport extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'topic_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topics()
    {
        $user = Auth::user();
        $level = $user->details->level;
        $type = $user->details->type;

        $topics = Topic::with('seasonalReports.files')
            ->where('levels', 'LIKE', "%{$level}%")
            ->where('type', $type)->get();

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

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
